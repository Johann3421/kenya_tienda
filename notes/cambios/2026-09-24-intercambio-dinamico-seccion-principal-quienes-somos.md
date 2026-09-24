# Registro de Cambio: Intercambio de Posición Dinámico entre Sección Principal y Tarjetas en Quiénes Somos

- **Fecha:** 2026-09-24
- **Tipo:** UX / Feature / Frontend
- **Estado:** Completado
- **Módulo:** Vista Quiénes Somos (`resources/views/quienes-somos.blade.php`)

---

## 1. Contexto y Diagnóstico
En la vista `https://www.kenya.com.pe/quienes-somos`, la sección superior contiene el bloque principal con **Nuestra Historia** (`#historia`), y en la parte inferior se encuentra una cuadrícula de 3 columnas con **Nuestra Misión** (`#mision`), **Nuestra Visión** (`#vision`) y **Nuestros Valores** (`#valores`).

### Solicitud del Cliente:
- Previamente, al acceder mediante enlaces de anclaje (por ejemplo desde el footer: `quienes-somos#mision`) o al hacer clic, solo se aplicaba un resaltado visual temporizado sobre la tarjeta correspondiente en el grid inferior.
- El cliente solicitó explícitamente que al hacer clic o anclar en cualquier sección (Historia, Misión, Visión o Valores), la sección elegida pase a ocupar la posición del div principal superior (`.about-intro`), intercambiando su lugar con el elemento que se encuentre actualmente allí.

---

## 2. Solución Implementada

### A. Estructura Unificada de Contenidos (`.about-item`)
- Se homogeneizó el marcado interno de las 4 secciones (`historia`, `mision`, `vision`, `valores`) con `.about-text`, encabezado `<h2>`, contenedor `.about-description` e indicador `.card-swap-indicator`.
- Se crearon dos contenedores de ranura:
  - `#about-main-slot`: Aloja el elemento activo como bloque principal (`.about-intro`).
  - `#about-values-grid`: Aloja los 3 elementos restantes en formato de tarjetas interactivas (`.value-card`).

### B. Lógica de Intercambio Dinámico de Nodos en el DOM (`activateSection`)
- Cuando se activa una sección:
  1. Si ya se encuentra en `#about-main-slot`, se aplica animación de refresco y scroll.
  2. Si se encuentra en `#about-values-grid`, se inserta un nodo de referencia temporal para preservar la posición exacta del grid.
  3. El elemento objetivo se traslada a `#about-main-slot`, adquiere clase `.about-intro` (recibiendo estilos destacados: fondo `#fffaf7`, borde naranja izquierdo de 4px, tipografía `2.2rem`, párrafo de mayor escala).
  4. El elemento principal anterior se inserta en la posición liberada en `#about-values-grid`, adquiriendo clase `.value-card` (estilos de tarjeta: fondo `#f8f8f8`, borde interactivo, tamaño `1.3rem`, indicador `Ver en sección principal`).
  5. Se dispara una micro-animación de entrada (`swapFadeIn`) y se ejecuta un scroll suave (`window.scrollTo({ behavior: 'smooth' })`) compensando la cabecera fija (`.site-header`).

### C. Múltiples Puntos de Entrada Compatibles
- **Navegación directa por URL:** Carga inicial con hash (`#mision`, `#vision`, etc.) detectada automáticamente y posicionada arriba.
- **Enlaces en el Footer:** Evento `hashchange` detecta clics en los enlaces de Quiénes somos en el footer.
- **Clic interactivo en tarjetas:** Al hacer clic sobre cualquier tarjeta de la cuadrícula inferior, asciende de inmediato al espacio principal y actualiza la URL con `history.replaceState`.

---

## 3. Rediseño Visual y Nivelación de Alturas en Grid (2026-09-24)

### A. Eliminación de Sobreposición de Colores Naranja
- **Diagnóstico:** Previamente, el div principal `.about-intro` combinaba fondo durazno (`#fffaf7`), borde lateral naranja de 4px, sombra con resplandor naranja e iconografía naranja, sumado a una línea vertical pseudo-elemento `::before` dentro de `.about-description`. Esto generaba saturación cromática y fatiga visual ("sobreposición de naranjas").
- **Solución:**
  - Fondo limpio blanco `#ffffff` con borde neutro `1px solid #e2e8f0` y acento superior sutil `border-top: 4px solid #f26522`.
  - Se eliminó completamente la doble barra vertical `::before` de `.about-description`.
  - Iconos jerarquizados dentro de contenedores badge redondeados (`.icon-title` con fondo `#fff7ed`, borde `#fed7aa` y color `#ea580c`).
  - Sombra neutra corporativa (`box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05)`).

### B. Corrección de Alturas Asimétricas en Grid Inferior
- **Diagnóstico:** Al descender `#historia` a la cuadrícula `.values-grid`, sus 4 párrafos largos provocaban una altura desproporcionada (~600px) frente a Misión y Visión (1 párrafo de ~3 líneas), desfigurando la fila de tarjetas.
- **Solución:**
  - Regla quirúrgica CSS `#quienes-somos-page .value-card .about-description p:nth-of-type(n+2) { display: none !important; }`: Cuando `#historia` pasa a ser `.value-card`, solo muestra su primer párrafo introductorio (~3 líneas, longitud exacta a Misión y Visión). Al ascender a principal, se muestran los 4 párrafos en su totalidad.
  - Flexbox `display: flex; flex-direction: column; justify-content: space-between; height: 100%;` en `.value-card` con `align-items: stretch;` en el grid para alinear matemáticamente la altura de las 3 tarjetas y anclar el indicador `Ver en sección principal ↑` al pie de cada tarjeta.
  - Lista de valores compacta en chips (`.valores-list`) en la tarjeta y en cuadrícula con checkmarks en la sección principal.

---

## 4. Archivos Modificados
- `resources/views/quienes-somos.blade.php`: Reestructuración CSS, marcado HTML con slots e implementación de script con intercambio dinámico de nodos.

---

## 5. Verificación
- Sintaxis PHP y Blade validada con `php -l resources/views/quienes-somos.blade.php` sin errores.
- Alturas perfectamente homogéneas entre tarjetas en la cuadrícula inferior.
- Comportamiento responsivo asegurado para vistas móviles y desktop.
