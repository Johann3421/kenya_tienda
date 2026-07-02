import re

with open('kenyacom_kenya (7).sql', 'r', encoding='utf-8') as f:
    sql = f.read()

# Extract CREATE TABLE soportes
create_match = re.search(r'CREATE TABLE soportes \((.*?)\) ENGINE=.*?;\n', sql, re.DOTALL)
if create_match:
    print('--- CREATE TABLE SCHEMA ---')
    print('CREATE TABLE soportes (' + create_match.group(1) + ');\n')

# Extract INSERT INTO soportes
insert_match = re.search(r'INSERT INTO soportes \((.*?)\) VALUES\s*(.*?);', sql, re.DOTALL)
if insert_match:
    print('--- INSERT DATA ---')
    print('INSERT INTO soportes (' + insert_match.group(1) + ') VALUES ' + insert_match.group(2)[:200] + ' ...;')
