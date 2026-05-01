<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_event')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'nama_kategori')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('tanggal')
                    ->required(),
                \Filament\Forms\Components\FileUpload::make('gambar')
                    ->image()
                    ->directory('event-banners')
                    ->label('Banner Event')
                    ->columnSpanFull(),
                \Filament\Forms\Components\TimePicker::make('waktu')
                    ->label('Waktu Mulai'),
                \Filament\Forms\Components\TimePicker::make('waktu_selesai')
                    ->label('Waktu Selesai'),
                \Filament\Forms\Components\RichEditor::make('deskripsi')
                    ->label('Deskripsi Event')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'bulletList',
                        'orderedList',
                        'blockquote',
                        'redo',
                        'undo',
                    ])
                    ->columnSpanFull(),
                Select::make('venue_id')
                    ->relationship('venue', 'nama_venue')
                    ->searchable()
                    ->preload()
                    ->required(),
                \Filament\Forms\Components\Repeater::make('tikets')
                    ->relationship()
                    ->label('Jenis Tiket')
                    ->schema([
                        TextInput::make('nama_tiket')
                            ->label('Nama Tiket')
                            ->required(),
                        TextInput::make('harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('kuota')
                            ->numeric()
                            ->required(),
                        TextInput::make('max_beli')
                            ->label('Limit/Orang')
                            ->numeric()
                            ->default(5)
                            ->required(),
                    ])
                    ->columns(4)
                    ->addActionLabel('Tambah Jenis Tiket')
                    ->columnSpanFull()
                    ->defaultItems(0),
            ]);
    }
}
