# Desacoplamiento del Módulo Interactivo de Reciclaje a Vista Independiente (/reciclar)

- **Fecha:** 2026-10-13
- **Tipo:** Refactor / UX Architecture / Desacoplamiento
- **Estado:** Completado
- **Autor/Asistente:** Antigravity (Ponytail Ultra + Caveman Ultra)

---

## 1. Contexto y Objetivo
El usuario indicó: *"que ese apartado se haga en otra pestaña, por que hacerla en la misma pestaña, satura mucho la pestaña"*.
Tener el autoservicio express interactivo (selector de dispositivos, sedes de Huánuco/Lima/recojo y formulario B2B de lotes con Constancia RAEE) incrustado en la misma página de `reciclaje.blade.php` saturaba visual y cognitivamente una vista primordialmente informativa, institucional y normativa.

El objetivo fue desacoplar limpiamente el módulo a una ruta y vista propia (`/reciclar` -> `resources/views/reciclar.blade.php`), abriendo desde los llamados a la acción de `reciclaje.blade.php` en una nueva pestaña (`target="_blank"`), eliminando toda saturación y reduciendo la complejidad del frontend según la Ley de Tesler.

---

## 2. Archivos Modificados y Creados
- `routes/web.php`:
  - Se registró la ruta `Route::view('/reciclar', 'reciclar')->name('reciclar');`
- `resources/views/reciclar.blade.php` (NUEVO):
  - Vista independiente, rápida y ligera extendiendo `layouts.landing`.
  - Diseñada para la acción: Hero limpio, Breadcrumb institucional, selector de perfil en 2 pestañas ("Personas y Hogar" vs "Empresas e Instituciones").
  - Integración WhatsApp con mensaje dinámico preformateado y formulario RAEE formal.
  - Bloque de puntos de acopio (Jr. Crespo y Castillo 480 Huánuco y San Isidro Lima) y retorno a la documentación institucional.
- `resources/views/reciclaje.blade.php`:
  - Se eliminó el bloque interactivo `#iniciar-reciclaje` de más de 150 líneas HTML.
  - Se eliminaron las reglas CSS asociadas (`.rc-flow-box`, `.rc-dev-card`, `.rc-loc-card`, etc.) y el bloque `<script>` final.
  - Se actualizaron los botones del banner y de puntos de acopio para enlazar a `{{ route('reciclar') }}` con `target="_blank"`.

---

## 3. Decisiones de Arquitectura y UX
- **Ley de Tesler:** La página informativa `reciclaje.blade.php` permanece sobria, periodística e institucional, perfecta para consulta de políticas y MINAM. La página `reciclar.blade.php` se enfoca al 100% en la conversión y autoservicio sin distracciones.
- **Sin saturación de pestaña:** Al abrirse con `target="_blank"`, el usuario mantiene abierta la lectura del marco legal y políticas institucionales mientras gestiona su entrega o recojo en la pestaña nueva.
- **Estética Kenya:** Se mantuvieron los tonos institucionales (#15803d, #f26522, #0f172a) sin caer en gradientes cliché de IA ni elementos sobrecargados.

---

## 4. Verificación y Resultados
- Sintaxis PHP validada con `php -l`: Cero errores en `routes/web.php`, `reciclaje.blade.php` y `reciclar.blade.php`.
- Commit de producción: `af92ce4` pusheado a rama `feature/dokploy-postgres-sync`.

