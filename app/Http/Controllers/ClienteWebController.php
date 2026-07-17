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
        try {
            $clientes = \App\Models\UserPrecio::query();

            if ($request->search) {
                $clientes->where(function ($q) use ($request) {
                    $q->where('nombres', 'LIKE', "%{$request->search}%")
                      ->orWhere('dni', 'LIKE', "%{$request->search}%")
                      ->orWhere('username', 'LIKE', "%{$request->search}%")
                      ->orWhere('email', 'LIKE', "%{$request->search}%");
                });
            }

            // Ordenar por más recientes
            $clientes->orderBy('id', 'desc');

            $clientes = $clientes->paginate(10);

            return [
                'pagination' => [
                    'total'        => $clientes->total(),
                    'current_page' => $clientes->currentPage(),
                    'per_page'     => $clientes->perPage(),
                    'last_page'    => $clientes->lastPage(),
                    'from'         => $clientes->firstItem(),
                    'to'           => $clientes->lastItem(),
                    'index'        => ($clientes->currentPage() - 1) * $clientes->perPage(),
                ],
                'clientes' => $clientes
            ];
        } catch (\Throwable $e) {
            \Log::error('ClienteWebController@buscar error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return response()->json([
                'type' => 'danger',
                'title' => 'ERROR: ',
                'message' => $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombres'  => 'required|string|max:100',
            'dni'      => 'required|string|max:20',
            'email'    => 'required|email|unique:users_precios,email',
            'telefono' => 'nullable|digits:9',
            'username' => 'required|string|unique:users_precios,username',
            'password' => 'required|min:8',
        ]);

        try {
            DB::beginTransaction();

            $user = new \App\Models\UserPrecio();
            $user->nombres      = strtoupper($request->nombres);
            $user->ape_paterno  = 'Web'; // Hardcoded para identificar que fue desde admin/web
            $user->ape_materno  = $request->dni; // Guardar RUC
            $user->dni          = substr($request->dni, 0, 8); // DNI requiere 8
            $user->email        = $request->email;
            $user->telefono     = $request->telefono ?? '';
            $user->username     = $request->username;
            $user->password     = Hash::make($request->password);
            $user->activo       = 'SI';
            $user->save();

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
            'dni'      => 'required|string|max:20',
            'email'    => 'required|email|unique:users_precios,email,' . $request->id,
            'telefono' => 'nullable|digits:9',
            'username' => 'required|string|unique:users_precios,username,' . $request->id,
        ]);

        try {
            DB::beginTransaction();

            $user = \App\Models\UserPrecio::findOrFail($request->id);
            $user->nombres     = strtoupper($request->nombres);
            $user->ape_materno = $request->dni;
            $user->dni         = substr($request->dni, 0, 8);
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

            $user = \App\Models\UserPrecio::findOrFail($request->id);
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

    // ── Landing portal: Mi Perfil ───────────────────────────────────────────
    public function perfil()
    {
        $user = auth()->user();
        return view('cliente.perfil', compact('user'));
    }

    public function actualizarPerfil(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'nombres'    => 'required|string|max:255',
            'telefono'   => 'nullable|string|max:20',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|min:6|confirmed',
        ]);

        $data = $request->only(['nombres', 'telefono', 'email']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    // ── Landing portal: Mis Cotizaciones ───────────────────────────────────
    public function cotizaciones()
    {
        $user = auth()->user();
        // ponytail: no hay tabla de cotizaciones aún — vista placeholder lista para conectar
        return view('cliente.cotizaciones', compact('user'));
    }
}
