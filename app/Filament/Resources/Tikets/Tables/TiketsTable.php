<?php

namespace App\Filament\Resources\Tikets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TiketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
->modifyQueryUsing(fn (Builder $query) => $query
                ->withSum(['orderDetails as total_terjual' => function ($q) {
                    $q->whereHas('order', fn ($q2) => $q2->where('status', 'paid'));
                }], 'qty')
                ->withSum(['orderDetails as total_pendapatan' => function ($q) {
                    $q->whereHas('order', fn ($q2) => $q2->where('status', 'paid'));
                }], 'subtotal')
            )
            ->columns([
                TextColumn::make('event.nama_event')
                    ->label('Event')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_tiket')
                    ->searchable(),
                TextColumn::make('harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                
                TextColumn::make('total_terjual')
                    ->label('Laku Terjual')
                    ->numeric()
                    ->default(0)
                    ->sortable()
                    ->badge()
                    ->alignCenter()
                    ->icon(fn ($state) => $state > 0 ? 'heroicon-o-shopping-cart' : 'heroicon-o-minus-circle')
                    ->color(fn ($state) => $state > 0 ? 'info' : 'gray')
                    ->summarize(
        Sum::make()
            ->label('Total Tiket Terjual')
                    ),

                TextColumn::make('total_pendapatan')
                    ->label('Omset Kategori')
                    ->money('IDR', locale: 'id')
                    ->default(0)
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->weight('bold')
                    ->summarize(
                        Sum::make()
                            ->label('Total Omset Keseluruhan')
                            ->money('IDR', locale: 'id')
                    ),

                TextColumn::make('kuota')
                    ->label('Sisa Kuota')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->alignCenter()
                    ->icon(fn ($state) => $state <= 10 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-ticket')
                    ->color(fn ($state) => match (true) {
                        $state <= 10 => 'danger',
                        $state <= 30 => 'warning',
                        default => 'success',
                    })
                    ->summarize(
        Sum::make()
            ->label('Total Tiket Tersedia')
                    ),

            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->relationship('event', 'nama_event')
                    ->label('Filter Event')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(\App\Filament\Exports\TiketExporter::class)
                        ->icon('heroicon-o-arrow-down-tray')
                        ->label('Export Terpilih (CSV)'),
                ]),
            ]);
    }
}