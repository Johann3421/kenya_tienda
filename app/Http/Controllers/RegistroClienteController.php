<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\User;
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
            'tipo'      => 'required|string',
            'documento' => 'required|string|min:8|max:11',
            'correo'    => 'required|email',
        ]);

        // Ponytail: YAGNI — no SUNAT/RENIEC API, simple format check only
        $estado = 'aprobado';

        // 1. Detectar si el email YA está registrado
        $existingUser = User::where('email', $request->correo)->first();
        if ($existingUser) {
            return back()
                ->withInput()
                ->withErrors(['correo' => 'Este correo electrónico ya está registrado. Por favor ingresa al portal directamente.']);
        }

        // 2. Detectar si el DNI ya existe
        $dniCorto = substr($request->documento, 0, 8);
        if (User::where('dni', $dniCorto)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['documento' => 'Este documento ya está registrado en el sistema. Contacta a soporte si tienes problemas para ingresar.']);
        }

        // 3. Generar contraseña aleatoria
        $password = Str::random(8);

        // 2. Buscar o crear el usuario (role = cliente_web, username = correo)
        try {
            // Buscamos si ya existe por email
            $user = User::where('email', $request->correo)->first();
            
            if (!$user) {
                $user = new User();
                $user->dni = substr($request->documento, 0, 8); // DNI requiere 8 caracteres exactos
                $user->nombres = 'Cliente';
                $user->ape_paterno = 'Web';
                $user->ape_materno = $request->documento; // Guardamos el documento completo aquí
                $user->telefono = '000000000';
                $user->email = $request->correo;
                $user->username = $request->correo;
                $user->password = Hash::make($password);
                $user->activo = 'SI';
                $user->save();

                // Asignar rol si Spatie Permission está instalado (ignorar si falla)
                try { $user->assignRole('cliente_web'); } catch (\Exception $e) {}
            } else {
                // Si ya existe, actualizamos su contraseña al nuevo password generado para este intento
                $user->password = Hash::make($password);
                $user->save();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creando o actualizando usuario cliente: ' . $e->getMessage());
        }

        // 3. Enviar correo con credenciales (Forzado directo por código)
        $correoEnviado = false;
        try {
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => 'mail.abadgroup.tech',
                'mail.mailers.smtp.port' => 587,
                'mail.mailers.smtp.encryption' => 'tls',
                'mail.mailers.smtp.username' => 'prueba@kenya.com.pe',
                'mail.mailers.smtp.password' => 'nY5g5nDhoqhha3Ah',
                'mail.from.address' => 'prueba@kenya.com.pe',
                'mail.from.name' => 'Kenya',
                'mail.mailers.smtp.stream' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]
            ]);

            Mail::to($request->correo)->send(new CredencialesClienteMail($request->correo, $password));
            $correoEnviado = true;
        } catch (\Exception $e) {
            // Devolver error en JSON directamente para ver qué falla en producción
            return response()->json([
                'error' => 'Excepción atrapada al enviar e-mail',
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'stack' => substr($e->getTraceAsString(), 0, 500)
            ], 500);
        }

        return view('registro.resultado', [
            'estado' => $estado,
            'documento' => $request->documento,
            'correo' => $request->correo,
            'tipo' => $request->tipo,
            'correoEnviado' => $correoEnviado
        ]);
    }
}
