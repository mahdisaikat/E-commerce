<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Audit;

class IncomeSource extends Model implements Auditable
{
    use SoftDeletes, Audit;

    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * Get the incomes associated with the income source.
     */
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}

