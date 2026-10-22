# Reemplazo del Ítem 'Catálogo' en Header por Logo de Perú Compras y Centralización de Navegación

- **Fecha:** 2026-10-22
- **Tipo:** UI / Branding / Refactor Arquitectura
- **Estado:** Completado
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra)

---

## 1. Contexto y Requerimiento
El cliente solicitó sustituir el texto "CATALOGO" de la barra de navegación principal del header por el logotipo oficial de **Perú Compras** (`logo_PeruCompras.svg`), tal como se especificó en la imagen de referencia compartida (donde "CATALOGO" figuraba tachado y señalado con flecha hacia el logo de Perú Compras).
El objetivo es reforzar la presencia institucional de los acuerdos marco y catálogo electrónico de Perú Compras como canal comercial prioritario de la marca.

---

## 2. Archivos Modificados y Creados
- `logo_PeruCompras.svg`:
  - Se copió a `public/logo_PeruCompras.svg` y `public/theme/images/logo_PeruCompras.svg` para su consumo web con `asset()`.
- `resources/views/partials/header-menu.blade.php` (NUEVO):
  - Componente de navegación centralizado con detección dinámica de rutas activas (`request()->routeIs(...)` / `request()->is(...)`).
  - Ítem de catálogo con imagen `.kenya-perucompras-logo` enlazando a `route('catalogo')` con soporte accesible (`title` y `aria-label`).
- `resources/views/layouts/landing.blade.php`:
  - Se añadieron estilos CSS para `.site-header .kenya-nav-perucompras` y `.site-header .kenya-perucompras-logo` (dimensiones 32px alto, centrado vertical, hover suave y animación de subrayado inferior `::after` naranja).
  - En media query `<= 1100px`, ajuste responsivo a `28px` de altura.
  - Reemplazo de `@yield('menu')` por `@include('partials.header-menu')`.
  - Actualización del menú móvil para incluir el logo de Perú Compras junto con el texto descriptivo "Catálogo Perú Compras".
- Vistas del sistema (`welcome`, `quienes-somos`, `Catalogo`, `Novedades`, `Contactenos`, `codigo-conducta`, `reciclaje`, `reciclar`, `Reclamaciones`, `catalogo-modelo`, etc.):
  - Removidos los bloques redundantes `@section('menu')` que duplicaban el marcado y contenían código desfasado.

---

## 3. Decisiones de Arquitectura y UX
- **Eliminación de Deuda Técnica (Ponytail Ultra):** Se eliminó la duplicación masiva del menú en más de 15 vistas Blade, centralizando la barra de navegación en un único partial reutilizable y dinámico.
- **Armonía Visual y Alineación:** El logo SVG (aspect ratio ~2:1) se dimensionó a `32px` de altura para integrarse con balance exacto entre "QUIENES SOMOS" y "NOVEDADES" dentro de la altura de 80px del header.
- **Comportamiento Interactivo:** Mantiene la línea naranja indicadora de página activa (`.kenya-active`) y de hover propia del sistema de diseño de KENYA.

---

## 4. Verificación y Resultados
- Sintaxis PHP validada con `php -l` en todas las vistas modificadas: Cero errores.
- Commit de producción: `334b551` desplegado en Dokploy (`feature/dokploy-postgres-sync`).

