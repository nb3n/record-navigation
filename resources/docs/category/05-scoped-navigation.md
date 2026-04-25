# Scoped Navigation

Restrict navigation so that the next and previous buttons only move through a filtered subset of records, such as those with a specific status.

---

## What This Feature Does

The default navigation query iterates over all records ordered by the `order_column` from config. Scoped navigation overrides that query on the page class so that only records matching a given condition (for example `status = active`) are included in the navigation set. Records outside the scope are skipped entirely.

---

## Installation

Install the package via Composer:

```bash
composer require nben/filament-record-nav
```

No further setup is required.

---

## Implementation

Add the `WithRecordNavigation` trait to the page class and override `getPreviousRecord()` and `getNextRecord()` with queries that include your scope condition.

```php
<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Enums\CategoryStatus;
use App\Filament\Resources\CategoryResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;

class ViewActiveCategory extends ViewRecord
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
```

---

## Registering the Page in the Resource

If this is a custom page rather than the standard view or edit page, register it in the resource's `getPages()` method:

```php
public static function getPages(): array
{
    return [
        'index'           => ListCategories::route('/'),
        'create'          => CreateCategory::route('/create'),
        'edit'            => EditCategory::route('/{record}/edit'),
        'active-category' => ViewActiveCategory::route('/{record}/active'),
    ];
}
```

You can then link to it using `CategoryResource::getUrl('active-category', ['record' => $record])`.

---

## How It Works

When the actions render, they call `getCachedRecord()` from the `ResolvesAdjacentRecord` trait. That method checks whether the page class defines `getPreviousRecord()` or `getNextRecord()` using `method_exists()`. When those methods exist (because you added the `WithRecordNavigation` trait and overrode them), the custom queries run instead of the config fallback.

The smart boundary detection continues to work automatically. If the current active record is the first or last in the active set, the corresponding button turns gray and disables.

---

## Common Scoping Patterns

Filtering by status:

```php
->where('status', PostStatus::Published)
```

Filtering by a relationship or tenant:

```php
->where('team_id', auth()->user()->team_id)
```

Ordering by a non-ID column:

```php
->where('published_at', '<', $this->getRecord()->published_at)
->orderBy('published_at', 'desc')
```

Any Eloquent query builder expression is valid inside the override methods.

---

## Notes

- The trait is optional. Only add it when you need custom query logic. For pages that should navigate all records without filtering, the actions work without the trait.
- Both `getPreviousRecord()` and `getNextRecord()` must be overridden together if you add a scope. Overriding only one will produce inconsistent results where one direction respects the filter and the other does not.
