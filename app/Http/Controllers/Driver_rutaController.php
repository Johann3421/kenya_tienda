<?php

namespace App\Http\Controllers;
use App\Producto;
use App\Ruta;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Whoops\Run;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Driver_rutaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.garantia_ruta.index');
    }
    public function buscar(Request $request)
    {

        $drivers_ruta = Ruta::select("id", "rute","nombre_driver")
        ->where('rute', 'LIKE', '%'.$request->search.'%')
        ->orWhere('id', 'LIKE', '%'.$request->search.'%');

        $drivers_ruta = $drivers_ruta -> paginate(10);

        return [
            'pagination' => [
                'total' => $drivers_ruta->total(),
                'current_page' => $drivers_ruta->currentPage(),
                'per_page' => $drivers_ruta->perPage(),
                'last_page' => $drivers_ruta->lastPage(),
                'from' => $drivers_ruta->firstItem(),
                'to' => $drivers_ruta->lastPage(),
                'index' => ($drivers_ruta->currentPage() - 1) * $drivers_ruta->perPage(),
            ],
            'drivers_ruta' => $drivers_ruta,
        ];
    }
    public function store(Request $request)
    {
        $request->validate([
            // 'pdf_driver' => 'required|file|mimes:zip|max:1000000000',
            'nombre_driver'  => 'required',
        ]);

        try {
            DB::beginTransaction();

            $driver_ruta = new Ruta();
            //$driver_ruta ->  rute = $request->rute;
            $driver_ruta ->  nombre_driver = $request->nombre_driver;

            $driver_ruta ->  save();
            $route = 'DRIVERS/'.$driver_ruta->id;
            if ($request->hasFile('pdf_driver')) {
                $file = $request->file('pdf_driver');
                $extension = $file->extension();
                $file_name = 'KENYA_'.Str::random(10).'.'.$extension;
                Storage::putFileAs('public/'.$route, $file, $file_name);
                $driver_ruta->rute = $route.'/'.$file_name;
                $driver_ruta ->  save();
            }

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'La ruta se guardo correctamente.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar la ruta, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
    public function update(Request $request, Ruta  $drivers_ruta)
    {

        $request->validate([
            'id'            => 'required|int',
            //'rute'          => 'required',
            'nombre_driver' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $driver_ruta = Ruta::findOrFail($request->id);
            //$driver_ruta -> rute = $request->rute;
            $driver_ruta ->  nombre_driver = $request->nombre_driver;

            $route = 'DRIVERS/'.$driver_ruta->id;

            if ($request->hasFile('pdf_driver')) {
                Storage::delete('public/'.$driver_ruta->rute);

                $file = $request->file('pdf_driver');
                $extension = $file->extension();
                $file_name = 'KENYA_'.Str::random(10).'.'.$extension;

                Storage::putFileAs('public/'.$route, $file, $file_name);
                $driver_ruta->rute = $route.'/'.$file_name;
            }

            $driver_ruta -> update();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'Los datos del Garantia se actualizaron correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar los datos del Garantia, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request, Ruta  $drivers_ruta)
    {
        try {

            DB::beginTransaction();

            $driver_ruta = Ruta::findOrFail($request->id);
            $driver_ruta -> delete();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'La Ruta se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar la Ruta, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
