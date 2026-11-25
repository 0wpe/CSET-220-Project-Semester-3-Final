<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $table = 'Employee';
    protected $primaryKey = 'employee_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'department_id',
        'designation',
        'base_salary',
        'hourly_rate',
        'pay_type',
        'annual_leave_allowance',
    ];

    protected function casts(): array
    {
        return [
            'annual_leave_allowance' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id', 'employee_id');
    }

    public function payroll(): HasMany
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'employee_id');
    }

    public function shiftAssignments(): HasMany
    {
        return $this->hasMany(ShiftAssignment::class, 'employee_id', 'employee_id');
    }

    public function payChangeHistory(): HasMany
    {
        return $this->hasMany(PayChangeHistory::class, 'employee_id', 'employee_id');
    }
}
