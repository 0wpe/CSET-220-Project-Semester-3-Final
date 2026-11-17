<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $table = 'Shifts';
    protected $primaryKey = 'shift_id';
    public $timestamps = false;

    protected $fillable = [
        'schedule_id',
        'shift_date',
        'start_time',
        'end_time',
        'break_minutes',
    ];

    protected function casts(): array
    {
        return [
            'shift_date' => 'date',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'schedule_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ShiftAssignment::class, 'shift_id', 'shift_id');
    }
}
