<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'basic_salary',
        'allowances',
        'allowance_reason',
        'deductions',
        'deduction_reason',
        'net_salary',
        'payment_date',
        'status',
        'year',

    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
