<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class Expense extends Model implements Auditable {
    use SoftDeletes, Audit;

    protected $fillable = [
        'amount',
        'description',
        'expense_purpose_id',
        'date',
        'status',
    ];

    public function expensePurpose()
    {
        return $this->belongsTo(ExpensePurpose::class);
    }
}
