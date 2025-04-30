<?php

// app/Http/Controllers/Api/BannerMedioApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BannerMedio;
use Illuminate\Http\Request;

class BannerMedioApiController extends Controller
{
    public function index(Request $request)
    {
        // Filtro por posición si se especifica
        $posicion = $request->input('posicion', null);

        $banners = BannerMedio::select([
                'id',
                'titulo',
                'imagen_path',
                'url_destino',
                'orden',
                'posicion'
            ])
            ->where('activo', 1)
            ->when($posicion, function($query) use ($posicion) {
                return $query->where('posicion', $posicion);
            })
            ->orderBy('orden', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $banners
        ]);
    }

    public function show($id)
    {
        $banner = BannerMedio::select([
                'id',
                'titulo',
                'imagen_path',
                'url_destino',
                'orden',
                'posicion',
                'created_at',
                'updated_at'
            ])
            ->where('activo', 1)
            ->find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $banner
        ]);
    }

    public function porPosicion($posicion)
    {
        $banners = BannerMedio::select([
                'id',
                'titulo',
                'imagen_path',
                'url_destino',
                'orden'
            ])
            ->where('activo', 1)
            ->where('posicion', $posicion)
            ->orderBy('orden', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $banners
        ]);
    }
}
