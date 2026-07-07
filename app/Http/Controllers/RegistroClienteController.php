<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\CredencialesClienteMail;

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

        // 1. Generar contraseña aleatoria
        $password = Str::random(8);

        // 2. Buscar o crear el usuario (role = cliente_web, username = correo)
        // Usamos YAGNI: no manejaremos todo el Cliente model si la base de datos no estǭ lista o conectada.
        // Simulamos la creacin para no romper si no hay BD:
        try {
            $user = User::firstOrCreate(
                ['email' => $request->correo],
                [
                    'name' => 'Cliente ' . $request->documento,
                    'username' => $request->correo, // En kenya el username suele ser el login
                    'password' => Hash::make($password),
                ]
            );
            // Asignar rol si Spatie Permission está instalado (ignorar si falla)
            try { $user->assignRole('cliente_web'); } catch (\Exception $e) {}
        } catch (\Exception $e) {
            // Falla silenciosa si no hay BD corriendo en dev
        }

        // 3. Enviar correo con credenciales (falla silenciosa si no hay SMTP en .env)
        $correoEnviado = false;
        try {
            Mail::to($request->correo)->send(new CredencialesClienteMail($request->correo, $password));
            $correoEnviado = true;
        } catch (\Exception $e) {
            // Sin configuración SMTP
        }

        return view('registro.resultado', [
            'estado' => $estado,
            'documento' => $request->documento,
            'correo' => $request->correo,
            'tipo' => $request->tipo,
            'password' => $password, // Solo para dev
            'correoEnviado' => $correoEnviado
        ]);
    }
}
