<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title', 'KENYA Technology | Computadoras, Laptops y Equipos B2B en Perú')</title>
    <meta name="description" content="@yield('meta_description', 'KENYA Technology - Fabricante y proveedor de computadoras de escritorio, laptops, servidores y soluciones tecnológicas B2B y Convenio Marco en Perú. Garantía 36 meses On-Site.')">
    <meta name="keywords" content="@yield('meta_keywords', 'computadoras kenya, laps kenya, ofiszu sff, ezent, prowork, equipos b2b peru, convenio marco peru compras, tecnologia peru')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="alternate" type="text/plain" href="{{ url('/llms.txt') }}" title="AI Agent Specification">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <!-- Geo-Positioning SEO for Peru -->
    <meta name="geo.region" content="PE">
    <meta name="geo.placename" content="Lima, Perú">
    <meta name="geo.position" content="-12.046374;-77.042793">
    <meta name="ICBM" content="-12.046374, -77.042793">

    <!-- Open Graph / Facebook / WhatsApp Preview -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', 'KENYA Technology | Computadoras y Equipos B2B')">
    <meta property="og:description" content="@yield('og_description', 'Soluciones tecnológicas B2B y Convenio Marco Perú Compras. Garantía 36 meses On-Site.')">
    <meta property="og:image" content="@yield('og_image', asset('theme/images/kenya.png'))">
    <meta property="og:site_name" content="KENYA Technology">
    <meta property="og:locale" content="es_PE">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('og_url', url()->current())">
    <meta name="twitter:title" content="@yield('og_title', 'KENYA Technology | Computadoras y Equipos B2B')">
    <meta name="twitter:description" content="@yield('og_description', 'Soluciones tecnológicas B2B y Convenio Marco Perú Compras. Garantía 36 meses On-Site.')">
    <meta name="twitter:image" content="@yield('og_image', asset('theme/images/kenya.png'))">

    <!-- Schema.org Base Organization & Geo JSON-LD for AI Search Agents -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "KENYA Technology",
      "legalName": "IMPORTACIONES KENYA",
      "url": "https://www.kenya.com.pe",
      "logo": "{{ asset('theme/images/kenya.png') }}",
      "description": "Fabricante y distribuidor de computadoras de escritorio, laptops y soluciones tecnológicas B2B en Perú. Garantía de 36 meses On-Site a nivel nacional.",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Lima",
        "addressRegion": "Lima",
        "addressCountry": "PE"
      },
      "areaServed": {
        "@type": "Country",
        "name": "Perú"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+51-958021778",
        "contactType": "sales",
        "areaServed": "PE",
        "availableLanguage": "Spanish"
      },
      "knowsAbout": [
        "Computadoras de Escritorio Corporativas",
        "Laptops Corporativas",
        "Convenio Marco Perú Compras",
        "Fichas Técnicas KENYA OFISZU, EZENT, PROWORK",
        "Garantía 36 Meses On-Site Perú"
      ]
    }
    </script>
    @yield('schema_org')

    <!-- Favicons -->
    <link href="{{ asset('landing/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('landing/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('landing/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/venobox/venobox.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/aos/aos.css') }}" rel="stylesheet">


    <!-- Template Main CSS File -->
    <link href="{{ asset('landing/css/style.css') }}?v={{ filemtime(public_path('landing/css/style.css')) }}" rel="stylesheet">
    <!-- UX Refinements — tipografía, escala y márgenes optimizados -->
    <link href="{{ asset('css/ux-refinements.css') }}?v={{ filemtime(public_path('css/ux-refinements.css')) }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .nav-menu>ul>li:before {
            content: "";
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: 2px;
            left: 0;
            background-color: #428bca;
            visibility: hidden;
            width: 0px;
            transition: all 0.3s ease-in-out 0s;
        }

        .nav-menu .active:before {
            visibility: visible;
            width: 100%;
        }
    </style>

<style>
/* ═══════════════════════════════════════════════════════
   BASE LAYOUT
   ═══════════════════════════════════════════════════════ */
*, *::before, *::after {
    box-sizing: border-box;
}

html, body {
    height: 100%;
    min-height: 100%;
    margin: 0;
    padding: 0;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    background-color: #f8f8f8;
    color: #333;
}

