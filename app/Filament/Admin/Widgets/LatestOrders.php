<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(5)
            )
            ->columns([
                TextColumn::make('id')->label('Order ID'),
                TextColumn::make('user.name')->label('Pembeli'),
                TextColumn::make('total')->money('IDR', locale: 'id'),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->label('Tanggal Order')->dateTime(),
            ])
            ->actions([
                Action::make('Kelola')
                    ->url(fn (Order $record): string => OrderResource::getUrl('index'))
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->paginated(false);
    }
}
