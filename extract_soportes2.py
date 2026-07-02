with open('kenyacom_kenya (7).sql', 'r', encoding='utf-8') as f:
    lines = f.readlines()

in_create = False
in_insert = False
create_sql = ''
insert_sql = ''

for line in lines:
    if line.startswith('CREATE TABLE `soportes`'):
        in_create = True
    
    if in_create:
        create_sql += line
        if line.strip().endswith(';'):
            in_create = False
            
    if line.startswith('INSERT INTO `soportes`'):
        in_insert = True
        
    if in_insert:
        insert_sql += line
        if line.strip().endswith(';'):
            in_insert = False

with open('soportes_extracted.sql', 'w', encoding='utf-8') as f:
    f.write(create_sql + '\n\n' + insert_sql)
print("Extracted to soportes_extracted.sql")
