<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.clientes.index');
    }

    public function buscar(Request $request)
    {
        $clientes = Cliente::where('nombres', 'LIKE', "%{$request->search}%")
        ->orWhere('id', 'LIKE', "%{$request->search}%")
        ->withCount('getSoportes');

        $clientes = $clientes->paginate(10);

        return [
            'pagination' => [
                'total' => $clientes->total(),
                'current_page' => $clientes->currentPage(),
                'per_page' => $clientes->perPage(),
                'last_page' => $clientes->lastPage(),
                'from' => $clientes->firstItem(),
                'to' => $clientes->lastPage(),
                'index' => ($clientes->currentPage()-1)*$clientes->perPage(),
            ],
            'clientes' => $clientes
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'numero_documento'  => 'required|Integer|digits_between:8,11',
            'nombres'           => 'required|string',
            'direccion'         => 'required|string',
            'email'             => 'required|email',
            'telefono'          => 'required|digits:9',
        ]);

        try {

            DB::beginTransaction();

            if (!$cliente = Cliente::find($request->numero_documento)) {
                $cliente = new Cliente();
                $cliente->id = $request->numero_documento;
                $cliente->tipo = $request->tipo_documento;
                if ($request->tipo_documento == 'RUC') { //6=RUC, 1=DNI
                    $cliente->codigo_sunat = 6;
                } else {
                    $cliente->codigo_sunat = 1;
                }
                $cliente->nombres = strtoupper($request->nombres);
                $cliente->direccion = strtoupper($request->direccion);
                $cliente->email = strtoupper($request->email);
                $cliente->celular = $request->telefono;
                $cliente->user_id = Auth::user()->id;
                $cliente->save();

                DB::commit();

                return [
                    'type'     =>  'success',
                    'title'    =>  'CORRECTO: ',
                    'message'  =>  'El Cliente se guardo correctamente.'
                ];
            } else {
                return [
                    'type'     =>  'warning',
                    'title'    =>  'error: ',
                    'message'  =>  'El Cliente ya se encuentra registrado.',
                    'errors'    =>  [
                        'numero_documento' => ['Cliente ya existe.']
                    ]
                ];
            }


        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar el Cliente, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'direccion'         => 'required|string',
            'email'             => 'required|email',
            'telefono'          => 'required|digits:9',
        ]);

        try {

            DB::beginTransaction();

            $cliente = Cliente::findOrFail($request->id);
            $cliente->direccion = strtoupper($request->direccion);
            $cliente->email = strtoupper($request->email);
            $cliente->celular = $request->telefono;
            $cliente->save();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'Los datos del Cliente se actualizaron correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar los datos del Cliente, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $cliente = Cliente::findOrFail($request->id);
            $cliente->delete();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Cliente se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Cliente, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
