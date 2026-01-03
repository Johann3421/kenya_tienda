<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialDeviceLock extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_hash',
        'serial',
        'user_agent',
        'ip_address',
        'locked_at',
        'last_attempt_at',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
        'last_attempt_at' => 'datetime',
    ];

    public function attempts()
    {
        return $this->hasMany(SerialAttempt::class);
    }
}
