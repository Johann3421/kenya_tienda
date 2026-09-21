# Registro de Cambio: Deep Linking Directo a Términos y Condiciones de Garantía

- **Fecha:** 2026-09-21
- **Tipo:** UI / UX / Navegación / JavaScript
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
- El cliente solicitó que el enlace "Términos y condiciones de garantía" del footer lleve directamente a ese apartado en `/consultar/garantia`.
- Anteriormente, el enlace apuntaba a `#terms`, pero en la vista de garantía:
  1. No existía anclaje `#terms` o `#terminos`.
  2. La pestaña de términos (`tab-terminos-content`) estaba oculta con `display: none` por defecto (solo activa al hacer clic manual en la pestaña).
  3. No se ejecutaba el render del visor de PDF si no se pinchaba la pestaña.

---

## 2. Archivos Modificados
- `resources/views/layouts/landing.blade.php`:
  - Se actualizó el enlace del footer a `{{ route('consultar.garantia') }}#terminos`.
- `resources/views/consultar/garantia.blade.php`:
  - Se agregaron anclas semánticas `#terminos` y `#terms` con `scroll-margin-top: 110px`.
  - Se añadió `html { scroll-behavior: smooth; }` y offset al contenedor `.terms-container`.
  - Se implementó la función `activateTabByHash()` que:
    - Detecta `#terminos` / `#terms` al cargar la página o al cambiar el hash.
    - Activa la pestaña correspondiente (`.support-tab[data-target="tab-terminos"]`).
    - Dispara la renderización del PDF.
    - Realiza un scroll suave hacia el contenedor de términos respetando la altura del header sticky.
  - Se interceptaron clics en enlaces hash internos para navegación fluida cuando el usuario ya se encuentra dentro de `/consultar/garantia`.

---

## 3. Decisiones de Arquitectura y UX
- **Ley de Tesler:** El usuario no necesita saber que los términos están dentro de una pestaña oculta; el sistema se encarga de abrir la pestaña, renderizar el PDF y posicionar la vista.
- **Retrocompatibilidad:** Admite tanto `#terminos` como `#terms`, así como anclas para `#controladores`, `#garantia` y `#galeria`.

---

## 4. Próximos Pasos
- Desplegar cambios a producción y verificar navegación en vivo.
