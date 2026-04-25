# Next and Previous Buttons

Navigate seamlessly between records using two header actions that drop into any Filament resource page.

---

## What This Feature Does

Adding `PreviousRecordAction` and `NextRecordAction` to a `ViewRecord` or `EditRecord` page gives users two navigation buttons in the page header. Clicking either button redirects the browser to the adjacent record, ordered by the column defined in the package config (default: `id`).

---

## Installation

Install the package via Composer:

```bash
composer require nben/filament-record-nav
```

No further setup is required. The package registers its service provider automatically through Laravel's package discovery.

---

## Implementation

Open the `ViewRecord` (or `EditRecord`) page class for the resource you want to add navigation to. Import the two action classes, then return them from `getHeaderActions()`.

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

That is the entire integration. No trait is required on the page class and no config changes are needed.

---

## How It Works

When the page renders, Filament evaluates each action. The package reads the `order_column` value from its config file (default: `id`) and queries the database for the record immediately before or after the current one. The result is used to set the button color, its enabled or disabled state, and the URL it points to.

---

## Default Behaviour

- Records are ordered by the `id` column ascending.
- Both buttons render as outlined primary buttons with chevron icons.
- Clicking `PreviousRecordAction` navigates to the `view` page of the preceding record.
- Clicking `NextRecordAction` navigates to the `view` page of the following record.
- When there is no adjacent record the button turns gray and becomes unclickable. See the Smart Boundaries document for more detail on that behaviour.

---

## Customising Button Appearance

Both actions extend Filament's `Action` class, so every fluent method from the Filament Actions docs works as normal:

```php
PreviousRecordAction::make()
    ->label('Previous')
    ->color('secondary')
    ->tooltip('Go to previous record')
    ->keyBindings(['mod+left']),

NextRecordAction::make()
    ->label('Next')
    ->icon('heroicon-o-arrow-right')
    ->color('secondary')
    ->tooltip('Go to next record')
    ->keyBindings(['mod+right']),
```

---

## Configuration

Publish the config file to change the ordering column or sort directions:

```bash
php artisan vendor:publish --tag=filament-record-nav-config
```

This creates `config/filament-record-nav.php`:

```php
return [
    'order_column'       => 'id',
    'previous_direction' => 'desc',
    'next_direction'     => 'asc',
];
```

Change `order_column` to any indexed column on your table, for example `created_at` or `sort_order`.
