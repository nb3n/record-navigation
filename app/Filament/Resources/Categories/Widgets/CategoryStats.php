<?php

namespace App\Filament\Resources\Categories\Widgets;

use App\Filament\Resources\Categories\Pages\ListCategories;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CategoryStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Category Overview';

    protected ?string $description = 'Hierarchy breakdown of categories displayed in the table below.';

    protected function getTablePage(): string
    {
        return ListCategories::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $categoryCount = (clone $query)
            ->whereNull('parent_id')
            ->count();

        $subCategoryCount = (clone $query)
            ->whereNotNull('parent_id')
            ->whereHas('parent', function ($q) {
                $q->whereNull('parent_id');
            })
            ->count();

        $topicCount = (clone $query)
            ->whereHas('parent.parent')
            ->count();

        return [
            Stat::make('Categories', $categoryCount)
                ->color('success'),

            Stat::make('Subcategories', $subCategoryCount)
                ->color('info'),

            Stat::make('Topics', $topicCount)
                ->color('gray'),
        ];
    }
}
