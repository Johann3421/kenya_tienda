# 2026-09-22: Síntesis de Contexto Integral, Auditoría Fichas PC y Sanitización de Puertos Mínimos

**Fecha:** 2026-09-22  
**Tipo:** Documentación / Contexto / Fix Sanitización / Refactor  
**Estado:** Completado / Sincronizado  
**Rama:** `feature/dokploy-postgres-sync`  
**Modo Activo:** Ponytail Ultra & Caveman Ultra  

---

## 1. Contexto Global del Proyecto (Kenya Tienda)

Plataforma integral de comercio electrónico, catálogo y soporte técnico para **Kenya Technology** (equipos de computo, laptops, monitores, suministros/tóner).
- **Stack:** Laravel (PHP 8.x) + Blade + JavaScript (Vue.js en módulos reactivos de soporte/garantía) + Tailwind/CSS personalizado + PostgreSQL / MySQL (Dokploy).
- **Núcleo de Especificaciones:**
  - `SyncFichasCommand.php` (`php artisan fichas:sync`): Tokeniza y parsea PDFs de fichas técnicas de Perú Compras / catálogo interno.
  - `ProductoController.php`: Renderizado y administración de productos, plantillas canónicas de orden (`canonicalPcOrder`), normalización de campos (`normalizeCampo`).
  - `detalle.blade.php`: Vista pública de ficha de producto. Aplica sanitizaciones al vuelo (Ley de Tesler) para corregir datos históricos en BD sin esperar resincronizaciones masivas.
  - `garantia.blade.php` / `garantiaQR.blade.php`: Consulta de estado de garantía y descarga de controladores por número de serie o QR, soporte de auto-detección OEM (`detectar-pc-kenya.bat` / protocolo `kenya://`).

---

## 2. Evolución Cronológica del Historial de Chat y Decisiones

### A. Sesión 2026-09-21: Auditoría Inicial EZENT 368 y Reestructuración
1. **Unificación Formato:** Se fusionaron 'Formato' y 'Chasis' como `'Formato / Chasis'`.
2. **Puertos Mínimos:** Se había eliminado provisionalmente debido a que capturaba notas al pie legales contaminantes (`el equipo podría integrar...`).
3. **Desacoplamiento Fuente / Seguridad:** Se separó `Seguridad TPM 2.0` de `Fuente de Poder`.
4. **Empaque y Certificaciones:** Certificaciones (`ROHS, FCC, CE, RAEE`) se movieron de `Empaque` a `Certificaciones`.
5. **Detección OEM:** Paquete portable `public/oem/` para leer serie de BIOS/SMBIOS con 1 clic.
6. **Rediseño Garantía QR:** Paridad visual completa de `garantiaQR.blade.php` con la vista principal de soporte.

### B. Sesión 2026-09-22 (Hoy): Refinamiento, Reversiones y Robustez
1. **Reincorporación de 'Formato' y 'Puertos Mínimos' (Commit `13db4eb`):**
   - El cliente revirtió la decisión de `'Formato / Chasis'` -> Exige estrictamente `'Formato'`.
   - Reincorporación obligatoria de `'Puertos Mínimos'` tanto en vista pública (`$oldPcRows`), en plantilla (`$canonicalPcOrder`), como en el sincronizador.
2. **Truncado de Garantía en CARRY-IN (Commit `313ee4b`):**
   - Garantía debe terminar exactamente en `CARRY-IN` o `ON-SITE`. Todo texto posterior (ej. `UNIDAD KENYA TECHNOLOGY EZENT...`) se elimina automáticamente.
3. **Depuración Accesorios y Desolapamiento de Tokens (Commit `ca1b8cc`):**
   - Algoritmo de desolapamiento estricto por posición y longitud en `SyncFichasCommand::parseTokenizedText()`.
   - `Accesorios y Otros` filtrado de residuos legales (`MARCA REGISTRADA...`) y fallback de fábrica para PCs: `Teclado, Mouse, Cable de Poder, Manuales, Drivers, Términos de Garantia`.
4. **Sanitización Quirúrgica de Puertos Mínimos (Cambios Actuales):**
   - En lugar de suprimir `Puertos Mínimos`, se limpiaron mediante regex las notas residuales de pie de página (`podría integrar...`, `potencia mínima`, superíndices `¹ ² ³`).
   - Se estableció fallback estándar para PCs: `x2 USB 3.2 Gen 1 (Frontal), x4 USB 2.0 (Posterior), x1 HDMI, x1 DisplayPort, x1 RJ-45, Conectores de Audio`.
   - Se unificó la etiqueta a `'Formato'` y se restableció `'Puertos Mínimos'` en `$oldPcRows` de `detalle.blade.php` y `$canonicalPcOrder` de `ProductoController.php`.

---

## 3. Archivos Involucrados y Estado Actual

- [`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php):
  - Etiqueta `'Formato'`.
  - Sanitización en render de `puertosRaw` y visualización de `'Puertos Mínimos'`.
  - Limpieza de prefijos y truncado en `CARRY-IN` / `ON-SITE` de `$garantiaRaw`.
  - Limpieza y fallback de `$accesoriosRaw`.
- [`app/Http/Controllers/ProductoController.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Http/Controllers/ProductoController.php):
  - Mapeo canónico a `'FORMATO'`.
  - Inclusión de `'PUERTOS MÍNIMOS'` en `$canonicalPcOrder` y `$normalizeCampo`.
- [`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php):
  - Mapeo de tokens `'PUERTOS'` y `'PUERTO'`.
  - Sanitización de notas legales y superíndices en `puertos_minimos` y `cleanSpecValue()`.
- [`PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md`](file:///c:/xampp/htdocs/kenya_tienda/PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md):
  - Reglas de post-procesamiento de Python actualizadas para accesorios y puertos mínimos.

---

## 4. Guía para Próximas Sesiones (Directriz Anti-Divagación)
1. **No volver a renombrar 'Formato' a 'Formato / Chasis':** La orden del cliente es mantener `'Formato'`.
2. **No eliminar 'Puertos Mínimos':** El cliente lo requiere visible; cualquier ruido de texto legal se mitiga en la regex de sanitización, no borrando el campo.
3. **Respetar la Ley de Tesler:** Todo saneamiento de texto se procesa en el backend/sanitizador de la vista sin alterar negativamente la presentación limpia de cara al cliente final.
