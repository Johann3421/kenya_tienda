<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoPrecio extends Model
{
    protected $table = 'producto_precios';

    protected $fillable = [
        'producto_id',
        'tipo_cliente',
        'moneda',
        'precio',
        'incluye_igv',
    ];

    public function producto()
    {
        return $this->belongsTo(\App\Producto::class, 'producto_id');
    }
}
