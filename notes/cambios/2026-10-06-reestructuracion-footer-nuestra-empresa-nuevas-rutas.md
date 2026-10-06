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
   - Hero banner corporativo con título y descripción institucional.
   - Bloque principal con compromiso ético y de cumplimiento normativo (aplicable a Convenio Marco / OSCE y sector corporativo).
   - Cuadrícula con 4 pilares: Integridad y anticorrupción, Derechos laborales e inclusión, Confidencialidad y protección de datos (Ley N° 29733), Prácticas comerciales justas y garantía.
   - Canal de integridad y consultas con correo y canales oficiales.
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
