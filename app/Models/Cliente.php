<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $primaryKey = 'id';
    
    public $incrementing = false;

    protected $keyType = 'string';

    public function getSoportes()
    {
        return $this->hasMany('App\Models\Soporte', 'cliente_id', 'id');
    }
}