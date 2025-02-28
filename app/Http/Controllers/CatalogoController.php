<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use App\Models\Marca;
use App\Producto;
use App\Modelo;

class CatalogoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('activo', 'SI')->orderBy('nombre', 'ASC')->get();
        $productos = Producto::orderBy('nombre', 'ASC')->where('pagina_web', 'SI')->take(15)->get();
        //PRODUCTOS-FILTROS
        $productos_marcas = Producto::select('marca')->distinct()->whereNotNull('marca')->orderBy('marca')->get();



        return view('sistema.web.catalogo', compact('categorias', 'productos', 'productos_marcas'));
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
    public function detallemod($id)
    {
            $modelo = Modelo::with('getProducto')->findOrFail($id);
            $producto = Producto::with('getModelo')->findOrFail($id);
            $productos = Producto::with('getModelo')->where('modelo_id',$id)->where('pagina_web', 'SI')->take(15)->get();
            $modelos = Modelo::with('getProducto')->get();
            $categorias = Categoria::where('activo', 'SI')->orderBy('nombre', 'ASC')->get();
            //Filtros
            $productos_marcas = Producto::select('marca')->where('modelo_id',$id)->distinct()->whereNotNull('marca')->orderBy('marca')->get();
            $productos_procesador = Producto::select('procesador')->where('modelo_id',$id)->distinct()->whereNotNull('procesador')->orderBy('procesador')->get();
            $productos_ram = Producto::select('ram')->where('modelo_id',$id)->distinct()->whereNotNull('ram')->orderBy('ram')->get();
            $productos_sistema_operativo = Producto::select('sistema_operativo')->where('modelo_id',$id)->distinct()->whereNotNull('sistema_operativo')->orderBy('sistema_operativo')->get();
            $producto_almacenamiento = Producto::select('almacenamiento')->where('modelo_id',$id)->distinct()->whereNotNull('almacenamiento')->orderBy('almacenamiento')->get();
            $producto_lan = Producto::select('conectividad')->where('modelo_id',$id)->distinct()->whereNotNull('conectividad')->orderBy('conectividad')->get();
            $producto_wlan = Producto::select('conectividad_wlan')->where('modelo_id',$id)->distinct()->whereNotNull('conectividad_wlan')->orderBy('conectividad_wlan')->get();
            $producto_usb = Producto::select('conectividad_usb')->where('modelo_id',$id)->distinct()->whereNotNull('conectividad_usb')->orderBy('conectividad_usb')->get();
            $producto_vga = Producto::select('video_vga')->where('modelo_id',$id)->distinct()->whereNotNull('video_vga')->orderBy('video_vga')->get();
            $producto_hdmi = Producto::select('video_hdmi')->where('modelo_id',$id)->distinct()->whereNotNull('video_hdmi')->orderBy('video_hdmi')->get();
            $producto_unidades_opticas = Producto::select('unidad_optica')->where('modelo_id',$id)->distinct()->whereNotNull('unidad_optica')->orderBy('unidad_optica')->get();
            $producto_teclados = Producto::select('teclado')->where('modelo_id',$id)->distinct()->whereNotNull('teclado')->orderBy('teclado')->get();
            $producto_mouses = Producto::select('mouse')->where('modelo_id',$id)->distinct()->whereNotNull('mouse')->orderBy('mouse')->get();
            $producto_suites = Producto::select('suite_ofimatica')->where('modelo_id',$id)->distinct()->whereNotNull('suite_ofimatica')->orderBy('suite_ofimatica')->get();
            $producto_tarjetavideos = Producto::select('tarjetavideo')->where('modelo_id',$id)->distinct()->whereNotNull('tarjetavideo')->orderBy('tarjetavideo')->get();

            return view('sistema.web.detallemod', compact('producto','productos','categorias','modelo','productos_marcas','modelos', 'id', 'productos_procesador',
                                                            'productos_ram','productos_sistema_operativo','producto_almacenamiento','producto_lan',
                                                            'producto_usb','producto_wlan','producto_vga','producto_hdmi','producto_unidades_opticas',
                                                            'producto_teclados','producto_mouses','producto_suites','producto_tarjetavideos')
            );
    }
    public function buscar(Request $request)
    {
        $productos = Producto::with('getCategoria', 'getMarca','getModelo')
            ->where('pagina_web', 'SI')
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

        // $productos = Producto::with('getCategoria', 'getMarca')
        //     ->orderBy('nombre', 'ASC')->where('pagina_web', 'SI');
        // if ($request->search) {
        //     switch ($request->search_por) {
        //         case 'nombre':
        //             $productos->where('nombre', 'like', '%' . $request->search . '%');
        //             break;
        //     }
        // }
        // if ($request->categoria) {
        //     $productos->where('categoria_id', $request->categoria);
        // }

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
