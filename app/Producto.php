<?php

namespace App;

use App\Models\Aside;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
    protected $appends = ['specs_api'];

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

    /**
     * Scope: Buscador inteligente de productos.
     * Busca por múltiples términos, modelos, categorías, sinónimos, specs y campos clave.
     */
    public function scopeIntelligentSearch($query, $searchTerm)
    {
        $raw = trim((string) $searchTerm);
        if ($raw === '') {
            return $query;
        }

        // Quitar caracteres especiales molestos pero conservar letras, números y espacios
        $cleaned = preg_replace('/[^\p{L}\p{N}\s\.\-\_]/u', ' ', $raw);
        $terms = array_filter(explode(' ', $cleaned), fn($t) => mb_strlen(trim($t)) >= 1);

        if (empty($terms)) {
            return $query;
        }

        $isPgsql = DB::connection()->getDriverName() === 'pgsql';
        $likeOp = $isPgsql ? 'ILIKE' : 'LIKE';

        // Mapeo semántico de sinónimos y conceptos a modelos/categorías
        $synonymCategoryModelMap = [
            // Computadoras / PCs
            'computadora' => ['categories' => ['COMPUTADORA', 'COMPUTADORAS', 'PC', 'ALL IN ONE'], 'models' => ['OFISZU', 'GENWORK', 'EZENT', 'PROWORK', 'HENKO']],
            'computadoras' => ['categories' => ['COMPUTADORA', 'COMPUTADORAS', 'PC', 'ALL IN ONE'], 'models' => ['OFISZU', 'GENWORK', 'EZENT', 'PROWORK', 'HENKO']],
            'pc' => ['categories' => ['COMPUTADORA', 'COMPUTADORAS', 'PC', 'ALL IN ONE'], 'models' => ['OFISZU', 'GENWORK', 'EZENT', 'PROWORK', 'HENKO']],
            'pcs' => ['categories' => ['COMPUTADORA', 'COMPUTADORAS', 'PC', 'ALL IN ONE'], 'models' => ['OFISZU', 'GENWORK', 'EZENT', 'PROWORK', 'HENKO']],
            'escritorio' => ['categories' => ['COMPUTADORA', 'COMPUTADORAS', 'PC'], 'models' => ['OFISZU', 'GENWORK', 'EZENT', 'PROWORK', 'HENKO']],
            'desktop' => ['categories' => ['COMPUTADORA', 'COMPUTADORAS', 'PC'], 'models' => ['OFISZU', 'GENWORK', 'EZENT', 'PROWORK', 'HENKO']],
            'ordenador' => ['categories' => ['COMPUTADORA', 'COMPUTADORAS', 'PC'], 'models' => ['OFISZU', 'GENWORK', 'EZENT', 'PROWORK', 'HENKO']],

            // Monitores
            'monitor' => ['categories' => ['MONITOR', 'MONITORES', 'PANTALLA', 'PANTALLAS'], 'models' => ['RAITO']],
            'monitores' => ['categories' => ['MONITOR', 'MONITORES', 'PANTALLA', 'PANTALLAS'], 'models' => ['RAITO']],
            'pantalla' => ['categories' => ['MONITOR', 'MONITORES', 'PANTALLA', 'PANTALLAS'], 'models' => ['RAITO']],
            'pantallas' => ['categories' => ['MONITOR', 'MONITORES', 'PANTALLA', 'PANTALLAS'], 'models' => ['RAITO']],

            // Laptops
            'laptop' => ['categories' => ['LAPTOP', 'LAPTOPS', 'NOTEBOOK', 'NOTEBOOKS', 'PORTATIL', 'PORTATILES'], 'models' => []],
            'laptops' => ['categories' => ['LAPTOP', 'LAPTOPS', 'NOTEBOOK', 'NOTEBOOKS', 'PORTATIL', 'PORTATILES'], 'models' => []],
            'notebook' => ['categories' => ['LAPTOP', 'LAPTOPS', 'NOTEBOOK', 'NOTEBOOKS'], 'models' => []],
            'portatil' => ['categories' => ['LAPTOP', 'LAPTOPS', 'NOTEBOOK', 'NOTEBOOKS', 'PORTATIL'], 'models' => []],
            'portatiles' => ['categories' => ['LAPTOP', 'LAPTOPS', 'NOTEBOOK', 'NOTEBOOKS', 'PORTATIL'], 'models' => []],

            // Tóner / Suministros
            'toner' => ['categories' => ['TONER', 'TÓNER', 'SUMINISTRO', 'SUMINISTROS', 'TINTA'], 'models' => []],
            'tóner' => ['categories' => ['TONER', 'TÓNER', 'SUMINISTRO', 'SUMINISTROS', 'TINTA'], 'models' => []],
            'suministro' => ['categories' => ['TONER', 'TÓNER', 'SUMINISTRO', 'SUMINISTROS'], 'models' => []],
            'suministros' => ['categories' => ['TONER', 'TÓNER', 'SUMINISTRO', 'SUMINISTROS'], 'models' => []],
        ];

        return $query->where(function ($subQuery) use ($terms, $likeOp, $synonymCategoryModelMap) {
            foreach ($terms as $term) {
                $termLower = mb_strtolower(trim($term));

                $subQuery->where(function ($termQuery) use ($term, $termLower, $likeOp, $synonymCategoryModelMap) {
                    // 1. Campos directos en tabla productos
                    $termQuery->where('productos.nombre', $likeOp, "%{$term}%")
                        ->orWhere('productos.descripcion', $likeOp, "%{$term}%")
                        ->orWhere('productos.nro_parte', $likeOp, "%{$term}%")
                        ->orWhere('productos.codigo_pc', $likeOp, "%{$term}%")
                        ->orWhere('productos.procesador', $likeOp, "%{$term}%")
                        ->orWhere('productos.ram', $likeOp, "%{$term}%")
                        ->orWhere('productos.almacenamiento', $likeOp, "%{$term}%")
                        ->orWhere('productos.tarjetavideo', $likeOp, "%{$term}%")
                        ->orWhere('productos.sistema_operativo', $likeOp, "%{$term}%");

                    // 2. Modelo relacionado
                    $termQuery->orWhereHas('getModelo', function ($modQuery) use ($term, $likeOp) {
                        $modQuery->where('descripcion', $likeOp, "%{$term}%");
                    });

                    // 3. Categoría relacionada
                    $termQuery->orWhereHas('getCategoria', function ($catQuery) use ($term, $likeOp) {
                        $catQuery->where('nombre', $likeOp, "%{$term}%");
                    });

                    // 4. Expansión semántica si el término coincide con un concepto o sinónimo
                    if (isset($synonymCategoryModelMap[$termLower])) {
                        $map = $synonymCategoryModelMap[$termLower];

                        // Coincidencia con categorías asociadas
                        if (!empty($map['categories'])) {
                            $termQuery->orWhereHas('getCategoria', function ($catQuery) use ($map, $likeOp) {
                                $catQuery->where(function ($cQ) use ($map, $likeOp) {
                                    foreach ($map['categories'] as $catName) {
                                        $cQ->orWhere('nombre', $likeOp, "%{$catName}%");
                                    }
                                });
                            });
                        }

                        // Coincidencia con modelos asociados
                        if (!empty($map['models'])) {
                            $termQuery->orWhereHas('getModelo', function ($modQuery) use ($map, $likeOp) {
                                $modQuery->where(function ($mQ) use ($map, $likeOp) {
                                    foreach ($map['models'] as $modName) {
                                        $mQ->orWhere('descripcion', $likeOp, "%{$modName}%");
                                    }
                                });
                            });
                        }
                    }
                });
            }
        });
    }

    public function precios()
    {
        return $this->hasMany(\App\Models\ProductoPrecio::class, 'producto_id');
    }

    public function imagenes()
    {
        return $this->hasMany(\App\Models\ProductoImagen::class, 'producto_id')->orderBy('orden', 'asc');
    }

    public function especificacionesList()
    {
        return $this->hasMany(\App\Models\ProductoEspecificacion::class, 'producto_id')->orderBy('orden', 'asc');
    }

}
