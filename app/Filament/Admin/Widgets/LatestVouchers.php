<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Voucher;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestVouchers extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Voucher::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_voucher')->label('Kode Voucher'),
                Tables\Columns\TextColumn::make('diskon')->label('Diskon (%)')->numeric(),
                Tables\Columns\TextColumn::make('kuota')->label('Sisa Kuota'),
                Tables\Columns\TextColumn::make('status')->badge(),
            ])
            ->paginated(false);
    }
}
