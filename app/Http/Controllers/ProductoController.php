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
use App\Imports\EspecificacionesImport;
use App\Models\Especificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    protected $excel;

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
        $productos = Producto::with('especificaciones')->paginate(10); // Ajusta según necesites
        return view('sistema.productos.index', compact(
            'marcas',
            'categorias',
            'modelos',
            'procesador',
            'ram',
            'almacenamiento',
            'ofimatica',
            'tarjetavideo',
            'productos'
        ));
    }

    public function buscar(Request $request)
    {
        $productos = Producto::with('getCategoria', 'getMarca', 'getModelo')
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


        $productos = $productos->paginate(10);

        return [
            'pagination' => [
                'total'     => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'  => $productos->perPage(),
                'last_page' => $productos->lastPage(),
                'from'      => $productos->firstItem(),
                'to'        => $productos->lastPage(),
                'index'     => ($productos->currentPage() - 1) * $productos->perPage(),
            ],
            'productos'    => $productos,
        ];
    }


public function store(Request $request)
{
    $this->validate($request, [
        'nombre'                => 'required',
        'modelo_id'             => 'required',
        'tipo_afectacion'       => 'required',
        'nro_parte'             => 'required',
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
        $producto->modelo_id = $request->modelo_id;
        $producto->tipo_afectacion = $request->tipo_afectacion;
        $producto->codigo_barras = Str::upper($request->codigo_barras);
        $producto->codigo_interno = Str::upper($request->codigo_interno);
        $producto->codigo_sunat = Str::upper($request->codigo_sunat);
        $producto->linea_producto = Str::upper($request->linea_producto);
        $producto->categoria_id = $request->categoria;
        $producto->marca_id = $request->marca;

        $producto->save();

        // Subir PDF de ficha técnica
        $producto->ficha_tecnica = $this->subirFichaTecnica($request, $producto);
        $producto->save();

        // Subir imágenes (igual que ya tienes)
        $route = 'PRODUCTOS/' . $producto->id;
        for ($i = 1; $i <= 5; $i++) {
            if ($request->hasFile('imagen_' . $i)) {
                $file = $request->file('imagen_' . $i);
                $extension = $file->extension();
                $file_name = 'IMG' . $i . '_' . Str::random(10) . '.' . $extension;
                Storage::putFileAs('public/' . $route, $file, $file_name);
                $producto->{'imagen_' . $i} = $route . '/' . $file_name;
            }
        }
        $producto->save();

        DB::commit();

        return [
            'type'     =>  'success',
            'title'    =>  'CORRECTO: ',
            'message'  =>  'El Producto se guardó correctamente.'
        ];
    } catch (\Throwable $th) {
        DB::rollBack();
        return [
            'type'     =>  'danger',
            'title'    =>  'ERROR: ',
            'message'  =>  $th . ' Ocurrió un error al guardar el Producto, intente nuevamente o contacte al Administrador del Sistema.'
        ];
    }
}

public function update(Request $request)
{
    $this->validate($request, [
        'nombre'                => 'required',
        'modelo_id'             => 'required',
        'tipo_afectacion'       => 'required',
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
        $producto->mouse = $request->mouse;
        $producto->suite_ofimatica = $request->suite_ofimatica;
        $producto->garantia_de_fabrica = $request->garantia_de_fabrica;
        $producto->empaque_de_fabrica = $request->empaque_de_fabrica;
        $producto->certificacion = $request->certificacion;
        $producto->especificaciones = $request->especificaciones;
        $producto->modelo_id = $request->modelo_id;
        $producto->tarjetavideo = $request->tarjetavideo;
        $producto->tipo_afectacion = $request->tipo_afectacion;
        $producto->codigo_barras = Str::upper($request->codigo_barras);
        $producto->codigo_interno = Str::upper($request->codigo_interno);
        $producto->codigo_sunat = Str::upper($request->codigo_sunat);
        $producto->linea_producto = Str::upper($request->linea_producto);
        $producto->categoria_id = $request->categoria;
        $producto->marca_id = $request->marca;

        // Subir PDF de ficha técnica
        $producto->ficha_tecnica = $this->subirFichaTecnica($request, $producto);

        // Subir imágenes (igual que ya tienes)
        $route = 'PRODUCTOS/' . $producto->id;
        for ($i = 1; $i <= 5; $i++) {
            if ($request->hasFile('imagen_' . $i)) {
                // Elimina la imagen anterior si existe
                if ($producto->{'imagen_' . $i}) {
                    Storage::delete('public/' . $producto->{'imagen_' . $i});
                }
                $file = $request->file('imagen_' . $i);
                $extension = $file->extension();
                $file_name = 'IMG' . $i . '_' . Str::random(10) . '.' . $extension;
                Storage::putFileAs('public/' . $route, $file, $file_name);
                $producto->{'imagen_' . $i} = $route . '/' . $file_name;
            }
        }
        $producto->save();

        DB::commit();

        return [
            'type'     =>  'success',
            'title'    =>  'CORRECTO: ',
            'message'  =>  'El Producto se actualizó correctamente.'
        ];
    } catch (\Throwable $th) {
        DB::rollBack();
        return [
            'type'     =>  'danger',
            'title'    =>  'ERROR: ',
            'message'  =>  $th . ' Ocurrió un error al actualizar el Producto, intente nuevamente o contacte al Administrador del Sistema.'
        ];
    }
}

/**
 * Sube el PDF de ficha técnica a storage/app/public/pdfs/{producto_id}
 * y retorna la ruta relativa para guardar en la base de datos.
 */

public function subirFichaTecnica(Request $request, $producto)
{
    if ($request->hasFile('pdf_ficha')) {
        // Elimina el archivo anterior si existe
        if ($producto->ficha_tecnica && Storage::disk('public')->exists($producto->ficha_tecnica)) {
            Storage::disk('public')->delete($producto->ficha_tecnica);
        }

        $file = $request->file('pdf_ficha');
        $extension = $file->extension();
        // El nombre será: soporte_{id}_{random}.pdf
        $file_name = 'soporte_' . $producto->id . '_' . Str::random(10) . '.' . $extension;
        $folder = 'pdfs';

        Storage::disk('public')->putFileAs($folder, $file, $file_name);

        return $folder . '/' . $file_name;
    }
    return $producto->ficha_tecnica; // Si no hay nuevo archivo, retorna el anterior
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
        $producto = Producto::findOrFail($id);
        $especificaciones = Especificacion::where('producto_id', $id)->get();

        return view('sistema.productos.detalle', [
            'producto' => $producto,
            'especificaciones' => $especificaciones
        ]);
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

        if ($validator->fails()) {

            return response()->json($validator->errors()->all(), 422);
        }

        $imagen_ficha = $request->file('imagen_ficha');
        $nombre_ficha = preg_replace('([^A-Za-z0-9])', '', $request->nombre_ficha) . '.' . $imagen_ficha->extension();
        $imagen_ficha = $request->file('imagen_ficha')->storeAs('/', $nombre_ficha, 'public');

        $imagen = $request->file('imagen');
        $nombre = preg_replace('([^A-Za-z0-9])', '', $request->nombre) . '.' . $imagen->extension();
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
        if ($request->frase) {

            return Producto::where('nombre', 'like', '%' . $request->frase . '%')
                ->orWhere('nombre_secundario', 'like', '%' . $request->frase . '%')
                ->orWhere('descripcion', 'like', '%' . $request->frase . '%')
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

        if ($validator->fails()) {

            return response()->json($validator->errors()->all(), 422);
        }

        $producto = Producto::find($request->id);
        if ($request->hasFile('ficha')) {
            $validator = \Validator::make($request->all(), [
                'ficha' => 'nullable|ficha_t'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all(), 422);
            }

            if (\Storage::disk('public')->exists($producto->ficha)) {

                \Storage::disk('public')->delete($producto->ficha);
            }

            $ficha = $request->file('ficha');
            $nombre_ficha = preg_replace('([^A-Za-z0-9])', '', $request->nombre_ficha) . '.' . $ficha->extension();
            $ficha = $request->file('ficha')->storeAs('/', $nombre_ficha, 'public');

            $producto->ficha = $nombre_ficha;
        }

        if ($request->hasFile('imagen')) {

            $validator = \Validator::make($request->all(), [
                'imagen' => 'nullable|image'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all(), 422);
            }

            if (\Storage::disk('public')->exists($producto->imagen)) {

                \Storage::disk('public')->delete($producto->imagen);
            }

            $imagen = $request->file('imagen');
            $nombre = preg_replace('([^A-Za-z0-9])', '', $request->nombre) . '.' . $imagen->extension();
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
                'message'  =>  $th . ' Ocurrio un error al eliminar el Producto, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
    public function agregarEspecificacion(Request $request, $productoId)
    {
        $request->validate([
            'campo' => 'required|string|max:255',
            'descripcion' => 'required|string'
        ]);

        $producto = Producto::findOrFail($productoId);

        $especificacion = $producto->especificaciones()->create([
            'campo' => $request->campo,
            'descripcion' => $request->descripcion
        ]);

        return back()->with('success', 'Especificación agregada correctamente');
    }

    public function importarEspecificacionesExcel(Request $request, $productoId)
    {
        $request->validate([
            'archivo_excel' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $producto = Producto::findOrFail($productoId);

        try {
            $import = new EspecificacionesImport($producto);

            // Versión compatible con Laravel Excel 3.1
            Excel::import($import, $request->file('archivo_excel'));

            return back()->with('success', 'Especificaciones importadas correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    public function eliminarEspecificacion($id)
    {
        $especificacion = Especificacion::findOrFail($id);
        $especificacion->delete();

        return response()->json(['success' => true]);
    }
    public function getEspecificaciones($id)
    {
        $especificaciones = Especificacion::where('producto_id', $id)
            ->select('id', 'campo', 'descripcion')
            ->get()
            ->toArray();

        $producto = Producto::find($id, ['nombre', 'nro_parte']);

        return response()->json([
            'success' => true,
            'especificaciones' => $especificaciones,
            'producto' => $producto ? $producto->toArray() : null
        ]);
    }
    public function actualizarEspecificacion(Request $request, $id)
    {
        $especificacion = Especificacion::findOrFail($id);

        $validated = $request->validate([
            'campo' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string|max:1000',
        ]);

        $especificacion->update($validated);

        return response()->json(['success' => true]);
    }

    public function asignarFiltrosGenerico(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productos' => 'required|array',
            'productos.*' => ['required', 'json', function ($attribute, $value, $fail) {
                $decoded = json_decode($value, true);
                if (!is_array($decoded)) {
                    $fail('El formato de los filtros no es válido.');
                }
            }],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            foreach ($request->productos as $productoId => $filtrosJson) {
                $filtros = json_decode($filtrosJson, true);
                $producto = Producto::findOrFail($productoId);

                // Eliminar relaciones existentes
                $producto->filtros()->detach();

                // Crear nuevas relaciones
                foreach ($filtros as $asideId => $opciones) {
                    foreach ($opciones as $opcion) {
                        $producto->filtros()->attach($asideId, ['opcion' => $opcion]);
                    }
                }

                // Actualizar filtros_ids especificando la tabla correcta
                $producto->filtros_ids = $producto->filtros()
                    ->pluck('asides.id') // Especificamos la tabla para el campo id
                    ->unique()
                    ->toArray();

                $producto->save();
            }

            DB::commit();

            return back()->with('success', 'Filtros actualizados correctamente para todos los productos modificados.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar los filtros: ' . $e->getMessage());
        }
    }
    public function filtrarAjax(Request $request)
    {
        $modeloId = $request->input('modelo_id');
        $modelo = Modelo::with(['productos.filtros'])->findOrFail($modeloId);

        $productos = $modelo->productos;

        if ($request->query()) {
            $productos = $productos->filter(function ($producto) use ($request) {
                foreach ($request->query() as $key => $valores) {
                    if ($key === 'modelo_id') continue;

                    $valores = explode(',', $valores);

                    $filtroValido = $producto->filtros->contains(function ($filtro) use ($key, $valores) {
                        return Str::slug($filtro->nombre_aside) === $key
                            && in_array($filtro->pivot->opcion, $valores);
                    });

                    if (!$filtroValido) return false;
                }
                return true;
            })->values();
        }

        return response()->json($productos);
    }
    public function importMultipleEspecificaciones(Request $request)
    {
        // Depuración: Imprimir los datos recibidos
        \Log::info('Productos:', ['productos' => $request->input('productos')]);
        \Log::info('Archivos Excel:', ['archivos_excel' => $request->file('archivos_excel')]);

        $request->validate([
            'productos' => 'required|array',
            'productos.*' => 'integer|exists:productos,id',
            'archivos_excel' => 'required|array',
            'archivos_excel.*.*' => 'file|mimes:xlsx,xls|max:2048',
        ]);

        foreach ($request->input('productos') as $productoId) {
            $producto = Producto::find($productoId);
            if ($producto && isset($request->file('archivos_excel')[$productoId])) {
                foreach ($request->file('archivos_excel')[$productoId] as $file) {
                    // Procesar cada archivo para el producto
                    Excel::import(new EspecificacionesImport($producto), $file);
                }
            }
        }

        return response()->json(['message' => 'Especificaciones importadas correctamente.']);
    }

public function buscarPorModeloONroParte(Request $request)
{
    $query = \App\Producto::query();

    if ($request->filled('modelo_id')) {
        $query->where('modelo_id', $request->modelo_id);
    }

    if ($request->filled('nro_parte')) {
        $query->where('nro_parte', 'like', '%' . $request->nro_parte . '%');
    }

    $productos = $query->select('id', 'nombre', 'nro_parte')->get();

    return response()->json($productos);
}
public function importarEspecificaciones(Request $request)
{
    $request->validate([
        'producto_id' => 'required|exists:productos,id',
        'archivos_excel' => 'required|array',
        'archivos_excel.*' => 'file|mimes:xlsx,xls,csv|max:2048',
    ]);

    $producto = Producto::findOrFail($request->producto_id);

    try {
        foreach ($request->file('archivos_excel') as $archivo) {
            Excel::import(new EspecificacionesImport($producto), $archivo);
        }
        return response()->json(['message' => 'Especificaciones importadas correctamente.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al importar: ' . $e->getMessage()], 500);
    }
}
}
