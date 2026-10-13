# Registro de Cambio: Integración de Enlace IAF CertSearch ISO 37001 y Retiro de Sección cc-report

- **Fecha:** 2026-10-13
- **Tipo:** UI / Compliance / Auditoría / Refactor
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Requerimiento
El usuario solicitó:
1. Incluir el enlace oficial de verificación del certificado ISO 37001:2025 de KENYA TECHNOLOGY alojado en la base de datos global de acreditación IAF CertSearch:
   `https://www.iafcertsearch.org/certification/coab4xBQ2sckoPhoTI1foD8G`
   de forma sutil pero diferenciada.
2. Quitar por completo la sección `<section class="cc-report" id="canal-denuncias">` de la columna de artículos, dado que los canales oficiales de contacto y reporte ético ya están presentados de forma clara en la barra lateral fija (`.cc-sidebar-box`).

---

## 2. Decisiones de Arquitectura y Diseño
1. **Presentación de Verificación Sutil pero Diferenciada:**
   - **Metadatos Superiores (`.cc-meta`):** Se integró un badge con etiqueta de verificación (`.cc-verified-tag`) con icono de escudo y texto "Verificado", enlazando directamente al registro oficial de IAF CertSearch con indicador visual de enlace externo.
   - **Tarjeta Lateral de Certificado (`.cc-iso-card`):**
     - La imagen de la certificación `iso-37001.png` se enlazó hacia la URL de validación.
     - Se añadió un botón de validación integrado en el pie de la tarjeta (`.cc-iso-verify-btn`) con el check verde oficial (`fa-circle-check`), texto *"Verificado en IAF CertSearch"* y flecha de salida externa, que se ilumina con el naranja institucional Kenya al hacer hover.
2. **Depuración de Complejidad y Código Muerto (Ponytail Ultra):**
   - Se removió el bloque `<section class="cc-report" id="canal-denuncias">` junto con su tabla y notas redundantes.
   - Se eliminaron las reglas CSS huérfanas de `.cc-report`, `.cc-table`, `.cc-fine` y la media query móvil exclusiva de la tabla.
   - Se preservó la clase `.cc-btn` utilizada por el botón del canal confidencial en el sidebar.

---

## 3. Archivos Modificados
- `resources/views/codigo-conducta.blade.php`: Inclusión de validación IAF CertSearch en metadatos y sidebar; remoción de `.cc-report` y depuración de CSS.
- `notes/INDEX.md`: Indexación de la presente nota en la bóveda.

---

## 4. Próximos Pasos
- Desplegar cambios a producción cuando el usuario lo indique.
