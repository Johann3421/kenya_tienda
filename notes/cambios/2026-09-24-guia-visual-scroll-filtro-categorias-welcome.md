# Registro de Cambio: Scroll Guiado Inteligente y Feedback Visual al Filtrar Categorías en Home (Welcome)

- **Fecha:** 2026-09-24
- **Tipo:** UX / Feature / Frontend
- **Estado:** Completado
- **Módulo:** Portada Principal (`resources/views/welcome.blade.php`)

---

## 1. Contexto y Diagnóstico
En la página principal de la tienda (`welcome.blade.php`), la sección `<section class="productos-section">` ofrece botones de selección por categoría en formato circular ("Todos", "Computadoras", "Laptops", etc.).

### Problema Identificado:
- Cuando el usuario se encuentra visualizando la parte superior de la página (entre el hero slider y la grilla de categorías), al hacer clic en un botón de categoría, el carrusel con los modelos filtrados (`.productos-carousel` en `<section class="ofertas-section">`) queda fuera del campo visual inferior o cortado por el fold de la pantalla.
- En consecuencia, el visitante no percibía de forma inmediata qué acción realizaba el botón ni si estaba filtrando correctamente, obligándolo a scrollear manualmente a ciegas para descubrir el resultado.

---

## 2. Solución Implementada

Se implementó una experiencia guiada ("llevar de la mano") de alta fidelidad, natural y sin fricciones técnicas:

### A. Scroll Guiado Inteligente (`applyCategoryFilter`)
- Cálculo dinámico de la posición del carrusel (`#seccion-catalogo-carrusel`) relativo al viewport y al alto de la cabecera fija (`.site-header`, ~80px + offset de respiro).
- Si el carrusel de productos no está enfocado de forma óptima en pantalla, el navegador realiza un desplazamiento suave (`window.scrollTo({ behavior: 'smooth' })`), posicionando directamente el catálogo de modelos filtrados en el centro de atención del usuario.
- Evita saltos bruscos o innecesarios si el usuario ya se encuentra con el carrusel a la vista.

### B. Barra Guía y Feedback Dinámico (`.carousel-filtro-bar`)
- Se incorporó un encabezado contextual inmediatamente superior al carrusel:
  - **Badge corporativo:** `Catálogo de Equipos`.
  - **Título interactivo:** `Mostrando: <categoría seleccionada>` (destacado en color `#f26522`).
  - **Contador dinámico:** Número exacto de modelos disponibles para la categoría seleccionada en tiempo real.

### C. Micro-animación de Entrada (`@keyframes cardFilterIn`)
- Al cambiar de categoría, las tarjetas de producto visibles (`.producto-card`) ejecutan una suave animación de entrada (fade-in + ligero slide vertical de 12px) mediante clase `.anim-filter`, confirmando visualmente la actualización del catálogo.

### D. Manejo de Estado Vacío (`.carousel-empty-state`)
- Si una categoría seleccionada no cuenta con modelos en el carrusel en ese momento, se ocultan las flechas de navegación y se despliega un mensaje amigable con botón para restablecer el filtro ("Ver todos los productos"), evitando huecos vacíos o elementos rotos.

### E. Indicador de Botón Activo en Categorías
- Subrayado distintivo (`::after` de 32px en `#f26522`) en el botón de categoría activo, más subtítulo explicativo en el encabezado de la sección de categorías.

---

## 3. Archivos Modificados
- `resources/views/welcome.blade.php`: Inclusión de estilos CSS de feedback y micro-interacciones, marcado HTML del encabezado y empty-state del carrusel, y orquestación JavaScript del filtrado con scroll suave responsivo.

---

## 4. Verificación
- Sintaxis PHP y Blade validada con `php -l resources/views/welcome.blade.php` sin errores.
- Comportamiento suave en desktop y mobile con ajuste por media query `@media (max-width: 768px)`.
