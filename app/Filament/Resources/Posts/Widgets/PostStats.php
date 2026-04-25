<?php

namespace App\Filament\Resources\Posts\Widgets;

use App\Enums\PostStatus;
use App\Filament\Resources\Posts\Pages\ListPosts;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Post Insights';

    protected ?string $description = 'Quick overview of post distribution by status and featured content.';

    protected function getTablePage(): string
    {
        return ListPosts::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $totalPosts = (clone $query)->count();

        $publishedPosts = (clone $query)
            ->where('status', PostStatus::Published)
            ->count();

        $underReviewPosts = (clone $query)
            ->where('status', PostStatus::UnderReview)
            ->count();

        $featuredPosts = (clone $query)
            ->where('is_featured', true)
            ->count();

        return [
            Stat::make('Total Posts', $totalPosts)
                ->color('primary'),

            Stat::make('Published', $publishedPosts)
                ->color('success'),

            Stat::make('Under Review', $underReviewPosts)
                ->color('warning'),

            Stat::make('Featured', $featuredPosts)
                ->color('info'),
        ];
    }
}
