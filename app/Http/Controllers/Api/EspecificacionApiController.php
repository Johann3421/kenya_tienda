<?php

// app/Http/Controllers/Api/EspecificacionApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Especificacion;
use Illuminate\Http\Request;

class EspecificacionApiController extends Controller
{
    public function index()
    {
        $especificaciones = Especificacion::select([
            'id',
            'campo',
            'descripcion',
            'producto_id'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $especificaciones
        ]);
    }

    public function show($id)
    {
        $especificacion = Especificacion::select([
            'id',
            'campo',
            'descripcion',
            'producto_id',
            'created_at',
            'updated_at'
        ])->find($id);

        if (!$especificacion) {
            return response()->json([
                'success' => false,
                'message' => 'Especificación no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $especificacion
        ]);
    }

    public function porProducto($producto_id)
    {
        $especificaciones = Especificacion::select([
            'id',
            'campo',
            'descripcion'
        ])
        ->where('producto_id', $producto_id)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $especificaciones
        ]);
    }
}
