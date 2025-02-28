<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    // protected $fillable = ['user_id', 'cliente_id', 'tipo_comprobante', 'serie', 'numeracion', 'total',
    //     'acuenta', 'observacion'];

    public function getCliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id')->select('id', 'tipo', 'codigo_sunat', 'nombres', 'email', 'celular', 'direccion');
    }

    public function getProveedor()
    {
        return $this->hasOne('App\Models\Proveedor', 'id', 'proveedor_id')->select('id', 'numero_documento', 'nombres', 'email', 'telefono', 'direccion');
    }

    public function getProveedorPedido()
    {
        return $this->hasMany('App\Models\ProveedorPedido', 'pedido_id', 'id');
    }

    public function getDetalles()
    {
        return $this->hasMany('App\DetallePedido', 'pedido_id', 'id')->select('id', 'pedido_id', 'descripcion', 'precio', 'cantidad', 'importe');
    }
}
