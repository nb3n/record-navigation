# Custom Query Logic

Override the navigation query entirely on a per-page basis to implement any ordering, filtering, or relationship logic your use case requires.

---

## What This Feature Does

The default query finds the nearest record with a lower or higher value in the `order_column` (default: `id`). When that is not sufficient, you can override `getPreviousRecord()` and `getNextRecord()` on the page class to supply any Eloquent query you need. The actions detect and call your overrides automatically.

---

## Installation

Install the package via Composer:

```bash
composer require nben/filament-record-nav
```

No further setup is required.

---

## Implementation

Add the `WithRecordNavigation` trait to your page class and override the two navigation methods:

```php
<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;

class ViewParentCategory extends ViewRecord
{
    use WithRecordNavigation;

    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make(),
            NextRecordAction::make(),
        ];
    }

    // Only navigate through top-level categories (no parent)
    public function getPreviousRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->whereNull('parent_id')
            ->where('id', '<', $this->getRecord()->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getNextRecord(): ?Model
    {
        return $this->getRecord()
            ->newQuery()
            ->whereNull('parent_id')
            ->where('id', '>', $this->getRecord()->id)
            ->orderBy('id', 'asc')
            ->first();
    }
}
```

---

## Registering a Custom Page Route

If you create a dedicated page class for this view, register it in the resource:

```php
public static function getPages(): array
{
    return [
        'index'           => ListCategories::route('/'),
        'create'          => CreateCategory::route('/create'),
        'edit'            => EditCategory::route('/{record}/edit'),
        'parent-category' => ViewParentCategory::route('/{record}/parent'),
    ];
}
```

---

## Overriding the Navigation URL

Override `getRecordNavigationUrl()` to fully control where the action redirects after finding the adjacent record:

```php
use Illuminate\Database\Eloquent\Model;
use Nben\FilamentRecordNav\Enums\CustomNavigationPage;
use Nben\FilamentRecordNav\Enums\NavigationPage;

public function getRecordNavigationUrl(
    Model $record,
    NavigationPage|CustomNavigationPage $page
): string {
    // Always navigate to the edit page, ignoring the action's NavigationPage setting
    return static::getResource()::getUrl('edit', ['record' => $record]);
}
```

Or respect whichever page the action was configured with:

```php
public function getRecordNavigationUrl(
    Model $record,
    NavigationPage|CustomNavigationPage $page
): string {
    // $page->value is 'view', 'edit', or a custom route name
    return static::getResource()::getUrl($page->value, ['record' => $record]);
}
```

---

## How Detection Works

The actions use `method_exists()` rather than `instanceof` to detect your overrides:

```php
$method = $direction === 'previous' ? 'getPreviousRecord' : 'getNextRecord';

$record = method_exists($livewire, $method)
    ? $livewire->{$method}()
    : $this->defaultAdjacentQuery($livewire, $direction);
```

This works whether the method comes from the `WithRecordNavigation` trait, a direct definition on the page class, or an explicit `implements HasRecordNavigation` declaration. The interface is optional and exists only for IDE support and static analysis.

---

## Method Signatures

Your overrides must match these exact signatures for the actions to detect and call them:

```php
public function getPreviousRecord(): ?Model
public function getNextRecord(): ?Model
public function getRecordNavigationUrl(
    Model $record,
    NavigationPage|CustomNavigationPage $page
): string
```

Make sure `use Illuminate\Database\Eloquent\Model` is imported at the top of the file.

---

## Optional: Strict Typing with the Contract

If you use PHPStan, Psalm, or a strict IDE, declare the interface alongside the trait:

```php
use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;
use Nben\FilamentRecordNav\Contracts\HasRecordNavigation;

class ViewParentCategory extends ViewRecord implements HasRecordNavigation
{
    use WithRecordNavigation;
}
```

The interface declares the three methods above and gives static analysis tools a concrete type to verify against. The actions themselves do not check `instanceof` and will work correctly whether or not you add `implements`.