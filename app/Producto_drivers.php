<?php

namespace App;
use App\Producto;
use Illuminate\Database\Eloquent\Model;

class Producto_drivers extends Model
{
    //
    protected $table = 'producto_drivers';

    public $timestamps = false;

    public function Productos()
    {
        return $this->hasMany(Producto::class, 'id','producto_id')->where('pagina_web', 'SI');
    }
}
