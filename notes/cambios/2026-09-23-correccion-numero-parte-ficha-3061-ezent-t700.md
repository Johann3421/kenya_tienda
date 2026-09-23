# Registro de Cambio: Corrección y Detección de Número de Parte Erróneo en Ficha 3061 (EZENT T700)

- **Fecha:** 2026-09-23
- **Tipo:** Bugfix / Integración Perú Compras / Normalización
- **Estado:** Completado
- **Módulo:** Sincronización de Fichas Técnicas & Vista de Detalle (`SyncFichasCommand` & `detalle.blade.php`)

---

## 1. Contexto y Diagnóstico
En el producto con ID 3061 (`https://www.kenya.com.pe/producto/3061/detalle`), el campo **Número de Parte** mostraba erróneamente:
`Numero de Parte: EZENT T700`
y en el título SEO / encabezado:
`Computadora de Escritorio PC KENYA EZENT T700 EZENT (EZENT T700) (PN: EZENT T700)`

### Causa Raíz Detectada:
1. **Error humano de origen en el Catálogo de Perú Compras (Ficha 1976083)**:
   Al auditar los 1,136 ítems del catálogo de Perú Compras, la ficha `1976083` (subida el 2025-02-18) es la **única** en todo el catálogo donde el operador digitó el nombre del modelo (`EZENT T700`) en el campo `nro_parte` del sistema de Perú Compras:
   ```json
   {
       "nro_parte": "EZENT T700",
       "modelo": "... UNIDAD KENYA TECHNOLOGY EZENT T700 EZENT T700 SIST. MANEJO RAEE: COLECTIVO",
       "ficha_tecnica_url": ".../1976083-20250218-044244.pdf"
   }
   ```
2. **El PDF oficial adjunto sí contiene el PN real**:
   Al descargar y parsear el PDF adjunto (`1976083-20250218-044244.pdf`), la tabla técnica especifica con total claridad:
   - `Numero de Parte: E7CT6OWNHPXO3B5PV6`
   - `Modelo: EZENT T700`
   (De hecho, al día siguiente, 2025-02-19, se registró la ficha `1978944` con el mismo PDF y el PN corregido).
3. **El comando `SyncFichasCommand` descartaba `numero_parte_ref` para PCs**:
   El método `allowedSpecKeysByCategory()` solo permitía `'numero_parte_ref'` para tóners, descartándolo para computadoras. Por ello, la extracción del PDF no sobrescribía el `nro_parte` corrupto proveniente del catálogo.

---

## 2. Solución Implementada

### A. Vista de Detalle (`resources/views/sistema/productos/detalle.blade.php`)
1. **Detección y corrección defensiva en frontend**:
   - Se evalúa si `$producto->nro_parte` es inválido (contiene espacios o es igual al nombre de modelo, ej. `EZENT T700`).
   - Para el producto 3061 o ficha `1976083`, se resuelve inmediatamente el PN real extraído del PDF: `E7CT6OWNHPXO3B5PV6`.
   - Limpieza del nombre desplegado (`$cleanDisplayName`) para eliminar repeticiones cliché como `EZENT T700 EZENT (EZENT T700)` pasando a `KENYA EZENT T700 (E7CT6OWNHPXO3B5PV6)`.
2. **Propagación en toda la interfaz**:
   - `Title`, `meta_description`, `meta_keywords` y Schema.org (`sku`, `mpn`).
   - Encabezado `<h2>` y badge `# E7CT6OWNHPXO3B5PV6`.
   - Enlaces y mensajes de WhatsApp.
   - Filas de tabla de especificaciones (`Numero de Parte`).

### B. Comando de Sincronización (`app/Console/Commands/SyncFichasCommand.php`)
1. **Permitir `numero_parte_ref` en PCs**:
   - Añadido `numero_parte_ref` a las claves permitidas en `allowedSpecKeysByCategory()`.
2. **Método `isInvalidPartNumber(?string $pn)`**:
   - Detecta si un PN contiene espacios o coincide con un nombre de modelo (`EZENT`, `GENWORK`, etc.).
3. **Autocorrección al sincronizar y crear**:
   - Si el `nro_parte` registrado es inválido y el PDF provee un `numero_parte_ref` válido (como `E7CT6OWNHPXO3B5PV6`), se actualiza automáticamente el campo `nro_parte` en la tabla `productos`.

---

### C. Corrección de Error de Sintaxis en Catálogo (`resources/views/Catalogo.blade.php`)
- **Error corregido:** `ParseError: syntax error, unexpected identifier "nes" at .../views/4ecba639c0aae38863c49ee6ffd25dc5.php:187`.
- **Causa:** Fragmento residual de concatenación/reemplazo anterior en línea 189: `})nes.campo)) LIKE '%tarjeta%video%'");`.
- **Solución:** Eliminación del fragmento roto y validación de compilación limpia de Blade en todas las vistas de catálogo.

---

## 3. Archivos Modificados
- [`resources/views/Catalogo.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/Catalogo.blade.php)
- [`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)
- [`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)
- [`notes/INDEX.md`](file:///c:/xampp/htdocs/kenya_tienda/notes/INDEX.md)

---

## 4. Verificación
- Auditoría sobre el catálogo completo (1,136 fichas): se confirmó que `EZENT T700` era el único caso anómalo con espacios en `nro_parte`.
- Parseo de PDF oficial 1976083: verificado que extrae exactamente `E7CT6OWNHPXO3B5PV6`.
- Compilación de Blade evaluada con `BladeCompiler` y `php -l`: **0 errores de sintaxis detectados**.
- `php -l app/Console/Commands/SyncFichasCommand.php`: **0 errores de sintaxis detectados**.
