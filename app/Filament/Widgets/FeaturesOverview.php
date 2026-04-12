<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class FeaturesOverview extends Widget
{
    protected string $view = 'filament.widgets.features-overview';

    protected int | string | array $columnSpan = 'full';

    protected static bool $isLazy = false;

    /**
     * @return array<int, array{name: string, icon: string, color: string, features: array<int, array{name: string, description: string, url: string, resource: string}>}>
     */
    public function getCategories(): array
    {
        return array_filter(array_map(
            fn (?array $category): ?array => $category && count($category['features']) > 0 ? $category : null,
            [
                $this->tablesCategory(),
                $this->tablesCategory(),
                $this->tablesCategory(),
            ],
        ));
    }

    /**
     * @return array{name: string, icon: string, color: string, features: list<array{name: string, description: string, url: string, resource: string}>}
     */
    protected function tablesCategory(): array
    {
        return [
            'name' => 'Tables & Columns',
            'icon' => 'heroicon-o-table-cells',
            'color' => 'blue',
            'features' => [
                ['name' => 'Searchable & sortable', 'description' => 'Full-text search with sortable column headers', 'url' => '/', 'resource' => 'Products'],
                ['name' => 'Image columns', 'description' => 'Thumbnails from Spatie Media Library', 'url' => '/', 'resource' => 'Products'],
                ['name' => 'Column summarizers', 'description' => 'Scroll to the table footer to see sum totals for price and shipping', 'url' => '/', 'resource' => 'Orders'],
                ['name' => 'Inline editing', 'description' => 'Click a status cell to change it inline', 'url' => '/', 'resource' => 'Leave Requests'],
                ['name' => 'Table grouping', 'description' => 'Toggle grouping using the group icon in table header', 'url' => '/', 'resource' => 'Orders'],
                ['name' => 'Live polling', 'description' => 'Table data auto-refreshes every 30 seconds in the background', 'url' => '/', 'resource' => 'Expenses'],
                ['name' => 'Toggleable columns', 'description' => 'Click the column toggle icon in the table header', 'url' => '/', 'resource' => 'Employees'],
                ['name' => 'Color columns', 'description' => 'Hidden by default — enable "Team color" via the column toggle icon to see swatches', 'url' => '/', 'resource' => 'Employees'],
                ['name' => 'Column layouts', 'description' => 'Custom multi-row layouts with split and stack', 'url' => '/', 'resource' => 'Authors'],
                ['name' => 'Drag-and-drop reordering', 'description' => 'Click the reorder toggle in the table header, then drag rows', 'url' => '/', 'resource' => 'Brands'],
                ['name' => 'Copyable columns', 'description' => 'Click any email cell to copy the value to your clipboard', 'url' => '/', 'resource' => 'Employees'],
            ],
        ];
    }
}
