<?php

namespace App\Http\Controllers;

use App\DetallePedido;
use App\Models\ViewPedido;
use App\Pedido;
use App\Producto;
use App\Serie;
use App\User;
use Carbon\Carbon;
use App\Models\Proveedor;
use App\Models\Cliente;
use App\Models\ProveedorPedido;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function inicio()
    {
        /*$pedidos = Pedido::where('activo', 'SI')
        ->with('getCliente', 'getDetalles')->find(3);
        dd($pedidos);*/
        return view('sistema.pedidos.index');
    }

    public function buscar(Request $request)
    {
        $estados = ViewPedido::first();

        $pedidos = Pedido::where('activo', 'SI')
        ->with('getCliente', 'getDetalles', 'getProveedorPedido')
        ->orderBy('id', 'DESC');

        if ($request->search) {
            /*switch ($request->search_por) {
                case 'codigo_barras':
                    $size = strlen($request->search);
                    if ($size == 11) {
                        $codigo = substr($request->search, 0, 7);
                        $id = intval(substr($request->search, 7, 4));
                    } else {
                        $id = null;
                    }
                    $pedidos->where('id', $id);
                    break;
                case 'cliente':
                    $cliente = $request->search;
                    $pedidos->whereHas('getCliente', function ($query) use($cliente) {
                        $query->where('id', 'like', '%'.$cliente.'%')->orWhere('nombres', 'like', '%'.$cliente.'%');
                    });
                    break;
                case 'estado':
                    $pedidos->where($request->search_por, $request->search);
                    break;
            }*/
            $buscar = $request->search;
            $pedidos->where(function ($query) use ($buscar){
                $cliente = $buscar;
                $query->where('codigo_barras', 'like', '%'.$buscar.'%')
                      ->orWhereHas('getCliente', function ($q) use($cliente) {
                        $q->where('id', 'like', '%'.$cliente.'%')->orWhere('nombres', 'like', '%'.$cliente.'%');
                    });
            });
        }

        $pedidos = $pedidos->paginate(10);

        return [
            'pagination' => [
                'total' => $pedidos->total(),
                'current_page' => $pedidos->currentPage(),
                'per_page' => $pedidos->perPage(),
                'last_page' => $pedidos->lastPage(),
                'from' => $pedidos->firstItem(),
                'to' => $pedidos->lastPage(),
                'index' => ($pedidos->currentPage()-1)*$pedidos->perPage(),
            ],
            'pedidos' => $pedidos,
            'estados' => $estados,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'fecha_registro'    => 'required',
            'fecha_entrega'     => 'required',
            'tipo_entrega'      => 'required',
            /*'forma_envio'       => 'required',*/
            'estado_entrega'   => 'required',
            'numero_documento'  => 'required|digits_between:8,11',
            'acuenta'           => 'required|numeric',
            'costo_total'       => 'required|numeric',
            'saldo_total'       => 'required|numeric',
        ]);

        try {

            DB::beginTransaction();
            $date = Carbon::now();

            if (!$cliente = Cliente::find($request->numero_documento)) {
                $cliente = new Cliente();
                $cliente->id = $request->numero_documento;
                if (strlen($request->numero_documento) == 11) { //6=RUC, 1=DNI
                    $cliente->tipo = 'RUC';
                    $cliente->codigo_sunat = 6;
                } else {
                    $cliente->tipo = 'DNI';
                    $cliente->codigo_sunat = 1;
                }
            }
            $cliente->nombres = Str::upper($request->nombres);
            $cliente->direccion = Str::upper($request->direccion);
            $cliente->email = Str::upper($request->email);
            $cliente->celular = $request->celular;
            $cliente->user_id = Auth::user()->id;
            $cliente->save();

            /*if ($request->ruc_proveedor) {
                if (!$proveedor = Proveedor::where($request->ruc_proveedor)->first()) {
                    $proveedor = new Proveedor();
                    $proveedor->numero_documento = $request->ruc_proveedor;
                }
                $proveedor->nombres = Str::upper($request->nombres_proveedor);
                $proveedor->email = $request->email_proveedor;
                $proveedor->telefono = $request->celular_proveedor;
                $proveedor->direccion = Str::upper($request->direccion_proveedor);
                $proveedor->servicio = 'EQUIPOS';
                $proveedor->save();
            }*/

            $pedido = new Pedido();
            $pedido->user_id = Auth::user()->id;
            $pedido->cliente_id = $request->numero_documento;
            $pedido->fecha_registro = $date->format('Y-m-d H:i:s');
            $pedido->fecha_entrega = $request->fecha_entrega;
            $pedido->tipo_entrega = $request->tipo_entrega;
            $pedido->estado_entrega = $request->estado_entrega;
            $pedido->detalle_envio = $request->detalle_envio;
            $pedido->acuenta = $request->acuenta;
            $pedido->costo_total = $request->costo_total;
            $pedido->saldo_total = $request->saldo_total;
            $pedido->observacion = $request->observacion;
            $pedido->save();

            $pedido->codigo_barras = 'KENYA'.Str::padLeft($pedido->id, 4, '0');
            $pedido->save();

            foreach ($request->detalles as $value) {
                $detalle = new DetallePedido();
                $detalle->pedido_id = $pedido->id;
                $detalle->descripcion = $value['descripcion'];
                $detalle->precio = $value['precio'];
                $detalle->cantidad = $value['cantidad'];
                $detalle->importe = $value['importe'];
                $detalle->save();
            }

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Pedido se guardo correctamente.',
                'pedido_id'  =>  $pedido->id,
                'pedido_barra'  =>  $pedido->codigo_barras,
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th.' Ocurrio un error al guardar el Pedido, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'fecha_registro'    => 'required',
            'fecha_entrega'     => 'required',
            'tipo_entrega'      => 'required',
            /*'forma_envio'       => 'required',*/
            'estado_entrega'   => 'required',
            'numero_documento'  => 'required|digits_between:8,11',
            'acuenta'           => 'required|numeric',
            'costo_total'       => 'required|numeric',
            'saldo_total'       => 'required|numeric',
        ]);

        try {

            DB::beginTransaction();
            $data = null;

            if (!$cliente = Cliente::find($request->numero_documento)) {
                $cliente = new Cliente();
                $cliente->id = $request->numero_documento;
                if (strlen($request->numero_documento) == 11) { //6=RUC, 1=DNI
                    $cliente->tipo = 'RUC';
                    $cliente->codigo_sunat = 6;
                } else {
                    $cliente->tipo = 'DNI';
                    $cliente->codigo_sunat = 1;
                }
            }
            $cliente->nombres = Str::upper($request->nombres);
            $cliente->direccion = Str::upper($request->direccion);
            $cliente->email = Str::upper($request->email);
            $cliente->celular = $request->celular;
            $cliente->user_id = Auth::user()->id;
            $cliente->save();

            $pedido = Pedido::findOrFail($request->id);
            $pedido->user_id = Auth::user()->id;
            $pedido->cliente_id = $request->numero_documento;
            $pedido->fecha_entrega = $request->fecha_entrega;
            $pedido->tipo_entrega = $request->tipo_entrega;
            $pedido->estado_entrega = $request->estado_entrega;
            $pedido->detalle_envio = $request->detalle_envio;
            $pedido->acuenta = $request->acuenta;
            $pedido->costo_total = $request->costo_total;
            $pedido->saldo_total = $request->saldo_total;
            $pedido->observacion = $request->observacion;
            $pedido->save();

            if (count($request->detalle_proveedor) > 0) {
                foreach ($request->detalle_proveedor as $prov) {
                    //if (!$proveedor = Proveedor::where('nombres', $request->nombres)->first()) {
                    if (!$proveedor = Proveedor::find($prov['id'])) {
                        $proveedor = new Proveedor();
                        $proveedor->servicio = 'EQUIPOS';
                        $proveedor->nombres = Str::upper($prov['nombres']);
                    }
                    $proveedor->numero_documento = $prov['ruc'];
                    $proveedor->email = Str::lower($prov['email']);
                    $proveedor->telefono = $prov['celular'];
                    $proveedor->direccion = Str::upper($prov['direccion']);
                    $proveedor->save();

                    if ( !$pedidoproveedor = ProveedorPedido::where('proveedor_id', $proveedor->id)->where('pedido_id', $pedido->id)->first() ) {
                        $pedidoproveedor = new ProveedorPedido();
                        $pedidoproveedor->pedido_id = $pedido->id;
                        $pedidoproveedor->proveedor_id = $proveedor->id;
                        $pedidoproveedor->ruc = $prov['ruc'];
                        $pedidoproveedor->nombres = Str::upper($prov['nombres']);
                        $pedidoproveedor->celular = $prov['celular'];;
                    }

                    if ( count($prov['detalles']) > 0 ) {
                        foreach ($prov['detalles'] as $detalle) {
                            if ($data == null) {
                                $data = '{"pedido_id":'.$detalle['pedido_id'].',"pedido_descripcion":"'.$detalle['pedido_descripcion'].'","precio":"'.$detalle['precio'].'","cantidad":"'.$detalle['cantidad'].'","importe":"'.$detalle['importe'].'"}';
                            } else {
                                $data = $data.', {"pedido_id":'.$detalle['pedido_id'].',"pedido_descripcion":"'.$detalle['pedido_descripcion'].'","precio":"'.$detalle['precio'].'","cantidad":"'.$detalle['cantidad'].'","importe":"'.$detalle['importe'].'"}';
                            }
                        }
                    }
                    $pedidoproveedor->detalles = '['.$data.']';
                    $pedidoproveedor->save();
                }
            }

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Pedido se actualizo correctamente.',
                'error'    =>  ''
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar el Pedido, intente nuevamente o contacte al Administrador del Sistema.',
                'error'    =>  ': '.$th
            ];
        }
    }

    public function detalle_add(Request $request)
    {
        try {
            DB::beginTransaction();

            $pedido = Pedido::findOrFail($request->id);
            $pedido->costo_total= $request->costo;
            $pedido->saldo_total = $request->saldo;
            $pedido->save();

            $detalle = new DetallePedido();
            $detalle->pedido_id = $pedido->id;
            $detalle->descripcion = Str::upper($request->descripcion);
            $detalle->precio = $request->precio;
            ///$detalle->cantidad_proveedor = $request->cantidad_proveedor;
            $detalle->cantidad= $request->cantidad;
            $detalle->importe = $request->importe;
            $detalle->save();

            DB::commit();

            return [
                'detalles'  =>  $pedido->getDetalles,
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'error' => $th.' Ocurrio un error al guardar el detalle, intente nuevamente.'
            ];
        }
    }

    public function detalle_delete(Request $request)
    {
        try {
            DB::beginTransaction();

            DetallePedido::destroy($request->id);

            $pedido = Pedido::findOrFail($request->pedido);
            $pedido->costo_total = $request->costo;
            $pedido->saldo_total = $request->saldo;
            $pedido->save();

            DB::commit();

            return [
                'detalles'  =>  $pedido->getDetalles,
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'error' => $th.' Ocurrio un error al guardar el detalle, intente nuevamente.'
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $pedido = Pedido::findOrFail($request->id);
            $pedido->activo = 'NO';
            $pedido->save();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Pedido se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Permiso, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function proveedor_delete(Request $request)
    {
        try {
            DB::beginTransaction();

            ProveedorPedido::destroy($request->id);

            DB::commit();

            return [
                'state'  =>  'ok',
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'state'  =>  'error',
                'error' => $th.' Ocurrio un error al eliminar el proveedor, intente nuevamente.'
            ];
        }
    }

    public function recibo($numero)
    {
        try {
            $pedido = Pedido::with('getCliente', 'getDetalles')->findOrFail($numero);
            //return $pedido->getDetalles;
            $pdf = PDF::loadView('sistema.pedidos.recibo', compact('pedido'));

            $pdf->setPaper('A4');

            return $pdf->stream('Pedido.pdf');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
            abort(404);
        }
    }

    //CRUDO
    public function nuevo()
    {
        return view('sistema.pedidos.nuevo.inicio');
    }

    public function frmNuevoPedido()
    {
        $series = Serie::where('tipo', 'CI')->get();
        $serie_defecto = Serie::where('tipo', 'CI')->first();
        $numeracion = Pedido::where('serie', $serie_defecto->serie)->orderBy('numeracion', 'desc')->first();
        $vendedores = User::select('id', \DB::raw("concat(nombres, ' ', ape_paterno, ' ', ape_materno) as nombres_apellidos"))
            ->get();

        return [
            'series' => $series,
            'serie_defecto' => $serie_defecto->id,
            'numeracion' => ($numeracion ? $numeracion->numeracion + 1 : 1),
            'vendedores' => $vendedores,
            'vendedor_defecto' => \Auth::user()->id,
            'fecha' => Carbon::now()->format('Y-m-d')
        ];
    }

    public function buscarProductos(Request $request)
    {
        return Producto::select(
                'id as code',
                'nombre as label',
                'precio_unitario'
            )
            ->where('nombre', 'like', '%'.$request->frase.'%')
            ->orWhere('nombre_secundario', 'like', '%'.$request->frase.'%')
            ->orWhere('descripcion', 'like', '%'.$request->frase.'%')
            ->get()
        ;
    }

    public function buscarSeries(Request $request)
    {
        $series = Serie::where('tipo', $request->tipo)->get();
        $serie_defecto = Serie::where('tipo', $request->tipo)->first();
        $numeracion = Pedido::where('serie', $serie_defecto->serie)->orderBy('numeracion', 'desc')->first();

        return [
            'series' => $series,
            'serie_defecto' => $serie_defecto->id,
            'numeracion' => ($numeracion ? $numeracion->numeracion + 1 : 1)
        ];
    }

    public function buscarNumeracion(Request $request)
    {
        $serie = Serie::find($request->serie);

        $numeracion = Pedido::where('serie', $serie->serie)->orderBy('numeracion', 'desc')->first();

        return $numeracion ? $numeracion->numeracion + 1 : 1;
    }

    public function buscarProveedor(Request $request)
    {
        return Proveedor::select(
                'numero_documento as documento',
                'nombres as nombre',
                'direccion',
                'email',
                'telefono'
            )
            ->where('numero_documento', $request->documento)
            ->first()
        ;
    }

    public function guardarProveedor(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'documento' => 'required|size:11',
            'nombre' => 'required',
            'telefono' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->all(), 422);
        }

        $proveedor = new Proveedor;
        $proveedor->numero_documento = $request->documento;
        $proveedor->nombres = Str::upper($request->nombre);
        $proveedor->email = $request->email;
        $proveedor->telefono = $request->telefono;
        $proveedor->direccion = Str::upper($request->direccion);
        $proveedor->servicio = Str::upper($request->servicio);
        $proveedor->save();
    }

    public function guardar(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'tipo_comprobante' => 'required',
            'serie' => 'required|exists:series,id',
            'numeracion' => 'required|numeric',
            'fecha' => 'required|date',
            'vendedor' => 'required|exists:users,id',
            'cliente.documento' => 'required',
            'cliente.nombres_apellidos' => 'required',
            'cliente.direccion' => 'required',
            'cliente.email' => 'required',
            'cliente.celular' => 'required',
            'proveedor.documento' => 'required|exists:proveedores,numero_documento',
            'detalles' => 'required|min:1'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->all(), 422);
        }

        if(!$cliente = $this->guardarCliente($request->cliente)){

            return response()->json(['Los datos del cliente son incorrectos.'], 422);
        }

        $proveedor = Proveedor::where('numero_documento', $request->proveedor['documento'])->first();

        $serie = Serie::find($request->serie);

        $pedido = new Pedido;
        $pedido->user_id = $request->vendedor;
        $pedido->cliente_id = $cliente->id;
        $pedido->tipo_comprobante = $request->tipo_comprobante;
        $pedido->serie = $serie->serie;
        $pedido->numeracion = $request->numeracion;
        $pedido->total = 0;
        $pedido->acuenta = $request->acuenta ? $request->acuenta : 0;
        $pedido->observacion = mb_Str::upper($request->observacion);
        $pedido->save();

        foreach($request->detalles as $detalle)
        {
            $producto = Producto::find($detalle['producto']);
            DetallePedido::create([
                'pedido_id' => $pedido->id,
                'cantidad' => $detalle['cantidad_cliente'],
                'cantidad_proveedor' => $detalle['cantidad_proveedor'],
                'descripcion' => $detalle['descripcion'],
                'linea' => $producto->linea_producto,
                'modelo' => $producto->modelo,
                'marca' => $producto->marca,
                'precio_unitario' => $detalle['precio_unitario'],
                'total' => $detalle['total_detalle'],
                'producto_id' => $detalle['producto']
            ]);

            $pedido->total += $detalle['total_detalle'];
            $pedido->save();
        }

        return $pedido;
    }

    private function guardarCliente(array $datos)
    {
        if(strlen($datos['documento']) == 8){

            $tipo = 'DNI';
            $codigo_sunat = 1;
        }elseif(strlen($datos['documento']) == 11){

            $tipo = 'RUC';
            $codigo_sunat = 6;
        }else{

            return false;
        }

        try {
            if($cliente = Cliente::find($datos['documento'])){

                $cliente->tipo = $tipo;
                $cliente->codigo_sunat = $codigo_sunat;
                $cliente->nombres = $datos['nombres_apellidos'];
                $cliente->direccion = $datos['direccion'];
                $cliente->email = $datos['email'];
                $cliente->celular = $datos['celular'];
                $cliente->save();
            }else{

                $cliente = new Cliente;
                $cliente->id = $datos['documento'];
                $cliente->tipo = $tipo;
                $cliente->codigo_sunat = $codigo_sunat;
                $cliente->nombres = $datos['nombres_apellidos'];
                $cliente->direccion = $datos['direccion'];
                $cliente->email = $datos['email'];
                $cliente->celular = $datos['celular'];
                $cliente->user_id = \auth::user()->id;
                $cliente->save();
            }
        } catch (\Throwable $th) {
            \Log::error($th);
            return false;
        }

        return $cliente;
    }

    public function todos(Request $request)
    {
        if($request->frase){

            return Pedido::join('clientes as c', 'c.id', '=', 'pedidos.cliente_id')
                ->select(
                    'pedidos.id',
                    \DB::raw("concat(pedidos.serie, '-', pedidos.numeracion) as serie_numeracion"),
                    'c.id as documento_cliente',
                    'c.nombres as cliente',
                    'pedidos.total',
                    \DB::raw("date_format(pedidos.created_at, '%d/%m/%Y') as fecha")
                )
                ->where(\DB::raw("concat(pedidos.serie, '-', pedidos.numeracion)"), 'like', '%'.$request->frase.'%')
                ->orWhere('c.id', 'like', '%'.$request->frase.'%')
                ->orWhere('c.nombres', 'like', '%'.$request->frase.'%')
                ->paginate(10)
            ;
        }

        return Pedido::join('clientes as c', 'c.id', '=', 'pedidos.cliente_id')
            ->select(
                'pedidos.id',
                \DB::raw("concat(pedidos.serie, '-', pedidos.numeracion) as serie_numeracion"),
                'c.id as documento_cliente',
                'c.nombres as cliente',
                'pedidos.total',
                \DB::raw("date_format(pedidos.created_at, '%d/%m/%Y') as fecha")
            )
            ->paginate(10)
        ;
    }

    public function mdlMostrarRecibo(Request $request)
    {
        $pedido = Pedido::findOrFail($request->id);

        $pdf = PDF::loadView('sistema.pedidos.recibo', compact('pedido'));

        $pdf->setPaper('A4');

        return $pdf->stream('Pedido.pdf');
    }
}
