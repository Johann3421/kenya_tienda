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

        // 2. Escape de comilla simple: \' → ''
        $sql = str_replace("\\'", "''", $sql);

        // 3. Escape de comilla doble dentro de strings: \" → "
        //    Esto convierte '[\"v1\",\"v2\"]' → '["v1","v2"]'
        //    Y '"[{\"k\":\"v\"}]"' → '"[{"k":"v"}]"'
        $sql = str_replace('\\"', '"', $sql);

        // 4. Quitar outer double-quotes de JSON generados por MySQL:
        //    '"[{"k":"v"}]"' → '[{"k":"v"}]'   (JSON arrays)
        //    '"{"k":"v"}"'   → '{"k":"v"}'     (JSON objects)
        $sql = preg_replace("/'\"([\[{].*?[}\]])\"/s", "'$1'", $sql);

        // 5. Saltos de línea escapados
        $sql = str_replace('\\r\\n', "\r\n", $sql);
        $sql = str_replace('\\n', "\n", $sql);

        return $sql;
    }
}
