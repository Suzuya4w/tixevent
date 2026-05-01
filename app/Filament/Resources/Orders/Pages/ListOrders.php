<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Livewire\OrderStats;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\ExportAction;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(\App\Filament\Exports\OrderExporter::class)
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->label('Export Semua Pesanan'),
            // CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Pesanan'),
            
            'pending' => Tab::make('Menunggu Pembayaran')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),
                
            'waiting' => Tab::make('Menunggu Verifikasi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'waiting_confirmation')),
                
            'paid' => Tab::make('Lunas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'paid')),
                
            'cancel' => Tab::make('Dibatalkan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancel')),
        ];
    }
}
