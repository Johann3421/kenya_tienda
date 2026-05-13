import re

def extract_inserts(input_file, output_file):
    with open(input_file, 'r', encoding='utf-8') as f:
        lines = f.readlines()

    out_lines = []
    
    insert_pattern = re.compile(r'INSERT INTO `([^`]+)` \(([^)]+)\) VALUES')
    
    for line in lines:
        match = insert_pattern.search(line)
        if match:
            table = match.group(1)
            cols = match.group(2).replace('`', '"')
            
            # replace the first part with postgres format
            line = line.replace(f'INSERT INTO `{table}` ({match.group(2)})', f'INSERT INTO "{table}" ({cols})')
            
            # mysql escaping: \' -> ''
            line = line.replace("\\'", "''")
            
            # mysql escaping: \" -> "
            line = line.replace('\\"', '"')
            
            # mysql escaping: \\ -> \
            # Be careful with this, usually not needed if simple strings
            
            out_lines.append(line)

    with open(output_file, 'w', encoding='utf-8') as f:
        f.writelines(out_lines)
        
    print(f"Extracted {len(out_lines)} INSERT statements.")

if __name__ == '__main__':
    extract_inserts('c:/xampp/htdocs/kenya_tienda/RESPALDO_KENYA_DESPLEGADO_2-01-2026.sql', 'c:/xampp/htdocs/kenya_tienda/RESPALDO_POSTGRES.sql')
