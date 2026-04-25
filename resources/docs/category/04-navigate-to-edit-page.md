# Navigate to Edit Page

Control which Filament page type the next and previous actions redirect to. Each action can target a different page type independently.

---

## What This Feature Does

By default both actions navigate to the `view` page of the adjacent record. Passing `NavigationPage::Edit` to the `navigateTo()` method on either action changes the redirect target to the `edit` page instead. Each action is configured independently, so you can have one go to `view` while the other goes to `edit`.

---

## Installation

Install the package via Composer:

```bash
composer require nben/filament-record-nav
```

No further setup is required.

---

## Implementation

Import the `NavigationPage` enum alongside the two action classes:

```php
<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Resources\Pages\EditRecord;
use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;
use Nben\FilamentRecordNav\Enums\NavigationPage;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreviousRecordAction::make()->navigateTo(NavigationPage::Edit),
            NextRecordAction::make()->navigateTo(NavigationPage::Edit),
        ];
    }
}
```

Both actions now redirect to the `EditRecord` page of the adjacent record rather than the `ViewRecord` page.

---

## Mixing Page Types

Each action accepts its own `navigateTo()` call, so you can point them at different page types:

```php
protected function getHeaderActions(): array
{
    return [
        PreviousRecordAction::make()->navigateTo(NavigationPage::View),
        NextRecordAction::make()->navigateTo(NavigationPage::Edit),
    ];
}
```

In this example the previous button stays on `view` while the next button opens the `edit` page.

---

## Available NavigationPage Values

| Value                                  | Target page          | Filament route name        |
| -------------------------------------- | -------------------- | -------------------------- |
| `NavigationPage::View`                 | ViewRecord page      | `view`                     |
| `NavigationPage::Edit`                 | EditRecord page      | `edit`                     |
| `NavigationPage::custom('route-name')` | Any registered route | your key from `getPages()` |

For custom routes beyond `view` and `edit`, see the Custom Routes document.

---

## How It Works

The `navigateTo()` method stores the `NavigationPage` enum case on the action instance:

```php
public function navigateTo(NavigationPage|CustomNavigationPage $page): static
{
    $this->navigationPage = $page;
    return $this;
}
```

When the `url()` closure evaluates at render time, it passes the stored value to `resolveUrl()`:

```php
->url(function (Component $livewire): ?string {
    $record = $this->getCachedRecord('next', $livewire);
    if ($record === null) {
        return null;
    }
    return $this->resolveUrl($livewire, $record, $this->navigationPage);
})
```

`resolveUrl()` calls `Resource::getUrl($page->value, ['record' => $record])`. For `NavigationPage::Edit` the value is the string `'edit'`, which maps directly to the route key registered in your resource's `getPages()` array.
