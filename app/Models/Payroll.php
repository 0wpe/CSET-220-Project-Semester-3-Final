<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $table = 'Payroll';
    protected $primaryKey = 'payroll_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'payroll_month',
        'total_salary',
        'regular_hours',
        'overtime_hours',
        'gross_pay',
        'deductions',
        'net_pay',
        'is_processed',
    ];

    protected function casts(): array
    {
        return [
            'payroll_month' => 'date',
            'is_processed' => 'boolean',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
