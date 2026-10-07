# Registro de Cambio: Ampliación de Reciclaje — Rediseño Auténtico Institucional (Cero Plagio Dell)

- **Fecha:** 2026-10-07
- **Tipo:** UI / UX / Identidad de Marca / Reemplazo de Imagen Stock
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Requerimiento
El usuario solicitó corregir los 3 apartados previamente añadidos:
1. **Reemplazo de imagen:** La imagen anterior correspondía a Dell (persona empacando una laptop Dell). Se sustituyó por una fotografía de stock profesional auténtica y libre de marcas.
2. **Eliminación de estética copiada de Dell:**
   - Se removió el fondo verde claro/menta (`#eef8ee`) característico de las páginas ESG de Dell.
   - Se eliminaron los botones rectangulares azules (`#0284c7`) estilo Dell ("Comenzar", "Lectura", "Acceso").
   - Se transformaron los bloques genéricos en módulos institucionales con la paleta de KENYA TECHNOLOGY (blanco nítido, slate `#f8fafc`, verde bosque `#15803d` y azul marino `#1b2633`).

---

## 2. Nueva Fotografía de Stock Profesional
- Se generó una fotografía realista en resolución panorámica (16:9):
  - Especialista técnico de TI empacando componentes de computadoras y laptops en cajas rotuladas para reciclaje electrónico en un taller moderno y ordenado.
  - Equipos multimarca sin logotipos, iluminación natural de oficina técnica y cajas de clasificación de piezas.
- Guardada en:
  - `public/reciclaje-equipo-box.jpg` (744 KB).

---

## 3. Arquitectura Visual Auténtica de KENYA
1. **Hub de Recolección y Reciclaje:**
   - Tarjeta blanca con borde superior verde institucional (`#15803d`) y sombra suave.
   - Badge con pulso verde: `Servicio Gratuito Multimarca`.
   - Grid de 4 beneficios con checks: Multimarca, cero costo, retiro programado y constancia de entrega.
   - Botón de acción: `Coordinar Entrega de Equipos` en verde bosque KENYA + Central telefónica.
   - Fotografía con badge flotante translúcido acreditando el D.S. N° 009-2019-MINAM.
2. **Dúo de Tarjetas de Cumplimiento:**
   - **Nuestras Políticas:** Encabezado con icono ecológico, 4 directivas con checks (Cero vertederos, RoHS, borrado seguro de datos, economía circular) y botón desplegable con chevron para conocer el protocolo operativo paso a paso.
   - **Sistema RAEE:** Encabezado con icono legal, 4 directivas normativas (D.S. N° 009-2019-MINAM, bajas SBN, OEFA, Categoría 3) y botón desplegable con chevron para consultar directivas legales.
3. **Interacción JS:**
   - Función `toggleComplianceDrawer(id, btn)` que maneja la apertura fluida del cajón y alterna el icono chevron y textos.

---

## 4. Archivos Modificados
- `resources/views/reciclaje.blade.php`: Actualización completa de CSS, maquetación HTML y JS.
- `public/reciclaje-equipo-box.jpg`: Fotografía de stock profesional generada e integrada.
