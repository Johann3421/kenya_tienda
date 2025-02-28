<?php

namespace App\Http\Controllers;

use App\Producto_drivers;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductoDriversController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('sistema.drivers.index');
    }

    public function buscar(Request $request) {

        $drivers = Producto_drivers::join('productos', 'productos.id', '=', "producto_drivers.producto_id")
            ->select("producto_drivers.id", "productos.id as producto_id", "productos.nombre as producto_descripcion", "producto_drivers.categoria", "producto_drivers.nombre as driver_nombre", "producto_drivers.version", "producto_drivers.liberado", "producto_drivers.tamano", "producto_drivers.unidad", "producto_drivers.gravedad", "producto_drivers.activo", "producto_drivers.link")
            ->where('producto_drivers.nombre', 'LIKE', '%' . $request->search . '%')
            ->orWhere('producto_drivers.id', 'LIKE', '%' . $request->search . '%');
        $productos = Producto::all();

        $drivers = $drivers->paginate(10);

        return [
            'pagination' => [
                'total' => $drivers->total(),
                'current_page' => $drivers->currentPage(),
                'per_page' => $drivers->perPage(),
                'last_page' => $drivers->lastPage(),
                'from' => $drivers->firstItem(),
                'to' => $drivers->lastPage(),
                'index' => ($drivers->currentPage() - 1) * $drivers->perPage(),
            ],
            'drivers' => $drivers,
            'productos' => $productos
        ];
    }

    public function auto_buscar_producto(Request $request) {

        $producto = Producto::select('id', 'nombre')
            ->where('nombre', 'LIKE', '%' . $request->search . '%')
            ->orWhere('id', 'LIKE', '%' . $request->search . '%')
            ->get();

        return [
            'producto' => $producto
        ];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $request->validate(
            [
                'producto_id' => 'required|int',
                'categoria' => 'required|string',
                'driver_nombre' => 'required|string',
                'version' => 'required|string',
                'liberado' => 'required|string',
                'tamano' => 'required|string',
                'unidad' => 'required|string',
                'gravedad' => 'required|string',
                'estado' => 'required|string',
                //'link' => 'required|string',
            ]
        );

        try {
            DB::beginTransaction();

            $driver = new Producto_drivers();
            $driver->producto_id = $request->producto_id;
            $driver->categoria = $request->categoria;
            $driver->nombre = $request->driver_nombre;
            $driver->version = $request->version;
            $driver->liberado = $request->liberado;
            $driver->tamano = $request->tamano;
            $driver->unidad = $request->unidad;
            $driver->gravedad = $request->gravedad;
            $driver->activo = $request->estado;
            $driver->link = $request->link;

            $driver->save();


            DB::commit();

            return [
                'type' => 'success',
                'title' => 'CORRECTO: ',
                'message' => 'El Driver se guardo correctamente.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type' => 'danger',
                'title' => 'ERROR: ',
                'message' => 'Ocurrio un error al guardar el Driver, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto_drivers  $producto_drivers
     * @return \Illuminate\Http\Response
     */
    public function show(Producto_drivers $producto_drivers) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto_drivers  $producto_drivers
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto_drivers $producto_drivers) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto_drivers  $producto_drivers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto_drivers $producto_drivers) {
        $request->validate(
            [
                'id' => 'required|int',
                'producto_id' => 'required|int',
                'categoria' => 'required|string',
                'driver_nombre' => 'required|string',
                'version' => 'required|string',
                'liberado' => 'required|string',
                'tamano' => 'required|string',
                'unidad' => 'required|string',
                'gravedad' => 'required|string',
                'estado' => 'required|string',
                'link' => 'required|string',

            ]
        );

        try {

            DB::beginTransaction();

            $driver = Producto_drivers::findOrFail($request->id);
            $driver->producto_id = $request->producto_id;
            $driver->categoria = $request->categoria;
            $driver->nombre = $request->driver_nombre;
            $driver->version = $request->version;
            $driver->liberado = $request->liberado;
            $driver->tamano = $request->tamano;
            $driver->unidad = $request->unidad;
            $driver->gravedad = $request->gravedad;
            $driver->activo = $request->estado;
            $driver->link = $request->link;

            $driver->update();

            DB::commit();

            return [
                'type' => 'success',
                'title' => 'CORRECTO: ',
                'message' => 'Los datos del Driver se actualizaron correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type' => 'danger',
                'title' => 'ERROR: ',
                'message' => 'Ocurrio un error al actualizar los datos del Driver, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto_drivers  $producto_drivers
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Producto_drivers $producto_drivers) {
        try {

            DB::beginTransaction();

            $driver = Producto_drivers::findOrFail($request->id);

            $driver->delete();

            DB::commit();

            return [
                'type' => 'success',
                'title' => 'CORRECTO: ',
                'message' => 'El Driver se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type' => 'danger',
                'title' => 'ERROR: ',
                'message' => 'Ocurrio un error al eliminar el Driver, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
