# Remoción de Vista 3D Estática por Defecto y Preservación de Carga desde Administrador

- **Fecha:** 2026-11-11
- **Tipo:** UI / Frontend / Catálogo / Productos
- **Estado:** Completado
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra)

---

## 1. Contexto y Requerimiento
El cliente solicitó remover la visualización del modelo 3D por defecto que se mostraba automáticamente en los productos (específicamente la simulación estática de chasis en productos Genwork), manteniendo intacta la funcionalidad técnica de vistas 360/3D. Si un administrador sube un diseño 3D para un modelo desde el módulo administrativo (`/administrador/modelos360`), dicho diseño sí se mostrará en la ficha del producto; de lo contrario, se mostrará únicamente la imagen normal del producto (2D).

---

## 2. Causa Raíz Técnica
En `resources/views/sistema/productos/detalle.blade.php`:
Existía un bloque de fallback que forzaba la vista 360 (`has360 = true`) para cualquier producto que contuviera la palabra "genwork" en su nombre o descripción de modelo, cargando las 36 imágenes estáticas locales de `public/Diseño_3d_case/XTQ-209_*.jpg`.

---

## 3. Archivos Modificados y Soluciones Aplicadas

- `resources/views/sistema/productos/detalle.blade.php`:
  - Se eliminó el bloque de fallback estático `Diseño_3d_case/XTQ-209_`.
  - Se condicionó `$has360` exclusivamente a la existencia de imágenes dinámicas subidas por un administrador en `public/modelos_360/{modeloId}`.
  - Si no existe un set 360 subido para el modelo, `$has360` permanece en `false` y el producto renderiza su imagen 2D habitual sin el visor interactivo.
  - Se simplificó la variable JavaScript `baseUrl` para apuntar directamente al almacenamiento dinámico.

---

## 4. Verificación y Despliegue

- Sintaxis PHP validada con `php -l resources/views/sistema/productos/detalle.blade.php` (cero errores).
- Git commit y push a `origin/feature/dokploy-postgres-sync`.
