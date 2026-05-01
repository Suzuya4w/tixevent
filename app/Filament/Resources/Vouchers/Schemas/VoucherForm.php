<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->description('Atur kode dan nilai potongan voucher.')
                    ->schema([
                        TextInput::make('kode_voucher')
                            ->label('Kode Voucher (Ketik Huruf Besar)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state)),
                            
                        Grid::make(2)->schema([
                            TextInput::make('potongan')
                                ->label('Nilai Potongan')
                                ->required()
                                ->numeric()
                                ->prefix('Rp')
                                ->step(1000),
                                
                            TextInput::make('kuota')
                                ->label('Batas Pemakaian (Kuota)')
                                ->required()
                                ->numeric()
                                ->minValue(1),
                        ]),
                    ]),

                Section::make('Masa Berlaku & Status')
                    ->description('Tentukan kapan voucher ini bisa dipakai.')
                    ->schema([
                        Grid::make(2)->schema([
                            DateTimePicker::make('tanggal_mulai')
                                ->label('Berlaku Mulai')
                                ->required()
                                ->default(now()),
                                
                            DateTimePicker::make('tanggal_berakhir')
                                ->label('Berakhir Pada')
                                ->required()
                                ->afterOrEqual('tanggal_mulai'),
                        ]),

                        Select::make('status')
                            ->label('Status Voucher')
                            ->options([
                                'aktif' => 'Aktif (Bisa Dipakai)', 
                                'nonaktif' => 'Nonaktif (Dibekukan)'
                            ])
                            ->default('aktif')
                            ->required(),
                    ]),
            ]);
    }
}