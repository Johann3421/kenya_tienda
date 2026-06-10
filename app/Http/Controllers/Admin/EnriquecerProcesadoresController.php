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
        @file_put_contents('/tmp/.groq_key', $groqKey);
        @chmod('/tmp/.groq_key', 0600);

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

    /**
     * GET /admin/enriquecer-procesadores
     * Renderiza el panel de control interactivo para realizar el enriquecimiento en lotes seguros.
     */
    public function index(Request $request)
    {
        $token = $request->query('token', '');
        $tokenEsperado = env('ENRICH_TOKEN', 'kenya2026');

        if ($token !== $tokenEsperado) {
            return response()->json([
                'ok'    => false,
                'error' => 'Token inválido o ausente.',
                'hint'  => 'Agrega ?token=<ENRICH_TOKEN> a la URL.',
            ], 403);
        }

        $html = <<<'HTML'
<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenya — Enriquecedor de Procesadores</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
        }
        .glass {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .console-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .console-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }
        .console-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        .console-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="text-slate-100 antialiased font-sans flex flex-col items-center justify-start p-4 md:p-8">

    <div class="w-full max-w-5xl space-y-6">
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 glass rounded-2xl shadow-2xl">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 text-xs font-semibold bg-indigo-500/20 text-indigo-400 rounded-md border border-indigo-500/30">Módulo Admin</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                </div>
                <h1 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-100 to-indigo-400 bg-clip-text text-transparent mt-1">
                    Enriquecedor de Procesadores
                </h1>
                <p class="text-slate-400 text-sm mt-1">Cascada de datos: TechPowerUp (Puppeteer) → Intel ARK → Groq AI Fallback</p>
            </div>
            <div class="flex items-center gap-3">
                <button id="btn-diagnostico" class="px-4 py-2 text-sm font-medium bg-slate-800 border border-slate-700 hover:bg-slate-700 rounded-xl transition duration-200 flex items-center gap-2">
                    🔍 Diagnóstico
                </button>
                <button id="btn-test" class="px-4 py-2 text-sm font-medium bg-slate-800 border border-slate-700 hover:bg-slate-700 rounded-xl transition duration-200 flex items-center gap-2">
                    🧪 Test de Fuentes
                </button>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-6 glass rounded-2xl shadow-xl flex flex-col justify-between">
                <span class="text-xs text-slate-400 uppercase font-semibold tracking-wider">Productos en BD</span>
                <span id="stat-total-bd" class="text-4xl font-bold text-slate-200 mt-2">...</span>
                <div class="text-xs text-slate-400 mt-2 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-600"></span> Con CPU configurado
                </div>
            </div>
            <div class="p-6 glass rounded-2xl shadow-xl flex flex-col justify-between">
                <span class="text-xs text-slate-400 uppercase font-semibold tracking-wider">Campos Completos</span>
                <span id="stat-con-desc" class="text-4xl font-bold text-emerald-400 mt-2">...</span>
                <div class="text-xs text-slate-400 mt-2 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Poseen descripcion_2
                </div>
            </div>
            <div class="p-6 glass rounded-2xl shadow-xl flex flex-col justify-between">
                <span class="text-xs text-slate-400 uppercase font-semibold tracking-wider">Pendientes</span>
                <span id="stat-sin-desc" class="text-4xl font-bold text-amber-500 mt-2">...</span>
                <div class="text-xs text-slate-400 mt-2 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Campos descripción 2 vacíos
                </div>
            </div>
            <div class="p-6 glass rounded-2xl shadow-xl flex flex-col justify-between">
                <span class="text-xs text-slate-400 uppercase font-semibold tracking-wider">Porcentaje</span>
                <span id="stat-pct" class="text-4xl font-bold text-indigo-400 mt-2">...</span>
                <div class="text-xs text-slate-400 mt-2 flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Completitud global
                </div>
            </div>
        </div>

        <!-- Controls & Configuration -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Control Card -->
            <div class="p-6 glass rounded-2xl shadow-xl space-y-6 lg:col-span-1">
                <h2 class="text-lg font-semibold text-slate-200">Panel de Control</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Tamaño del Lote (Límite)</label>
                        <div class="flex items-center gap-3">
                            <input id="range-limit" type="range" min="10" max="200" step="10" value="50" class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                            <span id="lbl-limit" class="text-sm font-semibold px-2.5 py-1 bg-slate-800 border border-slate-700 rounded-md">50</span>
                        </div>
                        <p class="text-slate-400 text-xs mt-1">Lotes pequeños (50) evitan timeouts y son más seguros.</p>
                    </div>

                    <div class="pt-4 border-t border-slate-800/80 space-y-3">
                        <button id="btn-toggle-run" class="w-full py-3.5 bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 font-bold rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/20 active:scale-[0.98]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Iniciar Enriquecimiento
                        </button>
                        <button id="btn-pause" disabled class="w-full py-3 bg-slate-800 border border-slate-700 text-slate-300 hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed font-medium rounded-xl transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pausar
                        </button>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800/80 space-y-2">
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Estadísticas del Lote</h3>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-2 bg-slate-900/60 rounded-lg">
                            <span class="text-slate-400 block">Cache Hits:</span>
                            <span id="stats-lote-cache" class="font-bold text-amber-400 text-sm">0</span>
                        </div>
                        <div class="p-2 bg-slate-900/60 rounded-lg">
                            <span class="text-slate-400 block">Groq AI:</span>
                            <span id="stats-lote-groq" class="font-bold text-emerald-400 text-sm">0</span>
                        </div>
                        <div class="p-2 bg-slate-900/60 rounded-lg">
                            <span class="text-slate-400 block">TechPowerUp:</span>
                            <span id="stats-lote-tpu" class="font-bold text-indigo-400 text-sm">0</span>
                        </div>
                        <div class="p-2 bg-slate-900/60 rounded-lg">
                            <span class="text-slate-400 block">Intel ARK:</span>
                            <span id="stats-lote-ark" class="font-bold text-blue-400 text-sm">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Console Log Panel -->
            <div class="p-6 glass rounded-2xl shadow-xl flex flex-col lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-200">Terminal & Logs en Tiempo Real</h2>
                    <button id="btn-clear-console" class="text-xs text-slate-400 hover:text-slate-200 transition">🧹 Limpiar</button>
                </div>
                
                <div id="console" class="flex-1 min-h-[300px] max-h-[420px] bg-slate-950/80 border border-slate-800/50 p-4 rounded-xl font-mono text-xs text-slate-300 overflow-y-auto space-y-1 console-scrollbar">
                    <div class="text-indigo-400">--- Terminal de Control Kenya Iniciada ---</div>
                    <div class="text-slate-500">Cargando estado inicial, por favor espere...</div>
                </div>
            </div>
        </div>

        <!-- Global Progress Bar -->
        <div class="p-6 glass rounded-2xl shadow-xl space-y-3">
            <div class="flex justify-between items-center text-sm font-semibold text-slate-300">
                <span>Progreso General</span>
                <span id="progress-bar-pct">0%</span>
            </div>
            <div class="w-full bg-slate-950 rounded-full h-4 overflow-hidden p-0.5 border border-slate-800/80">
                <div id="progress-bar-fill" class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-full rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <!-- Script del Dashboard -->
    <script>
        const token = new URLSearchParams(window.location.search).get('token') || '';
        const limitRange = document.getElementById('range-limit');
        const limitLabel = document.getElementById('lbl-limit');
        const consoleEl = document.getElementById('console');
        const btnToggleRun = document.getElementById('btn-toggle-run');
        const btnPause = document.getElementById('btn-pause');
        const btnClearConsole = document.getElementById('btn-clear-console');
        const btnDiagnostico = document.getElementById('btn-diagnostico');
        const btnTest = document.getElementById('btn-test');

        let isRunning = false;
        let isPaused = false;
        
        let totalStats = { cache: 0, groq: 0, techpowerup: 0, intel_ark: 0, fallidos: 0 };

        // Helper para log en la terminal
        function log(message, type = 'info') {
            const time = new Date().toLocaleTimeString();
            let color = 'text-slate-300';
            if (type === 'success') color = 'text-emerald-400 font-semibold';
            if (type === 'warning') color = 'text-amber-400';
            if (type === 'error') color = 'text-red-400 font-bold';
            if (type === 'highlight') color = 'text-indigo-400';
            if (type === 'muted') color = 'text-slate-500';

            const logItem = document.createElement('div');
            logItem.innerHTML = `<span class="text-slate-500">[${time}]</span> <span class="${color}">${message}</span>`;
            consoleEl.appendChild(logItem);
            consoleEl.scrollTop = consoleEl.scrollHeight;
        }

        // Cargar Estadísticas Iniciales de la Base de Datos
        async function fetchStatus() {
            try {
                const res = await fetch(`/admin/enriquecer-procesadores/status?token=${token}`);
                const data = await res.json();
                if (data.ok) {
                    const total = data.productos_con_procesador || 0;
                    const sinDesc = data.productos_sin_descripcion_2 || 0;
                    const conDesc = total - sinDesc;
                    const pct = total > 0 ? ((conDesc / total) * 100).toFixed(1) : 0;

                    document.getElementById('stat-total-bd').textContent = total;
                    document.getElementById('stat-con-desc').textContent = conDesc;
                    document.getElementById('stat-sin-desc').textContent = sinDesc;
                    document.getElementById('stat-pct').textContent = `${pct}%`;

                    document.getElementById('progress-bar-pct').textContent = `${pct}%`;
                    document.getElementById('progress-bar-fill').style.width = `${pct}%`;

                    return { total, sinDesc };
                } else {
                    log(`Error cargando status: ${data.error || 'Desconocido'}`, 'error');
                }
            } catch (err) {
                log(`Error de conexión con la BD: ${err.message}`, 'error');
            }
            return null;
        }

        // Inicialización
        window.addEventListener('DOMContentLoaded', async () => {
            log('Obteniendo estado inicial de la base de datos...', 'muted');
            const state = await fetchStatus();
            if (state) {
                log(`Base de datos sincronizada. Pendientes: ${state.sinDesc} de ${state.total} productos.`, 'success');
            }
        });

        // Eventos de botones extras
        btnDiagnostico.addEventListener('click', async () => {
            log('Iniciando diagnóstico rápido...', 'highlight');
            try {
                const res = await fetch(`/admin/enriquecer-procesadores/diagnostico?token=${token}`);
                const data = await res.json();
                log('Resultados del diagnóstico:', 'muted');
                log(JSON.stringify(data.laravel_database || data, null, 2).replace(/\n/g, '<br>&nbsp;&nbsp;'), 'info');
            } catch (err) {
                log(`Error de conexión al diagnosticar: ${err.message}`, 'error');
            }
        });

        btnTest.addEventListener('click', async () => {
            log('Iniciando prueba rápida de fuentes (5 CPU fijos)...', 'highlight');
            try {
                const res = await fetch(`/admin/enriquecer-procesadores/test?token=${token}`);
                const data = await res.json();
                if (data.ok) {
                    log('Resultados de prueba:', 'success');
                    data.resultados.forEach(r => {
                        log(`• CPU: ${r.procesador} | Fuente: ${r.fuente} | Estado: ${r.descripcion_2 ? 'Completado' : 'Fallido'}`, r.descripcion_2 ? 'success' : 'warning');
                    });
                }
            } catch (err) {
                log(`Error de conexión al probar fuentes: ${err.message}`, 'error');
            }
        });

        // Configuración Slider
        limitRange.addEventListener('input', (e) => {
            limitLabel.textContent = e.target.value;
        });

        // Limpiar Consola
        btnClearConsole.addEventListener('click', () => {
            consoleEl.innerHTML = '<div class="text-slate-500">Terminal de logs limpiada.</div>';
        });

        // Bucle de Ejecución por Lotes
        async function runBatch() {
            if (!isRunning || isPaused) return;

            const limit = parseInt(limitRange.value);
            log(`Solicitando lote de enriquecimiento (límite: ${limit} productos)...`, 'highlight');

            try {
                const t0 = performance.now();
                const res = await fetch(`/admin/enriquecer-procesadores/run?token=${token}&limit=${limit}`);
                const data = await res.json();
                const duration = ((performance.now() - t0) / 1000).toFixed(1);

                if (data.ok) {
                    const stats = data.stats || {};
                    const updated = data.actualizados || 0;

                    // Actualizar contadores acumulativos
                    totalStats.cache += stats.cache || 0;
                    totalStats.groq += stats.groq || 0;
                    totalStats.techpowerup += stats.techpowerup || 0;
                    totalStats.intel_ark += stats.intel_ark || 0;
                    totalStats.fallidos += stats.fallido || 0;

                    document.getElementById('stats-lote-cache').textContent = totalStats.cache;
                    document.getElementById('stats-lote-groq').textContent = totalStats.groq;
                    document.getElementById('stats-lote-tpu').textContent = totalStats.techpowerup;
                    document.getElementById('stats-lote-ark').textContent = totalStats.intel_ark;

                    log(`Lote completado exitosamente en ${duration}s. Fila(s) actualizadas: ${updated}`, 'success');
                    log(`Estadísticas del lote: TPU: ${stats.techpowerup || 0} | Intel: ${stats.intel_ark || 0} | Groq: ${stats.groq || 0} | Cache: ${stats.cache || 0}`, 'muted');

                    // Recargar stats de la base de datos
                    const state = await fetchStatus();

                    if (state && state.sinDesc > 0) {
                        // Continuar al siguiente lote de forma segura
                        setTimeout(runBatch, 1500); // 1.5s delay entre llamadas
                    } else {
                        log('🎉 ¡Enhorabuena! Se han enriquecido todos los productos disponibles en la base de datos.', 'success');
                        stopRun();
                    }
                } else {
                    log(`Error retornado del servidor: ${data.error || 'Desconocido'}. Reintentando en 5s...`, 'error');
                    setTimeout(runBatch, 5000);
                }
            } catch (err) {
                log(`Error de conexión (Timeout o Cloudflare 524). Es posible que el lote se siga procesando en segundo plano. Reintentando lote en 8 segundos...`, 'warning');
                setTimeout(runBatch, 8000);
            }
        }

        // Acciones del Botón Ejecutar
        btnToggleRun.addEventListener('click', () => {
            if (isRunning) {
                stopRun();
            } else {
                startRun();
            }
        });

        btnPause.addEventListener('click', () => {
            if (isPaused) {
                isPaused = false;
                btnPause.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pausar`;
                log('Ejecución REANUDADA.', 'success');
                runBatch();
            } else {
                isPaused = true;
                btnPause.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Reanudar`;
                log('Ejecución PAUSADA al finalizar el lote actual.', 'warning');
            }
        });

        function startRun() {
            isRunning = true;
            isPaused = false;
            btnToggleRun.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Detener Proceso`;
            btnToggleRun.classList.remove('from-indigo-500', 'to-violet-600');
            btnToggleRun.classList.add('from-red-500', 'to-rose-600');
            btnPause.removeAttribute('disabled');
            log('Iniciando enriquecimiento general en lotes automatizados...', 'success');
            runBatch();
        }

        function stopRun() {
            isRunning = false;
            isPaused = false;
            btnToggleRun.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Iniciar Enriquecimiento`;
            btnToggleRun.classList.remove('from-red-500', 'to-rose-600');
            btnToggleRun.classList.add('from-indigo-500', 'to-violet-600');
            btnPause.setAttribute('disabled', 'true');
            btnPause.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pausar`;
            log('Proceso detenido manualmente.', 'warning');
        }
    </script>
</body>
</html>
HTML;

        return response($html, 200)->header('Content-Type', 'text/html');
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

