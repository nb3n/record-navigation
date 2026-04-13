<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Posts\PostResource;

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
        $user = User::query()->first();
        $category = Category::query()->first();
        
        return array_filter(array_map(
            fn (?array $category): ?array => $category && count($category['features']) > 0 ? $category : null,
            [
                $this->navigationBasics($post),
                $this->navigationCustomisation($post),
                $this->navigationDX($post),
            ],
        ));
    }

    protected function navigationBasics(?Model $post): array
    {
        return [
            'name' => 'Navigation Basics',
            'icon' => 'heroicon-o-arrow-right-circle',
            'color' => 'blue',
            'features' => array_values(array_filter([
                $post ? [
                    'name' => 'Next & Previous buttons',
                    'description' => 'Navigate seamlessly between records',
                    'url' => PostResource::getUrl('index'),
                    'resource' => 'Posts',
                ] : null,
                $post ? [
                    'name' => 'Smart boundaries',
                    'description' => 'Buttons disable at first and last record',
                    'url' => PostResource::getUrl('index'),
                    'resource' => 'Posts',
                ] : null,
                $post ? [
                    'name' => 'Single query resolution',
                    'description' => 'Efficient navigation with cached query',
                    'url' => PostResource::getUrl('index'),
                    'resource' => 'Posts',
                ] : null,
            ])),
        ];
    }

    protected function navigationCustomisation(?Model $post): array
{
    return [
        'name' => 'Customisation',
        'icon' => 'heroicon-o-adjustments-horizontal',
        'color' => 'violet',
        'features' => array_values(array_filter([
            $post ? [
                'name' => 'Navigate to edit page',
                'description' => 'Next/previous opens edit instead of view',
                'url' => PostResource::getUrl('index'),
                'resource' => 'Posts',
            ] : null,
            $post ? [
                'name' => 'Scoped navigation',
                'description' => 'Only navigate through published posts',
                'url' => PostResource::getUrl('index'),
                'resource' => 'Posts',
            ] : null,
            $post ? [
                'name' => 'Custom query logic',
                'description' => 'Override navigation queries per use-case',
                'url' => PostResource::getUrl('index'),
                'resource' => 'Posts',
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
