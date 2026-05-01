<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Event', Event::count())
                ->description('Semua event terdaftar')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
                
            Stat::make('Total Order', Order::count())
                ->description('Total transaksi masuk')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),
                
            Stat::make('Total Omzet (Lunas)', 'Rp ' . number_format(Order::where('status', 'paid')->sum('total'), 0, ',', '.'))
                ->description('Pendapatan kotor dari order lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
