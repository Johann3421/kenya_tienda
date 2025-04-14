<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use App\Garantia;
use Illuminate\Support\Facades\DB;

class GarantiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.garantias.index');
    }

    public function buscar(Request $request)
    {
        $garantias = Garantia::join('productos', 'productos.id', '=',"garantia.producto_id")
        ->select("garantia.id", "productos.id as producto_id", "productos.nombre as producto_descripcion","garantia.fecha_venta", "garantia.garantia", "garantia.fecha_Vencimiento", "garantia.serie", "garantia.activo")
        ->where('garantia.garantia', 'LIKE', '%'.$request->search.'%')
        ->orWhere('garantia.id', 'LIKE', '%'.$request->search.'%');

        $garantias = $garantias -> paginate(10);

        return [
            'pagination' => [
                'total' => $garantias->total(),
                'current_page' => $garantias->currentPage(),
                'per_page' => $garantias->perPage(),
                'last_page' => $garantias->lastPage(),
                'from' => $garantias->firstItem(),
                'to' => $garantias->lastPage(),
                'index' => ($garantias->currentPage() - 1) * $garantias->perPage(),
            ],
            'garantias' => $garantias
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
            'producto_id'          => 'required|int',
            'garantia'             => 'required|string',
            'fecha_venta'          => 'required',
            'fecha_Vencimiento'    => 'required',
            'serie'                => 'required|digits:14',
            'estado'               => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $garantia = new Garantia();
            $garantia ->  producto_id       = $request->producto_id;
            $garantia ->  garantia          = $request->garantia;
            $garantia ->  fecha_venta       = $request->fecha_venta;
            $garantia ->  fecha_Vencimiento = $request->fecha_Vencimiento;
            $garantia ->  serie             = $request->serie;
            $garantia ->  activo            = $request->estado;
            $garantia ->  save();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Garantia se guardo correctamente.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar el Garantia, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Garantia  $garantias
     * @return \Illuminate\Http\Response
     */
    public function show(Garantia  $garantias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Garantia  $garantias
     * @return \Illuminate\Http\Response
     */
    public function edit(Garantia  $garantias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Garantia  $garantias
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Garantia  $garantias)
    {

        $request->validate([
            'id'                   => 'required|int',
            'producto_id'          => 'required|int',
            'garantia'             => 'required|string',
            'fecha_venta'          => 'required',
            'fecha_Vencimiento'    => 'required',
            'serie'                => 'required|string',
            'estado'               => 'required|string'
        ]);

        try {

            DB::beginTransaction();

            $garantia = Garantia::findOrFail($request->id);
            $garantia ->  producto_id       = $request->producto_id;
            $garantia ->  garantia          = $request->garantia;
            $garantia ->  fecha_venta       = $request->fecha_venta;
            $garantia ->  fecha_Vencimiento = $request->fecha_Vencimiento;
            $garantia ->  serie             = $request->serie;
            $garantia ->  activo            = $request->estado;
            $garantia -> update();

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Garantia  $garantias
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Garantia  $garantias)
    {
        try {

            DB::beginTransaction();

            $garantia = Garantia::findOrFail($request->id);
            $garantia -> delete();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Garantia se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Garantia, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
