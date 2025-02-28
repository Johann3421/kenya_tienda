<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalles_pedidos';

    // protected $fillable = ['pedido_id', 'cantidad', 'descripcion', 'linea', 'modelo', 'marca', 'precio_unitario', 'total',
    //     'cantidad_proveedor', 'producto_id'];
}
