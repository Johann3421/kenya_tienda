<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;

class SearchController extends Controller
{
    public function products(Request $request)
    {
        $q = trim($request->get('q', ''));

        if ($q === '') {
            return response()->json(['data' => []]);
        }

        $results = Producto::query()
            ->where('pagina_web', 'SI')
            ->noSuspendido()
            ->intelligentSearch($q)
            ->with(['getModelo', 'getCategoria', 'modelo'])
            ->limit(15)
            ->get()
            ->map(function ($prod) {
                // Determine image with fallbacks
                $img = asset('producto.jpg');
                if (!empty($prod->imagen_1)) {
                    $img = asset('storage/' . $prod->imagen_1);
                } elseif (!empty($prod->imagen)) {
                    $img = asset('storage/' . $prod->imagen);
                } elseif ($prod->modelo && !empty($prod->modelo->img_mod)) {
                    $img = asset('storage/' . $prod->modelo->img_mod);
                } elseif ($prod->getCategoria && !empty($prod->getCategoria->img_url)) {
                    $img = $prod->getCategoria->img_url;
                }

                $catName = $prod->getCategoria->nombre ?? ($prod->modelo->descripcion ?? '');
                $rawName = $prod->display_name ?: ($prod->nombre ?: $prod->descripcion);
                $cleanName = preg_replace('/\s*\([A-Z0-9\-\.]+\)\s*$/i', '', $rawName);

                return [
                    'id' => $prod->id,
                    'nombre' => trim($cleanName),
                    'nro_parte' => (string) ($prod->nro_parte ?: ($prod->codigo_pc ?: '')),
                    'categoria' => (string) $catName,
                    'modelo' => (string) ($prod->modelo->descripcion ?? ''),
                    'procesador' => (string) ($prod->procesador ?? ''),
                    'img' => $img,
                    'url' => url('producto/' . $prod->id . '/detalle'),
                ];
            });

        return response()->json(['data' => $results]);
    }
}

