<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * EnriquecerProcesadoresController
 * ---------------------------------
 * Expone endpoints HTTP que invocan enriquecer_endpoint.py
 * dentro del contenedor Dokploy vía shell_exec.
 *
 * Rutas:
 *   GET /admin/enriquecer-procesadores/status?token=kenya2026
 *   GET /admin/enriquecer-procesadores/test?token=kenya2026
 *   GET /admin/enriquecer-procesadores/dry-run?token=kenya2026&limit=10
 *   GET /admin/enriquecer-procesadores/run?token=kenya2026&limit=50
 */
class EnriquecerProcesadoresController extends Controller
{
    /** Ruta absoluta al directorio raíz de la app (donde viven los .py) */
    private string $baseDir;

    /** Tiempo máximo en segundos para esperar la ejecución del script */
    private int $timeoutSeconds;

    public function __construct()
    {
        $this->baseDir        = base_path();
        $this->timeoutSeconds = 300; // 5 min máximo por request
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Middleware de token simple (no requiere sesión Laravel)
    // ─────────────────────────────────────────────────────────────────────────

    private function verificarToken(Request $request): ?JsonResponse
    {
        $tokenEsperado = env('ENRICH_TOKEN', 'kenya2026');
        $tokenRecibido = $request->query('token', '');

        if ($tokenRecibido !== $tokenEsperado) {
            return response()->json([
                'ok'    => false,
                'error' => 'Token inválido o ausente.',
                'hint'  => 'Agrega ?token=<ENRICH_TOKEN> a la URL.',
            ], 403);
        }

        return null;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Ejecutor del script Python
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Ejecuta enriquecer_endpoint.py con los argumentos dados
     * y retorna el JSON parseado.
     *
     * @param  array<string> $args  p.ej. ['test'] o ['dry-run', '--limit', '10']
     */
    private function ejecutarScript(array $args): array
    {
        $python  = $this->detectarPython();
        $script  = escapeshellarg($this->baseDir . '/enriquecer_endpoint.py');
        $argStr  = implode(' ', array_map('escapeshellarg', $args));
        $timeout = (int) $this->timeoutSeconds;

        // Cambiar al directorio base para que load_dotenv encuentre el .env
        $cmd = "cd {$this->baseDir} && timeout {$timeout} {$python} {$script} {$argStr} 2>&1";

        $output = shell_exec($cmd);

        if ($output === null || trim($output) === '') {
            return [
                'ok'     => false,
                'error'  => 'El script no produjo salida (timeout o error de ejecución).',
                'cmd'    => $cmd,
            ];
        }

        // El script puede tener líneas de log antes del JSON final.
        // Extraemos el primer bloque JSON válido desde el final del output.
        $json = $this->extraerUltimoJson($output);

        if ($json === null) {
            return [
                'ok'     => false,
                'error'  => 'El script no retornó JSON válido.',
                'output' => substr($output, -2000), // últimos 2000 chars para debug
            ];
        }

        return $json;
    }

    /**
     * Busca el último bloque JSON completo en el output del script.
     * El script puede imprimir logs de texto antes del JSON final.
     */
    private function extraerUltimoJson(string $output): ?array
    {
        // Buscar el último '{' que abra un objeto JSON completo
        $pos = strrpos($output, '{');
        if ($pos === false) {
            return null;
        }

        $candidato = substr($output, $pos);
        $decoded   = json_decode($candidato, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return null;
    }

    /**
     * Detecta el binario python3 o python disponible en el contenedor.
     */
    private function detectarPython(): string
    {
        foreach (['python3', 'python'] as $bin) {
            $ver = shell_exec("which {$bin} 2>/dev/null");
            if ($ver && trim($ver) !== '') {
                return $bin;
            }
        }
        return 'python3'; // fallback optimista
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Endpoints públicos
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * GET /admin/enriquecer-procesadores/status
     * Verifica conexión a BD y muestra estadísticas básicas.
     */
    public function status(Request $request): JsonResponse
    {
        if ($err = $this->verificarToken($request)) {
            return $err;
        }

        $resultado = $this->ejecutarScript(['status']);
        $codigo    = ($resultado['ok'] ?? false) ? 200 : 500;

        return response()->json($resultado, $codigo);
    }

    /**
     * GET /admin/enriquecer-procesadores/test
     * Prueba los 5 procesadores fijos. No modifica la BD.
     */
    public function test(Request $request): JsonResponse
    {
        if ($err = $this->verificarToken($request)) {
            return $err;
        }

        $resultado = $this->ejecutarScript(['test']);
        $codigo    = ($resultado['ok'] ?? false) ? 200 : 500;

        return response()->json($resultado, $codigo);
    }

    /**
     * GET /admin/enriquecer-procesadores/dry-run?limit=10
     * Muestra qué escribiría en descripcion_2 sin hacer UPDATE.
     */
    public function dryRun(Request $request): JsonResponse
    {
        if ($err = $this->verificarToken($request)) {
            return $err;
        }

        $limit = (int) $request->query('limit', 10);
        $limit = max(1, min($limit, 100)); // clamp 1-100

        $resultado = $this->ejecutarScript(['dry-run', '--limit', (string) $limit]);
        $codigo    = ($resultado['ok'] ?? false) ? 200 : 500;

        return response()->json($resultado, $codigo);
    }

    /**
     * GET /admin/enriquecer-procesadores/run?limit=50
     * Enriquecimiento real: hace UPDATE en la BD.
     *
     * ⚠️  Solo usar cuando status y dry-run confirmen que todo está bien.
     */
    public function run(Request $request): JsonResponse
    {
        if ($err = $this->verificarToken($request)) {
            return $err;
        }

        $limit = $request->query('limit');
        $args  = ['run'];

        if ($limit !== null) {
            $limit = max(1, min((int) $limit, 500)); // clamp 1-500
            $args  = array_merge($args, ['--limit', (string) $limit]);
        }

        $resultado = $this->ejecutarScript($args);
        $codigo    = ($resultado['ok'] ?? false) ? 200 : 500;

        return response()->json($resultado, $codigo);
    }
}
