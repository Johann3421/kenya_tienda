<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    
    protected $primaryKey = 'id';

    public $timestamps = false;
    public function getModelo()
    {
        return $this->hasMany(Modelo::class,'id','categoria_id')->where('activo', 'SI');
    }
}
