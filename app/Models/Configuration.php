<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Configuration extends Model implements Auditable
{
    use HasFactory, SoftDeletes, Audit;

    protected $fillable = [
        'type',
        'key',
        'value',
        'remarks',
        'status',
    ];
}
