# Optional Overrides and Custom Routes

Add the trait only when customisation is needed, and use `NavigationPage::custom()` to navigate to any route registered in your resource's `getPages()` array.

---

## What This Feature Does

Two extension points are available when the defaults are not enough. First, the `WithRecordNavigation` trait lets you override the navigation query and URL logic on a per-page basis. Second, `NavigationPage::custom()` lets you point the actions at a route beyond the built-in `view` and `edit` cases. Both are opt-in and can be used independently.

---

## Installation

Install the package via Composer:

```bash
composer require nben/filament-record-nav
```

No further setup is required.

---

## Using NavigationPage::custom()

Register your custom page in the resource's `getPages()` array:

```php
// In your Resource class
public static function getPages(): array
{
    return [
        'index'          => ListPosts::route('/'),
        'create'         => CreatePost::route('/create'),
        'view'           => ViewPost::route('/{record}'),
        'edit'           => EditPost::route('/{record}/edit'),
        'published-view' => ViewPublishedPost::route('/{record}/published'),
    ];
}
```

Then pass the route name string to `NavigationPage::custom()` on the action:

```php
<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Enums\NavigationPage;

class ViewPublishedPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()->navigateTo(NavigationPage::custom('published-view')),
            NextRecordAction::make()->navigateTo(NavigationPage::custom('published-view')),
        ];
    }
}
```

The string passed to `custom()` must exactly match a key in `getPages()`, including hyphens and casing.

---

## Adding Query Overrides on the Same Page

Combine `NavigationPage::custom()` with the `WithRecordNavigation` trait to scope both the query and the destination page:

```php
<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Enums\PostStatus;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;
use Nben\FilamentRecordNav\Enums\NavigationPage;

class ViewPublishedPost extends ViewRecord
{
    use WithRecordNavigation;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()->navigateTo(NavigationPage::custom('published-view')),
            NextRecordAction::make()->navigateTo(NavigationPage::custom('published-view')),
        ];
    }

    public function getPreviousRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->where('status', PostStatus::Published)
            ->where('id', '<', $this->getRecord()->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getNextRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->where('status', PostStatus::Published)
            ->where('id', '>', $this->getRecord()->id)
            ->orderBy('id', 'asc')
            ->first();
    }
}
```

---

## How NavigationPage::custom() Works

`NavigationPage::custom()` is a named constructor on the `NavigationPage` enum that returns a `CustomNavigationPage` value object:

```php
public static function custom(string $routeName): CustomNavigationPage
{
    return new CustomNavigationPage($routeName);
}
```

`CustomNavigationPage` is a simple final class with a single `readonly string $value` property. It shares the same `->value` interface as the `NavigationPage` backed enum, so `resolveUrl()` can call `$page->value` on either type without any `instanceof` branching:

```php
protected function resolveUrl(
    Component $livewire,
    Model $record,
    NavigationPage|CustomNavigationPage $page
): string {
    // ...
    return $livewire::getResource()::getUrl($page->value, ['record' => $record]);
}
```

The string `'published-view'` from `NavigationPage::custom('published-view')` is passed directly to `Resource::getUrl()`, which looks it up in your resource's `getPages()` array and generates the correct URL.

---

## Overriding getRecordNavigationUrl() for Full URL Control

If you need complete control over the URL regardless of what was passed to `navigateTo()`, override `getRecordNavigationUrl()` in the trait:

```php
use Illuminate\Database\Eloquent\Model;
use Nben\FilamentRecordNav\Enums\CustomNavigationPage;
use Nben\FilamentRecordNav\Enums\NavigationPage;

public function getRecordNavigationUrl(
    Model $record,
    NavigationPage|CustomNavigationPage $page
): string {
    // Use $page->value if you want to respect the action's navigateTo() setting
    return static::getResource()::getUrl($page->value, ['record' => $record]);
}
```

---

## Troubleshooting

**`InvalidArgumentException` or route not found**

The route name string passed to `custom()` must exactly match a key in `getPages()`. Check hyphens, underscores, and casing. For example, `'published-view'` in `getPages()` must be written as `NavigationPage::custom('published-view')`, not `NavigationPage::custom('publishedView')`.

**Smart boundaries not working on a custom route page**

Boundary detection relies on `getPreviousRecord()` and `getNextRecord()`. If you are on a scoped custom page, override those methods with the same scope condition so the boundary check is consistent with the navigation query. See the Scoped Navigation document for examples.