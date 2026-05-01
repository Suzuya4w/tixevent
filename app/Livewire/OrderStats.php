<?php

namespace App\Livewire;

use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrderStats extends BaseWidget
{
    use InteractsWithPageTable;

    public array $tableColumnSearches = []; 

    protected ?string $pollingInterval = '15s';

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getStats(): array
    {


    $query = $this->getPageTableQuery();


    $orderData = Trend::query($query)
        ->between(
            start: now()->subDays(6),
            end: now(),
        )
        ->perDay()
        ->count();

        return [
            Stat::make('Total Pesanan', $this->getPageTableQuery()->count())
                ->description('Semua pesanan masuk')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart(
                    $orderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('Menunggu Verifikasi', $this->getPageTableQuery()->where('status', 'waiting_confirmation')->count())
                ->description('Perlu dicek segera')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([2, 5, 3, 7, 4, 8, 10]),

            Stat::make('Total Pendapatan', 'Rp ' . number_format((float) $this->getPageTableQuery()->where('status', 'paid')->sum('total'), 0, ',', '.'))
                ->description('Dari pesanan lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 8]),
        ];
    }
}