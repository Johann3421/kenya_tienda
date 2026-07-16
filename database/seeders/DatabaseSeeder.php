<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $inputFile = public_path('kenyacom_kenya (7).sql');

        if (!file_exists($inputFile)) {
            $this->command->info("No se encontró el archivo de respaldo para migrar.");
            return;
        }

        $this->command->info("Iniciando migración mágica de datos MySQL -> PostgreSQL...");

        DB::unprepared("SET session_replication_role = 'replica';");

        $lines    = file($inputFile);
        $total    = 0;
        $buffer   = '';
        $inInsert = false;
        $truncatedTables = [];

        foreach ($lines as $line) {
            if (strpos($line, 'INSERT INTO') === 0) {
                if (preg_match('/INSERT INTO `([^`]+)`/', $line, $m)) {
                    $t = $m[1];
                    if (!in_array($t, $truncatedTables)) {
                        try { DB::unprepared("TRUNCATE TABLE \"$t\" CASCADE;"); } catch (\Exception $e) {}
                        $truncatedTables[] = $t;
                    }
                }
                $inInsert = true;
                $buffer   = '';
            }

            if ($inInsert) {
                $buffer .= $line;

                if (substr(rtrim($line), -1) === ';') {
                    $inInsert = false;
                    $sql = $this->mysqlToPostgres($buffer);
                    try {
                        DB::unprepared($sql);
                        $total++;
                    } catch (\Exception $e) {
                        $this->command->warn("Fila omitida: " . substr($e->getMessage(), 0, 300));
                    }
                }
            }
        }

        DB::unprepared("SET session_replication_role = 'origin';");
        $this->command->info("Exito! Se insertaron {$total} bloques en PostgreSQL.");
    }

    private function mysqlToPostgres(string $sql): string
    {
        $bs = chr(92); // backslash literal
        $dq = chr(34); // double-quote literal
        $sq = chr(39); // single-quote literal

        // Paso 1: backticks → comillas dobles (identificadores Postgres)
        $sql = str_replace('`', $dq, $sql);

        // Paso 2: des-escapar secuencias MySQL. Orden crítico: secuencias largas primero.
        //
        // a) \\" (dos backslashes + comilla) es MySQL \\  (literal \) seguido de "
        //    → en Postgres queremos \" que en JSON es una comilla literal. Pero para
        //      columnas JSON tipadas de Postgres, la comilla doble debe ser literal "
        //      dentro del string SQL, así que simplificamos a solo ": \\" → "
        $sql = str_replace($bs.$bs.$dq, $dq, $sql);

        // b) \" (un backslash + comilla doble) → " (comilla literal en el string SQL)
        $sql = str_replace($bs.$dq, $dq, $sql);

        // c) \' (backslash + comilla simple) → '' (escape SQL para comilla simple)
        $sql = str_replace($bs.$sq, $sq.$sq, $sql);

        // d) \\ (dos backslashes) → \ (backslash literal, si quedó alguno)
        $sql = str_replace($bs.$bs, $bs, $sql);

        // Paso 3: remover outer double-quotes de JSON que MySQL genera en ciertos campos.
        // Después del paso 2 el patrón es: '"[{"k":"v"}]"' con ' de SQL alrededor.
        // El patrón incluye la ' de apertura y cierre para reemplazar exactamente.
        $sql = preg_replace("/{$sq}{$dq}(\\[.*?\\]){$dq}{$sq}/s", "{$sq}$1{$sq}", $sql);
        $sql = preg_replace("/{$sq}{$dq}(\\{.*?\\}){$dq}{$sq}/s", "{$sq}$1{$sq}", $sql);

        // Paso 4: secuencias de salto de línea
        $sql = str_replace($bs.'r'.$bs.'n', "\r\n", $sql);
        $sql = str_replace($bs.'n', "\n", $sql);

        return $sql;
    }
}
