<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use App\Enums\PostStatus;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Enums\NavigationPage;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()
                ->navigateTo(NavigationPage::View),

            NextRecordAction::make()
                ->navigateTo(NavigationPage::View),
                
            Action::make('quick_publish')
                ->icon(Heroicon::RocketLaunch)
                ->color('success')
                ->keyBindings(['mod+shift+p'])
                ->visible(fn (Post $record): bool => ! $record->published_at?->isPast())
                ->action(function (Post $record): void {
                    $record->update([
                        'published_at' => now(),
                        'scheduled_at' => null,
                        'status' => PostStatus::Published,
                    ]);

                    $this->refreshFormData(['published_at', 'status', 'scheduled_at']);

                    Notification::make()
                        ->title('Post published')
                        ->success()
                        ->send();
                }),

            Action::make('unpublish')
                ->icon(Heroicon::XCircle)
                ->color('warning')
                ->visible(fn (Post $record): bool => (bool) $record->published_at?->isPast())
                ->action(function (Post $record): void {
                    $record->update([
                        'published_at' => null,
                        'status' => PostStatus::Draft,
                    ]);

                    $this->refreshFormData(['published_at', 'status']);

                    Notification::make()
                        ->title('Post unpublished')
                        ->warning()
                        ->send();
                }),

            EditAction::make(),
        ];
    }
}
