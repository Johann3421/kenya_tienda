# Registro de Cambio: Integración OEM de Fábrica y Detección Automática de Hardware para PCs Kenya

- **Fecha:** 2026-09-21
- **Tipo:** Feature / Hardware / OEM / Frontend / Integración SO
- **Estado:** Completado
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
- El cliente solicitó una solución para que los usuarios de computadoras KENYA (PCs de escritorio y laptops) no tengan que buscar ni escribir manualmente su número de serie para consultar garantía o descargar controladores.
- Dado que las computadoras Kenya tienen su serie arraigada en el firmware OEM/BIOS y en el nombre del equipo, se implementó una integración directa basada en estándares OEM de Microsoft (similar a Dell SupportAssist / Lenovo LSB).

---

## 2. Arquitectura de la Solución

### A. Herramientas OEM de Fábrica (`public/oem/`)
1. **`detectar-pc-kenya.bat`**:
   - Asistente portable de 1 clic (solo lectura WMI/SMBIOS).
   - Extrae la serie mediante `Win32_BIOS`, `Win32_ComputerSystemProduct`, registro OEM o `$env:COMPUTERNAME`.
   - Abre automáticamente el navegador predeterminado en `https://www.kenya.com.pe/consultar/garantia?serie=<SERIAL>&auto=1#controladores`.
2. **`instalar-oem-kenya.bat`**:
   - Script para incluir en la imagen maestra de Windows (Sysprep/OEM en taller).
   - Registra el protocolo personalizado `kenya://` en el Registro de Windows (`HKCR` y `HKCU`).
   - Genera acceso directo en el Escritorio público: *"Soporte y Drivers KENYA"*.
3. **`desinstalar-oem-kenya.bat`**:
   - Limpieza y desinstalación completa si se requiere.
4. **`LEAME_INSTRUCCIONES_OEM.txt`**:
   - Manual de instrucciones para el equipo técnico de ensamblaje de Kenya.

### B. Frontend Web (`resources/views/consultar/garantia.blade.php`)
- **Botón `[🔍 Detectar mi PC Kenya automáticamente]`**:
  - Intenta invocar el protocolo nativo de fábrica `kenya://detect`.
  - Si el equipo cuenta con la integración OEM, Windows ejecuta la lectura y abre la web con la serie precargada en menos de 1 segundo.
  - Si el navegador no tiene el protocolo registrado (Windows reinstalado o no OEM), tras 1.8s se despliega de forma elegante el modal `#modal-detectar-kenya` ofreciendo la descarga directa del detector de 1 clic (`detectar-pc-kenya.bat`) o la guía de serie manual.

---

## 3. Seguridad e Impacto Estructural
- **Riesgo:** 0% de impacto en backend y hardware.
- La consulta WMI es de solo lectura (Read-Only) en memoria SMBIOS. No altera la EEPROM, NVRAM ni voltajes de la BIOS.
- En la base de datos se ejecuta un `SELECT` idéntico a una búsqueda manual por URL param `?serie=`.

---

## 4. Próximos Pasos
- Desplegar cambios a Dokploy.
- Proveer los scripts de `public/oem/` al área técnica de ensamblaje para incluirlos en el Sysprep de fábrica.
