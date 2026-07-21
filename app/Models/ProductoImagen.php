<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoImagen extends Model
{
    protected $table = 'producto_imagenes';

    protected $fillable = [
        'producto_id',
        'url',
        'orden',
        'es_principal',
    ];

    public function producto()
    {
        return $this->belongsTo(\App\Producto::class, 'producto_id');
    }
}
