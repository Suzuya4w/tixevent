<?php

namespace App\Filament\Resources\Attendees;

use App\Filament\Resources\Attendees\Pages\CreateAttendee;
use App\Filament\Resources\Attendees\Pages\EditAttendee;
use App\Filament\Resources\Attendees\Pages\ListAttendees;
use App\Filament\Resources\Attendees\Schemas\AttendeeForm;
use App\Filament\Resources\Attendees\Tables\AttendeesTable;
use App\Models\Attendee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AttendeeResource extends Resource
{
    protected static ?string $model = Attendee::class;
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi & Kehadiran';
    protected static ?int $navigationSort = 3;


    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'kode_tiket';

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'petugas']);
    }

    public static function form(Schema $schema): Schema
    {
        return AttendeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendeesTable::configure($table);
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
            'index' => ListAttendees::route('/'),
            // 'create' => CreateAttendee::route('/create'),
            // 'edit' => EditAttendee::route('/{record}/edit'),
        ];
    }
}
