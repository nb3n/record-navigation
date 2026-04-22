# Single Query Resolution

Each action fires exactly one database query per render cycle, regardless of how many closures consume the result.

---

## What This Feature Does

When a Filament page renders, each action evaluates three closures: `color()`, `disabled()`, and `url()`. Without caching, each closure would independently query the database for the adjacent record, producing three queries per action and six per page render. This package caches the result of the first query and reuses it for the remaining closures, keeping the total at one query per action per render.

---

## Installation

Install the package via Composer:

```bash
composer require nben/filament-record-nav
```

No further setup is required. The caching behaviour is built in and active by default.

---

## Implementation

The single query behaviour requires no code changes beyond adding the actions to your page. The cache is internal to the `ResolvesAdjacentRecord` trait used by both action classes.

```php
<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;

class ViewVerifiedUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make(),
            NextRecordAction::make(),
        ];
    }
}
```

---

## How It Works

The cache lives in a private array property on the action instance, keyed by direction and the `spl_object_id` of the Livewire component:

```php
private array $resolvedRecordCache = [];

protected function getCachedRecord(string $direction, Component $livewire): ?Model
{
    $cacheKey = $direction . '-' . spl_object_id($livewire);

    if (array_key_exists($cacheKey, $this->resolvedRecordCache)) {
        return $this->resolvedRecordCache[$cacheKey];
    }

    // Query runs only on the first call
    $record = method_exists($livewire, $method)
        ? $livewire->{$method}()
        : $this->defaultAdjacentQuery($livewire, $direction);

    return $this->resolvedRecordCache[$cacheKey] = $record;
}
```

On the first closure call the record is resolved and stored. The second and third closures read directly from the array. The `spl_object_id` key ensures the cache is scoped to a single Livewire component instance, so having both `PreviousRecordAction` and `NextRecordAction` on the same page does not cause cache collisions between them.

---

## Query Count Per Page Render

| Scenario | Queries without caching | Queries with caching |
|---|---|---|
| One action on the page | 3 | 1 |
| Both actions on the page | 6 | 2 |
| Custom query via trait | 3 per action | 1 per action |

---

## Notes

- The cache is scoped to a single render cycle. It does not persist between requests.
- Custom query overrides defined in `getPreviousRecord()` or `getNextRecord()` on the page class are also cached. The cache wraps the method call regardless of where the record resolution logic lives.
- On large tables, add a database index on the `order_column` (default: `id`) for best performance. The index is independent of the caching mechanism but significantly reduces query time.