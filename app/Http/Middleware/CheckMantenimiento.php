<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PaginaEstado;

class CheckMantenimiento
{
    public function handle(Request $request, Closure $next)
    {
        $rutaActual = '/' . ltrim($request->path(), '/');
        // Excepción para la página principal
        if ($request->path() === '/') {
            $rutaActual = '/';
        }

        $pagina = PaginaEstado::where('ruta', $rutaActual)->first();
        if ($pagina && $pagina->estado === 'mantenimiento') {
    return response()->view('mantenimiento', [
        'fin_mantenimiento' => $pagina->fin_mantenimiento
    ]);
}

        return $next($request);
    }
}
