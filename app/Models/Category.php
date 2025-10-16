<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Category extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Audit;

    const TYPE_PRODUCT = 1;
    const TYPE_BLOG = 2;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'type',
        'status',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories')
        ->using(ProductCategory::class)
            ->withTimestamps()
            ->withPivot('status');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function isProductCategory()
    {
        return $this->type === self::TYPE_PRODUCT;
    }

    public function isBlogCategory()
    {
        return $this->type === self::TYPE_BLOG;
    }

    // Boot method to handle slug generation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = static::generateSlug($category);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty(['name', 'parent_id'])) {
                $category->slug = static::generateSlug($category);
            }
        });
    }

    // Helper to generate a unique slug
    protected static function generateSlug($category)
    {
        $slugBase = $category->name;

        // Include parent category in the slug if it exists
        if ($category->parent) {
            $slugBase = $category->parent->slug . '-' . $slugBase;
        }

        $slug = Str::slug($slugBase);

        // Ensure the slug is unique, including soft-deleted rows
        $count = static::withTrashed()
            ->where('slug', 'like', "$slug%")
            ->where('id', '!=', $category->id) // Exclude the current category during updates
            ->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}

