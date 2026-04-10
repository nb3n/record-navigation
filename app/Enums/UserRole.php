<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum UserRole: string implements HasColor, HasIcon, HasLabel
{
    case Admin   = 'admin';
    case General = 'general';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin   => 'Admin',
            self::General => 'General',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Admin   => 'warning',
            self::General => 'gray',
        };
    }

    public function getIcon(): ?Heroicon
    {
        return match ($this) {
            self::Admin   => Heroicon::ShieldCheck,
            self::General => Heroicon::User,
        };
    }
}
