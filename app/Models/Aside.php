<?php

// app/Models/Aside.php
namespace App\Models;

use App\Modelo;
use Illuminate\Database\Eloquent\Model;

class Aside extends Model
{
    // app/Models/Aside.php
protected $fillable = ['modelo_id', 'nombre_aside', 'opciones', 'activo'];
protected $casts = [
    'opciones' => 'array',
    'activo' => 'boolean'
];

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}


