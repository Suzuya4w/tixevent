<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Tiket;
use Illuminate\Support\Carbon;

class TiketStats extends BaseWidget
{
    protected ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $query = Tiket::query();


        $tiketTerjual = (clone $query)->withSum(['orderDetails as total_terjual' => function ($q) {
            $q->whereHas('order', fn ($q2) => $q2->where('status', 'paid'));
        }], 'qty')->get()->sum('total_terjual');


        $terjualHariIni = (clone $query)->withSum(['orderDetails as total_terjual' => function ($q) {
            $q->whereHas('order', fn ($q2) => $q2->where('status', 'paid')->whereDate('tanggal_order', today()));
        }], 'qty')->get()->sum('total_terjual');


        $terjualMingguIni = (clone $query)->withSum(['orderDetails as total_terjual' => function ($q) {
            $q->whereHas('order', fn ($q2) => $q2->where('status', 'paid')->whereBetween('tanggal_order', [now()->startOfWeek(), now()->endOfWeek()]));
        }], 'qty')->get()->sum('total_terjual');


        $chartData = [];

        $days = collect(range(6, 0))->map(fn (int $ago) => Carbon::today()->subDays($ago));
        
        foreach ($days as $day) {
            $salesPerDay = (clone $query)->withSum(['orderDetails as total_terjual' => function ($q) use ($day) {
                $q->whereHas('order', fn ($q2) => $q2->where('status', 'paid')->whereDate('tanggal_order', $day));
            }], 'qty')->get()->sum('total_terjual');
            
            $chartData[] = (int) $salesPerDay;
        }

        return [
            Stat::make('Total Varian Tiket', $query->count())
                ->description('Jenis tiket aktif di sistem')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),

            Stat::make('Tiket Laku (Total)', number_format((float) $tiketTerjual, 0, ',', '.'))
                ->description('Semua pesanan lunas')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([10, 22, 15, 30, 45, 20, 50]),

            Stat::make('Tiket Laku (Hari Ini)', number_format((float) $terjualHariIni, 0, ',', '.'))
                ->description($terjualHariIni > 0 ? 'Ada penjualan hari ini' : 'Belum ada penjualan')
                ->descriptionIcon($terjualHariIni > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-minus')
                ->color($terjualHariIni > 0 ? 'success' : 'gray')

                ->chart($chartData), 

            Stat::make('Tiket Laku (Minggu Ini)', number_format((float) $terjualMingguIni, 0, ',', '.'))
                ->description('Periode Senin - Minggu')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->chart($chartData),
        ];
    }
}