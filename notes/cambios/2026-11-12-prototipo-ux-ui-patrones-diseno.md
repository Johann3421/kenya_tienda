# Rediseño Estructural del Header B2B y Erradicación de Patrones Cliché de IA en Prototipo

- **Fecha:** 2026-11-12
- **Tipo:** Prototipo / UI / UX / Arquitectura de Header / Eliminación Patrones IA / E-commerce B2B
- **Estado:** Completado (Aislado en `public/prototipo-ux.html`)
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra)

---

## 1. Diagnóstico y Problemas Identificados
Tras la primera iteración, el usuario observó que:
1. **Header mal distribuido:** El prototipo previo condensaba todo en una sola barra apretada con un botón ficticio `Ctrl + K` (estilo herramienta de desarrolladores), careciendo de la distribución estándar de portales de cómputo B2B y del diseño real de Kenya Technology en producción.
2. **Patrones cliché de IA:** Se detectaron elementos típicos que generan los modelos de IA de forma predeterminada:
   - Banners de debugger anunciando métricas y tokens ("PROTOTIPO TÉCNICO / ANTI-SLOP ENGINE").
   - Tres cajitas flotantes de "KPIs" descontextualizadas en el Hero (`+250 Fichas`, `36 Meses`, `100% OEM`).
   - Una sección de "Muestrario de los 7 Estados de UI", que pertenece a una librería de diseño interna y no a la interfaz de usuario de una tienda o catálogo.
   - Command Palette modal (`Ctrl+K`) en lugar de un buscador de productos integrado con autocompletado en el encabezado.

---

## 2. Solución y Nueva Arquitectura del Header (3 Capas Reales)

El nuevo header se reestructuró fielmente a las plataformas B2B de hardware y al layout real de producción de Kenya (`resources/views/layouts/landing.blade.php`):

1. **Capa 1: Top Utility Bar (`#0f172a`, altura 30px):**
   - Contactos de atención directa: Central telefónica `(01) 680-4966`, correo corporativo `ventas@kenya.com.pe` y horarios de oficina.
   - Enlace directo a consulta de **Garantía por Código QR / Serie**.
   - Selector visual de moneda (`USD / PEN`).
   - Acceso para compradores de entidades públicas y empresas privadas.

2. **Capa 2: Main Brand Bar (`#ffffff`, altura 72px):**
   - **Logotipo Principal:** Imagen oficial `theme/images/kenya.png` con altura generosa (44px), nítida y sin subtítulos artificiales.
   - **Buscador Central Prominente:** Input real de e-commerce (`width: 100%`, max 620px) con icono de búsqueda reactivo en naranja `#f26522` y **Dropdown predictivo interactivo en vivo** que muestra coincidencias de modelos, SKUs, procesadores y precios en tiempo real al escribir.
   - **Acciones B2B:** Acceso a **Mis Cotizaciones** con badge de fichas agregadas y botón de perfil de entidad/cliente.

3. **Capa 3: Navigation Bar (Sticky, con Logo Oficial de Perú Compras):**
   - Ítems de navegación: Inicio, Quiénes Somos, Computadoras EZENT, GENWORK, OFISZU SFF, PROWORK WS, Monitores RAITO, Soporte y Contacto.
   - **Integración Prioritaria de Perú Compras:** Botón destacado con el archivo SVG oficial `theme/images/logo_PeruCompras.svg`, fondo tonal `#fef2f2` y enlace directo al catálogo de Acuerdos Marco.
   - Subrayado animado de navegación en naranja de marca `#f26522`.

---

## 3. Reemplazo de Secciones Cliché por Módulos Auténticos de Negocio

- **Eliminación de KPIs y Banners Artificiales:** El Hero ahora presenta una estructura comercial limpia con propuesta de valor para contratación estatal (Acuerdos Marco IM-CE-2020-5 y EXT-CE-2021-1), sellos de calidad ISO 9001, ISO 37001 y tarjeta de hardware con la ficha del producto estrella (EZENT T700 con Intel Core i7 13700).
- **Selector de Gamas Reales de Kenya:** En sustitución del Bento Grid decorativo, se implementó un selector de las 5 líneas de hardware reales de la empresa (`EZENT`, `GENWORK`, `OFISZU`, `PROWORK`, `RAITO`) que filtra de inmediato el catálogo inferior al hacer clic.
- **Filtros Facetados Dinámicos (Estilo CEAM Perú Compras):** Sidebar con casillas de verificación para filtrar por Línea, Procesador, Memoria RAM y Catálogo de Acuerdo Marco, actualizando dinámicamente el conteo de fichas y permitiendo restablecer con un clic.
- **Apertura de Fichas en Nueva Pestaña:** Todas las tarjetas de producto y botones "Ver Ficha" utilizan enlaces nativos con `target="_blank" rel="noopener noreferrer"`, garantizando apertura en pestaña independiente y compatibilidad total con clic derecho.

---

## 4. Verificación y Despliegue
- Archivo intervenido: Únicamente `public/prototipo-ux.html`. Cero impacto en código o vistas de producción.
- Visualización local: `http://localhost/kenya_tienda/public/prototipo-ux.html`.
