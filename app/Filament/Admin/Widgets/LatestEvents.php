<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\Events\EventResource;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;

class LatestEvents extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('nama_event')->label('Nama Event'),
                TextColumn::make('category.nama_kategori')->label('Kategori'),
                TextColumn::make('tanggal')->date(),
                TextColumn::make('venue.nama_venue')->label('Lokasi'),
            ])
            ->actions([
                Action::make('Edit')
                    ->url(fn (Event $record): string => EventResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->paginated(false);
    }
}
