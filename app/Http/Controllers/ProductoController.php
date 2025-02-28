<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Modelo;
use App\Procesador;
use App\Tarjetavideo;
use App\Ram;
use App\Almacenamiento;
use App\Ofimatica;
use App\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductoController extends Controller
{
    public function inicio()
    {
        $marcas = Marca::where('activo', 'SI')->orderBy('nombre', 'ASC')->get();
        $modelos = Modelo::where('activo', 'SI')->orderBy('id', 'ASC')->get();
        $procesador = Procesador::orderBy('id', 'ASC')->get();
        $tarjetavideo = Tarjetavideo::orderBy('id', 'ASC')->get();
        $ram = Ram::orderBy('id', 'ASC')->get();
        $almacenamiento = Almacenamiento::orderBy('id', 'ASC')->get();
        $ofimatica = Ofimatica::orderBy('id', 'ASC')->get();
        $categorias = Categoria::where('activo', 'SI')->orderBy('nombre', 'ASC')->get();
        return view('sistema.productos.index', compact('marcas', 'categorias','modelos',
        'procesador','ram','almacenamiento','ofimatica','tarjetavideo'));
    }

    public function buscar(Request $request)
    {
        $productos = Producto::with('getCategoria', 'getMarca','getModelo')
            ->orderBy('nombre', 'ASC');
        if ($request->search) {
            switch ($request->search_por) {
                case 'codigo_barras':
                    $productos->where('codigo_barras', $request->search);
                    break;
                case 'codigo_interno':
                    $productos->where('codigo_interno', $request->search);
                    break;
                case 'nombre':
                    $productos->where('nombre', 'like', '%'.$request->search.'%');
                    break;
            }
        }

        if ($request->categoria) {
            $productos->where('categoria_id', $request->categoria);
        }

        if ($request->web) {
            $productos->where('pagina_web', $request->web);
        }


        $productos = $productos->paginate(10);

        return [
            'pagination' => [
                'total'     => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'  => $productos->perPage(),
                'last_page' => $productos->lastPage(),
                'from'      => $productos->firstItem(),
                'to'        => $productos->lastPage(),
                'index'     => ($productos->currentPage()-1)*$productos->perPage(),
            ],
            'productos'    => $productos,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre'                => 'required',
            'modelo_id'             => 'required',
            //'unidad'                => 'required',
            //'moneda'                => 'required',
            //'precio_unitario'       => 'required|numeric',
            'tipo_afectacion'       => 'required',
            'nro_parte'       => 'required',
            //'incluye_igv'           => 'required',
        ]);

        try {
            DB::beginTransaction();

            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->nombre_secundario = $request->nombre_secundario;
            $producto->descripcion = $request->descripcion;
            $producto->nro_parte = $request->nro_parte;
            $producto->procesador = $request->procesador;
            $producto->ram = $request->ram;
            $producto->almacenamiento = $request->almacenamiento;
            $producto->conectividad = $request->conectividad;
            $producto->conectividad_wlan = $request->conectividad_wlan;
            $producto->conectividad_usb = $request->conectividad_usb;
            $producto->video_vga = $request->video_vga;
            $producto->video_hdmi = $request->video_hdmi;
            $producto->sistema_operativo = $request->sistema_operativo;
            $producto->unidad_optica = $request->unidad_optica;
            $producto->teclado = $request->teclado;
            $producto->tarjetavideo = $request->tarjetavideo;
            $producto->mouse = $request->mouse;
            $producto->suite_ofimatica = $request->suite_ofimatica;
            $producto->garantia_de_fabrica = $request->garantia_de_fabrica;
            $producto->empaque_de_fabrica = $request->empaque_de_fabrica;
            $producto->certificacion = $request->certificacion;
            if ($request->especificaciones) {
                $producto->especificaciones = $request->especificaciones;
            }
            $producto->modelo_id = Str::upper($request->modelo_id);
            //$producto->unidad = $request->unidad;
            //$producto->moneda = $request->moneda;
            //$producto->precio_unitario = $request->precio_unitario;

            $producto->tipo_afectacion = $request->tipo_afectacion;


            $producto->codigo_barras = Str::upper($request->codigo_barras);
            $producto->codigo_interno = Str::upper($request->codigo_interno);
            $producto->codigo_sunat = Str::upper($request->codigo_sunat);
            $producto->linea_producto = Str::upper($request->linea_producto);
            //$producto->incluye_igv = $request->incluye_igv;
            $producto->categoria_id = $request->categoria;
            $producto->marca_id = $request->marca;



            //$producto->ficha_tecnica = $request->ficha_tecnica;


            $producto->save();


            $route2 = 'pdf/'.$producto->id;

            if ($request->hasFile('pdf_ficha')) {
                $file = $request->file('pdf_ficha');
                $extension = $file->extension();
                $file_name = 'PDF_'.Str::random(10).'.'.$extension;

                Storage::putFileAs('public/'.$route2, $file, $file_name);
                $producto->ficha_tecnica = $route2.'/'.$file_name;

                $producto->save();
            }

            if (!$request->codigo_barras) {
                $producto->codigo_barras = Str::padLeft($producto->id, 7, '0');
                $producto->save();
            }

            $route = 'PRODUCTOS/'.$producto->id;

            if ($request->hasFile('imagen_1')) {
                $file_1 = $request->file('imagen_1');
                $extension_1 = $file_1->extension();
                $file_name_1 = 'IMG1_'.Str::random(10).'.'.$extension_1;

                Storage::putFileAs('public/'.$route, $file_1, $file_name_1);
                $producto->imagen_1 = $route.'/'.$file_name_1;
            }
            if ($request->hasFile('imagen_2')) {
                $file_2 = $request->file('imagen_2');
                $extension_2 = $file_2->extension();
                $file_name_2 = 'IMG2_'.Str::random(10).'.'.$extension_2;

                Storage::putFileAs('public/'.$route, $file_2, $file_name_2);
                $producto->imagen_2 = $route.'/'.$file_name_2;
            }
            if ($request->hasFile('imagen_3')) {
                $file_3 = $request->file('imagen_3');
                $extension_3 = $file_3->extension();
                $file_name_3 = 'IMG3_'.Str::random(10).'.'.$extension_3;

                Storage::putFileAs('public/'.$route, $file_3, $file_name_3);
                $producto->imagen_3 = $route.'/'.$file_name_3;
            }
            if ($request->hasFile('imagen_4')) {
                $file_4 = $request->file('imagen_4');
                $extension_4 = $file_4->extension();
                $file_name_4 = 'IMG4_'.Str::random(10).'.'.$extension_4;

                Storage::putFileAs('public/'.$route, $file_4, $file_name_4);
                $producto->imagen_4 = $route.'/'.$file_name_4;
            }
            if ($request->hasFile('imagen_5')) {
                $file_5 = $request->file('imagen_5');
                $extension_5 = $file_5->extension();
                $file_name_5 = 'IMG5_'.Str::random(10).'.'.$extension_5;

                Storage::putFileAs('public/'.$route, $file_5, $file_name_5);
                $producto->imagen_5 = $route.'/'.$file_name_5;
            }

            if ($request->hasFile('imagen_1') || $request->hasFile('imagen_2') || $request->hasFile('imagen_3') || $request->hasFile('imagen_4') || $request->hasFile('imagen_5')) {
                $producto->save();
            }

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Producto se guardo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th.' Ocurrio un error al guardar el Producto, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        //return $request->especificaciones;
        $this->validate($request, [
            'nombre'                => 'required',
            'modelo_id'                => 'required',
            //'unidad'                => 'required',
            //'moneda'                => 'required',
            //'precio_unitario'       => 'required|numeric',
            'tipo_afectacion'       => 'required',
            //'incluye_igv'           => 'required',
        ]);

        try {
            DB::beginTransaction();

            $producto = Producto::findOrFail($request->id);
            $producto->nombre = $request->nombre;
            $producto->nombre_secundario = $request->nombre_secundario;
            $producto->descripcion = $request->descripcion;
            $producto->nro_parte = $request->nro_parte;
            $producto->procesador = $request->procesador;
            $producto->ram = $request->ram;
            $producto->almacenamiento = $request->almacenamiento;
            $producto->conectividad = $request->conectividad;
            $producto->conectividad_wlan = $request->conectividad_wlan;
            $producto->conectividad_usb = $request->conectividad_usb;
            $producto->video_vga = $request->video_vga;
            $producto->video_hdmi = $request->video_hdmi;
            $producto->sistema_operativo = $request->sistema_operativo;
            $producto->unidad_optica = $request->unidad_optica;
            $producto->teclado = $request->teclado;
            //$producto->ficha_tecnica = $request->ficha_tecnica;
            $producto->mouse = $request->mouse;
            $producto->suite_ofimatica = $request->suite_ofimatica;
            $producto->garantia_de_fabrica = $request->garantia_de_fabrica;
            $producto->empaque_de_fabrica = $request->empaque_de_fabrica;
            $producto->certificacion = $request->certificacion;
            $producto->especificaciones = $request->especificaciones;
            $producto->modelo_id = $request->modelo_id;
            $producto->tarjetavideo = $request->tarjetavideo;
            //$producto->unidad = $request->unidad;
            //$producto->moneda = $request->moneda;
            //$producto->precio_unitario = $request->precio_unitario;

            $producto->tipo_afectacion = $request->tipo_afectacion;


            $producto->codigo_barras = Str::upper($request->codigo_barras);
            $producto->codigo_interno = Str::upper($request->codigo_interno);
            $producto->codigo_sunat = Str::upper($request->codigo_sunat);
            $producto->linea_producto = Str::upper($request->linea_producto);
            //$producto->incluye_igv = $request->incluye_igv;
            $producto->categoria_id = $request->categoria;
            $producto->marca_id = $request->marca;


            $route2 = 'pdf/'.$producto->id;

            if ($request->hasFile('pdf_ficha')) {
                Storage::delete('public/'.$producto->ficha_tecnica);

                $file = $request->file('pdf_ficha');
                $extension = $file->extension();
                $file_name = 'PDF_'.Str::random(10).'.'.$extension;

                Storage::putFileAs('public/'.$route2, $file, $file_name);
                $producto->ficha_tecnica = $route2.'/'.$file_name;
            }



            $route = 'PRODUCTOS/'.$request->id;

            if ($request->hasFile('imagen_1')) {
                Storage::delete('public/'.$producto->imagen_1);

                $file_1 = $request->file('imagen_1');
                $extension_1 = $file_1->extension();
                $file_name_1 = 'IMG1_'.Str::random(10).'.'.$extension_1;

                Storage::putFileAs('public/'.$route, $file_1, $file_name_1);
                $producto->imagen_1 = $route.'/'.$file_name_1;
            }
            if ($request->hasFile('imagen_2')) {
                Storage::delete('public/'.$producto->imagen_2);

                $file_2 = $request->file('imagen_2');
                $extension_2 = $file_2->extension();
                $file_name_2 = 'IMG2_'.Str::random(10).'.'.$extension_2;

                Storage::putFileAs('public/'.$route, $file_2, $file_name_2);
                $producto->imagen_2 = $route.'/'.$file_name_2;
            }
            if ($request->hasFile('imagen_3')) {
                Storage::delete('public/'.$producto->imagen_3);

                $file_3 = $request->file('imagen_3');
                $extension_3 = $file_3->extension();
                $file_name_3 = 'IMG3_'.Str::random(10).'.'.$extension_3;

                Storage::putFileAs('public/'.$route, $file_3, $file_name_3);
                $producto->imagen_3 = $route.'/'.$file_name_3;
            }
            if ($request->hasFile('imagen_4')) {
                Storage::delete('public/'.$producto->imagen_4);

                $file_4 = $request->file('imagen_4');
                $extension_4 = $file_4->extension();
                $file_name_4 = 'IMG4_'.Str::random(10).'.'.$extension_4;

                Storage::putFileAs('public/'.$route, $file_4, $file_name_4);
                $producto->imagen_4 = $route.'/'.$file_name_4;
            }
            if ($request->hasFile('imagen_5')) {
                Storage::delete('public/'.$producto->imagen_5);

                $file_5 = $request->file('imagen_5');
                $extension_5 = $file_5->extension();
                $file_name_5 = 'IMG5_'.Str::random(10).'.'.$extension_5;

                Storage::putFileAs('public/'.$route, $file_5, $file_name_5);
                $producto->imagen_5 = $route.'/'.$file_name_5;
            }

            $producto->update();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Producto se actualizo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th.' Ocurrio un error al actualizar el Producto, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function web(Request $request)
    {
        $producto = Producto::findOrFail($request->id, ['id', 'pagina_web']);
        $producto->pagina_web = $request->mostrar;
        $producto->save();

        return [
            'type'          =>  'success',
            'title'         =>  'CORRECTO: ',
            'message'       =>  'La Categoría se guardo correctamente.'
        ];
    }

    public function detalle($id)
    {
        //try {
            $producto = Producto::findOrFail($id);
            return view('sistema.productos.detalle', compact('producto'));
        /*} catch (\Throwable $th) {
            return back();
        }*/
    }

    public function guardar(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nombre' => 'required',
            'nombre_secundario' => 'required',
            'descripcion' => 'required',
            'nro_parte' => 'required',
            'procesador' => 'required',
            'ram' => 'required',
            'almacenamiento' => 'required',
            'conectividad' => 'required',
            'conectividad_wlan' => 'required',
            'conectividad_usb' => 'required',
            'video_vga' => 'required',
            'video_hdmi' => 'required',
            'sistema_operativo' => 'required',
            'unidad_optica' => 'required',
            'teclado' => 'required',
            //'ficha' => 'required|ficha_t',
            'mouse' => 'required',
            'suite_ofimatica' => 'required',
            'garantia_de_fabrica' => 'required',
            'empaque_de_fabrica' => 'required',
            'certificacion' => 'required',
            //'unidad' => 'required',
            //'moneda' => 'required',
            //'precio_unitario' => 'required|numeric',
            'tipo_afectacion' => 'required',
            'categoria' => 'required',
            'marca' => 'required',
            'codigo_interno' => 'required',
            'codigo_sunat' => 'required',
            'linea_producto' => 'required',
            'imagen' => 'required|image'
        ]);

        if($validator->fails()){

            return response()->json($validator->errors()->all(), 422);
        }

        $imagen_ficha = $request->file('imagen_ficha');
        $nombre_ficha = preg_replace('([^A-Za-z0-9])', '', $request->nombre_ficha).'.'.$imagen_ficha->extension();
        $imagen_ficha = $request->file('imagen_ficha')->storeAs('/', $nombre_ficha, 'public');

        $imagen = $request->file('imagen');
        $nombre = preg_replace('([^A-Za-z0-9])', '', $request->nombre).'.'.$imagen->extension();
        $imagen = $request->file('imagen')->storeAs('/', $nombre, 'public');

        Producto::create([
            'nombre'                => mb_strtoupper($request->nombre),
            'nombre_secundario'     => mb_strtoupper($request->nombre_secundario),
            'descripcion'           => mb_strtoupper($request->descripcion),
            'nro_parte'             => $request->nro_parte,
            'procesador'            => $request->procesador,
            'ram'                   => $request->ram,
            'almacenamiento'        => $request->almacenamiento,
            'conectividad'          => $request->conectividad,
            'conectividad_wlan'     => $request->conectividad_wlan,
            'conectividad_usb'      => $request->conectividad_usb,
            'video_vga'             => $request->video_vga,
            'video_hdmi'            => $request->video_hdmi,
            'sistema_operativo'     => $request->sistema_operativo,
            'unidad_optica'         => $request->unidad_optica,
            'teclado'               => $request->teclado,
            'ficha'                 => $nombre_ficha,
            'mouse'                 => $request->mouse,
            'suite_ofimatica'       => $request->suite_ofimatica,
            'garantia_de_fabrica'   => $request->garantia_de_fabrica,
            'empaque_de_fabrica'    => $request->empaque_de_fabrica,
            'certificacion'         => $request->certificacion,
            //'unidad'                => $request->unidad,
            //'moneda'                => mb_strtoupper($request->moneda),
            //'precio_unitario'       => $request->precio_unitario,
            'tipo_afectacion'       => $request->tipo_afectacion,
            'categoria'             => mb_strtoupper($request->categoria),
            'marca'                 => mb_strtoupper($request->marca),
            'modelo_id'             => $request->modelo_id,
            'cantidad_por_precio'   => $request->cantidad_por_precio == 'true' ? 1 : 0,
            //'incluye_igv'           => $request->incluye_igv == 'true' ? 1 : 0,
            'codigo_interno'        => mb_strtoupper($request->codigo_interno),
            'codigo_sunat'          => mb_strtoupper($request->codigo_sunat),
            'maneja_lotes'          => $request->maneja_lotes == 'true' ? 1 : 0,
            'maneja_series'         => $request->maneja_series == 'true' ? 1 : 0,
            'incluye_percepcion'    => $request->incluye_percepcion == 'true' ? 1 : 0,
            'linea_producto'        => mb_strtoupper($request->linea_producto),
            'imagen'                => $nombre
        ]);
    }

    public function todos(Request $request)
    {
        if($request-> frase){

            return Producto::where('nombre', 'like', '%'.$request->frase.'%')
                ->orWhere('nombre_secundario', 'like', '%'.$request->frase.'%')
                ->orWhere('descripcion', 'like', '%'.$request->frase.'%')
                ->paginate(10)
            ;
        }

        return Producto::paginate(10);
    }

    public function ver(Request $request)
    {
        return Producto::select(
                'productos.*',
                \DB::raw("case
                    when tipo_afectacion = '10' then 'GRAVADO'
                    when tipo_afectacion = '20' then 'EXONERADO'
                    when tipo_afectacion = '30' then 'INAFECTO'
                end as tipo_afectacion")
            )
            ->where('id', $request->id)
            ->first()
        ;
    }

    public function mdlEditarProducto(Request $request)
    {
        return Producto::select(
                'productos.*',
                \DB::raw("case
                    when id is not null then ''
                    else ''
                end as imagen"),
                'productos.imagen as imagen_actual'
            )
            ->where('id', $request->id)
            ->first()
        ;
    }

    public function modificar(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nombre' => 'required',
            'nombre_ficha' => 'required',
            'nombre_secundario' => 'required',
            'descripcion' => 'required',
            'nro_parte' => 'required',
            'procesador' => 'required',
            'ram' => 'required',
            'almacenamiento' => 'required',
            'conectividad' => 'required',
            'conectividad_wlan' => 'required',
            'conectividad_usb' => 'required',
            'video_vga' => 'required',
            'video_hdmi' => 'required',
            'sistema_operativo' => 'required',
            'unidad_optica' => 'required',
            'teclado' => 'required',
            //'ficha_tecnica' => 'required',
            'mouse' => 'required',
            'suite_ofimatica' => 'required',
            'garantia_de_fabrica' => 'required',
            'empaque_de_fabrica' => 'required',
            'certificacion' => 'required',
            //'unidad' => 'required',
            //'moneda' => 'required',
            //'precio_unitario' => 'required|numeric',
            'tipo_afectacion' => 'required',
            'categoria' => 'required',
            'marca' => 'required',
            'codigo_interno' => 'required',
            'codigo_sunat' => 'required',
            'linea_producto' => 'required'
        ]);

        if($validator->fails()){

            return response()->json($validator->errors()->all(), 422);
        }

        $producto = Producto::find($request->id);
        if($request->hasFile('ficha')){
            $validator = \Validator::make($request->all(), [
                'ficha' => 'nullable|ficha_t'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->all(), 422);
            }

            if(\Storage::disk('public')->exists($producto->ficha)){

                \Storage::disk('public')->delete($producto->ficha);
            }

            $ficha = $request->file('ficha');
            $nombre_ficha = preg_replace('([^A-Za-z0-9])', '', $request->nombre_ficha).'.'.$ficha->extension();
            $ficha = $request->file('ficha')->storeAs('/', $nombre_ficha, 'public');

            $producto->ficha = $nombre_ficha;
        }

        if($request->hasFile('imagen')){

            $validator = \Validator::make($request->all(), [
                'imagen' => 'nullable|image'
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->all(), 422);
            }

            if(\Storage::disk('public')->exists($producto->imagen)){

                \Storage::disk('public')->delete($producto->imagen);
            }

            $imagen = $request->file('imagen');
            $nombre = preg_replace('([^A-Za-z0-9])', '', $request->nombre).'.'.$imagen->extension();
            $imagen = $request->file('imagen')->storeAs('/', $nombre, 'public');

            $producto->imagen = $nombre;
        }

        $producto->nombre                = mb_strtoupper($request->nombre);
        $producto->nombre_secundario     = mb_strtoupper($request->nombre_secundario);
        $producto->descripcion           = mb_strtoupper($request->descripcion);
        $producto->nro_parte             = $request->nro_parte;
        $producto->procesador            = $request->procesador;
        $producto->ram                   = $request->ram;
        $producto->almacenamiento        = $request->almacenamiento;
        $producto->conectividad          = $request->conectividad;
        $producto->conectividad_wlan     = $request->conectividad_wlan;
        $producto->conectividad_usb      = $request->conectividad_usb;
        $producto->video_vga             = $request->video_vga;
        $producto->video_hdmi            = $request->video_hdmi;
        $producto->sistema_operativo     = $request->sistema_operativo;
        $producto->unidad_optica         = $request->unidad_optica;
        $producto->teclado               = $request->teclado;
        //$producto->ficha_tecnica         = $request->ficha_tecnica;
        $producto->mouse                 = $request->mouse;
        $producto->suite_ofimatica       = $request->suite_ofimatica;
        $producto->garantia_de_fabrica   = $request->garantia_de_fabrica;
        $producto->empaque_de_fabrica    = $request->empaque_de_fabrica;
        $producto->certificacion         = $request->certificacion;
        //$producto->unidad                = $request->unidad;
        //$producto->moneda                = mb_strtoupper($request->moneda);
        //$producto->precio_unitario       = $request->precio_unitario;
        $producto->tipo_afectacion       = $request->tipo_afectacion;
        $producto->categoria             = mb_strtoupper($request->categoria);
        $producto->marca                 = mb_strtoupper($request->marca);
        $producto->modelo_id             = $request->modelo_id;
        $producto->cantidad_por_precio   = $request->cantidad_por_precio;
        //$producto->incluye_igv           = $request->incluye_igv;
        $producto->codigo_interno        = mb_strtoupper($request->codigo_interno);
        $producto->codigo_sunat          = mb_strtoupper($request->codigo_sunat);
        $producto->maneja_lotes          = $request->maneja_lotes;
        $producto->maneja_series         = $request->maneja_series;
        $producto->incluye_percepcion    = $request->incluye_percepcion;
        $producto->linea_producto        = mb_strtoupper($request->linea_producto);
        $producto->save();
    }

    public function mdlEliminarProducto(Request $request)
    {
        return Producto::find($request->id);
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $producto = Producto::findOrFail($request->id);
            $route2 = 'pdf/'.$producto->id;

            if ($producto->ficha_tecnica) {
                Storage::delete('public/'.$producto->ficha_tecnica);

                Storage::deleteDirectory('public/'.$route2);
            }
            $producto->delete();

            DB::commit();

            return [
                'action'    =>  'success',
                'title'     =>  'Bien!!',
                'message'   =>  'El producto se elimino con exito',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th.' Ocurrio un error al eliminar el Producto, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

}
