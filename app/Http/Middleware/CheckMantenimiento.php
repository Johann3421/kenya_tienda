<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PaginaEstado;
use Illuminate\Support\Facades\Log;

class CheckMantenimiento
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('health') || $request->is('up')) {
            return $next($request);
        }

        $rutaActual = '/' . ltrim($request->path(), '/');
        // Excepción para la página principal
        if ($request->path() === '/') {
            $rutaActual = '/';
        }

        try {
            $pagina = PaginaEstado::where('ruta', $rutaActual)->first();
        } catch (\Throwable $th) {
            Log::warning('No se pudo consultar el estado de mantenimiento.', [
                'ruta' => $rutaActual,
                'error' => $th->getMessage(),
            ]);

            return $next($request);
        }
        if ($pagina && $pagina->estado === 'mantenimiento') {
    return response()->view('mantenimiento', [
        'fin_mantenimiento' => $pagina->fin_mantenimiento
    ]);
}

        return $next($request);
    }
}
