<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class ExpensePurpose extends Model implements Auditable
{
    use SoftDeletes, Audit;

    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Get the expenses associated with the expense purpose.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
