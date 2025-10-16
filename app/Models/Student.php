<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Student extends Model implements Auditable {
    use SoftDeletes, Audit;

    protected $fillable = [
        'student_id',
        'name_bn',
        'name_en',
        'section_id',
        'father_name',
        'father_phone',
        'father_occupation',
        'mother_name',
        'mother_phone',
        'mother_occupation',
        'permanent_address',
        'present_address',
        'dob',
        'birth_certificate_no',
        'religion',
        'gender',
        'class_applied',
        'nationality',
        'fee_waiver',
        'roll',
        'shift',
        'email',
        'phone',
        'transport_type',
        'monthly_fee',
        'status',
    ];

    protected $casts = [
        'fee_waiver' => 'boolean',
        'dob' => 'date',
        'monthly_fee' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public static function generateStudentId()
    {
        $prefix = 'STU'; // or any prefix you want
        $latest = self::latest('id')->first();

        if (!$latest)
        {
            $number = 1;
        } else
        {
            // Get last numeric part
            $number = (int) substr($latest->student_id, 3) + 1;
        }

        // Format as 6-digit number: STU000001
        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

}
