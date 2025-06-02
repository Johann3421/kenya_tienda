<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Configuracion;
use App\Models\DetallesSoporte;
use App\Models\Soporte;
use App\Serie;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SoporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $series     = Serie::all();
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
        $buscar = $request->search;
        $soportes->where('serie', 'like', '%' . $buscar . '%');
    }

    $soportes = $soportes->paginate(10);

    $star  = Carbon::now();
    $fin   = Carbon::now()->addDay(5);
    $antes = Carbon::now()->addDay(-5);

    $avencerse = Soporte::whereDate('fecha_entrega', '<=', $fin)->whereDate('fecha_entrega', '>', $star)->get();
    $vencidos  = Soporte::whereDate('fecha_entrega', '<', $star)->whereDate('fecha_entrega', '>=', $antes)->get();

    return [
        'pagination' => [
            'total'        => $soportes->total(),
            'current_page' => $soportes->currentPage(),
            'per_page'     => $soportes->perPage(),
            'last_page'    => $soportes->lastPage(),
            'from'         => $soportes->firstItem(),
            'to'           => $soportes->lastPage(),
            'index'        => ($soportes->currentPage() - 1) * $soportes->perPage(),
        ],
        'soportes'   => $soportes,
        'avencerse'  => $avencerse,
        'vencidos'   => $vencidos,
        'estados'    => $estados,
    ];
}

    public function uploadPdf(Request $request)
{
    $request->validate([
        'pdf' => 'required|file|mimes:pdf|max:5120', // 5MB máximo
    ]);

    if ($request->hasFile('pdf')) {
        $file = $request->file('pdf');
        $fileName = 'soporte_' . time() . '_' . Str::random(10) . '.pdf';
        $path = $file->storeAs('pdfs', $fileName, 'public');

        return response()->json([
            'path' => '/storage/' . $path,
        ]);
    }

    return response()->json(['error' => 'No se pudo subir el PDF'], 400);
}

public function store(Request $request)
{
    $this->validate($request, [
        'fecha_registro'   => 'required',
        'fecha_entrega'    => 'required',
        'tipo_servicio'    => 'required',
        'estado_servicio'  => 'required',
        'numero_documento' => 'required|digits_between:8,11',
        'nombres'          => 'required',
        'celular'          => 'required|digits:9',
        'equipo'           => 'required',
        'marca'            => 'required',
        'modelo'           => 'required',
        'serie'           => 'required',
        'descripcion'      => 'required',
        'acuenta'         => 'required|numeric',
        'costo_servicio'   => 'required|numeric',
        'saldo_total'      => 'required|numeric',
        'pdf_file'         => 'nullable|file|mimes:pdf|max:5120', // 5MB máximo
        'numero_caso' => 'nullable|string|max:255',

    ]);

    try {
        DB::beginTransaction();

        if (! $cliente = Cliente::find($request->numero_documento)) {
            $cliente       = new Cliente();
            $cliente->id   = $request->numero_documento;
            $cliente->tipo = $request->tipo_documento;
            if ($request->tipo_documento == 'RUC') {
                $cliente->codigo_sunat = 6;
            } else {
                $cliente->codigo_sunat = 1;
            }
        }

        $cliente->nombres   = Str::upper($request->nombres);
        $cliente->direccion = Str::upper($request->direccion);
        $cliente->email     = Str::upper($request->email);
        $cliente->celular   = $request->celular;
        $cliente->user_id   = Auth::user()->id;
        $cliente->save();

        $datos = '{"cargador":"' . $request->cargador . '","cable_usb":"' . $request->cable_usb . '","cable_poder":"' . $request->cable_poder . '","sin_accesorios":"' . $request->sin_accesorios . '","otros":"' . $request->otros . '"}';
        $date  = Carbon::now();

        $soporte                       = new Soporte();
        $soporte->user_id              = Auth::user()->id;
        $soporte->cliente_id           = $request->numero_documento;
        $soporte->servicio             = $request->tipo_servicio;
        $soporte->estado               = $request->estado_servicio;
        $soporte->equipo               = Str::upper($request->equipo);
        $soporte->marca                = Str::upper($request->marca);
        $soporte->modelo               = Str::upper($request->modelo);
        $soporte->serie                = Str::upper($request->serie);
        if (is_array($request->descripcion)) {
    $soporte->descripcion = json_encode($request->descripcion, JSON_UNESCAPED_UNICODE);
} else {
    // Si ya es JSON string, guárdalo tal cual
    $soporte->descripcion = $request->descripcion;
}
        $soporte->accesorios           = $datos;
        $soporte->acuenta              = $request->acuenta;
        $soporte->costo_servicio       = $request->costo_servicio;
        $soporte->saldo_total          = $request->saldo_total;
        $soporte->fecha_registro       = $date->format('Y-m-d H:i:s');
        $soporte->fecha_entrega        = $request->fecha_entrega;
        $soporte->confirmar_reparacion = $request->confirmar_reparacion;
        $soporte->solo_diagnostico     = $request->solo_diagnostico;
        $soporte->observacion          = Str::upper($request->observacion);
        $soporte->reporte_tecnico      = Str::upper($request->reporte_tecnico);
        $soporte->numero_caso = $request->numero_caso;


        // Guardar PDF si existe
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $fileName = 'soporte_' . time() . '_' . Str::random(10) . '.pdf';
            $path = $file->storeAs('pdfs', $fileName, 'public');
            $soporte->pdf_link = '/storage/' . $path;
        }

        $soporte->save();

        $soporte->codigo_barras = 'VASCO' . $date->format('y') . Str::padLeft($soporte->id, 4, '0');
        $soporte->save();

        $detalles = json_decode($request->detalles, true);
        foreach ($detalles as $value) {
            $detalle              = new DetallesSoporte();
            $detalle->soporte_id  = $soporte->id;
            $detalle->descripcion = $value['descripcion'];
            $detalle->precio      = $value['precio'];
            $detalle->descuento   = $value['descuento'];
            $detalle->cantidad    = $value['cantidad'];
            $detalle->importe     = $value['importe'];
            $detalle->save();
        }

        DB::commit();

        return [
            'type'          => 'success',
            'title'         => 'CORRECTO: ',
            'message'       => 'El Soporte Técnico se guardo correctamente.',
            'soporte_id'    => $soporte->id,
            'soporte_barra' => $soporte->codigo_barras,
        ];

    } catch (\Throwable $th) {
        DB::rollBack();

        return [
            'type'    => 'danger',
            'title'   => 'ERROR: ',
            'message' => $th . 'Ocurrio un error al guardar el Soporte Técnico, intente nuevamente o contacte al Administrador del Sistema.',
        ];
    }
}

