<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use DB;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.administrador.perfiles.roles');
    }

    public function buscar(Request $request)
    {
        $roles = Role::where('name', 'LIKE', "%{$request->search}%")
            ->with([
                'permissions' => function($query) {
                    $query->select('id', 'name');
                }
            ])
            ->paginate(10);

        $permisos = Permission::all(['id', 'name']);

        return [
            'pagination' => [
                'total' => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastPage(),
                'index' => ($roles->currentPage()-1)*$roles->perPage(),
            ],
            'roles' => $roles,
            'permisos' => $permisos
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string|unique:roles',
            'permisos'   => 'required',
        ]);

        try {

            DB::beginTransaction();

            $role = Role::create(['name' => strtolower($request->name)]);

            $role->syncPermissions($request->permisos);
            
            DB::commit();
    
            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Rol se guardo y se le asignaron los Permisos correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar el Rol, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string|unique:roles,name,'.$request->id,
            'permisos'   => 'required',
        ]);

        try {

            DB::beginTransaction();

            $role = Role::findOrFail($request->id);
            $role->name = strtolower($request->name);
            $role->save();

            if ($request->modificado == 'SI') {

                $role->revokePermissionTo($request->permisos);

                $role->syncPermissions($request->permisos);
            }
            
            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Rol se actualizo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar el Rol, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $rol = Role::findOrFail($request->id);
            $rol->delete();
            
            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Rol se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Rol, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
