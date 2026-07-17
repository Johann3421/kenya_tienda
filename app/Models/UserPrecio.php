<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserPrecio extends Authenticatable
{
    use Notifiable;

    protected $table = 'users_precios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dni',
        'nombres',
        'ape_paterno',
        'ape_materno',
        'telefono',
        'email',
        'username',
        'password',
        'activo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Helper to get full name (RUC format compat)
     */
    public function getNameAttribute()
    {
        return trim($this->nombres . ' ' . $this->ape_paterno . ' ' . $this->ape_materno);
    }
}
