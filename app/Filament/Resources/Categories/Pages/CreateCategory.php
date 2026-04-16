<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

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
