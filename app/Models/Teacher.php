<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Teacher extends Model implements Auditable 
{
    use SoftDeletes, Audit;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'designation_id',
        'address',
        'status',
    ];

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
