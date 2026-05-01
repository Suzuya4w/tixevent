<?php

namespace App\Filament\Resources\Tikets\Pages;

use App\Filament\Resources\Tikets\TiketResource;
use App\Livewire\TiketStats;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTikets extends ListRecords
{
    protected static string $resource = TiketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ExportAction::make()
                ->exporter(\App\Filament\Exports\TiketExporter::class)
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->label('Export Semua Tiket'),
            CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            TiketStats::class,
        ];
    }
}
