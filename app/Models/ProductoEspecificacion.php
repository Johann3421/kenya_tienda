<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoEspecificacion extends Model
{
    protected $table = 'producto_especificaciones';

    protected $fillable = [
        'producto_id',
        'clave',
        'etiqueta',
        'valor',
        'orden',
    ];

    public function producto()
    {
        return $this->belongsTo(\App\Producto::class, 'producto_id');
    }
}
