<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Enums\NavigationPage;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function beforeSave(): void
    {
        Notification::make()
            ->title('Now, now, these records aren\'t yours to edit!')
            ->warning()
            ->send();

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
        ];
    }

    protected function beforeSave(): void
    {
        Notification::make()
            ->title('Now, now, these records aren\'t yours to change!')
            ->warning()
            ->send();

        $this->fillForm();
        $this->halt();
    }
}
