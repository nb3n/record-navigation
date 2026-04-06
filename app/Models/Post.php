<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'cover_image',
        'status',
        'published_at',
        'scheduled_at',
        'is_featured',
        'views_count',
    ];

    protected $casts = [
        'status' => PostStatus::class,
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * Categories associated with this post.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }
    
    /**
     * Authors of this post.
     */
    public function authors()
    {
        return $this->belongsToMany(User::class, 'post_user')
            ->withPivot(['role', 'is_primary'])
            ->withTimestamps();
    }
}
