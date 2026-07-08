<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class SyncPricesCommand extends Command
{
    protected $signature = 'kenya:sync-prices {--test : Solo prueba la lectura del archivo e imprime las primeras filas}';
    protected $description = 'Sincroniza los precios desde una Web App privada de Google Apps Script';

    public function handle()
    {
        // Esta URL te la dará Google Apps Script cuando publiques la Web App.
        $scriptUrl = env('GOOGLE_SCRIPT_URL', 'https://script.google.com/macros/s/AKfycbywCyOlDZxjAonCPJeavoIRe0GuPuY4n_EahViAnP-SjIPvoucQ5IDxOTnxqZWggzSW/exec');
        $token = env('GOOGLE_SCRIPT_TOKEN', 'KENYA_2026_SECRETO');

        if (empty($scriptUrl)) {
            $this->error("No has configurado GOOGLE_SCRIPT_URL en el archivo .env");
            return 1;
        }

        $this->info("Conectando a Google Apps Script...");

        try {
            $response = Http::timeout(30)->get($scriptUrl, [
                'token' => $token
            ]);

            if ($response->failed()) {
                $this->error("Error al conectar: " . $response->status() . " - " . $response->body());
                return 1;
            }

            $values = $response->json();

            if (!is_array($values) || empty($values)) {
                $this->warn("El Excel está vacío o el formato JSON es incorrecto.");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("Error de conexión: " . $e->getMessage());
            return 1;
        }

        $this->info("Se han leído " . count($values) . " filas correctamente desde el Excel.");

        if ($this->option('test')) {
            $this->line("MODO TEST: Mostrando las primeras 2 filas para entender la estructura.");
            $this->table(
                ['Columna A', 'Columna B', 'Columna C', 'Columna D', 'Columna E', 'Columna F'],
                [
                    array_slice($values[0] ?? [], 0, 6),
                    array_slice($values[1] ?? [], 0, 6)
                ]
            );
            return 0;
        }

        $this->info("Iniciando sincronización de precios...");

        // Suponemos que la fila 0 son cabeceras, iteramos desde 1
        $actualizados = 0;
        $noEncontrados = 0;
        $tc = env('TIPO_CAMBIO_USD', 3.75); // Tipo de cambio base si no existe

        foreach ($values as $index => $row) {
            if ($index === 0) continue; // Saltar cabeceras

            $nroParte = $row[4] ?? null;
            $precioUsd = $row[5] ?? null;

            if (!$nroParte || !is_numeric($precioUsd)) {
                continue;
            }

            // Convertir a soles + IGV si el catálogo muestra S/ con IGV
            // Ponytail shortcut: Asumimos cálculo fijo, luego el usuario lo ajusta si necesita.
            $precioSoles = round($precioUsd * $tc * 1.18, 2);

            $afectados = DB::table('productos')
                ->where('nro_parte', trim($nroParte))
                ->update(['precio_especial' => $precioSoles]);

            if ($afectados) {
                $actualizados++;
            } else {
                $noEncontrados++;
            }
        }

        $this->info("Sincronización completada.");
        $this->info("Productos actualizados: $actualizados");
        $this->warn("Nro de parte no encontrados en BD: $noEncontrados (Normal si hay productos descatalogados)");

        return 0;
    }
}
