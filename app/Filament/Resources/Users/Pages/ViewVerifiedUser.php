<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;
use Nben\FilamentRecordNav\Enums\NavigationPage;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;

class ViewVerifiedUser extends ViewRecord
{
    use WithRecordNavigation;

    protected static string $resource = UserResource::class;

    protected function getDoc(string $path): string
    {
        return file_get_contents(resource_path("docs/{$path}.md"));
    }

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()
                ->tooltip('Previous Verified User')
                ->navigateTo(NavigationPage::custom('verified-view')),

            NextRecordAction::make()
                ->tooltip('Next Verified User')
                ->navigateTo(NavigationPage::custom('verified-view')),

            Action::make('docs')
                ->button()
                ->outlined()
                ->label('Docs')
                ->infolist([
                    TextEntry::make('content')
                        ->state(fn () => $this->getDoc('user/03-single-query-resolution'))
                        ->markdown()
                        ->prose()
                        ->hiddenLabel()
                        ->columnSpanFull(),
                ])
                ->slideOver()
                ->modalSubmitAction(false)
                ->modalCancelAction(false),

            EditAction::make(),
        ];
    }

    public function getPreviousRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->whereNotNull('email_verified_at')
            ->where('id', '<', $this->getRecord()->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getNextRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->whereNotNull('email_verified_at')
            ->where('id', '>', $this->getRecord()->id)
            ->orderBy('id', 'asc')
            ->first();
    }
}
