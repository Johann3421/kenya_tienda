<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Auth;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class PermisoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.administrador.perfiles.permisos');
    }

    public function buscar(Request $request)
    {
        $permisos = Permission::where('name', 'LIKE', "%{$request->search}%")->paginate(10);

        return [
            'pagination' => [
                'total' => $permisos->total(),
                'current_page' => $permisos->currentPage(),
                'per_page' => $permisos->perPage(),
                'last_page' => $permisos->lastPage(),
                'from' => $permisos->firstItem(),
                'to' => $permisos->lastPage(),
                'index' => ($permisos->currentPage()-1)*$permisos->perPage(),
            ],
            'permisos' => $permisos
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string|unique:permissions',
        ]);

        try {

            FacadesDB::beginTransaction();

            $permiso = Permission::create(['name' => strtolower($request->name)]);
                
            FacadesDB::commit();
    
            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Permiso se guardo correctamente.'
            ];

        } catch (\Throwable $th) {
            FacadesDB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar el Permiso, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string|unique:permissions,name,'.$request->id
        ]);

        try {

            FacadesDB::beginTransaction();

            $permiso = Permission::findOrFail($request->id);
            $permiso->name = strtolower($request->name);
            $permiso->save();
            
            FacadesDB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Permiso se actualizo correctamente.'
            ];

        } catch (\Throwable $th) {
            FacadesDB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar el Permiso, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            FacadesDB::beginTransaction();

            $permiso = Permission::findOrFail($request->id);
            $permiso->delete();
            
            FacadesDB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Permiso se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            FacadesDB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Permiso, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
