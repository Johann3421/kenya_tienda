<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\DetallesVenta;
use Carbon\Carbon;
use Auth;
use DB;

class VentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.ventas.misventas');
    }

    public function buscar(Request $request)
    {
        $ventas = Venta::where('activo', 'SI')
        ->orderBy('id', 'DESC')
        ->with(['getCliente', 'getDetalles']);

        $ventas = $ventas->paginate(10);

        return [
            'pagination' => [
                'total' => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page' => $ventas->perPage(),
                'last_page' => $ventas->lastPage(),
                'from' => $ventas->firstItem(),
                'to' => $ventas->lastPage(),
                'index' => ($ventas->currentPage()-1)*$ventas->perPage(),
            ],
            'ventas' => $ventas,
        ];
    }
}
