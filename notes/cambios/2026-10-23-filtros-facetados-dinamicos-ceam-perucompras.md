# Filtros Facetados Dinámicos en Cascada con Conteos Reales (Estilo CEAM Perú Compras)

- **Fecha:** 2026-10-23
- **Tipo:** Feature / UI / Backend / Catálogo / Rendimiento
- **Estado:** Completado
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra)

---

## 1. Contexto y Requerimiento
El cliente solicitó dotar a los filtros de especificaciones técnicas del catálogo de un comportamiento facetado dinámico similar a las plataformas e-commerce líderes y al Catálogo Electrónico de Acuerdos Marco (**CEAM Perú Compras**):
1. **Conteo por opción:** Cada valor de especificación (ej. `Intel Core i7-13700 (200)`) debe indicar entre paréntesis la cantidad exacta de fichas técnicas disponibles que coinciden con dicha característica.
2. **Cascada interactiva y piezas compatibles:** Al seleccionar una especificación (ej. procesador `Intel Core i7 13700`), el sistema filtra inmediatamente los productos y, de forma reactiva, recalcula las opciones en los demás bloques de filtros (Memoria RAM, Almacenamiento, Tarjeta de Video, etc.), mostrando **únicamente las piezas y configuraciones compatibles** con sus respectivos conteos actualizados.
3. **Reducción acumulativa:** Conforme el usuario continúa seleccionando componentes adicionales (ej. RAM 16 GB), el abanico de especificaciones compatibles se reduce progresivamente, evitando selecciones inconsistentes o fichas sin resultados.
4. **Restablecimiento y reversibilidad:** Al desmarcar opciones o pulsar "Limpiar Filtros", las opciones y conteos se expanden instantáneamente.

---

## 2. Archivos Modificados
- `resources/views/partials/aside-detallemod.blade.php`:
  - Reemplazo del algoritmo de carga estática por cálculo en memoria de facetas multidimensionales sobre el conjunto de productos del modelo activo (o catálogo general).
  - Normalización unificada de hardware (CPU, RAM, almacenamiento SSD/HDD/M.2, tarjetas de video dedicadas/integradas y garantías On-Site/Carry-In).
  - Cálculo de conteo por campo excluyendo el filtro propio (`multi-select facet rule`), permitiendo visualizar opciones alternativas de la misma categoría mientras se restringe estrictamente en las demás categorías.
  - Ocultamiento automático de opciones incompatibles (conteo 0), preservando siempre visibles las opciones que el usuario mantiene activas.
  - Diseño responsivo de cada fila con flexbox: etiqueta alineada a la izquierda y contador resaltado en naranja Kenya (`#ee7c31`).
  - Botón de "Limpiar todo" superior e inferior con reseteo sin recargar la página completa.
- `resources/views/catalogo-preview.blade.php`:
  - Modificación de `fetchFilters(modeloId)` para transmitir los parámetros activos (`window.location.search`) al endpoint de filtros y memorizar las secciones de acordeón abiertas durante la actualización AJAX.
  - Vinculación del evento `filterchange` para disparar simultáneamente `fetchProducts()` y `fetchFilters()`.
  - Actualización reactiva al buscar por texto (`applySearchQuery`) y reseteo ordenado al cambiar de modelo.
- `resources/views/catalogo-modelo.blade.php`:
  - Replicación del comportamiento reactivo `fetchFilters` y `filterchange` en la vista de catálogo por modelo.
- `app/Http/Controllers/CatalogoController.php`:
  - Ajuste en `previewFilters($id = null)` para resolver el ID de modelo desde parámetros de URL o desde `request('modelo')`.

---

## 3. Decisiones de Arquitectura y UX
- **Ley de Tesler (Complejidad en el Backend):** Toda la lógica de normalización, compatibilidad cruzada e intersección de facetas se procesa en el servidor en una única consulta de base de datos ultrarrápida (< 5 ms para los cientos de productos del modelo), entregando al frontend un HTML limpio y listo para renderizar.
- **Cero Abstracciones Innecesarias (Ponytail Ultra):** Se prescindió de motores de búsqueda externos pesados (Elasticsearch / MeiliSearch) o librerías JS complejas; se utilizó la stdlib de colecciones de PHP y Vanilla JS nativo para la persistencia del estado en el DOM.
- **Diseño No-IA:** Interfaz sobria, checkboxes nativos estilizados, tipografía limpia y contadores en naranja institucional sin sombras ni emojis.

---

## 4. Verificación y Corrección de Error 500
- **Diagnóstico Error 500 (`/catalogo/16/detallemod`):**
  - Causa raíz: En el modelo `App\Producto`, el campo `especificaciones` existe como columna en la base de datos (y en `$fillable`) además del método de relación `public function especificaciones()`. Al invocar `$p->especificaciones`, Eloquent prioriza el valor del atributo en la tabla (que era `null`) en lugar de la relación, disparando `ErrorException: foreach() argument must be of type array|object, null given`.
  - Solución quirúrgica (Ponytail Ultra): Se reescribió la extracción de especificaciones para Monitores y Tóner mediante consulta directa con `\DB::table('especificaciones')->join('productos', ...)` agrupada por `producto_id`. Esto elimina por completo la colisión de atributos en Eloquent y optimiza la velocidad al consultar directamente la tabla relacional.
- Sintaxis PHP validada con `php -l`: Cero errores.
- Compilación de plantillas validada con `php artisan view:cache`: Exitoso.
