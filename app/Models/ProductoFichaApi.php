<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoFichaApi extends Model
{
    protected $fillable = [
        'producto_id', 'codigo_pc', 'datos_crudos', 'pdf_url', 'imagenes'
    ];

    protected $casts = [
        'datos_crudos' => 'array',
        'imagenes' => 'array',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
