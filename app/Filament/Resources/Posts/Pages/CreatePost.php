<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

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
