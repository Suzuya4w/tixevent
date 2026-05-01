<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Waiting = 'waiting_confirmation';
    case Paid = 'paid';
    case Cancel = 'cancel';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Menunggu Pembayaran',
            self::Waiting => 'Menunggu Verifikasi',
            self::Paid => 'Lunas',
            self::Cancel => 'Dibatalkan',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Waiting => 'warning',
            self::Paid => 'success',
            self::Cancel => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-m-clock',
            self::Waiting => 'heroicon-m-arrow-path',
            self::Paid => 'heroicon-m-check-badge',
            self::Cancel => 'heroicon-m-x-circle',
        };
    }
}