<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;

class ConfiguracionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.administrador.configuracion.index');
    }

    public function buscar(Request $request)
    {
        $configs = Configuracion::orderBy('nombre', 'ASC');

        if($request->search) {
            $configs->where('nombre', 'LIKE', '%'.$request->search.'%');
        }

        $configs = $configs->paginate(10);
        return [
            'pagination' => [
                'total'         => $configs->total(),
                'current_page'  => $configs->currentPage(),
                'per_page'      => $configs->perPage(),
                'last_page'     => $configs->lastPage(),
                'from'          => $configs->firstItem(),
                'to'            => $configs->lastPage(),
                'index'         => ($configs->currentPage()-1)*$configs->perPage(),
            ],
            'configs'   => $configs,
            'ruta'      => request()->root()
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre'          => 'required|string|max:100',
            'descripcion'   => 'required|string|max:255',
        ]);

        try {

            DB::beginTransaction();

            $config = new Configuracion();
            $config->nombre = strtolower($request->nombre);
            $config->descripcion = $request->descripcion;
            $config->archivo = $request->archivo;
            $config->archivo_nombre = $request->archivo_nombre;
            $config->archivo_ruta = $request->ruta;
            $config->save();
                
            DB::commit();
    
            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Registro se guardo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar el Registro, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            //'nombre'     => 'required|string|max:100',
            'descripcion' => 'required|string|max:255',
        ]);

        try {

            DB::beginTransaction();

            $config = Configuracion::findOrFail($request->id);
            $config->descripcion = $request->descripcion;
            $config->archivo = $request->archivo;
            $config->archivo_nombre = $request->archivo_nombre;
            $config->archivo_ruta = $request->ruta;
            $config->save();
            
            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Registro se actualizo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar el Registro, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function file(Request $request)
    {
        try {
            
            if ($request->hasFile('archivo')) {
    
                $file = $request->file('archivo');
                $extension = $file->extension();
                $filename = time() . "." . $extension;
                $route = 'config/images';

                Storage::putFileAs('public/'.$route, $file, $filename);

                return [
                    'estado' => 'correcto',
                    'ruta' => $route,
                    'archivo' => $filename,
                ];
            } else {
                return [
                    'estado' => 'error'
                ];
            }
            
        } catch (\Throwable $th) {

            return [
                'estado' => 'error'
            ];
            
        }
    }
    

    public function show_file($id)
    {
        $file = Configuracion::findOrFail($id);
        if ($file) {
            return Storage::response('public/'.$file->ruta_archivo.'/'.$file->archivo);
        }

        abort(404);
    }

    public function delete_file(Request $request)
    {
        $file = Configuracion::findOrFail($request->id);
        Storage::delete('public/'.$file->archivo_ruta.'/'.$file->archivo);
        $file->archivo = null;
        $file->archivo_nombre = null;
        $file->archivo_ruta = null;
        $file->save();

        return [
            'estado' => 'success',
        ];
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $config = Configuracion::findOrFail($request->id);
            Storage::delete('public/'.$config->archivo_ruta.'/'.$config->archivo);
            $config->delete();

            
            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Registro se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Registro, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
