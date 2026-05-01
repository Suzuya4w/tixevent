<?php

namespace App\Filament\Resources\Attendees\Tables;

use App\Filament\Exports\AttendeeExporter;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Actions\ExportAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AttendeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('waktu_checkin', 'desc') 
            ->columns([
                TextColumn::make('orderDetail.order.id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('orderDetail.order.status')
                    ->label('Status Bayar')
                    ->badge()
                    ->color(fn ($state) => match ($state?->value ?? $state) {
                        'paid' => 'success',
                        'waiting' => 'warning',
                        'waiting_confirmation' => 'warning',
                        'pending' => 'gray',
                        'cancel' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('orderDetail.tiket.event.nama_event')
                    ->label('Event')
                    ->sortable()
                    ->weight('bold')
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 20 ? $state : null;
                    }),

                TextColumn::make('orderDetail.tiket.nama_tiket')
                    ->label('Kategori')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('orderDetail.order.user.name')
                    ->label('Pembeli')
                    ->searchable(),

                TextColumn::make('kode_tiket')
                    ->label('Kode Tiket')
                    ->searchable()
                    ->copyable()
                    ->copyableState(fn (string $state): string => $state)
                    ->limit(15)
                    ->tooltip(fn (string $state): string => $state)
                    ->fontFamily('mono'),


                TextColumn::make('status_checkin')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sudah' => 'success',
                        'belum' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                TextColumn::make('waktu_checkin')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i') 
                    ->sortable()
                    ->placeholder('Belum Hadir'), 
            ])
->filters([

                SelectFilter::make('status_checkin')
                    ->label('Status Kehadiran')
                    ->options([
                        'sudah' => 'Sudah Check-in',
                        'belum' => 'Belum Hadir',
                    ]),


                Filter::make('Event')
                    ->form([
                        Select::make('event_id')
                            ->label('Filter Berdasarkan Event')
                            ->options(Event::pluck('nama_event', 'id'))
                            ->searchable()
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['event_id'],

                            fn (Builder $query, $eventId): Builder => $query->whereHas('orderDetail.tiket', fn($q) => $q->where('event_id', $eventId)),
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['event_id']) {
                            return null;
                        }
                        return 'Event: ' . Event::find($data['event_id'])?->nama_event;
                    }),

                Filter::make('waktu_checkin')
                    ->form([
                        DatePicker::make('dari_tanggal')->label('Check-in Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')->label('Check-in Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('waktu_checkin', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('waktu_checkin', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        
                        if ($data['dari_tanggal'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make('Dari: ' . \Carbon\Carbon::parse($data['dari_tanggal'])->translatedFormat('d M Y'))
                                ->removeField('dari_tanggal');
                        }
                        
                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make('Sampai: ' . \Carbon\Carbon::parse($data['sampai_tanggal'])->translatedFormat('d M Y'))
                                ->removeField('sampai_tanggal');
                        }
                        
                        return $indicators;
                    }),
            ])
            ->recordActions([
Action::make('manual_check_in')
                ->label('Manual check in')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->hidden(fn ($record) => $record->status_checkin === 'sudah')
                ->disabled(function ($record) {
                    $statusBayar = $record->orderDetail->order->status->value ?? $record->orderDetail->order->status;
                    return $statusBayar !== 'paid';
                })
                ->tooltip(function ($record) {
                    $statusBayar = $record->orderDetail->order->status->value ?? $record->orderDetail->order->status;
                    return $statusBayar !== 'paid' ? 'Pesanan belum lunas!' : 'Klik untuk Check-in';
                })
                ->action(function ($record) {
                    
                    $statusBayar = $record->orderDetail->order->status->value ?? $record->orderDetail->order->status;
                    
                    if ($statusBayar !== 'paid') {
                        Notification::make()
                            ->title('Akses Ditolak!')
                            ->body('Tidak bisa Check-in. Pesanan ini belum LUNAS / belum diverifikasi Admin.')
                            ->danger()
                            ->send();
                        return; 
                    }


                    $record->update([
                        'status_checkin' => 'sudah',
                        'waktu_checkin' => now(),
                    ]);
                    
                    Notification::make()
                        ->title('Berhasil!')
                        ->body('Peserta berhasil Check-in.')
                        ->success()
                        ->send();
                }),
            ])
->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(AttendeeExporter::class)
                        ->icon('heroicon-o-arrow-down-tray')
                        ->label('Export Data CSV'),


                    BulkAction::make('bulk_manual_check_in')
                        ->label('Check-in Massal (Terpilih)')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Check-in Rombongan')
                        ->modalDescription('Apakah Anda yakin ingin melakukan check-in untuk semua tiket yang dicentang?')
                        ->modalSubmitActionLabel('Ya, Check-in Sekarang')
                        ->action(function (Collection $records) {
                            $berhasil = 0;
                            $gagal = 0;

                            foreach ($records as $record) {

                                $statusBayar = $record->orderDetail->order->status->value ?? $record->orderDetail->order->status;


                                if ($statusBayar === 'paid' && $record->status_checkin !== 'sudah') {
                                    $record->update([
                                        'status_checkin' => 'sudah',
                                        'waktu_checkin' => now(),
                                    ]);
                                    $berhasil++;
                                } else {
                                    $gagal++;
                                }
                            }

                            if ($berhasil > 0) {
                                Notification::make()
                                    ->title('Check-in Berhasil!')
                                    ->body("{$berhasil} tiket berhasil masuk.")
                                    ->success()
                                    ->send();
                            }


                            if ($gagal > 0) {
                                Notification::make()
                                    ->title('Sebagian Ditolak')
                                    ->body("{$gagal} tiket gagal di-check-in karena pesanan belum lunas atau tiket sudah pernah digunakan.")
                                    ->warning()
                                    ->send();
                            }
                        })
                        ->deselectRecordsAfterCompletion(), 
                ]),
            ]);
    }
}