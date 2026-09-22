# 2026-09-22: Mapeo de Chasis a Formato, Extracción de Torre Mediana e Inferencia por Modelo

**Fecha:** 2026-09-22  
**Tipo:** Bug Fix / Normalización de Especificaciones / UI  
**Estado:** Completado  
**Rama:** `feature/dokploy-postgres-sync`  
**Modo Activo:** Ponytail Ultra & Caveman Ultra  

---

## 1. Contexto y Caso Reportado
- **Producto:** `COMPUTADORA KENYA EZENT T700 (E7CD6OWNHPXO3B5PV6)`
- **Problema:** En el detalle web, el campo `Formato` mostraba `"No especificado"`, mientras que en el PDF oficial de Perú Compras figuraba claramente como `Chasis Torre Mediana`.
- **Causa raíz:**
  1. En las fichas técnicas del catálogo del estado, algunas máquinas denominan el factor de forma como `Chasis`, `Tipo de Chasis`, `Chasis / Formato` o `Factor de Forma`, en lugar del término canónico `Formato`.
  2. Si la especificación no fue cargada bajo el término exacto en la tabla `especificaciones`, la vista no analizaba el campo `descripcion` ni el texto compuesto del producto.
  3. `SyncFichasCommand::SPEC_TOKENS` no incluía delimitadores de `CHASIS:` ni `FORMATO:` para el parser de texto de descripción.

---

## 2. Solución Implementada
1. **Vista de Detalle ([`detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)):**
   - **Mapeo Ampliado:** Se añadieron patrones exactos para capturar `Chasis`, `Formato / Chasis`, `Tipo de Chasis`, `Gabinete` y `Factor de Forma`.
   - **Extracción de Texto Respaldo:** Si la BD no tiene la fila en `especificaciones`, analiza automáticamente `descripcion`, `especificaciones` y `nombre` buscando `"CHASIS: ..."` o menciones directas como `"Torre Mediana"`, `"Small Form Factor"`, etc.
   - **Inferencia por Nomenclatura OEM:** Para PCs Kenya EZENT / ProWork / GenWork:
     - Serie `T` (ej. `T700`, `T500`, `TOWER`, `E7CT` / `E7CD`) -> `Torre Mediana`.
     - Serie `S` (ej. `S700`, `SFF`, `E7S`) -> `Small Form Factor`.
     - Serie `M` (ej. `M700`, `MINI`) -> `Mini PC`.
   - **Limpieza de Prefijo:** Limpieza automática del prefijo (`Chasis: `, `Formato: `) dejando únicamente el valor legible (`Torre Mediana`).

2. **Sincronizador Backend ([`SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)):**
   - Agregados tokens `CHASIS:`, `FORMATO:`, `TIPO DE CHASIS:`, `FACTOR DE FORMA:` a `SPEC_TOKENS`.
   - Agregados tokens extendidos `FACTOR DE FORMA / CHASIS`, `TIPO DE CHASIS`, `CHASIS / FORMATO`, `GABINETE` a `PDF_SPEC_TOKENS`.
   - Sanitización en `normalizePcSpecs()` para limpiar prefijos residuales de `formato`.

3. **Extractor Python ([`PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md`](file:///c:/xampp/htdocs/kenya_tienda/PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md)):**
   - Regla de post-procesamiento para normalizar y depurar `formato` a formato título.

---

## 3. Archivos Modificados
- [`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)
- [`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)
- [`PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md`](file:///c:/xampp/htdocs/kenya_tienda/PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md)
- [`notes/INDEX.md`](file:///c:/xampp/htdocs/kenya_tienda/notes/INDEX.md)
