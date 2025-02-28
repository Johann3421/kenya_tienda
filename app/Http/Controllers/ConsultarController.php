<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soporte;
use App\Pedido;
use App\Garantia;
use App\Models\Cliente;
use App\Models\Configuracion;
use Carbon\Carbon;

class ConsultarController extends Controller {
    public function soporte() {
        $whatsapp = Configuracion::where('nombre', 'contacto_whatsapp')->first();
        return view('consultar.soporte', compact('whatsapp'));
    }

    public function soporte_buscar(Request $request) {
        $dato = strtoupper($request->search);

        $barras = substr($dato, 0, 7);
        $id = substr($dato, -1, 4);

        $soporte = Soporte::where('id', $id)->where('codigo_barras', $barras)->with(['getCliente', 'getDetalles'])->first();

        if ($soporte) {
            return [
                'state' => 'success',
                'soporte' => $soporte,
            ];
        } else {
            return [
                'state' => 'error',
            ];
        }
    }

    public function pedido() {
        $whatsapp = Configuracion::where('nombre', 'contacto_whatsapp')->first();
        return view('consultar.pedido', compact('whatsapp'));
    }

    public function pedido_buscar(Request $request) {

        $dato = strtoupper($request->search);

        $barras = substr($dato, 0, 7);
        $id = substr($dato, -1, 4);

        $pedido = pedido::where('id', $id)->where('codigo_barras', $barras)->with(['getCliente', 'getDetalles'])->first();

        if ($pedido) {
            return [
                'state' => 'success',
                'pedido' => $pedido,
            ];
        } else {
            return [
                'state' => 'error',
            ];
        }
    }
    public function garantia() {
        $whatsapp = Configuracion::where('nombre', 'contacto_whatsapp')->first();
        $garantia = Garantia::with('getManuales')->first();
        $resta = Garantia::select('garantia.garantia')->get();

        $fventa = Garantia::select('garantia.fecha_venta');
        $prod = Garantia::with('getManuales.getManual')->first();
        //$date =Garantia::select('garantia.fecha_venta')->get();
        return view('consultar.garantia', compact('whatsapp', 'garantia', 'prod'));
        //return ($garantia);
    }

    public function garantia_buscar(Request $request) {
        $dato = strtoupper($request->search);
        $numserie = substr($dato, 0, 7);
        $id = substr($dato, -1, 4);
        $garantia = Garantia::where('serie', 'LIKE', "%{$request->search}%")
            ->with(['getProductos', 'getManuales.getManual', 'getDriversprod.getDrivers'])->first();

        //$garantia= Garantia::with('getManuales')->get();
        //return ($garantia);
        if ($garantia) {
            return [
                'state' => 'success',
                'garantia' => $garantia,
                //'prod'   => $prod,
            ];
        } else {
            return [
                'state' => 'error',
            ];
        }
        $garantia = $garantia->with(['getProductos'])->get();
    }

    public function buscar_serie($serie) {
        $whatsapp = Configuracion::where('nombre', 'contacto_whatsapp')->first();
        $prod = Garantia::with('getManuales.getManual')->first();
        $garantia = Garantia::where('serie', 'LIKE', "%{$serie}%")
            ->with(['getProductos', 'getManuales.getManual', 'getDriversprod.getDrivers'])->first();

        return view('consultar.garantiaQR', compact('whatsapp', 'garantia', 'prod'));
    }
}
