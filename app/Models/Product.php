<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Product extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Audit;

    protected $fillable = [
        'name',
        'slug',
        'sku', // Stock Keeping Unit
        'upc', // Universal Product Code
        'description',
        'details',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Generate missing identifiers
            if (empty($product->slug)) {
                $product->slug = static::generateIdentifier($product, 'slug');
            }
            if (empty($product->sku)) {
                $product->sku = static::generateIdentifier($product, 'sku');
            }
            if (empty($product->upc)) {
                $product->upc = static::generateIdentifier($product, 'upc');
            }
        });

        static::updating(function ($product) {
            // Regenerate slug if name or identifiers change
            if ($product->isDirty(['name', 'sku', 'upc'])) {
                $product->slug = static::generateIdentifier($product, 'slug');
            }
        });
    }

    protected static function generateIdentifier($product, $type)
    {
        $primaryCategory = $product->categories->firstWhere('pivot.is_primary', true);

        switch ($type) {
            case 'sku':
                $base = $primaryCategory
                    ? strtoupper(substr($primaryCategory->slug, 0, 3)) . '-'
                    : 'PROD-';
                $base .= strtoupper(Str::random(6));
                return static::makeUnique($product, 'sku', $base);

            case 'upc':
                $base = $primaryCategory
                    ? $primaryCategory->id . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT)
                    : mt_rand(100000, 999999);
                $base .= str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                return static::makeUnique($product, 'upc', $base);

            case 'slug':
            default:
                $parts = array_filter([$product->name, $product->sku, $product->upc]);
                if (empty($parts)) {
                    throw new \RuntimeException('Cannot generate slug - at least one of name, SKU or UPC must be provided');
                }

                $slugBase = implode('-', $parts);
                if ($primaryCategory) {
                    $slugBase = $primaryCategory->slug . '-' . $slugBase;
                }

                $slug = Str::slug($slugBase);
                return static::makeUnique($product, 'slug', $slug, $primaryCategory);
        }
    }

    protected static function makeUnique($product, $field, $base, $primaryCategory = null)
    {
        $query = static::withTrashed()
            ->where($field, 'like', "$base%")
            ->where('id', '!=', $product->id ?? 0);

        // For slugs, scope to same category if primary category exists
        if ($field === 'slug' && $primaryCategory) {
            $query->whereHas('categories', function ($q) use ($primaryCategory) {
                $q->where('categories.id', $primaryCategory->id);
            });
        }

        $count = $query->count();

        // For SKU/UPC, pad with leading zeros. For slug, append count
        return match ($field) {
            'sku' => $count ? sprintf("%s-%04d", $base, $count) : $base,
            'upc' => $count ? $base . str_pad($count, 4, '0', STR_PAD_LEFT) : $base,
            default => $count ? "{$base}-{$count}" : $base
        };
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->using(ProductCategory::class)
            ->withTimestamps()
            ->withPivot('status');
    }

    public function primaryCategory()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->wherePivot('is_primary', 1);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function stockProducts()
    {
        return $this->hasMany(ProductStock::class);
    }

}
