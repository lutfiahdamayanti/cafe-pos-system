<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeKpi extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period_month',
        'period_year',
        'attendance_score',
        'service_speed_score',
        'sop_compliance_score',
        'teamwork_score',
        'final_score',
        'grade',
        'evaluated_by',
        'evaluation_notes',
    ];

    protected $casts = [
        'attendance_score' => 'decimal:2',
        'service_speed_score' => 'decimal:2',
        'sop_compliance_score' => 'decimal:2',
        'teamwork_score' => 'decimal:2',
        'final_score' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