#main {
    flex: 1 0 auto;
}

/* Neutralizar el max-width de Bootstrap para el header/footer */
.site-header .container,
.kenya-final-footer .container {
    max-width: none !important;
    padding: 0 !important;
    width: 100% !important;
}

/* ═══════════════════════════════════════════════════════
   HEADER — ESTILO INDEX.HTML
   ═══════════════════════════════════════════════════════ */
.site-header {
    background-color: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    position: sticky;
    top: 0;
    z-index: 1000;
    width: 100%;
    height: 80px;
    border: none;
    padding: 0;
    margin: 0;
}

.site-header .header-content {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    height: 80px !important;
    max-width: 1400px !important;
    margin: 0 auto !important;
    padding: 0 24px !important;
    gap: 24px !important;
    /* Anular Bootstrap flex/display */
    flex-wrap: nowrap !important;
}

/* ── Logo ── */
.site-header .header-left {
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.site-header .header-logo {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.site-header .header-logo-img,
.site-header .header-logo img {
    height: 45px;
    width: auto;
    display: block;
    object-fit: contain;
}

/* ── Buscador ── */
.site-header .header-search {
    flex: 1 1 0;
    max-width: 540px;
    display: flex;
    align-items: center;
}

.site-header .header-search-wrapper {
    position: relative;
    width: 100%;
}

.site-header .header-search-wrapper input,
.site-header #productSearch {
    width: 100%;
    padding: 11px 42px 11px 22px;
    border: 1.5px solid #eaeaea;
    background-color: #f9f9f9;
    border-radius: 30px;
    font-size: 0.95rem;
    font-family: inherit;
    outline: none;
    transition: border-color 0.25s, box-shadow 0.25s, background-color 0.25s;
    color: #333;
    /* Anular Bootstrap form-control */
    box-shadow: none;
    appearance: none;
    -webkit-appearance: none;
}

.site-header .header-search-wrapper input:focus,
.site-header #productSearch:focus {
    border-color: #f26522;
    background-color: #fff;
    box-shadow: 0 0 0 3px rgba(242, 101, 34, 0.12);
}

.site-header .header-search-wrapper > i {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    font-size: 0.95rem;
    cursor: pointer;
    transition: color 0.25s, transform 0.3s ease;
}

.site-header .header-search-wrapper input:focus ~ i {
    color: #f26522;
}

.site-header .header-search-wrapper i.search-clear {
    color: #999;
}
.site-header .header-search-wrapper i.search-clear:hover {
    color: #f26522;
}

/* Resultados de búsqueda */
#searchResults {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    width: 100%;
    background: #fff;
    border: 1px solid #eaeaea;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
    z-index: 9999;
    overflow: hidden;
    display: none;
}

/* ── Navegación desktop ── */
.site-header .kenya-main-nav {
    display: flex;
    align-items: center;
    flex-shrink: 0;
    float: none !important;
}

.site-header .kenya-nav-list {
    list-style: none;
    display: flex;
    align-items: center;
    gap: 26px;
    margin: 0;
    padding: 0;
}

.site-header .kenya-nav-list > li {
    position: relative;
    margin: 0;
    padding: 0;
}

.site-header .kenya-nav-list > li > a,
.site-header .kenya-nav-link {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    padding: 6px 0;
    display: inline-block;
    transition: color 0.25s ease;
    white-space: nowrap;
    line-height: 1;
    /* Anular estilos del template */
    background: none !important;
    border: none !important;
}

/* Subrayado naranja animado */
.site-header .kenya-nav-list > li > a::after,
.site-header .kenya-nav-link::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 0;
    height: 2px;
    background-color: #f26522;
    transition: width 0.28s ease;
}

.site-header .kenya-nav-list > li > a:hover,
.site-header .kenya-nav-link:hover,
.site-header .kenya-active > a,
.site-header .kenya-active .kenya-nav-link {
    color: #f26522 !important;
}

.site-header .kenya-nav-list > li > a:hover::after,
.site-header .kenya-nav-link:hover::after,
.site-header .kenya-active > a::after,
.site-header .kenya-active .kenya-nav-link::after {
    width: 100%;
}

