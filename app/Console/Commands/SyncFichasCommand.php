<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser as PdfParser;

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
        'TIPO DE SUMINISTRO:'            => 'tipo_suministro',
        'DESCRIPCION:'                   => 'descripcion_toner',
        'DESCRIPCIÓN:'                   => 'descripcion_toner',
        'MODELO:'                        => 'modelo_toner',
        'COLOR:'                         => 'color_toner',
        'RENDIMIENTO:'                   => 'rendimiento',
        'SIST. MANEJO RAEE:'             => 'sistema_raee',
        'SISTEMA DE MANEJO DE RAEE:'     => 'sistema_raee',
        'CERTIFICACIONES:'               => 'certificaciones',
        'EMPAQUE:'                       => 'empaque',
        'DIMENSIONES:'                   => 'dimensiones',
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

    // Tokens encontrados en ficha técnica PDF → clave de especificación
    // Se usan para completar campos que no llegan en descripción/API.
    const PDF_SPEC_TOKENS = [
        // Variante sin ':' porque muchos PDF extraídos no respetan delimitadores
        'TIPO DE SUMINISTRO'             => 'tipo_suministro',
        'DESCRIPCION'                    => 'descripcion_toner',
        'DESCRIPCIÓN'                    => 'descripcion_toner',
        'MODELO'                         => 'modelo_toner',
        'COLOR'                          => 'color_toner',
        'RENDIMIENTO'                    => 'rendimiento',
        'SISTEMA DE MANEJO DE RAEE'      => 'sistema_raee',
        'SIST. MANEJO RAEE'              => 'sistema_raee',
        'UNIDAD CAJA'                    => 'unidad',
        'NUMERO DE PARTE'                => 'numero_parte_ref',
        'NÚMERO DE PARTE'                => 'numero_parte_ref',
        'N° DE PARTE'                    => 'numero_parte_ref',
        'Nº DE PARTE'                    => 'numero_parte_ref',
        'DIMENSIONES'                    => 'dimensiones',
        'FORMATO'                        => 'formato',
        'PROCESADOR'                     => 'procesador',
        'MEMORIA RAM'                    => 'ram',
        'RAM'                            => 'ram',
        'ALMACENAMIENTO'                 => 'almacenamiento',
        'GRAFICOS'                       => 'graficos',
        'GRÁFICOS'                       => 'graficos',
        'TARJETA GRAFICA'                => 'graficos',
        'TARJETA GRÁFICA'                => 'graficos',
        'SISTEMA OPERATIVO'              => 'sistema_operativo',
        'SUITE OFIMATICA PRE-INSTALADA'  => 'suite_ofimatica',
        'SUITE OFIMATICA'                => 'suite_ofimatica',
        'SUITE OFIMÁTICA'                => 'suite_ofimatica',
        'SONIDO'                         => 'sonido',
        'CHIPSET'                        => 'chipset',
        'LAN'                            => 'conectividad',
        'WLAN'                           => 'conectividad_wlan',
        'PUERTOS MINIMOS'                => 'puertos_minimos',
        'PUERTOS MÍNIMOS'                => 'puertos_minimos',
        'SLOT DE EXPANSION'              => 'slot_expansion',
        'SLOT DE EXPANSIÓN'              => 'slot_expansion',
        'FUENTE DE PODER'                => 'fuente_poder',
        'GARANTIA DE FABRICA'            => 'garantia_de_fabrica',
        'GARANTÍA DE FÁBRICA'            => 'garantia_de_fabrica',
        'GARANTIA'                       => 'garantia_de_fabrica',
        'GARANTÍA'                       => 'garantia_de_fabrica',
        'EMPAQUE'                        => 'empaque',
        'CERTIFICACIONES'                => 'certificaciones',
        'ACCESORIOS Y OTROS'             => 'accesorios_otros',
        'ACCESORIOSY OTROS'              => 'accesorios_otros',
        'ACCESORIOS'                     => 'accesorios_otros',
        'OTROS'                          => 'accesorios_otros',
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
        'formato'             => 'Formato',
        'sonido'              => 'Sonido',
        'chipset'             => 'Chipset',
        'puertos_minimos'     => 'Puertos Mínimos',
        'slot_expansion'      => 'Slot de Expansión',
        'fuente_poder'        => 'Fuente de Poder',
        'empaque'             => 'Empaque',
        'certificaciones'     => 'Certificaciones',
        'accesorios_otros'    => 'Accesorios y Otros',
        // ── Tóner ───────────────────────────────────────────────────────────
        'tipo_suministro'     => 'Tipo de suministro',
        'modelo_toner'        => 'Modelo',
        'color_toner'         => 'Color',
        'descripcion_toner'   => 'Descripción',
        'rendimiento'         => 'Rendimiento',
        'sistema_raee'        => 'Sistema RAEE',
        'unidad'              => 'Unidad',
        'numero_parte_ref'    => 'Número de parte',
        'dimensiones'         => 'Dimensiones',
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
    /** Parser de PDF (instancia única) */
    private ?PdfParser $pdfParser = null;
    /** Caché URL PDF → specs extraídas */
    private array $pdfSpecsCache = [];

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
        $pdfEnriched = 0;

        $bar = $this->output->createProgressBar($totalUnique);
        $bar->start();

        foreach ($deduped as $codigo => $ficha) {
            $estado = strtoupper($ficha['estado'] ?? 'OFERTADA');
            $specs  = $ficha['specs'] ?? [];
            $categoriaApi = strtoupper($ficha['categoria_api'] ?? '');
            $pdfUrl = $ficha['ficha_tecnica_url'] ?? null;

            if (!$soloVig) {
                $specsBefore = $specs;
                $specs = $this->enrichSpecsFromPdfIfNeeded($specs, $pdfUrl, $categoriaApi);
                $ficha['specs'] = $specs; // aplica también para --crear
                if ($specs !== $specsBefore) {
                    $pdfEnriched++;
                }
            }

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
                ['Completados desde PDF', $pdfEnriched],
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
            Log::info('sync:fichas', compact('updated', 'creados', 'noMatch', 'suspended', 'pdfEnriched', 'suspendidosViejos'));
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
            'graficos'            => 'tarjetavideo',
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

    private function enrichSpecsFromPdfIfNeeded(array $specs, ?string $pdfUrl, string $categoria): array
    {
        if (strtoupper($categoria) === 'MONITOR' || empty($pdfUrl)) {
            return $specs;
        }

        if (!$this->shouldExtractPdfSpecs($specs, $categoria)) {
            return $specs;
        }

        $fromPdf = $this->extractSpecsFromPdfUrl($pdfUrl);
        if (empty($fromPdf)) {
            return $specs;
        }

        $allowed = array_flip($this->allowedSpecKeysByCategory($categoria));

        foreach ($fromPdf as $key => $value) {
            if (!isset($allowed[$key])) {
                continue;
            }
            if (empty($specs[$key]) && !empty($value)) {
                $specs[$key] = $value;
            }
        }

        return $specs;
    }

    private function shouldExtractPdfSpecs(array $specs, string $categoria): bool
    {
        $wanted = $this->allowedSpecKeysByCategory($categoria);

        foreach ($wanted as $key) {
            $val = strtoupper(trim((string) ($specs[$key] ?? '')));
            if ($val === '' || $val === 'N/A' || $val === '-') {
                return true;
            }
        }

        return false;
    }

    private function allowedSpecKeysByCategory(string $categoria): array
    {
        $cat = strtoupper(trim($categoria));

        if ($cat === 'TONER') {
            return [
                'tipo_suministro', 'modelo_toner', 'color_toner', 'descripcion_toner',
                'rendimiento', 'garantia_de_fabrica', 'sistema_raee', 'certificaciones',
                'empaque', 'unidad', 'numero_parte_ref', 'dimensiones',
            ];
        }

        return [
            'graficos', 'sistema_operativo', 'suite_ofimatica',
            'formato', 'sonido', 'chipset', 'puertos_minimos',
            'slot_expansion', 'fuente_poder', 'empaque',
            'certificaciones', 'accesorios_otros',
        ];
    }

    private function extractSpecsFromPdfUrl(string $pdfUrl): array
    {
        $url = trim($pdfUrl);
        if ($url === '') {
            return [];
        }

        if (array_key_exists($url, $this->pdfSpecsCache)) {
            return $this->pdfSpecsCache[$url];
        }

        if (!preg_match('/^https?:\/\//i', $url)) {
            $this->pdfSpecsCache[$url] = [];
            return [];
        }

        try {
            $resp = Http::timeout(30)->retry(1, 250)->get($url);
            if (!$resp->successful()) {
                $this->pdfSpecsCache[$url] = [];
                return [];
            }

            $contentType = strtolower((string) $resp->header('Content-Type', ''));
            if ($contentType !== '' && str_contains($contentType, 'html') && !str_contains($contentType, 'pdf')) {
                $this->pdfSpecsCache[$url] = [];
                return [];
            }

            $specs = $this->parsePdfBinaryToSpecs((string) $resp->body());
            $this->pdfSpecsCache[$url] = $specs;
            return $specs;
        } catch (\Throwable $e) {
            $this->pdfSpecsCache[$url] = [];
            return [];
        }
    }

    private function parsePdfBinaryToSpecs(string $pdfBinary): array
    {
        if ($pdfBinary === '') {
            return [];
        }

        try {
            $text = $this->getPdfParser()->parseContent($pdfBinary)->getText();
        } catch (\Throwable $e) {
            return [];
        }

        if (!$text) {
            return [];
        }

        return $this->parseTokenizedText(
            $text,
            self::PDF_SPEC_TOKENS,
            ['UNIDAD KENYA TECHNOLOGY', 'SIST. MANEJO RAEE', 'WWW.', 'HTTP://', 'HTTPS://']
        );
    }

    private function getPdfParser(): PdfParser
    {
        if ($this->pdfParser === null) {
            $this->pdfParser = new PdfParser();
        }

        return $this->pdfParser;
    }

    private function sanitizeSpecValue(string $value): ?string
    {
        $fixed = $this->fixEncoding($value) ?? '';
        $fixed = preg_replace('/\s+/u', ' ', trim($fixed));
        // Quitar marcadores de nota al pie típicos de fichas (¹ ² ³)
        $fixed = preg_replace('/^[\x{00B9}\x{00B2}\x{00B3}]+\s*/u', '', $fixed);
        $fixed = trim((string) $fixed, ":;,. ");

        $upper = strtoupper((string) $fixed);
        if ($fixed === '' || $upper === 'N/A' || $upper === '-') {
            return null;
        }

        return $fixed;
    }

    private function parseTokenizedText(string $text, array $tokenMap, array $endMarkers = []): array
    {
        $specs = [];
        $upper = mb_strtoupper($text, 'UTF-8');

        $positions = [];
        foreach (array_keys($tokenMap) as $token) {
            $pattern = '/(?<![A-ZÁÉÍÓÚÑ])' . preg_quote($token, '/') . '(?![A-ZÁÉÍÓÚÑ])/u';
            if (preg_match($pattern, $upper, $m, PREG_OFFSET_CAPTURE)) {
                $positions[$token] = $m[0][1];
            }
        }

        asort($positions);
        $tokenList  = array_keys($positions);
        $tokenCount = count($tokenList);

        for ($i = 0; $i < $tokenCount; $i++) {
            $token = $tokenList[$i];
            $specKey = $tokenMap[$token];
            $start = $positions[$token] + strlen($token);

            if ($i + 1 < $tokenCount) {
                $end = $positions[$tokenList[$i + 1]];
            } else {
                $end = strlen($text);
                foreach ($endMarkers as $marker) {
                    $markerPos = stripos($text, $marker, $start);
                    if ($markerPos !== false && $markerPos < $end) {
                        $end = $markerPos;
                    }
                }
            }

            $raw = substr($text, $start, max(0, $end - $start));
            $value = $this->sanitizeSpecValue($raw);

            if ($value !== null && !isset($specs[$specKey])) {
                $specs[$specKey] = $value;
            }
        }

        return $specs;
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
            'TONER'                     => '%TONER%',
            'TONNER'                    => '%TONER%',
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

        // 2b. Catálogo de tóneres KENYA (fuera del filtro de marca principal)
        $tonerItems = $this->fetchTonerCatalogItems();
        if (!empty($tonerItems)) {
            $catalogItems = array_merge($catalogItems, $tonerItems);
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

            if ($categoria === 'TONER') {
                $specs = $this->enrichTonerSpecsFromDescription($specs, $descripcion, $codigo);
            }

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
                if ($graficosRaw && !in_array($categoria, ['MONITOR', 'TONER'], true)) {
                    $specs['graficos'] = $this->fixEncoding($graficosRaw);
                }
                // Preferir URL de ficha desde video-specs si está disponible
                $fichaUrl = $videoMap[$codigo]['ficha_tecnica_url'] ?? $fichaUrl;
            }

            $all[] = [
                'codigo_ficha'      => $codigo,
                'estado'            => $estado,
                'modelo_api'        => $categoria === 'TONER' ? 'TONER' : $this->extractModelFromDesc($descripcion),
                'categoria_api'     => strtoupper($item['categoria'] ?? ''),
                'imagen'            => $item['imagen_url'] ?? null,
                'ficha_tecnica_url' => $fichaUrl,
                'specs'             => $specs,
            ];
        }

        $this->info("Total fichas fusionadas: " . count($all));
        return $all;
    }

    private function fetchTonerCatalogItems(): array
    {
        try {
            $resp = Http::timeout(60)->get(self::API_BASE . '/fichas/catalog', [
                'categoria' => 'TONER',
                'search'    => 'KENYA',
                'limit'     => 2000,
            ]);

            if ($resp->successful()) {
                $items = $resp->json()['items'] ?? [];
                if (!empty($items)) {
                    $this->info("Catálogo tóner: " . count($items) . " fichas");
                }
                return $items;
            }

            $this->warn("catalog tóner respondió " . $resp->status());
        } catch (\Exception $e) {
            $this->warn("No se pudo obtener catálogo tóner: " . $e->getMessage());
        }

        return [];
    }

    private function enrichTonerSpecsFromDescription(array $specs, string $descripcion, string $codigo): array
    {
        if (empty($specs['tipo_suministro'])) {
            $specs['tipo_suministro'] = 'Toner';
        }

        if (empty($specs['numero_parte_ref'])) {
            $specs['numero_parte_ref'] = $codigo;
        }

        if (empty($specs['color_toner'])) {
            if (preg_match('/\b(NEGRO|AMARILLO|MAGENTA|CIAN|CYAN)\b/ui', $descripcion, $m)) {
                $specs['color_toner'] = ucfirst(strtolower($m[1]));
            }
        }

        if (!empty($specs['sistema_raee'])) {
            $specs['sistema_raee'] = trim(preg_replace('/\b' . preg_quote($codigo, '/') . '\b/i', '', $specs['sistema_raee']));
        }

        if (!empty($specs['garantia_de_fabrica'])) {
            $specs['garantia_de_fabrica'] = trim((string) preg_replace(
                '/CAJA\s*X\s*\d+\s*UNIDAD(?:ES)?/iu',
                '',
                $specs['garantia_de_fabrica']
            ));
        }

        if (empty($specs['modelo_toner'])) {
            if (preg_match('/KENYA\s+([A-Z0-9\-\s]+?)\s+' . preg_quote($codigo, '/') . '$/iu', $descripcion, $m)) {
                $specs['modelo_toner'] = trim($m[1]);
            }
        }

        if (empty($specs['empaque'])) {
            if (preg_match('/CAJA\s*X\s*\d+\s*UNIDAD(?:ES)?/iu', $descripcion, $m)) {
                $specs['empaque'] = $m[0];
            }
        }

        if (empty($specs['unidad'])) {
            if (preg_match('/CAJA\s*X\s*(\d+\s*UNIDAD(?:ES)?)/iu', $descripcion, $m)) {
                $specs['unidad'] = $m[1];
            }
        }

        return $specs;
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
        return $this->parseTokenizedText(
            $text,
            self::SPEC_TOKENS,
            ['UNIDAD KENYA TECHNOLOGY', 'SIST. MANEJO RAEE']
        );
    }
}
