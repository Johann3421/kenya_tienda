<?php

namespace App;

use App\Models\Aside;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Model;
use App\Producto;
use Illuminate\Support\Facades\Storage;

class Modelo extends Model
{
    protected $table = 'modelos';
    public $timestamps = false;

    public function Productos()
    {
        return $this->hasMany(Producto::class, 'modelo_id', 'id')->where('pagina_web', 'SI');
    }
    public function getCat()
    {
        return $this->hasMany(Categoria::class,'id','categoria_id')->where('activo', 'SI');
    }
    public function getProducto()
    {
        return $this->hasMany(Producto::class,'modelo_id','id');
    }
    // En app/Models/Modelo.php
public function asides()
{
    return $this->hasMany(Aside::class); // Asegúrate que es hasMany y no belongsToMany
}

    // Accessor: full public URL for image
    public function getImgUrlAttribute()
    {
        return $this->img_mod ? asset('storage/'.$this->img_mod) : null;
    }
}
