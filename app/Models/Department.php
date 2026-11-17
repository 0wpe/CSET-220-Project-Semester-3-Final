<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'Departments';
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    protected $fillable = [
        'department_name',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id', 'department_id');
    }
}
