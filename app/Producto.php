<?php

namespace App;

use App\Models\Aside;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Especificacion;
use App\Models\Marca;
use App\Producto_drivers;

class Producto extends Model
{

    protected $fillable = [
        // otros campos existentes...
        'Tipo de suministro',
        'Modelo',
        'Color',
        'Descripción',
        'Rendimiento',
        'Garantia',
        'Sistema RAEE',
        'Certificaciones',
        'Empaque',
        'Unidad',
        'Número de parte',
        'Dimensiones',
    ];
    protected $table = 'productos';

    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, 'id', 'categoria_id');
    }

    public function getMarca()
    {
        return $this->hasOne(Marca::class, 'id', 'marca_id');
    }
    public function getDrivers()
    {
        return $this->hasMany(Producto_drivers::class,'producto_id','id');
    }
    public function getManual()
    {
        return $this->hasMany(Manual::class,'producto_id','id');
    }
    public function getGarantia()
    {
        return $this->hasMany(Garantia::class,'producto_id','id');
    }
    public function getModelo()
    {
        return $this->hasOne(Modelo::class, 'id', 'modelo_id');
    }
    public function modelo()
{
    return $this->belongsTo(Modelo::class);
}
public function esToner()
{
    return $this->categoria_id == CATEGORIA_TONER ||
           !empty($this->modelo_toner) ||
           str_contains(strtolower($this->nombre), 'toner');
}

public function especificaciones()
{
    return $this->hasMany(Especificacion::class, 'producto_id'); // Especifica la clave foránea
}
// En el modelo Producto.php
public function filtros()
{
    return $this->belongsToMany(Aside::class, 'producto_filtros')
                ->withPivot('opcion')
                ->withTimestamps();
}
public function getOpcionesFiltro($asideId)
{
    return $this->filtros()
        ->where('asides.id', $asideId)
        ->pluck('opcion')
        ->toArray();
}

}
