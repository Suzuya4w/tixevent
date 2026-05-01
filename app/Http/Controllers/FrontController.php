<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Inertia\Inertia;



class FrontController extends Controller
{

    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();

        $query = \App\Models\Event::with(['venue', 'category', 'tikets'])->latest();

        if ($request->has('kategori') && $request->kategori != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        $events = $query->take(6)->get();

        $vouchers = \App\Models\Voucher::where('status', 'aktif')
            ->where('kuota', '>', 0)
            ->where('tanggal_berakhir', '>', now())
            ->latest()
            ->take(3)
            ->get();

        $stats = [
            'total_events' => \App\Models\Event::count(),
            'total_tiket_terjual' => \App\Models\Attendee::whereHas('orderDetail.order', fn($q) => $q->where('status', 'paid'))->count(),
            'total_user' => \App\Models\User::count(),
        ];

        return Inertia::render('Home', [
            'events' => $events,
            'categories' => $categories,
            'filters' => $request->only('kategori'),
            'vouchers' => $vouchers,
            'stats' => $stats,
        ]);
    }

   
    public function allEvents(Request $request)
    {
        $categories = \App\Models\Category::all();

        $query = \App\Models\Event::with(['venue', 'category', 'tikets'])->latest();

        if ($request->has('kategori') && $request->kategori != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama_event', 'like', '%' . $request->search . '%')
                  ->orWhereHas('venue', function($v) use ($request) {
                      $v->where('nama_venue', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('tanggal', $request->date);
        }

        $events = $query->paginate(12)->withQueryString();

        return Inertia::render('AllEvents', [
            'events' => $events,
            'categories' => $categories,
            'filters' => $request->only('kategori', 'search', 'date')
        ]);
    }


    public function detail($id)
    {
        $event = Event::with(['venue', 'tikets', 'category'])->findOrFail($id);

        $userId = \Illuminate\Support\Facades\Auth::id();

        $tikets = $event->tikets->map(function ($tiket) use ($userId) {

            $terjual = \App\Models\Attendee::whereHas('orderDetail', function($query) use ($tiket) {
                $query->where('tiket_id', $tiket->id)
                      ->whereHas('order', function($qOrder) {
                          $qOrder->where('status', 'paid');
                      });
            })->count();

            $sudahDibeliUser = 0;
            if ($userId) {
                $sudahDibeliUser = \App\Models\OrderDetail::where('tiket_id', $tiket->id)
                    ->whereHas('order', function ($query) use ($userId) {
                        $query->where('user_id', $userId)
                              ->whereIn('status', ['pending', 'waiting', 'waiting_confirmation', 'paid']);
                    })
                    ->sum('qty');
            }

            return [
                'id' => $tiket->id,
                'nama_tiket' => $tiket->nama_tiket,
                'harga' => $tiket->harga,
                'kuota' => $tiket->kuota,
                'terjual' => $terjual,
                'sisa' => $tiket->kuota - $terjual,
                'max_beli' => $tiket->max_beli,
                'sudah_dibeli' => $sudahDibeliUser,
            ];
        });

        $voucherTerpakai = [];
        if ($userId) {
            $voucherTerpakai = \App\Models\Order::where('user_id', $userId)
                ->whereNotNull('voucher_id')
                ->whereIn('status', ['pending', 'waiting', 'waiting_confirmation', 'paid'])
                ->pluck('voucher_id')
                ->toArray();
        }

        $vouchers = \App\Models\Voucher::where('status', 'aktif')
            ->where('kuota', '>', 0)
            ->whereNotIn('id', $voucherTerpakai)
            ->get();

        return Inertia::render('EventDetail', [
            'event' => $event,
            'tikets' => $tikets,
            'vouchers' => $vouchers,
        ]);
    }

    public function tiketSaya()
    {

        $orders = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())
                                   ->whereIn('status', ['paid', 'waiting_confirmation'])
                                   ->get();

        $orderIds = $orders->map(fn($o) => $o->id_order ?? $o->id);

        $orderDetails = \App\Models\OrderDetail::whereIn('order_id', $orderIds)->get();
        $detailIds = $orderDetails->map(fn($d) => $d->id_detail ?? $d->id);

        $tikets = \App\Models\Attendee::whereIn('order_detail_id', $detailIds)->get();

        foreach($tikets as $t) {

            $detail = \App\Models\OrderDetail::find($t->order_detail_id);
            $masterTiket = \App\Models\Tiket::find($detail->tiket_id); 
            $event = \App\Models\Event::find($masterTiket->event_id);
            $venue = \App\Models\Venue::find($event->venue_id);

            $order = $orders->firstWhere('id', $detail->order_id);

            $t->nama_event = $event->nama_event ?? 'Event Tidak Diketahui';
            $t->tanggal = $event->tanggal ?? now();
            $t->waktu = $event->waktu ?? null;
            $t->waktu_selesai = $event->waktu_selesai ?? null;
            $t->nama_venue = $venue->nama_venue ?? 'Lokasi Belum Ditentukan';
            $t->jenis_tiket = $masterTiket->nama_tiket ?? 'Reguler';
            $t->status_order = $order->status?->value ?? 'pending';
            $t->total_harga = $order->total ?? 0;
            $t->tanggal_beli = $order->created_at ? $order->created_at->format('Y-m-d H:i') : null;
        }
        
        return Inertia::render('MyTickets', [
            'tikets' => $tikets
        ]);
    }

  
    public function downloadPdf(\Illuminate\Http\Request $request)
    {
        $orders = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())
                                   ->whereIn('status', ['paid', 'waiting_confirmation'])
                                   ->get();
        
        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada tiket yang bisa diunduh.');
        }

        $orderIds = $orders->map(fn($o) => $o->id_order ?? $o->id);
        $orderDetails = \App\Models\OrderDetail::whereIn('order_id', $orderIds)->get();
        $detailIds = $orderDetails->map(fn($d) => $d->id_detail ?? $d->id);
        $tikets = \App\Models\Attendee::whereIn('order_detail_id', $detailIds)->get();

        $filteredTikets = collect();

        foreach($tikets as $t) {
            $detail = \App\Models\OrderDetail::find($t->order_detail_id);
            $masterTiket = \App\Models\Tiket::find($detail->tiket_id); 
            $event = \App\Models\Event::find($masterTiket->event_id);
            $venue = \App\Models\Venue::find($event->venue_id);
            $order = $orders->firstWhere('id', $detail->order_id);

            $namaEvent = $event->nama_event ?? 'Event Tidak Diketahui';

            if ($request->has('event') && $request->event != $namaEvent) {
                continue;
            }

            $t->nama_event = $namaEvent;
            $t->tanggal = $event->tanggal ?? date('Y-m-d');
            $t->waktu = $event->waktu ?? null;
            $t->waktu_selesai = $event->waktu_selesai ?? null;
            $t->nama_venue = $venue->nama_venue ?? 'Lokasi Belum Ditentukan';
            $t->jenis_tiket = $masterTiket->nama_tiket ?? 'Reguler';
            $t->status_order = $order->status?->value ?? 'pending';
            $t->total_harga = $order->total ?? 0;
            $t->tanggal_beli = $order->created_at ? $order->created_at->format('Y-m-d H:i') : null;
            

            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($t->kode_tiket);
            $t->qr_code_base64 = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

            $filteredTikets->push($t);
        }

        if ($filteredTikets->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada tiket yang cocok untuk diunduh.');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.tickets', ['tikets' => $filteredTikets]);
        
        $filename = $request->has('event') ? 'Tiket_' . str_replace(' ', '_', $request->event) . '.pdf' : 'Semua_Tiket_TIXEVENT.pdf';
        
        return $pdf->download($filename);
    }
}