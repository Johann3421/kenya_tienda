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

        foreach ($lines as $line) {
            if (preg_match('/INSERT INTO `([^`]+)` \(([^)]+)\) VALUES/', $line, $matches)) {
                $table = $matches[1];
                $cols = str_replace('`', '"', $matches[2]);
                
                $newLine = str_replace("INSERT INTO `{$table}` ({$matches[2]})", "INSERT INTO \"{$table}\" ({$cols})", $line);
                
                // Convertir escapes de MySQL a PostgreSQL
                $newLine = str_replace("\\'", "''", $newLine);
                $newLine = str_replace('\\"', '"', $newLine);
                $newLine = str_replace('\\r\\n', "\r\n", $newLine);
                
                DB::unprepared($newLine);
                $total++;
            }
        }

        // Reactivar llaves foráneas
        DB::unprepared('SET session_replication_role = \'origin\';');

        $this->command->info("¡Exito! Se extrajeron e insertaron {$total} bloques de registros en PostgreSQL.");
    }
}
