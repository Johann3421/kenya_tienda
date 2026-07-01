<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Sincroniza la tabla `garantia` desde la base de datos de producción.
 *
 * Uso:
 *   php artisan garantia:sync
 *   php artisan garantia:sync --host=kenya.com.pe --token=SECRET
 *   php artisan garantia:sync --dry-run
 *
 * El endpoint de producción debe existir en:
 *   GET https://{host}/api/garantia/export?token={token}
 *
 * Si no hay endpoint remoto disponible, también soporta importar
 * desde un archivo JSON local:
 *   php artisan garantia:sync --file=garantia_export.json
 */
class SyncGarantiaCommand extends Command
{
    protected $signature = 'garantia:sync
                            {--host=kenya.com.pe : Dominio del servidor de producción}
                            {--token= : Token de autenticación para el endpoint remoto}
                            {--file= : Ruta a un archivo JSON local con los datos a importar}
                            {--dry-run : Solo muestra estadísticas sin modificar la BD}
                            {--truncate : Limpia la tabla local antes de importar (riesgo de pérdida de datos)}';

    protected $description = 'Sincroniza la tabla garantia desde producción (HTTP API o archivo JSON)';

    public function handle(): int
    {
        $dryRun   = $this->option('dry-run');
        $file     = $this->option('file');
        $host     = rtrim((string) $this->option('host'), '/');
        $token    = (string) $this->option('token');
        $truncate = $this->option('truncate');

        $this->info('=== SINCRONIZACIÓN DE TABLA GARANTIA ===');

        // ── 1. Obtener los datos ────────────────────────────────────────────
        if ($file) {
            $records = $this->loadFromFile($file);
        } else {
            $records = $this->loadFromRemote($host, $token);
        }

        if ($records === null) {
            $this->error('No se pudo obtener datos. Verifica el host, token o archivo.');
            return self::FAILURE;
        }

        $total = count($records);
        $this->info("Registros obtenidos: {$total}");

        if ($total === 0) {
            $this->warn('No hay registros para importar.');
            return self::SUCCESS;
        }

        // ── 2. Estadísticas locales ─────────────────────────────────────────
        $localCount   = DB::table('garantia')->count();
        $localMax     = DB::table('garantia')->max('id') ?? 0;
        $localMaxDate = DB::table('garantia')->max('fecha_venta');

        $this->info("BD local actual: {$localCount} registros (ID máx: {$localMax}, fecha más reciente: {$localMaxDate})");

        // ── 3. Filtrar solo los registros nuevos/faltantes ──────────────────
        $existingSeries = DB::table('garantia')->pluck('serie')->toArray();
        $existingIds    = DB::table('garantia')->pluck('id')->toArray();

        $toInsert = [];
        $skipped  = 0;
        $updated  = 0;

        foreach ($records as $row) {
            $row = (array) $row;

            // Limpiar campos de timestamp si están vacíos
            if (isset($row['created_at']) && $row['created_at'] === '') {
                $row['created_at'] = null;
            }
            if (isset($row['updated_at']) && $row['updated_at'] === '') {
                $row['updated_at'] = null;
            }

            // Verificar si ya existe por serie
            if (in_array($row['serie'] ?? '', $existingSeries)) {
                $skipped++;
                continue;
            }

            $toInsert[] = $row;
        }

        $newCount = count($toInsert);
        $this->info("Registros nuevos a insertar: {$newCount}");
        $this->info("Registros ya existentes (omitidos): {$skipped}");

        if ($newCount === 0) {
            $this->info('La BD local ya está actualizada.');
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn('[DRY-RUN] No se modificó la BD.');
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['Registros en producción', $total],
                    ['Registros en local', $localCount],
                    ['Registros a insertar', $newCount],
                    ['Registros ya existentes', $skipped],
                ]
            );

            // Muestra una muestra de los seriales nuevos
            $muestra = array_slice($toInsert, 0, 10);
            $this->info('Muestra de series a insertar:');
            foreach ($muestra as $r) {
                $this->line('  ' . ($r['serie'] ?? '?') . ' - Fecha: ' . ($r['fecha_venta'] ?? '?') . ' - Producto ID: ' . ($r['producto_id'] ?? '?'));
            }

