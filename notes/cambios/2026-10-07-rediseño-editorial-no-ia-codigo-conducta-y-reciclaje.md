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
- **Banner de Recolección en Bloque Navy:** Bloque sólido institucional azul marino con fotografía de stock auténtica de reciclaje, texto conciso y botón *"Comenzar"* + central telefónica.
- **Proceso en 4 Pasos:** Línea editorial con numeración técnica (`01`, `02`, `03`, `04`) explicando la entrega, recepción, tratamiento y constancia.
- **Banda Documental:** Tratamiento de *Nuestras Políticas* y *Sistema RAEE* como normativas corporativas con detalle desplegable nativo.
- **FAQ y Sedes:** Preguntas frecuentes con `<details>` limpio y panel lateral de puntos de acopio oficiales (Huánuco y Lima).

---

## 3. Archivos Modificados
- `resources/views/codigo-conducta.blade.php`: Rediseño completo con barra lateral sticky prominente para `iso-37001.png` y navegación activa.
- `resources/views/reciclaje.blade.php`: Maquetación editorial corporativa sin componentes cliché de IA.
- `notes/INDEX.md`: Enlazado en el índice de la bóveda.
