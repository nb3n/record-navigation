<?php

namespace App\Models;

use App\Enums\CategoryStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'parent_id',
    ];

    protected $casts = [
        'status' => CategoryStatus::class,
    ];

    /**
     * Booted model events.
     */
    protected static function booted(): void
    {
        static::saving(function (Category $category): void {
            if ($category->parent_id) {
                $count = self::query()
                    ->where('id', $category->parent_id)
                    ->whereHas('parent.parent.parent')
                    ->count();

                if ($count > 0) {
                    throw new \Exception('Maximum category depth of 3 exceeded.');
                }
            }
        });
    }

    /**
     * Posts associated with this category.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_category');
    }

    /**
     * Parent category (nullable).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Scope: only categories that can be selected as parent.
     * Filters out:
     * 1. Categories that would exceed depth = 3
     * 2. Self and descendants to avoid cycles
     */
    public function scopeSelectableAsParent(Builder $query, ?int $childId = null): Builder
    {
        $query = $query->whereDoesntHave('parent.parent.parent');

        if ($childId) {
            $query->where('id', '!=', $childId);

            $query->whereNotIn('id', function ($q) use ($childId) {
                $q->select('id')
                  ->from('categories')
                  ->where('parent_id', $childId);
            });
        }

        return $query;
    }
}