/* Ocultar ícono de home dentro del texto del nav */
.site-header .kenya-nav-icon {
    display: none;
}

/* ── Botón búsqueda móvil ── */
.site-header .kenya-search-toggle {
    display: none;
}

/* ── Botón hamburguesa ── */
.site-header .kenya-mobile-menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.7rem;
    cursor: pointer;
    color: #333;
    padding: 4px 6px;
    line-height: 1;
    flex-shrink: 0;
    transition: color 0.25s;
}

.site-header .kenya-mobile-menu-toggle:hover {
    color: #f26522;
}

/* ── Menú móvil desplegable ── */
.kenya-mobile-menu {
    display: none;
    background: #fff;
    box-shadow: 0 6px 16px rgba(0,0,0,0.09);
    border-top: 1px solid #f0f0f0;
    position: sticky;
    top: 80px;
    z-index: 999;
    width: 100%;
}

.kenya-mobile-menu.active {
    display: block;
}

.kenya-mobile-menu ul {
    list-style: none;
    margin: 0;
    padding: 8px 0;
}

.kenya-mobile-menu ul li a {
    display: block;
    padding: 13px 24px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    border-bottom: 1px solid #f4f4f4;
    transition: color 0.25s, background 0.25s;
}

.kenya-mobile-menu ul li:last-child a {
    border-bottom: none;
}

.kenya-mobile-menu ul li a:hover {
    color: #f26522;
    background-color: #fff8f5;
}

/* ── Responsive header ── */
@media (max-width: 1100px) {
    .site-header .kenya-nav-list {
        gap: 18px;
    }
    .site-header .kenya-nav-link,
    .site-header .kenya-nav-list > li > a {
        font-size: 0.78rem;
    }
}

@media (max-width: 991px) {
    .site-header {
        height: auto;
        position: sticky;
        top: 0;
    }
    .site-header .header-content {
        flex-wrap: wrap !important;
        height: auto !important;
        padding: 10px 16px !important;
        gap: 6px !important;
        row-gap: 8px !important;
    }
    .site-header .header-logo-img,
    .site-header .header-logo img {
        height: 36px !important;
    }
    .site-header .kenya-main-nav {
        display: none !important;
    }
    .site-header .kenya-mobile-menu-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2px 4px !important;
        font-size: 1.5rem !important;
    }
    .site-header .header-search {
        display: none;
        order: 3;
        max-width: 100%;
        width: 100%;
        flex: 1 1 100%;
    }
    .site-header .header-search.active {
        display: flex;
    }
    .site-header .header-search-wrapper input,
    .site-header #productSearch {
        padding: 9px 38px 9px 16px !important;
    }
    .site-header .kenya-search-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #333;
        padding: 4px 6px;
        line-height: 1;
        flex-shrink: 0;
        transition: color 0.25s;
    }
    .site-header .kenya-search-toggle:hover {
        color: #f26522;
    }
    .kenya-mobile-menu {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
    }
}

@media (max-width: 576px) {
    .site-header .header-content {
        padding: 8px 12px !important;
        row-gap: 6px !important;
    }
    .site-header .header-logo-img,
    .site-header .header-logo img {
        height: 30px !important;
    }
    .site-header .header-search-wrapper input,
    .site-header #productSearch {
        font-size: 16px !important;
        padding: 8px 36px 8px 14px !important;
    }
}

/* ═══════════════════════════════════════════════════════
   PDF PRINT
   ═══════════════════════════════════════════════════════ */
#print-pdf-container {
    width: 210mm;
    min-height: 297mm;
    background: white;
    padding: 20mm;
    box-sizing: border-box;
    color: black;
    font-family: 'Arial', sans-serif;
    display: none;
}

#print-pdf-container.printing {
    display: block;
}

/* ═══════════════════════════════════════════════════════
   FOOTER
   ═══════════════════════════════════════════════════════ */
.kenya-footer-bottom-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.kenya-right-section {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 18px;
}

.kenya-complaint-book,
.kenya-login-link {
    color: rgba(255,255,255,0.85);
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.2s ease;
}

.kenya-complaint-book:hover,
.kenya-login-link:hover {
    color: #fff;
    text-decoration: underline;
}

