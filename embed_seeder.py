import os

with open('soportes_postgres_produccion.sql', 'r', encoding='utf-8') as f:
    sql = f.read()

seeder = f"""<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;

class SoportesProduccionSeeder extends Seeder
{{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {{
        $sql = <<<'SQL'
{sql}
SQL;
        DB::unprepared($sql);
        $this->command->info('Datos de soportes insertados exitosamente en PostgreSQL.');
    }}
}}
"""

with open('database/seeders/SoportesProduccionSeeder.php', 'w', encoding='utf-8') as f:
    f.write(seeder)
