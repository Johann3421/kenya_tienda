# Registro de Cambio: Prototipos de Rediseño para Código de Conducta (ISO 37001-2016)

- **Fecha:** 2026-10-06
- **Tipo:** UI / UX / Prototipado
- **Estado:** En Evaluación por Usuario
- **Rama:** feature/dokploy-postgres-sync

---

## 1. Contexto y Objetivo
El cliente proporcionó una captura/maqueta con el contenido estructurado del **Código de Conducta de KENYA TECHNOLOGY** y su vinculación con la certificación **ISO 37001:2016 (Sistema de Gestión Antisoborno)**, incluyendo:
- 16 lineamientos numerados exhaustivos.
- Infografía de ISO 37001 con diagrama de certificación.
- Solicitud de rediseño profesional que no se perciba genérico de IA ni sobrecargado.

Para permitir que el usuario elija la mejor experiencia, se desarrollaron 3 prototipos funcionales independientes en HTML/CSS/JS con servidor local en el puerto `8099` y se verificaron visualmente mediante subagente de navegador.

---

## 2. Prototipos Desarrollados y Verificados

### Opción A — Editorial (`opcion-a-editorial.html`)
- **Estructura:** 2 columnas asimétricas.
- **Columna izquierda:** Encabezado institucional, introducción oficial y acordeón interactivo de los 16 principios (con toggle "Mostrar todos los detalles").
- **Columna derecha:** Tarjeta *sticky* con la imagen ISO 37001 y caja de acceso directo al Canal Ético de denuncias.
- **Fidelidad:** Es la alternativa más cercana a la maqueta original enviada por el cliente, optimizada con interacción sin scroll infinito.

### Opción B — Documento Normativo (`opcion-b-documento.html`)
- **Estructura:** Formato de documento corporativo / portal de auditoría.
- **Componentes:** Hero Banner azul marino institucional, barra de metadatos (vigencia, alcance, versión e impresión), tabla de contenidos (*TOC*) lateral *sticky* con detección de scroll vía `IntersectionObserver`, secciones 01–16 secuenciales con resalte especial en el punto 05 (Prevención del Soborno) y caja de reporte ético al final.

### Opción C — Tarjetas Ejecutivas (`opcion-c-tarjetas.html`)
- **Estructura:** Formato visual en cuadrícula.
- **Componentes:** Hero con infografía ISO 37001 y 3 métricas de impacto (16 lineamientos, Cero tolerancia, 100% confidencial). Cuadrícula 4×4 de tarjetas interactivas con iconografía diferenciada por lineamiento y tarjeta 05 en contraste azul marino. Franja de contacto ético al pie.

---

## 3. Verificación Automatizada en Navegador
- Se ejecutó servidor local en `http://127.0.0.1:8099/`.
- El subagente de navegador recorrió las 3 opciones, probó el acordeón, el sticky TOC y capturó evidencias gráficas sin errores de consola ni recursos rotos.
- Grabación de sesión guardada como `prototipos_conducta_1791328483388.webp`.

---

## 4. Próximos Pasos
- Selección por parte del usuario de la opción deseada (A, B o C).
- Integración directa de la opción elegida en la vista de producción `resources/views/codigo-conducta.blade.php`.
