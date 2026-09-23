# Registro de Cambio: Desacoplamiento de Suite Ofimática y Video Integrado en Modelos PC (GENWORK / EZENT)

- **Fecha:** 2026-09-23
- **Tipo:** Bugfix / Normalización / Extracción PDF
- **Estado:** Completado
- **Módulo:** Sincronización de Fichas Técnicas & Vista de Detalle (`SyncFichasCommand` & `detalle.blade.php`)

---

## 1. Contexto y Diagnóstico
En los modelos de PC (en especial **GENWORK** con gráficos integrados y procesadores Intel Core de 13ª y 14ª generación como UHD Graphics 770), el campo `Suite Ofimática` devolvía texto colisionado y absorbido:
`"(Pre-Instalado)No Video Integrado -Intel® UHD Graphics 770 Conectividadº"`

### Causas Raíz:
1. **Falta de variantes con tilde en tokens de Suite Ofimática**:
   El catálogo de Perú Compras rotula `Suite Ofimática (Pre-Instalado)` o `Suite Ofimática (Pre-Instalada)`. El listado de tokens únicamente contemplaba sin tildes (`SUITE OFIMATICA (PRE-INSTALADO)`), por lo que el parser cortaba en `Suite Ofimática` y dejaba `(Pre-Instalado)No` como parte del valor.
2. **Ausencia del token `VIDEO INTEGRADO` y `VÍDEO INTEGRADO`**:
   Los tokens para gráficos solo incluían `TARJETA GRAFICA`, `GRAFICOS` y `VIDEO`. Al no tener `VIDEO INTEGRADO` ni `CONTROLADOR DE VIDEO`, el parser no detectaba el inicio de la siguiente fila de la tabla, haciendo que `Suite Ofimática` devorase toda la fila de video y conectividad.
3. **Restricción de `TARJETA DE VIDEO`**:
   `TARJETA DE VIDEO` no puede ser un token global de cabecera porque aparece como subsección de puertos dentro de la tabla (`Tarjeta de Video: x1 HDMI; x3 DisplayPort`).

---

## 2. Solución Implementada

### A. Comando de Sincronización (`app/Console/Commands/SyncFichasCommand.php`)
1. **Ampliación exhaustiva de tokens (`PDF_SPEC_TOKENS` y `getPdfTokensForModel`)**:
   - Agregadas todas las combinaciones con y sin tilde de `SUITE OFIMÁTICA (PRE-INSTALADO)`, `SUITE OFIMÁTICA (PRE-INSTALADA)`, `SUITE OFIMÁTICA PRE-INSTALADA`, etc.
   - Agregados tokens de video específicos que no colisionan con puertos:
     - `VIDEO INTEGRADO`, `VÍDEO INTEGRADO`
     - `VIDEO DEDICADO`, `VÍDEO DEDICADO`
     - `TARJETA DE VIDEO DEDICADA`, `TARJETA DE VIDEO INTEGRADA`
     - `CONTROLADOR DE VIDEO`, `CONTROLADOR DE VÍDEO`
   - Agregados tokens de delimitación: `CONECTIVIDADº`, `CONECTIVIDAD°`, `CHIPSET PRINCIPAL`, `SONIDO INTEGRADO`.
2. **Normalización y desacoplamiento en `normalizePcSpecs()`**:
   - Detecta si en `suite_ofimatica` viene contenido con `Video` / `Gráficos` y lo extrae a `graficos`.
   - Limpia prefijos y sufijos `(Pre-Instalado)`, normalizando valores a `'No'` o al nombre real de la suite (ej. `'Office Home and Business 2024 Español'`).
   - Sanitiza `graficos`: elimina prefijos (`video integrado:`) y corta colas accidentales de conectividad o sonido.

### B. Vista de Detalle (`resources/views/sistema/productos/detalle.blade.php`)
1. **Defensa activa en presentación**:
   - Desacoplamiento y sanitización en frontend de `$suiteOfimaticaRaw` y `$graficosRaw` antes de renderizar tanto el resumen superior (`$topOrdered`) como la tabla completa (`$oldPcRows`).
   - Evita la colisión visual inmediatamente, incluso para productos que aún no se hayan re-sincronizado en la base de datos.
2. **Aislamiento de resumen para Tóner**:
   - Condición `if (!$isMonitor && !$isToner)` para asegurar que el resumen de suministros/tóner no sea sobreescrito por la lógica de PC.

---

## 3. Archivos Modificados
- [`app/Console/Commands/SyncFichasCommand.php`](file:///c:/xampp/htdocs/kenya_tienda/app/Console/Commands/SyncFichasCommand.php)
- [`resources/views/sistema/productos/detalle.blade.php`](file:///c:/xampp/htdocs/kenya_tienda/resources/views/sistema/productos/detalle.blade.php)
- [`notes/INDEX.md`](file:///c:/xampp/htdocs/kenya_tienda/notes/INDEX.md)

---

## 4. Verificación
- Parsing validado con extractos de fichas de Perú Compras:
  - `Suite Ofimática`: pasa de `(Pre-Instalado)No Video Integrado -Intel® UHD Graphics 770 Conectividadº` a `No`.
  - `Gráficos`: extraído limpiamente como `Integrado - Intel® UHD Graphics 770`.
  - `Puertos Mínimos`: conserva `Tarjeta de Video: x1 HDMI; x3 DisplayPort` sin truncamiento.
- `php -l app/Console/Commands/SyncFichasCommand.php`: Sintaxis validada sin errores.
- Compilación de Blade (`detalle.blade.php`) validada con `BladeCompiler` y `php -l` (cierre de bloque `$isToner` verificado).
