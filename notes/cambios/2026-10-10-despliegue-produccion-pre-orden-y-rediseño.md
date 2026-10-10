# Registro de Cambio: Despliegue a Producción (Dokploy) — PRE ORDEN, Bloqueo B2B Unificado y Rediseño No-IA

- **Fecha:** 2026-10-10
- **Tipo:** Despliegue / Producción / UI / Comercial
- **Estado:** Completado y Desplegado en Vivo
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Requerimiento
El usuario solicitó desplegar a producción los cambios realizados en el frontend y catálogo:
1. Reemplazo del término comercial de stock `"A IMPORTAR"` por `"PRE ORDEN"` en todas las vistas públicas.
2. Unificación de la caja de bloqueo B2B (`product-prices-locked`) en el catálogo para que todos los usuarios no autenticados vean consistentemente la invitación a iniciar sesión antes de revelar precios.
3. Despliegue de los rediseños sobrios no-IA de `codigo-conducta.blade.php` (16 lineamientos limpios, panel ISO 37001:2025 sticky lateral, tabla formal de canales) y `reciclaje.blade.php` (banner institucional enmarcado con imagen oficial, cita editorial limpia sin bordes verdes y ampliación a 1400px).

---

## 2. Acciones Realizadas y Despliegue
1. **Normalización y Commit de Vistas:**
   - Se verificaron los cambios en `resources/views/Novedades.blade.php`, `resources/views/components/novedades.blade.php`, `resources/views/partials/catalogo-products.blade.php` y `resources/views/welcome.blade.php`.
   - Se limpió el subproyecto residual `ponytail` para mantener el árbol de trabajo intacto.
   - Commit registrado: `e77eb32` (`feat(stock): actualizar indicador comercial de stock a PRE ORDEN`).
2. **Push a Repositorio Remoto:**
   - Se subió a `origin/feature/dokploy-postgres-sync`, la rama activa que Dokploy monitorea y sincroniza.
3. **Verificación en Producción en Vivo (`https://www.kenya.com.pe`):**
   - Se verificó mediante inspección web directa que el contenedor en Dokploy reconstruyó y actualizó los cambios:
     - `https://www.kenya.com.pe/codigo-conducta`: Renderizando los 16 artículos limpios (`<ol class="cc-art-list">`), ISO 37001:2025 y sidebar sticky de 380px.
     - `https://www.kenya.com.pe/reciclaje`: Renderizando el banner institucional con `reciclaje-equipo-box.jpg`, cita editorial limpia y botón naranja Kenya.

---

## 3. Archivos Involucrados
- `resources/views/Novedades.blade.php`
- `resources/views/components/novedades.blade.php`
- `resources/views/partials/catalogo-products.blade.php`
- `resources/views/welcome.blade.php`
- `resources/views/codigo-conducta.blade.php`
- `resources/views/reciclaje.blade.php`
- `notes/INDEX.md`

---

## 4. Próximos Pasos
- Monitoreo de logs y tráfico en vivo en Dokploy.
