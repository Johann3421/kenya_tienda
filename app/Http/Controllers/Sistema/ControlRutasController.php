<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaginaEstado;
use Illuminate\Support\Facades\Route;

class ControlRutasController extends Controller
{
    public function index()
    {
        // Obtén todas las rutas principales (excepto admin y rutas internas)
        $routes = collect(Route::getRoutes())
            ->filter(function ($route) {
                $uri = $route->uri();
                // Excluye rutas de admin, api, login, logout, y rutas internas
                return !preg_match('#^(admin|api|login|logout|register|password|_ignition|sanctum|telescope|horizon)#', $uri)
                    && !str_contains($uri, '{') // Excluye rutas con parámetros dinámicos
                    && $route->methods()[0] === 'GET'; // Solo GET principales
            })
            ->map(function ($route) {
                return [
                    'nombre' => ucfirst(str_replace('-', ' ', basename($route->uri())) ?: 'Inicio'),
                    'ruta' => '/' . ltrim($route->uri(), '/'),
                ];
            })
            ->unique('ruta')
            ->values();

        // Sincroniza las rutas con la base de datos
        foreach ($routes as $pagina) {
            PaginaEstado::firstOrCreate(
                ['ruta' => $pagina['ruta']],
                ['nombre' => $pagina['nombre'], 'estado' => 'activo']
            );
        }

        // Obtén todas las páginas para mostrar
        $paginas = PaginaEstado::orderBy('ruta')->paginate(10);

        return view('sistema.control_rutas.index', compact('paginas'));
    }

    public function cambiarEstado(Request $request)
    {
        $ruta = $request->input('ruta');
        $estado = $request->input('estado');
        $fin_mantenimiento = $request->input('fin_mantenimiento'); // Puede ser null

        $pagina = PaginaEstado::where('ruta', $ruta)->first();
        if ($pagina) {
            $pagina->estado = $estado;
            $pagina->fin_mantenimiento = $estado === 'mantenimiento' ? $fin_mantenimiento : null;
            $pagina->save();
        }
        return redirect()->route('paginas.admin')->with('success', 'Estado actualizado correctamente.');
    }
}
