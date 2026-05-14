# Contexto de Migración: MySQL a PostgreSQL (Proyecto Kenya Tienda)

Este documento resume todos los avances, estrategias y modificaciones realizadas en el proyecto para lograr la migración de datos desde un archivo dump de MySQL (`RESPALDO_KENYA_DESPLEGADO_2-01-2026.sql`) hacia una base de datos PostgreSQL en el entorno de producción (Dokploy/Docker).

**IMPORTANTE PARA LA IA RECEPTORA:** NO reviertas estos cambios ni sugieras usar herramientas externas (como pgloader) ya que se descartaron por problemas de dependencias en el contenedor. El enfoque actual es nativo vía Laravel (`php artisan db:seed`).

---

## 1. Estrategia de Inserción (DatabaseSeeder.php)
Se ha configurado un script de importación nativo en `database/seeders/DatabaseSeeder.php` que lee el archivo SQL línea por línea y lo adapta "al vuelo" para PostgreSQL.
- **Multilínea:** Agrupa los `INSERT INTO` línea por línea en un "buffer" hasta encontrar un punto y coma (`;`), evitando el error `syntax error at end of input`.
- **Sanitización de Dialecto:**
  - Convierte las comillas invertidas de MySQL (\`) a comillas dobles (") para PostgreSQL.
  - Reemplaza los escapes de MySQL (`\'`) por el estándar de Postgres (`''`).
- **Prevención de Duplicados (Unique Constraint Violation):** El seeder extrae el nombre de la tabla de la sentencia SQL y ejecuta `DB::unprepared("TRUNCATE TABLE \"$tableName\" CASCADE;");` automáticamente antes del primer insert de cada tabla. Esto evita colisiones con datos que ya hayan sido insertados "de fábrica" por las propias migraciones (ej. tabla `apis`).

## 2. Ajustes Realizados en las Migraciones
PostgreSQL es mucho más estricto que MySQL en cuanto a tipos de datos y sintaxis. Se realizaron las siguientes modificaciones en la carpeta `database/migrations/`:

### A. Corrección de Sintaxis MySQL (`IF()` a `CASE WHEN`)
PostgreSQL no soporta la función `IF()`. Se han modificado las vistas en las migraciones para usar sintaxis ANSI estándar:
- **`2020_12_24_144354_create_soportes_table.php`**: Reemplazado `IF(estado = 'E1', 1, 0)` por `CASE WHEN estado = 'E1' THEN 1 ELSE 0 END`.
- **`2021_02_05_125644_crear_tabla_pedidos.php`**: Reemplazado `IF(estado_entrega = 'P1', 1, 0)` por `CASE WHEN estado_entrega = 'P1' THEN 1 ELSE 0 END`.

### B. Corrección de Llaves Foráneas (Foreign Keys)
PostgreSQL exige que las columnas referenciadas por una llave foránea sean estrictamente Llaves Primarias o Únicas (MySQL permite que sean simples índices).
- **`2021_05_31_192542_create_location_table.php`**: Se cambió `$table->char('id')->index()` a `$table->char('id')->primary()` en las tablas `departments`, `provinces` y `districts`.

### C. Corrección de Mismatch de Tipos de Datos (Boolean vs Integer, String vs Double)
El dump de MySQL tiene dos particularidades importantes:

1. **Booleanos exportados como enteros:** Exporta los valores booleanos como números (`1` o `0`). Al inyectar un `1` en una columna creada como `boolean` en Postgres, arroja un error de validación de tipo de dato. Para que Postgres acepte el `1` de MySQL, se cambiaron las definiciones de columnas de `boolean()` a `smallInteger()`. Esto no rompe Laravel debido al casting.
Archivos parcheados (`boolean` -> `smallInteger`):
- `2026_01_02_000001_create_serial_draw_tables.php`
- `2025_04_16_161225_create_banner_medios_table.php`
- `2025_04_12_090019_create_asides_table.php`
- `2021_05_31_192542_create_location_table.php`

2. **Cadenas alfanuméricas en columnas Double:** En la base de datos legacy, algunos usuarios ingresaron texto (como `'87654RE3'` o `'H610m-k d4'`) en columnas que originalmente debían ser numéricas (`double`). MySQL lo permitía de forma silenciosa, pero Postgres lanza el error `invalid input syntax for type double precision`.
Archivo parcheado (`double` -> `string`):
- `2021_01_04_205204_create_detalles_soportes_table.php` (Columna `descuento` cambiada a `string`).

---

## 3. Creación de Migraciones Faltantes (Tablas Huérfanas)
Durante el volcado, se detectó que la base de datos MySQL tenía tablas que **nunca fueron declaradas en las migraciones de Laravel**. PostgreSQL necesita que las tablas existan antes de inyectar datos.

- **`especificaciones`**: La tabla existía en MySQL y era usada por el modelo `Especificacion.php` y múltiples controladores, pero no tenía archivo de migración. Se creó manualmente la migración `2024_01_01_000000_create_especificaciones_table.php` definiendo la llave foránea hacia `productos`.
- **`pagina_estados`**: Otra tabla huérfana en las migraciones pero presente en el volcado y en uso en la aplicación. Se creó su respectiva migración `2024_01_01_000001_create_pagina_estados_table.php` para almacenar las rutas, nombres y estados de las páginas.

### B. Columnas Faltantes (Añadidas directamente en MySQL)
Al igual que las tablas huérfanas, algunos administradores añadieron columnas a tablas existentes usando phpMyAdmin o comandos directos sin dejar rastro en las migraciones de Laravel. Postgres es estricto y arroja un error si el volcado de MySQL contiene columnas que no existen en su esquema.
- **Tabla `productos`**: Faltaban las columnas `"Tipo de suministro"`, `"Tipo de panel"` y `"Modelo"`. Se creó la migración correctiva `2024_01_01_000002_add_missing_fields_to_productos_table.php` para agregarlas al esquema antes de insertar los datos.

---

## 4. Estado Actual y Procedimiento de Ejecución
El proyecto está en un estado en el que las migraciones pasan al 100% en PostgreSQL.
Cualquier error futuro en la inserción probablemente se deba a otras incompatibilidades menores de dialecto de datos dentro de los `INSERT`.

**Rutina estándar a ejecutar dentro del contenedor tras cada parche:**
1. `php artisan migrate:fresh` (Para regenerar el esquema desde cero aplicando cambios en migraciones).
2. `php artisan db:seed` (Para ejecutar la inserción y limpieza adaptada).
