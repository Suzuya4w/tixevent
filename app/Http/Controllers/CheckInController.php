<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendee;
use Carbon\Carbon;

class CheckInController extends Controller
{

    public function verify(Request $request)
    {
        $request->validate(['kode_tiket' => 'required']);
        $kode = $request->kode_tiket;

        $tiket = Attendee::with('orderDetail.tiket.event')->where('kode_tiket', $kode)->first();

        if (!$tiket) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Tiket tidak ditemukan atau Palsu!'
            ]);
        }

        $statusOrder = $tiket->orderDetail->order->status->value ?? $tiket->orderDetail->order->status;
        if ($statusOrder !== 'paid') {
            return response()->json([
                'status' => 'error', 
                'message' => 'AKSES DITOLAK! Pembayaran tiket ini belum diverifikasi / belum Lunas.'
            ]);
        }

        if ($tiket->status_checkin === 'sudah') {
            $waktu = Carbon::parse($tiket->waktu_checkin)->format('d M Y H:i:s');
            return response()->json([
                'status' => 'warning', 
                'message' => 'Tiket SUDAH DIGUNAKAN pada: ' . $waktu
            ]);
        }

        $event = $tiket->orderDetail->tiket->event;
        $sekarang = Carbon::now();

        $waktuMulaiStr = $event->waktu ? $event->waktu : '00:00:00';
        $waktuAcara = Carbon::parse($event->tanggal . ' ' . $waktuMulaiStr);

        $openGate = $waktuAcara->copy()->subHours(3);

        if ($event->waktu_selesai) {
            $closeGate = Carbon::parse($event->tanggal . ' ' . $event->waktu_selesai);
        } else {
            $closeGate = $waktuAcara->copy()->addHours(12);
        }


        if ($sekarang->lessThan($openGate)) {
            return response()->json([
                'status' => 'warning', 
                'message' => 'GATE BELUM DIBUKA! Open Gate baru dimulai pada: ' . $openGate->translatedFormat('d M Y - H:i') . ' WIB.'
            ]);
        }


        if ($sekarang->greaterThan($closeGate)) {
            return response()->json([
                'status' => 'error', 
                'message' => 'KEDALUWARSA! Acara sudah berakhir pada: ' . $closeGate->translatedFormat('d M Y - H:i') . ' WIB.'
            ]);
        }

        $tiket->update([
            'status_checkin' => 'sudah',
            'waktu_checkin' => now(),
        ]);

        return response()->json([
            'status' => 'success', 
            'message' => 'Check-in Berhasil! Akses Diberikan.',
            'data' => [
                'event' => $event->nama_event ?? 'Event',
                'jenis' => $tiket->orderDetail->tiket->nama_tiket ?? 'Reguler',
            ]
        ]);
    }
}