<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Producto_drivers;

class DriversController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.drivers.index');
    }

    public function buscar(Request $request)
    {


        $drivers = Driver::select("id", "categoria","nombre", "version", "liberado", "tamano", "unidad", "gravedad", "activo", "link")
        ->where('nombre', 'LIKE', '%'.$request->search.'%')
        ->orWhere('id', 'LIKE', '%'.$request->search.'%');

        $drivers = $drivers -> paginate(10);

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
        ];
    }

    public function auto_buscar_producto(Request $request)
    {

        $producto = Producto::select('id','nombre')
            ->where('nombre', 'LIKE', '%'.$request->search.'%')
            ->orWhere('id', 'LIKE', '%'.$request->search.'%')
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria'            => 'required|string',
            'driver_nombre'        => 'required|string',
            'version'              => 'required|string',
            'liberado'             => 'required|string',
            'tamano'               => 'required|string',
            'unidad'               => 'required|string',
            'gravedad'             => 'required|string',
            'estado'               => 'required|string',

        ]);

        try {
            DB::beginTransaction();

            $driver = new Driver();
            $driver ->  categoria   = $request->categoria;
            $driver ->  nombre      = $request->driver_nombre;
            $driver ->  version     = $request->version;
            $driver ->  liberado    = $request->liberado;
            $driver ->  tamano      = $request->tamano;
            $driver ->  unidad      = $request->unidad;
            $driver ->  gravedad    = $request->gravedad;
            $driver ->  activo      = $request->estado;

            $route = 'DRIVERS/'.$driver->id;

            if ($request->hasFile('pdf_driver')) {
                $file = $request->file('pdf_driver');
                $extension = $file->extension();
                $file_name = 'KENYA_'.Str::random(10).'.'.$extension;

                Storage::putFileAs('public/'.$route, $file, $file_name);
                $driver->link = $route.'/'.$file_name;
            }

            $driver ->  save();


            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Driver se guardo correctamente.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar el Driver, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'id'                   => 'required|int',
            'categoria'            => 'required|string',
            'driver_nombre'        => 'required|string',
            'version'              => 'required|string',
            'liberado'             => 'required|string',
            'tamano'               => 'required|string',
            'unidad'               => 'required|string',
            'gravedad'             => 'required|string',
            'estado'               => 'required|string',
        ]);

        try {

            DB::beginTransaction();

            $driver = Driver::findOrFail($request->id);
            $driver -> categoria   = $request->categoria;
            $driver -> nombre      = $request->driver_nombre;
            $driver -> version     = $request->version;
            $driver -> liberado    = $request->liberado;
            $driver -> tamano      = $request->tamano;
            $driver -> unidad      = $request->unidad;
            $driver -> gravedad    = $request->gravedad;
            $driver -> activo      = $request->estado;

            $route = 'DRIVERS/'.$driver->id;

            if ($request->hasFile('pdf_driver')) {
                Storage::delete('public/'.$driver->link);

                $file = $request->file('pdf_driver');
                $extension = $file->extension();
                $file_name = 'KENYA_'.Str::random(10).'.'.$extension;

                Storage::putFileAs('public/'.$route, $file, $file_name);
                $driver->link = $route.'/'.$file_name;
            }

            $driver -> update();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'Los datos del Driver se actualizaron correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar los datos del Driver, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Driver $driver)
    {
        try {

            DB::beginTransaction();

            $driver = Driver::findOrFail($request->id);

            $route = 'DRIVERS/'.$driver->id;

            if ($driver->link) {
                Storage::delete('public/'.$driver->link);

                Storage::deleteDirectory('public/'.$route);
            }
            $driver -> delete();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Driver se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Driver, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

public function asignarSerie(Request $request)
{
    $request->validate([
        'driver_id' => 'required|integer|exists:producto_drivers,id',
        'series'    => 'required|array|min:1',
        'series.*'  => 'string|max:255'
    ]);

    try {
        DB::beginTransaction();

        $driver = Producto_drivers::findOrFail($request->driver_id);
        $driver->serie = $request->series; // Guarda como array/JSON
        $driver->save();

        DB::commit();

        return [
            'type' => 'success',
            'title' => 'Serie(s) asignada(s)',
            'message' => 'Las series fueron asignadas correctamente al driver.'
        ];
    } catch (\Throwable $th) {
        DB::rollBack();
        return [
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'No se pudo asignar la(s) serie(s).'
        ];
    }
}
}

