<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'payroll_code',
        'base_salary',
        'allowance',
        'overtime_pay',
        'bonus',
        'deductions',
        'net_salary',
        'status',
        'paid_at',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'base_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
