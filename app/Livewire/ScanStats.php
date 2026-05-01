<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ScanStats extends BaseWidget
{
    protected ?string $pollingInterval = '5s'; 

    protected function getStats(): array
    {

        $totalCheckinHariIni = Attendee::where('status_checkin', 'sudah')
                                ->whereDate('waktu_checkin', today())
                                ->count();
                                

        $totalBelumHadir = Attendee::where('status_checkin', 'belum')->count();

        return [
            Stat::make('Total Masuk (Hari Ini)', $totalCheckinHariIni . ' Orang')
                ->description('Telah berhasil di-scan')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([2, 5, 10, 8, 15, 20]),

            Stat::make('Sisa Antrean (Global)', $totalBelumHadir . ' Tiket')
                ->description('Belum melakukan check-in')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('warning'),
        ];
    }
}