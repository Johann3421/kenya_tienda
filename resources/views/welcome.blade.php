@extends('layouts.landing')
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li class="kenya-active"><a href="{{ url('/') }}" class="kenya-nav-link"><i
                        class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catalogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            {{-- Sorteo temporalmente oculto en producción --}}
            {{-- <li><a href="{{ route('serial.draw') }}" class="kenya-nav-link">🎁 Sorteo</a></li> --}}
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('css')
    <style>
        /* Registro de fuente personalizada Kenya */
        @font-face {
            font-family: 'Kenyav1';
            src: url('/Kenyav1-Regular.otf') format('opentype');
        }

        /* Tipografías de líneas de producto Kenya */
        @font-face {
            font-family: 'EzentFont';
            src: url('/TIPOGRAFIA%20KENYA/EZENT/Ezent-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/EZENT/Ezent-Regular.otf') format('opentype');
        }
        @font-face {
            font-family: 'GenworkFont';
            src: url('/TIPOGRAFIA%20KENYA/GENWORK/Genwork-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/GENWORK/Genwork-Regular.otf') format('opentype');
        }
        @font-face {
            font-family: 'OfiszuFont';
            src: url('/TIPOGRAFIA%20KENYA/OFISZU%20Y%20HENKO/OfiszuYHenko-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/OFISZU%20Y%20HENKO/OfiszuYHenko-Regular.otf') format('opentype');
        }
        @font-face {
            font-family: 'ProworkFont';
            src: url('/TIPOGRAFIA%20KENYA/PROWORK/Prowork-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/PROWORK/Prowork-Regular.otf') format('opentype');
        }
        @font-face {
            font-family: 'RaitoFont';
            src: url('/TIPOGRAFIA%20KENYA/RAITO/Raito-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/RAITO/Raito-Regular.otf') format('opentype');
        }

        /* Clases de marca para aplicar cada tipografía */
        .brand-ezent    .prod-title, .brand-ezent    .prod-overlay-text { font-family: 'EzentFont', sans-serif; }
        .brand-genwork  .prod-title, .brand-genwork  .prod-overlay-text { font-family: 'GenworkFont', sans-serif; }
        .brand-ofiszu   .prod-title, .brand-ofiszu   .prod-overlay-text { font-family: 'OfiszuFont', sans-serif; }
        .brand-henko    .prod-title, .brand-henko    .prod-overlay-text { font-family: 'OfiszuFont', sans-serif; }
        .brand-prowork  .prod-title, .brand-prowork  .prod-overlay-text { font-family: 'ProworkFont', sans-serif; }
        .brand-raito    .prod-title, .brand-raito    .prod-overlay-text { font-family: 'RaitoFont', sans-serif; }

        .site-width {
            margin: 0 auto;
            padding: 0;
            box-sizing: border-box;
            width: 100%;
        }

        /* Ensure product cards fit inside the constrained width */
        .site-width .prod-filter-container {
            gap: 20px;
            justify-content: center !important;
        }

        /* Grid de categorías centrado y simétrico (4 items) */
        #portfolio-flters {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            justify-content: center !important;
            justify-items: center !important;
            align-items: center !important;
            gap: 20px !important;
            width: 100% !important;
            max-width: 900px !important;
            margin: 0 auto 30px auto !important;
            padding: 0 20px !important;
            list-style: none !important;
        }

        @media (max-width: 991px) {
            #portfolio-flters {
                grid-template-columns: repeat(2, 1fr) !important;
                max-width: 500px !important;
            }
        }

        @media (max-width: 480px) {
            #portfolio-flters {
                grid-template-columns: repeat(2, 1fr) !important;
                max-width: 320px !important;
            }
        }

        .contorno {
            border: 1px solid #cecece;
            border-radius: 2px;
            background-color: #fff
        }

        .descripcion {
            padding: 7px 9px;
        }

        .p-nombre {
            font-family: "Inter", sans-serif;
            color: #444;
            font-weight: 600;
        }

        .p-nombre:hover {
            color: #000;
            text-decoration: underline;
        }

        .p-precio {
            font-size: 20px;
            color: #1965a7;
        }

        .p-precio-old {
            font-size: 12px;
            color: red;
            text-decoration: line-through;
        }

        .portfolio-wrap {
            width: 250px;
            height: 225px;
            display: flex;
            justify-content: space-around;
        }

        .oferta {
            position: absolute;
            right: -8px;
            top: 8px;
            background-color: red;
            color: #fff;
            padding: 0 10px;
            z-index: 1;
            border: 1px solid #bd0000;
            border-radius: 15px;
        }

        .novedad {
            position: absolute;
            right: -8px;
            top: 8px;
            background-color: #099409;
            color: #fff;
            padding: 0 10px;
            z-index: 1;
            border: 1px solid green;
            border-radius: 15px;
        }

        .team {
            background-color: #f2fff0;
        }

        .botones {
            display: flex;
            flex-wrap: nowrap;
            flex-direction: row;
            justify-content: space-between;
        }

        .botones a:first-child {
            background-color: #2869a1;
            color: #ffffff;
            text-align: center;
            padding: .3rem;
            flex: 1 1 100%;
            border: none;
            transition: border-radius 0.6s linear;
        }

        .botones a:first-child:hover {
            background-color: #124e83;
        }

        .botones a:nth-child(2) {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #57cf57;
            color: #ffffff;
            border-top-left-radius: .5rem;
            border-bottom-left-radius: .5rem;
            flex: 1 1 0%;
            width: 0;
            transition: flex .5s;
        }

        .botones a:nth-child(2):hover {
            background-color: #1bd81b;
        }

        .botones:hover>a:nth-child(2) {
            flex: 1 1 20%;
            margin-left: .5rem;
        }

        .botones:hover>a:first-child {
            border-top-right-radius: .5rem;
            border-bottom-right-radius: .5rem;
        }

        /* Contenedor DE LAS CATEGORAS PERO DE LOS CONTENEDORES */
        .prod-filter-container {

            gap: 25px;
            justify-content: space-between !important;
        }

        /* Item de producto */
        .prod-filter-item {
            flex: 0 0 calc(33.333% - 25px);
            max-width: calc(33.333% - 25px);
            padding: 0 !important;
            transition: all 0.4s ease;
            opacity: 1;
            transform: scale(1);
        }

        /* Contenedor de tarjeta */
        .prod-card-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .prod-card-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(238, 124, 49, 0.2);
        }

        /* Contenedor de imagen */
        .prod-image-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
            margin: 0 auto !important;
            /* fallback ratio using padding-top so container keeps shape across browsers */
            padding-top: 92%; /* aumentar altura para mostrar aún más de la imagen */
            height: 0;
            min-height: 280px; /* evita que sea demasiado pequeño en pantallas grandes */
        }

        .prod-main-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* fill container, keep centered */
            object-position: center 45%; /* desplaza el foco hacia arriba ligeramente */
            display: block;
            transition: transform 0.5s ease;
        }

        .prod-card-container:hover .prod-main-image {
            transform: scale(1.05);
        }

        /* Overlay de imagen */
        .prod-image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3));
            color: white;
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        .prod-overlay-text {
            margin: 0;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Detalles del producto */
        .prod-details {
            padding: 15px;
            background: #f8f9fa;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .prod-title-container {
            height: auto;
            min-height: 25px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .prod-title {
            font-size: clamp(0.8rem, 1.2vw, 1rem);
            font-weight: 900;
            text-align: center;
            margin-top: 5px;
            margin-bottom: 12px;
            line-height: 1.2;
            text-transform: uppercase;
            color: #333;
        }
        .prod-prefix {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 0.85em;
        }

        /* Botón de acción */
        .prod-action-btn {
            margin-top: auto;
        }

        .prod-action-btn a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 15px;
            background: #ee7c31;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .prod-action-btn a:hover {
            background: #e67125;
            transform: translateY(-2px);
        }

        .prod-action-btn i {
            margin-right: 8px;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .prod-filter-item {
                flex: 0 0 calc(50% - 25px);
                max-width: calc(50% - 25px);
                margin-left: 5px;
            }

            .prod-image-wrapper { padding-top: 86%; min-height: 220px; }
        }

        @media (max-width: 768px) {
            .prod-filter-item {
                flex: 0 0 calc(50% - 15px);
                max-width: calc(50% - 15px);
            }

            .prod-image-wrapper { padding-top: 78%; min-height: 200px; }
        }

        @media (max-width: 576px) {
            .prod-filter-item {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .prod-image-wrapper { padding-top: 88%; min-height: 180px; }
        }

        /* FIN DE LA SECCION DE CATEGORIAS*/

        /*ESTILO DE OFERTAS*/

        /* Estilos para el carrusel de banners */
        .promo-banner-section {
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .promo-banner-container {
            position: relative;
            margin: 0 auto;
        }

        .promo-banner-track {
            display: flex;
            transition: transform 0.5s ease;

        }

        .promo-banner-slide {
            min-width: 100%;
            position: relative;
        }

        .promo-banner-content {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ee7c31 0%, #e67125 100%);
            color: white;
            font-size: 2rem;
            font-weight: bold;
        }

        .promo-banner-placeholder {
            padding: 20px 40px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        /* Controles de navegación */
        .promo-banner-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .promo-banner-nav:hover {
            background: white;
        }

        .promo-banner-prev {
            left: 20px;
        }

        .promo-banner-next {
            right: 20px;
        }

        .promo-banner-nav i {
            font-size: 24px;
            color: #ee7c31;
        }

        /* Indicadores */
        .promo-banner-dots {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            z-index: 10;
        }

        .promo-banner-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .promo-banner-dot.active {
            background: white;
            transform: scale(1.2);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .promo-banner-track {}
        }

        @media (max-width: 768px) {
            .promo-banner-track {}

            .promo-banner-content {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .promo-banner-track {}

            .promo-banner-nav {
                width: 30px;
                height: 30px;
            }

            .promo-banner-nav i {
                font-size: 18px;
            }
        }

        /* FIN DE ESTILO DE OFERTAS */

        /* Estilo de la sección de novedades */
        /* Estilos generales del carrusel */
        .novedades-section {
            background-color: #f8f9fa;
        }

        .novedades-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .novedades-title-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .novedades-title-section h2 {
            font-size: 2.5rem;
            color: #212121;
            margin-bottom: 15px;
        }

        .novedades-title-section p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Contenedor del carrusel */
        .novedades-carousel-wrapper {
            position: relative;
            overflow: hidden;
        }

        /* Pista del carrusel */
        .novedades-carousel-track {
            display: flex;
            transition: transform 0.5s ease;
            gap: 20px;
        }

        /* Items del carrusel */
        .novedades-carousel-item {
            flex: 0 0 calc(25% - 15px);
            min-width: calc(25% - 15px);
            padding: 0 5px;
        }

        /* Tarjeta de producto */
        .novedades-product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .novedades-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Badge "Nuevo" */
        .novedades-badge {
            background: #ee7c31;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            z-index: 2;
        }

        /* Imagen del producto */
        .novedades-image-container {
            position: relative;
            width: 100%;
            aspect-ratio: 4/3;
            overflow: hidden;
        }

        .novedades-product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .novedades-product-card:hover .novedades-product-image {
            transform: scale(1.05);
        }

        /* Overlay de imagen */
        .novedades-image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            color: white;
        }

        .novedades-image-overlay h6 {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Detalles del producto */
        .novedades-product-details {
            padding: 15px;
        }

        .novedades-product-title {
            margin: 0;
            font-size: 1rem;
            text-align: center;
        }

        .novedades-product-title a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .novedades-product-title a:hover {
            color: #ee7c31;
        }

        /* Controles de navegación */
        .novedades-carousel-prev,
        .novedades-carousel-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: white;
            border: none;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .novedades-carousel-prev {
            left: 0;
        }

        .novedades-carousel-next {
            right: 0;
        }

        .novedades-carousel-prev:hover,
        .novedades-carousel-next:hover {
            background: #ee7c31;
            color: white;
        }

        /* Indicadores */
        .novedades-carousel-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .novedades-carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .novedades-carousel-dot.active {
            background: #ee7c31;
            transform: scale(1.2);
        }

        /* Estilo para la descripción del slide (mejor estética) */
        .hero-slide-desc {
            display: inline-block;
            background: rgba(0, 0, 0, 0.35);
            padding: 14px 20px;
            border-radius: 10px;
            color: #f7f7f7;
            font-size: 1.02rem;
            line-height: 1.45;
            max-width: 880px;
            margin-top: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.25);
            backdrop-filter: blur(2px);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .novedades-carousel-item {
                flex: 0 0 calc(50% - 10px);
                min-width: calc(50% - 10px);
            }

            .novedades-image-container {
                height: 180px;
            }
        }

        @media (max-width: 768px) {
            .novedades-carousel-wrapper {
                padding: 0 30px;
            }

            .novedades-carousel-item {
                flex: 0 0 calc(50% - 10px);
                min-width: calc(50% - 10px);
            }
        }

        @media (max-width: 576px) {
            .novedades-carousel-item {
                flex: 0 0 100%;
                min-width: 100%;
            }

            .novedades-carousel-wrapper {
                padding: 0 20px;
            }

            .novedades-image-container {
                height: 220px;
            }

            .novedades-carousel-prev,
            .novedades-carousel-next {
                width: 30px;
                height: 30px;
            }
        }

        /* FIN DE ESTILO DE NOVEDADES */

        /* =====================================================
           HERO LENOVO PE — Carrusel 100% ancho, 500px desktop
           ===================================================== */
        :root {
            --hero-hover-bg-color: #000000;
            --hero-hover-text-color: #ffffff;
            --hero-btn-bg: #ffffff;
            --hero-btn-text: #000000;
            --hero-btn-border: #171717;
            --hero-height: 500px;
            --hero-top-offset: 96px;
            --hero-max-width: 1600px;
        }

        /* Sobrescribir el #hero viejo del style.css externo */
        #hero {
            width: 100% !important;
            height: var(--hero-height) !important;
            min-height: 400px !important;
            padding: 0 !important;
            margin: var(--hero-top-offset) 0 0 0 !important;
            overflow: hidden !important;
            position: relative !important;
        }

        /* Contenedor general del hero */
        .lenovo-hero {
            width: 100%;
            margin: 0;
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        .lenovo-hero__viewport {
            position: relative;
            width: 100%;
            max-width: none;
            margin: 0;
            height: 100%;
            min-height: var(--hero-height);
            overflow: hidden;
        }

        /* Track que contiene los slides */
        .lenovo-hero__track {
            display: flex;
            width: 100%;
            height: 100%;
            min-height: var(--hero-height);
            transition: transform 0.6s ease-in-out;
        }

        /* Cada slide */
        .lenovo-hero__slide {
            position: relative;
            flex: 0 0 100%;
            width: 100%;
            height: 100%;
            min-height: var(--hero-height);
            overflow: hidden;
        }

        /* Capa 1 — Imagen de fondo */
        .lenovo-hero__bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        /* Capa 2 — Máscara de enlace invisible */
        .lenovo-hero__mask-link {
            position: absolute;
            inset: 0;
            z-index: 1;
            text-decoration: none;
        }

        /* Capa 3 — Bloque de contenido (logo, subtítulo, titular, botones) */
        .lenovo-hero__content {
            position: absolute;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: min(var(--hero-max-width), calc(100% - 30px));
            height: 100%;
            left: 50%;
            top: 0;
            right: auto;
            bottom: auto;
            transform: translateX(-50%);
            padding: 0 0 0 clamp(28px, 4vw, 64px);
            box-sizing: border-box;
            color: #fff;
        }

        .lenovo-hero__logo {
            max-height: 40px;
            width: auto;
            margin-bottom: 0;
            object-fit: contain;
        }

        .lenovo-hero__subhead {
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 8px 0 0 0;
            color: #fff;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }

        .lenovo-hero__headline {
            font-size: 30px;
            font-weight: 700;
            line-height: 1.15;
            margin: 6px 0 0 0;
            color: #fff;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.6);
            max-width: 90%;
        }

        .lenovo-hero__buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 16px;
        }

        .lenovo-hero__btn {
            display: inline-block;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 4px;
            background-color: var(--hero-btn-bg);
            color: var(--hero-btn-text);
            border: 1px solid var(--hero-btn-border);
            text-decoration: none;
            transition: background-color 0.25s ease, color 0.25s ease;
            cursor: pointer;
        }

        .lenovo-hero__btn:hover {
            background-color: var(--hero-hover-bg-color);
            color: var(--hero-hover-text-color);
        }

        /* Capa 4 — Controles del carrusel */
        .lenovo-hero__arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            background: rgba(0,0,0,0.35);
            border: none;
            color: #fff;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.25s ease;
        }

        .lenovo-hero__arrow:hover {
            background: rgba(0,0,0,0.65);
        }

        .lenovo-hero__arrow--prev { left: 16px; }
        .lenovo-hero__arrow--next { right: 16px; }

        .lenovo-hero__arrow i {
            font-size: 22px;
            line-height: 1;
        }

        .lenovo-hero__dots-wrap {
            position: absolute;
            bottom: 16px;
            left: 50%;
            right: auto;
            transform: translateX(-50%);
            width: min(var(--hero-max-width), calc(100% - 30px));
            padding-left: clamp(28px, 4vw, 64px);
            z-index: 3;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .lenovo-hero__dots {
            display: flex;
            gap: 6px;
        }

        .lenovo-hero__dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            border: none;
            cursor: pointer;
            padding: 0;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .lenovo-hero__dot.active {
            background: #fff;
            transform: scale(1.2);
        }

        .lenovo-hero__playpause {
            background: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            padding: 4px 6px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .lenovo-hero__playpause:hover {
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 991px) {
            :root {
                --hero-height: 380px;
                --hero-top-offset: 82px;
            }
            .lenovo-hero__content { width: calc(100% - 24px); padding-left: 24px; }
            .lenovo-hero__headline { font-size: 26px; }
            .lenovo-hero__dots-wrap { width: calc(100% - 24px); padding-left: 24px; }
        }

        @media (max-width: 576px) {
            :root {
                --hero-height: 320px;
                --hero-top-offset: 74px;
            }
            .lenovo-hero__content { width: calc(100% - 20px); padding-left: 16px; }
            .lenovo-hero__headline { font-size: 20px; }
            .lenovo-hero__subhead { font-size: 12px; }
            .lenovo-hero__btn { padding: 10px 16px; font-size: 14px; }
            .lenovo-hero__arrow { width: 36px; height: 36px; }
            .lenovo-hero__arrow i { font-size: 18px; }
            .lenovo-hero__dots-wrap { width: calc(100% - 20px); padding-left: 16px; bottom: 10px; }
        }
    </style>
@endsection
@section('content')
    <!-- ======= Hero Section (Lenovo PE) ======= -->
    <section id="hero" class="lenovo-hero">
        <div class="lenovo-hero__viewport" id="lenovoHeroViewport">
            <div class="lenovo-hero__track" id="lenovoHeroTrack">
                @foreach ($banners as $banner)
                    @php
                        $totalSlides = $banners->count();
                        $bgImg = $banner->imagen
                            ? asset('storage/' . $banner->imagen)
                            : asset('landing/img/slide/slide-' . ($loop->index + 1) . '.jpg');

                        // Reemplazo temporal HENKO -> PROWORK
                        $tituloRaw = preg_replace('/\\bHENKO\\b/iu', 'PROWORK', (string)($banner->titulo ?? ''));
                        $bt = strtoupper($tituloRaw);
                        if (str_contains($bt, 'EZENT'))        $heroFont = "'EzentFont', sans-serif";
                        elseif (str_contains($bt, 'GENWORK'))  $heroFont = "'GenworkFont', sans-serif";
                        elseif (str_contains($bt, 'OFISZU'))   $heroFont = "'OfiszuFont', sans-serif";
                        elseif (str_contains($bt, 'HENKO'))    $heroFont = "'ProworkFont', sans-serif";
                        elseif (str_contains($bt, 'PROWORK'))  $heroFont = "'ProworkFont', sans-serif";
                        elseif (str_contains($bt, 'RAITO'))    $heroFont = "'RaitoFont', sans-serif";
                        else                                   $heroFont = "'Kenyav1', sans-serif";

                        $tituloColor = $banner->titulo_color ?? '#ffffff';
                        if (preg_match('/^(KENYA\s+)(.+)$/iu', $tituloRaw, $m)) {
                            $tituloHtml = '<span style="font-family:\'Kenyav1\',sans-serif;color:' . e($tituloColor) . '">' . e($m[1]) . '</span>'
                                        . '<span style="font-family:' . $heroFont . ';color:' . e($tituloColor) . '">' . e($m[2]) . '</span>';
                        } else {
                            $tituloHtml = '<span style="color:' . e($tituloColor) . ';font-family:' . $heroFont . '">' . e($tituloRaw) . '</span>';
                        }

                        $bannerDesc = trim((string)($banner->descripcion ?? ''));
                        $showSubhead = $bannerDesc !== '' && strtolower($bannerDesc) !== 'especialistas en soporte técnico';
                    @endphp

                    <div class="lenovo-hero__slide"
                         role="group"
                         aria-roledescription="slide"
                         aria-label="slide {{ $loop->index + 1 }} of {{ $totalSlides }}"
                         @if($loop->index == 0) aria-hidden="false" @else aria-hidden="true" @endif>

                        {{-- Capa 1: imagen de fondo --}}
                        <img class="lenovo-hero__bg" src="{{ $bgImg }}" alt="">

                        {{-- Capa 2: máscara de enlace invisible --}}
                        @if($banner->link)
                            <a class="lenovo-hero__mask-link" href="{{ $banner->link }}" aria-label="Ir a {{ $banner->link_nombre ?? 'banner' }}"></a>
                        @else
                            <span class="lenovo-hero__mask-link" aria-hidden="true"></span>
                        @endif

                        {{-- Capa 3: bloque de contenido --}}
                        <div class="lenovo-hero__content">
                            @if($showSubhead)
                                <p class="lenovo-hero__subhead">{{ $banner->descripcion }}</p>
                            @endif

                            <h2 class="lenovo-hero__headline" role="heading" aria-level="2">{!! $tituloHtml !!}</h2>

                            @if($banner->link && $banner->link_nombre)
                                <div class="lenovo-hero__buttons">
                                    <a class="lenovo-hero__btn" href="{{ $banner->link }}">
                                        {{ $banner->link_nombre }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Capa 4: controles del carrusel --}}
            <button type="button" class="lenovo-hero__arrow lenovo-hero__arrow--prev" id="lenovoHeroPrev" aria-label="Previous slide">
                <i class="bx bx-chevron-left" aria-hidden="true"></i>
            </button>
            <button type="button" class="lenovo-hero__arrow lenovo-hero__arrow--next" id="lenovoHeroNext" aria-label="Next slide">
                <i class="bx bx-chevron-right" aria-hidden="true"></i>
            </button>

            <div class="lenovo-hero__dots-wrap">
                <div class="lenovo-hero__dots" id="lenovoHeroDots">
                    @foreach ($banners as $banner)
                        <button type="button"
                                class="lenovo-hero__dot @if($loop->index == 0) active @endif"
                                data-index="{{ $loop->index }}"
                                aria-label="Go to slide {{ $loop->index + 1 }}"></button>
                    @endforeach
                </div>
                <button type="button" class="lenovo-hero__playpause" id="lenovoHeroPlayPause" aria-label="Pause autoplay">
                    <i class="bx bx-pause" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </section><!-- End Hero -->

    <main id="main">
        <!-- ======= About Us Section ======= -->
        <section id="productos" class="portfolio section-bg">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="site-width">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2>CATEGORIAS</h2>
                        </div>
                        <ul id="portfolio-flters">
                            <li data-filter="*" class="filter-active">
                                <div class="card" style="width: 8rem;">
                                    <img class="card-img-top" src="{{ asset('pord.png') }}" alt="Card image cap">
                                    <div class="card-body">
                                        <p class="card-text" style="color:black">Todos</p>
                                    </div>
                                </div>
                            </li>
                            @foreach ($categorias as $cat)
                                <li data-filter=".filter-{{ $cat->id }}">
                                    <div class="card" style="width: 8rem;">
                                        @if ($cat->img_url)
                                            <img class="card-img-top" src="{{ $cat->img_url }}"
                                                alt="Card image cap">
                                        @else
                                            <img class="card-img-top" src="{{ asset('producto.jpg') }}"
                                                alt="Card image cap">
                                        @endif
                                        <div class="card-body">
                                            <p class="card-text" style="color:black"> {{ $cat->nombre }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row prod-filter-container" style="justify-content: center">
                    @foreach ($modelo as $mod)
                        @php
                            $modNombre = strtoupper($mod->descripcion ?? $mod->nombre ?? '');
                            $brandClass = '';
                            if (str_contains($modNombre, 'EZENT'))        $brandClass = 'brand-ezent';
                            elseif (str_contains($modNombre, 'GENWORK'))  $brandClass = 'brand-genwork';
                            elseif (str_contains($modNombre, 'OFISZU'))   $brandClass = 'brand-ofiszu';
                            elseif (str_contains($modNombre, 'HENKO'))    $brandClass = 'brand-henko';
                            elseif (str_contains($modNombre, 'PROWORK'))  $brandClass = 'brand-prowork';
                            elseif (str_contains($modNombre, 'RAITO'))    $brandClass = 'brand-raito';
                        @endphp
                        <div class="col-lg-3 col-md-4 prod-filter-item filter-{{ $mod->categoria_id }} {{ $brandClass }}">
                            <div class="prod-card-container">
                                <div class="prod-image-wrapper" style="margin: 0 auto;">
                                    @if ($mod->img_mod)
                                        <img src="{{ asset('storage/' . $mod->img_mod) }}"
                                            class="img-fluid prod-main-image" alt="">
                                    @else
                                        <img src="{{ asset('producto.jpg') }}" class="img-fluid prod-main-image"
                                            alt="">
                                    @endif
                                    <div class="prod-image-overlay">
                                        @if ($mod->categoria_id)
                                            <h6 class="prod-overlay-text" title="{{ ($mod->prefix ? $mod->prefix . ' ' : '') . ($mod->descripcion ?? '') }}">
                                                @if($mod->prefix)<span class="prod-prefix">{{ $mod->prefix }} </span>@endif{{ Str::limit($mod->descripcion ?? '', 160) }}
                                            </h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="prod-details">
                                    <div class="prod-title-container">
                                        <p class="prod-title" title="{{ ($mod->prefix ? $mod->prefix . ' ' : '') . ($mod->descripcion ?? '') }}">
                                            @if($mod->prefix)<span class="prod-prefix">{{ $mod->prefix }} </span>@endif{{ Str::limit($mod->descripcion ?? '', 90) }}
                                        </p>
                                    </div>
                                    <div class="prod-action-btn">
                                        <a href="{{ route('detallemod', $mod->id) }}"><i class='bx bx-shopping-bag'></i>
                                            Ver Catálogo</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- ======= Services Section ======= -->
        <section id="ofertas" class="services portfolio">
            <div class="container">
                <div class="section-title">
                    <h2>Ofertas</h2>
                    <p>Productos con super promociones y descuentos.</p>
                </div>
            </div>
        </section>
        <!-- ======= Carrusel de Banners ======= -->
        <section class="promo-banner-section">
            <div class="promo-banner-container">
                <div class="promo-banner-track">
                    @foreach (\App\Models\BannerMedio::where('activo', true)->orderBy('orden')->get() as $banner)
                        <div class="promo-banner-slide">
                            <div class="promo-banner-content">
                                <a href="{{ $banner->url_destino }}" target="_blank">
                                    <img src="{{ asset($banner->imagen_path) }}"
                                        alt="{{ $banner->titulo ?? 'Banner promocional' }}" class="img-fluid">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Controles de navegación -->
                <button class="promo-banner-nav promo-banner-prev">
                    <i class='bx bx-chevron-left'></i>
                </button>
                <button class="promo-banner-nav promo-banner-next">
                    <i class='bx bx-chevron-right'></i>
                </button>

                <!-- Indicadores -->
                <div class="promo-banner-dots">
                    @foreach (\App\Models\BannerMedio::where('activo', true)->orderBy('orden')->get() as $index => $banner)
                        <button class="promo-banner-dot {{ $index === 0 ? 'active' : '' }}"
                            data-slide="{{ $index }}"></button>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ======= Novedades Section ======= -->
        @include('components.novedades', ['novedades' => $novedades])
    </main><!-- End #main -->
    <script src="{{ asset('js/detallemod.js') }}"></script>
@endsection
@section('js')
    <!-- SCRIPT DE LAS CATEGORIAS -->
    <script>
        new Vue({
            el: '#portfolio',
            data: {

            },
            methods: {

            },
        });
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('#portfolio-flters li');
            const portfolioItems = document.querySelectorAll('.prod-filter-item');

            // Función de filtrado corregida
            function filterPortfolio() {
                const filterValue = this.getAttribute('data-filter');

                // Actualizar clase activa (igual que antes)

                // Filtrar elementos mejorado
                portfolioItems.forEach(item => {
                    const shouldShow = filterValue === '*' ||
                        item.classList.contains(filterValue.substring(1)) ||
                        item.classList.value.includes(filterValue.replace('.filter-', 'filter-'));

                    item.style.display = shouldShow ? 'block' : 'none';
                    if (shouldShow) {
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 50);
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'scale(0.9)';
                    }
                });
            }

            // Añadir eventos
            filterButtons.forEach(button => {
                button.addEventListener('click', filterPortfolio);
            });

            // Mostrar todos los items inicialmente
            portfolioItems.forEach(item => {
                item.style.display = 'block';
            });

            // Activar el filtro "Todos" por defecto
            const defaultFilter = document.querySelector('#portfolio-flters li.filter-active');
            if (defaultFilter && defaultFilter.querySelector('.card')) {
                defaultFilter.querySelector('.card').style.border = '2px solid #ee7c31';
            }
        });
    </script>
    <!-- FIN DEL SCRIPT DE LAS CATEGORIAS -->


    <!-- FIN DEL SCRIPT DEL CARRUSEL DE BANNERS -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.querySelector('.promo-banner-track');
            const items = document.querySelectorAll('.promo-banner-slide'); // Cambiado a promo-banner-slide
            const prevBtn = document.querySelector('.promo-banner-prev'); // Cambiado a promo-banner-prev
            const nextBtn = document.querySelector('.promo-banner-next'); // Cambiado a promo-banner-next
            const dotsContainer = document.querySelector('.promo-banner-dots'); // Cambiado a promo-banner-dots

            let currentIndex = 0;
            let visibleItems = 1; // Mostrar solo 1 banner a la vez
            let totalSlides = items.length;

            // Calcular items visibles según el ancho de pantalla
            function updateVisibleItems() {
                // Mantenemos siempre 1 banner visible (carrusel clásico)
                visibleItems = 1;
                updateTrackPosition();
                createDots();
            }

            // Crear indicadores - MODIFICADO PARA MOSTRAR 1 DOT POR BANNER
            function createDots() {
                dotsContainer.innerHTML = '';
                const dotCount = totalSlides; // Un dot por cada banner

                for (let i = 0; i < dotCount; i++) {
                    const dot = document.createElement('button'); // Cambiado a button para mejor accesibilidad
                    dot.classList.add('promo-banner-dot');
                    if (i === currentIndex) dot.classList.add('active');
                    dot.addEventListener('click', () => goToSlide(i));
                    dotsContainer.appendChild(dot);
                }
            }

            // Actualizar posición del track
            function updateTrackPosition() {
                const itemWidth = items[0].offsetWidth;
                const gap = 0; // Sin gap entre banners
                const newPosition = -(currentIndex * (itemWidth + gap));

                track.style.transform = `translateX(${newPosition}px)`;

                // Actualizar dots activos - MODIFICADO PARA SELECCIONAR SOLO EL DOT ACTUAL
                document.querySelectorAll('.promo-banner-dot').forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentIndex);
                });
            }

            // Navegación
            function nextSlide() {
                if (currentIndex < totalSlides - visibleItems) {
                    currentIndex++;
                } else {
                    currentIndex = 0; // Volver al inicio
                }
                updateTrackPosition();
            }

            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                } else {
                    currentIndex = totalSlides - visibleItems; // Ir al final
                }
                updateTrackPosition();
            }

            // Ir a slide específico - MODIFICADO PARA IR DIRECTAMENTE AL BANNER
            function goToSlide(index) {
                currentIndex = index;
                updateTrackPosition();
            }

            // Event listeners
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);

            // Auto-desplazamiento
            let slideInterval;

            function startAutoSlide() {
                slideInterval = setInterval(() => {
                    nextSlide();
                }, 5000);
            }

            function stopAutoSlide() {
                clearInterval(slideInterval);
            }

            // Inicializar
            function initCarousel() {
                updateVisibleItems();
                startAutoSlide();

                // Pausar al interactuar
                track.addEventListener('mouseenter', stopAutoSlide);
                track.addEventListener('mouseleave', startAutoSlide);

                // Touch events para móviles
                let touchStartX = 0;
                let touchEndX = 0;

                track.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                    stopAutoSlide();
                }, {
                    passive: true
                });

                track.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                    startAutoSlide();
                }, {
                    passive: true
                });

                function handleSwipe() {
                    const diff = touchStartX - touchEndX;
                    if (diff > 50) nextSlide();
                    if (diff < -50) prevSlide();
                }
            }

            // Redimensionamiento
            window.addEventListener('resize', () => {
                updateVisibleItems();
            });

            // Iniciar carrusel
            initCarousel();
        });
    </script>
    <script>
        // Expand/collapse slide description
        (function(){
            function setCollapsed(el) {
                el.classList.remove('expanded');
                el.innerHTML = el.getAttribute('data-trunc') + ' <a href="#" class="slide-toggle" style="color:#fff; font-weight:600; margin-left:6px;">ver más</a>';
            }

            function setExpanded(el) {
                el.classList.add('expanded');
                el.innerHTML = el.getAttribute('data-full') + ' <a href="#" class="slide-toggle" style="color:#fff; font-weight:600; margin-left:6px;">ver menos</a>';
            }

            document.addEventListener('click', function(e){
                const toggle = e.target.closest('.slide-toggle');
                if (!toggle) return;
                e.preventDefault();
                const parent = toggle.closest('.hero-slide-desc');
                if (!parent) return;
                if (parent.classList.contains('expanded')) {
                    setCollapsed(parent);
                } else {
                    setExpanded(parent);
                }
            });

            // Initialize all hero-slide-desc elements as collapsed
            document.querySelectorAll('.hero-slide-desc').forEach(el => setCollapsed(el));
        })();
    </script>
    <!-- FIN DEL SCRIPT DEL CARRUSEL DE NOVEDADES -->

    <!-- SCRIPT HERO LENOVO PE -->
    <script>
        (function(){
            const track   = document.getElementById('lenovoHeroTrack');
            const slides  = track ? track.querySelectorAll('.lenovo-hero__slide') : [];
            const dots    = document.querySelectorAll('.lenovo-hero__dot');
            const prevBtn = document.getElementById('lenovoHeroPrev');
            const nextBtn = document.getElementById('lenovoHeroNext');
            const ppBtn   = document.getElementById('lenovoHeroPlayPause');
            const viewport= document.getElementById('lenovoHeroViewport');

            if (!track || slides.length === 0) return;

            const total = slides.length;
            let current = 0;
            let isPlaying = true;
            let autoTimer = null;
            const AUTO_MS = 5000;

            function goTo(idx) {
                if (idx < 0) idx = total - 1;
                if (idx >= total) idx = 0;
                current = idx;
                track.style.transform = 'translateX(-' + (current * 100) + '%)';
                dots.forEach((d, i) => d.classList.toggle('active', i === current));
                slides.forEach((s, i) => s.setAttribute('aria-hidden', i === current ? 'false' : 'true'));
                if (prevBtn) prevBtn.setAttribute('aria-label', 'currently displaying item ' + (current + 1) + ' of ' + total);
                if (nextBtn) nextBtn.setAttribute('aria-label', 'currently displaying item ' + (current + 1) + ' of ' + total);
            }

            function next() { goTo(current + 1); }
            function prev() { goTo(current - 1); }

            function startAuto() {
                stopAuto();
                autoTimer = setInterval(next, AUTO_MS);
                isPlaying = true;
                if (ppBtn) {
                    ppBtn.innerHTML = '<i class="bx bx-pause" aria-hidden="true"></i>';
                    ppBtn.setAttribute('aria-label', 'Pause autoplay');
                }
            }

            function stopAuto() {
                if (autoTimer) clearInterval(autoTimer);
                autoTimer = null;
                isPlaying = false;
                if (ppBtn) {
                    ppBtn.innerHTML = '<i class="bx bx-play" aria-hidden="true"></i>';
                    ppBtn.setAttribute('aria-label', 'Play autoplay');
                }
            }

            if (prevBtn) prevBtn.addEventListener('click', () => { prev(); startAuto(); });
            if (nextBtn) nextBtn.addEventListener('click', () => { next(); startAuto(); });

            dots.forEach((d) => {
                d.addEventListener('click', () => {
                    const i = parseInt(d.getAttribute('data-index'), 10) || 0;
                    goTo(i);
                    startAuto();
                });
            });

            if (ppBtn) {
                ppBtn.addEventListener('click', () => {
                    if (isPlaying) stopAuto(); else startAuto();
                });
            }

            if (viewport) {
                viewport.addEventListener('mouseenter', stopAuto);
                viewport.addEventListener('mouseleave', () => { if (!isPlaying) return; startAuto(); });
            }

            // Swipe básico
            let tx = 0;
            if (viewport) {
                viewport.addEventListener('touchstart', (e) => { tx = e.changedTouches[0].screenX; }, { passive: true });
                viewport.addEventListener('touchend', (e) => {
                    const diff = tx - e.changedTouches[0].screenX;
                    if (Math.abs(diff) > 50) { diff > 0 ? next() : prev(); startAuto(); }
                });
            }

            goTo(0);
            startAuto();
        })();
    </script>
@endsection
<script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
