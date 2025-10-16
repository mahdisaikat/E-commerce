<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Income extends Model implements Auditable
{
    use SoftDeletes, Audit;

    protected $fillable = [
        'student_id',
        'income_source_id',
        'amount',
        'session',
        'date',
        'invoice_no',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function incomeSource()
    {
        return $this->belongsTo(IncomeSource::class);
    }

}
