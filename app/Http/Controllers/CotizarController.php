<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Models\Especificacion;
use Illuminate\Http\Request;

class CotizarController extends Controller
{


    public function index(Request $request)
    {
        // ponytail: reusar la lógica del catálogo público, añadir precio_unitario
        $query = Producto::with(['getModelo', 'getCategoria'])
            ->where('pagina_web', 'SI')
            ->noSuspendido();

        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $query->where(function ($b) use ($q) {
                $b->where('nombre', 'like', "%{$q}%")
                  ->orWhere('nro_parte', 'like', "%{$q}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        $productos = $query->orderBy('nombre')->paginate(24)->withQueryString();

        return view('cotizar.index', compact('productos'));
    }

    public function detalle($id)
    {
        $producto = Producto::findOrFail($id);

        // ponytail: misma lógica de ordenamiento que ProductoController@detalle
        $especificaciones = Especificacion::where('producto_id', $id)
            ->get()
            ->filter(fn($e) => strtolower(trim($e->descripcion ?? '')) !== 'no')
            ->values();

        return view('sistema.productos.detalle', compact('producto', 'especificaciones'));
    }
}
