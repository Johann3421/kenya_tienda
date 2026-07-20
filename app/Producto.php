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
        'nombre',
        'nombre_secundario',
        'descripcion',
        'descripcion_2',
        'especificaciones',
        'nro_parte',
        'resolucion',
        'procesador',
        'ram',
        'almacenamiento',
        'conectividad',
        'conectividad_wlan',
        'conectividad_usb',
        'video_vga',
        'video_hdmi',
        'sistema_operativo',
        'unidad_optica',
        'teclado',
        'mouse',
        'suite_ofimatica',
        'garantia_de_fabrica',
        'empaque_de_fabrica',
        'certificacion',
        'tarjetavideo',
        'unidad',
        'marca',
        'moneda',
        'precio_unitario',
        'precio_compra',
        'precio_anterior',
        'tipo_afectacion',
        'tipo_afectacion_compra',
        'stock_inicial',
        'stock_minimo',
        'fecha_vencimiento',
        'codigo_barras',
        'codigo_interno',
        'codigo_sunat',
        'linea_producto',
        'incluye_igv',
        'imagen_1',
        'imagen_2',
        'imagen_3',
        'imagen_4',
        'imagen_5',
        'categoria_id',
        'marca_id',
        'pagina_web',
        'modelo_id',
        'ficha_tecnica',
        'codigo_pc',
        'vigencia',
        'ficha_editada_localmente',
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
        'sonido',
        'chipset',
        'slot_expansion',
        'fuente_poder',
        'accesorios',
    ];
    protected $appends = ['estado', 'specs_api'];

    public function getSpecsApiAttribute()
    {
        if ($this->relationLoaded('especificaciones')) {
            return $this->getRelation('especificaciones');
        }
        return [];
    }
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

public function fichaApi()
{
    return $this->hasOne(\App\Models\ProductoFichaApi::class, 'producto_id', 'id');
}

    public function getDisplayNameAttribute()
    {
        $name = $this->nombre ?? '';
        if ($this->relationLoaded('modelo') && $this->modelo) {
            $prefix = $this->modelo->prefix;
            return $prefix ? "{$prefix} {$name}" : $name;
        }
        if ($this->relationLoaded('getModelo') && $this->getModelo) {
            $prefix = $this->getModelo->prefix;
            return $prefix ? "{$prefix} {$name}" : $name;
        }
        return $name;
    }

    /**
     * Scope: excluye productos con vigencia SUSPENDIDA.
     * Productos sin vigencia (NULL) o con cualquier otro estado se consideran visibles.
     */
    public function scopeNoSuspendido($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('vigencia')->orWhereNotIn('vigencia', ['SUSPENDIDA', 'INACTIVA', 'ANULADA']);
        })->whereNotNull('nombre')->where('nombre', '!=', '');
    }

}
