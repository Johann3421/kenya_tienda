# 2026-09-23: Resolución Definitiva en Extracción PDF ProWork (Poda sin anclaje, Tokens sin espacio y Reemplazo de Booleanos)

**Fecha:** 2026-09-23  
**Tipo:** Bug Fix / Parser PDF / Lógica de Negocio / UI  
**Estado:** Completado  
**Rama:** `feature/dokploy-postgres-sync`  
**Modo Activo:** Ponytail Ultra & Caveman Ultra  

---

## 1. Contexto y Diagnóstico (Caso Producto 3759 - `PU9K21WD13`)

Al inspeccionar la página en vivo `https://www.kenya.com.pe/producto/3759/detalle` y auditar los datos reales de la API y el PDF:

1. **La poda `^\s*Comentarios\b` fallaba:**
   - En el PDF de ProWork WS90 (`PU9K21WD13`), el documento incluye 9 líneas de texto comercial previo ("La Solución más Potente y segura para alto rendimiento... PSU80Plus") antes del cuadro `Comentarios`.
   - Al usar `^\s*Comentarios`, la regex exigía que el texto empezara estrictamente con "Comentarios", por lo que nunca hacía match.
   - El token `RENDIMIENTO` coincidía con "alto rendimiento" en la portada, y `PUERTOS` capturaba el texto de la nota al pie `² Puede integrar mas Puertos y/o Slots...`. Al quedar registrado primero, bloqueaba la fila real de puertos.
2. **Tokens de Ranuras de Expansión concatenados:**
   - En el PDF, Smalot extrae `Ranurasde ExpansiónMínimos²x1 PCIe; x1 M.2` sin espacios entre `Expansión` y `Mínimos`.
   - El negative lookahead `(?![A-Z])` descartaba el match de `EXPANSIÓN` porque estaba inmediatamente pegado a la `M` de `Mínimos`.
3. **Bloqueo de enriquecimiento por valores booleanos del API (`TECLADO: SI`, `MOUSE: SI`):**
   - La API de catálogo de Perú Compras trae en su descripción: `TECLADO: SI MOUSE: SI`.
   - En `enrichSpecsFromPdfIfNeeded()`, la condición `if (empty($specs[$key]) && !empty($value))` evaluaba `empty('SI') === false`, por lo que el PDF **nunca** sobreescribía los valores con las especificaciones ricas (`Multimedia en Español...`).
4. **Duplicación de periféricos en `Accesorios`:**
   - Si una PC ProWork ya tenía `teclado`, `mouse` y `otros` del PDF, `normalizePcSpecs()` inyectaba de todas formas el fallback `Teclado, Mouse, Cable de Poder...` en `accesorios`, duplicando los periféricos.

---

## 2. Solución de Arquitectura Aplicada

1. **[`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php):**
   - **Poda por offset sin anclaje:** Se localiza el inicio de la tabla técnica buscando `Numero de Parte` (o `Modelo`/`Procesador`) mediante `preg_match(..., PREG_OFFSET_CAPTURE)` y se corta mediante `substr()`. Se poda toda la portada comercial y las notas al pie sin importar qué texto haya antes.
   - **Separación de mayúsculas concatenadas:** `preg_replace('/([a-zñáéíóú])([A-ZÁÉÍÓÚ])/u', '$1 $2', $text)` para separar palabras pegadas como `ExpansiónMínimos`.
   - **Nuevos Tokens:** Agregadas variantes sin espacios (`RANURASDE EXPANSIÓNMÍNIMOS`, `RANURAS DE EXPANSIÓNMÍNIMOS`, `RANURAS`, etc.).
   - **Enriquecimiento sobre booleanos:** `enrichSpecsFromPdfIfNeeded()` reemplaza cualquier valor vacío o booleano (`SI`, `NO`, `N/A`, etc.) por el valor descriptivo oficial del PDF.
   - **Prevención de duplicados en accesorios:** Solo se inyecta fallback de accesorios si `teclado` y `otros` no existen.

2. **[`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php):**
   - **Filtro de booleanos en vista:** `$isBoolVal` evita renderizar filas con valores planos como `SI` o `NO` para Teclado y Mouse.
   - **Fallback condicional:** Se evita inyectar la fila de accesorios genéricos si ya existen `teclado` u `otros`.

---

## 3. Verificación
- Simulación completa ejecutada con el PDF oficial y descripción real del API para el producto `PU9K21WD13`:
  - `puertos_minimos`: `Frontales: x1 USB 3.0; x2 USB 2.0; x1 Line-In; x1 MIC-In | Posteriores: x2 USB 3.2; x2 USB 2.0; x1 PS2 opcional; x3 Jack Audio; x1HDMI; x1 Display Port`
  - `slot_expansion`: `x1 PCIe; x1 M.2`
  - `teclado`: `Multimedia en Español con retroiluminación y efectos adaptables`
  - `mouse`: `Ergonómico, Sensor Óptico y Scroll`
  - `otros`: `Manual, Drivers, Cable de poder, Términos de Garantía`
  - `accesorios`: No duplicado.
- Sintaxis y Blade validados sin errores.
