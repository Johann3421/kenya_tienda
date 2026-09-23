# Registro de Cambio: Desacoplamiento de Slot de Expansión y Fuente de Poder en Fichas EZENT (Producto 2669)

- **Fecha:** 2026-09-23
- **Tipo:** Bugfix / Extracción PDF / Frontend Resiliente
- **Estado:** Completado
- **Módulo:** Sincronización de Fichas Técnicas & Vista de Detalle (`SyncFichasCommand` & `detalle.blade.php`)

---

## 1. Contexto y Diagnóstico
En la vista de detalle del producto 2669 (`https://www.kenya.com.pe/producto/2669/detalle` - COMPUTADORA KENYA EZENT V1_MT):
- **Fuente de Poder** en tarjeta superior y tabla técnica mostraba: `No especificado`.
- **Slot de Expansión** en la tabla técnica mostraba: `No especificado`.
- **Puertos Mínimos** mostraba el texto inflado:
  `Frontal:x2USB2.0,x1Line IN,x1Line MIC | Posterior:x2USB2.0,x2USB3.0,x1RJ45,x1PS2opcional, x1Line IN/OUT,x1Line MIC,x1VGA,x1HDMI Slotde Expansión⁰ x1PCIe,x1M.2 Fuentede Poder ATX600W-220V`

### Causa Raíz Detectada en el PDF Oficial:
1. **Falta de espacios en preposiciones unidas generadas por el parser (`Smalot\PdfParser`)**:
   El PDF técnico (`soporte_2669_PxIJH8fL43.pdf`) extrajo:
   `SlotdeExpansión⁰ x1PCIe,x1M.2`
   `FuentedePoder ATX600W-220V`
   La regex previa solo separaba `([a-z])([A-Z])`. Al unirse con `de` (`Slotde` o `Fuentede`), la letra `t` o `e` es minúscula y `d` también es minúscula, por lo que resultaba en `Slotde Expansión⁰` y `Fuentede Poder`.
2. **Colisión de tokens y superíndices**:
   El token en el mapa era `SLOT DE EXPANSIÓN` y `FUENTE DE PODER`, mientras que el texto extraído contenía `SLOTDE` y `FUENTEDE` junto a superíndices como `⁰`. Al no coincidir los tokens, el parser no detectaba los campos y los absorbía enteros como parte del valor de `Puertos Mínimos`.
3. **Inicio de tabla técnica pegado (`NumerodeParte`)**:
   La cabecera de la tabla contenía `NumerodeParte` sin espacios, por lo que la regex de recorte inicial no lo detectaba en el primer intento y arrastraba notas preliminares de comentarios.

---

## 2. Solución Implementada

### A. Comando de Sincronización (`app/Console/Commands/SyncFichasCommand.php`)
1. **Normalización robusta de preposiciones/conjunciones pegadas**:
   - `/\b(Slot|Fuente|Numero|Número|Nro|Factor|Ranuras?|Sistema|Garant[ií]a|Cable|T[eé]rminos)de\b/iu` -> `$1 de`.
   - `/\b(Accesorios)y\b/iu` -> `$1 y`.
   - `/\b([A-Za-zñáéíóú]{3,})de([A-ZÁÉÍÓÚ])/u` -> `$1 de $2`.
   - `/\b([A-Za-zñáéíóú]{3,})y([A-ZÁÉÍÓÚ])/u` -> `$1 y $2`.
2. **Tokens variantes tolerantes a ausencia de espacios y fuentes alternativas**:
   - Agregados `SLOTDE EXPANSIÓN`, `SLOTDE EXPANSION`, `FUENTEDE PODER`, `FUENTE DE ALIMENTACION`, etc.
3. **Desacoplamiento defensivo en `normalizePcSpecs`**:
   - Si `puertos_minimos` absorbió `Slot de Expansión` o `Fuente de Poder`, se extraen automáticamente hacia sus respectivas claves (`slot_expansion` y `fuente_poder`) y se poda el exceso de `puertos_minimos`.
4. **Sanitización de `fuente_poder` y `sanitizeSpecValue`**:
   - Limpieza de superíndices/notas al pie iniciales (incluyendo `?` residuales de decodificación).

### B. Vista de Detalle (`resources/views/sistema/productos/detalle.blade.php`)
1. **Desacoplamiento en frontend antes de construir `$topOrdered`**:
   - Se obtiene `$slotRaw` con anterioridad.
   - Si `$puertosRaw` contiene `Slot de Expansión` o `Fuente de Poder` debido a sincronizaciones previas en la BD, se extraen hacia `$slotRaw` y `$fuenteRaw` y se podan de `$puertosRaw`.
2. **Propagación**:
   - Tarjeta superior `#design-v2`: `FUENTE PODER` muestra `ATX600W-220V`.
   - Tabla de especificaciones:
     - `Puertos Mínimos`: puertos reales frontal y posterior limpios.
     - `Slot de Expansión`: `x1PCIe,x1M.2`.
     - `Fuente de Poder`: `ATX600W-220V`.

---

## 3. Archivos Modificados
- [`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)
- [`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)
- [`notes/INDEX.md`](file:///c:/xampp/htdocs/kenya_tienda/notes/INDEX.md)

---

## 4. Verificación
- Prueba con el binario real del PDF de la ficha 2669 (`soporte_2669_PxIJH8fL43.pdf`):
  - `puertos_minimos`: `Frontal:x2USB2.0,x1Line IN,x1Line MIC Posterior:x2USB2.0,x2USB3.0,x1RJ45,x1PS2opcional, x1Line IN/OUT,x1Line MIC,x1VGA,x1HDMI`
  - `slot_expansion`: `x1PCIe,x1M.2`
  - `fuente_poder`: `ATX600W-220V`
- Simulación de render de `detalle.blade.php`: valores correctos en tarjeta superior y filas de tabla.
- Compilación de Blade y linter PHP: **0 errores**.
