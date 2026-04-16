<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function beforeCreate(): void
    {
        Notification::make()
            ->title('Now, now, these records aren\'t yours to create!')
            ->warning()
            ->send();

        $this->form->fill();
        $this->halt();
    }
}
