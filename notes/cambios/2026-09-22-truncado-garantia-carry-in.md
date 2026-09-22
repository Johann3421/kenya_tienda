# Registro de Cambio: Truncado de Garantía hasta 'CARRY-IN'

- **Fecha:** 2026-09-22
- **Tipo:** Sanitización / Limpieza de Datos / UI
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
- Solicitud de acotar el texto de la especificación de Garantía para que finalice exactamente en `'CARRY-IN'` (o variantes como `'CARRY IN'`, `'ON-SITE'`), suprimiendo todo texto repetido o residual posterior (`UNIDAD KENYA TECHNOLOGY EZENT V1_MT ET7S20W15T`, modelos, marcas o seriales).
- Ejemplo de transformación:
  - Entrada: `36 MESES CARRY-IN UNIDAD KENYA TECHNOLOGY EZENT V1_MT ET7S20W15T`
  - Salida: `36 MESES CARRY-IN`

---

## 2. Archivos Modificados
- `resources/views/sistema/productos/detalle.blade.php`:
  - En `$garantiaRaw`: agregada expresión regular con recorte exacto en `\bCARRY[\s\-]IN\b` o `\bON[\s\-]SITE\b`, descartando todo el texto posterior.
- `app/Console/Commands/SyncFichasCommand.php`:
  - En `normalizePcSpecs`: agregada la misma regla de truncado para las especificaciones procesadas durante la sincronización.
- `PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md`:
  - En `post_procesar_specs_pc`: agregada la lógica en Python con `re.search` para cortar en `CARRY-IN` y limpiar residuales.

---

## 3. Decisiones de Arquitectura y UX
- **Ley de Tesler:** El frontend sanitiza directamente la cadena antes de renderizar la vista, asegurando que cualquier producto previamente sincronizado en la base de datos se muestre limpio y legible sin esperar una resincronización de BD.
- **Tolerancia a formatos:** Acepta tanto `CARRY-IN` como `CARRY IN`, insensible a mayúsculas/minúsculas.

---

## 4. Próximos Pasos
- Proceder con las siguientes instrucciones o revisiones que solicite el usuario.
