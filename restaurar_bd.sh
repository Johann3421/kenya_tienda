#!/bin/bash
# Script para restaurar el último respaldo de la base de datos generado por el contenedor kenya_db_backup
# IMPORTANTE: Esto sobrescribirá la base de datos actual con el respaldo más reciente.

echo "Iniciando proceso de restauración..."

# Buscar el archivo .sql.gz más reciente en el volumen de backups
LATEST_BACKUP=$(docker exec kenya_db_backup sh -c 'ls -t /backups/daily/*.sql.gz 2>/dev/null | head -n 1')

if [ -z "$LATEST_BACKUP" ]; then
    echo "❌ Error: No se encontraron respaldos en /backups/daily/ dentro del contenedor kenya_db_backup."
    echo "Asegúrate de que se haya ejecutado al menos un respaldo."
    exit 1
fi

echo "✅ Último respaldo encontrado: $LATEST_BACKUP"
echo "⚠️  ADVERTENCIA: Vas a restaurar este respaldo sobre la base de datos actual."
read -p "¿Estás seguro de continuar? (Escribe 'si' para continuar): " confirmacion

if [ "$confirmacion" != "si" ]; then
    echo "Restauración cancelada."
    exit 0
fi

echo "Limpiando la base de datos y restaurando..."

# Obtener variables de entorno del contenedor de respaldo
DB_USER=$(docker exec kenya_db_backup printenv POSTGRES_USER)
DB_NAME=$(docker exec kenya_db_backup printenv POSTGRES_DB)

# 1. Terminar conexiones activas
docker exec kenya_db_backup sh -c "psql -h db -U $DB_USER -d postgres -c \"SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = '$DB_NAME';\""

# 2. Recrear la base de datos para asegurar una restauración limpia (opcional pero recomendado para no cruzar datos)
docker exec kenya_db_backup sh -c "dropdb -h db -U $DB_USER --if-exists $DB_NAME && createdb -h db -U $DB_USER $DB_NAME"

# 3. Restaurar desde el archivo comprimido
docker exec kenya_db_backup sh -c "gunzip -c $LATEST_BACKUP | psql -h db -U $DB_USER -d $DB_NAME"

echo "✅ ¡Restauración completada con éxito!"
