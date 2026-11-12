# Creación y Refinamiento Anti-Slop de Prototipo HTML Standalone UI/UX

- **Fecha:** 2026-11-12
- **Tipo:** Prototipo / UI / UX / Arquitectura de Información / Frontend / Anti-Slop
- **Estado:** Completado (Aislado sin modificar código de producción)
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra + Anti-Slop)

---

## 1. Contexto y Objetivo
El usuario solicitó diseñar y estructurar un prototipo completo en un único archivo HTML aislado (`public/prototipo-ux.html`) para evaluar los nuevos patrones de diseño de la plataforma de Kenya Technology, **sin modificar ningún archivo productivo del proyecto**, incorporando los recursos reales de producción y eliminando cualquier estética genérica atribuible a IA (Anti-Slop Craft Engine).

---

## 2. Parámetros Anti-Slop & Craft Visual Aplicados

1. **Color Discipline & Surfaces:**
   - Paleta sobria basada en escala neutral entintada Zinc (`#fafafa`, `#f4f4f5`, `#e4e4e7`, `#09090b`).
   - Cero gradientes genéricos púrpura/índigo ni azul Tailwind `#3B82F6`.
   - Único acento intencional: naranja industrial corporativo de Kenya (`#ea580c`).
   - Sustitución de sombras infladas por bordes hairline crisp de 1px (`#e4e4e7`).

2. **Tipografía & Micro-Jerarquía:**
   - Tracking ajustado (`letter-spacing: -0.035em`) y line-height ceñido (`1.1` a `1.15`) en todos los encabezados (`h1` a `h3`).
   - Tipografía monospace (`JetBrains Mono`) con `tabular-nums` para SKUs, precios, métricas, especificaciones y stock.
   - **Cero emojis** en toda la interfaz; iconos en SVG limpios (trazo 1.5px uniforme estilo Lucide/Phosphor).

3. **Ritmo de Layout & Anti-Monotonía:**
   - Hero asimétrico con split editorial 7 / 5.
   - Bento Grid modular asimétrico con spans de 8, 4 y 6 columnas.
   - Divisores hairline de rejilla (`specs-hairline-table` y `matrix-row`).
   - Medida de párrafos limitada a `66ch` máximo.

4. **Component Craft & Realismo B2B:**
   - Radios arquitectónicos limpios de 6px a 8px (sin píldoras de juguete).
   - Curvas de aceleración desaceleradas (`cubic-bezier(0.16, 1, 0.3, 1)`).
   - Datos, SKUs, RUC (20556811431 - R.T.H. KENYA S.A.C.), catálogos de Perú Compras (IM-CE-2020-5 y EXT-CE-2021-1) y precios duales reales (USD y PEN con IGV).

---

## 3. Integración de Activos Reales del Proyecto

- **Logotipo Principal:** `theme/images/kenya.png` con fallback a `theme/images/logo.png`.
- **Logotipo Perú Compras:** `theme/images/logo_PeruCompras.svg` y `logo_PeruCompras.svg`.
- **Certificación Oficial Anti-Soborno:** `iso-37001.png`.
- **Fotografía de Hardware Mid-Tower:** `producto.jpg` con fallback a `pord.jpg`.
- **Fotografía de Chasis Compacto:** `ofiszusff.webp` con fallback a `ofiszusff.png`.

---

## 4. Leyes de UX & Navegación

- **Ley de Fitts:** Botones CTA con dimensiones táctiles ergonómicas (>= 40px altura, paddings de 16px).
- **Ley de Hick:** Menú de navegación acotado a 5 enlaces funcionales.
- **Ley de Jakob:** Estructura mental familiar (Logo superior izquierdo, barra de búsqueda / atajo Ctrl+K, filtros facetados en lateral izquierdo y catálogo en main).
- **Ley de Tesler:** Toda la lógica de combinatoria y compatibilidad de filtros se procesa en el script interno, permitiendo al usuario una experiencia inmediata, sin fricción y con conteos dinámicos en vivo.
- **Navegación de Fichas Técnicas:** Enlace nativo con `target="_blank" rel="noopener noreferrer"` y botón directo para apertura garantizada en nueva pestaña y soporte total de clic derecho del mouse.

---

## 5. Auditoría de los 7 Estados de UI

1. **Default:** Superficie limpia con contraste accesible 4.5:1.
2. **Hover:** Oscurecimiento controlado de acento y desaceleración sin saltos.
3. **Focus Visible:** Anillo de 3px exterior con `rgba(234, 88, 12, 0.22)`.
4. **Active:** Escalamiento a 0.99 y tono oscurecido (`#9a3412`).
5. **Disabled:** Opacidad 0.5 y cursor `not-allowed`.
6. **Loading (Skeleton):** Animación shimmer arquitectónica a 1.5s sin romper dimensiones.
7. **Empty State:** Bloque centrado con SVG de búsqueda tachada y botón directo de restablecimiento de filtros.

---

## 6. Verificación Técnica

- **Ruta local:** `http://localhost/kenya_tienda/public/prototipo-ux.html` o vía protocolo `file://`.
- **Archivos de Producción tocados:** 0 (Cero modificaciones en `app/`, `resources/views/`, `routes/web.php` o base de datos).
