# Registro de Cambio: Reestructuración de Columna 1 del Footer a 'Nuestra Empresa', Creación de Nuevas Rutas y Vistas (Código de Conducta y Reciclaje)

- **Fecha:** 2026-10-06
- **Tipo:** UI / UX / Frontend / Routing
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Solicitud del Cliente
El cliente solicitó sustituir por completo la primera columna del footer (`kenya-final-footer`):
- **Anteriormente:** Título "Quiénes somos" con cuatro enlaces fragmentados con hash anchors (`Historia`, `Misión`, `Visión`, `Valores`).
- **Nuevo Requerimiento (según maqueta):**
  - Título de columna: **Nuestra Empresa**
  - Elemento 1: **Quienes Somos** (enlace directo a `route('quienes.somos')` sin anclas de sección).
  - Elemento 2: **Codigo Conducta** (enlace a nueva ruta `route('codigo.conducta')`).
  - Elemento 3: **Reciclaje** (enlace a nueva ruta `route('reciclaje')`).
  - Elemento 4 (indicado con comas suspensivas `,,,,,,,,,,`): eliminado por completo.
- **Simplificación de Quiénes Somos:** La vista `/quienes-somos` regresa a ser una página unificada y estática ("como estaba planeado desde antes"), con Nuestra Historia destacada en la parte superior y la cuadrícula de Misión, Visión y Valores en la parte inferior, eliminando la complejidad innecesaria de intercambio de nodos en el DOM (`activateSection`).

---

## 2. Solución Implementada

### A. Reestructuración del Footer (`resources/views/layouts/landing.blade.php`)
- Se cambió el encabezado de la primera columna a `Nuestra Empresa`.
- Se configuró la lista con los 3 enlaces:
  1. `<a href="{{ route('quienes.somos') }}">Quienes Somos</a>`
  2. `<a href="{{ route('codigo.conducta') }}">Codigo Conducta</a>`
  3. `<a href="{{ route('reciclaje') }}">Reciclaje</a>`

### B. Nuevas Rutas Registradas (`routes/web.php`)
- `Route::view('/codigo-conducta', 'codigo-conducta')->name('codigo.conducta');`
- `Route::view('/reciclaje', 'reciclaje')->name('reciclaje');`

### C. Nuevas Vistas Creadas
1. `resources/views/codigo-conducta.blade.php`:
   - Hero banner corporativo con badge de certificación `ISO 37001:2016` (Sistema de Gestión Antisoborno).
   - Bloque superior en 2 columnas: Declaración institucional de integridad y panel de la norma ISO 37001 (debida diligencia, cero dádivas, controles financieros y protección).
   - Implementación oficial de los **16 principios solicitados por el cliente**:
     1. Objetivos, 2. Alcance, 3. Nuestros valores, 4. Cumplimiento de las leyes, 5. Integridad y prevención del soborno (ISO 37001), 6. Conflictos de interés, 7. Regalos y atenciones, 8. Relación con proveedores y clientes, 9. Relación con autoridades, 10. Protección de información y datos, 11. Uso de bienes y recursos, 12. Canal de consultas y denuncias, 13. Prohibición de represalias, 14. Incumplimientos y medidas disciplinarias, 15. Capacitación y actualización, 16. Declaración de compromiso.
   - Barra de filtros interactivos en vanilla JS por ejes temáticos para exploración ágil y limpia.
   - Bloque de Canal Ético y Denuncias con garantías de confidencialidad, no represalias y canales de comunicación directos.
2. `resources/views/reciclaje.blade.php`:
   - Hero banner institucional de Sostenibilidad y Reciclaje.
   - Bloque principal con Sistema de Manejo y Gestión Ambiental de RAEE (D.S. N° 009-2019-MINAM).
   - Pilares de gestión: Componentes seguros bajo normativa RoHS/CE, Economía circular y valorización de partes, Disposición final segura.
   - Procedimiento de 3 pasos para entrega de equipos en desuso y direcciones de puntos de acopio en Huánuco y Lima (San Isidro).

### D. Unificación Limpia de `quienes-somos.blade.php`
- Se removieron los botones interactivos de intercambio (`card-swap-indicator`) y el script de swapping en el DOM.
- Todos los párrafos de Nuestra Historia permanecen visibles en la sección principal superior.
- Misión, Visión y Valores se organizan de forma balanceada y con alturas simétricas en la cuadrícula inferior.

---

## 3. Archivos Modificados y Creados
- `routes/web.php`: Registro de rutas `codigo.conducta` y `reciclaje`.
- `resources/views/layouts/landing.blade.php`: Reemplazo de columna 1 del footer.
- `resources/views/quienes-somos.blade.php`: Limpieza de script swap y presentación unificada.
- `resources/views/codigo-conducta.blade.php`: Nueva vista de Código de Conducta.
- `resources/views/reciclaje.blade.php`: Nueva vista de Reciclaje y Sistema RAEE.

---

## 4. Verificación
- Validación sintáctica de PHP y Blade (`php -l`) en todos los archivos afectados con 0 errores.
- Rutas resueltas correctamente por Laravel con named routes.
- Diseño consistente y sobrio acorde a la identidad institucional de KENYA Technology.
