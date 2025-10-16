<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Slider extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Audit;

    protected $fillable = [
        'title',
        'link',
        'header',
        'details',
        'status',
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

}
