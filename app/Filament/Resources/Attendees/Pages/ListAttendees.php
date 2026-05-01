<?php

namespace App\Filament\Resources\Attendees\Pages;

use App\Filament\Resources\Attendees\AttendeeResource;
use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Livewire\Attributes\On;

class ListAttendees extends ListRecords
{
    protected static string $resource = AttendeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Export
            ExportAction::make()
                ->exporter(\App\Filament\Exports\AttendeeExporter::class)
                ->icon('heroicon-o-document-arrow-down')
                ->label('Download Laporan (CSV)')
                ->color('success'),
        ];
    }
        public function getTabs(): array
    {
        return [
            'hari_ini' => Tab::make('Berlangsung Hari Ini')

                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('orderDetail.tiket.event', function ($q) {
                    $q->whereDate('tanggal', today());
                })),
                
            'belum_checkin' => Tab::make('Belum Check-in (Hari Ini)')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_checkin', 'belum')
                    ->whereHas('orderDetail.tiket.event', fn ($q) => $q->whereDate('tanggal', today()))
                ),

            'semua' => Tab::make('Semua Data Riwayat')
        ];
    }
}

