<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncFichasCommand extends Command
{
    protected $signature = 'sync:fichas
                            {--file=fichas_kenya.json : Ruta al JSON generado por parse_fichas.py}
                            {--dry-run : Solo muestra lo que haría, sin escribir en BD}
                            {--solo-vigencia : Solo actualiza vigencia, no las specs de producto}';

    protected $description = 'Importa las características de fichas Kenya (del auditor Peru Compras) a la tabla productos';

    public function handle(): int
    {
        $file    = $this->option('file');
        $dryRun  = $this->option('dry-run');
        $soloVig = $this->option('solo-vigencia');

        $path = base_path($file);
        if (!file_exists($path)) {
            $this->error("Archivo no encontrado: {$path}");
            $this->line("Ejecuta primero: python parse_fichas.py");
            return self::FAILURE;
        }

        $fichas = json_decode(file_get_contents($path), true);
        if (!$fichas) {
            $this->error("JSON inválido o vacío.");
            return self::FAILURE;
        }

        $this->info("Fichas cargadas del JSON: " . count($fichas));

        // Deduplicar por codigo_ficha: quedarse con el más reciente (último en la lista)
        $deduped = [];
        foreach ($fichas as $f) {
            $codigo = strtoupper($f['codigo_ficha'] ?? '');
            if ($codigo) {
                $deduped[$codigo] = $f;
            }
        }
        $this->info("Fichas únicas (por nro_parte): " . count($deduped));

        $updated   = 0;
        $skipped   = 0;
        $noMatch   = 0;
        $suspended = 0;

        $bar = $this->output->createProgressBar(count($deduped));
        $bar->start();

        foreach ($deduped as $codigo => $ficha) {
            $estado = strtoupper($ficha['estado'] ?? 'OFERTADA');
            $specs  = $ficha['specs']  ?? [];

            // Buscar producto por nro_parte (case-insensitive)
            $producto = DB::table('productos')
                ->whereRaw('UPPER(nro_parte) = ?', [$codigo])
                ->first();

            if (!$producto) {
                $noMatch++;
                $bar->advance();
                continue;
            }

            // Construir datos a actualizar
            $data = [
                'vigencia'      => $estado,            // OFERTADA | SUSPENDIDA
                'ficha_sync_at' => now(),
            ];

            if (!$soloVig && !empty($specs)) {
                $fieldMap = [
                    'procesador'        => 'procesador',
                    'ram'               => 'ram',
                    'almacenamiento'    => 'almacenamiento',
                    'conectividad'      => 'conectividad',       // LAN: SI/NO
                    'conectividad_wlan' => 'conectividad_wlan',  // WLAN: SI/NO
                    'conectividad_usb'  => 'conectividad_usb',   // USB: SI/NO
                    'video_vga'         => 'video_vga',
                    'video_hdmi'        => 'video_hdmi',
                    'sistema_operativo' => 'sistema_operativo',
                    'unidad_optica'     => 'unidad_optica',
                    'teclado'           => 'teclado',
                    'mouse'             => 'mouse',
                    'suite_ofimatica'   => 'suite_ofimatica',
                    'garantia_de_fabrica' => 'garantia_de_fabrica',
                    'pantalla'          => 'descripcion',        // para monitores
                ];

                foreach ($fieldMap as $specKey => $dbCol) {
                    if (!empty($specs[$specKey])) {
                        $data[$dbCol] = $this->normalizeValue($specs[$specKey], $dbCol);
                    }
                }
            }

            if ($dryRun) {
                if ($estado === 'SUSPENDIDA') {
                    $this->line("\n  [DRY] {$codigo} → SUSPENDIDA");
                    $suspended++;
                }
            } else {
                DB::table('productos')
                    ->where('id', $producto->id)
                    ->update($data);
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
                ['Actualizados',    $dryRun ? "0 (dry-run, serían {$updated})" : $updated],
                ['Sin match en BD', $noMatch],
                ['SUSPENDIDOS',     $suspended],
                ['OFERTADOS',       $updated - $suspended],
            ]
        );

        if ($dryRun) {
            $this->warn("Modo dry-run: no se escribió nada en la BD.");
        } else {
            $this->info("Sincronización completada.");
            Log::info("sync:fichas completado", compact('updated', 'noMatch', 'suspended'));
        }

        return self::SUCCESS;
    }

    /**
     * Normaliza valores al formato esperado por la tabla productos.
     * SI/NO → SI/NO (ya viene correcto desde el parser)
     */
    private function normalizeValue(string $value, string $col): string
    {
        $boolCols = [
            'conectividad', 'conectividad_wlan', 'conectividad_usb',
            'video_vga', 'video_hdmi', 'unidad_optica', 'teclado', 'mouse',
        ];

        $val = strtoupper(trim($value));

        if (in_array($col, $boolCols, true)) {
            return in_array($val, ['SI', 'SÍ', 'YES', '1', 'TRUE']) ? 'SI' : 'NO';
        }

        return trim($value);
    }
}