public function update(Request $request)
{
    $this->validate($request, [
        'fecha_registro'   => 'required',
        'fecha_entrega'    => 'required',
        'tipo_servicio'    => 'required',
        'estado_servicio'  => 'required',
        'numero_documento' => 'required|integer|digits_between:8,11',
        'nombres'          => 'required',
        'celular'          => 'required|integer|min:9',
        'equipo'           => 'required',
        'marca'            => 'required',
        'modelo'           => 'required',
        'serie'            => 'required',
        'descripcion'      => 'required',
        'acuenta'          => 'required|integer',
        'costo_servicio'   => 'required|integer',
        'saldo_total'      => 'required|integer',
        'pdf_file'         => 'nullable|file|mimes:pdf|max:5120', // 5MB máximo
        'numero_caso' => 'nullable|string|max:255',

    ]);

    try {
        DB::beginTransaction();

        if (! $cliente = Cliente::find($request->numero_documento)) {
            $cliente       = new Cliente();
            $cliente->id   = $request->numero_documento;
            $cliente->tipo = $request->tipo_documento;
            if ($request->tipo_documento == 'RUC') {
                $cliente->codigo_sunat = 6;
            } else {
                $cliente->codigo_sunat = 1;
            }
        }

        $cliente->nombres   = Str::upper($request->nombres);
        $cliente->direccion = Str::upper($request->direccion);
        $cliente->email     = Str::upper($request->email);
        $cliente->celular   = $request->celular;
        $cliente->user_id   = Auth::user()->id;
        $cliente->save();

        $datos = '{"cargador":"' . $request->cargador . '","cable_usb":"' . $request->cable_usb . '","cable_poder":"' . $request->cable_poder . '","sin_accesorios":"' . $request->sin_accesorios . '","otros":"' . $request->otros . '"}';

        $soporte = Soporte::findOrFail($request->id);

        // Eliminar PDF anterior si se sube uno nuevo
        if ($request->hasFile('pdf_file')) {
            // Eliminar el archivo anterior si existe
            if ($soporte->pdf_link) {
                $oldPath = str_replace('/storage/', '', $soporte->pdf_link);
                Storage::disk('public')->delete($oldPath);
            }

            // Guardar el nuevo archivo
            $file = $request->file('pdf_file');
            $fileName = 'soporte_' . time() . '_' . Str::random(10) . '.pdf';
            $path = $file->storeAs('pdfs', $fileName, 'public');
            $soporte->pdf_link = '/storage/' . $path;
        }

        // Actualizar el resto de campos
        $soporte->user_id              = Auth::user()->id;
        $soporte->cliente_id           = $request->numero_documento;
        $soporte->servicio             = $request->tipo_servicio;
        $soporte->estado               = $request->estado_servicio;
        $soporte->equipo               = Str::upper($request->equipo);
        $soporte->marca                = Str::upper($request->marca);
        $soporte->modelo               = Str::upper($request->modelo);
        $soporte->serie                = Str::upper($request->serie);
        if (is_array($request->descripcion)) {
    $soporte->descripcion = json_encode($request->descripcion, JSON_UNESCAPED_UNICODE);
} else {
    $soporte->descripcion = $request->descripcion;
}
        $soporte->accesorios           = $datos;
        $soporte->acuenta              = $request->acuenta;
        $soporte->costo_servicio       = $request->costo_servicio;
        $soporte->saldo_total          = $request->saldo_total;
        $soporte->fecha_entrega        = $request->fecha_entrega;
        $soporte->confirmar_reparacion = $request->confirmar_reparacion;
        $soporte->solo_diagnostico     = $request->solo_diagnostico;
        $soporte->observacion          = Str::upper($request->observacion);
        $soporte->reporte_tecnico      = Str::upper($request->reporte_tecnico);
        $soporte->numero_caso = $request->numero_caso;
        $soporte->save();

        DB::commit();

        return [
            'type'    => 'success',
            'title'   => 'CORRECTO: ',
            'message' => 'El Soporte Técnico se actualizó correctamente.',
        ];

    } catch (\Throwable $th) {
        DB::rollBack();
        return [
            'type'    => 'danger',
            'title'   => 'ERROR: ',
            'message' => 'Ocurrió un error al actualizar el Soporte Técnico: ' . $th->getMessage(),
        ];
    }
}


    public function delete(Request $request)
{
    try {
        DB::beginTransaction();

        $soporte = Soporte::find($request->id);
        if ($soporte) {
            // Eliminar PDF si existe
            if ($soporte->pdf_link) {
                $path = str_replace('/storage/', '', $soporte->pdf_link);
                Storage::disk('public')->delete($path);
            }
            // Eliminar detalles relacionados
            $soporte->getDetalles()->delete();
            // Eliminar el soporte
            $soporte->delete();
        }

        DB::commit();

        return [
            'type'    => 'success',
            'title'   => 'CORRECTO: ',
            'message' => 'El Soporte Técnico y todos sus datos se eliminaron correctamente.',
        ];

    } catch (\Throwable $th) {
        DB::rollBack();

        return [
            'type'    => 'danger',
            'title'   => 'ERROR: ',
            'message' => $th . ' Ocurrió un error al eliminar el Soporte Técnico, intente nuevamente o contacte al Administrador del Sistema.',
        ];
    }
}

    public function recibo($numero)
{
    try {
        set_time_limit(120);
        ini_set('memory_limit', '256M');

        $soporte = Soporte::with([
            'getCliente:id,nombres,direccion,email,celular',
            'getDetalles:id,soporte_id,descripcion,cantidad,precio'
        ])->findOrFail($numero);

        // Verificar existencia de la vista de recibo
        if (!view()->exists('sistema.servicio.recibo')) {
            throw new \Exception("La vista de recibo no fue encontrada");
        }

        return FacadePdf::loadView('sistema.servicio.recibo', [
            'soporte' => $soporte,
            'fecha' => now()->format('d/m/Y H:i')
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('defaultFont', 'helvetica')
        ->stream("recibo_{$numero}.pdf");

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Manejo específico para cuando no se encuentra el soporte
        return response()->json([
            'error' => 'No se encontró el soporte técnico solicitado'
        ], 404);

    } catch (\Throwable $th) {
        // Manejo genérico de errores
        \Log::error('Error al generar PDF: ' . $th->getMessage());

        // Opción 1: Retornar JSON con error
        return response()->json([
            'error' => 'Ocurrió un error al generar el PDF',
            'message' => $th->getMessage()
        ], 500);

        // Opción 2: Redirigir a una página de error (si tienes una ruta configurada)
        // return redirect()->route('pagina.de.error');
    }
}

    public function detalleAdd(Request $request)
    {
        try {
            DB::beginTransaction();

            $soporte                 = Soporte::findOrFail($request->id);
            $soporte->costo_servicio = $request->costo;
            $soporte->saldo_total    = $request->saldo;
            $soporte->save();

            $detalle              = new DetallesSoporte();
            $detalle->soporte_id  = $soporte->id;
            $detalle->descripcion = strtoupper($request->descripcion);
            $detalle->precio      = $request->precio;
            $detalle->descuento   = $request->descuento;
            $detalle->cantidad    = $request->cantidad;
            $detalle->importe     = $request->importe;
            $detalle->save();

            DB::commit();

            return [
                'detalles' => $soporte->getDetalles,
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                $th,
            ];
        }
    }

    public function detalleDelete(Request $request)
    {
        try {
            DB::beginTransaction();

            DetallesSoporte::destroy($request->id);

            $soporte                 = Soporte::findOrFail($request->soporte);
            $soporte->costo_servicio = $request->costo;
            $soporte->saldo_total    = $request->saldo;
            $soporte->save();

            DB::commit();

            return [
                'detalles' => $soporte->getDetalles,
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                $th,
            ];
        }
    }

    public function codigoBarra(Request $request)
    {
        return Soporte::find($request->id);
    }


}


