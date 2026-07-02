import re

with open('soportes_extracted.sql', 'r', encoding='utf-8') as f:
    sql = f.read()

# Replace backticks with double quotes
sql = sql.replace('`', '"')
sql = sql.replace("\\'", "''")

# Clean double-encoded JSON arrays so postgres JSON parser accepts them
sql = sql.replace('\'\\"', '\'')  # Remove outer double quote at beginning of string
sql = sql.replace('\\"\'', '\'')  # Remove outer double quote at end of string
sql = sql.replace('\\\\\\"', '"') # Unescape inner triple-escaped quotes
sql = sql.replace('\\"', '"')     # Unescape standard escaped quotes

# Find the INSERT block properly
lines = sql.split('\n')
insert_lines = []
in_insert = False

for line in lines:
    if line.startswith('INSERT INTO "soportes"'):
        in_insert = True
        
    if in_insert:
        insert_lines.append(line)
        if line.endswith(');'):
            break

insert_sql = '\n'.join(insert_lines)

out_sql = '-- Script de migracion de Soportes para PostgreSQL\n\n'
out_sql += 'BEGIN;\n\n'
out_sql += 'DELETE FROM "soportes";\n\n'
out_sql += insert_sql + '\n\n'
out_sql += "SELECT setval('soportes_id_seq', COALESCE((SELECT MAX(id)+1 FROM \"soportes\"), 1), false);\n\n"
out_sql += 'COMMIT;\n'

with open('soportes_postgres_produccion.sql', 'w', encoding='utf-8') as f:
    f.write(out_sql)

print("PostgreSQL script generated!")
