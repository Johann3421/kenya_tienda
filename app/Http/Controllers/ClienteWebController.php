<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use App\User;
use Illuminate\Support\Facades\DB;

class ClienteWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.administrador.clientes-web.index');
    }

    public function buscar(Request $request)
    {
        // Asegurar que el rol existe (idempotente)
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'cliente_web', 'guard_name' => 'web']);

        try {
            $clientes = User::role('cliente_web')
                ->with(['roles' => function ($query) {
                    $query->select('id', 'name');
                }]);

            if ($request->search) {
                $clientes->where(function ($q) use ($request) {
                    $q->where('nombres', 'LIKE', "%{$request->search}%")
                      ->orWhere('ape_paterno', 'LIKE', "%{$request->search}%")
                      ->orWhere('username', 'LIKE', "%{$request->search}%")
                      ->orWhere('email', 'LIKE', "%{$request->search}%");
                });
            }

            $clientes = $clientes->paginate(10);

            return [
                'pagination' => [
                    'total'        => $clientes->total(),
                    'current_page' => $clientes->currentPage(),
                    'per_page'     => $clientes->perPage(),
                    'last_page'    => $clientes->lastPage(),
                    'from'         => $clientes->firstItem(),
                    'to'           => $clientes->to,
                    'index'        => ($clientes->currentPage() - 1) * $clientes->perPage(),
                ],
                'clientes' => $clientes
            ];
        } catch (\Throwable $e) {
            \Log::error('ClienteWebController@buscar error: ' . $e->getMessage());
            return response()->json([
                'type' => 'danger',
                'title' => 'ERROR: ',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombres'  => 'required|string|max:100',
            'paterno'  => 'required|string|max:100',
            'materno'  => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'telefono' => 'nullable|digits:9',
            'username' => 'required|string|unique:users',
            'password' => 'required|min:8',
        ]);

        try {
            DB::beginTransaction();

            $user = new User();
            $user->nombres      = strtoupper($request->nombres);
            $user->ape_paterno  = strtoupper($request->paterno);
            $user->ape_materno  = strtoupper($request->materno);
            $user->email        = $request->email;
            $user->telefono     = $request->telefono ?? '';
            $user->username     = $request->username;
            $user->password     = Hash::make($request->password);
            $user->dni          = $request->dni ?? '00000000';
            $user->save();

            // Asignar siempre el rol cliente_web
            $user->assignRole('cliente_web');

            DB::commit();

            return [
                'type'    => 'success',
                'title'   => 'CORRECTO: ',
                'message' => 'El cliente fue creado correctamente y tiene acceso al portal de cotizaciones.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'type'    => 'danger',
                'title'   => 'ERROR: ',
                'message' => 'Ocurrió un error al crear el cliente: ' . $th->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'nombres'  => 'required|string|max:100',
            'paterno'  => 'required|string|max:100',
            'materno'  => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $request->id,
            'telefono' => 'nullable|digits:9',
            'username' => 'required|string|unique:users,username,' . $request->id,
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $user->nombres     = strtoupper($request->nombres);
            $user->ape_paterno = strtoupper($request->paterno);
            $user->ape_materno = strtoupper($request->materno);
            $user->email       = $request->email;
            $user->telefono    = $request->telefono ?? $user->telefono;
            $user->username    = $request->username;
            $user->activo      = $request->activo ?? $user->activo;

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            DB::commit();

            return [
                'type'    => 'success',
                'title'   => 'CORRECTO: ',
                'message' => 'Los datos del cliente se actualizaron correctamente.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'type'    => 'danger',
                'title'   => 'ERROR: ',
                'message' => 'Ocurrió un error al actualizar el cliente: ' . $th->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $user->removeRole('cliente_web');
            $user->delete();

            DB::commit();

            return [
                'type'    => 'success',
                'title'   => 'CORRECTO: ',
                'message' => 'El cliente fue eliminado correctamente.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'type'    => 'danger',
                'title'   => 'ERROR: ',
                'message' => 'Ocurrió un error al eliminar el cliente: ' . $th->getMessage()
            ];
        }
    }
}
