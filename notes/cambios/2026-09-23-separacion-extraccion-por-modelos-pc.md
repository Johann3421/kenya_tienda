# Registro de Cambio: Separación de Extracción y Presentación por Modelos de PC (EZENT, GENWORK, OFISZU, PROWORK)

- **Fecha:** 2026-09-23
- **Tipo:** Bugfix / Arquitectura / Normalización
- **Estado:** Completado
- **Módulo:** Sincronización de Fichas Técnicas & Vista de Detalle de Producto (`SyncFichasCommand` & `detalle.blade.php`)

---

## 1. Contexto y Diagnóstico
En la extracción de fichas técnicas de Perú Compras, se detectó una colisión grave en los modelos **EZENT** y **GENWORK**:
- El campo `Accesorios` de estas fichas contiene como valor de texto: `"Teclado, Mouse, Cable de Poder, Manuales, Drivers, Términos de Garantia"`.
- Al usar un diccionario de tokens estático y común a todos los modelos (que contenía `TECLADO` y `MOUSE`), el parser fragmentaba erróneamente el valor de accesorios:
  - Eliminaba `Accesorios`.
  - Asignaba a `Teclado` el residuo `","`.
  - Asignaba a `Mouse` el contenido `"Cable de Poder, Manuales, Drivers, Términos de Garantia"`.
- Por su parte, modelos como **OFISZU** y **PROWORK WS90** sí cuentan con filas independientes para `Teclado` y `Mouse` con descripciones extensas individuales, mientras que **PROWORK WS70** agrupa en `Accesorios`.

---

## 2. Taxonomía de Modelos Perú Compras Descubierta
1. **EZENT**:
   - Accesorios: `"Teclado, Mouse, Cable de Poder, Manuales, Drivers, Términos de Garantia"`.
   - Otros: `"Sistema de Enfriamiento por Flujo de Aire"`.
   - Sin campos independientes de `Teclado` ni `Mouse`.
2. **GENWORK**:
   - Accesorios: `"Teclado, Mouse, Cable de Poder, Manuales, Drivers, Términos de Garantia"`.
   - Sin campos independientes de `Teclado` ni `Mouse`.
3. **OFISZU**:
   - Teclado y Mouse independientes y descriptivos (ej. `Teclado: Español latinoamericano...`, `Mouse: Óptico 1000 DPI...`).
   - Sin campo `Accesorios`.
   - Otros: `"Manuales, Drivers, Certificado de Garantía"`.
4. **PROWORK**:
   - WS90: Teclado y Mouse descriptivos independientes, Otros (`Manuales, Drivers...`), sin Accesorios.
   - WS70: Periféricos agrupados en `Accesorios`, Otros (`Sistema de Enfriamiento...`).

---

## 3. Modificaciones Realizadas

### A. Comando de Sincronización (`app/Console/Commands/SyncFichasCommand.php`)
1. **Método `getPdfTokensForModel(string $modelGroup, string $pdfText): array`**:
   - Provee diccionarios de tokens especializados según el modelo inferido o explícito:
     - `EZENT` y `GENWORK`: No incluyen tokens `TECLADO` ni `MOUSE`. Conservan `ACCESORIOS` y `OTROS` intactos.
     - `OFISZU`: Incluye `TECLADO`, `MOUSE`, `RANURAS DE EXPANSIÓN MÍNIMOS`, `DISIPACIÓN DE CALOR`, omitiendo `ACCESORIOS`.
     - `PROWORK`: Evalúa dinámicamente si el PDF contiene `ACCESORIOS` (WS70) o ranuras/teclado individuales (WS90).
2. **Inferencia Automática de Modelo en PDF**:
   - Si no se pasa el grupo de modelo, se infiere del contenido del PDF (`Modelo EZENT...` o por prefijo de PN: `E` → EZENT, `G` → GENWORK, `P` → PROWORK, `KOT` → OFISZU).
3. **Normalización por Modelo en `normalizePcSpecs()` y `syncEspecificaciones()`**:
   - Se asegura que en EZENT y GENWORK se eliminen claves residuales de `teclado` y `mouse`, consolidando todo en `accesorios`.

### B. Vista Web (`resources/views/sistema/productos/detalle.blade.php`)
1. **Validación Defensiva `isInvalidPeripheral()`**:
   - Descarta de inmediato cualquier valor de `Teclado` o `Mouse` que contenga frases como `"Cable de Poder, Manuales, Drivers..."` o valores booleanos.
2. **Detección de Modelo en la Vista**:
   - Para EZENT y GENWORK: suprime filas de `Teclado` y `Mouse`, garantizando la fila de `Accesorios` y el campo `Otros` recortado en `"Sistema de Enfriamiento por Flujo de Aire"`.
   - Para OFISZU y PROWORK WS90: renderiza `Teclado` y `Mouse` individuales descriptivos y omite `Accesorios`.

---

## 4. Archivos Modificados
- [`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)
- [`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)
- [`notes/INDEX.md`](file:///c:/xampp/htdocs/kenya_tienda/notes/INDEX.md)

---

## 5. Verificación
- Probado contra PDFs reales de Perú Compras:
  - EZENT (`E7C1B69NWVHDT`, `E7CD6OWNHPXO3B5PV6`): Accesorios intacto con todos los periféricos, Otros = `"Sistema de Enfriamiento por Flujo de Aire"`, 0 filas falsas de Mouse/Teclado.
  - GENWORK (`GC7XDQUW203-V12`): Accesorios intacto con periféricos, 0 colisiones.
  - OFISZU (`KOT7S20W11B3`): Teclado y Mouse descriptivos extraídos individualmente, sin campo fantasma de Accesorios.
  - PROWORK (`P7CDDDWNHAXV3BLPV8`, `PU9K21WD13`): WS90 y WS70 discriminados con precisión.
