# Registro de Cambio: Rediseño Editorial No-IA para Código de Conducta y Reciclaje

- **Fecha:** 2026-10-07
- **Tipo:** UI / UX / Rediseño Editorial Humano / Maquetación Web Profesional
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Requerimiento
El usuario solicitó eliminar cualquier aspecto que se asociara a plantillas genéricas de IA (cards flotantes repetitivas con sombras suaves, badges/pills genéricos, iconos innecesarios en cajas de colores y acordeones artificiales), y reemplazarlo por un diseño y distribución web profesional, sobrio y editorial, tanto para:
- `resources/views/codigo-conducta.blade.php`
- `resources/views/reciclaje.blade.php`

Asimismo, en `codigo-conducta.blade.php`, se solicitó devolver la imagen de la certificación ISO 37001 a su posición lateral con tamaño prominente y legible, tras haber quedado reducida en un intento previo de cabecera.

---

## 2. Decisiones de Arquitectura y Diseño

### A. Código de Conducta (`codigo-conducta.blade.php`)
- **Cabecera Institucional:** Eliminada la miniatura de 240px que encogía la imagen. Cabecera limpia con kicker tipográfico, título principal y lead editorial.
- **Ficha Técnica de Metadatos:** Barra superior con datos normativos (`ISO 37001:2016`, `Ley N° 30424`, alcance y 16 artículos).
- **Columna Izquierda (Artículos):**
  - Lectura continua con numeración tabular en tipografía sobria (`01` a `16`).
  - Resalte sobrio del artículo 05 (Prevención del soborno) como eje antisoborno con nota explicativa.
  - Sección final con tabla corporativa de canales de denuncia y botón oficial para reporte confidencial.
- **Columna Derecha (Sidebar Lateral Sticky de 380px):**
  - Imagen `iso-37001.png` a ancho completo (380px), nítida y visible durante toda la lectura.
  - Caja de Línea Ética y Confidencial con datos de contacto directo.
  - Índice interactivo con `IntersectionObserver` que resalta el artículo visible en tiempo real.

### B. Reciclaje Tecnológico (`reciclaje.blade.php`)
- **Introducción Editorial:** Grid asimétrico con cita destacada tipográfica (*"¿Qué hacemos con ellos cuando dejan de utilizarse?"*) y fotografía del bosque limpia.
- **Proceso en 4 Pasos:** Línea editorial con numeración técnica (`01`, `02`, `03`, `04`) explicando la entrega, recepción, tratamiento y constancia.
- **Banda Documental:** Tratamiento de *Nuestras Políticas* y *Sistema RAEE* como normativas corporativas con detalle desplegable nativo.
- **FAQ y Sedes:** Preguntas frecuentes con `<details>` limpio y panel lateral de puntos de acopio oficiales (Huánuco y Lima).

### C. Ajustes de Banner, Puntos de Acopio y Cita Editorial en Reciclaje
- **Reemplazo de Imagen Exacta:** Se integró la etiqueta exacta requerida por el usuario: `<img src="https://www.kenya.com.pe/reciclaje-equipo-box.jpg" alt="Técnico empacando equipos informáticos en desuso para su reciclaje" loading="lazy">`.
- **Nueva Forma y Color del Banner (`#reciclar`):** Se eliminó el bloque 50/50 azul marino con verde neón (arquetipo de plantilla IA) y se rediseñó como una tarjeta institucional luminosa (`background: #f8fafc`, `border-top: 4px solid #15803d`), contenedor fotográfico enmarcado con relación 4:3 y sombra sutil, lista de garantías del servicio (`✓`) y botón de acción naranja Kenya.
- **Corrección de Distribución de Colores en Puntos de Acopio:**
  - Se estructuró `.rc-points` como una tarjeta formal (`background: #f8fafc`, `border-top: 3px solid var(--rc-accent)`).
  - Se corrigió el botón `.rc-btn-dark` eliminando el fondo azul marino que cambiaba bruscamente a verde oscuro; ahora se unifica en color de acción institucional Naranja Kenya (`var(--rc-accent)` #f26522, hover `#d9541a`), con ancho completo y jerarquía visual armónica con la página.
### D. Ajuste de Ancho Máximo a 1400px y Despliegue Git LFS
- **Ampliación de Envoltorios:** Se ampliaron `#rc-page .rc-wrap`, `#rc-page .rc-hero-inner`, `#cc-page .cc-wrap` y `#cc-page .cc-hero-inner` de `1200px` a `1400px` para aprovechar pantallas de alta resolución.
- **Resolución de Error Git LFS:** Se desactivó la verificación de locks del API de GitHub (`git config lfs.https://github.com/Johann3421/kenya_tienda.git/info/lfs.locksverify false`) permitiendo completar el push del commit `cb9e134`.

### E. Depuración de la Barra Lateral en Código de Conducta
- **Eliminación de `cc-toc`:** Se eliminó la caja de índice con scrollbar interno (`<nav class="cc-toc">`), sus estilos CSS y el observador JS (`IntersectionObserver`).
- **Enfoque Institucional:** La barra lateral sticky queda ahora 100% limpia y enfocada en sus dos pilares clave: la certificación ISO 37001 prominente y la caja de canal ético confidencial con accesos directos de contacto.

---

## 3. Archivos Modificados
- `resources/views/codigo-conducta.blade.php`: Rediseño editorial, barra lateral sticky limpia (sin `cc-toc`) enfocada en ISO 37001 y canal confidencial.
- `resources/views/reciclaje.blade.php`: Maquetación editorial, banner luminoso enmarcado, cita sin borde verde y ancho ampliado a 1400px.
- `notes/INDEX.md`: Enlazado en el índice de la bóveda.
