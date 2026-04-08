<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\CategoryStatus;
use App\Models\Category;


class SelectParentCategoryTable extends CategoriesTable
{
    public static function configure(Table $table): Table
    {
        $table = parent::configure($table);

        return $table->modifyQueryUsing(function (Builder $query) use ($table): Builder {
            $arguments = $table->getArguments();

            $recordId = $arguments['record_id'] ?? null;

            return $query
                ->when($recordId, fn ($q) => $q->where('id', '!=', $recordId))
                ->selectableAsParent();
        });
    }
}