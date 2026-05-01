<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InfoImport extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Panduan Urutan Import Data';

    protected function getStats(): array
    {
        return [
            Stat::make('Langkah 1: Fondasi Utama', 'Import Venue')
                ->description('Mencatat ID lokasi sebelum event dibuat.')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),


            Stat::make('Langkah 2: Data Relasi', 'Import Event')
                ->description('Sistem akan mencocokkan event dengan ID Venue.')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),


            Stat::make('Langkah 3: Bebas', 'Import Voucher')
                ->description('Bisa di-import kapan saja karena tidak terikat relasi lokasi.')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('warning'),
        ];
    }
}