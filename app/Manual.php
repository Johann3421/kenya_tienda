<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    protected $table = 'manual';

    public $timestamps = false;

    public function Productos()
    {
        return $this->hasMany(Producto::class, 'id','producto_id')->where('pagina_web', 'SI');
    }
}
