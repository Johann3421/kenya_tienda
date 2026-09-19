# Registro de Cambio: Ajuste de Tiempo en Hero Slider y Reestructuración de Temas/Títulos del Footer

- **Fecha:** 2026-09-19
- **Tipo:** UI / UX / Frontend
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
- Aumentar el tiempo de rotación automática de los slides en la sección Hero de la página principal para permitir una lectura más pausada de títulos y descripciones de cada modelo.
- Reestructurar los títulos, temas y enlaces de las 4 columnas del footer según el nuevo diseño:
  - Columna 1: "Quiénes somos" con enlaces a Historia, Misión, Visión y Valores.
  - Columna 2: "Atención al cliente", retirando "Consulta el estado de tu Producto" y conservando Preguntas frecuentes y Términos de garantía.
  - Columna 3: "Soporte técnico", con enlaces a Descargar controladores, Estado de la garantía y Guía de activación.
  - Columna 4: "Contáctenos" con los datos de contacto y direcciones.

---

## 2. Archivos Modificados
- `resources/views/welcome.blade.php`: Intervalo de rotación del hero slider aumentado de 5000ms a 8000ms.
- `resources/views/layouts/landing.blade.php`: Actualización de títulos y listas de enlaces en las 4 columnas del footer (`kenya-final-footer`).
- `resources/views/quienes-somos.blade.php`: Incorporación de anclas (`#historia`, `#mision`, `#vision`, `#valores`) y `scroll-margin-top` para navegación directa desde el footer.

---

## 3. Decisiones de Arquitectura y UX
- **Ley de Tesler y navegación fluida:** En lugar de recargar la página general de "Quiénes somos", los enlaces del footer apuntan con hash anchors a cada sección específica (`#historia`, `#mision`, `#vision`, `#valores`) con offset de scroll para que el navbar sticky no oculte los encabezados.
- **Tiempo de Hero:** 8 segundos es el estándar óptimo de e-commerce para banners con texto descriptivo y botones CTA sin fatiga visual.

---

## 4. Próximos Pasos
- Desplegar a Dokploy, purgar caché y verificar en vivo.
