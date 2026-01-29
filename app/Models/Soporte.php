<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soporte extends Model
{
    protected $table = 'soportes';
    protected $primaryKey = 'id';

    protected $casts = [
        'piezas_retiradas_multiple' => 'json',
    ];

    public function getCliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id')->select('id', 'tipo', 'codigo_sunat', 'nombres', 'email', 'celular', 'direccion');
    }

    public function getDetalles()
    {
        return $this->hasMany('App\Models\DetallesSoporte', 'soporte_id', 'id')->select('id', 'soporte_id', 'descripcion', 'precio', 'descuento', 'cantidad', 'importe');
    }
}
