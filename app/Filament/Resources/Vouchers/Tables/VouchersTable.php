<?php

namespace App\Filament\Resources\Vouchers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\ExportBulkAction;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount(['orders as total_terpakai' => function ($q) {
                $q->whereNotIn('status', ['cancel']); 
            }]))
            ->columns([
                TextColumn::make('kode_voucher')
                    ->label('Kode Voucher')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->weight('bold'),
                    
                TextColumn::make('potongan')
                    ->label('Potongan')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                    
    TextColumn::make('total_terpakai')
                    ->label('Telah Dipakai')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                TextColumn::make('kuota')
                    ->label('Sisa Kuota')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => $state <= 5 ? 'danger' : 'success')
                    ->summarize(
        Sum::make()
            ->label('Total Voucher Tersedia')
    ),
                    
                TextColumn::make('tanggal_berakhir')
                    ->label('Kadaluarsa')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->color(fn ($record) => Carbon::parse($record->tanggal_berakhir)->isPast() ? 'danger' : 'gray'), 
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->label('Status Voucher'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\VoucherExporter::class)
                        ->icon('heroicon-o-arrow-down-tray')
                        ->label('Export Terpilih (CSV)'),
                ]),
            ]);
    }
}