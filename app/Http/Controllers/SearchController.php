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
                $mod = $prod->modelo ?: ($prod->getModelo ?: ($prod->modelo_id ? \App\Modelo::find($prod->modelo_id) : null));
                $img = asset('producto.jpg');

                if ($mod && !empty($mod->img_mod)) {
                    $imgMod = ltrim($mod->img_mod, '/');
                    if (str_starts_with($imgMod, 'http://') || str_starts_with($imgMod, 'https://')) {
                        $img = $imgMod;
                    } elseif (str_starts_with($imgMod, 'storage/')) {
                        $img = asset($imgMod);
                    } else {
                        $img = asset('storage/' . $imgMod);
                    }
                } elseif (!empty($prod->imagen_1)) {
                    $img1 = ltrim($prod->imagen_1, '/');
                    $img = str_starts_with($img1, 'storage/') ? asset($img1) : asset('storage/' . $img1);
                } elseif (!empty($prod->imagen)) {
                    $img0 = ltrim($prod->imagen, '/');
                    $img = str_starts_with($img0, 'storage/') ? asset($img0) : asset('storage/' . $img0);
                } elseif ($prod->getCategoria && !empty($prod->getCategoria->img_url)) {
                    $img = $prod->getCategoria->img_url;
                }

                $catName = $prod->getCategoria->nombre ?? ($mod->descripcion ?? '');
                $rawName = $prod->display_name ?: ($prod->nombre ?: $prod->descripcion);
                $cleanName = preg_replace('/\s*\([A-Z0-9\-\.]+\)\s*$/i', '', $rawName);

                return [
                    'id' => $prod->id,
                    'nombre' => trim($cleanName),
                    'nro_parte' => (string) ($prod->nro_parte ?: ($prod->codigo_pc ?: '')),
                    'categoria' => (string) $catName,
                    'modelo' => (string) ($mod->descripcion ?? ''),
                    'procesador' => (string) ($prod->procesador ?? ''),
                    'img' => $img,
                    'url' => url('producto/' . $prod->id . '/detalle'),
                ];
            });

        return response()->json(['data' => $results]);
    }
}


