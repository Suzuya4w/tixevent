<?php

namespace App\Filament\Resources\Attendees\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttendeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_detail_id')
                    ->required()
                    ->numeric(),
                TextInput::make('kode_tiket')
                    ->required(),
                Select::make('status_checkin')
                    ->options(['belum' => 'Belum', 'sudah' => 'Sudah'])
                    ->default('belum')
                    ->required(),
                DateTimePicker::make('waktu_checkin'),
            ]);
    }
}
