<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'Schedules';
    protected $primaryKey = 'schedule_id';
    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'schedule_month',
        'status',
    ];

    protected $casts = [
        'schedule_month' => 'date',
    ];

    // Relationship: A schedule belongs to an Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }
}
