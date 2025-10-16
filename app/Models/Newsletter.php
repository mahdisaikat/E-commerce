<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Newsletter extends Model implements Auditable
{
    use SoftDeletes, Audit;

    protected $fillable = [
        'name',
        'email',
        'token',
        'source',
        'confirmed_at',
        'unsubscribed_at',
        'ip_address',
        'status',
    ];


}
