<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;

class RegistroClienteController extends Controller
{
    /**
     * Muestra el paso 1: Selección de tipo de cliente
     */
    public function paso1()
    {
        return view('registro.paso1');
    }

    /**
     * Muestra el paso 2: Formulario RUC / DNI y Correo
     */
    public function paso2(Request $request)
    {
        $tipo = $request->query('tipo', 'regular');
        return view('registro.paso2', compact('tipo'));
    }

    /**
     * Procesa la validación del paso 2
     */
    public function validarRegistro(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
            'documento' => 'required|string|min:8|max:11',
            'correo' => 'required|email'
        ]);

        // Ponytail: YAGNI. No complex SUNAT/RENIEC API or strict domain validation yet.
        // Simplification: Any valid 8 to 11-digit document and valid email format is "aprobado" for now.
        // The real business logic goes here later.
        
        $estado = 'aprobado'; // Asumimos aprobado por defecto para desatascar

        // Aquí se crearía el usuario/cliente o se guardaría en una tabla temporal.
        // Como simplificación extrema, solo mostramos el resultado.

        return view('registro.resultado', [
            'estado' => $estado,
            'documento' => $request->documento,
            'correo' => $request->correo,
            'tipo' => $request->tipo
        ]);
    }
}
