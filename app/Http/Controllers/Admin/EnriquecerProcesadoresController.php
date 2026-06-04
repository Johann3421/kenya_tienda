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

        // BUSCAR GROQ_API_KEY en múltiples fuentes
        $groqKey = config('groq.api_key') ?: env('GROQ_API_KEY') ?: getenv('GROQ_API_KEY') ?: '';

        // Fallback: leer del archivo que escribe el entrypoint del contenedor
        if (!$groqKey && file_exists('/tmp/.groq_key')) {
            $groqKey = trim(file_get_contents('/tmp/.groq_key'));
        }

        // SIEMPRE sobrescribir el archivo con el valor que tengamos
        file_put_contents('/tmp/.groq_key', $groqKey);
        chmod('/tmp/.groq_key', 0600);

        $groqKeyEsc = escapeshellarg($groqKey);
        
        // Obtener la configuración de la BD de Laravel para pasarla al script de Python
        $dbConn = config('database.default', 'pgsql');
        $dbConfig = config("database.connections.{$dbConn}", []);
        $dbHost = $dbConfig['host'] ?? 'postgres-prod';
        $dbPort = $dbConfig['port'] ?? '5432';
        $dbDatabase = $dbConfig['database'] ?? 'kenya_tienda';
        $dbUsername = $dbConfig['username'] ?? 'kenya_app';
        $dbPassword = $dbConfig['password'] ?? '';

        $dbConnEsc = escapeshellarg($dbConn);
        $dbHostEsc = escapeshellarg($dbHost);
        $dbPortEsc = escapeshellarg($dbPort);
        $dbDatabaseEsc = escapeshellarg($dbDatabase);
        $dbUsernameEsc = escapeshellarg($dbUsername);
        $dbPasswordEsc = escapeshellarg($dbPassword);

        $cmd = "cd {$this->baseDir} && env GROQ_API_KEY={$groqKeyEsc} DB_CONNECTION={$dbConnEsc} DB_HOST={$dbHostEsc} DB_PORT={$dbPortEsc} DB_DATABASE={$dbDatabaseEsc} DB_USERNAME={$dbUsernameEsc} DB_PASSWORD={$dbPasswordEsc} timeout {$timeout} {$python} {$script} {$argStr} 2>&1";

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

    private function extraerUltimoJson(string $output): ?array
    {
        $offset = 0;
        while (($pos = strpos($output, '{', $offset)) !== false) {
            $lastBrace = strrpos($output, '}');
            if ($lastBrace > $pos) {
                $candidato = substr($output, $pos, $lastBrace - $pos + 1);
                $decoded = json_decode($candidato, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }
            $offset = $pos + 1;
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
            $limit = max(1, min((int) $limit, 2000)); // clamp 1-2000
            $args  = array_merge($args, ['--limit', (string) $limit]);
        }

        $resultado = $this->ejecutarScript($args);
        $codigo    = ($resultado['ok'] ?? false) ? 200 : 500;

        return response()->json($resultado, $codigo);
    }

    public function diagnostico(Request $request)
    {
        if ($request->get('token') !== env('ENRICH_TOKEN', 'kenya2026')) {
            return response()->json(['error' => 'Acceso denegado'], 403);
        }

        // Obtener info de la BD según Laravel
        $dbInfo = [];
        try {
            $defaultConn = \Illuminate\Support\Facades\DB::getDefaultConnection();
            $dbConfig = config("database.connections.{$defaultConn}");
            
            // Ocultar password por seguridad
            if (isset($dbConfig['password'])) {
                $dbConfig['password'] = '******';
            }

            $totalProductos = \Illuminate\Support\Facades\DB::table('productos')->count();
            $conProcesador = \Illuminate\Support\Facades\DB::table('productos')->whereNotNull('procesador')->where('procesador', '<>', '')->count();
            $conDesc2 = \Illuminate\Support\Facades\DB::table('productos')->whereNotNull('descripcion_2')->where('descripcion_2', '<>', '')->count();
            
            $samples = \Illuminate\Support\Facades\DB::table('productos')
                ->whereNotNull('procesador')
                ->where('procesador', '<>', '')
                ->limit(5)
                ->get(['id', 'nombre', 'procesador', 'descripcion_2']);

            $dbInfo = [
                'default_connection' => $defaultConn,
                'driver' => $dbConfig['driver'] ?? null,
                'host' => $dbConfig['host'] ?? null,
                'port' => $dbConfig['port'] ?? null,
                'database' => $dbConfig['database'] ?? null,
                'total_productos' => $totalProductos,
                'con_procesador' => $conProcesador,
                'con_descripcion_2' => $conDesc2,
                'samples' => $samples,
            ];
        } catch (\Exception $e) {
            $dbInfo = [
                'error' => $e->getMessage(),
            ];
        }

        $procesadores = [
            'INTEL CORE I7-14700',
            'AMD RYZEN 7 8700G',
            'AMD RYZEN 5 5600X',
        ];

        $resultados = [];

        foreach ($procesadores as $nombre) {
            // Usar el microservicio local de Node.js (Puppeteer Stealth)
            $q = urlencode(trim($nombre));
            $url = "http://localhost:3000/scrape?q={$q}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 45); // Puppeteer puede tardar más
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            $resultados[] = [
                'procesador' => $nombre,
                'url_consultada' => $url,
                'http_code' => $httpCode,
                'curl_error' => $curlError ?: null,
                'respuesta_cruda' => $response ? substr($response, 0, 500) : null,
            ];
        }

        return response()->json([
            'laravel_database' => $dbInfo,
            'scraper_tests' => $resultados
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

