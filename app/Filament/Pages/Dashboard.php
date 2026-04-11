<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Enums\IconPosition;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Welcome';

    protected ?string $heading = 'Record Navigation Demo';

    protected ?string $subheading = 'Explore how record navigation works in Filament.';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('github')
                ->label('View Source')
                ->url(config('filament-portal.github'))
                ->color('gray')
                ->outlined()
                ->icon(Heroicon::CodeBracket),

            Action::make('plugin')
                ->label('View Plugin')
                ->url(config('filament-portal.plugin'))
                ->color('primary')
                ->icon(Heroicon::Squares2x2),

            Action::make('sponsor')
                ->label('Support Project')
                ->url(config('filament-portal.sponsor'))
                ->color('danger')
                ->icon(Heroicon::Heart)
                ->iconPosition(IconPosition::After),
        ];
    }
}
