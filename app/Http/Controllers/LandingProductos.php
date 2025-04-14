<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Producto;
use App\Modelo;

class LandingProductos extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('nombre', 'ASC')->with('Productos')->get();
        $modelo = Modelo::orderBy('id', 'ASC')->with('Productos')->get();
        $productos = Producto::orderBy('nombre', 'ASC')->where('pagina_web', 'SI')->take(15)->get();

        return view('welcome', compact('categorias', 'productos','modelo'));
    }
    public function categoria(Request $request)
    {
        if ($request->id) {
            $productos = Producto::with('getCategoria', 'getMarca','getModelo')->where('pagina_web', 'SI')
                                    ->where('categoria_id', $request->id)
                                    ->where('modelo_id', $request->id)
                                    ->orderBy('nombre', 'ASC')->paginate(8);
        } else {
            $productos = Producto::where('pagina_web', 'SI')
                ->orderBy('nombre', 'ASC')->paginate(8);
        }
        if ($request->search) {
            switch ($request->search_por) {
                case 'nombre':
                    $productos->where('nombre', 'like', '%' . $request->search . '%');
                    break;
            }
        }
        if ($request->categoria) {
            $productos->where('categoria_id', $request->categoria);
        }
        if ($request->web) {
            $productos->where('pagina_web', $request->web);
        }
        return [
            'pagination' => [
                'total' => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page' => $productos->perPage(),
                'last_page' => $productos->lastPage(),
                'from' => $productos->firstItem(),
                'to' => $productos->lastPage(),
                'index' => ($productos->currentPage() - 1) * $productos->perPage(),
            ],
            'productos' => $productos
        ];
    }
    public function buscar(Request $request)
    {
        $productos = Producto::with('getCategoria', 'getMarca','getModelo')
            ->where('pagina_web', 'SI')
            ->orderBy('nombre', 'ASC');
        if ($request->categoria_id) {
            $productos->where('categoria_id', $request->categoria_id);
        }

        

        $productos = $productos->paginate(8);

        return [
            'pagination' => [
                'total'        => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'     => $productos->perPage(),
                'last_page'    => $productos->lastPage(),
                'from'         => $productos->firstItem(),
                'to'           => $productos->lastPage(),
                'index'        => ($productos->currentPage() - 1) * $productos->perPage(),
            ],
            'productos'    => $productos,
        ];
    }
}
