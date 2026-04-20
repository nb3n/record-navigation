<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

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
