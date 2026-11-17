<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayChangeHistory extends Model
{
    protected $table = 'PayChangeHistory';
    protected $primaryKey = 'history_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'old_pay_type',
        'new_pay_type',
        'old_hourly_rate',
        'new_hourly_rate',
        'changed_by',
        'old_base_salary',
        'new_base_salary',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'changed_by', 'admin_id');
    }
}
