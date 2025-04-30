<?php

// app/Http/Controllers/Api/ModeloApiController.php



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modelo; // Asegúrate de que este es el nombre correcto de tu modelo
use Illuminate\Http\Request;

class ModeloApiController extends Controller
{
    public function index()
    {
        $modelos = Modelo::select([
            'id',
            'descripcion',
            'categoria_id',
            'activo',
            'img_mod'
        ])
        ->where('activo', 'Si') // Solo modelos activos
        ->get();

        return response()->json([
            'success' => true,
            'data' => $modelos
        ]);
    }

    public function show($id)
    {
        $modelo = Modelo::select([
            'id',
            'descripcion',
            'categoria_id',
            'activo',
            'img_mod',
            'created_at',
            'updated_at'
        ])->find($id);

        if (!$modelo) {
            return response()->json([
                'success' => false,
                'message' => 'Modelo no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $modelo
        ]);
    }

    // Opcional: Obtener modelos por categoría
    public function porCategoria($categoria_id)
    {
        $modelos = Modelo::select([
            'id',
            'descripcion',
            'img_mod'
        ])
        ->where('categoria_id', $categoria_id)
        ->where('activo', 'Si')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $modelos
        ]);
    }
}
