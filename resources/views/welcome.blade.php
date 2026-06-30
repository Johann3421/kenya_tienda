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
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('css')
    <style>
        /* ═══════════════════════════════════════════════════════════════
           CONTENEDOR PRINCIPAL
           ═══════════════════════════════════════════════════════════════ */
        #main-welcome-container {
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
        }

        #main-welcome-container *,
        #main-welcome-container *::before,
        #main-welcome-container *::after {
            box-sizing: border-box !important;
        }

        /* ═══════════════════════════════════════════════════════════════
           FUENTES PERSONALIZADAS KENYA
           ═══════════════════════════════════════════════════════════════ */
        @font-face {
            font-family: 'EzentFont';
            src: url('/TIPOGRAFIA%20KENYA/EZENT/Ezent-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/EZENT/Ezent-Regular.otf') format('opentype');
            font-display: swap;
        }
        @font-face {
            font-family: 'GenworkFont';
            src: url('/TIPOGRAFIA%20KENYA/GENWORK/Genwork-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/GENWORK/Genwork-Regular.otf') format('opentype');
            font-display: swap;
        }
        @font-face {
            font-family: 'OfiszuFont';
            src: url('/TIPOGRAFIA%20KENYA/OFISZU%20Y%20HENKO/OfiszuYHenko-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/OFISZU%20Y%20HENKO/OfiszuYHenko-Regular.otf') format('opentype');
            font-display: swap;
        }
        @font-face {
            font-family: 'ProworkFont';
            src: url('/TIPOGRAFIA%20KENYA/PROWORK/Prowork-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/PROWORK/Prowork-Regular.otf') format('opentype');
            font-display: swap;
        }
        @font-face {
            font-family: 'RaitoFont';
            src: url('/TIPOGRAFIA%20KENYA/RAITO/Raito-Regular.ttf') format('truetype'),
                 url('/TIPOGRAFIA%20KENYA/RAITO/Raito-Regular.otf') format('opentype');
            font-display: swap;
        }

        /* ═══════════════════════════════════════════════════════════════
           HERO SLIDER - COMPLETAMENTE AISLADO
           ═══════════════════════════════════════════════════════════════ */
        #main-welcome-container .hero-slider-section {
            position: relative !important;
            width: 100% !important;
            height: 60vh !important;
            overflow: hidden !important;
            background-color: #111 !important;
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
        }

        #main-welcome-container .hero-slide {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            opacity: 0 !important;
            transition: opacity 0.8s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
            z-index: 1 !important;
        }

        #main-welcome-container .hero-slide.active {
            opacity: 1 !important;
            z-index: 2 !important;
        }

        #main-welcome-container .hero-slide-content {
            position: relative !important;
            z-index: 3 !important;
            width: 100% !important;
            max-width: 1400px !important;
            margin: 0 auto !important;
            padding: 0 15px !important;
            color: #fff !important;
        }

        #main-welcome-container .hero-slide h1 {
            font-size: 3.5rem !important;
            font-weight: 700 !important;
            margin: 0 0 20px 0 !important;
            color: #ffffff !important;
            transform: translateY(30px) !important;
            opacity: 0 !important;
            transition: all 0.8s ease 0.2s !important;
            line-height: 1.1 !important;
        }

        #main-welcome-container .hero-slide.active h1 {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }

        #main-welcome-container .hero-slide h2 {
            font-size: 2.5rem !important;
            font-weight: 700 !important;
            margin: 0 0 15px 0 !important;
            color: #ffffff !important;
            transform: translateY(30px) !important;
            opacity: 0 !important;
            transition: all 0.8s ease 0.3s !important;
            line-height: 1.1 !important;
        }

        #main-welcome-container .hero-slide.active h2 {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }

        #main-welcome-container .hero-slide p {
            font-size: 1.3rem !important;
            font-weight: 500 !important;
            margin: 0 0 35px 0 !important;
            max-width: 550px !important;
            color: #fff !important;
            line-height: 1.4 !important;
            transform: translateY(30px) !important;
            opacity: 0 !important;
            transition: all 0.8s ease 0.4s !important;
        }

        #main-welcome-container .hero-slide.active p {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }

        #main-welcome-container .hero-btn {
            display: inline-block !important;
            padding: 12px 30px !important;
            background: linear-gradient(to right, #f26522, #ff9c00) !important;
            color: #fff !important;
            text-decoration: none !important;
            font-weight: bold !important;
            font-size: 0.95rem !important;
            border-radius: 100px !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            transform: translateY(30px) !important;
            opacity: 0 !important;
            transition: all 0.8s ease 0.5s !important;
        }

        #main-welcome-container .hero-slide.active .hero-btn {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }

        #main-welcome-container .hero-btn:hover {
            background: linear-gradient(to right, #d96b20, #e67125) !important;
            transform: translateY(-2px) !important;
        }

        /* Controles del Hero */
        #main-welcome-container .hero-controls {
            position: absolute !important;
            top: 50% !important;
            width: 100% !important;
            transform: translateY(-50%) !important;
            display: flex !important;
            justify-content: space-between !important;
            padding: 0 3% !important;
            z-index: 10 !important;
            pointer-events: none !important;
        }

        #main-welcome-container .hero-controls button {
            pointer-events: auto !important;
            background-color: rgba(0,0,0,0.4) !important;
            color: white !important;
            border: 1px solid rgba(255,255,255,0.2) !important;
            width: 50px !important;
            height: 50px !important;
            border-radius: 50% !important;
            font-size: 1.2rem !important;
            cursor: pointer !important;
            transition: all 0.3s !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
        }

        #main-welcome-container .hero-controls button:hover {
            background-color: #f26522 !important;
            border-color: #f26522 !important;
            transform: scale(1.1) !important;
        }

        #main-welcome-container .hero-bottom-controls {
            position: absolute !important;
            bottom: 30px !important;
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
            z-index: 10 !important;
        }

        #main-welcome-container .hero-pill {
            background-color: rgba(0, 0, 0, 0.3) !important;
            border-radius: 50px !important;
            padding: 10px 20px !important;
            display: flex !important;
            align-items: center !important;
            gap: 15px !important;
            backdrop-filter: blur(5px) !important;
        }

        #main-welcome-container .hero-play-btn {
            background: transparent !important;
            border: none !important;
            color: white !important;
            font-size: 1rem !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            transition: color 0.3s !important;
        }

        #main-welcome-container .hero-play-btn:hover {
            color: #f26522 !important;
        }

        #main-welcome-container .hero-dots {
            display: flex !important;
            gap: 12px !important;
            align-items: center !important;
        }

        #main-welcome-container .hero-dot {
            width: 12px !important;
            height: 12px !important;
            background-color: transparent !important;
            border: 1.5px solid white !important;
            border-radius: 50% !important;
            cursor: pointer !important;
            transition: all 0.3s !important;
            padding: 0 !important;
        }

        #main-welcome-container .hero-dot.active {
            background-color: white !important;
            border-color: white !important;
            transform: scale(1.1) !important;
        }

        #main-welcome-container .hero-dot:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.3) !important;
        }

        /* ═══════════════════════════════════════════════════════════════
           SECCIÓN DE PRODUCTOS Y CATEGORÍAS
           ═══════════════════════════════════════════════════════════════ */
        #main-welcome-container .productos-section {
            width: 100% !important;
            padding: 60px 0 !important;
            background-color: #fff !important;
            margin: 0 !important;
        }

        #main-welcome-container .section-container {
            max-width: 1400px !important;
            margin: 0 auto !important;
            width: 100% !important;
            padding: 0 15px !important;
        }

        #main-welcome-container .section-title {
            text-align: center !important;
            margin: 0 0 40px 0 !important;
            padding: 0 !important;
        }

        #main-welcome-container .section-title h2 {
            font-size: 2.2rem !important;
            color: #111 !important;
            font-weight: 700 !important;
            margin: 0 !important;
        }

        /* GRID DE CATEGORÍAS - ESTILO CIRCULAR */
        #main-welcome-container .categorias-section-inner {
            background-color: #ffffff !important;
            padding: 2rem 0 !important;
        }

        #main-welcome-container .categoria-grid {
            display: flex !important;
            align-items: flex-start !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            gap: 40px !important;
            padding: 10px 5px !important;
            width: 100% !important;
            list-style: none !important;
            margin-bottom: 40px !important;
        }

        #main-welcome-container .categoria-btn {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            text-decoration: none !important;
            color: #111 !important;
            width: 200px !important;
            flex-shrink: 0 !important;
            text-align: center !important;
            gap: 15px !important;
            transition: transform 0.3s ease !important;
            cursor: pointer !important;
            border: none !important;
            background: none !important;
            padding: 0 !important;
        }

        #main-welcome-container .categoria-btn:hover {
            transform: translateY(-5px) !important;
        }

        #main-welcome-container .categoria-card {
            border-radius: 50% !important;
            background-color: #f4f4f4 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
            overflow: hidden !important;
            border: 1px solid transparent !important;
            aspect-ratio: unset !important;
            flex-direction: unset !important;
        }

        #main-welcome-container .categoria-card img {
            max-width: 70% !important;
            max-height: 70% !important;
            display: block !important;
            object-fit: contain !important;
            width: auto !important;
            height: auto !important;
            padding: 0 !important;
            background: transparent !important;
        }

        #main-welcome-container .categoria-btn:hover .categoria-card {
            box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
            background-color: #e8e8e8 !important;
        }

        #main-welcome-container .categoria-btn.active .categoria-card {
            border-color: #f26522 !important;
            background-color: #fffaf7 !important;
            box-shadow: 0 8px 20px rgba(242, 101, 34, 0.2) !important;
        }

        #main-welcome-container .categoria-label {
            font-size: 1rem !important;
            line-height: 1.3 !important;
            font-weight: 500 !important;
            transition: color 0.3s !important;
            display: block !important;
            background: none !important;
            color: #111 !important;
            text-transform: uppercase !important;
            padding: 0 !important;
            height: auto !important;
            width: auto !important;
            text-align: center !important;
        }

        #main-welcome-container .categoria-btn.active .categoria-label {
            color: #f26522 !important;
            font-weight: bold !important;
        }

        /* CARRUSEL DE PRODUCTOS */
        #main-welcome-container .productos-carousel {
            position: relative !important;
            margin-bottom: 40px !important;
        }

        #main-welcome-container .carousel-track {
            display: flex !important;
            gap: 20px !important;
            overflow-x: auto !important;
            scroll-behavior: smooth !important;
            padding: 20px 0 !important;
            scroll-padding: 0 !important;
        }

        #main-welcome-container .carousel-track::-webkit-scrollbar {
            height: 8px !important;
        }

        #main-welcome-container .carousel-track::-webkit-scrollbar-track {
            background: #f1f1f1 !important;
            border-radius: 10px !important;
        }

        #main-welcome-container .carousel-track::-webkit-scrollbar-thumb {
            background: #f26522 !important;
            border-radius: 10px !important;
        }

        #main-welcome-container .producto-card {
            flex: 0 0 calc(25% - 15px) !important;
            min-width: 280px !important;
            background: white !important;
            border-radius: 10px !important;
            overflow: hidden !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.09) !important;
            transition: all 0.3s ease !important;
            display: flex !important;
            flex-direction: column !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #main-welcome-container .producto-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 6px 18px rgba(242,101,34,0.22) !important;
        }

        #main-welcome-container .producto-imagen {
            position: relative !important;
            width: 100% !important;
            aspect-ratio: 1 !important;
            overflow: hidden !important;
            background: #f9f9f9 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        #main-welcome-container .producto-imagen img {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
            object-position: center !important;
            padding: 15px !important;
        }

        #main-welcome-container .producto-overlay {
            position: absolute !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            padding: 15px !important;
            background: linear-gradient(180deg, transparent, rgba(0,0,0,0.8)) !important;
            color: white !important;
        }

        #main-welcome-container .producto-overlay h6 {
            margin: 0 !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
        }

        #main-welcome-container .producto-accion {
            padding: 15px !important;
            flex-grow: 1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        #main-welcome-container .producto-accion a {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            padding: 10px 18px !important;
            background: #f26522 !important;
            color: #fff !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            font-size: 0.78rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
            box-shadow: 0 4px 12px rgba(242,101,34,0.25) !important;
            transition: all 0.25s ease !important;
            border: none !important;
            cursor: pointer !important;
            margin: 0 !important;
        }

        #main-welcome-container .producto-accion a:hover {
            background: #d96b20 !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(242,101,34,0.35) !important;
        }

        #main-welcome-container .carousel-btn {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 40px !important;
            height: 40px !important;
            background: white !important;
            border: none !important;
            border-radius: 50% !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
            cursor: pointer !important;
            z-index: 10 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
            padding: 0 !important;
            color: #333 !important;
        }

        #main-welcome-container .carousel-btn:hover {
            background: #f26522 !important;
            color: white !important;
            transform: translateY(-50%) scale(1.1) !important;
        }

        #main-welcome-container .carousel-prev {
            left: 0 !important;
        }

        #main-welcome-container .carousel-next {
            right: 0 !important;
        }

        /* ofertas-section: ver bloque de estilos al final de la sección css */

        /* ═══════════════════════════════════════════════════════════════
           SECCIÓN DE NOVEDADES - CARRUSEL HORIZONTAL
           ═══════════════════════════════════════════════════════════════ */
        #main-welcome-container .novedades-section {
            width: 100% !important;
            padding: 60px 0 !important;
            background-color: #f8f9fa !important;
            margin: 0 !important;
        }

        #main-welcome-container .novedades-title {
            text-align: center !important;
            margin-bottom: 10px !important;
        }

        #main-welcome-container .novedades-title h2 {
            font-size: 2.2rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            color: white !important;
            display: inline-block !important;
            background: linear-gradient(135deg, #f26522 0%, #e67125 100%) !important;
            padding: 8px 25px !important;
            border-radius: 55px !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
            letter-spacing: 1.5px !important;
            box-shadow: 0 6px 15px rgba(242,101,34,0.4) !important;
            border: 2px solid rgba(255,255,255,0.2) !important;
            margin: 0 !important;
        }

        #main-welcome-container .novedades-subtitle {
            text-align: center !important;
            font-size: 1rem !important;
            color: #666 !important;
            margin: 10px 0 30px !important;
        }

        /* Wrapper con flechas */
        #main-welcome-container .novedades-slider-wrapper {
            position: relative !important;
            padding: 0 50px !important;
        }

        /* Track scrollable */
        #main-welcome-container .novedades-grid {
            display: flex !important;
            gap: 20px !important;
            overflow-x: auto !important;
            scroll-behavior: smooth !important;
            padding: 10px 5px 20px !important;
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

        #main-welcome-container .novedades-grid::-webkit-scrollbar {
            display: none !important;
        }

        /* Tarjeta de novedad - estilo rec-card */
        #main-welcome-container .novedad-card {
            background: #ffffff !important;
            border-radius: 15px !important;
            padding: 20px !important;
            min-width: 280px !important;
            flex: 0 0 280px !important;
            display: flex !important;
            flex-direction: column !important;
            text-align: left !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07) !important;
            transition: box-shadow 0.3s ease, transform 0.3s ease !important;
            position: relative !important;
            overflow: visible !important;
            height: auto !important;
        }

        #main-welcome-container .novedad-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.13) !important;
            transform: translateY(-4px) !important;
        }

        #main-welcome-container .novedad-badge {
            display: inline-block !important;
            background-color: #f26522 !important;
            color: #ffffff !important;
            font-size: 0.75rem !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            padding: 5px 12px !important;
            border-radius: 15px !important;
            letter-spacing: 0.5px !important;
            margin-bottom: 12px !important;
            position: static !important;
        }

        #main-welcome-container .novedad-imagen {
            width: 100% !important;
            height: 200px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            margin-bottom: 15px !important;
            aspect-ratio: unset !important;
            position: static !important;
        }

        #main-welcome-container .novedad-imagen img {
            width: 100% !important;
            height: 200px !important;
            object-fit: contain !important;
            transition: transform 0.3s ease !important;
        }

        #main-welcome-container .novedad-card:hover .novedad-imagen img {
            transform: scale(1.04) !important;
        }

        #main-welcome-container .novedad-info {
            padding: 0 !important;
            flex-grow: 1 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 8px !important;
            justify-content: flex-start !important;
            align-items: flex-start !important;
        }

        #main-welcome-container .novedad-titulo {
            margin: 0 !important;
            font-size: 1rem !important;
            font-weight: 700 !important;
            color: #111 !important;
            text-align: left !important;
        }

        #main-welcome-container .novedad-titulo a {
            color: #111 !important;
            text-decoration: none !important;
            transition: color 0.3s ease !important;
        }

        #main-welcome-container .novedad-titulo a:hover {
            color: #f26522 !important;
        }

        #main-welcome-container .novedad-btn-detalle {
            display: block !important;
            background-color: #f26522 !important;
            color: #fff !important;
            text-align: center !important;
            padding: 10px !important;
            border-radius: 30px !important;
            text-decoration: none !important;
            font-weight: bold !important;
            font-size: 0.9rem !important;
            transition: background 0.3s !important;
            width: 100% !important;
            margin-top: 12px !important;
            border: none !important;
            cursor: pointer !important;
        }

        #main-welcome-container .novedad-btn-detalle:hover {
            background-color: #444 !important;
        }

        /* Botones del carrusel de novedades */
        #main-welcome-container .novedades-btn {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background-color: #fff !important;
            border: 1px solid #eaeaea !important;
            border-radius: 50% !important;
            width: 45px !important;
            height: 45px !important;
            cursor: pointer !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
            z-index: 10 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #f26522 !important;
            font-size: 1.1rem !important;
            transition: all 0.3s !important;
            padding: 0 !important;
        }

        #main-welcome-container .novedades-btn:hover {
            background-color: #f26522 !important;
            color: #fff !important;
        }

        #main-welcome-container .novedades-prev {
            left: 0 !important;
        }

        #main-welcome-container .novedades-next {
            right: 0 !important;
        }

        @media (max-width: 768px) {
            #main-welcome-container .novedades-slider-wrapper {
                padding: 0 35px !important;
            }
            #main-welcome-container .novedad-card {
                min-width: 230px !important;
                flex: 0 0 230px !important;
            }
        }

        /* ════════════════════════════════════════════════════════════════
           PROMO EMPRESAS SECTION
           ════════════════════════════════════════════════════════════════ */
        #main-welcome-container .promo-empresas {
            background-color: #fff9eb !important;
            padding: 32px 0 !important;
            border-bottom: 1px solid #fff9eb !important;
        }

        #main-welcome-container .promo-content {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 40px !important;
            flex-wrap: wrap !important;
            max-width: 1400px !important;
            margin: 0 auto !important;
            padding: 0 15px !important;
        }

        #main-welcome-container .promo-left {
            font-size: 1.3rem !important;
            color: #000 !important;
            text-align: right !important;
            line-height: 1.2 !important;
        }

        #main-welcome-container .promo-center {
            display: flex !important;
            flex-direction: column !important;
            color: #000 !important;
        }

        #main-welcome-container .promo-center strong {
            font-size: 1.1rem !important;
            margin-bottom: 2px !important;
        }

        #main-welcome-container .promo-center span {
            font-size: 1.3rem !important;
            color: #111 !important;
        }

        #main-welcome-container .btn-saber-mas {
            background-color: #05a64f !important;
            color: #ffffff !important;
            padding: 10px 30px !important;
            border-radius: 50px !important;
            text-decoration: none !important;
            font-weight: bold !important;
            font-size: 1.15rem !important;
            transition: background-color 0.3s ease !important;
            white-space: nowrap !important;
        }

        #main-welcome-container .btn-saber-mas:hover {
            background-color: #333333 !important;
        }

        @media (max-width: 992px) {
            #main-welcome-container .promo-content {
                gap: 20px !important;
                text-align: center !important;
            }
            #main-welcome-container .promo-left {
                text-align: center !important;
            }
        }

        @media (max-width: 576px) {
            #main-welcome-container .promo-content {
                flex-direction: column !important;
                gap: 15px !important;
            }
        }
            #main-welcome-container .producto-card {
                flex: 0 0 calc(33.333% - 13px) !important;
            }
        }

        @media (max-width: 992px) {
            #main-welcome-container .hero-slide h1 {
                font-size: 2.5rem !important;
            }
            #main-welcome-container .hero-slide h2 {
                font-size: 1.8rem !important;
            }
            #main-welcome-container .hero-slide p {
                font-size: 1.2rem !important;
            }
            /* categoria-grid: flex wrap, sin override de columnas */
            #main-welcome-container .producto-card {
                flex: 0 0 calc(33.333% - 13px) !important;
            }
        }

        @media (max-width: 768px) {
            #main-welcome-container .hero-slider-section {
                height: 50vh !important;
            }
            #main-welcome-container .hero-slide h1 {
                font-size: 2rem !important;
            }
            #main-welcome-container .hero-slide h2 {
                font-size: 1.5rem !important;
            }
            #main-welcome-container .hero-slide p {
                font-size: 1rem !important;
            }
            /* categoria-grid: flex wrap, sin override */
            #main-welcome-container .producto-card {
                flex: 0 0 calc(50% - 10px) !important;
            }
            /* novedades: carrusel, sin cambio de grid */
        }

        @media (max-width: 576px) {
            #main-welcome-container .hero-slider-section {
                height: 40vh !important;
            }
            #main-welcome-container .hero-slide h1 {
                font-size: 1.5rem !important;
            }
            #main-welcome-container .hero-slide h2 {
                font-size: 1.2rem !important;
            }
            #main-welcome-container .hero-slide p {
                font-size: 0.95rem !important;
            }
            #main-welcome-container .hero-slide h1,
            #main-welcome-container .hero-slide h2,
            #main-welcome-container .hero-slide p {
                margin-bottom: 10px !important;
            }
            #main-welcome-container .hero-controls button {
                width: 40px !important;
                height: 40px !important;
            }
            /* categoria-grid: flex wrap, sin override */
            #main-welcome-container .producto-card {
                flex: 0 0 100% !important;
            }
            #main-welcome-container .carousel-btn {
                width: 35px !important;
                height: 35px !important;
            }
            /* novedades carrusel sin override */
        }

        /* ═══════════════════════════════════════════════════════════════
           OFERTAS - ESTILO INDEX.HTML
           ═══════════════════════════════════════════════════════════════ */
        #main-welcome-container .ofertas-section {
            padding: 4rem 0 !important;
            background-color: #f8f8f8 !important;
        }

        #main-welcome-container .ofertas-heading {
            text-align: center !important;
            margin-bottom: 40px !important;
            padding: 0 10px !important;
        }

        #main-welcome-container .ofertas-heading h2 {
            font-size: 2.2rem !important;
            color: #111 !important;
            font-weight: 700 !important;
            margin: 0 0 5px !important;
        }

        #main-welcome-container .ofertas-heading p {
            font-size: 1rem !important;
            color: #666 !important;
            margin: 0 !important;
        }

        #main-welcome-container .ofertas-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
            gap: 25px !important;
            padding: 0 10px !important;
        }

        #main-welcome-container .oferta-card {
            background: #fff !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
            display: flex !important;
            flex-direction: column !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease !important;
            border: 1px solid #eaeaea !important;
            cursor: pointer !important;
            height: 100% !important;
        }

        #main-welcome-container .oferta-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
        }

        #main-welcome-container .oferta-content {
            padding: 25px 25px 20px !important;
            background-color: #fff !important;
        }

        #main-welcome-container .oferta-content h3 {
            font-size: 1.4rem !important;
            color: #111 !important;
            margin-bottom: 10px !important;
            font-weight: 700 !important;
        }

        #main-welcome-container .oferta-content p {
            font-size: 0.95rem !important;
            color: #555 !important;
            margin: 0 !important;
            line-height: 1.4 !important;
        }

        #main-welcome-container .oferta-image-wrapper {
            width: 100% !important;
            height: 220px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow: hidden !important;
            position: relative !important;
            margin-top: auto !important;
        }

        #main-welcome-container .oferta-image-wrapper img {
            max-width: 100% !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            display: block !important;
            transition: transform 0.3s ease !important;
            filter: drop-shadow(0 8px 12px rgba(0,0,0,0.3)) !important;
        }

        #main-welcome-container .oferta-card:hover .oferta-image-wrapper img {
            transform: scale(1.05) !important;
        }

        #main-welcome-container .bg-purple {
            background: linear-gradient(135deg, #8e24aa, #4a148c) !important;
        }

        #main-welcome-container .bg-dark-purple {
            background: linear-gradient(135deg, #4a148c, #311b92) !important;
        }

        #main-welcome-container .bg-red {
            background: linear-gradient(135deg, #c62828, #b71c1c) !important;
        }

        /* CLASES DE MARCA PARA TIPOGRAFÍAS */
        #main-welcome-container .brand-ezent .producto-overlay,
        #main-welcome-container .brand-ezent .hero-slide {
            font-family: 'EzentFont', sans-serif !important;
        }

        #main-welcome-container .brand-genwork .producto-overlay,
        #main-welcome-container .brand-genwork .hero-slide {
            font-family: 'GenworkFont', sans-serif !important;
        }

        #main-welcome-container .brand-ofiszu .producto-overlay,
        #main-welcome-container .brand-ofiszu .hero-slide {
            font-family: 'OfiszuFont', sans-serif !important;
        }

        #main-welcome-container .brand-prowork .producto-overlay,
        #main-welcome-container .brand-prowork .hero-slide {
            font-family: 'ProworkFont', sans-serif !important;
        }

        #main-welcome-container .brand-raito .producto-overlay,
        #main-welcome-container .brand-raito .hero-slide {
            font-family: 'RaitoFont', sans-serif !important;
        }
    </style>
