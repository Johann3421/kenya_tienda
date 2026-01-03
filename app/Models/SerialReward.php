<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'attempt_threshold',
        'available_from',
        'is_active',
    ];

    protected $casts = [
        'available_from' => 'date',
        'is_active' => 'boolean',
    ];

    public function attempts()
    {
        return $this->hasMany(SerialAttempt::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
