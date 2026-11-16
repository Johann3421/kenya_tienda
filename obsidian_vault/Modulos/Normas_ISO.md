---
title: "Módulo Normas Internacionales ISO (9001:2015 y 14001:2015)"
date: 2026-11-15 23:27:00
tags: [modulo, iso-9001, iso-14001, arquitectura]
author: "Antigravity Pair Programmer & Johann Abad"
status: "COMPLETADO"
---

# Módulo Normas ISO — `NormaIsoController` & `norma-iso.blade.php`

> [!IMPORTANT] Principio de Diseño
> Implementado bajo **Ley de Tesler** y **Ponytail**: toda la definición de textos, SEO y lineamientos vive en `NormaIsoController::catalogoNormas()`, mientras `norma-iso.blade.php` permanece libre de lógica de negocio y compartida entre ambas normas sin duplicar estilos ni estructura.

## Rutas Registradas (`routes/web.php`)
- `GET /norma-iso-9001` → `NormaIsoController@iso9001` (`name: iso.9001`)
- `GET /norma-iso-14001` → `NormaIsoController@iso14001` (`name: iso.14001`)

## Extensión Futura (IAF CertSearch)
Cuando se disponga de los enlaces oficiales de verificación IAF CertSearch, basta agregar `'verify_url' => '...'` en el arreglo de `NormaIsoController::catalogoNormas()` y renderizar el badge en `norma-iso.blade.php` condicionado a `!empty($norma['verify_url'])`.

Ver nota cronológica en [[2026-11-15]].
