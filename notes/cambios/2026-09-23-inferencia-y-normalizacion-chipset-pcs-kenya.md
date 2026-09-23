# Registro de Cambio: Inferencia, Normalización y Sanitización de Chipset en Computadoras Kenya (Producto 2943)

- **Fecha:** 2026-09-23
- **Tipo:** Bugfix / Arquitectura / Normalización de Especificaciones
- **Estado:** Completado
- **Módulo:** Fichas Técnicas de PCs, Vista de Detalle, Modelo Eloquent y Comando de Sincronización (`detalle.blade.php`, `Producto.php`, `SyncFichasCommand.php`, `ProductoController.php`)

---

## 1. Contexto y Diagnóstico
En la vista de detalle del producto con ID 2943 (`https://www.kenya.com.pe/producto/2943/detalle`, modelo `EZENT V1_MT`, PN `ED7S03N1OT`), el campo **Chipset** mostraba:
- En tarjetas principales (`design-v2`): `CHIPSET: No especificado`
- En tabla completa de especificaciones: `Chipset: No especificado`

### Causas Raíz Detectadas:
1. **Omisión de origen en el PDF de Perú Compras (Ficha 1749400)**:
   Al parsear el PDF oficial adjunto (`1749400-20240913-112456.pdf`), el creador de la ficha técnica omitió por completo la fila `Chipset`, pasando directamente de `Sonido` a `Lan`.
2. **Ausencia de Chipset en descripción de Catálogo Perú Compras**:
   El string de descripción de Perú Compras nunca incluye `CHIPSET` en ninguna computadora Kenya.
3. **Omisión en `SyncFichasCommand::$fieldMap`**:
   La columna `productos.chipset` existía en la base de datos, pero en `SyncFichasCommand::mergeSpecs()` no estaba mapeada `'chipset' => 'chipset'`.
4. **Falta de Inferencia en la Vista de Detalle (`detalle.blade.php`)**:
   Campos como `formato`, `fuente_poder` y `puertos_minimos` contaban con inferencias inteligentes por modelo Kenya (`EZENT` -> `Mid Tower`, etc.), pero `chipset` carecía de fallback, arrojando directamente `'No especificado'`.

---

## 2. Solución Implementada

### A. Vista de Detalle (`resources/views/sistema/productos/detalle.blade.php`)
1. **Extracción y Sanitización Ampliada**:
   - Búsqueda por regex de `/^chipset[⁰¹²³\*°\?]?$/iu`, `/placa\s*madre/iu`, `/mainboard/iu`, `/motherboard/iu` o columna `chipset`.
   - Descarte de valores booleanos o nulos (`NO ESPECIFICADO`, `N/A`, `-`, etc.).
2. **Inferencia Inteligente por Plataforma / Procesador**:
   - Si una computadora no tiene chipset especificado, se analiza el procesador:
     - Plataforma Intel (Core i3, i5, i7, i9, etc.) -> `'Intel'`.
     - Plataforma AMD (Ryzen, Athlon) -> `'AMD'`.
3. **Aplicación en Todas las Capas Visuales**:
   - `topOrdered` (`design-v2`): muestra `Intel` en vez de `No especificado`.
   - `oldPcRows` (tabla inferior): muestra `Intel`.

### B. Modelo Eloquent (`app/Producto.php`)
- **Accessor `getChipsetAttribute($value)`**:
  Normaliza lecturas de `chipset`. Si en base de datos es `null` o `'No especificado'`, infiere de forma transparente `'Intel'` o `'AMD'` para computadoras de escritorio.

### C. Comando de Sincronización (`app/Console/Commands/SyncFichasCommand.php`)
1. **Mapeo a Base de Datos en `mergeSpecs`**:
   - Incorporado `'chipset' => 'chipset'` (junto con `'formato'`, `'sonido'`, `'fuente_poder'`, `'slot_expansion'`).
2. **Tokens de PDF**:
   - Añadidos tokens: `'PLACA MADRE'`, `'MAINBOARD'`, `'MOTHERBOARD'`, `'CHIP SET'`, `'CHIPSET:'`.
3. **Sanitización y Fallback en `normalizePcSpecs`**:
   - Limpieza de prefijos y superíndices. Si viene vacío en PCs Kenya, infiere `'Intel'` o `'AMD'`.

### D. Controlador Administrativo (`app/Http/Controllers/ProductoController.php`)
- Reconocimiento de `PLACA`, `MAINBOARD` y `MOTHERBOARD` mapeados al campo estándar `CHIPSET`.

### E. Migración de Datos (`database/migrations/2026_09_23_210000_fix_chipset_productos_pc.php`)
- Actualización retroactiva de `productos.chipset` en la base de datos para todas las PCs corporativas que tenían el campo vacío o como `'No especificado'`.

---

## 3. Archivos Modificados / Creados
- `resources/views/sistema/productos/detalle.blade.php`
- `app/Producto.php`
- `app/Console/Commands/SyncFichasCommand.php`
- `app/Http/Controllers/ProductoController.php`
- `database/migrations/2026_09_23_210000_fix_chipset_productos_pc.php`
