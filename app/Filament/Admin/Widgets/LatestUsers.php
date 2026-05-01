<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestUsers extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->where('role', 'user')->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Pengguna'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Daftar')->dateTime(),
            ])
            ->paginated(false);
    }
}
