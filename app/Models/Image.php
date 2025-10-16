<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Image extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Audit;

    protected $fillable = [
        'filename',
        'type',
        'imageable_id',
        'imageable_type',
        'width',
        'height',
        'size',
        'mime_type'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/images/' . $this->filename);
    }

}
