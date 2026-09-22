# 2026-09-22: Desagrupación y Sanitización de Empaque, Certificaciones, Sistema RAEE, Accesorios y Otros

**Fecha:** 2026-09-22  
**Tipo:** Bug Fix / Normalización de Especificaciones / UI  
**Estado:** Completado  
**Rama:** `feature/dokploy-postgres-sync`  
**Modo Activo:** Ponytail Ultra & Caveman Ultra  

---

## 1. Contexto y Caso Reportado
- **Producto:** `COMPUTADORA KENYA EZENT T700 (E7CD6OWNHPXO3B5PV6)` (y fichas PC de Perú Compras).
- **Problema:** En el bloque final de especificaciones técnicas:
  1. `Empaque`: Mostraba el texto genérico `"Empaque individual de fábrica"` en vez de `"En caja - Unidad"`.
  2. `Certificaciones`: Colapsó y acumuló múltiples apartados en una sola cadena ilegible (`"En caja - Unidad Certificación³ ROSH, FCC, CE Sistema de Manejo de RaeeColectivo"`).
  3. `Sistema de Manejo de Raee`: No se mostraba como fila independiente (campo faltante).
  4. `Accesorios y Otros`: Mostraba únicamente accesorios (`"Teclado, Mouse, Cable de Poder, Manuales, Drivers, Términos de Garantia"`), omitiendo el apartado `"Otros: Sistema de Enfriamiento por Flujo de Aire"`.
- **Causa raíz:**
  - **Falta de tokens singulares:** En el PDF oficial figura `Certificación³`, pero el tokenizador solo buscaba `CERTIFICACIONES` (plural). Al no encontrar token, el texto subsiguiente fue absorbido por `EMPAQUE`.
  - **Sobrescritura forzada en vista y comando:** Si `empaque` contenía `ROHS`/`CE`/`RAEE`, se transfería toda la cadena sucia a `certificaciones` y se reemplazaba `empaque` por `"Empaque individual de fábrica"`.
  - **Omisión de claves permitidas:** `allowedSpecKeysByCategory('PC')` en `SyncFichasCommand` descartaba `sistema_raee` y `otros` para computadoras.
  - **Ausencia de filas en `$oldPcRows`:** La vista solo renderizaba `Empaque`, `Certificaciones` y `Accesorios y Otros`, sin fila para `Sistema de Manejo de Raee` ni desglose para `Otros`.

---

## 2. Solución Implementada
1. **Vista de Detalle ([`detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)):**
   - **Desensamblaje Quirúrgico:** Algoritmo de extracción por expresiones regulares para desarmar blobs combinados preexistentes en BD:
     - `Empaque`: Extrae el texto previo a `Certificación` / `RAEE` (obteniendo `"En caja - Unidad"`).
     - `Certificaciones`: Extrae el tramo entre `Certificación` y `Sistema de Manejo de Raee` (obteniendo `"ROSH, FCC, CE"` y limpiando superíndices como `³`).
     - `Sistema de Manejo de Raee`: Extrae el valor posterior a `Sistema de Manejo de Raee` (obteniendo `"Colectivo"`).
   - **Desacoplamiento Estricto de Accesorios y Otros:** 
     - Exclusión de cualquier campo que contenga `"accesorio"` al buscar `"Otros"` (evitando que `Accesorios y Otros` alimente la variable `otrosRaw`).
     - Descarte automático si el candidato a `otrosRaw` contiene palabras clave de periféricos (`teclado`, `mouse`, `cable de poder`, `manuales`).
     - Extracción prioritaria de `"Sistema de Enfriamiento por Flujo de Aire"` desde specs, descripciones o fallback de fábrica Perú Compras.
     - Condicional estricto en `$oldPcRows` (`otrosRaw !== accesoriosRaw`) garantizando que `Accesorios` y `Otros` muestren valores independientes.
   - **Fila Independiente en `$oldPcRows`:**
     - Agregada fila para `Sistema de Manejo de Raee`.
     - Si existe `Otros`, renderiza `Accesorios` y `Otros` como filas separadas idénticas al PDF oficial.
   - **Fallbacks Oficiales de Fábrica:** Si los valores están vacíos para PCs Kenya de escritorio, se aplican los valores canónicos homologados en Perú Compras (`En caja - Unidad`, `ROSH, FCC, CE`, `Colectivo`, `Sistema de Enfriamiento por Flujo de Aire`).

2. **Sincronizador Backend ([`SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)):**
   - **Tokens de PDF Extendidos:** Incorporados `CERTIFICACIÓN`, `CERTIFICACIÓN³`, `CERTIFICACION`, `SISTEMA DE MANEJO DE RAEE`, `SISTEMA MANEJO RAEE`, `SIST. MANEJO RAEE`, `SISTEMA RAEE`, `ACCESORIOS` y `OTROS` en `PDF_SPEC_TOKENS`.
   - **Inclusión en Claves Permitidas:** Agregadas `sistema_raee`, `accesorios`, `otros` a `allowedSpecKeysByCategory('PC')`.
   - **Normalización de Specs:** Limpieza de blobs en `normalizePcSpecs()` para almacenar de forma desagregada y limpia.
   - **Etiquetas de Especificaciones:** Mapeadas en `SPEC_LABELS` para persistencia en tabla `especificaciones`.

3. **Controlador ([`ProductoController.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Http/Controllers/ProductoController.php)):**
   - Asignadas prioridades canónicas en `$specPriority` para `Empaque` (44), `Certificaciones` (45), `Sistema de Manejo de Raee` (46), `Accesorios` (47) y `Otros` (48).

4. **Prompt CEAM Auditor ([`PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md`](file:///c:/xampp/htdocs/kenya_tienda/PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md)):**
   - Sincronizados los diccionarios de tokens para `certificaciones`, `sistema_raee`, `accesorios` y `otros`.

---

## 3. Archivos Modificados
- [`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)
- [`app/Http/Controllers/ProductoController.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Http/Controllers/ProductoController.php)
- [`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)
- [`PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md`](file:///c:/xampp/htdocs/kenya_tienda/PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md)
- [`notes/INDEX.md`](file:///c:/xampp/htdocs/kenya_tienda/notes/INDEX.md)
