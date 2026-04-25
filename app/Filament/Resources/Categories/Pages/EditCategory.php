<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Enums\NavigationPage;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getDoc(string $path): string
    {
        return file_get_contents(resource_path("docs/{$path}.md"));
    }

    protected function beforeSave(): void
    {
        Notification::make()
            ->title('Now, now, these records aren\'t yours to edit!')
            ->warning()
            ->send();

        $this->fillForm();
        $this->halt();
    }

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()
                ->navigateTo(NavigationPage::Edit),

            NextRecordAction::make()
                ->navigateTo(NavigationPage::Edit),

            Action::make('docs')
                ->button()
                ->outlined()
                ->label('Docs')
                ->infolist([
                    TextEntry::make('content')
                        ->state(fn () => $this->getDoc('category/04-navigate-to-edit-page'))
                        ->markdown()
                        ->prose()
                        ->hiddenLabel()
                        ->columnSpanFull(),
                ])
                ->slideOver()
                ->modalSubmitAction(false)
                ->modalCancelAction(false),

            ViewAction::make(),

            DeleteAction::make()
                ->before(function ($action): void {
                    Notification::make()
                        ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                        ->warning()
                        ->send();

                    $action->cancel();
                }),

            ForceDeleteAction::make()
                ->before(function ($action): void {
                    Notification::make()
                        ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                        ->warning()
                        ->send();

                    $action->cancel();
                }),

            RestoreAction::make()
                ->before(function ($action): void {
                    Notification::make()
                        ->title('Now, now, these records aren\'t yours to restore!')
                        ->warning()
                        ->send();

                    $action->cancel();
                }),
        ];
    }
}
