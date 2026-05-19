import re
import sys

TABLA_WHITELIST = {
    'categorias', 'modelos', 'productos', 'especificaciones',
    'banners', 'banner_medios', 'asides', 'configuraciones',
    'almacenamiento', 'users', 'roles', 'permissions', 
    'model_has_roles', 'role_has_permissions', 'model_has_permissions',
    'marcas', 'procesador', 'tarjetavideo', 'ram', 'ofimatica',
    'producto_filtros'
}

def unescape_mysql(s):
    # Primero convertimos los booleanos binarios de MySQL a Postgres
    s = s.replace("b'0'", 'FALSE')
    s = s.replace("b'1'", 'TRUE')
    
    # Reemplazamos los escapes de MySQL a sintaxis estándar de Postgres
    # El orden es importante.
    s = s.replace("\\'", "''")   # \' -> ''
    s = s.replace('\\"', '"')    # \" -> "
    s = s.replace('\\n', '\n')   # \n -> literal newline
    s = s.replace('\\r', '\r')   # \r -> literal carriage return
    s = s.replace('\\\\', '\\')  # \\ -> \
    
    return s

def convert(src_path: str, dst_path: str):
    with open(src_path, encoding='utf-8', errors='replace') as f:
        lines = f.readlines()

    out_lines = [
        "-- Importación de datos MySQL → PostgreSQL (Kenya Tienda)",
        "-- Generado automáticamente. Solo tablas de datos clave.",
        "SET session_replication_role = 'replica';  -- desactiva FK checks temporalmente",
        ""
    ]

    current_table = None
    cols_str = None
    buffer = []

    for line in lines:
        # Detectar el inicio de un INSERT
        if line.startswith('INSERT INTO'):
            # Parsear: INSERT INTO `tabla` (`col1`, ...) VALUES
            m = re.match(r"INSERT INTO `(\w+)` \((.*?)\) VALUES", line)
            if m:
                tabla = m.group(1)
                if tabla in TABLA_WHITELIST:
                    current_table = tabla
                    # Formatear columnas
                    cols = ', '.join(f'"{c.strip().strip("`")}"' for c in m.group(2).split(','))
                    cols_str = cols
                    buffer = []
            continue

        if current_table:
            # Estamos acumulando valores para un INSERT
            # Si encontramos otra instrucción o un comentario, asumimos que terminó el INSERT
            if line.startswith('--') or line.startswith('/*!') or line.startswith('CREATE TABLE') or line.startswith('INSERT INTO') or line.startswith('ALTER TABLE') or line.strip() == '':
                # Si el buffer no está vacío, lo procesamos (significa que terminó sin ; en la misma línea o no lo detectamos bien)
                pass # El buffer se procesa cuando vemos el ; final
            
            buffer.append(line)
            
            # Verificamos si la línea termina el INSERT
            if line.strip().endswith(';'):
                # Terminó este INSERT
                values_block = ''.join(buffer)
                # Remover el punto y coma final para poner nuestro ON CONFLICT
                values_block = values_block.strip()[:-1]
                
                # Desescapar todo
                values_block = unescape_mysql(values_block)
                
                sql = (
                    f"\n-- Tabla: {current_table}\n"
                    f"INSERT INTO \"{current_table}\" ({cols_str})\n"
                    f"VALUES\n{values_block}\nON CONFLICT DO NOTHING;\n"
                )
                out_lines.append(sql)
                
                current_table = None
                cols_str = None
                buffer = []

    out_lines.append("\nSET session_replication_role = 'origin';  -- reactiva FK checks\n")

    with open(dst_path, 'w', encoding='utf-8') as f:
        f.write('\n'.join(out_lines))

    print(f"✅ Convertido → {dst_path}")


if __name__ == '__main__':
    src = r'c:\xampp\htdocs\kenya_tienda\kenyacom_kenya (7).sql'
    dst = r'c:\xampp\htdocs\kenya_tienda\import_data.sql'
    convert(src, dst)
