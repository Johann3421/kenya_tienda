<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Producto;
use App\Modelo;

class Categoria extends Model
{
    public $timestamps = false;

    public function Productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id', 'id')->where('pagina_web', 'SI');
    }
    public function getModelo()
    {
        return $this->hasMany(Modelo::class,'id','categoria_id')->where('activo', 'SI');
    }
}
