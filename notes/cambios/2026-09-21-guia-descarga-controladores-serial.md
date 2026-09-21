# Registro de Cambio: Guía Informativa y Autofoco para Descarga de Controladores por Serie

- **Fecha:** 2026-09-21
- **Tipo:** UI / UX / Navegación / Frontend
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
- Cuando el usuario hacía clic en "Descargar controladores" en el footer, llegaba a `/consultar/garantia` donde se requería el número de serie para filtrar los drivers específicos de su modelo, pero la interfaz no explicaba por qué era necesario ni guiaba hacia el buscador.
- Se implementó un estado vacío informativo ("Empty State") y un flujo de autofoco con animación de pulso que orienta de manera pedagógica al usuario sobre cómo y por qué debe ingresar su número de serie antes de ver la lista de controladores.

---

## 2. Archivos Modificados
- `resources/views/layouts/landing.blade.php`:
  - Enlaces de la columna "Soporte técnico" en el footer actualizados con sus respectivos hashes: `#controladores`, `#garantia` y `#galeria`.
- `resources/views/consultar/garantia.blade.php`:
  - Animación CSS `@keyframes searchHighlightPulse` para resaltar el input de búsqueda.
  - Contenedor `#drivers-empty-prompt` con diseño limpio de marca: explica la necesidad de ingresar la serie para asegurar compatibilidad de hardware y dónde encontrar la etiqueta posterior.
  - Lógica de cambio de pestañas: muestra el empty prompt en "Controladores" si aún no se ha buscado serie (`state != 'success'`), y enfoca automáticamente el input.
  - Soporte completo en `activateTabByHash()` para `#controladores`, `#garantia`, `#galeria` y `#terminos`.
  - Cache buster actualizado para `garantia.js?v=6`.
- `public/js/consultar/garantia.js`:
  - En el método `Buscar()`, al obtener resultado exitoso, oculta el `#drivers-empty-prompt` y despliega automáticamente la lista de drivers si la pestaña activa es "Controladores".

---

## 3. Decisiones de Arquitectura y UX
- **Ley de Tesler:** El sistema explica con claridad técnica el motivo del requisito previo (compatibilidad de chipset/placa) en vez de dejar una pantalla en blanco o mensajes de error confusos.
- **Autofoco y Pulso:** Al hacer clic en "Descargar controladores", la vista hace scroll suave directo al buscador y enfoca el campo de serie sin fricción.

---

## 4. Próximos Pasos
- Desplegar cambios a producción en Dokploy.
