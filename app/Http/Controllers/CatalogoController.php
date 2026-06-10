<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use App\Models\Marca;
use App\Producto;
use App\Modelo;
use App\Models\Aside;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $modelos = \App\Modelo::whereRaw("UPPER(activo) = 'SI'")
            ->whereHas('getProducto', function ($q) {
                $q->where('pagina_web', 'SI')->noSuspendido();
            })
            ->orderBy('descripcion')
            ->get();

        $productosQuery = \App\Producto::query()
            ->where('pagina_web', 'SI')
            ->noSuspendido();

        if ($request->busqueda) {
            $productosQuery->where(function($q) use ($request) {
                $q->where('descripcion', 'LIKE', "%{$request->busqueda}%")
                  ->orWhere('nro_parte', 'LIKE', "%{$request->busqueda}%");
            });
        }
        if ($request->modelo) {
            $productosQuery->where('modelo_id', $request->modelo);
        }

        $this->applySpecFilters($productosQuery, $request);

        $orden = $request->orden ?? 'newest';
        switch ($orden) {
            case 'nombre_asc': $productosQuery->orderBy('descripcion','asc'); break;
            case 'nombre_desc': $productosQuery->orderBy('descripcion','desc'); break;
            case 'oldest': $productosQuery->orderBy('created_at','asc'); break;
            default: $productosQuery->orderBy('created_at','desc'); break;
        }

        $productos = $productosQuery->with('modelo')->paginate(9);

        $novedades = \App\Producto::with('modelo')
            ->orderBy('created_at', 'DESC')
            ->where('pagina_web', 'SI')
            ->noSuspendido()
            ->whereNull('precio_anterior')
            ->take(16)
            ->get();

        return view('catalogo-preview', compact('modelos', 'productos', 'novedades'));
    }

    private function applySpecFilters($query, Request $request)
    {
        $directColumns = [
            'procesador', 'ram', 'almacenamiento', 'tarjetavideo',
            'sistema_operativo', 'unidad_optica', 'conectividad_wlan',
            'video_vga', 'video_hdmi', 'suite_ofimatica',
        ];

        foreach ($directColumns as $col) {
            if ($request->filled($col)) {
                $values = array_map('trim', explode(',', $request->$col));
                $values = array_filter($values, fn($v) => $v !== '');
                if (!empty($values)) {
                    $query->whereIn($col, $values);
                }
            }
        }

        $monitorSpecs = [
            'espec_tamano'      => 'Tamaño de Pantalla',
            'espec_panel'       => 'Panel',
            'espec_hdmi'        => 'HDMI',
            'espec_displayport' => 'DisplayPort',
            'espec_garantia'    => 'Garantía de Fábrica',
        ];

        foreach ($monitorSpecs as $param => $campo) {
            if ($request->filled($param)) {
                $values = array_map('trim', explode(',', $request->$param));
                $query->whereHas('especificaciones', function($q) use ($campo, $values) {
                    $q->where('campo', $campo)
                      ->whereIn('descripcion', $values);
                });
            }
        }
    }

    public function categoria(Request $request)
    {
        if ($request->id) {
            $productos = Producto::with('getCategoria', 'getMarca','getModelo')->where('pagina_web', 'SI')
                                    ->where('categoria_id','modelo_id', $request->id)
                                    ->orderBy('nombre', 'ASC')->paginate(6);
        } else {
            $productos = Producto::where('pagina_web', 'SI')
                ->orderBy('nombre', 'ASC')->paginate(6);
        }
        if ($request->search) {
            switch ($request->search_por) {
                case 'nombre':
                    $productos->where('nombre', 'like', '%' . $request->search . '%');
                    break;
            }
        }
        if ($request->categoria) {
            $productos->where('categoria_id', $request->categoria);
        }
        if ($request->web) {
            $productos->where('pagina_web', $request->web);
        }
        return [
            'pagination' => [
                'total' => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page' => $productos->perPage(),
                'last_page' => $productos->lastPage(),
                'from' => $productos->firstItem(),
                'to' => $productos->lastPage(),
                'index' => ($productos->currentPage() - 1) * $productos->perPage(),
            ],
            'productos' => $productos
        ];
    }
    public function detallemod($id, Request $request)
    {
        $modelo = \App\Modelo::findOrFail($id);

        $modelos = \App\Modelo::whereRaw("UPPER(activo) = 'SI'")
            ->whereHas('getProducto', function ($q) {
                $q->where('pagina_web', 'SI')->noSuspendido();
            })
            ->orderBy('descripcion')
            ->get();

        $productosQuery = \App\Producto::with('modelo')
            ->where('modelo_id', $id)
            ->where('pagina_web', 'SI')
            ->noSuspendido();

        if ($request->busqueda) {
            $productosQuery->where(function($q) use ($request) {
                $q->where('descripcion', 'LIKE', "%{$request->busqueda}%")
                  ->orWhere('nro_parte', 'LIKE', "%{$request->busqueda}%");
            });
        }

        $this->applySpecFilters($productosQuery, $request);

        $productos = $productosQuery->orderBy('created_at', 'desc')->paginate(9);

        $novedades = \App\Producto::with('modelo')
            ->orderBy('created_at', 'DESC')
            ->where('pagina_web', 'SI')
            ->noSuspendido()
            ->whereNull('precio_anterior')
            ->take(16)
            ->get();

        return view('catalogo-modelo', compact('modelo', 'modelos', 'productos', 'id', 'novedades'));
    }

    // Return the aside filters partial for a given modelo (used by preview AJAX)
    public function previewFilters($id = null)
    {
        if (!$id) {
            return response('<p style="padding:15px;color:#666;font-size:14px;">Seleccione un modelo para ver los filtros disponibles.</p>')
                ->header('Content-Type', 'text/html; charset=utf-8');
        }
        return view('partials.aside-detallemod', ['id' => $id]);
    }

    // Return the products grid partial for preview with filters applied via query string
    public function previewProducts(Request $request)
    {
        $productosQuery = \App\Producto::query()
            ->where('pagina_web', 'SI')
            ->noSuspendido();

        if ($request->busqueda) {
            $productosQuery->where(function($q) use ($request) {
                $q->where('descripcion', 'LIKE', "%{$request->busqueda}%")
                  ->orWhere('nro_parte', 'LIKE', "%{$request->busqueda}%");
            });
        }

        if ($request->modelo) {
            $productosQuery->where('modelo_id', $request->modelo);
        }

        $this->applySpecFilters($productosQuery, $request);

        // ordering
        $orden = $request->orden ?? 'newest';
        switch ($orden) {
            case 'nombre_asc': $productosQuery->orderBy('descripcion','asc'); break;
            case 'nombre_desc': $productosQuery->orderBy('descripcion','desc'); break;
            case 'oldest': $productosQuery->orderBy('created_at','asc'); break;
            case 'newest':
            default: $productosQuery->orderBy('created_at','desc'); break;
        }

        $productos = $productosQuery->with('modelo')->paginate(9);

        return view('partials.catalogo-products', compact('productos'));
    }

    // Return JSON suggestions for the intelligent search
    public function previewSuggest(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $modelo = $request->get('modelo');

        if ($q === '') {
            return response()->json([]);
        }

        $query = \App\Producto::query()
            ->select(['id', 'nombre', 'imagen_1', 'imagen', 'nro_parte'])
            ->where('pagina_web', 'SI')
            ->noSuspendido()
            ->where(function ($s) use ($q) {
                $s->where('nombre', 'LIKE', "%{$q}%")
                  ->orWhere('descripcion', 'LIKE', "%{$q}%")
                  ->orWhere('nro_parte', 'LIKE', "%{$q}%");
            })
            ->orderByRaw("CASE WHEN nombre LIKE ? THEN 0 ELSE 1 END", ["{$q}%"])
            ->limit(8);

        if ($modelo) {
            $query->where('modelo_id', $modelo);
        }

        $results = $query->with('modelo')->get()->map(function ($p) {
            $img = $p->imagen_1 ? asset('storage/' . $p->imagen_1) : ($p->imagen ? asset('storage/' . $p->imagen) : asset('producto.jpg'));
            return [
                'id' => $p->id,
                'nombre' => (string) ($p->display_name ?: $p->nombre),
                'nro_parte' => (string) $p->nro_parte,
                'modelo' => $p->modelo ? ($p->modelo->descripcion ?? $p->modelo->nombre ?? '') : '',
                'img' => $img,
                'url' => url('producto/' . $p->id . '/detalle'),
            ];
        });

        return response()->json($results);
    }

    public function buscar(Request $request)
    {
        $productos = Producto::with('getCategoria', 'getMarca','getModelo')
            ->where('pagina_web', 'SI')
            ->noSuspendido()
            ->orderBy('nombre', 'ASC');

        if($request->modelo_id) {
            $productos->where('modelo_id', $request->modelo_id);
        }

        if ($request->nombre) {
            $productos->where('nombre', 'LIKE', '%'.$request->nombre.'%');
        }

        // $productos->whereRaw("where (marca = 'MARCA2' or marca = 'KENYA')");
        // $productos->touch();

        if (!empty($request->marcas)) {
            $marcas = $request->marcas;

            foreach($marcas as $key =>$marca){
                if($key === 0) {
                    $productos->where('marca', $marcas[0]);
                }else {
                    $productos->orwhere('marca', $marca);
                }
            }
        }

        if (!empty($request->procesadores)) {
            $procesadores = $request->procesadores;

            foreach($procesadores as $key =>$procesador){
                if($key === 0) {
                    $productos->where('procesador', $procesadores[0]);
                }else {
                    $productos->orwhere('procesador', $procesador);
                }
            }
        }
        if (!empty($request->tarjetavideos)) {
            $tarjetavideos = $request->tarjetavideos;

            foreach($tarjetavideos as $key =>$tarjetavideo){
                if($key === 0) {
                    $productos->where('tarjetavideo', $tarjetavideos[0]);
                }else {
                    $productos->orwhere('tarjetavideo', $tarjetavideo);
                }
            }
        }

        if (!empty($request->ram)) {
            $rams = $request->ram;

            foreach($rams as $key =>$ram){
                if($key === 0) {
                    $productos->where('ram', $rams[0]);
                }else {
                    $productos->orwhere('ram', $ram);
                }
            }

        }

        if (!empty($request->sistema_operativo)) {
            $sistemas_operativos = $request->sistema_operativo;

            foreach($sistemas_operativos as $key =>$sistema_operativo){
                if($key === 0) {
                    $productos->where('sistema_operativo', $sistemas_operativos[0]);
                }else {
                    $productos->orwhere('sistema_operativo', $sistema_operativo);
                }
            }

        }

        if (!empty($request->almacenamiento)) {
            $almacenamientos = $request->almacenamiento;

            foreach($almacenamientos as $key =>$almacenamiento){
                if($key === 0) {
                    $productos->where('almacenamiento', $almacenamientos[0]);
                }else {
                    $productos->orwhere('almacenamiento', $almacenamiento);
                }
            }

        }

        if (!empty($request->conectividad)) {
            $conectividads = $request->conectividad;

            foreach($conectividads as $key =>$conectividad){
                if($key === 0) {
                    $productos->where('conectividad', $conectividads[0]);
                }else {
                    $productos->orwhere('conectividad', $conectividad);
                }
            }

        }

        if (!empty($request->conectividad_wlan)) {
            $conectividads_wlan = $request->conectividad_wlan;

            foreach($conectividads_wlan as $key =>$conectividad_wlan){
                if($key === 0) {
                    $productos->where('conectividad_wlan', $conectividads_wlan[0]);
                }else {
                    $productos->orwhere('conectividad_wlan', $conectividad_wlan);
                }
            }

        }

        if (!empty($request->conectividad_usb)) {
            $conectividads_usb = $request->conectividad_usb;

            foreach($conectividads_usb as $key =>$conectividad_usb){
                if($key === 0) {
                    $productos->where('conectividad_usb', $conectividads_usb[0]);
                }else {
                    $productos->orwhere('conectividad_usb', $conectividad_usb);
                }
            }

        }

        if (!empty($request->video_vga)) {
            $videos_vga = $request->video_vga;

            foreach($videos_vga as $key =>$video_vga){
                if($key === 0) {
                    $productos->where('video_vga', $videos_vga[0]);
                }else {
                    $productos->orwhere('video_vga', $video_vga);
                }
            }
        }

        if (!empty($request->video_hdmi)) {
            $videos_hdmi = $request->video_hdmi;

            foreach($videos_hdmi as $key =>$video_hdmi){
                if($key === 0) {
                    $productos->where('video_hdmi', $videos_hdmi[0]);
                }else {
                    $productos->orwhere('video_hdmi', $video_hdmi);
                }
            }

        }

        if (!empty($request->unidades_opticas)) {
            $unidades_opticas = $request->unidades_opticas;

            foreach($unidades_opticas as $key =>$unidad_optica){
                if($key === 0) {
                    $productos->where('unidad_optica', $unidades_opticas[0]);
                }else {
                    $productos->orwhere('unidad_optica', $unidad_optica);
                }
            }
        }

        if (!empty($request->teclados)) {
            $teclados = $request->teclados;

            foreach($teclados as $key =>$teclado){
                if($key === 0) {
                    $productos->where('teclado', $teclados[0]);
                }else {
                    $productos->orwhere('teclado', $teclado);
                }
            }
        }

        if (!empty($request->mouses)) {
            $mouses = $request->mouses;

            foreach($mouses as $key =>$mouse){
                if($key === 0) {
                    $productos->where('mouse', $mouses[0]);
                }else {
                    $productos->orwhere('mouse', $mouse);
                }
            }
        }

        if (!empty($request->suites)) {
            $suites = $request->suites;

            foreach($suites as $key =>$suite){
                if($key === 0) {
                    $productos->where('suite_ofimatica', $suites[0]);
                }else {
                    $productos->orwhere('suite_ofimatica', $suite);
                }
            }
        }

        if (!empty($request->stocks)) {
            $stocks = $request->stocks;

            foreach($stocks as $key =>$stock){
                if($key === 0) {
                    $productos->where('stock_inicial', $stocks[0]);
                }else {
                    $productos->orwhere('stock_inicial', $stock);
                }
            }
        }

        $sql = $productos->toSql();

        $productos = $productos->paginate(9);

        return [
            'pagination' => [
                'marcas'        => [$request->marcas, $request->modelo_id, $sql],
                'total'        => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'     => $productos->perPage(),
                'last_page'    => $productos->lastPage(),
                'from'         => $productos->firstItem(),
                'to'           => $productos->lastPage(),
                'index'        => ($productos->currentPage() - 1) * $productos->perPage(),
            ],
            'productos'    => $productos,
        ];
    }
    public function buscarmod(Request $request, $id)
    {
        $productos = Producto::with('getCategoria', 'getMarca')
            ->where('pagina_web', 'SI')
            ->noSuspendido()
            ->orderBy('nombre', 'ASC');

        if ($request->nombre) {
            $productos->where('nombre', 'LIKE', '%'.$request->nombre.'%');
        }

        if ($request->categoria_id) {
            $productos->where('categoria_id', $request->categoria_id);
        }

        if ($request->marca) {
            $productos->where('marca', $request->marca);
        }
        if ($request->almacenamiento){
            $productos->where('almacenamiento', $request->almacenamiento);
        }
        if ($request->tarjetavideo){
            $productos->where('tarjetavideo', $request->tarjetavideo);
        }

        $productos = $productos->paginate(6);

        return [
            'pagination' => [
                'total'        => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'     => $productos->perPage(),
                'last_page'    => $productos->lastPage(),
                'from'         => $productos->firstItem(),
                'to'           => $productos->lastPage(),
                'index'        => ($productos->currentPage() - 1) * $productos->perPage(),
            ],
            'productos'    => $productos,
        ];
    }
}
