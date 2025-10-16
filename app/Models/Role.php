<?php

namespace App\Models;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    protected $fillable = [
        'name',
        'display_name',
        'guard_name'
    ];

}
