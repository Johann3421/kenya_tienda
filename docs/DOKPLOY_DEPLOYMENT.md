# Despliegue en Dokploy

Esta app debe desplegarse en Dokploy con build type `Dockerfile`. El repositorio no debe incluir `vendor`, `node_modules`, backups SQL ni drivers pesados.

## Servicios

1. Crea una base de datos PostgreSQL en Dokploy.
2. Usa el nombre interno del servicio PostgreSQL como `DB_HOST`, por ejemplo `postgres-prod`.
3. Crea la app desde GitHub usando el `Dockerfile` del repo.
4. En Advanced > Volumes monta un volumen persistente en:

```text
/var/www/html/storage/app/public
```

Ese volumen contendrá imágenes subidas, PDFs y `DRIVERS`. Sin ese volumen, los archivos subidos se perderán en cada redeploy.

## Variables

Copia `.env.dokploy.example` en la pestaña Environment de Dokploy y completa:

```text
APP_KEY=base64:...
APP_URL=https://tu-dominio
DB_HOST=<nombre-interno-postgres>
DB_PASSWORD=<password>
MAIL_*=...
```

Deja `RUN_MIGRATIONS=false` en el primer deploy si vas a restaurar una base migrada desde MySQL. Actívalo solo cuando quieras que el contenedor ejecute migraciones automáticamente.

## Drivers grandes

Los drivers actuales pesan varios GB y viven en `storage/app/public/DRIVERS`. No deben subirse a GitHub.

Opciones recomendadas:

1. Volumen persistente en Dokploy: copiar la carpeta `DRIVERS` al volumen montado.
2. S3/MinIO: mover drivers a object storage y guardar URLs firmadas o públicas en base de datos.

Para la primera subida al VPS puedes comprimir solo `DRIVERS`, subirlo por `scp/rsync` y extraerlo dentro del volumen del contenedor.

## Migración MySQL a PostgreSQL

Ruta recomendada:

1. Congelar escrituras en producción antigua.
2. Exportar MySQL con `pgloader` o convertir dump con una herramienta especializada.
3. Importar en PostgreSQL.
4. Ejecutar una revisión de conteos por tabla.
5. Probar login, catálogo, soporte, pedidos, garantías, drivers y PDF.
6. Cambiar DNS/dominio cuando pase la prueba.

Comando de referencia con `pgloader` desde un host con acceso a ambas bases:

```bash
pgloader mysql://root:password@mysql-host/kenya_tienda postgresql://kenya_app:password@postgres-prod:5432/kenya_tienda
```

Luego revisa secuencias en PostgreSQL si se insertaron IDs manualmente:

```sql
SELECT setval(pg_get_serial_sequence('productos','id'), COALESCE(MAX(id),1)) FROM productos;
SELECT setval(pg_get_serial_sequence('pedidos','id'), COALESCE(MAX(id),1)) FROM pedidos;
SELECT setval(pg_get_serial_sequence('soportes','id'), COALESCE(MAX(id),1)) FROM soportes;
```

## Checklist

- `php artisan route:list` debe terminar sin error.
- `php artisan config:cache` debe terminar sin error.
- `php artisan route:cache` debe terminar sin error.
- `/health` debe responder `ok`.
- `public/storage` debe apuntar a `storage/app/public`.
- `storage/app/public/DRIVERS` debe existir en el volumen.
