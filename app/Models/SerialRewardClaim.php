<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialRewardClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_attempt_id',
        'nombre',
        'email',
        'telefono',
        'codigo_premio',
        'claimed_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
    ];

    public function attempt()
    {
        return $this->belongsTo(SerialAttempt::class, 'serial_attempt_id');
    }
}
