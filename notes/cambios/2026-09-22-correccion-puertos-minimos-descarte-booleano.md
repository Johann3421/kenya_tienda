# 2026-09-22: Corrección de Puertos Mínimos, Descarte de Valores Booleanos y Fallback Exacto de PDF

**Fecha:** 2026-09-22  
**Tipo:** Bug Fix / Sanitización / UI  
**Estado:** Completado  
**Rama:** `feature/dokploy-postgres-sync`  
**Modo Activo:** Ponytail Ultra & Caveman Ultra  

---

## 1. Contexto y Causa Raíz
- **Problema reportado:** En producción, la fila de `Puertos Mínimos` renderizaba `SI` en lugar de la descripción física de puertos extraída del PDF (`x2 USB 3.0; x4 USB 2.0; x1 RJ45; x3 Jacks`).
- **Causa raíz:**
  1. En los catálogos y tablas históricas de Perú Compras, el campo `conectividad_usb` almacena valores booleanos (`"SI"` / `"NO"`).
  2. En [`detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php), el extractor `$getSpecValue` y el fallback `?? $getProductValue(['conectividad_usb'])` capturaban `"SI"`, el cual era considerado válido por no estar vacío (`empty("SI") === false`).
  3. Esto bloqueaba la búsqueda profunda en especificaciones secundarias y el fallback de fábrica.
  4. La detección `$isDesktopOrWorkstation` fallaba si la relación `$producto->modelo->categoria_id` no venía precargada directamente en memoria.

---

## 2. Solución Aplicada
1. **Descarte Estricto de Booleanos (`$isInvalidPortValue`):**
   - Se rechaza explícitamente cualquier valor como `'SI'`, `'SÍ'`, `'NO'`, `'TRUE'`, `'FALSE'`, `'APLICA'`, `'CUMPLE'`, `'N/A'`, o cadenas de longitud < 4.
2. **Priorización de Especificaciones Físicas:**
   - Se recorre `$specsList` buscando campos de puertos con contenido descriptivo real (`usb`, `rj45`, `jack`, `hdmi`, etc.).
   - Si no hay campo nombrado, se escanea cualquier fila con el desglose físico.
3. **Limpieza de Prefijos y Notas al Pie:**
   - Se suprimen notas al pie (`podría integrar...`, superíndices `⁰ ¹ ² ³...`) y prefijos repetidos tipo `Puertos⁰` o `Puertos:`.
4. **Fallback Exacto de Ficha PDF:**
   - Fallback canónico para PCs y Workstations Kenya / EZENT:  
     `x2 USB 3.0; x4 USB 2.0; x1 RJ45; x3 Jacks`.
5. **Ajuste en `oldPcRows`:**
   - Se eliminó el fallback a `$getProductValue(['conectividad_usb'])` que forzaba el `"SI"`.

---

## 3. Archivos Modificados
- [`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)
- [`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)
- [`PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md`](file:///c:/xampp/htdocs/kenya_tienda/PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md)
- [`notes/INDEX.md`](file:///c:/xampp/htdocs/kenya_tienda/notes/INDEX.md)
