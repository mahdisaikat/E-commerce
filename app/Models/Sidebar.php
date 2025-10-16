<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sidebar extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'label',
        'serial',
        'route',
        'parent_id',
        'permission_id',
        'icon',
        'status',
    ];

    public function mainMenu()
    {
        return $this->belongsTo(Sidebar::class, 'parent_id');
    }

    public function subMenu()
    {
        return $this->hasMany(Sidebar::class, 'parent_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

}
