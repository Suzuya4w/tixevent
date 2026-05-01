<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Order ID')->sortable(),
                TextColumn::make('user.name')->label('Pembeli')->searchable(),
                TextColumn::make('total')->money('IDR', locale: 'id')->sortable()->alignEnd()
                ->summarize(
        Sum::make()
            ->label('Total Omzet Lunas')
            ->money('IDR', locale: 'id')
            ->query(fn ($query) => $query->where('status', 'paid'))
    ),
    TextColumn::make('voucher.kode_voucher')
                    ->label('Voucher Dipakai')
                    ->searchable()
                    ->badge()
                    ->color(fn ($state) => $state === '-' ? 'gray' : 'warning')
                    ->default('-')
                    ->alignCenter(),
                TextColumn::make('status')
                    ->badge(),

ImageColumn::make('bukti_transfer')
                    ->label('Bukti Transfer')
                    ->disk('public')
                    ->width(50) 
                    ->height(50)
                    ->square()
                    ->alignCenter()
                    ->defaultImageUrl('https://placehold.co/50x50/f1f5f9/94a3b8?text=Kosong')
                    ->extraImgAttributes([
                        
                        'class' => 'rounded-md shadow-sm border border-gray-600 object-cover', 
                    ])
                    ->action(
                        Action::make('Lihat Gambar')
                            ->modalHeading('Bukti Transfer')
                            ->modalContent(fn (\App\Models\Order $record) => view('filament.admin.image-modal', [
                                'url' => $record->bukti_transfer ? asset('storage/' . $record->bukti_transfer) : null
                            ]))
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                    ),


                TextColumn::make('tanggal_order')
                    ->label('Tanggal Order')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
->filters([
    SelectFilter::make('status')
        ->label('Status Pesanan')
        ->options([
            'pending' => 'Pending',
            'waiting_confirmation' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
            'expired' => 'Kadaluarsa',
            'cancelled' => 'Dibatalkan',
        ]),

    Filter::make('total')
        ->form([
            TextInput::make('min_total')->numeric()->label('Min. Harga (Rp)'),
            TextInput::make('max_total')->numeric()->label('Max. Harga (Rp)'),
        ])
        ->query(function (Builder $query, array $data): Builder {
            return $query
                ->when(
                    $data['min_total'],
                    fn (Builder $query, $min): Builder => $query->where('total', '>=', $min),
                )
                ->when(
                    $data['max_total'],
                    fn (Builder $query, $max): Builder => $query->where('total', '<=', $max),
                );
        })
        ->indicateUsing(function (array $data): array {
            $indicators = [];
            if ($data['min_total'] ?? null) {
                $indicators[] = \Filament\Tables\Filters\Indicator::make('Harga Min: Rp ' . number_format($data['min_total'], 0, ',', '.'))
                    ->removeField('min_total');
            }
            if ($data['max_total'] ?? null) {
                $indicators[] = \Filament\Tables\Filters\Indicator::make('Harga Max: Rp ' . number_format($data['max_total'], 0, ',', '.'))
                    ->removeField('max_total');
            }
            return $indicators;
        }),

    Filter::make('tanggal_order')
        ->form([
            DatePicker::make('dari_tanggal')->label('Dari Tanggal'),
            DatePicker::make('sampai_tanggal')->label('Sampai Tanggal'),
        ])
        ->query(function (Builder $query, array $data): Builder {
            return $query
                ->when(
                    $data['dari_tanggal'],
                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                )
                ->when(
                    $data['sampai_tanggal'],
                    fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
        })
])
            ->recordActions([
Action::make('Verifikasi Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(\App\Models\Order $record) => $record->status->value === 'waiting_confirmation')
                    ->action(function (\App\Models\Order $record) {
                        $record->update(['status' => 'paid']);
                        Notification::make()
                            ->success()
                            ->title('Berhasil diverifikasi')
                            ->send();
                    }),
                    ViewAction::make(),
            ])
->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\OrderExporter::class)
                        ->icon('heroicon-o-arrow-down-tray')
                        ->label('Export Terpilih (CSV)'),

                    BulkAction::make('Verifikasi Lunas Terpilih')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Semua Pesanan Terpilih?')
                        ->modalDescription('Apakah Anda yakin ingin mengubah status semua pesanan yang dipilih menjadi Lunas?')
                        ->action(function (Collection $records) {
                            $verifiedCount = 0;

                            foreach ($records as $record) {
                                if ($record->status->value === 'waiting_confirmation') {
                                    $record->update(['status' => 'paid']);
                                    $verifiedCount++;
                                }
                            }

                            
                            Notification::make()
                                ->success()
                                ->title('Verifikasi Selesai')
                                ->body("{$verifiedCount} pesanan berhasil diubah menjadi Lunas.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
