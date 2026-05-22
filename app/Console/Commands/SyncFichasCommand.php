<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncFichasCommand extends Command
{
    protected $signature = 'sync:fichas
                            {--dry-run : Solo muestra lo que haría, sin escribir en BD}
                            {--solo-vigencia : Solo actualiza vigencia, no las specs del producto}
                            {--crear : Crea productos nuevos en BD para fichas sin match}
                            {--suspender-sin-ficha : Marca SUSPENDIDA los productos de modelos PC que no tienen codigo_pc}
                            {--file= : Usar JSON local en vez de llamar a la API}';

    protected $description = 'Sincroniza características y vigencia de productos Kenya desde el auditor Peru Compras';

    const API_BASE = 'https://api-auditor.sekaitech.com.pe/api/v1';

    /** Grupos de modelos que tienen representación en Peru Compras */
    const PC_MODEL_GROUPS = ['EZENT', 'PROWORK', 'OFISZU', 'RAITO', 'GENWORK'];

    // Tokens del texto de descripción → columna en productos
    // Orden importa: tokens más largos primero para evitar matches parciales
    const SPEC_TOKENS = [
        'SUITE OFIMATICA PRE-INSTALADA:' => 'suite_ofimatica',
        'SUITE OFIMATICA:'               => 'suite_ofimatica',
        'SIST. OPER:'                    => 'sistema_operativo',
        'SISTEMA OPERATIVO:'             => 'sistema_operativo',
        'UNIDAD OPTICA:'                 => 'unidad_optica',
        'PROCESADOR:'                    => 'procesador',
        'ALMACENAMIENTO:'                => 'almacenamiento',
        'PANTALLA:'                      => 'pantalla',
        'G. F:'                          => 'garantia_de_fabrica',
        'TECLADO:'                       => 'teclado',
        'MOUSE:'                         => 'mouse',
        'WLAN:'                          => 'conectividad_wlan',
        'HDMI:'                          => 'video_hdmi',
        'RAM:'                           => 'ram',
        'LAN:'                           => 'conectividad',
        'USB:'                           => 'conectividad_usb',
        'VGA:'                           => 'video_vga',
    ];

    /** Etiquetas legibles para la tabla especificaciones */
    const SPEC_LABELS = [
        // ── Computadoras / PCs ──────────────────────────────────────────────
        'procesador'          => 'Procesador',
        'ram'                 => 'RAM',
        'almacenamiento'      => 'Almacenamiento',
        'graficos'            => 'Gráficos',
        'conectividad'        => 'Conectividad LAN',
        'conectividad_wlan'   => 'Conectividad WLAN',
        'conectividad_usb'    => 'Conectividad USB',
        'video_vga'           => 'Salida VGA',
        'video_hdmi'          => 'Salida HDMI',
        'sistema_operativo'   => 'Sistema Operativo',
        'unidad_optica'       => 'Unidad Óptica',
        'teclado'             => 'Teclado',
        'mouse'               => 'Mouse',
        'suite_ofimatica'     => 'Suite Ofimática',
        'garantia_de_fabrica' => 'Garantía de Fábrica',
        // ── Monitores ───────────────────────────────────────────────────────
        'tamano_pantalla'     => 'Tamaño de Pantalla',
        'panel'               => 'Panel',
        'resolucion'          => 'Resolución',
        'contraste'           => 'Contraste',
        'brillo'              => 'Brillo',
        'tiempo_respuesta'    => 'Tiempo de Respuesta',
        'hdmi'                => 'HDMI',
        'displayport'         => 'DisplayPort',
        'soporte_vesa'        => 'Soporte VESA',
        'accesorios'          => 'Accesorios',
        'otros'               => 'Características Adicionales',
    ];

    /** Caché modelo_group → modelo_id */
    private array $modelosCache    = [];
    /** Caché categoria_api → categoria_id */
    private array $categoriasCache = [];

    // ─── Entry point ──────────────────────────────────────────────────────────

    public function handle(): int
    {
        $dryRun            = $this->option('dry-run');
        $soloVig           = $this->option('solo-vigencia');
        $crearNuevos       = $this->option('crear');
        $suspenderSinFicha = $this->option('suspender-sin-ficha');
        $file              = $this->option('file');

        $fichas = $file ? $this->loadFromFile($file) : $this->fetchFromApi();

        if (empty($fichas)) {
            $this->error("No se obtuvieron fichas.");
            return self::FAILURE;
        }

        $this->info("Fichas obtenidas: " . count($fichas));

        // Deduplicar: mismo código puede aparecer en múltiples acuerdos marco
        $deduped = [];
        foreach ($fichas as $f) {
            $codigo = strtoupper($f['codigo_ficha'] ?? '');
            if ($codigo) {
                $deduped[$codigo] = $f;
            }
        }
        $totalUnique = count($deduped);
        $this->info("Fichas únicas (nro_parte): {$totalUnique}");

        $updated   = 0;
        $creados   = 0;
        $noMatch   = 0;
        $suspended = 0;
        $ofertados = 0;

        $bar = $this->output->createProgressBar($totalUnique);
        $bar->start();

        foreach ($deduped as $codigo => $ficha) {
            $estado = strtoupper($ficha['estado'] ?? 'OFERTADA');
            $specs  = $ficha['specs'] ?? [];

            $producto = $this->findProducto($codigo);

            if (!$producto) {
                $noMatch++;
                if ($crearNuevos) {
                    if ($this->crearProducto($codigo, $ficha, $dryRun)) {
                        $creados++;
                        if ($estado === 'SUSPENDIDA') {
                            $suspended++;
                        } else {
                            $ofertados++;
                        }
                    }
                }
                $bar->advance();
                continue;
            }

            // — Producto encontrado: actualizar —
            $data = [
                'codigo_pc'     => $codigo,
                'vigencia'      => $estado,
                'ficha_sync_at' => now(),
            ];

            if (!$soloVig && !empty($specs)) {
                $this->mergeSpecs($data, $specs);
            }

            if (!$dryRun) {
                DB::table('productos')->where('id', $producto->id)->update($data);
                if (!$soloVig && !empty($specs)) {
                    $this->syncEspecificaciones($producto->id, $specs);
                }
            }

            $updated++;
            if ($estado === 'SUSPENDIDA') {
                $suspended++;
            } else {
                $ofertados++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // — Suspender productos de modelos PC sin codigo_pc (los "viejos") —
        $suspendidosViejos = 0;
        if ($suspenderSinFicha) {
            $suspendidosViejos = $this->suspenderProductosSinFicha($dryRun);
        }

        $this->newLine();
        $this->table(
            ['Resultado', 'Cantidad'],
            [
                ['Actualizados (match)',  $dryRun ? "(dry-run) serían {$updated}"          : $updated],
                ['Creados nuevos',        $dryRun ? "(dry-run) serían {$creados}"           : $creados],
                ['Sin match en BD',       $noMatch - $creados],
                ['SUSPENDIDOS (ficha)',   $suspended],
                ['OFERTADOS (ficha)',     $ofertados],
                ['Suspendidos sin ficha', $dryRun ? "(dry-run) serían {$suspendidosViejos}" : $suspendidosViejos],
            ]
        );

        if ($noMatch > $creados) {
            $this->newLine();
            $this->warn(($noMatch - $creados) . " fichas sin match. Usa --crear para crearlos automáticamente.");
        }

        if ($dryRun) {
            $this->warn("Modo dry-run: no se escribió nada en la BD.");
        } else {
            $this->info("Sincronización completada.");
            Log::info('sync:fichas', compact('updated', 'creados', 'noMatch', 'suspended', 'suspendidosViejos'));
        }

        return self::SUCCESS;
    }

    // ─── Buscar producto (3 estrategias) ─────────────────────────────────────

    /**
     * Busca el producto en la BD por:
     *  1. codigo_pc  (columna dedicada al código Peru Compras)
     *  2. nro_parte  (por si el admin ingresó el código manualmente)
     *  3. nombre que contiene "(CODIGO)" - formato admin legacy
     */
    private function findProducto(string $codigo): ?object
    {
        // 1. Columna codigo_pc (principal)
        $p = DB::table('productos')
            ->whereRaw('UPPER(codigo_pc) = ?', [$codigo])
            ->first();
        if ($p) return $p;

        // 2. nro_parte (el admin puede haberlo ingresado)
        $p = DB::table('productos')
            ->whereRaw('UPPER(nro_parte) = ?', [$codigo])
            ->first();
        if ($p) return $p;

        // 3. nombre contiene "(CODIGO)" — legacy
        $p = DB::table('productos')
            ->whereRaw("UPPER(nombre) LIKE ?", ['%(' . $codigo . ')%'])
            ->first();

        return $p ?: null;
    }

    // ─── Crear producto desde ficha ───────────────────────────────────────────

    private function crearProducto(string $codigo, array $ficha, bool $dryRun): bool
    {
        $estado    = strtoupper($ficha['estado'] ?? 'OFERTADA');
        $apiModel  = $ficha['modelo_api']       ?? '';
        $apiCateg  = $ficha['categoria_api']    ?? '';
        $imgUrl    = $ficha['imagen']            ?? null;
        $pdfUrl    = $ficha['ficha_tecnica_url'] ?? null;
        $specs     = $ficha['specs']             ?? [];

        // Resolver modelo_id (obligatorio - NOT NULL en la tabla)
        $modelGroup = $this->extractModelGroup($apiModel);
        $modeloId   = $this->resolveModeloId($modelGroup);
        if (!$modeloId) {
            $this->line("  <comment>Skip {$codigo}: modelo '{$modelGroup}' no encontrado en BD</comment>");
            return false;
        }

        $categoriaId = $this->resolveCategoriaId($apiCateg);
        $nombre      = 'KENYA ' . strtoupper($apiModel) . ' (' . $codigo . ')';

        $data = [
            'nombre'        => $nombre,
            'nro_parte'     => $codigo,
            'codigo_pc'     => $codigo,
            'vigencia'      => $estado,
            'ficha_sync_at' => now(),
            'pagina_web'    => 'SI',     // visible de inmediato (el admin puede cambiar)
            'modelo_id'     => $modeloId,
            'categoria_id'  => $categoriaId,
            'imagen_1'      => $imgUrl,
            'ficha_tecnica' => $pdfUrl,
            'created_at'    => now(),
            'updated_at'    => now(),
        ];

        if (!empty($specs)) {
            $this->mergeSpecs($data, $specs);
        }

        if (!$dryRun) {
            $newId = DB::table('productos')->insertGetId($data);
            $this->syncEspecificaciones($newId, $specs);
        }

        $tag = $dryRun ? '[dry-run] Crearía' : 'Creado';
        $this->line("  <info>{$tag}: {$nombre}</info>");
        return true;
    }

    // ─── Sincronizar tabla especificaciones ────────────────────────────────────

    /**
     * Borra las especificaciones existentes del producto y las recrea
     * a partir del array $specs (columna → valor).
     */
    private function syncEspecificaciones(int $productoId, array $specs): void
    {
        DB::table('especificaciones')->where('producto_id', $productoId)->delete();

        $rows = [];
        $now  = now();
        foreach (self::SPEC_LABELS as $col => $label) {
            if (!empty($specs[$col])) {
                $rows[] = [
                    'campo'       => $label,
                    'descripcion' => $specs[$col],
                    'producto_id' => $productoId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        if (!empty($rows)) {
            DB::table('especificaciones')->insert($rows);
        }
    }

    // ─── Suspender productos de modelos PC sin codigo_pc ─────────────────────

    /**
     * Marca SUSPENDIDA los productos que:
     *  - Pertenecen a un modelo presente en Peru Compras (EZENT, PROWORK, etc.)
     *  - No tienen codigo_pc (es decir, no fueron importados del API)
     * Estos son los productos "viejos" que han sido reemplazados por los nuevos.
     */
    private function suspenderProductosSinFicha(bool $dryRun): int
    {
        // IDs de modelos que tienen fichas en Peru Compras
        $modeloIds = DB::table('modelos')
            ->where(function ($q) {
                foreach (self::PC_MODEL_GROUPS as $g) {
                    $q->orWhereRaw("UPPER(descripcion) LIKE ?", [$g . '%']);
                }
            })
            ->pluck('id');

        if ($modeloIds->isEmpty()) {
            return 0;
        }

        $query = DB::table('productos')
            ->whereIn('modelo_id', $modeloIds)
            ->whereNull('codigo_pc')
            ->where(function ($q) {
                $q->whereNull('vigencia')->orWhere('vigencia', '!=', 'SUSPENDIDA');
            });

        $count = $query->count();

        if ($count > 0) {
            if ($dryRun) {
                $this->warn("[dry-run] Marcaría {$count} productos sin ficha como SUSPENDIDA.");
            } else {
                DB::table('productos')
                    ->whereIn('modelo_id', $modeloIds)
                    ->whereNull('codigo_pc')
                    ->update(['vigencia' => 'SUSPENDIDA', 'updated_at' => now()]);
                $this->warn("Marcados {$count} productos sin codigo_pc como SUSPENDIDA.");
            }
        }

        return $count;
    }

    // ─── Helpers specs ────────────────────────────────────────────────────────

    private function mergeSpecs(array &$data, array $specs): void
    {
        $fieldMap = [
            'procesador'          => 'procesador',
            'ram'                 => 'ram',
            'almacenamiento'      => 'almacenamiento',
            'conectividad'        => 'conectividad',
            'conectividad_wlan'   => 'conectividad_wlan',
            'conectividad_usb'    => 'conectividad_usb',
            'video_vga'           => 'video_vga',
            'video_hdmi'          => 'video_hdmi',
            'sistema_operativo'   => 'sistema_operativo',
            'unidad_optica'       => 'unidad_optica',
            'teclado'             => 'teclado',
            'mouse'               => 'mouse',
            'suite_ofimatica'     => 'suite_ofimatica',
            'garantia_de_fabrica' => 'garantia_de_fabrica',
        ];

        $boolCols = [
            'conectividad', 'conectividad_wlan', 'conectividad_usb',
            'video_vga', 'video_hdmi', 'unidad_optica', 'teclado', 'mouse',
        ];

        foreach ($fieldMap as $specKey => $dbCol) {
            if (!empty($specs[$specKey])) {
                $val = trim($specs[$specKey]);
                if (in_array($dbCol, $boolCols, true)) {
                    $val = in_array(strtoupper($val), ['SI', 'SÍ', 'YES', '1', 'TRUE']) ? 'SI' : 'NO';
                }
                $data[$dbCol] = $val;
            }
        }
    }

    // ─── Helpers modelo / categoría ──────────────────────────────────────────

    /** Extrae el grupo de modelo: "EZENT V1_MT" → "EZENT", "PROWORK WS90" → "PROWORK" */
    private function extractModelGroup(string $apiModel): string
    {
        $upper = strtoupper(trim($apiModel));
        foreach (self::PC_MODEL_GROUPS as $group) {
            if (str_starts_with($upper, $group)) {
                return $group;
            }
        }
        return strtok($upper, ' ') ?: $upper;
    }

    private function resolveModeloId(string $group): ?int
    {
        if (array_key_exists($group, $this->modelosCache)) {
            return $this->modelosCache[$group];
        }
        $row = DB::table('modelos')
            ->whereRaw("UPPER(descripcion) LIKE ?", [$group . '%'])
            ->first();
        $id = $row ? (int) $row->id : null;
        $this->modelosCache[$group] = $id;
        return $id;
    }

    private function resolveCategoriaId(string $apiCateg): ?int
    {
        $key = strtoupper(trim($apiCateg));
        if (array_key_exists($key, $this->categoriasCache)) {
            return $this->categoriasCache[$key];
        }

        $patterns = [
            'COMPUTADORA DE ESCRITORIO' => '%ESCRITORIO%',
            'ESTACION DE TRABAJO'       => '%TRABAJO%',
            'LAPTOP'                    => '%LAPTOP%',
            'COMPUTADORA PORTATIL'      => '%PORTATIL%',
            'SCANNER'                   => '%SCAN%',
        ];
        $pattern = $patterns[$key] ?? '%' . $key . '%';

        // Intentar en tabla 'categorias' (solo columna nombre)
        $row = DB::table('categorias')
            ->whereRaw("UPPER(nombre) LIKE ?", [$pattern])
            ->first();

        $id = $row ? (int) $row->id : null;
        $this->categoriasCache[$key] = $id;
        return $id;
    }

    // ─── Fuente: APIs del auditor (catálogo + video-specs fusionados) ──────────

    private function fetchFromApi(): array
    {
        $this->info("Obteniendo fichas del auditor...");

        // 1. Video-specs: nro_parte, graficos, ficha_tecnica_url, modelo (descripción), categoria
        $videoMap = [];
        try {
            $resp = Http::timeout(60)->get(self::API_BASE . '/fichas/video-specs');
            if ($resp->successful()) {
                foreach ($resp->json()['items'] ?? [] as $item) {
                    $code = strtoupper($item['nro_parte'] ?? '');
                    if ($code) {
                        $videoMap[$code] = $item;
                    }
                }
                $this->info("Video-specs: " . count($videoMap) . " fichas");
            } else {
                $this->warn("video-specs respondió " . $resp->status());
            }
        } catch (\Exception $e) {
            $this->warn("No se pudo obtener video-specs: " . $e->getMessage());
        }

        // 2. Catálogo: nro_parte, estado, imagen_url, modelo (descripción), categoria
        $catalogItems = [];
        try {
            $resp = Http::timeout(60)->get(self::API_BASE . '/fichas/catalog', [
                'marca' => 'KENYA TECHNOLOGY',
                'limit' => 2000,
            ]);
            if ($resp->successful()) {
                $catalogItems = $resp->json()['items'] ?? [];
                $this->info("Catálogo: " . count($catalogItems) . " fichas");
            } else {
                $this->warn("catalog respondió " . $resp->status());
            }
        } catch (\Exception $e) {
            $this->warn("No se pudo obtener catálogo: " . $e->getMessage());
        }

        // Fallback: si catalog falla usar video-specs como base
        if (empty($catalogItems) && !empty($videoMap)) {
            $catalogItems = array_values($videoMap);
        }

        if (empty($catalogItems)) {
            $this->error("No se obtuvieron fichas de ningún endpoint.");
            return [];
        }

        $all = [];
        foreach ($catalogItems as $item) {
            $codigo = strtoupper($item['nro_parte'] ?? '');
            if (!$codigo) continue;

            // 'modelo' en ambos endpoints contiene la descripción completa del producto
            $descripcion = $item['modelo'] ?? '';
            $estado      = strtoupper($item['estado'] ?? 'OFERTADA');
            $categoria   = strtoupper($item['categoria'] ?? '');

            // Parsear specs desde la descripción (PCs y productos sin specs directas)
            $specs = $this->parseDescription($descripcion);

            // Para monitores (y cualquier producto), el API ya devuelve specs pre-computadas.
            // Tienen prioridad sobre el parser de texto — se fusionan encima.
            $apiSpecs = $item['specs'] ?? [];
            if (!empty($apiSpecs)) {
                $specs = array_merge($specs, $apiSpecs);
            }

            // Fusionar graficos desde video-specs (solo aplica a PCs)
            $fichaUrl = $item['ficha_tecnica_url'] ?? null;
            if (isset($videoMap[$codigo])) {
                $graficosRaw = $videoMap[$codigo]['graficos'] ?? null;
                if ($graficosRaw && $categoria !== 'MONITOR') {
                    $specs['graficos'] = $this->fixEncoding($graficosRaw);
                }
                // Preferir URL de ficha desde video-specs si está disponible
                $fichaUrl = $videoMap[$codigo]['ficha_tecnica_url'] ?? $fichaUrl;
            }

            $all[] = [
                'codigo_ficha'      => $codigo,
                'estado'            => $estado,
                'modelo_api'        => $this->extractModelFromDesc($descripcion),
                'categoria_api'     => strtoupper($item['categoria'] ?? ''),
                'imagen'            => $item['imagen_url'] ?? null,
                'ficha_tecnica_url' => $fichaUrl,
                'specs'             => $specs,
            ];
        }

        $this->info("Total fichas fusionadas: " . count($all));
        return $all;
    }

    /**
     * Extrae el nombre del modelo desde el final de la descripción.
     * Formato: "... UNIDAD KENYA TECHNOLOGY MODELO NRO_PARTE SIST. MANEJO RAEE ..."
     * El último token antes de SIST. MANEJO RAEE es el nro_parte; todo lo anterior = modelo.
     */
    private function extractModelFromDesc(string $desc): string
    {
        if (preg_match('/UNIDAD KENYA TECHNOLOGY\s+(.+?)\s+SIST\.?\s*MANEJO/i', $desc, $m)) {
            $parts = preg_split('/\s+/', trim($m[1]));
            array_pop($parts); // Quitar último token (= nro_parte)
            return implode(' ', $parts);
        }
        return '';
    }

    // ─── Fuente: JSON local ───────────────────────────────────────────────────

    private function loadFromFile(string $file): array
    {
        $path = base_path($file);
        if (!file_exists($path)) {
            $this->error("Archivo no encontrado: {$path}");
            return [];
        }
        $raw = json_decode(file_get_contents($path), true) ?? [];
        return array_map(fn($f) => [
            'codigo_ficha'      => strtoupper($f['codigo_ficha'] ?? ''),
            'estado'            => strtoupper($f['estado'] ?? 'OFERTADA'),
            'modelo_api'        => $f['modelo_api'] ?? '',
            'categoria_api'     => strtoupper($f['categoria_api'] ?? ''),
            'imagen'            => $f['imagen'] ?? null,
            'ficha_tecnica_url' => $f['ficha_tecnica_url'] ?? null,
            'specs'             => $f['specs'] ?? [],
        ], $raw);
    }

    // ─── Encoding helper ─────────────────────────────────────────────────────

    /**
     * Corrige strings con encoding incorrecto provenientes del API:
     *  - Bytes Latin-1 crudos (0xAE para ®) → convierte desde ISO-8859-1 a UTF-8
     *  - Doble-codificación UTF-8 ("Â®" en lugar de "®") → deshace la capa extra
     */
    private function fixEncoding(?string $str): ?string
    {
        if (!$str) return null;

        if (!mb_check_encoding($str, 'UTF-8')) {
            // Contiene bytes no-UTF8 (ej. 0xAE solo) → interpretar como Latin-1
            return mb_convert_encoding($str, 'UTF-8', 'ISO-8859-1');
        }

        // Válido UTF-8: intentar deshacer doble-codificación
        // ("Â®" = U+00C2 U+00AE → ISO-8859-1 bytes C2 AE → UTF-8 válido para ®)
        $decoded = mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
        if (mb_check_encoding($decoded, 'UTF-8')) {
            return $decoded;
        }

        return $str; // no era doble-codificado, devolver original
    }

    // ─── Parser de descripción ────────────────────────────────────────────────
    // Formato: "TIPO: PROCESADOR: X RAM: Y ... UNIDAD KENYA TECHNOLOGY MODELO NRO SIST. MANEJO RAEE"

    private function parseDescription(string $text): array
    {
        $specs = [];
        $upper = strtoupper($text);

        $positions = [];
        foreach (array_keys(self::SPEC_TOKENS) as $token) {
            $pos = strpos($upper, $token);
            if ($pos !== false) {
                $positions[$token] = $pos;
            }
        }
        asort($positions);

        $tokenList  = array_keys($positions);
        $tokenCount = count($tokenList);

        for ($i = 0; $i < $tokenCount; $i++) {
            $token = $tokenList[$i];
            $col   = self::SPEC_TOKENS[$token];
            $start = $positions[$token] + strlen($token);

            if ($i + 1 < $tokenCount) {
                $raw = substr($text, $start, $positions[$tokenList[$i + 1]] - $start);
            } else {
                // Último token: cortar antes de "UNIDAD KENYA TECHNOLOGY" o "SIST. MANEJO RAEE"
                $end = strlen($text);
                foreach (['UNIDAD KENYA TECHNOLOGY', 'SIST. MANEJO RAEE'] as $marker) {
                    $p = stripos($text, $marker, $start);
                    if ($p !== false && $p < $end) {
                        $end = $p;
                    }
                }
                $raw = substr($text, $start, $end - $start);
            }

            $val = trim($raw);
            if ($val !== '' && !isset($specs[$col])) {
                $specs[$col] = $val;
            }
        }

        return $specs;
    }
}
