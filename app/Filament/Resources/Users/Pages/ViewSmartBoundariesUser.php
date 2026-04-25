<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Enums\NavigationPage;

class ViewSmartBoundariesUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getDoc(string $path): string
    {
        return file_get_contents(resource_path("docs/{$path}.md"));
    }

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()
                ->navigateTo(NavigationPage::custom('smart-boundaries')),

            NextRecordAction::make()
                ->navigateTo(NavigationPage::custom('smart-boundaries')),

            Action::make('docs')
                ->button()
                ->outlined()
                ->label('Docs')
                ->infolist([
                    TextEntry::make('content')
                        ->state(fn () => $this->getDoc('user/02-smart-boundaries'))
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
}
