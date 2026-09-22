# Registro de Cambio: Reincorporación de Campo 'Formato' y 'Puertos Mínimos'

- **Fecha:** 2026-09-22
- **Tipo:** UI / Refactor / Normalización de Especificaciones
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
- Solicitud del cliente de restituir el campo de especificación como `'Formato'` (desestimando `'Formato / Chasis'`).
- Reincorporar la característica y fila de `'Puertos Mínimos'` tanto en la visualización web como en los modelos y sincronizador.

---

## 2. Archivos Modificados
- `resources/views/sistema/productos/detalle.blade.php`:
  - Se cambió `'campo' => 'FORMATO / CHASIS'` a `'campo' => 'FORMATO'` en el resumen superior `$topOrdered`.
  - Se cambió `'label' => 'Formato / Chasis'` a `'label' => 'Formato'` en `$oldPcRows`.
  - Se reincorporó la fila `['label' => 'Puertos Mínimos', 'value' => ...]` en `$oldPcRows` para computadoras.
- `app/Http/Controllers/ProductoController.php`:
  - En `$normalizeCampo`: unificación a `'FORMATO'`.
  - En `$canonicalPcOrder`: reemplazo de `'FORMATO / CHASIS'` por `'FORMATO'` y adición de `'PUERTOS MÍNIMOS'`.
- `app/Console/Commands/SyncFichasCommand.php`:
  - En `SPEC_LABELS`: etiqueta `'formato' => 'Formato'`.
  - En `allowedSpecKeysByCategory`: reincorporación de `'puertos_minimos'`.
  - En `normalizePcSpecs`: eliminación de la supresión de `puertos_minimos`.
- `PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md`:
  - En `post_procesar_specs_pc`: eliminado el paso que descartaba `puertos_minimos`.

---

## 3. Decisiones de Arquitectura y UX
- Mantenimiento de la consistencia canónica entre la vista frontend, la plantilla ordenada de modelos en el controlador y el extractor de PDFs.
- Respeto del principio YAGNI y diff quirúrgico sobre los archivos previamente afectados.

---

## 4. Próximos Pasos
- Proceder con los siguientes cambios indicados por el usuario.
