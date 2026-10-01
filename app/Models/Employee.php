<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'outlet_id',
        'employee_code',
        'name',
        'email',
        'phone',
        'position',
        'department',
        'join_date',
        'employment_status',
        'base_salary',
        'daily_allowance',
        'status',
    ];

    protected $casts = [
        'join_date' => 'date',
        'base_salary' => 'decimal:2',
        'daily_allowance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function schedules()
    {
        return $this->hasMany(EmployeeSchedule::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function kpis()
    {
        return $this->hasMany(EmployeeKpi::class);
    }
}
