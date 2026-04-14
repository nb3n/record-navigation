<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav\Enums\NavigationPage;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CategoryStatus;

class ViewActiveCategory extends ViewRecord
{
    use WithRecordNavigation;

    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()
                ->tooltip('Previous Active Category')
                ->navigateTo(NavigationPage::custom('active-category')),

            NextRecordAction::make()
                ->tooltip('Next Active Category')
                ->navigateTo(NavigationPage::custom('active-category')),

            EditAction::make(),
        ];
    }

    public function getPreviousRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->where('status', CategoryStatus::Active)
            ->where('id', '<', $this->getRecord()->id)
            ->orderBy('id', 'desc')
            ->first();
    }
 
    public function getNextRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->where('status', CategoryStatus::Active)
            ->where('id', '>', $this->getRecord()->id)
            ->orderBy('id', 'asc')
            ->first();
    }
}
