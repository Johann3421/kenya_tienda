<?php

namespace App\Http\Controllers;

use App\Models\SerialAttempt;
use App\Models\SerialDeviceLock;
use App\Models\SerialNumber;
use App\Models\SerialReward;
use App\Models\SerialRewardClaim;
use App\Garantia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SerialDrawController extends Controller
{
    /**
     * Muestra la página principal del sorteo
     */
    public function index()
    {
        return view('sorteo.index');
    }

    /**
     * Procesa el número de serie y determina el premio
     */
    public function store(Request $request)
    {
        // Validar manualmente para tener más control
        $validator = \Validator::make($request->all(), [
            'serial' => 'required|string|max:120',
        ]);

        if ($validator->fails()) {
            \Log::error('Sorteo - Validación falló', [
                'errors' => $validator->errors()->toArray(),
                'request_all' => $request->all(),
                'request_content' => $request->getContent(),
                'headers' => $request->headers->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos: ' . $validator->errors()->first(),
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        \Log::info('Sorteo - Validación exitosa', ['serial' => $request->serial]);

        $serial = strtoupper(trim($request->serial));

        \Log::info('Sorteo - Buscando serial en garantia', ['serial' => $serial]);

        // Verificar si el serial existe en la tabla de garantía
        $garantia = Garantia::where('serie', $serial)
            ->where('activo', 'Si')
            ->first();

        \Log::info('Sorteo - Resultado búsqueda', [
            'encontrado' => $garantia ? 'SI' : 'NO',
            'garantia' => $garantia
        ]);

        if (!$garantia) {
            \Log::error('Sorteo - Serial no encontrado en garantía', ['serial' => $serial]);
            return response()->json([
                'success' => false,
                'message' => 'Número de serie no válido o inactivo. Por favor verifica e intenta nuevamente.'
            ], 422);
        }

        $productoId = $garantia->producto_id;
        $deviceFingerprint = $this->generateDeviceFingerprint($request);

        // Verificar si el dispositivo ya está bloqueado con este serial
        $deviceLock = SerialDeviceLock::where('device_hash', $deviceFingerprint)
            ->where('serial', $serial)
            ->first();

        if ($deviceLock) {
            return response()->json([
                'success' => false,
                'message' => 'Este dispositivo ya ha participado con este número de serie.'
            ], 422);
        }

        // Contar intentos del día de hoy
        $today = now()->format('Y-m-d');
        $attemptCount = SerialAttempt::where('device_fingerprint', $deviceFingerprint)
            ->where('attempt_date', $today)
            ->count();

        $newAttemptNumber = $attemptCount + 1;

        // Crear o actualizar el bloqueo del dispositivo
        $deviceLock = SerialDeviceLock::updateOrCreate(
            ['device_hash' => $deviceFingerprint],
            [
                'serial' => $serial,
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'locked_at' => now(),
                'last_attempt_at' => now(),
            ]
        );

        // Determinar el premio según el número de intentos
        $reward = SerialReward::active()
            ->where('attempt_threshold', '<=', $newAttemptNumber)
            ->orderBy('attempt_threshold', 'desc')
            ->first();

        // Crear el intento
        $attempt = SerialAttempt::create([
            'producto_id' => $productoId,
            'serial' => $serial,
            'device_id' => null,
            'serial_reward_id' => $reward ? $reward->id : null,
            'serial_device_lock_id' => $deviceLock->id,
            'device_fingerprint' => $deviceFingerprint,
            'client_ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'attempt_number' => $newAttemptNumber,
            'attempt_date' => $today,
        ]);

        // Obtener todos los premios activos para la ruleta
        $allRewards = SerialReward::active()
            ->orderBy('attempt_threshold', 'asc')
            ->get()
            ->map(function($r) {
                return [
                    'id' => $r->id,
                    'title' => $r->title,
                    'description' => $r->description,
                    'threshold' => $r->attempt_threshold,
                ];
            });

        return response()->json([
            'success' => true,
            'attempt_id' => $attempt->id,
            'attempt_number' => $newAttemptNumber,
            'reward' => $reward ? [
                'id' => $reward->id,
                'title' => $reward->title,
                'description' => $reward->description,
                'threshold' => $reward->attempt_threshold,
            ] : null,
            'all_rewards' => $allRewards,
            'message' => $reward
                ? '¡Felicidades! Has ganado: ' . $reward->title
                : 'Sigue participando para ganar premios increíbles.'
        ]);
    }

    /**
     * Procesa el reclamo de un premio
     */
    public function claim(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:serial_attempts,id',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:255',
        ]);

        $attempt = SerialAttempt::with('reward')->findOrFail($request->attempt_id);

        if (!$attempt->serial_reward_id) {
            return response()->json([
                'success' => false,
                'message' => 'Este intento no tiene un premio asociado.'
            ], 422);
        }

        // Verificar si ya se reclamó
        if ($attempt->claim) {
            return response()->json([
                'success' => false,
                'message' => 'Este premio ya ha sido reclamado.'
            ], 422);
        }

        // Generar código único del premio
        $codigoPremio = 'PR-' . strtoupper(uniqid());

        // Crear el reclamo
        $claim = SerialRewardClaim::create([
            'serial_attempt_id' => $attempt->id,
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'codigo_premio' => $codigoPremio,
            'claimed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'codigo_premio' => $codigoPremio,
            'message' => '¡Premio reclamado exitosamente! Tu código es: ' . $codigoPremio
        ]);
    }

    /**
     * Genera un fingerprint único del dispositivo
     */
    private function generateDeviceFingerprint(Request $request)
    {
        $components = [
            $request->ip(),
            $request->userAgent(),
            $request->header('Accept-Language'),
            $request->header('Accept-Encoding'),
        ];

        return hash('sha256', implode('|', array_filter($components)));
    }
}
