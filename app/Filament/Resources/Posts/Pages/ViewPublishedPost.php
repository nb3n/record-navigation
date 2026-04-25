<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Enums\PostStatus;
use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;
use Nben\FilamentRecordNav\Enums\NavigationPage;

class ViewPublishedPost extends ViewRecord
{
    use WithRecordNavigation;

    protected static string $resource = PostResource::class;

    protected function getDoc(string $path): string
    {
        return file_get_contents(resource_path("docs/{$path}.md"));
    }

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()
                ->tooltip('Previous Published Post')
                ->navigateTo(NavigationPage::custom('published-view')),

            NextRecordAction::make()
                ->tooltip('Next Published Post')
                ->navigateTo(NavigationPage::custom('published-view')),

            Action::make('docs')
                ->button()
                ->outlined()
                ->label('Docs')
                ->infolist([
                    TextEntry::make('content')
                        ->state(fn () => $this->getDoc('post/09-optional-overrides-and-custom-routes'))
                        ->markdown()
                        ->prose()
                        ->hiddenLabel()
                        ->columnSpanFull(),
                ])
                ->slideOver()
                ->modalSubmitAction(false)
                ->modalCancelAction(false),

            Action::make('quick_publish')
                ->icon(Heroicon::RocketLaunch)
                ->color('success')
                ->keyBindings(['mod+shift+p'])
                ->visible(fn (Post $record): bool => ! $record->published_at?->isPast())
                ->action(function (Post $record): void {
                    Notification::make()
                        ->title('Now, now, these records aren\'t yours to change!')
                        ->warning()
                        ->send();

                    $this->halt();

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
                    Notification::make()
                        ->title('Now, now, these records aren\'t yours to change!')
                        ->warning()
                        ->send();

                    $this->halt();

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

    public function getPreviousRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->where('status', PostStatus::Published)
            ->where('id', '<', $this->getRecord()->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getNextRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->where('status', PostStatus::Published)
            ->where('id', '>', $this->getRecord()->id)
            ->orderBy('id', 'asc')
            ->first();
    }
}
