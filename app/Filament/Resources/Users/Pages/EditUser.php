<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Enums\NavigationPage;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
}