.kenya-footer-fullwidth {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 24px;
}

.kenya-contact-list li {
    display: flex !important;
    align-items: flex-start !important;
    gap: 8px !important;
}

.kenya-contact-list li .kenya-icon {
    flex-shrink: 0;
    margin-top: 3px;
    width: 14px;
    text-align: center;
}

.kenya-contact-list li span {
    line-height: 1.45;
}
</style>

    @yield('css')
</head>

<body>
@hasSection('hide_header_footer')
        {{-- No mostrar header/footer --}}
    @else
    <!-- ======= Header (redesign v2) ======= -->
    <header class="site-header">
        <div class="container header-content">
            <div class="header-left">
                <a href="{{ url('/') }}" class="header-logo">
                    @php
                        $logo_sistema = App\Models\Configuracion::where('nombre', 'logo_sistema')->first();
                    @endphp
                    @if ($logo_sistema && $logo_sistema->archivo)
                        <img src="{{ asset('storage/' . $logo_sistema->archivo_ruta . '/' . $logo_sistema->archivo) }}"
                            alt="KENYA Logo" class="header-logo-img">
                    @else
                        <img src="{{ asset('theme/images/kenya.png') }}" alt="KENYA" class="header-logo-img">
                    @endif
                </a>
            </div>
            <div class="header-search">
                <div class="header-search-wrapper">
                    <input id="productSearch" type="search" placeholder="Buscar productos...">
                    <i id="searchIcon" class="fa-solid fa-magnifying-glass"></i>
                    <div id="searchResults"></div>
                </div>
            </div>

            @yield('menu')

            <!-- User Auth Dropdown -->
            <div class="kenya-user-dropdown" style="position: relative; margin-left: 15px; display: flex; align-items: center;">
                @if(Auth::guard('cliente')->check())
                    <button id="userMenuToggle" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; color: #333;">
                        <div style="position: relative;">
                            <i class="fa-solid fa-user-circle" style="font-size: 1.5rem; color: #ee7c31;"></i>
                            <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #22cca6; border: 2px solid #fff; border-radius: 50%;"></span>
                        </div>
                        @php
                            $nombresArr = explode(' ', trim(Auth::guard('cliente')->user()->nombres ?? 'Cliente'));
                            if (count($nombresArr) >= 3) {
                                // Formato RUC: PATERNO MATERNO NOMBRES
                                // ej: ABAD CAMPOS JOHANN CRISTOPHER
                                // [0] = ABAD, [1] = CAMPOS, [2] = JOHANN
                                $shortName = ucfirst(strtolower($nombresArr[2])) . ' ' . strtoupper(substr($nombresArr[0], 0, 1)) . '.';
                            } else {
                                // Formato corto o empresa: IMPORTACIONES KENYA
                                $shortName = ucfirst(strtolower($nombresArr[0] ?? 'Cliente'));
                                if (isset($nombresArr[1])) {
                                    $shortName .= ' ' . strtoupper(substr($nombresArr[1], 0, 1)) . '.';
                                }
                            }
                        @endphp
                        <span class="d-none d-md-inline" style="font-weight: 500; font-size: 0.9rem;">{{ $shortName }} <i class="fa-solid fa-chevron-down" style="font-size: 0.7rem;"></i></span>
                    </button>
                    <div id="userMenuContent" style="display: none; position: absolute; top: 100%; right: 0; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 8px; min-width: 200px; z-index: 1000; overflow: hidden; margin-top: 10px;">
                        <ul style="list-style: none; margin: 0; padding: 0;">
                            <li><a href="{{ url('/mi-perfil') }}" style="display: block; padding: 10px 15px; color: #555; text-decoration: none; font-size: 0.9rem; transition: background 0.2s;"><i class="fa-solid fa-id-card" style="width: 20px; color: #ee7c31;"></i> Mi Perfil</a></li>
                            <li><a href="{{ url('/mis-cotizaciones') }}" style="display: block; padding: 10px 15px; color: #555; text-decoration: none; font-size: 0.9rem; transition: background 0.2s;"><i class="fa-solid fa-file-invoice-dollar" style="width: 20px; color: #ee7c31;"></i> Mis Cotizaciones</a></li>
                            <li style="border-top: 1px solid #eee;">
                                <form action="{{ url('/cliente/logout') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" style="width: 100%; text-align: left; background: none; border: none; display: block; padding: 10px 15px; color: #dc3545; font-size: 0.9rem; cursor: pointer; transition: background 0.2s;"><i class="fa-solid fa-sign-out-alt" style="width: 20px;"></i> Cerrar Sesión</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ url('/acceso-clientes') }}" style="color: #555; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-regular fa-user-circle" style="font-size: 1.5rem;"></i>
                        <span class="d-none d-md-inline" style="font-weight: 500; font-size: 0.9rem;">Ingresar</span>
                    </a>
                @endif
            </div>

            <button class="kenya-search-toggle" id="kenyaSearchToggle" title="Buscar">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <button class="kenya-mobile-menu-toggle" id="kenyaMobileMenuToggle" title="Menú">
                <i class="bx bx-menu"></i>
            </button>
        </div>

        <nav class="kenya-mobile-menu" id="kenyaMobileMenu">
            <ul>
                <li><a href="{{ url('/') }}"><i class="bx bx-home"></i> Inicio</a></li>
                <li><a href="{{ route('quienes.somos') }}">Quienes Somos</a></li>
                <li><a href="{{ route('catalogo') }}">Catalogo</a></li>
                <li><a href="{{ route('novedades') }}">Novedades</a></li>
                <li><a href="{{ route('consultar.garantia') }}">Soporte</a></li>
                {{-- Sorteo temporalmente oculto en producción --}}
                {{-- <li><a href="{{ route('serial.draw') }}">🎁 Sorteo</a></li> --}}
                <li><a href="{{ route('contactenos') }}">Contáctenos</a></li>
                @if(Auth::guard('cliente')->check())
                    <li><a href="{{ url('/mi-perfil') }}"><i class="bx bx-user"></i> Mi Perfil</a></li>
                    <li><a href="{{ url('/mis-cotizaciones') }}"><i class="bx bx-file"></i> Mis Cotizaciones</a></li>
                    <li>
                        <form action="{{ url('/cliente/logout') }}" method="POST" style="margin:0;padding:0;">
                            @csrf
                            <button type="submit" style="background:none;border:none;padding:13px 24px;color:#dc3545;font-weight:500;font-size:0.95rem;width:100%;text-align:left;cursor:pointer;"><i class="bx bx-log-out"></i> Cerrar Sesión</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ url('/acceso-clientes') }}"><i class="bx bx-lock-open"></i> Ingresar</a></li>
                @endif
            </ul>
        </nav>
    </header><!-- End Header -->
