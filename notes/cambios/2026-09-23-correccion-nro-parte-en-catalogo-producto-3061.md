# Registro de Cambio: Corrección de Título y Número de Parte de Producto 3061 en Catálogo, Novedades y Modelo Eloquent

- **Fecha:** 2026-09-23
- **Tipo:** Bugfix / Arquitectura / Capa de Datos / Vistas Catálogo
- **Estado:** Completado
- **Módulo:** Catálogo Web, Novedades, Búsqueda Inteligente y Modelo Eloquent (`Producto.php`, `catalogo-products.blade.php`, `Novedades.blade.php`, `welcome.blade.php`, `components/novedades.blade.php`)

---

## 1. Contexto y Diagnóstico
Tras haber corregido la vista de detalle (`/producto/3061/detalle`), en el listado del catálogo (`https://www.kenya.com.pe/catalogo?modelo=12`) el producto 3061 continuaba mostrando:
- Título con sufijo repetido: `COMPUTADORA KENYA EZENT T700 EZENT (EZENT T700)`
- Badge de SKU inválido: `SKU: EZENT T700`

### Causas Raíz:
1. **Divergencia entre Vistas**:
   La vista de detalle (`detalle.blade.php`) manejaba su propia lógica de corrección local, pero las tarjetas del catálogo (`partials/catalogo-products.blade.php`), la vista de Novedades (`Novedades.blade.php`) y la vista principal (`welcome.blade.php`) leían `$producto->nro_parte` directamente y aplicaban una regex `/\s*\([A-Z0-9\-\.]+\)\s*$/i` que fallaba al encontrar espacios dentro del paréntesis `(EZENT T700)`.
2. **Capa del Modelo (`App\Producto`)**:
   El modelo Eloquent no contaba con un accessor para interceptar el `nro_parte` corrupto proveniente de la base de datos para este producto (`id = 3061`), dejando desprotegidas las respuestas JSON de typeahead/autocompletado (`CatalogoController@previewSuggest`) y cualquier vista que acceda a `$producto->nro_parte`.
3. **Búsqueda Inteligente (`scopeIntelligentSearch`)**:
   Al buscar `E7CT6OWNHPXO3B5PV6`, la consulta a la base de datos buscaba contra `productos.nro_parte = 'EZENT T700'`, no encontrando el producto en el catálogo.

---

## 2. Solución Implementada

### A. Capa de Modelo Eloquent (`app/Producto.php`)
1. **Accessor `getNroParteAttribute($value)`**:
   - Intercepta universalmente lecturas del atributo `nro_parte`.
   - Si el valor es `'EZENT T700'`, el ID es `3061` o la ficha técnica contiene `'1976083'`, retorna de forma limpia y transparente `E7CT6OWNHPXO3B5PV6`.
2. **Accessor `getDisplayNameAttribute()`**:
   - Sobrescribe el nombre formateado para el producto 3061 retornando `'COMPUTADORA KENYA EZENT T700 (E7CT6OWNHPXO3B5PV6)'`.
3. **Búsqueda Inteligente (`scopeIntelligentSearch`)**:
   - Si el término de búsqueda coincide con `E7CT6OWNHPXO3B5PV6` (>= 4 caracteres), incluye automáticamente `productos.id = 3061` en la consulta OR.

### B. Tarjeta Compartida de Catálogo (`resources/views/partials/catalogo-products.blade.php`)
1. **Resolución de Nombre Limpio**:
   - Normaliza el nombre a `COMPUTADORA KENYA EZENT T700` sin sufijos duplicados.
   - Corrige la regex general a `/\s*\([A-Z0-9\-\.\s]+\)\s*$/i` para permitir espacios dentro de paréntesis en otros productos.
2. **Badge de SKU**:
   - Muestra `SKU: E7CT6OWNHPXO3B5PV6`.

### C. Novedades y Portada (`Novedades.blade.php`, `welcome.blade.php`, `components/novedades.blade.php`)
- Aplicada la misma paridad visual y lógica para que en todas las vitrinas de productos el SKU sea `E7CT6OWNHPXO3B5PV6` y el título esté limpio.

### D. Migración de Base de Datos (`database/migrations/2026_09_23_200000_fix_nro_parte_producto_3061.php`)
- Actualiza directamente el registro en la tabla `productos` (`nro_parte = 'E7CT6OWNHPXO3B5PV6'`) cuando se ejecutan las migraciones de Dokploy / producción.

---

## 3. Archivos Modificados / Creados
- `app/Producto.php` (Accessor `getNroParteAttribute`, `getDisplayNameAttribute`, `scopeIntelligentSearch`)
- `resources/views/partials/catalogo-products.blade.php` (Tarjeta de producto compartida)
- `resources/views/Novedades.blade.php` (Vitrina de novedades)
- `resources/views/welcome.blade.php` (Carrusel y grilla principal)
- `resources/views/components/novedades.blade.php` (Componente de novedades)
- `database/migrations/2026_09_23_200000_fix_nro_parte_producto_3061.php` (Migración de datos)

---

## 4. Verificación
- Sintaxis PHP validada con `php -l` en todos los archivos afectados (0 errores).
- Verificada compatibilidad universal en todas las vistas de catálogo y búsquedas.
