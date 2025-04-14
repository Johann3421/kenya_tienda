<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    public function getCliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id')->select('id', 'tipo', 'codigo_sunat', 'nombres', 'email', 'celular', 'direccion');
    }

    public function getDetalles()
    {
        return $this->hasMany('App\Models\DetallesVenta', 'venta_id', 'id');
    }
}
