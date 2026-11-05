# Análisis de Logs de Producción, Auditoría de Errores y Corrección Quirúrgica

- **Fecha:** 2026-11-05
- **Tipo:** Bug Fix / Auditoría / Logs / Backend / Seguridad
- **Estado:** Completado
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra)

---

## 1. Contexto y Análisis de Logs del Servidor

A partir de la extracción de logs de producción (`production.INFO`, Apache/Nginx access logs), se identificaron 5 comportamientos clave:

1. **Absorción de Accesorios en Mouse en Consulta de Garantía QR (`/consultar/garantia/PHC00120401476`):**
   - **Log:** `QR - Especificación original: "Mouse" → Mapeado a: "mouse" = Cable de Poder, Manuales, Drivers, Términos de Garantia`.
   - **Causa Raíz:** En `SyncFichasCommand.php`, para el modelo PROWORK (WS70/WS90), el extractor de fichas de Perú Compras no incluía el token `ACCESORIOS` en la rama de tokens por defecto. Al leer `MOUSE`, el parser absorbió el texto continuo de accesorios hasta el siguiente token. Posteriormente, la función `$isDescriptive($specs['mouse'])` evaluó erróneamente que "Cable de Poder..." era un mouse descriptivo (> 5 caracteres), bloqueando la agrupación estándar en Accesorios.
2. **Error HTTP 419 (CSRF Mismatch) y Paginación `?page=undefined`:**
   - **Log:** `POST /garantias/buscar?page=undefined HTTP/1.1 419 742 "https://www.kenya.com.pe/producto/garantias"`.
   - **Causa Raíz:** En `public/js/views/garantia/garantia.js`, el hook `created()` invocaba `this.Buscar()` sin parámetros. La función concatenaba `urlBuscar = "../garantias/buscar?page=" + page;`, enviando `?page=undefined`. El error 419 ocurrió por expiración de sesión/CSRF de un usuario administrativo tras inactividad.
3. **Errores HTTP 404 en Imágenes de `/storage/PRODUCTOS/...` y `/storage/MODELOS/...`:**
   - **Log:** `GET /storage/PRODUCTOS/1089/IMG_r963Hh4t1R.png HTTP/1.1 404 153`.
   - **Causa Raíz:** Los registros en base de datos apuntan a imágenes subidas históricamente en el servidor previo que no se sincronizaron en el volumen persistente de Dokploy (`/var/www/html/storage/app/public`).
4. **Error HTTP 404 en `/cotizar/producto/2150`:**
   - **Log:** `GET /cotizar/producto/2150 HTTP/1.1 404 153 "https://www.kenya.com.pe/catalogo/16/detallemod"`.
   - **Causa Raíz:** `CotizarController@detalle` aplica `findOrFail($id)` con condiciones `noSuspendido()->where('pagina_web', 'SI')`. El producto 2150 no está publicado o fue desactivado, por lo que el 404 es la respuesta HTTP estándar y esperada.
5. **Tráfico de Escaneo y Crawlers:**
   - `GET /v1/models` (404): Escaneo de bot buscando APIs LLM/OpenAI expuestas. Mitigado.
   - Peticiones binarias `\x01\x01\xe6\n` (400) y escáner Palo Alto Cortex-Xpanse. Mitigados.
   - Crawlers indexadores legítimos: Googlebot, Bingbot, Amazonbot, Claude-SearchBot.

---

## 2. Archivos Modificados y Soluciones Aplicadas

- `public/js/views/garantia/garantia.js`:
  - Valor por defecto en firma de función `Buscar(page = 1)`.
  - Validación defensiva: si `page` es falsy o string `'undefined'`, se normaliza a `1`.
- `app/Console/Commands/SyncFichasCommand.php`:
  - En `getPdfTokensForModel('PROWORK')`: se añadieron los tokens `'ACCESORIOS Y OTROS'`, `'ACCESORIOSY OTROS'` y `'ACCESORIOS'` a la lista fallback de tokens para evitar que `MOUSE` absorba el bloque de accesorios.
  - En la normalización de PROWORK: si `$specs['mouse']` contiene cadenas de cables, manuales o términos de garantía, se reencamina automáticamente a `$specs['accesorios']` y se descarta del campo `mouse`.
  - Se blindó `$isDescriptive` para rechazar explícitamente textos de accesorios.
- `app/Http/Controllers/ConsultarController.php`:
  - Filtro defensivo tanto en `garantia_buscar` como en `buscar_serie`: si un registro existente en la base de datos contiene texto de accesorios dentro del campo `mouse`, se reasigna a `$productoArray['accesorios']` y se normaliza `mouse` a `'SI'`.
- `app/Http/Controllers/CotizarController.php`:
  - Generación de `$especificacionesResumen` para monitores y PCs antes de renderizar `sistema.productos.detalle`.
- `resources/views/sistema/productos/detalle.blade.php`:
  - Blindaje defensivo en línea 1520 con `($especificacionesResumen ?? [])` para evitar errores 500 por variable no definida.

---

## 3. Decisiones de Arquitectura

- **Corrección en Causa Raíz (Ponytail Ultra):** Se corrigió la lógica del parser de PDFs para futuras ingestas de fichas y se añadió una capa defensiva ligera en el controlador para registros existentes, evitando necesidad de ejecutar migraciones masivas destructivas en producción.
- **Ley de Tesler:** El frontend administrativo y el módulo QR garantizan valores limpios sin requerir reconfiguración manual por el usuario.

---

## 4. Verificación y Despliegue

- `php -l` ejecutado sin errores sintácticos.
- Commit `2085316` empujado a `origin/feature/dokploy-postgres-sync`.
- Commit `c81c3d5` empujado a `origin/feature/dokploy-postgres-sync` (Fix Error 500 en `/cotizar/producto/{id}` para monitores).

