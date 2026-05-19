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
                            {--file= : Usar JSON local en vez de llamar a la API}';

    protected $description = 'Sincroniza características y vigencia de productos Kenya desde el auditor Peru Compras';

    // URL de la API del auditor
    const API_BASE  = 'https://api-auditor.sekaitech.com.pe/api/v1';
    const PAGE_SIZE = 50;

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

    public function handle(): int
    {
        $dryRun  = $this->option('dry-run');
        $soloVig = $this->option('solo-vigencia');
        $file    = $this->option('file');

        if ($file) {
            $fichas = $this->loadFromFile($file);
        } else {
            $fichas = $this->fetchFromApi();
        }

        if (empty($fichas)) {
            $this->error("No se obtuvieron fichas.");
            return self::FAILURE;
        }

        $this->info("Fichas obtenidas: " . count($fichas));

        // Deduplicar por nro_parte (el mismo código puede aparecer en múltiples acuerdos marco)
        $deduped = [];
        foreach ($fichas as $f) {
            $codigo = strtoupper($f['codigo_ficha'] ?? '');
            if ($codigo) {
                $deduped[$codigo] = $f;
            }
        }
        $this->info("Fichas únicas por nro_parte: " . count($deduped));

        $updated   = 0;
        $noMatch   = 0;
        $suspended = 0;

        $bar = $this->output->createProgressBar(count($deduped));
        $bar->start();

        foreach ($deduped as $codigo => $ficha) {
            $estado = strtoupper($ficha['estado'] ?? 'OFERTADA');
            $specs  = $ficha['specs']  ?? [];

            $producto = DB::table('productos')
                ->whereRaw('UPPER(nro_parte) = ?', [$codigo])
                ->first();

            if (!$producto) {
                $noMatch++;
                $bar->advance();
                continue;
            }

            $data = [
                'vigencia'      => $estado,
                'ficha_sync_at' => now(),
            ];

            if (!$soloVig && !empty($specs)) {
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

            if (!$dryRun) {
                DB::table('productos')->where('id', $producto->id)->update($data);
            }

            $updated++;
            if ($estado === 'SUSPENDIDA') {
                $suspended++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Resultado', 'Cantidad'],
            [
                ['Actualizados',    $dryRun ? "(dry-run) serían {$updated}" : $updated],
                ['Sin match en BD', $noMatch],
                ['SUSPENDIDOS',     $suspended],
                ['OFERTADOS',       $updated - $suspended],
            ]
        );

        if ($dryRun) {
            $this->warn("Modo dry-run: no se escribió nada en la BD.");
        } else {
            $this->info("Sincronización completada.");
            Log::info('sync:fichas', compact('updated', 'noMatch', 'suspended'));
        }

        return self::SUCCESS;
    }

    // ─── Fuente: API REST del auditor ─────────────────────────────────────────

    private function fetchFromApi(): array
    {
        $all   = [];
        $page  = 1;

        $this->info("Conectando a la API del auditor...");

        while (true) {
            $this->line("  Página {$page}...", null, 'v');

            try {
                $resp = Http::timeout(20)->get(self::API_BASE . '/fichas/', [
                    'marca'     => 'Kenya',
                    'page'      => $page,
                    'page_size' => self::PAGE_SIZE,
                ]);
            } catch (\Exception $e) {
                $this->error("Error al llamar a la API: " . $e->getMessage());
                break;
            }

            if (!$resp->successful()) {
                $this->error("API respondió {$resp->status()}");
                break;
            }

            $data  = $resp->json();
            $items = $data['items'] ?? [];
            $total = $data['total'] ?? 0;

            if (empty($items)) {
                break;
            }

            foreach ($items as $item) {
                $codigo = strtoupper($item['nro_parte_o_cdigo_nico_de_identificacin'] ?? '');
                if (!$codigo) continue;

                $descripcion = $item['descripcin_fichaproducto'] ?? '';
                $estado      = strtoupper($item['estado_ficha_producto'] ?? 'OFERTADA');

                $all[] = [
                    'codigo_ficha' => $codigo,
                    'estado'       => $estado,
                    'specs'        => $this->parseDescription($descripcion),
                ];
            }

            if (count($all) >= $total) {
                break;
            }

            $page++;
        }

        $this->info("Total fichas desde API: " . count($all));
        return $all;
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
        // Normaliza el formato del JSON (generado por parse_fichas.py)
        return array_map(fn($f) => [
            'codigo_ficha' => strtoupper($f['codigo_ficha'] ?? ''),
            'estado'       => strtoupper($f['estado'] ?? 'OFERTADA'),
            'specs'        => $f['specs'] ?? [],
        ], $raw);
    }

    // ─── Parser de descripción ────────────────────────────────────────────────
    // Formato: "TIPO : PROCESADOR: X RAM: Y ... UNIDAD KENYA TECHNOLOGY MODELO NRO SIST. MANEJO RAEE: COLECTIVO"

    private function parseDescription(string $text): array
    {
        $specs = [];
        $upper = strtoupper($text);

        // Construir lista de posiciones de cada token para delimitar valores
        $positions = [];
        foreach (array_keys(self::SPEC_TOKENS) as $token) {
            $pos = strpos($upper, $token);
            if ($pos !== false) {
                $positions[$token] = $pos;
            }
        }
        // Ordenar por posición
        asort($positions);

        $tokenList   = array_keys($positions);
        $tokenCount  = count($tokenList);

        for ($i = 0; $i < $tokenCount; $i++) {
            $token    = $tokenList[$i];
            $col      = self::SPEC_TOKENS[$token];
            $start    = $positions[$token] + strlen($token);

            // El valor va hasta el siguiente token conocido
            if ($i + 1 < $tokenCount) {
                $nextPos = $positions[$tokenList[$i + 1]];
                $raw = substr($text, $start, $nextPos - $start);
            } else {
                // Último token: hasta "UNIDAD KENYA TECHNOLOGY" o fin
                $endMarkers = ['UNIDAD KENYA TECHNOLOGY', 'SIST. MANEJO RAEE'];
                $end = strlen($text);
                foreach ($endMarkers as $marker) {
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

        // Extraer modelo del texto "UNIDAD KENYA TECHNOLOGY MODELO NRO_PARTE SIST. MANEJO"
        if (preg_match('/UNIDAD KENYA TECHNOLOGY\s+(.+?)\s+(\S+)\s+SIST\.?\s*MANEJO/i', $text, $m)) {
            $specs['modelo_ficha']    = trim($m[1]);
            $specs['nro_parte_ficha'] = strtoupper(trim($m[2]));
        }

        return $specs;
    }
}

