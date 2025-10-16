<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Post extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Audit;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'slug',
        'datetime',
        'status',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateSlug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = static::generateSlug($post->title);
            }
        });
    }

    // Helper to generate a unique slug
    protected static function generateSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', "$slug%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
