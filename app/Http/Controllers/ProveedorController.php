<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use DB;
use Auth;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.proveedores.index');
    }

    public function buscar(Request $request)
    {
        $proveedores = Proveedor::where('nombres', 'LIKE', "%{$request->search}%")
                    ->orWhere('numero_documento', 'LIKE', "%{$request->search}%");

        $proveedores = $proveedores->paginate(10);

        return [
            'pagination' => [
                'total' => $proveedores->total(),
                'current_page' => $proveedores->currentPage(),
                'per_page' => $proveedores->perPage(),
                'last_page' => $proveedores->lastPage(),
                'from' => $proveedores->firstItem(),
                'to' => $proveedores->lastPage(),
                'index' => ($proveedores->currentPage()-1)*$proveedores->perPage(),
            ],
            'proveedores' => $proveedores
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ruc'       => 'required|digits:11',
            'nombres'   => 'required|string',
            'email'     => 'required|email',
            'direccion' => 'required|string',
            'telefono'  => 'required|digits:9',
            'servicio'  => 'required|string',
        ]);

        try {

            DB::beginTransaction();

            $proveedor = new Proveedor();
            $proveedor->numero_documento = $request->ruc;
            $proveedor->nombres = strtoupper($request->nombres);
            $proveedor->email = $request->email;
            $proveedor->telefono = $request->telefono;
            $proveedor->direccion = strtoupper($request->direccion);
            $proveedor->servicio = strtoupper($request->servicio);
            $proveedor->save();

            DB::commit();
    
            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Proveedor se guardo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar el Proveedor, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'ruc'       => 'required|digits:11',
            'nombres'   => 'required|string',
            'email'     => 'required|email',
            'direccion' => 'required|string',
            'telefono'  => 'required|digits:9',
            'servicio'  => 'required|string',
        ]);

        try {

            DB::beginTransaction();

            $proveedor = Proveedor::findOrFail($request->id);
            $proveedor->numero_documento = $request->ruc;
            $proveedor->nombres = strtoupper($request->nombres);
            $proveedor->email = $request->email;
            $proveedor->telefono = $request->telefono;
            $proveedor->direccion = strtoupper($request->direccion);
            $proveedor->servicio = $request->servicio;
            $proveedor->save();
            
            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'Los datos del Proveedor se actualizaron correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar los datos del Proveedor, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $proveedor = Proveedor::findOrFail($request->id);
            $proveedor->delete();
            
            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Proveedor se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Proveedor, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }


    public function buscar_take(Request $request)
    {
        $proveedores = Proveedor::where('nombres', 'LIKE', "%{$request->search}%")->take(20)->get();

        return [
            'proveedores'  => $proveedores
        ];
    }

    public function nuevo(Request $request)
    {
        $this->validate($request, [
            /*'ruc'       => 'required|digits:11',*/
            'nombres'   => 'required|string',
            'telefono'  => 'required|digits:9',
        ]);

        try {

            DB::beginTransaction();

            $proveedor = new Proveedor();
            $proveedor->numero_documento = $request->ruc;
            $proveedor->nombres = strtoupper($request->nombres);
            $proveedor->email = $request->email;
            $proveedor->telefono = $request->telefono;
            $proveedor->direccion = strtoupper($request->direccion);
            $proveedor->servicio = strtoupper($request->servicio);
            $proveedor->save();

            DB::commit();
    
            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Proveedor se guardo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar el Proveedor, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