@endif
    <main id="main">
        @yield('content')
    </main>

    @hasSection('hide_header_footer')
        {{-- No mostrar footer --}}
    @else
    <!-- ======= Footer Kenya ======= -->
    <!-- Footer KENYA - Versión mejorada -->
    <footer class="kenya-final-footer">
        <div class="kenya-footer-fullwidth">
            <div class="kenya-footer-columns">
                <!-- Columna 1: Información -->
                <div class="kenya-footer-col">
                    <h3 class="kenya-footer-heading">Información</h3>
                    <ul class="kenya-footer-list">
                        <li><a href="{{ route('quienes.somos') }}">Quiénes somos</a></li>
                        <li><a href="{{ route('quienes.somos') }}">Misión y Visión</a></li>
                        <li><a href="{{ route('quienes.somos') }}">Historia</a></li>
                    </ul>
                </div>

                <!-- Columna 2: Atención al cliente -->
                <div class="kenya-footer-col">
                    <h3 class="kenya-footer-heading">Atención al cliente</h3>
                    <ul class="kenya-footer-list">
                        <li><a href="{{ route('consultar.garantia') }}">Consulta el estado de tu Producto</a></li>
                        <li><a href="{{ route('contactenos') }}">Preguntas frecuentes</a></li>
                        <li><a href="{{ route('consultar.garantia') }}#terms">Términos y condiciones de garantía</a></li>
                    </ul>
                </div>

                <!-- Columna 3: Videos tutoriales -->
                <div class="kenya-footer-col">
                    <h3 class="kenya-footer-heading">CONTROLADORES</h3>
                    <ul class="kenya-footer-list">
                        <li><a href="{{ route('consultar.garantia') }}">Descargar controladores</a></li>
                        <li><a href="{{ route('consultar.garantia') }}">Estado de la garantía</a></li>
                        <li><a href="{{ route('consultar.garantia') }}">Problemas con la activación</a></li>
                    </ul>
                </div>

                <!-- Columna 4: Contáctanos -->
                <div class="kenya-footer-col">
                    <h3 class="kenya-footer-heading">Contáctanos</h3>
                    <ul class="kenya-footer-list kenya-contact-list">
                        <li>
                            <i class="kenya-icon fas fa-map-marker-alt"></i>
                            <span><strong>Principal:</strong> Jr Huallayco N° 135 - Huánuco</span>
                        </li>
                        <li>
                            <i class="kenya-icon fas fa-map-marker-alt"></i>
                            <span><strong>Oficina:</strong> Av. Pablo Carriquiry N° 455 - San Isidro - Lima - Perú</span>
                        </li>
                        <li>
                            <i class="kenya-icon fas fa-envelope"></i>
                            <span>acuerdos.marco@kenya.com.pe</span>
                        </li>
                        <li>
                            <i class="kenya-icon fas fa-envelope"></i>
                            <span>soporte@kenya.com.pe</span>
                        </li>
                        <li>
                            <i class="kenya-icon fab fa-whatsapp"></i>
                            <span>958 021 778</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer inferior -->
            <div class="kenya-footer-bottom">
                <div class="kenya-footer-bottom-content">
                    <div class="kenya-copyright">
                        © Copyright Kenya - Todos los derechos reservados
                    </div>
                    <div class="kenya-right-section">
                        <a href="{{ route('reclamaciones') }}" class="kenya-complaint-book">
                            <i class="fas fa-book-open"></i> Libro de reclamaciones
                        </a>

                        <a href="{{ url('/login') }}" title="Iniciar sesión" class="kenya-login-link">
                            <i class="fas fa-user"></i> Iniciar sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- End Footer -->


    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
