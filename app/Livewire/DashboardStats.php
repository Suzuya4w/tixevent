<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\Attendee;
use App\Models\Tiket;


class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        $tiketTerjual = Attendee::whereHas('orderDetail.order', function ($query) {
            $query->where('status', 'paid');
        })->count();

        $penontonMasuk = Attendee::where('status_checkin', 'sudah')->count();


        $pendapatan = Order::where('status', 'paid')->sum('total');


        $totalKuotaDisediakan = Tiket::sum('kuota'); 

        $sisaTiket = $totalKuotaDisediakan - $tiketTerjual;


        return [
            Stat::make('Tiket Terjual', $tiketTerjual . ' Lembar')
                ->description('Dari total ' . $totalKuotaDisediakan . ' kuota tiket')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('primary'),


            Stat::make('Sisa Tiket Tersedia', $sisaTiket . ' Lembar')
                ->description($sisaTiket < 20 ? 'Stok menipis!' : 'Stok masih aman')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color($sisaTiket < 20 ? 'danger' : 'success'), 

            Stat::make('Penonton Masuk (Check-In)', $penontonMasuk . ' Orang')
                ->description('Sudah berada di dalam venue')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($pendapatan, 0, ',', '.'))
                ->description('Dari pesanan lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}