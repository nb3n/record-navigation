# Smart Boundaries

Buttons automatically disable and turn gray at the first and last record, giving users a clear visual signal that there is nowhere further to navigate.

---

## What This Feature Does

When the current record is the first in the ordered set, the `PreviousRecordAction` button turns gray and becomes unclickable. When the current record is the last, the `NextRecordAction` button does the same. No configuration is needed; this behaviour is built into both actions.

---

## Installation

Install the package via Composer:

```bash
composer require nben/filament-record-nav
```

No further setup is required. The package registers its service provider automatically through Laravel's package discovery.

---

## Implementation

Add both actions to `getHeaderActions()` on any `ViewRecord` or `EditRecord` page:

```php
<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;

class ViewUser extends ViewRecord
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

To see the boundary behaviour directly, navigate to the first record in the list. The `PreviousRecordAction` button will render gray and will not respond to clicks. Navigate to the last record and the `NextRecordAction` button will do the same.

---

## How It Works

Each action registers three closures during setup: `color()`, `disabled()`, and `url()`. All three call the internal `getCachedRecord()` method, which queries the database for the adjacent record and caches the result for the rest of the render cycle.

```php
// Inside the action's setUp() method (simplified)
->color(function (Component $livewire): string {
    return $this->getCachedRecord('next', $livewire) ? 'primary' : 'gray';
})
->disabled(function (Component $livewire): bool {
    return $this->getCachedRecord('next', $livewire) === null;
})
->url(function (Component $livewire): ?string {
    $record = $this->getCachedRecord('next', $livewire);
    if ($record === null) {
        return null;
    }
    return $this->resolveUrl($livewire, $record, $this->navigationPage);
})
```

When `getCachedRecord()` returns `null` (no adjacent record exists), the button turns gray via the `color()` closure and is disabled via the `disabled()` closure. The `url()` closure returns `null`, so no broken href is added to the rendered HTML.

---

## Behaviour at Each Boundary

| Position      | PreviousRecordAction       | NextRecordAction           |
| ------------- | -------------------------- | -------------------------- |
| First record  | Gray, disabled, no href    | Primary, enabled, href set |
| Middle record | Primary, enabled, href set | Primary, enabled, href set |
| Last record   | Primary, enabled, href set | Gray, disabled, no href    |
| Only record   | Gray, disabled, no href    | Gray, disabled, no href    |

---

## Notes

- The boundary is determined by the `order_column` defined in `config/filament-record-nav.php` (default: `id`). If you have only one record in the database, both buttons will be gray simultaneously.
- If you use a custom query via the `WithRecordNavigation` trait (see the Custom Query Logic document), boundaries are still detected automatically based on whether your `getPreviousRecord()` or `getNextRecord()` method returns `null`.
