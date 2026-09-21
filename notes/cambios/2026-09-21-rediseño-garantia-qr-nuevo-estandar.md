# Registro de Cambio: Rediseño de Vista Garantía QR al Nuevo Estándar

- **Fecha:** 2026-09-21
- **Tipo:** UI / UX / Refactor / Frontend
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
- La vista de consulta de garantía por código QR (`resources/views/consultar/garantiaQR.blade.php`, ruta `/consultar/garantia/{serie}`) mantenía una estructura visual antigua y desfasada respecto a la pantalla principal `/consultar/garantia`.
- Se refactorizó la vista para unificar el 100% del diseño visual, componentes y experiencia de usuario.

---

## 2. Archivos Modificados
- `resources/views/consultar/garantiaQR.blade.php`:
  - Se eliminó el maquetado Bootstrap antiguo y estilos obsoletos de tablas.
  - Se implementó el nuevo Hero con banner, barra de navegación por pestañas en píldoras (Garantía, Controladores, Galería de videos, Términos y condiciones).
  - Se integró la nueva tarjeta de producto con especificaciones en lista con viñetas, imagen de producto y barra de progreso multi-etapa (Vencida, Por vencer, Intermedia, Nueva).
  - Se integró el visor de controladores en doble vista (Cuadrícula y Lista Acordeón categorizado por Audio, Chipset, LAN, VGA, etc.).
  - Se incluyeron los módulos de Galería de videos con carga diferida y visor de Términos y Condiciones con renderizado PDF.js y modal de pantalla completa.
  - Se precarga automáticamente la información del producto escaneado mediante `$garantia` inyectado por el controlador, inicializando Vue en estado `'success'` de forma instantánea.
  - Se configuró la ruta absoluta `{{ url('/consultar/garantia/buscar') }}` para búsquedas posteriores desde URLs con subniveles.

---

## 3. Decisiones de Arquitectura y UX
- **Paridad Visual y de Experiencia:** Tanto si el usuario ingresa por la web principal como si escanea el código QR de la etiqueta física de la PC, la experiencia de consulta es idéntica y consistente.
- **Rendimiento:** Precarga reactiva sin requerir peticiones AJAX adicionales al momento de escanear el QR.

---

## 4. Próximos Pasos
- Desplegar cambios a producción.
