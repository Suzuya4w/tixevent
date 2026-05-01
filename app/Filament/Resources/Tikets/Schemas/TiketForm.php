<?php

namespace App\Filament\Resources\Tikets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;


class TiketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        Select::make('event_id')
                            ->relationship('event', 'nama_event')
                            ->label('Pilih Event')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        TextInput::make('nama_tiket')
                            ->label('Nama Tiket (contoh: VIP, Festival)')
                            ->required()
                            ->maxLength(50),
                    ]),

                Section::make('Harga & Kontrol Kuota')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('harga')
                                ->label('Harga Tiket')
                                ->required()
                                ->numeric()
                                ->prefix('Rp'),
                            
                            TextInput::make('kuota')
                                ->label('Kapasitas / Kuota')
                                ->required()
                                ->numeric()
                                ->minValue(1),

                            TextInput::make('max_beli')
                                ->label('Limit Beli Per Orang')
                                ->numeric()
                                ->default(5)
                                ->minValue(1)
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}