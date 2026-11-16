---
title: "Changelog — KENYA Tienda"
date: 2026-11-15 23:27:00
tags: [changelog, releases, kenya-tienda]
author: "Antigravity Pair Programmer & Johann Abad"
status: "COMPLETADO"
---

# Changelog Formal — KENYA Tienda

## [2026-11-15] — Normas ISO 9001:2015, ISO 14001:2015 y Footer Nuestra Empresa

### `#feat` Nuevas Funcionalidades
- Incorporación de vistas públicas `/norma-iso-9001` (`iso.9001`) y `/norma-iso-14001` (`iso.14001`) mediante [[Normas_ISO|NormaIsoController]] y plantilla única `resources/views/norma-iso.blade.php`.
- Adición de activos visuales corporativos `public/iso-9001.jpg` y `public/iso-14001.jpg`.
- Registro de ambas rutas en `SitemapController.php` para indexación SEO.

### `#ui` / `#refactor` Footer y Navegación
- Actualización de la columna **Nuestra Empresa** en `resources/views/layouts/landing.blade.php`: enlace `Codigo Conducta` ocultado en footer (preservando la ruta directa `/codigo-conducta`) y adición de `Norma ISO 9001:2015` y `Norma ISO 14001:2015`.

### `#docs` Documentación
- Registro en [[2026-11-15]] y actualización de `notes/INDEX.md` + `notes/cambios/2026-11-15-normas-iso-9001-14001-footer.md`.
