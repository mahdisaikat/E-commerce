<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Designation extends Model implements Auditable 
{
    use SoftDeletes, Audit;

    protected $fillable = [
        'title',
        'description',
        'status',
    ];
}
