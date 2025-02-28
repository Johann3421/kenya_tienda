<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\User;
use DB;
use Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.administrador.usuarios.index');
    }

    public function buscar(Request $request)
    {
        $usuarios = User::where('id', '!=', 1)
        ->with([
            'roles' => function($query) {
                $query->select('id', 'name');
            }
        ]);

        if ($request->search) {
            $usuarios->where('nombres', 'LIKE', "%{$request->search}%")
                    ->orWhere('ape_paterno', 'LIKE', "%{$request->search}%")
                    ->orWhere('ape_materno', 'LIKE', "%{$request->search}%");
        }

        $usuarios = $usuarios->paginate(10);

        $roles = Role::orderBy('name', 'ASC')->select('id', 'name')->get();

        return [
            'pagination' => [
                'total' => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page' => $usuarios->perPage(),
                'last_page' => $usuarios->lastPage(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastPage(),
                'index' => ($usuarios->currentPage()-1)*$usuarios->perPage(),
            ],
            'roles' => $roles,
            'usuarios' => $usuarios
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'dni'       => 'required|digits:8',
            'nombres'   => 'required|string',
            'paterno'   => 'required|string',
            'materno'   => 'required|string',
            'telefono'  => 'required|digits:9',
            'email'     => 'required|email',
            'username'  => 'required|string|unique:users',
            'password'  => 'required|min:8',
            'perfil'    => 'required',
        ]);

        try {

            DB::beginTransaction();

            $user = new User();
            $user->dni = $request->dni;
            $user->nombres = strtoupper($request->nombres);
            $user->ape_paterno = strtoupper($request->paterno);
            $user->ape_materno = strtoupper($request->materno);
            $user->telefono = $request->telefono;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->save();

            $user->assignRole($request->perfil);

            DB::commit();
    
            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Usuario se guardo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th.'Ocurrio un error al guardar el Usuario, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'dni'       => 'required|digits:8',
            'nombres'   => 'required|string',
            'paterno'   => 'required|string',
            'materno'   => 'required|string',
            'telefono'  => 'required|digits:9',
            'email'     => 'required|email',
            'username'  => 'required|string|unique:users,username,'.$request->id,
            'perfil'    => 'required',
        ]);

        try {

            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $user->dni = $request->dni;
            $user->nombres = strtoupper($request->nombres);
            $user->ape_paterno = strtoupper($request->paterno);
            $user->ape_materno = strtoupper($request->materno);
            $user->telefono = $request->telefono;
            $user->email = $request->email;
            $user->username = $request->username;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->activo = $request->activo;
            $user->save();

            if ($request->modificado == 'SI' ) {
                if ($request->perfil_anterior) {
                    $user->removeRole($request->perfil_anterior);
                }
                $user->assignRole($request->perfil);
            }
            
            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'Los datos del Usuario se actualizo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th.' Ocurrio un error al actualizar los datos del Usuario, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $user->delete();
            
            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Usuario se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Usuario, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
