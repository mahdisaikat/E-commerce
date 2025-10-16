<?php

namespace App\Models;

use App\Enums\TagType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Tag extends Model implements Auditable
{
    use SoftDeletes, Audit;

    protected $fillable = ['name', 'slug', 'type', 'status'];

    protected $casts = [
        'type' => TagType::class, // Laravel will auto-convert int <-> enum
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = static::generateSlug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = static::generateSlug($tag->name);
            }
        });
    }

    protected static function generateSlug($name): string
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'like', "$slug%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type instanceof TagType ? $this->type->label() : 'Unknown';
    }

    public function scopeOfType($query, TagType $type)
    {
        return $query->where('type', $type->value);
    }

    public function productStocks()
    {
        return $this->belongsToMany(ProductStock::class, 'product_stock_tag');
    }

    public static function createOrFirst(array $data)
    {
        $query = static::withTrashed()
            ->where('name', $data['name'])
            ->where('type', $data['type']);

        $tag = $query->first();

        if ($tag) {
            if ($tag->trashed()) {
                $tag->restore();
                $tag->update($data);
            }
            return $tag;
        }

        return static::create($data);
    }

}
