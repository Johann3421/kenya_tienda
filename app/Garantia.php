<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Garantia extends Model
{
    protected $table = 'garantia';

    public $timestamps = false;


    public function getProductos()
    {
        return $this->hasMany(Producto::class,'id','producto_id');
    }
    public function getManuales()
    {
        return $this->hasOne(Producto::class,'id','producto_id')->with('getManual');
    }
    public function getDriversprod()
    {
        return $this->hasOne(Producto::class,'id','producto_id')->with('getDrivers');
    }
}
