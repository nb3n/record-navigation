<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;
use Nben\FilamentRecordNav\Enums\NavigationPage;
use Illuminate\Database\Eloquent\Model;

class ViewVerifiedUser extends ViewRecord
{
    use WithRecordNavigation;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()
                ->tooltip('Previous Verified User')
                ->navigateTo(NavigationPage::custom('verified-view')),

            NextRecordAction::make()
                ->tooltip('Next Verified User')
                ->navigateTo(NavigationPage::custom('verified-view')),

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
