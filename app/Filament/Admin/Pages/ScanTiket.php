<?php

namespace App\Filament\Admin\Pages;

use App\Models\Event;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use UnitEnum;
use Filament\Schemas\Schema;

class ScanTiket extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Tiket';
    protected static ?string $title = 'Scanner Tiket Masuk';
    protected string $view = 'filament.admin.pages.scan-tiket';


    public ?array $data = [];

    public function mount(): void
    {

        $this->form->fill([
            'event_id' => Event::whereDate('tanggal', '>=', today())->orderBy('tanggal', 'asc')->first()?->id,
        ]);
    }

public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')
                    ->label('Filter Event (Wajib Dipilih)')
                    ->options(Event::pluck('nama_event', 'id'))
                    ->searchable()
                    ->live()
                    ->required(),
            ])
            ->statePath('data');
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Livewire\ScanStats::class,
        ];
    }
}