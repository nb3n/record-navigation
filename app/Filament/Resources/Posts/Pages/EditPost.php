<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Enums\NavigationPage;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

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
