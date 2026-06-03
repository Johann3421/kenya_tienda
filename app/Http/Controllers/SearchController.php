<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Producto;

class SearchController extends Controller
{
    public function products(Request $request)
    {
        $q = trim($request->get('q', ''));

        Log::info('SearchController.products called', ['q' => $q, 'ip' => $request->ip()]);

        if ($q === '') {
            return response()->json(['data' => []]);
        }

        $query = Producto::query();

        // Build conditional where clauses only for columns that exist to avoid SQL errors
        $columnsToCheck = ['nombre', 'descripcion', 'ficha_tecnica', 'especificaciones_json'];
        $first = true;

        foreach ($columnsToCheck as $col) {
            if (Schema::hasColumn('productos', $col)) {
                if ($first) {
                    $query->where($col, 'like', "%{$q}%");
                    $first = false;
                } else {
                    $query->orWhere($col, 'like', "%{$q}%");
                }
            }
        }

        // also search in the related modelo.descripcion if available
        if (Schema::hasTable('modelos') && Schema::hasColumn('modelos', 'descripcion')) {
            $query->orWhereHas('getModelo', function ($sub) use ($q) {
                $sub->where('descripcion', 'like', "%{$q}%");
            });
        }

        $results = $query->with('getModelo')->limit(30)->get()->map(function ($prod) {
            // determine image
            $img = 'producto.jpg';
            if (isset($prod->imagen) && $prod->imagen) {
                $img = 'storage/' . $prod->imagen;
            } elseif ($prod->getModelo && isset($prod->getModelo->img_mod) && $prod->getModelo->img_mod) {
                $img = 'storage/' . $prod->getModelo->img_mod;
            } elseif (isset($prod->imagen_1) && $prod->imagen_1) {
                $img = 'storage/' . $prod->imagen_1;
            }

            return [
                'id' => $prod->id,
                'nombre' => (string) $prod->display_name,
                'descripcion' =>
                    (isset($prod->descripcion) && $prod->descripcion) ? $prod->descripcion : ($prod->getModelo->descripcion ?? ''),
                'img' => asset($img),
                'url' => route('producto_detalle', $prod->id),
            ];
        });

        return response()->json(['data' => $results]);
    }
}