            return self::SUCCESS;
        }

        // ── 4. Insertar en lotes ────────────────────────────────────────────
        $bar = $this->output->createProgressBar($newCount);
        $bar->start();

        $chunks = array_chunk($toInsert, 50);
        $inserted = 0;
        $errors   = 0;

        foreach ($chunks as $chunk) {
            try {
                DB::table('garantia')->insert($chunk);
                $inserted += count($chunk);
            } catch (\Exception $e) {
                // Si falla el bloque, insertar uno a uno para aislar el error
                foreach ($chunk as $row) {
                    try {
                        DB::table('garantia')->insert($row);
                        $inserted++;
                    } catch (\Exception $ex) {
                        $errors++;
                        $this->newLine();
                        $this->warn("Error insertando serie '{$row['serie']}': " . $ex->getMessage());
                    }
                }
            }
            $bar->advance(count($chunk));
        }

        $bar->finish();
        $this->newLine();

        $this->info("✅ Sincronización completada:");
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Insertados exitosamente', $inserted],
                ['Errores', $errors],
                ['Total en BD local ahora', DB::table('garantia')->count()],
            ]
        );

        Log::info('garantia:sync', compact('inserted', 'errors', 'skipped', 'total'));

        return self::SUCCESS;
    }

    /**
     * Carga los registros desde el endpoint HTTP de producción.
     */
    private function loadFromRemote(string $host, string $token): ?array
    {
        if (!$token) {
            // Intentar leer el token del .env
            $token = config('app.garantia_sync_token', env('GARANTIA_SYNC_TOKEN', ''));
        }

        $url = "https://{$host}/api/garantia/export";
        if ($token) {
            $url .= "?token={$token}";
        }

        $this->info("Conectando a: {$url}");

        try {
            $response = Http::timeout(60)->get($url);

            if ($response->failed()) {
                $this->error("Error HTTP {$response->status()} al conectar con producción.");
                $this->warn('Si el endpoint no está habilitado en producción, usa --file= para importar desde un JSON local.');
                return null;
            }

            $data = $response->json();

            if (!isset($data['data']) || !is_array($data['data'])) {
                $this->error('Formato de respuesta inesperado. Se esperaba {"data": [...]}');
                return null;
            }

            return $data['data'];
        } catch (\Exception $e) {
            $this->error('Excepción: ' . $e->getMessage());
            $this->warn('Tip: Si estás en local sin acceso a producción, exporta la tabla garantia como JSON desde phpMyAdmin y usa --file=garantia.json');
            return null;
        }
    }

    /**
     * Carga los registros desde un archivo JSON local.
     *
     * Formato esperado:
     *   [{"id":1,"serie":"OS221021000660",...}, ...]
     * o envuelto en:
     *   {"data": [...]}
     */
    private function loadFromFile(string $filePath): ?array
    {
        // Si es ruta relativa, resolver desde base_path
        if (!str_starts_with($filePath, '/') && !str_starts_with($filePath, 'C:') && !str_starts_with($filePath, 'c:')) {
            $filePath = base_path($filePath);
        }

        if (!file_exists($filePath)) {
            $this->error("Archivo no encontrado: {$filePath}");
            return null;
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            $this->error("No se pudo leer el archivo: {$filePath}");
            return null;
        }

        $decoded = json_decode($content, true);
        if ($decoded === null) {
            $this->error('El archivo no contiene JSON válido.');
            return null;
        }

        // Soportar formato plano o envuelto en "data"
        if (isset($decoded['data']) && is_array($decoded['data'])) {
            return $decoded['data'];
        }

        if (is_array($decoded) && count($decoded) > 0 && isset($decoded[0])) {
            return $decoded;
        }

        $this->error('El archivo JSON no tiene el formato esperado (array o {"data":[...]}).');
        return null;
    }
}
