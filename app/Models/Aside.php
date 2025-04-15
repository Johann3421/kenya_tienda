<?php

// app/Models/Aside.php
namespace App\Models;

use App\Modelo;
use App\Producto;
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
    // En el modelo Aside.php
// App\Models\Aside.php
public function productos()
{
    return $this->belongsToMany(\App\Producto::class, 'producto_filtros', 'aside_id', 'producto_id')
                ->withPivot('opcion')
                ->withTimestamps();
}

}


