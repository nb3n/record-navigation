# Zero Configuration

Drop the two actions into `getHeaderActions()` and they work instantly. No config publishing, no trait, no method overrides.

---

## What This Feature Does

Both `PreviousRecordAction` and `NextRecordAction` are self-contained. They resolve the current record, find the adjacent one, and build the navigation URL entirely through Filament's standard `$livewire` injection API. Nothing needs to be added to the page class beyond the two action instances returned from `getHeaderActions()`.

---

## Installation

Install the package via Composer:

```bash
composer require nben/filament-record-nav
```

Laravel's package auto-discovery registers the service provider. The package merges its default config automatically, so publishing the config file is optional.

---

## Implementation

This is the complete implementation for zero-configuration navigation:

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

No other files need to be changed.

---

## What You Get for Free

Adding the two lines above gives you all of the following without any further work:

- Previous and next buttons in the page header with chevron icons
- Buttons ordered by `id` ascending (change via config if needed)
- Smart boundary detection: buttons turn gray and disable at the first or last record
- Navigation to the `view` page of the adjacent record
- One database query per action per render (query cache is built in)
- All standard Filament Action fluent methods available for customisation

---

## Default Config Values

The package ships with sensible defaults that are active even before you publish the config file:

```php
// config/filament-record-nav.php (defaults)
return [
    'order_column'       => 'id',
    'previous_direction' => 'desc',
    'next_direction'     => 'asc',
];
```

If these defaults suit your use case you never need to touch the config.

---

## When to Add More

Zero configuration covers the majority of use cases. You only need to do more when:

- You want to order by a column other than `id` globally: publish the config and change `order_column`.
- You want to navigate to the `edit` page instead of `view`: add `->navigateTo(NavigationPage::Edit)` on the action.
- You want to filter which records appear in the navigation set: add the `WithRecordNavigation` trait and override the query methods (see the Custom Query Logic document).
- You want to navigate to a custom page route: use `NavigationPage::custom('your-route-name')` (see the Custom Routes document).

---

## Notes

- The actions work on both `ViewRecord` and `EditRecord` page classes.
- Placing the actions in a different order inside `getHeaderActions()` changes which button appears on the left and which appears on the right in the header.
- All other actions already in `getHeaderActions()` (such as the built-in delete or edit actions) continue to work normally alongside the navigation actions.
