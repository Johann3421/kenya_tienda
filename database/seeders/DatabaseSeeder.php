<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $inputFile = base_path('RESPALDO_KENYA_DESPLEGADO_2-01-2026.sql');
        
        if (!file_exists($inputFile)) {
            $this->command->info("No se encontró el archivo de respaldo para migrar.");
            return;
        }

        $this->command->info("Iniciando migración mágica de datos MySQL -> PostgreSQL...");

        // Desactivar restricciones de llaves foráneas temporalmente durante el seed
        DB::unprepared('SET session_replication_role = \'replica\';');

        $lines = file($inputFile);
        $total = 0;
        $buffer = '';
        $inInsert = false;
        $truncatedTables = [];

        foreach ($lines as $line) {
            // Empezar a capturar si la línea es un INSERT
            if (strpos($line, 'INSERT INTO') === 0) {
                if (preg_match('/INSERT INTO `([^`]+)`/', $line, $matches)) {
                    $tableName = $matches[1];
                    // Truncar la tabla antes del primer bloque de INSERT para evitar errores de Unique Constraint
                    if (!in_array($tableName, $truncatedTables)) {
                        try {
                            DB::unprepared("TRUNCATE TABLE \"$tableName\" CASCADE;");
                        } catch (\Exception $e) {
                            // Ignorar si hay algún problema al truncar
                        }
                        $truncatedTables[] = $tableName;
                    }
                }
                
                $inInsert = true;
                $buffer = '';
            }
            
            if ($inInsert) {
                $buffer .= $line;
                
                // Si la línea termina en punto y coma, ejecutamos el bloque
                if (substr(rtrim($line), -1) === ';') {
                    $inInsert = false;
                    
                    // 1. Reemplazar comillas invertidas por comillas dobles (para identificadores Postgres)
                    $sql = str_replace('`', '"', $buffer);
                    
                    // 2. Reemplazar escapes de MySQL por escapes de Postgres
                    $sql = str_replace("\\'", "''", $sql);
                    $sql = str_replace('\\"', '"', $sql);
                    $sql = str_replace('\\r\\n', "\r\n", $sql);
                    $sql = str_replace('\\n', "\n", $sql);
                    
                    DB::unprepared($sql);
                    $total++;
                }
            }
        }

        // Reactivar llaves foráneas
        DB::unprepared('SET session_replication_role = \'origin\';');

        $this->command->info("¡Exito! Se extrajeron e insertaron {$total} bloques de registros en PostgreSQL.");
    }
}
