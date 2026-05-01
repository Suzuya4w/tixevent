<?php

namespace App\Filament\Resources\Venues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;

class VenuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_venue')
                    ->searchable(),
                TextColumn::make('kapasitas')
                    ->numeric()
                    ->sortable()
                    ->summarize(
        Sum::make()
            ->label('Total Seluruh Kapasitas')
    ),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('kapasitas')
                    ->form([
                        TextInput::make('min_kapasitas')->numeric()->label('Kapasitas Min.'),
                        TextInput::make('max_kapasitas')->numeric()->label('Kapasitas Max.'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_kapasitas'],
                                fn (Builder $query, $min): Builder => $query->where('kapasitas', '>=', $min),
                            )
                            ->when(
                                $data['max_kapasitas'],
                                fn (Builder $query, $max): Builder => $query->where('kapasitas', '<=', $max),
                            );
                    })
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
