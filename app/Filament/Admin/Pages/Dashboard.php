<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\ActionGroup;
use Filament\Actions\ImportAction;
use App\Livewire\InfoImport;
use App\Livewire\OmsetBulananChart;
use App\Livewire\StatistikPenjualanWidget;

class Dashboard extends BaseDashboard
{

    protected static ?string $title = 'Dashboard';

    // IMPORT
    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                ImportAction::make('importVenues')
                    ->label('Import Data Venue (CSV)')
                    ->icon('heroicon-m-map-pin')
                    ->importer(\App\Filament\Imports\VenueImporter::class), 
                    
                ImportAction::make('importEvents')
                    ->label('Import Data Event (CSV)')
                    ->icon('heroicon-m-calendar')
                    ->importer(\App\Filament\Imports\EventImporter::class),
                    
                ImportAction::make('importVouchers')
                    ->label('Import Data Voucher (CSV)')
                    ->icon('heroicon-m-ticket')
                    ->importer(\App\Filament\Imports\VoucherImporter::class),
            ])
            ->label('Import Data')
            ->icon('heroicon-m-arrow-down-tray')
            ->button()
            ->color('primary'),
        ];
    }

    public function getWidgets(): array
    {
        return [
            InfoImport::class,
            \App\Filament\Admin\Widgets\StatsOverview::class,
            StatistikPenjualanWidget::class,
            OmsetBulananChart::class,
            \App\Filament\Admin\Widgets\LatestOrders::class,
            \App\Filament\Admin\Widgets\LatestEvents::class,
            \App\Filament\Admin\Widgets\LatestVouchers::class,
            \App\Filament\Admin\Widgets\LatestUsers::class,
        ];
    }

}