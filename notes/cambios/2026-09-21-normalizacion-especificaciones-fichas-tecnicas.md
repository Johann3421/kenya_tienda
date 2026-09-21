# Registro de Cambio: Normalización y Sanitización de Especificaciones de Fichas Técnicas (Caso EZENT)

- **Fecha:** 2026-09-21
- **Tipo:** Bug Fix / Refactor / Normalización de Datos / Backend & Frontend
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
- A partir de la auditoría y observaciones de fichas técnicas de computadoras (ej. Modelo EZENT Ficha 368 / N° Parte `E7CT6OWNHPXO3B5PV6`), se detectaron campos contaminados con residuos legales, valores cruzados y etiquetas no agrupadas.
- Se implementaron las 6 correcciones solicitadas por el cliente:
  1. **Formato / Chasis:** Unificación de 'Formato' y 'Chasis' bajo la etiqueta `'Formato / Chasis'`.
  2. **Puertos Mínimos:** Eliminación completa de la fila/característica debido a textos legales residuales del auditor.
  3. **Fuente de Poder y Seguridad:** Desacoplamiento de `Seguridad TPM 2.0` de `Fuente de Poder`, dejando la potencia en la fuente e introduciendo la característica independiente `Seguridad` (TPM 2.0).
  4. **Garantía:** Limpieza de prefijos de modelo repetidos (ej. `UNIDAD KENYA TECHNOLOGY EZENT T700...`).
  5. **Empaque y Certificaciones:** Mapeo inteligente que traslada certificaciones (`ROSH, FCC, CE, RAEE`) desde `Empaque` hacia `Certificaciones`, normalizando el empaque a estándar individual.
  6. **Accesorios y Otros:** Filtrado y supresión automática de avisos de propiedad intelectual residuales (`ESPECIFICACIONES TÉCNICAS KENYA TECHNOLOGY Marca Registrada`).

---

## 2. Archivos Modificados
- `resources/views/sistema/productos/detalle.blade.php`:
  - Se agregó bloque de sanitización de variables antes del renderizado de especificaciones para computadoras (`$fuenteRaw`, `$seguridadRaw`, `$empaqueRaw`, `$certificacionesRaw`, `$garantiaRaw`, `$accesoriosRaw`, `$formatoRaw`).
  - Se actualizó `$oldPcRows` eliminando `Puertos Mínimos`, agregando `Seguridad` dinámicamente si está presente, y renombrando `Formato` a `Formato / Chasis`.
  - Se adaptó la cuadrícula resumen superior `$topOrdered` para computadoras.
- `app/Http/Controllers/ProductoController.php`:
  - En `$specPriority`: se agregaron `'Formato / Chasis'`, `'Fuente de Poder'` y `'Seguridad'`.
  - En `$normalizeCampo`: mapeo de `'SEGURIDAD'` y `'TPM'`, y unificación de `'FORMATO / CHASIS'`.
  - En `$canonicalPcOrder`: se removió `'PUERTOS MÍNIMOS'`, se incorporó `'SEGURIDAD'` y se estandarizó `'FORMATO / CHASIS'`.
- `app/Console/Commands/SyncFichasCommand.php`:
  - `PDF_SPEC_TOKENS`: incorporación de tokens para `'FORMATO / CHASIS'`, `'CHASIS'`, `'SEGURIDAD TPM'`, `'SEGURIDAD'`, `'TPM'`.
  - `SPEC_LABELS`: actualización a `'Formato / Chasis'` y adición de `'seguridad' => 'Seguridad'`.
  - `allowedSpecKeysByCategory`: exclusión de `puertos_minimos` e inclusión de `seguridad`.
  - Se creó el método `normalizePcSpecs()` y se integró a `syncEspecificaciones()`.
- `PROMPT_EXTRACCION_PDF_CEAM_AUDITOR.md`:
  - Actualización de diccionario de tokens y adición de función `post_procesar_specs_pc()` para el microservicio de extracción Python en `CEAM_AUDITOR_2.0`.

---

## 3. Decisiones de Arquitectura y UX
- **Corrección en Capas Múltiples:** La sanitización se implementó tanto en la capa de vista (para efecto inmediato en productos ya almacenados en BD) como en el controlador y en el pipeline de sincronización/extracción (para consistencia futura).
- **Ley de Tesler:** El usuario final no tiene que lidiar con inconsistencias de formato ni textos legales desubicados; la limpieza y separación de atributos ocurre de forma transparente.

---

## 4. Próximos Pasos
- Desplegar cambios y validar la visualización de la ficha técnica 368 en el entorno de producción.
