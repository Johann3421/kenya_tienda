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
            'documento' => ['required', 'string', 'size:11', 'regex:/^(10|15|17|20)\d{9}$/'],
            'correo'    => [
                'required', 
                'email', 
                function ($attribute, $value, $fail) {
                    $domain = substr(strrchr($value, "@"), 1);
                    if (!in_array($domain, ['hotmail.com', 'gmail.com', 'outlook.com'])) {
                        $fail('El correo electrónico debe ser de dominio @hotmail.com, @gmail.com o @outlook.com');
                    }
                }
            ],
        ], [
            'documento.size' => 'El RUC debe tener exactamente 11 dígitos.',
            'documento.regex' => 'El RUC ingresado no tiene un formato válido.',
        ]);

        $estado = 'aprobado';

        // 1. Detectar si el email YA está registrado
        $existingUser = \App\Models\UserPrecio::where('email', $request->correo)->first();
        if ($existingUser) {
            return back()
                ->withInput()
                ->withErrors(['correo' => 'Este correo electrónico ya está registrado. Por favor ingresa al portal directamente.']);
        }

        // 2. Detectar si el RUC ya existe
        if (\App\Models\UserPrecio::where('ape_materno', $request->documento)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['documento' => 'Este RUC ya está registrado en el sistema. Contacta a soporte si tienes problemas para ingresar.']);
        }

        // 3. Validar RUC con API externa
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Imxvcml0b3gzNDIxQGdtYWlsLmNvbSJ9.WN9y8akxDNlUsWzvwD1Nv7eJGk3qx5Gaaa6VHmjJyf4';
        $url = "https://dniruc.apisperu.com/api/v1/ruc/{$request->documento}?token={$token}";
        $razonSocial = 'Cliente Web'; // Default
        
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
            
            if (!$response->successful() || !isset($response->json()['ruc'])) {
                return back()
                    ->withInput()
                    ->withErrors(['documento' => 'El RUC ingresado no existe o no es válido.']);
            }
            
            $apiData = $response->json();
            if (isset($apiData['estado']) && strtoupper($apiData['estado']) !== 'ACTIVO') {
                return back()
                    ->withInput()
                    ->withErrors(['documento' => 'El RUC ingresado se encuentra ' . $apiData['estado'] . '. Solo se permiten RUCs activos.']);
            }
            
            $razonSocial = $apiData['razonSocial'] ?? 'Cliente Web';
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error consultando API RUC: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['documento' => 'Error de conexión al validar el RUC. Por favor intenta más tarde.']);
        }

        // 4. Generar contraseña aleatoria
        $password = Str::random(8);

        // 5. Buscar o crear el usuario en users_precios
        try {
            // Buscamos si ya existe por email
            $user = \App\Models\UserPrecio::where('email', $request->correo)->first();
            
            if (!$user) {
                $user = new \App\Models\UserPrecio();
                $user->dni = substr($request->documento, 0, 8); // DNI requiere 8 caracteres exactos en la BD
                $user->nombres = substr($razonSocial, 0, 100);
                $user->ape_paterno = 'Web';
                $user->ape_materno = $request->documento; // Guardamos el documento completo aquí
                $user->telefono = '000000000';
                $user->email = $request->correo;
                $user->username = $request->correo;
                $user->password = Hash::make($password);
                $user->activo = 'SI';
                $user->save();
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