@endif
    <!-- Vendor JS Files -->
    <script src="{{ asset('landing/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('landing/vendor/jquery-sticky/jquery.sticky.js') }}"></script>
    <script src="{{ asset('landing/vendor/venobox/venobox.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/aos/aos.js') }}"></script>

    <!-- Toastr para notificaciones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('landing/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.4/dist/axios.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>
    <script>
        (function(){
            const input = document.getElementById('productSearch');
            const resultsBox = document.getElementById('searchResults');
            let timer = null;

            function renderResults(items) {
                if (!resultsBox) return;
                if (!items || items.length === 0) {
                    resultsBox.style.display = 'none';
                    resultsBox.innerHTML = '';
                    return;
                }
                resultsBox.style.display = 'block';
                resultsBox.innerHTML = items.map(i => `
                    <a href="${i.url}" class="search-item" style="display:flex; gap:12px; padding:10px; border-bottom:1px solid #f2f2f2; align-items:center; text-decoration:none; color:#333;">
                        <img src="${i.img}" style="width:56px; height:56px; object-fit:cover; border-radius:6px;" alt="${i.nombre}">
                        <div style="flex:1">
                            <div style="font-weight:600;">${i.nombre}</div>
                            <div style="font-size:12px; color:#666;">${(i.descripcion || '').substring(0,120)}</div>
                        </div>
                    </a>`).join('');
            }

            async function doSearch(q){
                if (!q || q.trim().length < 2) {
                    renderResults([]);
                    return;
                }
                try {
                    const res = await fetch(`{{ route('search.products') }}?q=` + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    renderResults(json.data || []);
                } catch (err) {
                    console.error('Search error', err);
                    renderResults([]);
                }
            }

            const searchIcon = document.getElementById('searchIcon');

            if (input && searchIcon) {
                input.addEventListener('input', function(e){
                    clearTimeout(timer);
                    const q = e.target.value;
                    timer = setTimeout(() => doSearch(q), 300);
                });

                input.addEventListener('focus', function() {
                    searchIcon.className = 'fa-solid fa-xmark';
                    searchIcon.classList.add('search-clear');
                });

                input.addEventListener('blur', function() {
                    searchIcon.className = 'fa-solid fa-magnifying-glass';
                    searchIcon.classList.remove('search-clear');
                });

                searchIcon.addEventListener('click', function() {
                    if (input === document.activeElement && input.value) {
                        input.value = '';
                        input.blur();
                        renderResults([]);
                        searchIcon.className = 'fa-solid fa-magnifying-glass';
                        searchIcon.classList.remove('search-clear');
                    } else {
                        input.focus();
                    }
                });
            }

            // Mobile search toggle
            const searchToggle = document.getElementById('kenyaSearchToggle');
            const headerSearch = document.querySelector('.header-search');
            if (searchToggle && headerSearch) {
                searchToggle.addEventListener('click', function() {
                    headerSearch.classList.toggle('active');
                    if (headerSearch.classList.contains('active')) {
                        input?.focus();
                    } else {
                        input?.blur();
                    }
                });
                // Close search when clicking outside header
                document.addEventListener('click', function(e) {
                    if (headerSearch.classList.contains('active') && !e.target.closest('.site-header')) {
                        headerSearch.classList.remove('active');
                    }
                });
            }

            // Ensure dropdown width and positioning follow the input
            function syncSearchDropdown() {
                if (!input || !resultsBox) return;
                // make parent of input a positioned container (in case inline styles change)
                const parent = input.parentElement;
                if (parent) parent.style.position = parent.style.position || 'relative';
                // ensure results box fills the parent width
                resultsBox.style.width = '100%';
                resultsBox.style.left = '0';
                resultsBox.style.transform = 'none';
            }

            // initial sync and on resize
            syncSearchDropdown();
            window.addEventListener('resize', syncSearchDropdown);

            // close on outside click
            document.addEventListener('click', function(ev){
                if (!resultsBox) return;
                if (!resultsBox.contains(ev.target) && ev.target !== input) {
                    resultsBox.style.display = 'none';
                }
            });
        })();
    </script>

    <!-- Script para Menú Móvil -->
    <script>
        (function(){
            const toggleBtn = document.getElementById('kenyaMobileMenuToggle');
            const mobileMenu = document.getElementById('kenyaMobileMenu');
            const menuLinks = mobileMenu ? mobileMenu.querySelectorAll('a') : [];

            if (toggleBtn && mobileMenu) {
                // Toggle menu on button click
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileMenu.classList.toggle('active');
                });

                // Close menu when clicking on a link
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.remove('active');
                    });
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.site-header')) {
                        mobileMenu.classList.remove('active');
                    }
                });

                // Close menu on scroll
                window.addEventListener('scroll', function() {
                    if (mobileMenu.classList.contains('active')) {
                        mobileMenu.classList.remove('active');
                    }
                });
            }
        })();
    </script>

    @yield('js')

    <!-- Script para el dropdown de usuario -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuToggle = document.getElementById('userMenuToggle');
            const userMenuContent = document.getElementById('userMenuContent');

            if (userMenuToggle && userMenuContent) {
                userMenuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (userMenuContent.style.display === 'none') {
                        userMenuContent.style.display = 'block';
                    } else {
                        userMenuContent.style.display = 'none';
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!userMenuToggle.contains(e.target) && !userMenuContent.contains(e.target)) {
                        userMenuContent.style.display = 'none';
                    }
                });
            }
        });
    </script>
    <!-- Floating CEM WhatsApp Conversion Widget -->
    <a href="https://wa.me/+51958021778?text={{ urlencode('¡Hola KENYA Technology! Quisiera solicitar cotización de computadoras y equipos corporativos.') }}" 
       target="_blank" 
       rel="noopener" 
       title="Cotizaciones y Ventas Corporativas por WhatsApp" 
       style="position: fixed; bottom: 25px; right: 25px; width: 56px; height: 56px; background-color: #25d366; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 18px rgba(37,211,102,0.45); z-index: 9999; text-decoration: none; transition: transform 0.2s ease;"
       onmouseover="this.style.transform='scale(1.1)';"
       onmouseout="this.style.transform='scale(1)';"
    >
        <i class="fa-brands fa-whatsapp" style="font-size: 32px;"></i>
    </a>
</body>

</html>

