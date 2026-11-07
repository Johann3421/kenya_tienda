# Apertura de Fichas Técnicas en Nueva Pestaña y Soporte de Menú Contextual en Catálogo

- **Fecha:** 2026-11-07
- **Tipo:** UI / UX / Frontend / Catálogo / Accesibilidad
- **Estado:** Completado
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra)

---

## 1. Contexto y Requerimiento
El usuario reportó que al navegar y filtrar fichas en el catálogo (`/catalogo/2/detallemod` o cualquier modelo filtrado), si deseaba abrir una ficha en una pestaña o ventana independiente para no perder sus filtros o comparar productos, el navegador no le ofrecía esa opción. Al hacer clic derecho, únicamente salía el menú contextual de página ("Atrás", "Adelante", "Recargar"), sin la opción nativa "Abrir enlace en una pestaña nueva", y al hacer clic izquierdo la página navegaba en la misma pestaña destruyendo el estado activo de filtros.

---

## 2. Causa Raíz
1. En `resources/views/partials/catalogo-products.blade.php`, el botón de detalle estaba implementado con `<button class="btn-details pill" onclick="window.location.href='...'">`.
2. Para el motor del navegador web (Chromium, Firefox, Safari), un `<button>` no es un hipervínculo HTML semántico (`<a>`). Por tanto:
   - El menú contextual de clic derecho no muestra las opciones de enlace ("Abrir enlace en una pestaña nueva", "Abrir en nueva ventana", "Copiar dirección de enlace").
   - El clic con rueda central (clic medio) o combinaciones de teclado (`Ctrl + Clic`) quedan inoperantes.
   - La navegación forzada mediante `window.location.href` saca al usuario del catálogo filtrado.
3. De igual manera, ni la imagen ni el título (`h3`) contaban con enlaces nativos `<a>`.

---

## 3. Archivos Modificados y Soluciones Aplicadas

- `resources/views/partials/catalogo-products.blade.php`:
  - Botón "Más información" transformado de `<button onclick="...">` a `<a href="{{ $prodDetalleUrl }}" target="_blank" rel="noopener noreferrer" class="btn-details pill">`.
  - Imagen del producto envuelta en `<a href="{{ $prodDetalleUrl }}" target="_blank" rel="noopener noreferrer">`.
  - Título del producto envuelto en `<a href="{{ $prodDetalleUrl }}" target="_blank" rel="noopener noreferrer">`.
- `resources/views/catalogo-preview.blade.php`:
  - Adecuación de estilo en `.btn-details`: `display: flex; justify-content: center; align-items: center; text-decoration: none !important; color: #fff !important;` y hover/focus sin subrayado.
  - Añadido efecto hover en título `.product-title:hover { color: #ee7c31; }`.
  - Typeahead suggest (`renderSuggestions`): Cada sugerencia de búsqueda se transformó en un elemento `<a>` nativo con `target="_blank"`, permitiendo clic derecho y apertura en pestaña nueva desde el buscador inteligente.
- `resources/views/catalogo-modelo.blade.php`:
  - Incorporación de reglas CSS para `.btn-details` como hipervínculo.
  - Typeahead suggest actualizado para renderizar elementos `<a>` con `target="_blank"`.
- `resources/views/Novedades.blade.php`:
  - Actualización del botón `.btn-details.pill`, imagen y título con enlaces nativos `<a>` con `target="_blank"`.
- `resources/views/components/novedades.blade.php`:
  - Adición de `target="_blank" rel="noopener noreferrer"` en los enlaces de novedades.

---

## 4. Verificación y Despliegue

- `php -l` ejecutado sin errores sintácticos en todas las vistas afectadas.
- `php artisan view:clear` ejecutado exitosamente.
- Git commit y push a `origin/feature/dokploy-postgres-sync`.
