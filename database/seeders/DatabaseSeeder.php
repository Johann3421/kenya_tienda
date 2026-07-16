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

        $lines   = file($inputFile);
        $total   = 0;
        $buffer  = '';
        $inInsert = false;
        $truncatedTables = [];

        foreach ($lines as $line) {
            if (strpos($line, 'INSERT INTO') === 0) {
                if (preg_match('/INSERT INTO `([^`]+)`/', $line, $m)) {
                    $tableName = $m[1];
                    if (!in_array($tableName, $truncatedTables)) {
                        try { DB::unprepared("TRUNCATE TABLE \"$tableName\" CASCADE;"); } catch (\Exception $e) {}
                        $truncatedTables[] = $tableName;
                    }
                }
                $inInsert = true;
                $buffer = '';
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
                        $this->command->warn("Fila omitida: " . substr($e->getMessage(), 0, 250));
                    }
                }
            }
        }

        DB::unprepared("SET session_replication_role = 'origin';");
        $this->command->info("¡Exito! Se extrajeron e insertaron {$total} bloques de registros en PostgreSQL.");
    }

    private function mysqlToPostgres(string $sql): string
    {
        // 1. Backticks → comillas dobles (identificadores Postgres)
        $sql = str_replace('`', '"', $sql);

        // 2. Escapes de MySQL — el ORDEN importa:
        //    a) \\\\ (dos backslashes en archivo) → \  (antes de procesar \")
        $sql = str_replace('\\\\', '\\', $sql);
        //    b) \' → '' (comilla simple escapada → dos comillas simples para Postgres)
        $sql = str_replace("\\'", "''", $sql);
        //    c) \" → "  (ya no hay doble-backslash, así que esto es seguro)
        $sql = str_replace('\\"', '"', $sql);

        // 3. Quitar outer double-quotes de JSON generados por MySQL:
        //    '"[{"k":"v"}]"' → '[{"k":"v"}]'
        //    El patrón incluye la ' de cierre para no dejar una '' suelta al reemplazar
        $sql = preg_replace("/'\"([\[{].*?[}\]])\"'/s", "'$1'", $sql);

        // 4. Saltos de línea escapados
        $sql = str_replace('\\r\\n', "\r\n", $sql);
        $sql = str_replace('\\n', "\n", $sql);

        return $sql;
    }
}
