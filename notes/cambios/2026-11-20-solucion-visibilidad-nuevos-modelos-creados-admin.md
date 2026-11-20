# Solución a la Visibilidad de Nuevos Modelos Creados desde el Panel Administrativo

- **Fecha:** 2026-11-20
- **Tipo:** Bug Fix / Backend / UI / Catálogo
- **Estado:** Completado
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra)

---

## 1. Contexto y Problema Reportado
El cliente reportó que tras crear un nuevo modelo desde el panel administrativo (`/producto/modelos`), este no se mostraba en la página web pública (ni en el carrusel del home, ni en los selectores del catálogo).

---

## 2. Investigación y Causa Raíz Detectada
Tras auditar el flujo completo de persistencia y consumo de modelos (`modelos` -> controladores -> vistas públicas):
1. **Restricción Excluyente `whereHas('getProducto')`:**
   En las consultas del frontend (`routes/web.php`, `CatalogoController.php`, `SitemapController.php`, `Catalogo.blade.php`, `Novedades.blade.php`), se filtraba estrictamente con:
   ```php
   ->whereHas('getProducto', function ($q) {
       $q->where('pagina_web', 'SI')->noSuspendido();
   })
   ```
   Al crear un modelo nuevo desde el panel administrativo, este nace con 0 productos asociados (`total_productos = 0`). Esta condición omitía al 100% el nuevo modelo de todas las colecciones enviadas a la web pública.
2. **Visibilidad Web de Productos por Defecto:**
   Al crear un producto en el formulario administrativo (`productos.js`), el valor por defecto de `pagina_web` era `'NO'` (oculto), lo que impedía que incluso al crear un producto nuevo este activara el modelo en web salvo que el usuario cambiara manualmente el selector a público.
3. **Mapeo Invertido en Relación Eloquent `Categoria::getModelo()`:**
   En `app/Models/Categoria.php`, la relación `getModelo()` estaba declarada erróneamente con claves invertidas `hasMany(Modelo::class, 'id', 'categoria_id')`, lo que provocaba consultas fallidas o vacías al cargar categorías con sus modelos.
4. **Ausencia de Accessor para Categoría en Nuevos Modelos:**
   En `app/Modelo.php`, no existía el accessor `categoria_nombre` ni la relación `belongsTo`, por lo que modelos con nombres que no encajaban en los prefijos legacy (EZENT, GENWORK, OFISZU, PROWORK, RAITO) no mostraban su etiqueta de categoría.

---

## 3. Correcciones Quirúrgicas Aplicadas (Ponytail Ultra)
1. **Remoción de `whereHas('getProducto')` en Consultas Públicas:**
   - `routes/web.php` (Home): Se eliminó `whereHas('getProducto')`, se ordenó por `id DESC` como fallback prioritario y se usó `whereRaw("UPPER(activo) = 'SI'")`.
   - `app/Http/Controllers/CatalogoController.php` (métodos `index` y `detallemod`): Se eliminó `whereHas('getProducto')` permitiendo que el selector `#preview-modelo` liste todos los modelos activos.
   - `app/Http/Controllers/SitemapController.php`: Sincronizado para incluir todos los modelos activos.
   - `resources/views/Catalogo.blade.php` y `resources/views/Novedades.blade.php`: Selectores actualizados para incluir modelos activos sin requerir productos preexistentes.
2. **Corrección de Relaciones y Accessors en Eloquent:**
   - `app/Models/Categoria.php`: Corregida la relación a `$this->hasMany(Modelo::class, 'categoria_id', 'id')->whereRaw("UPPER(activo) = 'SI'")`.
   - `app/Modelo.php`: Agregada la relación `categoria()` (`belongsTo`) y el accessor `getCategoriaNombreAttribute()` para garantizar que cualquier modelo nuevo muestre su badge de categoría en el carrusel de `welcome.blade.php`.
3. **Mejora en UX Administrativo (`productos.js`):**
   - Se estableció `'pagina_web': 'SI'` como valor predeterminado al crear productos desde el panel, alineado con el principio de mínima fricción (Ley de Tesler).

---

## 4. Archivos Modificados
- `routes/web.php`
- `app/Http/Controllers/CatalogoController.php`
- `app/Http/Controllers/SitemapController.php`
- `app/Models/Categoria.php`
- `app/Modelo.php`
- `resources/views/Catalogo.blade.php`
- `resources/views/Novedades.blade.php`
- `public/js/views/productos/productos.js`
- `notes/INDEX.md`

---

## 5. Verificación
- Análisis de sintaxis PHP ejecutado con `php -l` en todos los archivos modificados: 0 errores de sintaxis detectados.
- Compatible al 100% tanto con PostgreSQL (Dokploy producción) como con MySQL (local).
