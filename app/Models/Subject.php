<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Subject extends Model implements Auditable
{
    use SoftDeletes, Audit;

    protected $fillable = [
        'name',
        'section_id',
        'status',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

}
