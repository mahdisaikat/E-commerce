<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Exam extends Model implements Auditable {
    use SoftDeletes, Audit;

    protected $fillable = [
        'name',
        'exam_date',
        'status',
    ];

    protected $casts = ['exam_date' => 'date'];

}

