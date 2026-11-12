# Navegación Clickeable en Banners del Hero Carrusel (Apartado "Disponible en Perú Compras")

- **Fecha:** 2026-11-12
- **Tipo:** Feature / UX / Hero Slider / Acuerdos Marco / Frontend
- **Estado:** Completado
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra)

---

## 1. Contexto y Requerimiento
En producción se subieron nuevos banners al carrusel principal (`hero-slider-section`) que incorporan dentro de su diseño gráfico el apartado oficial:
> **"Ya disponible en: [Logo de Perú Compras] PERÚ COMPRAS"**

El cliente solicitó que dicho apartado (y en consecuencia la superficie visual del banner) sea clickeable por el usuario, replicando exactamente el comportamiento del botón "Ver Catálogo" para dirigir al comprador al catálogo correspondiente de la marca (`$bannerUrl` / `route('detallemod', $matchedMod->id)` o `route('catalogo')`).

---

## 2. Diagnóstico Técnico
- Anteriormente, el carrusel renderizaba la imagen del banner como fondo estático en `div.hero-slide` (`background: url(...) center/cover;`).
- Únicamente el botón HTML `<a class="hero-btn">` era interactivo.
- Cualquier clic realizado por un usuario sobre la imagen del banner (incluyendo el texto gráfico "Ya disponible en Perú Compras") quedaba inactivo sin respuesta visual ni navegación.

---

## 3. Solución Quirúrgica Implementada

### Archivo modificado: `resources/views/welcome.blade.php`
1. **Atributos accesibles y Overlay Link en cada Slide:**
   - Se añadió `data-href="{{ $bannerUrl }}"`, `role="link"`, `tabindex="0"` y `cursor: pointer` al contenedor `.hero-slide`.
   - Se incorporó un enlace overlay semántico `<a class="hero-slide-overlay-link" href="{{ $bannerUrl }}" aria-label="..."></a>` que cubre el 100% del área del slide (`position: absolute; inset: 0; z-index: 2`).

2. **Ajuste de Capas y Puntero:**
   - `.hero-slide-content` configurado con `pointer-events: none` para no interceptar los clics dirigidos a la imagen o al apartado gráfico.
   - Elementos interactivos internos (`.hero-btn`, botones y enlaces) restaurados con `pointer-events: auto`.
   - Controles del carrusel (`.hero-controls`, `.hero-prev`, `.hero-next`, `.hero-bottom-controls`, `.hero-pill`) mantienen prioridad con `z-index: 10`.

3. **Manejador de Eventos JS con Detección de Swipe:**
   - Se añadió un listener de `click` a cada slide para redirigir a `slide.dataset.href`.
   - Se implementó discriminación entre gestos táctiles de deslizamiento (swipe horizontal `diff > 15px`) y toques simples (tap), evitando redirecciones accidentales al cambiar de slide en móviles.

---

## 4. Verificación
- Clic en el apartado gráfico "Ya disponible en Perú Compras" o en cualquier área del banner redirige a `$bannerUrl` (mismo destino que "Ver Catálogo").
- Clic en las flechas anterior/siguiente o en el botón de pausa/reproducción opera los controles sin disparar navegación.
- Soporte táctil validado para deslizar slides sin salto no deseado.
