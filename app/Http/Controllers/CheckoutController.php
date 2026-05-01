<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Voucher;
use App\Models\Attendee;
use App\Models\User;
use Filament\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Illuminate\Notifications\Action;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{

    public function process(Request $request)
    {
        $tiket = Tiket::findOrFail($request->id_tiket);

        $request->validate([
            'id_tiket' => 'required|exists:tikets,id',
            'qty' => [
                'required', 
                'integer', 
                'min:1', 
                'max:' . $tiket->max_beli
            ],
            'voucher_id' => 'nullable|exists:vouchers,id'
        ], [
            'qty.max' => "Batas maksimal pembelian adalah {$tiket->max_beli} tiket per transaksi."
        ]);

        $tiketSudahDibeli = \App\Models\OrderDetail::where('tiket_id', $tiket->id)
            ->whereHas('order', function ($query) {
                $query->where('user_id', Auth::id())

                      ->whereIn('status', ['pending', 'waiting', 'waiting_confirmation', 'paid']);
            })
            ->sum('qty');


        if (($tiketSudahDibeli + $request->qty) > $tiket->max_beli) {
            $sisaBolehBeli = $tiket->max_beli - $tiketSudahDibeli;
            
            $pesan = $sisaBolehBeli > 0 
                ? "Anda sudah memiliki {$tiketSudahDibeli} tiket ini. Sisa kuota untuk akun anda tinggal {$sisaBolehBeli} tiket lagi."
                : "Akses ditolak! Akun anda sudah mencapai batas maksimal pembelian tiket ini.";
                
            return back()->with('error', $pesan);
        }


        $recentOrder = Order::where('user_id', Auth::id())
            ->where('status', \App\Enums\OrderStatus::Pending)
            ->where('created_at', '>=', now()->subMinutes(1))
            ->whereHas('details', function ($query) use ($request) {
                $query->where('tiket_id', $request->id_tiket)
                      ->where('qty', $request->qty);
            })
            ->first();

        if ($recentOrder) {

            return redirect()->route('checkout.payment', $recentOrder->id)
                ->with('success', 'Pesanan tiket sudah berhasil dibuat, silakan lanjutkan pembayaran.');
        }


        $lock = \Illuminate\Support\Facades\Cache::lock('checkout_tiket_' . $request->id_tiket, 10);

        try {

            $lock->block(5);

            DB::beginTransaction();

            $tiket = Tiket::findOrFail($request->id_tiket);

            if ($request->qty > $tiket->kuota) {
                DB::rollBack();
                return back()->with('error', 'Maaf, kuota tiket tidak mencukupi!');
            }

            $subtotal = $tiket->harga * $request->qty;
            $discount = 0;

if ($request->voucher_id) {
                $pernahPakai = Order::where('user_id', Auth::id())
                    ->where('voucher_id', $request->voucher_id)
                    ->whereIn('status', ['pending', 'waiting', 'waiting_confirmation', 'paid'])
                    ->exists();

                if ($pernahPakai) {
                    DB::rollBack();
                    return back()->with('error', 'Akses ditolak! Kamu sudah pernah menggunakan kode promo ini sebelumnya.');
                }

                $voucher = Voucher::lockForUpdate()->find($request->voucher_id);
                if ($voucher && $voucher->status === 'aktif' && $voucher->kuota > 0) {


                    if ($subtotal <= $voucher->potongan) {
                        DB::rollBack();
                        return back()->with('error', 'Maaf, voucher ini tidak dapat digunakan karena nilai potongan melebihi atau sama dengan total belanja Anda.');
                    }

                    $discount = $voucher->potongan;
                    $voucher->decrement('kuota');
                } else {
                    DB::rollBack();
                    return back()->with('error', 'Maaf, voucher tidak valid atau kuota sudah habis.');
                }
            }

            $total = max(0, $subtotal - $discount);

            $order = Order::create([
                'user_id' => Auth::id(),
                'tanggal_order' => now(),
                'total' => $total,
                'status' => \App\Enums\OrderStatus::Pending,
                'voucher_id' => $request->voucher_id,
            ]);

            OrderDetail::create([
                'order_id' => $order->id,
                'tiket_id' => $tiket->id,
                'qty' => $request->qty,
                'subtotal' => $subtotal,
            ]);

            $tiket->decrement('kuota', $request->qty);

            DB::commit();

            return redirect()->route('checkout.payment', $order->id)->with('success', 'Berhasil membuat pesanan! Silakan lakukan pembayaran.');

        } catch (\Illuminate\Contracts\Cache\LockTimeoutException $e) {

            return back()->with('error', 'Sistem sedang sibuk memproses tiket ini, silakan coba lagi beberapa saat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        } finally {
            $lock?->release(); 
        }
    }

    // Fungsi Payment
    public function payment($id)
    {
        $order = Order::with(['details.tiket.event.venue', 'voucher'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== \App\Enums\OrderStatus::Pending && $order->status !== \App\Enums\OrderStatus::Waiting) {
            return redirect()->route('tiket.saya')->with('error', 'Pesanan ini sudah tidak valid untuk dibayar.');
        }

        return \Inertia\Inertia::render('Payment', ['order' => $order]);
    }


    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = Order::where('id', $id)->firstOrFail();

   
        if ($order->status->value !== 'pending') {
            return redirect()->route('tiket.saya')->with('error', 'Pesanan ini sudah diproses.');
        }

        DB::beginTransaction();

        try {

            if ($request->hasFile('bukti_transfer')) {
                $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
                $order->bukti_transfer = $path;
            }


            $order->status = \App\Enums\OrderStatus::Waiting;
            $order->save();


            $orderDetails = OrderDetail::where('order_id', $order->id_order ?? $order->id)->get();

            $secretKey = env('TIXEVENT_SECRET_KEY', 'default_secret_fallback');

            foreach ($orderDetails as $detail) {
              
                for ($i = 0; $i < $detail->qty; $i++) {
                    $payload = 'TIX-' . strtoupper(Str::random(6));
                    $signature = hash_hmac('sha256', $payload, $secretKey);
                    $kodeTiket = $payload . '.' . $signature;

                    Attendee::create([
                        'order_detail_id' => $detail->id,
                        'kode_tiket' => $kodeTiket,
                        'status_checkin' => 'belum',

                    ]);
                }
            }


            DB::commit();

        
            $admins = User::where('role', 'admin')->get();

            if ($admins->isNotEmpty()) {
                Notification::make()
                    ->title('Ada Pembayaran Baru! 💰')
                    ->body("Pesanan #{$order->id} sebesar Rp " . number_format($order->total, 0, ',', '.') . " menunggu verifikasi Anda.")
                    ->success()

                    ->actions([
                        Notification::make('Lihat Pesanan')
                            ->url('/admin/orders/' . $order->id . '/edit')
                            ->button()
                    ])
                    ->sendToDatabase($admins);
            }

            return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu konfirmasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }


    public function cancelPayment($id)
    {
        $order = Order::with('details.tiket')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status->value !== 'pending') {
            return redirect()->route('tiket.saya')->with('error', 'Pesanan ini tidak bisa dibatalkan.');
        }

        DB::beginTransaction();
        try {

            foreach ($order->details as $detail) {
                $detail->tiket->increment('kuota', $detail->qty);
            }


            if ($order->voucher_id) {
                $voucher = Voucher::find($order->voucher_id);
                if ($voucher) {
                    $voucher->increment('kuota');
                }
            }


            $order->status = \App\Enums\OrderStatus::Cancel;
            $order->save();

            DB::commit();
            return redirect()->route('event.detail', $order->details->first()->tiket->event_id)
                ->with('success', 'Pesanan dibatalkan. Kuota tiket telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage() . ' di baris ' . $e->getLine());
        }
    }
}