<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'Employee';
    protected $primaryKey = 'employee_id';
    public $timestamps = false; // Set to true if you have created_at/updated_at

    protected $fillable = [
        'user_id',
        'department_id',
        'designation',
        'base_salary',
        'hourly_rate',
        'pay_type',
    ];

    // Inverse relationship: An employee belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship to the department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
