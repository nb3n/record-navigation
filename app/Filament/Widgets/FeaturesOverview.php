<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Users\UserResource;
use App\Enums\CategoryStatus;

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
        $post = Post::query()->first();

        return array_filter(array_map(
            fn (?array $category): ?array => $category && count($category['features']) > 0 ? $category : null,
            [
                $this->navigationBasics(),
                $this->navigationCustomisation(),
                $this->navigationDX($post),
            ],
        ));
    }

    protected function navigationBasics(): array
    {           
        $baseQuery = User::query()->orderBy('id');
        $total = (clone $baseQuery)->count();

        $firstUser = (clone $baseQuery)->first();
        $middleUser = $total > 0 ? (clone $baseQuery)->offset((int) floor($total / 2))->first() : null;
        $verifiedUser = (clone $baseQuery)->whereNotNull('email_verified_at')->first();
        
        return [
            'name' => 'Navigation Basics',
            'icon' => 'heroicon-o-arrow-right-circle',
            'color' => 'blue',
            'features' => array_values(array_filter([
                $middleUser ? [
                    'name' => 'Next & Previous buttons',
                    'description' => 'Navigate seamlessly between records',
                    'url' => UserResource::getUrl('view', ['record' => $middleUser]),
                    'resource' => 'Users',
                ] : null,
                $firstUser ? [
                    'name' => 'Smart boundaries',
                    'description' => 'Buttons disable at first and last record',
                    'url' => UserResource::getUrl('view', ['record' => $firstUser]),
                    'resource' => 'Users',
                ] : null,
                $verifiedUser ? [
                    'name' => 'Single query resolution',
                    'description' => 'Efficient navigation with cached query',
                    'url' => UserResource::getUrl('verified-view', ['record' => $verifiedUser]),
                    'resource' => 'Users',
                ] : null,
            ])),
        ];
    }

    protected function navigationCustomisation(): array
    {
        $baseQuery = Category::query()->orderBy('id');
        $firstCategory = (clone $baseQuery)->first();
        $scopedNavigation = (clone $baseQuery)->where('status', CategoryStatus::Active)->first();

        return [
            'name' => 'Customisation',
            'icon' => 'heroicon-o-adjustments-horizontal',
            'color' => 'violet',
            'features' => array_values(array_filter([
                $firstCategory ? [
                    'name' => 'Navigate to edit page',
                    'description' => 'Next/previous opens edit instead of view',
                    'url' => CategoryResource::getUrl('edit', ['record' => $firstCategory]),
                    'resource' => 'Category',
                ] : null,
                $scopedNavigation ? [
                    'name' => 'Scoped navigation',
                    'description' => 'Only navigate through active status',
                    'url' => CategoryResource::getUrl('active-category', ['record' => $scopedNavigation]),
                    'resource' => 'Category',
                ] : null,
                $scopedNavigation ? [
                    'name' => 'Custom query logic',
                    'description' => 'Override navigation queries per use-case',
                    'url' => CategoryResource::getUrl('active-category', ['record' => $scopedNavigation]), // TODO: Override navigation logic
                    'resource' => 'Category',
                ] : null,
            ])),
        ];
    }

protected function navigationDX(?Model $post): array
{
    return [
        'name' => 'Developer Experience',
        'icon' => 'heroicon-o-code-bracket',
        'color' => 'emerald',
        'features' => array_values(array_filter([
            $post ? [
                'name' => 'Zero configuration',
                'description' => 'Drop in actions and it works instantly',
                'url' => PostResource::getUrl('index'),
                'resource' => 'Posts',
            ] : null,
            $post ? [
                'name' => 'No trait required',
                'description' => 'Works without modifying your page class',
                'url' => PostResource::getUrl('index'),
                'resource' => 'Posts',
            ] : null,
            $post ? [
                'name' => 'Optional overrides',
                'description' => 'Add trait only when customization is needed',
                'url' => PostResource::getUrl('index'),
                'resource' => 'Posts',
            ] : null,
        ])),
    ];
}
}
