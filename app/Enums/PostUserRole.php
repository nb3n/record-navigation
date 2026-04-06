<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum PostUserRole: string implements HasColor, HasIcon, HasLabel
{
    case Author = 'author';
    case Editor = 'editor';
    case Contributor = 'contributor';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Author => 'Author',
            self::Editor => 'Editor',
            self::Contributor => 'Contributor',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Author => 'success',
            self::Editor => 'warning',
            self::Contributor => 'gray',
        };
    }

    public function getIcon(): ?Heroicon
    {
        return match ($this) {
            self::Author => Heroicon::PencilSquare,
            self::Editor => Heroicon::Eye,
            self::Contributor => Heroicon::User,
        };
    }
}