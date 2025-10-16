<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Result extends Model implements Auditable {
    use SoftDeletes, Audit;

    protected $fillable = [
        'student_id',
        'exam_id',
        'subject_id',
        'marks',
        'grade',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

}
