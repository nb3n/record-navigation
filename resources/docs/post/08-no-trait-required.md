# No Trait Required

The navigation actions work without adding any trait to your page class. The trait is available for customisation but is never a prerequisite.

---

## What This Feature Does

In version 1 of this package the `WithRecordNavigation` trait was required on every page that used the actions. In version 2 it is fully optional. The actions wire themselves up through Filament's `$livewire` injection parameter at render time, so no lifecycle hook or trait is needed on the page class for the default behaviour.

---

## Installation

Install the package via Composer:

```bash
composer require nben/filament-record-nav
```

No further setup is required.

---

## Implementation Without the Trait

The following is a complete, working page with no trait:

```php
<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make(),
            NextRecordAction::make(),
        ];
    }
}
```

There is no `use WithRecordNavigation` line and no `implements HasRecordNavigation` declaration. The actions still navigate between records, detect boundaries, and cache queries correctly.

---

## How It Works Without the Trait

When a closure on the action receives the `$livewire` component instance, the action calls `method_exists($livewire, 'getPreviousRecord')` to check whether a custom method is available. If that check returns `false` (because no trait or direct definition is present), the action falls back to `defaultAdjacentQuery()`:

```php
$record = method_exists($livewire, $method)
    ? $livewire->{$method}()
    : $this->defaultAdjacentQuery($livewire, $direction);
```

`defaultAdjacentQuery()` reads `order_column` and sort direction from the config and builds a standard Eloquent query. This path requires nothing from the page class at all.

Similarly, URL resolution calls `method_exists($livewire, 'getRecordNavigationUrl')` first and falls back to calling `getResource()::getUrl()` directly on the Livewire component if that method is absent.

---

## When to Add the Trait

The `WithRecordNavigation` trait exists for one reason: to give you a place to override the navigation logic. Add it only when one of the following applies:

- You need to filter which records appear in the navigation set (for example, only published posts).
- You need to order by a column other than the global `order_column` config value.
- You need to scope navigation to a tenant, team, or related model.
- You want to control the URL the action redirects to beyond what `NavigationPage` enum provides.

For any page where the global config ordering is sufficient, leave the trait out.

---

## Comparison: With and Without the Trait

| Aspect | Without trait | With trait |
|---|---|---|
| Works out of the box | Yes | Yes |
| Uses config for ordering | Yes | Yes (unless overridden) |
| Custom query filtering | No | Yes, via getPreviousRecord / getNextRecord |
| Custom URL | No | Yes, via getRecordNavigationUrl |
| Required for basic navigation | No | No |

---

## Notes

- Removing the trait from a page that previously used it in v1 is safe as long as you did not override any methods. If you did override methods, keep the trait and the overrides.
- The `HasRecordNavigation` interface is also entirely optional. It exists only for IDE autocompletion and static analysis (PHPStan, Psalm). The actions never check `instanceof` against it.