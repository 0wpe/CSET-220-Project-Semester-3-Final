<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    protected $table = 'Admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
    ];

    // Inverse relationship: An admin belongs to a user
    public function user()
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship to schedules created by this admin
    public function schedules()
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'admin_id', 'admin_id');
    }
}
