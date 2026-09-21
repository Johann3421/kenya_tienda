# Registro de Cambio: Navegación y Scroll Suave con Resalte Visual desde Footer a Quiénes Somos

- **Fecha:** 2026-09-21
- **Tipo:** UI / UX / Frontend / Navegación
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
- El cliente requirió que los 4 enlaces del footer bajo "Quiénes somos" ("Historia", "Misión", "Visión", "Valores") lleven directamente a sus respectivos bloques de contenido en `/quienes-somos`.
- Dado que las tarjetas de "Misión", "Visión" y "Valores" se encuentran en la misma fila horizontal en desktop, era crítico añadir una distinción visual (foco) para que el usuario identifique de inmediato a qué tarjeta fue dirigido.
- Resolver el offset del navbar fijo (`.site-header`) para evitar que tape el contenido tanto en carga directa con hash como en navegación interna.

---

## 2. Archivos Modificados
- `public/landing/js/main.js`:
  - Cálculo dinámico de `scrolltoOffset` basado en la altura real de `.site-header` (+20px de margen).
  - Intercepción de clics en `.kenya-footer-list a` para navegación fluida en la misma página con `pushState` y activación de clase `.target-highlight`.
  - Corrección de la animación inicial en `$(document).ready` ante URLs con hash.
- `resources/views/layouts/landing.blade.php`:
  - Cache busting agregando `?v=2.2` a la inclusión de `landing/js/main.js`.
- `resources/views/quienes-somos.blade.php`:
  - Activación de `html { scroll-behavior: smooth; }`.
  - Offset `scroll-margin-top: 110px;` en `#historia` y las tarjetas de valor.
  - Estilos `:target` y `.target-highlight` con animación de pulso y borde naranja institucional (`#f26522`).
  - Script complementario para disparar el resalte visual en eventos `DOMContentLoaded` y `hashchange`.

---

## 3. Decisiones de Arquitectura y UX
- **Ley de Tesler:** El usuario no tiene que buscar manualmente la tarjeta ni calcular dónde hacer scroll; el sistema ubica el elemento con precisión milimétrica bajo el header sticky.
- **Feedback visual no invasivo:** Animación de pulso sutil de 1.8 segundos y borde naranja de marca que guía la vista del usuario sin romper el diseño limpio.
- **Resistencia al caché:** Actualización del parámetro de versión del script principal para asegurar la aplicación inmediata en el cliente.

---

## 4. Próximos Pasos
- Desplegar cambios al entorno de producción (Dokploy).
