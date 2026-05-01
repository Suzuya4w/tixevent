<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
Section::make('Detail Pesanan')
                    ->schema([
                        TextInput::make('id')
                            ->label('Order ID'),
                            
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Nama Pembeli')
                            ->disabled(),
                            
                        TextInput::make('total')
                            ->label('Total Pembayaran')
                            ->prefix('Rp')
                            ->numeric(),
                            
                        Select::make('status')
                            ->options([
                                'pending' => 'Menunggu Pembayaran',
                                'waiting_confirmation' => 'Menunggu Verifikasi',
                                'paid' => 'Lunas',
                                'cancel' => 'Dibatalkan',
                            ]),
                            
                        DateTimePicker::make('created_at')
                            ->label('Tanggal Order'),
                    ])->columns(2),


                Section::make('Dokumen Verifikasi')
                    ->schema([
                        FileUpload::make('bukti_transfer')
                            ->label('Bukti Transfer')
                            ->disk('public')
                            ->image()
                            ->downloadable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
