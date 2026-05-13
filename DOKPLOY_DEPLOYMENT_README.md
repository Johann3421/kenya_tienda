# 🚀 Guía de Despliegue con Docker Compose en Dokploy - Kenya Tienda

Esta guía detalla el proceso paso a paso para desplegar todo tu proyecto **Kenya Tienda** (Aplicación + Base de Datos) de forma unificada utilizando la funcionalidad **Compose** de Dokploy y tu archivo `docker-compose.yml`.

Al utilizar este método, tu aplicación web y tu base de datos convivirán dentro del mismo entorno de red, facilitando enormemente la configuración.

---

## 🛠️ PASO 1: Ajuste Previo (Base de Datos)

En tu archivo `docker-compose.yml` actual existe un comentario muy importante:
> *ATENCIÓN: Este dump es de MariaDB. Para que Postgres lo importe sin errores, deberá ser convertido a dialecto Postgres previamente.*

El archivo actual está usando la imagen `postgres:15-alpine` pero intenta importar `RESPALDO_KENYA_DESPLEGADO_2-01-2026.sql` (que es de MySQL/MariaDB). **Si no cambias esto, el contenedor de base de datos fallará en Dokploy**.

Tienes dos opciones antes de hacer commit/push:
1. **Opción A:** Convertir tu archivo `.sql` al formato compatible con PostgreSQL.
2. **Opción B (Recomendada si el código está en MySQL):** Cambiar el `docker-compose.yml` para usar MariaDB. Si decides esto, edita la sección `db` así:
   ```yaml
   db:
     image: mariadb:10.11
     container_name: kenya_db
     restart: unless-stopped
     environment:
       MYSQL_DATABASE: ${DB_DATABASE:-kenya_tienda}
       MYSQL_USER: ${DB_USERNAME:-kenya_app}
       MYSQL_PASSWORD: ${DB_PASSWORD:-mypassword}
       MYSQL_ROOT_PASSWORD: ${DB_PASSWORD:-mypassword}
     ports:
       - "3306:3306"
     volumes:
       - db_data:/var/lib/mysql
       - ./RESPALDO_KENYA_DESPLEGADO_2-01-2026.sql:/docker-entrypoint-initdb.d/init.sql
   ```
   *(Asegúrate de cambiar también `DB_CONNECTION=mysql` y `DB_PORT=3306` en el entorno de Laravel).*

---

## 🛠️ PASO 2: Crear el servicio Compose en Dokploy

1. Inicia sesión en tu panel de administración de **Dokploy**.
2. Ve a la sección **Compose** (en el menú lateral).
3. Haz clic en el botón **Create Compose**.
4. Rellena los datos:
   - **Name:** `kenya-tienda` (o el nombre que prefieras).
   - **Source:** Selecciona **Github** o **Git** y vincula el repositorio donde tienes subido tu proyecto `kenya_tienda`.
   - **Branch:** La rama principal (ej. `main` o `master`).
   - **Compose Path:** Asegúrate de que diga exactamente `docker-compose.yml` (Dokploy leerá este archivo desde la raíz de tu repo).
5. Haz clic en **Save** o **Create**.

---

## 🛠️ PASO 3: Configurar Variables de Entorno (.env)

1. Entra a la configuración del Compose `kenya-tienda` recién creado.
2. Ve a la pestaña **Environment**.
3. Pega el contenido de tu archivo local `.env.dokploy.example` o `.env.example`.
4. Realiza los **ajustes obligatorios**:
   - `APP_URL=https://tudominio.com` (Tu dominio final).
   - `APP_KEY=base64:xxx...` (Copia la clave que ya generaste localmente).
   - **Para PostgreSQL (si mantuviste Postgres):**
     - `DB_CONNECTION=pgsql`
     - `DB_HOST=db` *(Debe llamarse 'db' tal cual está en el services del docker-compose.yml)*
     - `DB_PORT=5432`
   - **Para MariaDB/MySQL (si hiciste el cambio del Paso 1):**
     - `DB_CONNECTION=mysql`
     - `DB_HOST=db`
     - `DB_PORT=3306`
   - Configura contraseñas fuertes para `DB_PASSWORD`.
5. Haz clic en **Save**.

---

## 🛠️ PASO 4: Dominios y SSL

A diferencia de las aplicaciones nativas de Dokploy, los dominios en Compose se pueden gestionar directamente en Dokploy si expones el puerto, pero la forma oficial es:
1. Ve a la pestaña **Domains** dentro de la vista de tu Compose en Dokploy.
2. Añade tu dominio principal (ej. `www.kenya.com.pe`).
3. En **Port**, debes especificar el puerto de tu aplicación web definido en el docker-compose. El puerto que expone el contenedor de la app hacia adentro de Dokploy es el **80** (el que maneja Apache dentro del contenedor). Por lo tanto, selecciona el contenedor `app` y el puerto `80`.
4. Marca la opción para **Generar certificado SSL (Let's Encrypt)**.
5. Guarda.
*(Recuerda que debes tener apuntado el DNS (Registro A) de tu dominio hacia la IP del servidor Dokploy).*

---

## 🛠️ PASO 5: ¡Desplegar!

1. Haz clic en el botón **Deploy**.
2. Ve a la pestaña **Deploy Logs**.
3. Verás cómo Dokploy construye el `Dockerfile` de tu app e inicia el servicio de la base de datos simultáneamente. La primera vez puede tardar unos minutos ya que importará el SQL pesado de 1.7MB.

---

## 🛠️ PASO 6: Comandos Finales (Solo la primera vez)

Aunque en tu `Dockerfile` ya instalas las dependencias con Composer, hay acciones propias de Laravel que requieren ejecutarse en el contenedor vivo:

1. Ve a la pestaña **Terminal** de tu Compose en Dokploy.
2. Selecciona el contenedor de tu aplicación (`kenya_app`).
3. Ejecuta estos comandos básicos para pulir el caché y los permisos:
   ```bash
   # Generar symlink para las imágenes
   php artisan storage:link

   # Limpiar y optimizar todas las cachés
   php artisan optimize:clear
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   
   # Asegurar permisos (el entrypoint lo hace, pero no está de más)
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

¡Listo! Tu tienda Kenya estará operando completamente dockerizada y controlada a través de un único servicio Compose en Dokploy.
