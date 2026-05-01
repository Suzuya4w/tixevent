<?php

namespace App\Filament\Resources\Tikets;

use App\Filament\Resources\Tikets\Pages\CreateTiket;
use App\Filament\Resources\Tikets\Pages\EditTiket;
use App\Filament\Resources\Tikets\Pages\ListTikets;
use App\Filament\Resources\Tikets\Schemas\TiketForm;
use App\Filament\Resources\Tikets\Tables\TiketsTable;
use App\Models\Tiket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TiketResource extends Resource
{
    protected static ?string $model = Tiket::class;
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Event';
    protected static ?int $navigationSort = 4;


    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $recordTitleAttribute = 'nama_tiket';

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return TiketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TiketsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTikets::route('/'),
            'create' => CreateTiket::route('/create'),
            'edit' => EditTiket::route('/{record}/edit'),
        ];
    }
}
