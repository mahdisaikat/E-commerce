<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'module_name',
    ];

}
