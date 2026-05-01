<?php

namespace App\Filament\Resources\OrderDetails\Pages;

use App\Filament\Resources\OrderDetails\OrderDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

use Filament\Actions\ExportAction;

class ListOrderDetails extends ListRecords
{
    protected static string $resource = OrderDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(\App\Filament\Exports\OrderDetailExporter::class)
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->label('Export Detail Pesanan'),
            // CreateAction::make(),
        ];
    }
}
