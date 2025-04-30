<?php

// app/Http/Controllers/Api/ProductoApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Producto; // Asegúrate de que este es el nombre correcto de tu modelo
use Illuminate\Http\Request;

class ProductoApiController extends Controller
{
    public function index()
    {
        $productos = Producto::select([
            'id',
            'nombre',
            'nro_parte',
            'precio_unitario',
            'stock_inicial',
            'codigo_barras',
            'codigo_interno',
            'imagen_1',
            'categoria_id',
            'marca_id',
            'modelo_id',
            'especificaciones_json',
            'filtros_ids'
        ])
        ->get();

        return response()->json([
            'success' => true,
            'data' => $productos
        ]);
    }

    public function show($id)
    {
        $producto = Producto::select([
            'id',
            'nombre',
            'nro_parte',
            'procesador',
            'ram',
            'almacenamiento',
            'precio_unitario',
            'stock_inicial',
            'codigo_barras',
            'codigo_interno',
            'imagen_1',
            'categoria_id',
            'marca_id',
            'ficha_tecnica',
            'modelo_id',
            'especificaciones_json',
            'filtros_ids'
        ])->find($id);

        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $producto
        ]);
    }
}
