<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

#[Signature('app:expire-pending-orders')]
#[Description('Membatalkan order yang pending lebih dari 15 menit dan mengembalikan kuota tiket')]
class ExpirePendingOrders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Batas Waktu Transaksi 15 Menit
        $expiredOrders = Order::with('details.tiket')
            ->where('status', \App\Enums\OrderStatus::Pending)
            ->where('created_at', '<=', now()->subMinutes(15))
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Tidak ada order pending yang expired.');
            return;
        }

        // Jika Waktu Berakhir
        foreach ($expiredOrders as $order) {
            DB::beginTransaction();
            try {
                // Kembalikan kuota tiket
                foreach ($order->details as $detail) {
                    if ($detail->tiket) {
                        $detail->tiket->increment('kuota', $detail->qty);
                    }
                }

                // Kembalikan kuota voucher jika ada
                if ($order->voucher_id) {
                    $voucher = Voucher::find($order->voucher_id);
                    if ($voucher) {
                        $voucher->increment('kuota');
                    }
                }

                // Ubah status order menjadi dibatalkan
                $order->status = \App\Enums\OrderStatus::Cancel;
                $order->save();

                DB::commit();
                $this->info("Order {$order->id} telah expired dan dibatalkan.");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal membatalkan order {$order->id}: " . $e->getMessage());
            }
        }
    }
}
