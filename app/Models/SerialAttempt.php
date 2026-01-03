<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Producto;

class SerialAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'serial',
        'device_id',
        'serial_reward_id',
        'serial_device_lock_id',
        'device_fingerprint',
        'client_ip',
        'user_agent',
        'attempt_number',
        'attempt_date',
    ];

    protected $casts = [
        'attempt_date' => 'date',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function reward()
    {
        return $this->belongsTo(SerialReward::class, 'serial_reward_id');
    }

    public function deviceLock()
    {
        return $this->belongsTo(SerialDeviceLock::class, 'serial_device_lock_id');
    }

    public function claim()
    {
        return $this->hasOne(SerialRewardClaim::class);
    }
}
