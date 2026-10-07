# Registro de Cambio: Prototipos de Rediseño para Reciclaje Tecnológico y Sostenibilidad RAEE

- **Fecha:** 2026-10-06
- **Tipo:** UI / UX / Prototipado / Limpieza de Activos
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Requerimiento
El cliente solicitó el rediseño del apartado **Reciclaje** (bajo la columna *Nuestra Empresa*), proporcionando:
1. Una captura/maqueta con el texto base:
   - *"Reciclaje responsable de la tecnología, construyamos un futuro sostenible"*
   - Párrafo contextual sobre la masificación de computadoras/laptops y la interrogante: *"¿qué hacemos con ellos?"*
   - Manifiesto del modelo de recuperación y aprovechamiento de componentes para evitar que terminen en vertederos comunes.
2. Fotografía de referencia (familia caminando en bosque templado) que contenía marcas de agua y superposiciones de stock (iStock / Getty Images).
3. Instrucción explícita de eliminar las marcas de agua de la fotografía y desarrollar **3 alternativas en HTML** con diseño profesional y adaptado al ecosistema de KENYA TECHNOLOGY.

---

## 2. Restauración de la Imagen del Bosque
- Se obtuvo la captura en alta resolución (2048×1366 px).
- Se ejecutó un proceso de inpainting digital y reconstrucción armónica de texturas en Python (OpenCV y Pillow):
  - Inpainting y fusión natural del número de referencia en el sendero inferior izquierdo.
  - Reemplazo y difuminado estructural de la superposición translúcida y las letras del badge en el tronco y follaje mediante clones de textura sin distorsión.
- Activo limpio guardado en:
  - `public/bosque-reciclaje.jpg`
  - `prototipos-reciclaje/bosque-reciclaje.jpg`

---

## 3. Prototipos Desarrollados y Verificados

### Opción A — Editorial & Causa Ambiental (`opcion-a-editorial.html`) [SELECCIONADA]
- **Estructura:** 2 columnas asimétricas.
- **Contenido:** Narrativa fiel a la maqueta original con jerarquía sobria, bloque destacado de la pregunta *"¿Qué hacemos con ellos?"*, 3 pilares clave de acción ecológica y tarjeta lateral fija (*sticky*) con la fotografía limpia y botón para solicitud de certificados RAEE.

### Opción B — Split-Screen & Impacto Visual (`opcion-b-impacto.html`)
- **Estructura:** Hero inmersivo de pantalla dividida con fondo azul marino institucional e integración de la fotografía.
- **Componentes:** Barra de métricas de impacto (100% trazabilidad, cero vertederos, valorización de metales y auditorías), desglose visual del ciclo RAEE en 4 fases y directorio de sedes de acopio oficiales (Huánuco y Lima).

### Opción C — Portal Corporativo con Pestañas (`opcion-c-corporativo.html`)
- **Estructura:** Formato interactivo institucional.
- **Componentes:** Tarjeta superior combinada con el manifiesto y la imagen natural, selector de pestañas sin recarga (*Entidades Públicas y Empresas*, *Equipos que Aceptamos*, *Normativa & Certificación RAEE*) y franja de contacto directo.

---

## 4. Implementación Definitiva en Producción
El usuario seleccionó la **Opción A (Editorial)**. Se integró directamente en:
- `resources/views/reciclaje.blade.php`:
  - Hero banner oficial con badge `Gestión Ambiental RAEE · D.S. N° 009-2019-MINAM`.
  - Migas de pan y cabecera institucional.
  - Manifiesto completo y pulido del cliente con pull-quote destacado de la interrogante *"¿Qué hacemos con ellos?"*.
  - 3 pilares de acción ecológica (Economía circular, Manejo certificado RAEE y Preservación de ecosistemas).
  - Columna derecha con la fotografía de bosque limpia (`public/bosque-reciclaje.jpg`), caja de expedición de certificados RAEE y direcciones de puntos de acopio oficiales en Huánuco y Lima.
