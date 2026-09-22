# 2026-09-22: Resolución de Causa Raíz en Extracción de PDF (Tokens Ambiguos, Colisiones y Descarte de Comentarios)

**Fecha:** 2026-09-22  
**Tipo:** Bug Fix / Causa Raíz / Parser PDF / UI  
**Estado:** Completado  
**Rama:** `feature/dokploy-postgres-sync`  
**Modo Activo:** Ponytail Ultra & Caveman Ultra  

---

## 1. Contexto y Diagnóstico Exhaustivo

### Síntomas Observados
1. **EZENT (`ET7S23W1FT`):**
   - `Puertos Mínimos` mostraba `y/o Slots :`.
   - `Certificaciones` mostraba `de componentes Internos Imágenes referenciales Numerode Parte ET7S23W1FT`.
2. **PROWORK:**
   - `Puertos Mínimos` se cortaba a la mitad en `Jack Audio Tarjeta de`.
   - `Slot de Expansión` no se extraía o caía en `No especificado`.

### Causa Raíz Real Identificada
1. **Bloque Inicial "Comentarios":**
   Los PDFs de Perú Compras tienen un cuadro inicial con notas al pie:
   ```text
   Comentarios
   ⁰WIFI Pci adapter
   ¹Equipo podría integrar más Puertos y/o Slots 
   ²   Certificación de componentes Internos
   Imágenes referenciales
   Numerode Parte ET7S23W1FT
   ```
2. **Tokens Ambiguos en `PDF_SPEC_TOKENS`:**
   - El token `PUERTOS` coincidía con `...más Puertos y/o Slots` en el encabezado (offset ~60) antes de llegar a la tabla real `Puertos Mínimos¹` (offset ~600). Al guardarse primero, bloqueaba el valor real (`!isset($specs[$specKey])`).
   - El token `CERTIFICACIÓN` coincidía con `Certificación de componentes Internos` en el encabezado (offset ~80) en lugar de la fila `Certificaciones² ROSH, FCC, CE`.
3. **Colisiones en ProWork:**
   - La especificación de puertos de ProWork contiene:
     `Posteriores: ... x3 Jack Audio` y `Tarjeta de Video: x1 HDMI...`
   - `PDF_SPEC_TOKENS` contenía los tokens aislados `AUDIO` (mapeado a `sonido`) y `VIDEO` (mapeado a `graficos`).
   - Al parsear los puertos, `AUDIO` capturaba a partir de `Jack Audio` asignándolo a sonido (`Tarjeta de`), y `VIDEO` capturaba `Tarjeta de Video` asignándolo a gráficos (`: x1 HDMI; x3 DisplayPort`), mutilando por completo el campo de puertos.

---

## 2. Solución de Arquitectura Definitiva

1. **Depuración de Tokens en [`SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php):**
   - **Eliminación de colisiones:** Se removieron los tokens genéricos `VIDEO`, `AUDIO`, `PUERTO` y `CERTIFICACIÓN` (sin superíndice).
   - **Tokens exactos mantenidos:** `PUERTOS MÍNIMOS`, `PUERTOS MINIMOS`, `PUERTOS:`, `PUERTOS`, `RANURAS DE EXPANSIÓN MÍNIMOS`, `CERTIFICACIONES`, `CERTIFICACIÓN²`, `CERTIFICACIÓN³`.
   - **Descarte de bloque `Comentarios`:** `parsePdfBinaryToSpecs()` elimina con regex el bloque inicial de comentarios previo al primer encabezado de la tabla (`Modelo`, `Formato`, `Chasis`, `Procesador`), blindando el parser contra notas al pie.
2. **Normalización Multi-línea de Puertos:**
   - Inserción limpia de separadores ` | ` antes de `Frontal(es):`, `Posterior(es):` y `Tarjeta de Video:` en `normalizePcSpecs()`.
   - Soporte automático para render multi-línea tanto en EZENT como en PROWORK.
3. **Protección en Vista ([`detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)):**
   - `$isInvalidPortValue`: rechaza cualquier residuo de footnote (`y/o slots`, `podría integrar`).
   - `certificacionesRaw`: descarta texto contaminado con `componentes Internos` o `Imágenes referenciales` y activa fallback canónico.
   - Render con `{!! nl2br(e(str_replace(' | ', "\n", ...))) !!}` para saltos de línea estéticos y limpios.

---

## 3. Archivos Modificados
- [`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)
- [`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)
- [`notes/INDEX.md`](file:///c:/xampp/htdocs/kenya_tienda/notes/INDEX.md)