@endsection

@section('content')
    <!-- CONTENEDOR PRINCIPAL AISLADO -->
    <div id="main-welcome-container">

        <!-- ══════════════════════════════════════════════════════════
             HERO SLIDER - 4 BANNERS ESTÁTICOS
             ══════════════════════════════════════════════════════════ -->
        <section class="hero-slider-section">
            @php
                $heroSlides = [
                    [
                        'image' => asset('banner-mundial.png'),
                        'h1' => 'OFISZU SFF',
                        'h2' => 'COMPUTADORA DE ESCRITORIO',
                        'p' => 'Diseñada para oficinas modernas y espacios reducidos, ofreciendo equipos ultra compactos, elegantes y eficientes para todo tipo de usuarios.',
                        'link' => route('catalogo'),
                        'link_text' => 'Ver Catálogo'
                    ],
                    [
                        'image' => asset('banner-mundial1.png'),
                        'h1' => 'GENWORK',
                        'h2' => 'COMPUTADORA DE ESCRITORIO',
                        'p' => 'Diseñada para usuarios de oficina, profesionales y diseñadores que requieren mayor rendimiento gráfico y estabilidad para el trabajo diario.',
                        'link' => route('catalogo'),
                        'link_text' => 'Ver Catálogo'
                    ],
                    [
                        'image' => asset('banner-mundial3.png'),
                        'h1' => 'PROWORK',
                        'h2' => 'ESTACIÓN DE TRABAJO',
                        'p' => 'Diseñada especialmente para trabajo continuo y entornos profesionales exigentes, adaptándose a todo tipo de usuario y aplicaciones especializadas.',
                        'link' => route('catalogo'),
                        'link_text' => 'Ver Catálogo'
                    ],
                    [
                        'image' => asset('banner-mundial2.png'),
                        'h1' => 'EZENT',
                        'h2' => 'COMPUTADORA DE ESCRITORIO',
                        'p' => 'Diseñada especialmente para usuarios de oficina y empresas, ofreciendo múltiples configuraciones adaptadas a cada necesidad de trabajo.',
                        'link' => route('catalogo'),
                        'link_text' => 'Ver Catálogo'
                    ],
                ];
            @endphp

            @foreach ($heroSlides as $index => $slide)
                <div class="hero-slide @if($index == 0) active @endif" style="background: url('{{ $slide['image'] }}') center/cover;">
                    <div class="hero-slide-content">
                        <h1>{{ $slide['h1'] }}</h1>
                        <h2>{{ $slide['h2'] }}</h2>
                        <p>{{ $slide['p'] }}</p>
                        <a class="hero-btn" href="{{ $slide['link'] }}">{{ $slide['link_text'] }}</a>
                    </div>
                </div>
            @endforeach

            <div class="hero-controls">
                <button class="hero-prev" aria-label="Anterior"><i class="fa-solid fa-chevron-left"></i></button>
                <button class="hero-next" aria-label="Siguiente"><i class="fa-solid fa-chevron-right"></i></button>
            </div>

            <div class="hero-bottom-controls">
                <div class="hero-pill">
                    <button class="hero-play-btn" id="playPauseBtn" aria-label="Pausar">
                        <i class="fa-solid fa-pause"></i>
                    </button>
                    <div class="hero-dots"></div>
                </div>
            </div>
        </section>

        <!-- ══════════════════════════════════════════════════════════
             PROMO EMPRESAS
             ══════════════════════════════════════════════════════════ -->
        <section class="promo-empresas">
            <div class="promo-content">
                <div class="promo-left">
                    <strong>¿Busca equipos para su empresa?</strong>
                </div>
                <div class="promo-center">
                    <span>Las nuevas empresas afiliadas a Kenya reciben beneficios únicos en su primer pedido.</span>
                </div>
                <div class="promo-right">
                    <a href="https://wa.me/51999888777" class="btn-saber-mas" target="_blank">¡Cotiza Ahora!</a>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════
             SECCIÓN DE PRODUCTOS Y CATEGORÍAS
             ═══════════════════════════════════════════════════════════ -->
        <section class="productos-section">
            <div class="section-container">
                <div class="section-title">
                    <h2>Categorías</h2>
                </div>

                <!-- GRID DE CATEGORÍAS -->
                <div class="categoria-grid" id="categoria-grid">
                    <button class="categoria-btn active" data-category="*">
                        <div class="categoria-card">
                            <img src="{{ asset('producto-placeholder.png') }}" alt="Todos">
                        </div>
                        <p class="categoria-label">Todos</p>
                    </button>
                    @foreach ($categorias as $cat)
                        <button class="categoria-btn" data-category=".filter-{{ $cat->id }}">
                            <div class="categoria-card">
                                @if ($cat->img_url)
                                    <img src="{{ $cat->img_url }}" alt="{{ $cat->nombre }}">
                                @else
                                    <img src="{{ asset('producto.jpg') }}" alt="{{ $cat->nombre }}">
                                @endif
                            </div>
                            <p class="categoria-label">{{ $cat->nombre }}</p>
                        </button>
                    @endforeach
                </div>

                <!-- CARRUSEL DE PRODUCTOS -->
                <div class="productos-carousel">
                    <button class="carousel-btn carousel-prev" aria-label="Anterior">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <div class="carousel-track" id="carousel-track">
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

                            <div class="producto-card filter-{{ $mod->categoria_id }} {{ $brandClass }}">
                                <div class="producto-imagen">
                                    @if ($mod->img_mod)
                                        <img src="{{ asset('storage/' . $mod->img_mod) }}" alt="{{ $mod->descripcion ?? 'Producto' }}">
                                    @else
                                        <img src="{{ asset('producto.jpg') }}" alt="{{ $mod->descripcion ?? 'Producto' }}">
                                    @endif
                                    <div class="producto-overlay">
                                        @if ($mod->categoria_id)
                                            <h6 title="{{ ($mod->prefix ? $mod->prefix . ' ' : '') . ($mod->descripcion ?? '') }}">
                                                @if($mod->prefix)<span>{{ $mod->prefix }} </span>@endif{{ Str::limit($mod->descripcion ?? '', 160) }}
                                            </h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="producto-accion">
                                    <a href="{{ route('detallemod', $mod->id) }}">
                                        <i class='fa-solid fa-shopping-bag'></i> Ver Catálogo
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-btn carousel-next" aria-label="Siguiente">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════
             SECCIÓN DE OFERTAS
             ═══════════════════════════════════════════════════════════ -->
        <section class="ofertas-section">
            <div class="section-container">
                <div class="ofertas-heading">
                    <h2>Ver Ofertas</h2>
                    <p>Productos con super promociones y descuentos</p>
                </div>

                <div class="ofertas-grid">
                    <div class="oferta-card">
                        <div class="oferta-content">
                            <h3>OFISZU SFF</h3>
                            <p>Ofreciendo equipos ultra compactos, elegantes y eficientes.</p>
                        </div>
                        <div class="oferta-image-wrapper bg-purple">
                            <img src="{{ asset('ofiszusff.png') }}" alt="Ofiszu SFF">
                        </div>
                    </div>
                    <div class="oferta-card">
                        <div class="oferta-content">
                            <h3>Grandes descuentos</h3>
                            <p>Es tu oportunidad para renovar o adquirir tu equipo Kenya</p>
                        </div>
                        <div class="oferta-image-wrapper bg-dark-purple">
                            <img src="{{ asset('oferta-descuentos.png') }}" alt="Descuentos Kenya">
                        </div>
                    </div>
                    <div class="oferta-card">
                        <div class="oferta-content">
                            <h3>EZENT V1_MT</h3>
                            <p>Equipo diseñado especialmente para usuarios de oficina y empresas</p>
                        </div>
                        <div class="oferta-image-wrapper bg-red">
                            <img src="{{ asset('oferta-thinkpad.png') }}" alt="Ezent V1 MT">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════
             SECCIÓN DE NOVEDADES
             ═══════════════════════════════════════════════════════════ -->
        <!-- ═══════════════════════════════════════════════════════════
             SECCIÓN DE NOVEDADES - CARRUSEL
             ═══════════════════════════════════════════════════════════ -->
        <section class="novedades-section">
            <div class="section-container">
                <div class="novedades-title">
                    <h2>Novedades</h2>
                </div>
                <p class="novedades-subtitle">Nuevos productos en nuestra lista, ¡qué esperas!</p>

                <div class="novedades-slider-wrapper">
                    <button class="novedades-btn novedades-prev" aria-label="Anterior">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <div class="novedades-grid" id="novedades-track">
                        @if(isset($novedades) && $novedades->count() > 0)
                            @foreach($novedades as $novedad)
                                @php
                                    $novedadImg = $novedad->imagen ?: $novedad->imagen_1;
                                    $novedadNombre = $novedad->nombre ?: $novedad->descripcion;
                                    $novedadUrl = $novedad->modelo ? route('detallemod', $novedad->modelo->id) : '#';
                                @endphp
                                <div class="novedad-card">
                                    <span class="novedad-badge">Nuevo</span>
                                    <div class="novedad-imagen">
                                        @if($novedadImg)
                                            <img src="{{ asset('storage/' . $novedadImg) }}" alt="{{ $novedadNombre ?? 'Producto nuevo' }}">
                                        @else
                                            <img src="{{ asset('producto.jpg') }}" alt="{{ $novedadNombre ?? 'Producto nuevo' }}">
                                        @endif
                                    </div>
                                    <div class="novedad-info">
                                        <h5 class="novedad-titulo">
                                            <a href="{{ $novedadUrl }}">{{ $novedadNombre ?? 'Producto nuevo' }}</a>
                                        </h5>
                                        <a href="{{ $novedadUrl }}" class="novedad-btn-detalle">Ver detalles</a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button class="novedades-btn novedades-next" aria-label="Siguiente">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>

    </div><!-- FIN CONTENEDOR AISLADO -->

    <script src="{{ asset('js/detallemod.js') }}"></script>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('main-welcome-container');

            // ==================================================
            // 1. SLIDER HERO
            // ==================================================
            const slides = container.querySelectorAll('.hero-slide');
            const dotsContainer = container.querySelector('.hero-dots');
            const playPauseBtn = container.querySelector('#playPauseBtn');
            const heroNext = container.querySelector('.hero-next');
            const heroPrev = container.querySelector('.hero-prev');

            let currentSlide = 0;
            let slideInterval;
            let isPlaying = true;

            if (slides.length > 0) {
                slides.forEach((_, index) => {
                    const dot = document.createElement('div');
                    dot.classList.add('hero-dot');
                    if (index === 0) dot.classList.add('active');
                    dot.addEventListener('click', () => goToSlide(index));
                    dotsContainer.appendChild(dot);
                });

                const dots = container.querySelectorAll('.hero-dot');

                function goToSlide(index) {
                    slides[currentSlide].classList.remove('active');
                    dots[currentSlide].classList.remove('active');
                    currentSlide = index;
                    slides[currentSlide].classList.add('active');
                    dots[currentSlide].classList.add('active');
                }

                function nextSlide() {
                    goToSlide((currentSlide + 1) % slides.length);
                }

                function prevSlide() {
                    goToSlide((currentSlide - 1 + slides.length) % slides.length);
                }

                function startInterval() {
                    slideInterval = setInterval(nextSlide, 5000);
                }

                function resetInterval() {
                    clearInterval(slideInterval);
                    if(isPlaying) startInterval();
                }

                heroNext?.addEventListener('click', () => { nextSlide(); resetInterval(); });
                heroPrev?.addEventListener('click', () => { prevSlide(); resetInterval(); });

                playPauseBtn?.addEventListener('click', () => {
                    isPlaying = !isPlaying;
                    const icon = playPauseBtn.querySelector('i');
                    if (isPlaying) {
                        icon.classList.remove('fa-play');
                        icon.classList.add('fa-pause');
                        startInterval();
                    } else {
                        icon.classList.remove('fa-pause');
                        icon.classList.add('fa-play');
                        clearInterval(slideInterval);
                    }
                });

                startInterval();
            }

            // ==================================================
            // 2. FILTRADO DE CATEGORÍAS
            // ==================================================
            const categoryButtons = container.querySelectorAll('.categoria-btn');
            const productCards = container.querySelectorAll('.producto-card');

            categoryButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    categoryButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const selectedCategory = btn.getAttribute('data-category');
                    productCards.forEach(card => {
                        if (selectedCategory === '*') {
                            card.style.display = 'flex';
                        } else {
                            const cardClass = selectedCategory.substring(1);
                            card.style.display = card.classList.contains(cardClass) ? 'flex' : 'none';
                        }
                    });
                });
            });

            // ==================================================
            // 3. CARRUSEL DE PRODUCTOS
            // ==================================================
            const carousel = container.querySelector('#carousel-track');
            const carouselPrev = container.querySelector('.carousel-prev');
            const carouselNext = container.querySelector('.carousel-next');

            carouselPrev?.addEventListener('click', () => {
                carousel?.scrollBy({ left: -330, behavior: 'smooth' });
            });
            carouselNext?.addEventListener('click', () => {
                carousel?.scrollBy({ left: 330, behavior: 'smooth' });
            });

            // ==================================================
            // 4. CARRUSEL DE NOVEDADES
            // ==================================================
            const novedadesTrack = container.querySelector('#novedades-track');
            const novedadesPrev = container.querySelector('.novedades-prev');
            const novedadesNext = container.querySelector('.novedades-next');

            novedadesPrev?.addEventListener('click', () => {
                novedadesTrack?.scrollBy({ left: -300, behavior: 'smooth' });
            });
            novedadesNext?.addEventListener('click', () => {
                novedadesTrack?.scrollBy({ left: 300, behavior: 'smooth' });
            });
        });
    </script>
@endsection
