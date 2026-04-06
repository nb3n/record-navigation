<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum PostStatus: string implements HasColor, HasIcon, HasLabel
{
    case Draft = 'draft';
    case UnderReview = 'under_review';
    case Published = 'published';
    case Scheduled = 'scheduled';
    case Archived = 'archived';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::UnderReview => 'Under Review',
            self::Published => 'Published',
            self::Scheduled => 'Scheduled',
            self::Archived => 'Archived',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::UnderReview => 'warning',
            self::Published => 'success',
            self::Scheduled => 'info',
            self::Archived => 'danger',
        };
    }

    public function getIcon(): ?Heroicon
    {
        return match ($this) {
            self::Draft => Heroicon::PencilSquare,
            self::UnderReview => Heroicon::Eye,
            self::Published => Heroicon::CheckBadge,
            self::Scheduled => Heroicon::Calendar,
            self::Archived => Heroicon::ArchiveBox,
        };
    }
}