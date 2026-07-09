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
            $this->line("CABECERAS COMPLETAS DEL EXCEL:");
            foreach ($values[0] as $i => $header) {
                $this->line("Columna [$i]: $header");
            }
            
            // Debug: Buscar 5 nro_parte de la BD a ver qué pasa
            $this->line("\nDiagnosticando NRO_PARTE de la BD vs Google Sheet...");
            $sampleDbParts = DB::table('productos')->whereNotNull('nro_parte')->where('nro_parte', '!=', '')->limit(5)->pluck('nro_parte')->toArray();
            $this->line("5 Nros de parte aleatorios en la BD: " . implode(', ', $sampleDbParts));
            
            $sheetParts = [];
            for($i = 1; $i <= 10; $i++) {
                if(isset($values[$i][4])) $sheetParts[] = $values[$i][4];
            }
            $this->line("10 Nros de parte en las primeras filas del Sheet: " . implode(', ', $sheetParts));

            return 0;
        }

        $this->info("Iniciando sincronización de precios...");

        // Suponemos que la fila 0 son cabeceras, iteramos desde 1
        $actualizados = 0;
        $noEncontrados = 0;

        // Tipo de cambio desde el mismo Excel (Fila 1, Columna 11 -> Indice [0][10])
        $tc = 3.75; // Default
        if (isset($values[0][10]) && is_numeric($values[0][10])) {
            $tc = (float) $values[0][10];
            $this->info("Usando Tipo de Cambio del Excel: " . $tc);
        }

        // Identificamos indices
        $idxNroParte = array_search('NRO_PARTE', $values[0]);
        $idxPrecio = array_search('PRECIO REFERENCIAL CLIENTE (USD SIN IGV)', $values[0]);

        if ($idxNroParte === false || $idxPrecio === false) {
            $this->error("No se encontraron las columnas necesarias (NRO_PARTE o PRECIO REFERENCIAL CLIENTE (USD SIN IGV)) en la fila de cabeceras.");
            return 1;
        }

        foreach ($values as $index => $row) {
            if ($index === 0) continue; // Saltar cabeceras

            $nroParte = $row[$idxNroParte] ?? null;
            $precioUsd = $row[$idxPrecio] ?? null;

            if (!$nroParte || !is_numeric($precioUsd)) {
                continue;
            }

            // Guardamos el precio convertido a soles
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
        $this->warn("Nro de parte no encontrados en BD: $noEncontrados (El Excel tiene productos que no están en tu BD)");

        return 0;
    }
}
