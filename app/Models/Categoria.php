<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Producto;
use App\Modelo;

class Categoria extends Model
{
    public $timestamps = false;

    protected $appends = ['img_url'];

    public function Productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id', 'id')->where('pagina_web', 'SI');
    }
    public function getModelo()
    {
        return $this->hasMany(Modelo::class,'id','categoria_id')->where('activo', 'SI');
    }

    public function getImgUrlAttribute()
    {
        return $this->img_cat ? asset('storage/'.$this->img_cat) : null;
    }
}
