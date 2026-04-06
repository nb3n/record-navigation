<?php

namespace App\Models;

use App\Enums\CategoryStatus;
use Illuminate\Database\Eloquent\Model;
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

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}