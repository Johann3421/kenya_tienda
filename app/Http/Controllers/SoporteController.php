<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Soporte;
use App\Models\Cliente;
use App\Models\DetallesSoporte;
use App\Models\Configuracion;
use App\Models\ViewSoporte;
use App\Serie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SoporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $series = Serie::all();
        $mw_soporte = Configuracion::where('nombre', 'mensaje_wahtsapp')->first();
        return view('sistema.servicio.soporte', compact('mw_soporte', 'series'));
    }

    public function buscar(Request $request)
    {
        $estados = DB::table('soportes')
    ->selectRaw('COUNT(CASE WHEN estado = "realizado" THEN 1 END) as realizado')
    ->selectRaw('COUNT(CASE WHEN estado = "transito" THEN 1 END) as transito')
    ->selectRaw('COUNT(CASE WHEN estado = "tienda" THEN 1 END) as tienda')
    ->selectRaw('COUNT(CASE WHEN estado = "entregado" THEN 1 END) as entregado')
    ->selectRaw('COUNT(CASE WHEN estado = "cancelado" THEN 1 END) as cancelado')
    ->first();

        $soportes = Soporte::where('activo', 'SI')
        ->orderBy('id', 'DESC')
        ->with(['getCliente', 'getDetalles']);

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
                    $soportes->where('id', $id);
                    break;
                case 'id':
                    $soportes->where($request->search_por, $request->search);
                    break;
                case 'cliente':
                    $cliente = $request->search;
                    $soportes->whereHas('getCliente', function ($query) use($cliente) {
                        $query->where('id', 'like', '%'.$cliente.'%')->orWhere('nombres', 'like', '%'.$cliente.'%');
                    });
                    break;
                case 'estado':
                    $soportes->where($request->search_por, $request->search);
                    break;
            }*/
            $buscar = $request->search;
            $soportes->where(function ($query) use ($buscar){
                $cliente = $buscar;
                $query->where('codigo_barras', 'like', '%'.$buscar.'%')
                      ->orWhereHas('getCliente', function ($q) use($cliente) {
                        $q->where('id', 'like', '%'.$cliente.'%')->orWhere('nombres', 'like', '%'.$cliente.'%');
                    });
            });
        }
        $soportes = $soportes->paginate(10);

        $star = Carbon::now();
        $fin = Carbon::now()->addDay(5);
        $antes = Carbon::now()->addDay(-5);

        $avencerse = Soporte::whereDate('fecha_entrega', '<=', $fin)->whereDate('fecha_entrega', '>', $star)->get();
        $vencidos = Soporte::whereDate('fecha_entrega', '<', $star)->whereDate('fecha_entrega', '>=', $antes)->get();

        return [
            'pagination' => [
                'total' => $soportes->total(),
                'current_page' => $soportes->currentPage(),
                'per_page' => $soportes->perPage(),
                'last_page' => $soportes->lastPage(),
                'from' => $soportes->firstItem(),
                'to' => $soportes->lastPage(),
                'index' => ($soportes->currentPage()-1)*$soportes->perPage(),
            ],
            'soportes' => $soportes,
            'avencerse' => $avencerse,
            'vencidos' => $vencidos,
            'estados' => $estados,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'fecha_registro'    => 'required',
            'fecha_entrega'     => 'required',
            'tipo_servicio'     => 'required',
            'estado_servicio'   => 'required',
            'numero_documento'  => 'required|digits_between:8,11',
            'nombres'           => 'required',
            //'direccion'         => 'required',
            //'email'             => 'required|email',
            'celular'           => 'required|digits:9',
            'equipo'            => 'required',
            'marca'             => 'required',
            'modelo'            => 'required',
            'serie'             => 'required',
            'descripcion'       => 'required',
            'acuenta'           => 'required|numeric',
            'costo_servicio'    => 'required|numeric',
            'saldo_total'       => 'required|numeric',
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
            }
            $cliente->nombres = Str::upper($request->nombres);
            $cliente->direccion = Str::upper($request->direccion);
            $cliente->email = Str::upper($request->email);
            $cliente->celular = $request->celular;
            $cliente->user_id = Auth::user()->id;
            $cliente->save();

            $datos = '{"cargador":"'.$request->cargador.'","cable_usb":"'.$request->cable_usb.'","cable_poder":"'.$request->cable_poder.'","sin_accesorios":"'.$request->sin_accesorios.'","otros":"'.$request->otros.'"}';
            $date = Carbon::now();

            $soporte = new Soporte();
            $soporte->user_id = Auth::user()->id;
            $soporte->cliente_id = $request->numero_documento;
            $soporte->servicio = $request->tipo_servicio;
            $soporte->estado = $request->estado_servicio;
            $soporte->equipo = Str::upper($request->equipo);
            $soporte->marca = Str::upper($request->marca);
            $soporte->modelo = Str::upper($request->modelo);
            $soporte->serie = Str::upper($request->serie);
            $soporte->descripcion = Str::upper($request->descripcion);
            $soporte->accesorios = $datos;
            $soporte->acuenta = $request->acuenta;
            $soporte->costo_servicio = $request->costo_servicio;
            $soporte->saldo_total = $request->saldo_total;
            $soporte->fecha_registro = $date->format('Y-m-d H:i:s');
            $soporte->fecha_entrega = $request->fecha_entrega;
            $soporte->confirmar_reparacion = $request->confirmar_reparacion;
            $soporte->solo_diagnostico = $request->solo_diagnostico;
            $soporte->observacion = Str::upper($request->observacion);
            $soporte->reporte_tecnico = Str::upper($request->reporte_tecnico);
            $soporte->save();

            $soporte->codigo_barras = 'VASCO'.$date->format('y').Str::padLeft($soporte->id, 4, '0');
            $soporte->save();

            foreach ($request->detalles as $value) {
                $detalle = new DetallesSoporte();
                $detalle->soporte_id = $soporte->id;
                $detalle->descripcion = $value['descripcion'];
                $detalle->precio = $value['precio'];
                $detalle->descuento = $value['descuento'];
                $detalle->cantidad = $value['cantidad'];
                $detalle->importe = $value['importe'];
                $detalle->save();
            }

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Soporte Técnico se guardo correctamente.',
                'soporte_id'  =>  $soporte->id,
                'soporte_barra'  =>  $soporte->codigo_barras,
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th.'Ocurrio un error al guardar el Soporte Técnico, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'fecha_registro'    => 'required',
            'fecha_entrega'     => 'required',
            'tipo_servicio'     => 'required',
            'estado_servicio'   => 'required',
            'numero_documento'  => 'required|Integer|digits_between:8,11',
            'nombres'           => 'required',
            //'direccion'         => 'required',
            //'email'             => 'required|email',
            'celular'           => 'required|Integer|Min:9',
            'equipo'            => 'required',
            'marca'             => 'required',
            'modelo'            => 'required',
            'serie'             => 'required',
            'descripcion'       => 'required',
            'acuenta'           => 'required|integer',
            'costo_servicio'    => 'required|integer',
            'saldo_total'       => 'required|integer',
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
            }
            $cliente->nombres = Str::upper($request->nombres);
            $cliente->direccion = Str::upper($request->direccion);
            $cliente->email = Str::upper($request->email);
            $cliente->celular = $request->celular;
            $cliente->user_id = Auth::user()->id;
            $cliente->save();

            $datos = '{"cargador":"'.$request->cargador.'","cable_usb":"'.$request->cable_usb.'","cable_poder":"'.$request->cable_poder.'","sin_accesorios":"'.$request->sin_accesorios.'","otros":"'.$request->otros.'"}';
            $date = Carbon::now();

            $soporte = Soporte::findOrFail($request->id);
            $soporte->user_id = Auth::user()->id;
            $soporte->cliente_id = $request->numero_documento;
            $soporte->servicio = $request->tipo_servicio;
            $soporte->estado = $request->estado_servicio;
            $soporte->equipo = Str::upper($request->equipo);
            $soporte->marca = Str::upper($request->marca);
            $soporte->modelo = Str::upper($request->modelo);
            $soporte->serie = Str::upper($request->serie);
            $soporte->descripcion = Str::upper($request->descripcion);
            $soporte->accesorios = $datos;
            $soporte->acuenta = $request->acuenta;
            $soporte->costo_servicio = $request->costo_servicio;
            $soporte->saldo_total = $request->saldo_total;
            $soporte->fecha_entrega = $request->fecha_entrega;
            $soporte->confirmar_reparacion = $request->confirmar_reparacion;
            $soporte->solo_diagnostico = $request->solo_diagnostico;
            $soporte->observacion = Str::upper($request->observacion);
            $soporte->reporte_tecnico = Str::upper($request->reporte_tecnico);
            $soporte->save();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Soporte Técnico se actualizo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th.'Ocurrio un error al actualizar el Soporte Técnico, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            Soporte::destroy($request->id);

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Soporte Técnico se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th.'Ocurrio un error al eliminar el Soporte Técnico, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function recibo($numero)
    {
        try {
            $soporte = Soporte::findOrFail($numero);

            $pdf = PDF::loadView('sistema.servicio.recibo', compact('soporte'));

            $pdf->setPaper('A4');

            return $pdf->stream('Soporte.pdf');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
            abort(404);
        }
    }

    public function detalleAdd(Request $request)
    {
        try {
            DB::beginTransaction();

            $soporte = Soporte::findOrFail($request->id);
            $soporte->costo_servicio = $request->costo;
            $soporte->saldo_total = $request->saldo;
            $soporte->save();

            $detalle = new DetallesSoporte();
            $detalle->soporte_id = $soporte->id;
            $detalle->descripcion = strtoupper($request->descripcion);
            $detalle->precio = $request->precio;
            $detalle->descuento = $request->descuento;
            $detalle->cantidad = $request->cantidad;
            $detalle->importe = $request->importe;
            $detalle->save();

            DB::commit();

            return [
                'detalles'  =>  $soporte->getDetalles,
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                $th
            ];
        }
    }

    public function detalleDelete(Request $request)
    {
        try {
            DB::beginTransaction();

            DetallesSoporte::destroy($request->id);

            $soporte = Soporte::findOrFail($request->soporte);
            $soporte->costo_servicio = $request->costo;
            $soporte->saldo_total = $request->saldo;
            $soporte->save();

            DB::commit();

            return [
                'detalles'  =>  $soporte->getDetalles,
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                $th
            ];
        }
    }

    public function codigoBarra(Request $request)
    {
        return Soporte::find($request->id);
    }
}
