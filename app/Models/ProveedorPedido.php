<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProveedorPedido extends Model
{
    public function getProveedor()
    {
        return $this->hasOne('App\Models\Proveedor', 'id', 'proveedor_id')->select('id', 'numero_documento', 'nombres', 'email', 'telefono', 'direccion');
    }
}
