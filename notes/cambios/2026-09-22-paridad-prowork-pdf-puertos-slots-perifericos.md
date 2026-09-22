# 2026-09-22: Paridad 1:1 en Fichas ProWork y Ezent (Puertos, Slots, Periféricos y Certificaciones)

**Fecha:** 2026-09-22  
**Tipo:** Bug Fix / Parser PDF / Vista Web / Paridad de Datos  
**Estado:** Completado  
**Rama:** `feature/dokploy-postgres-sync`  
**Modo Activo:** Ponytail Ultra & Caveman Ultra  

---

## 1. Contexto y Diagnóstico

### Comparativa: Vista Tienda Kenya vs. PDF Oficial Perú Compras (ProWork)
1. **Puertos Mínimos:**
   - *Anterior en web:* Fallback genérico de Ezent (`x2 USB 3.0; x4 USB 2.0; x1 RJ45; x3 Jacks`).
   - *PDF oficial:* `Frontales: x1 USB 3.0; x2 USB 2.0; x1 Line-In; x1 MIC-In | Posteriores: x2 USB 3.2; x2 USB 2.0; x1 PS2 opcional; x3 Jack Audio; x1 HDMI; x1 DisplayPort`.
   - *Causa raíz:* En ProWork la tabla inicia con `Puertos Mínimos²` y el encabezado `Comentarios` (`²Puertos Mínimos podría integrar más Puertos y/o Slots`) no se podaba porque la regex anterior buscaba solo `Modelo|Chasis|Procesador`. El parser tomaba la nota al pie con `podría integrar` y activaba el fallback.
2. **Ranuras de Expansión Mínimos / Slot de Expansión:**
   - *Anterior en web:* `No especificado`.
   - *PDF oficial:* `x1 PCIe; x1 M.2`.
   - *Causa raíz:* En ProWork la etiqueta es `Ranuras de Expansión Mínimos²`. Faltaban tokens con y sin tildes/espacios (`RANURAS DE EXPANSIÓN MÍNIMOS`, `RANURAS DE EXPANSION MINIMOS`, `SLOT DE EXPANSIÓN MÍNIMOS`), y la limpieza de prefijos `Mínimos²` o `?`.
3. **Seguridad y Periféricos (Teclado y Mouse):**
   - *Anterior en web:* `Seguridad: TPM 2.0 Teclado Multimedia en Español con retroiluminación... Mouse Ergonómico...` (absorbido en un solo bloque).
   - *PDF oficial:* Filas separadas e independientes:
     - `Seguridad: TPM 2.0`
     - `Teclado: Multimedia en Español con retroiluminación y efectos adaptables`
     - `Mouse: Ergonómico, Sensor Óptico y Scroll`
   - *Causa raíz:* No existían tokens `TECLADO` ni `MOUSE` en `PDF_SPEC_TOKENS`, no estaban en `allowedSpecKeysByCategory('PC')`, y la vista `$oldPcRows` no los renderizaba.
4. **Garantía de Fábrica:**
   - *PDF oficial:* `36 Meses On Site`.
   - *Causa raíz:* La etiqueta en ProWork es `Garantía de Fabrica³` (sin tilde en fábrica). El token anterior `GARANTÍA DE FÁBRICA` (con tilde) dejaba `de Fabrica³` dentro del valor.
5. **Certificaciones:**
   - *PDF oficial:* `FCC; CE; ROHS`.
   - *Causa raíz:* Superíndice `⁴` y residuo `es` al matchear `Certificación` antes de `Certificaciones`.

---

## 2. Solución Aplicada

1. **[`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php):**
   - **Poda universal de Comentarios:** Regex ampliada para cortar hasta `Numero de Parte` o `Marca Registrada`, podando las notas al pie sin importar si la tabla inicia con `Puertos Mínimos` o `Modelo`.
   - **Nuevos Tokens:** Agregados `TECLADO`, `MOUSE`, `RANURAS DE EXPANSIÓN MÍNIMOS`, `RANURAS DE EXPANSION MINIMOS`, `SLOT DE EXPANSIÓN MÍNIMOS`, `GARANTÍA DE FABRICA`, `CERTIFICACIÓN⁴`, `CERTIFICACION⁴`.
   - **Categoría PC:** Incorporados `teclado` y `mouse` en `allowedSpecKeysByCategory('PC')`.
   - **Normalización (`normalizePcSpecs`):**
     - Desacople y recorte de periféricos en `seguridad`.
     - Limpieza de prefijo `Mínimos²` y caracteres no alfanuméricos en `slot_expansion`.
     - Limpieza de prefijos `de Fabrica³` en `garantia_de_fabrica`.
     - Limpieza de superíndices `⁴` / `?` y corrección de regex en `certificaciones`.
     - Formateo multi-línea con ` | ` en `puertos_minimos`.

2. **[`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php):**
   - **Desacoplamiento dinámico en frontend:** Si la base de datos contiene registros anteriores donde `seguridad` absorbió Teclado y Mouse, la vista los extrae y separa al vuelo.
   - **Filas dedicadas en `$oldPcRows`:** Incorporadas filas para `Teclado` y `Mouse` cuando existan datos.
   - **Sanitización de `$slotRaw`:** Búsqueda flexible de ranuras de expansión y eliminación de prefijos residuales.
   - **Limpieza de superíndices y prefijos:** En garantía, certificaciones y puertos mínimos.
   - **Render multi-línea estético:** Conversión de ` | ` a `\n` mediante `nl2br(e(...))` para respetar la estructura de frontales y posteriores del PDF.

---

## 3. Verificación
- Validada extracción con script de prueba en PHP: paridad 1:1 de todos los campos extraídos frente a la imagen del PDF oficial de Perú Compras.
- Compilación de Blade validada sin errores sintácticos.
