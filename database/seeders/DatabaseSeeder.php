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
        $inputFile = public_path('kenyacom_kenya (7).sql');

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

                    // 1. Reemplazar comillas invertidas por comillas dobles (identificadores Postgres)
                    $sql = str_replace('`', '"', $buffer);

                    // 2. Reemplazar escapes de MySQL por escapes de Postgres en strings
                    $sql = str_replace("\\'", "''", $sql);
                    $sql = str_replace('\\r\\n', "\r\n", $sql);
                    $sql = str_replace('\\n', "\n", $sql);

                    // 3. Reparar JSON sobre-escapado de MySQL.
                    // MySQL guarda JSON como: '"[{\"nombre\":\"val\"}]"' (con outer double-quotes y backslash-escapes).
                    // Postgres espera:         '[{"nombre":"val"}]'   (JSON limpio dentro de comillas simples).
                    // El patrón captura: '\"[...cadena...]\"'  y lo convierte en '[...sin backslashes...]'
                    $sql = preg_replace_callback(
                        // Captura valores entre comillas simples que empiezan con \" y son JSON sobre-escapado
                        "/'(\"(?:[^'\\\\]|\\\\.)*\")'(?=[,\\s)])/",
                        function ($matches) {
                            $inner = $matches[1]; // el valor incluyendo las outer-double-quotes
                            // quitar las comillas externas
                            $inner = substr($inner, 1, -1);
                            // des-escapar los backslash-quote internos
                            $inner = str_replace('\\"', '"', $inner);
                            $inner = str_replace('\\\\', '\\', $inner);
                            // validar que sea JSON antes de aceptarlo
                            if (json_decode($inner) !== null || $inner === 'null') {
                                return "'" . str_replace("'", "''", $inner) . "'";
                            }
                            // si no es JSON válido, devolver sin cambio
                            return $matches[0];
                        },
                        $sql
                    );

                    // 4. Convertir 'NULL' (string) a NULL real solo en posiciones de valor, no en strings de texto
                    // ponytail: omitido para evitar falsos positivos; las columnas nullable aceptan 'NULL' como texto

                    try {
                        DB::unprepared($sql);
                        $total++;
                    } catch (\Exception $e) {
                        $this->command->warn("Fila omitida (error al insertar): " . substr($e->getMessage(), 0, 200));
                    }
                }
            }
        }

        // Reactivar llaves foráneas
        DB::unprepared('SET session_replication_role = \'origin\';');

        $this->command->info("¡Exito! Se extrajeron e insertaron {$total} bloques de registros en PostgreSQL.");
    }
}
