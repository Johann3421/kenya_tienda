import sys
import re
import os

def extract_and_convert(table_name):
    print(f"Extracting table: {table_name}")
    with open('kenyacom_kenya (7).sql', 'r', encoding='utf-8') as f:
        sql = f.read()

    lines = sql.split('\n')
    insert_lines = []
    in_insert = False

    target = f"INSERT INTO `{table_name}`"
    
    for line in lines:
        if line.startswith(target):
            in_insert = True
            
        if in_insert:
            insert_lines.append(line)
            if line.endswith(');'):
                break

    if not insert_lines:
        print(f"Error: Table {table_name} not found or no INSERT statements.")
        sys.exit(1)

    insert_sql = '\n'.join(insert_lines)

    # 1. Replace backticks with double quotes (for Postgres identifiers)
    insert_sql = insert_sql.replace('`', '"')
    
    # 2. In MySQL dumps, single quotes inside single-quoted strings are escaped as \'
    # In Postgres, they must be escaped as ''
    insert_sql = insert_sql.replace("\\'", "''")

    # For these tables (clientes, detalles_soportes), we don't have double-encoded JSON,
    # but we might have string literals. Since Postgres accepts standard strings,
    # we don't need the crazy JSON cleaning we did for soportes unless there are JSON cols.
    # However, just to be safe with escaped quotes:
    # MySQL escaping for double quotes inside strings is \". Postgres doesn't need it.
    insert_sql = insert_sql.replace('\\"', '"')

    # Since it's a bulk INSERT, we can append ON CONFLICT DO NOTHING
    # Wait, the INSERT statement ends with ');'
    # We will replace the last ');' with ') ON CONFLICT (id) DO NOTHING;'
    
    # But wait, ON CONFLICT DO NOTHING is Postgres syntax, and it applies to the whole statement.
    if insert_sql.endswith(');'):
        insert_sql = insert_sql[:-2] + ') ON CONFLICT ("id") DO NOTHING;'

    # Now create the PHP Seeder string
    class_name = f"{table_name.replace('_', ' ').title().replace(' ', '')}ProduccionSeeder"
    
    php_code = f"""<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;

class {class_name} extends Seeder
{{
    public function run()
    {{
        $sql = <<<'SQL'
-- Script de migracion de {table_name} para PostgreSQL

BEGIN;

{insert_sql}

SELECT setval('{table_name}_id_seq', COALESCE((SELECT MAX(id)+1 FROM "{table_name}"), 1), false);

COMMIT;

SQL;
        DB::unprepared($sql);
        $this->command->info('Datos de {table_name} insertados exitosamente en PostgreSQL (ignorando duplicados).');
    }}
}}
"""

    out_file = f'database/seeders/{class_name}.php'
    with open(out_file, 'w', encoding='utf-8') as f:
        f.write(php_code)
    
    print(f"Successfully generated {out_file}")

if __name__ == '__main__':
    if len(sys.argv) < 2:
        print("Usage: python extract_table.py <table_name>")
        sys.exit(1)
    
    table = sys.argv[1]
    extract_and_convert(table)
