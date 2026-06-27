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
        /* Tipografías de líneas de producto Kenya (Kenyav1 usa Inter como fallback del layout) */
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

        /* =============================================================
           LAYOUT SECTION — UNICO BLOQUE DE ESTILOS DE DISTRIBUCION
           Anula: Bootstrap .row/.col-lg-3, style.css, cualquier herencia
           ============================================================= */

        /* ---- Contenedor site-width ---- */
        .site-width {
            margin: 0 auto;
            padding: 0;
            box-sizing: border-box;
            width: 100%;
        }

        /* ---- FILTROS DE CATEGORIA ---- */
        /* Grid alineado con la grilla de productos: 4 columnas iguales */
        section#productos ul#portfolio-flters {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 16px !important;
            justify-content: center !important;
            align-items: stretch !important;
            list-style: none !important;
            padding: 0 !important;
            margin: 0 auto 28px auto !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        section#productos ul#portfolio-flters li {
            width: 100% !important;
            cursor: pointer;
            margin: 0 !important;
            transition: transform 0.25s ease !important;
        }

        section#productos ul#portfolio-flters li:hover {
            transform: translateY(-3px) !important;
        }

        /* Card de categoria: ocupa todo el ancho de su celda y conserva proporcion */
        section#productos ul#portfolio-flters li .card {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            aspect-ratio: 4 / 3 !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            border: 2px solid transparent !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.09) !important;
            transition: all 0.25s ease !important;
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
        }

        section#productos ul#portfolio-flters li.filter-active .card,
        section#productos ul#portfolio-flters li .card:hover {
            border-color: #ee7c31 !important;
            box-shadow: 0 6px 18px rgba(238,124,49,0.22) !important;
            transform: translateY(-3px) !important;
        }

        section#productos ul#portfolio-flters li .card-img-top {
            width: 100% !important;
            height: 65% !important;
            object-fit: contain !important;
            object-position: center !important;
            padding: 10px !important;
            background: #fff;
        }

        section#productos ul#portfolio-flters li .card-body {
            padding: 0 !important;
            background: transparent !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            height: 35% !important;
        }

        section#productos ul#portfolio-flters li .card-text {
            font-size: clamp(0.7rem, 0.85vw, 0.85rem) !important;
            font-weight: 700 !important;
            color: #fff !important;
            text-align: center !important;
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
            margin: 0 !important;
            line-height: 1.2 !important;
            padding: 8px 4px !important;
            background: #ee7c31 !important;
            border-radius: 0 0 10px 10px !important;
            width: 100% !important;
            min-height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        @media (max-width: 1200px) {
            section#productos ul#portfolio-flters {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 14px !important;
            }
        }

        @media (max-width: 768px) {
            section#productos ul#portfolio-flters {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 12px !important;
            }
        }

        @media (max-width: 480px) {
            section#productos ul#portfolio-flters {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
            }
            section#productos ul#portfolio-flters li .card-text {
                font-size: 0.7rem !important;
                padding: 6px 2px !important;
            }
        }

        /* ---- CARRUSEL DE MODELOS/PRODUCTOS ---- */
        section#productos .prod-filter-container {
            display: flex !important;
            flex-wrap: nowrap !important;
            gap: 20px !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            scroll-behavior: smooth !important;
            justify-content: flex-start !important;
            align-items: stretch !important;
            margin: 0 !important;
            padding: 10px 0 !important;
            width: 100% !important;
            cursor: grab;
            user-select: none;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        section#productos .prod-filter-container::-webkit-scrollbar {
            display: none;
        }
        section#productos .prod-filter-container.dragging {
            cursor: grabbing;
        }

        /* Anula Bootstrap col-lg-3 / col-md-4: tarjeta de ancho fijo */
        section#productos .prod-filter-item {
            flex: 0 0 auto !important;
            width: 25% !important;
            min-width: 260px !important;
            max-width: 320px !important;
            padding: 0 !important;
            margin: 0 !important;
            scroll-snap-align: start;
            transition: opacity 0.35s ease, transform 0.35s ease;
            opacity: 1;
            transform: scale(1);
        }

        /* Card de producto */
        .prod-card-container {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.09);
            transition: all 0.3s ease;
            height: 100%;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .prod-card-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 24px rgba(238, 124, 49, 0.18);
        }

        /* Imagen de producto — proporcion 68% */
        .prod-image-wrapper {
            position: relative !important;
            width: 100% !important;
            overflow: hidden !important;
            padding-top: 68% !important;
            height: 0 !important;
        }

        .prod-main-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 45%;
            display: block;
            transition: transform 0.5s ease;
        }

        .prod-card-container:hover .prod-main-image {
            transform: scale(1.05);
        }

        .prod-image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 12px 14px;
            background: linear-gradient(to top, rgba(0,0,0,0.72), rgba(0,0,0,0.15));
            color: white;
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
        }

        .prod-overlay-text {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.25;
        }

        .prod-prefix {
            display: block;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            font-size: 0.65em;
            opacity: 0.78;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1px;
        }

        .prod-details {
            padding: 12px 14px;
            background: #f8f9fa;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .prod-action-btn { margin-top: auto; }

        .prod-action-btn a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            background: #ee7c31;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.82rem;
            transition: all 0.3s ease;
        }

        .prod-action-btn a:hover {
            background: #d96b20;
            transform: translateY(-2px);
        }

        .prod-action-btn i { margin-right: 7px; }

        /* --- UTILS --- */
        .contorno { border: 1px solid #cecece; border-radius: 2px; background-color: #fff; }
        .descripcion { padding: 7px 9px; }
        .p-nombre { font-family: "Inter", sans-serif; color: #444; font-weight: 600; }
        .p-nombre:hover { color: #000; text-decoration: underline; }
        .p-precio { font-size: 20px; color: #1965a7; }
        .p-precio-old { font-size: 12px; color: red; text-decoration: line-through; }
        .team { background-color: #f2fff0; }
        .oferta { position: absolute; right: -8px; top: 8px; background-color: red; color:#fff; padding: 0 10px; z-index:1; border:1px solid #bd0000; border-radius:15px; }
        .novedad { position: absolute; right: -8px; top: 8px; background-color: #099409; color:#fff; padding: 0 10px; z-index:1; border:1px solid green; border-radius:15px; }

        /* --- BOTONES PRODUCTOS --- */
        .botones { display:flex; flex-wrap:nowrap; flex-direction:row; justify-content:space-between; }
        .botones a:first-child { background-color:#2869a1; color:#ffffff; text-align:center; padding:.3rem; flex:1 1 100%; border:none; transition:border-radius 0.6s linear; }
        .botones a:first-child:hover { background-color:#124e83; }
        .botones a:nth-child(2) { display:flex; justify-content:center; align-items:center; background-color:#57cf57; color:#ffffff; border-top-left-radius:.5rem; border-bottom-left-radius:.5rem; flex:1 1 0%; width:0; transition:flex .5s; }
        .botones a:nth-child(2):hover { background-color:#1bd81b; }
        .botones:hover>a:nth-child(2) { flex:1 1 20%; margin-left:.5rem; }
        .botones:hover>a:first-child { border-top-right-radius:.5rem; border-bottom-right-radius:.5rem; }

        /* ---- RESPONSIVE CARRUSEL ---- */
        @media (max-width: 1200px) {
            section#productos .prod-filter-item {
                width: 33.333% !important;
                min-width: 240px !important;
            }
        }

        @media (max-width: 768px) {
            section#productos .prod-filter-item {
                width: 50% !important;
                min-width: 220px !important;
            }
            .prod-image-wrapper { padding-top: 62% !important; }
        }

        @media (max-width: 480px) {
            section#productos .prod-filter-item {
                width: 80% !important;
                min-width: 200px !important;
            }
            .prod-image-wrapper { padding-top: 68% !important; }
        }

        /* ---- CONTROLES Y ESTILOS DEL CARRUSEL ---- */
        .prod-carousel-wrapper {
            position: relative;
            width: 100%;
        }

        .prod-carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 50%;
            background: rgba(255,255,255,0.95);
            color: #ee7c31;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .prod-carousel-btn:hover {
            background: #ee7c31;
            color: #fff;
        }

        .prod-carousel-prev { left: 10px; }
        .prod-carousel-next { right: 10px; }

        @media (max-width: 768px) {
            .prod-carousel-btn {
                width: 36px;
                height: 36px;
                font-size: 1.2rem;
            }
            .prod-carousel-prev { left: 6px; }
            .prod-carousel-next { right: 6px; }
        }

        /* Mejora de boton Ver Catalogo y textos con Inter */
        section#productos .prod-action-btn a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            background: #ee7c31;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            box-shadow: 0 4px 12px rgba(238,124,49,0.25);
            transition: all 0.25s ease;
        }

        section#productos .prod-action-btn a:hover {
            background: #d96b20;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(238,124,49,0.35);
        }

        section#productos .prod-overlay-text,
        section#productos .prod-prefix {
            font-family: 'Inter', sans-serif;
        }

        /* FIN DE LA SECCION DE LAYOUT */

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
            position: relative;
            clear: both;
            padding: 40px 0;
            background-color: #f8f9fa;
            z-index: 1;
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
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 700;
            text-transform: uppercase;
            color: white !important;
            text-align: center;
            margin: 0 auto 15px;
            padding: 8px 25px;
            position: relative;
            display: inline-block;
            background: linear-gradient(135deg, #ee7c31 0%, #e67125 100%);
            border-radius: 55px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: 1.5px;
            box-shadow: 0 6px 15px rgba(238, 124, 49, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .novedades-title-section p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Contenedor del carrusel */
        .novedades-carousel-wrapper {
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
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
            box-sizing: border-box;
        }

        /* Tarjeta de producto */
        .novedades-product-card {
            position: relative;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .novedades-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Badge "Nuevo" */
        .novedades-badge {
            position: absolute;
            top: 10px;
            right: 10px;
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
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
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
           HERO SLIDER — 60vh full-width carousel
           ===================================================== */
        :root {
            --hero-height: 60vh;
            --hero-min-height: 400px;
        }

        #hero {
            width: 100% !important;
            height: var(--hero-height) !important;
            min-height: var(--hero-min-height) !important;
            padding: 0 !important;
            margin: 0 !important;
            overflow: hidden !important;
            position: relative !important;
        }

        .hero-slider {
            position: relative;
            height: 100%;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            background-color: #111;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s ease-in-out;
            display: flex;
            align-items: center;
            color: #fff;
            z-index: 1;
        }

        .slide.active {
            opacity: 1;
            z-index: 2;
        }

        .slide .container { text-align: left; position: relative; z-index: 3; }

        .slide h1 {
            font-size: 2.1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: -8px;
            transform: translateY(30px);
            opacity: 0;
            transition: all 0.8s ease 0.2s;
        }

        .slide h2 {
            font-size: 1.4rem;
            font-weight: 250;
            color: #ffffff;
            margin-bottom: 15px;
            transform: translateY(30px);
            opacity: 0;
            transition: all 0.8s ease 0.3s;
        }

        .slide p {
            font-size: 2.0rem;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 35px;
            max-width: 550px;
            transform: translateY(30px);
            opacity: 0;
            transition: all 0.8s ease 0.4s;
        }

        .slide .btn-hero {
            transform: translateY(30px);
            opacity: 0;
            transition: background 0.3s, transform 0.8s ease 0.5s;
        }

        .slide.active h1,
        .slide.active h2,
        .slide.active p,
        .slide.active .btn-hero {
            transform: translateY(0);
            opacity: 1;
        }

        .btn-hero {
            padding: 12px 30px;
            background: linear-gradient(to right, #ff3c00, #ff9c00);
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 100px;
            display: inline-block;
        }

        .btn-hero:hover { background-color: #d1551a; }

        .slider-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 3%;
            transform: translateY(-50%);
            z-index: 10;
            pointer-events: none;
        }

        .slider-controls button {
            background-color: rgba(0,0,0,0.4);
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            pointer-events: auto;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slider-controls button:hover {
            background-color: #f26522;
            border-color: #f26522;
            transform: scale(1.1);
        }

        .slider-bottom-controls {
            position: absolute;
            bottom: 30px;
            width: 100%;
            display: flex;
            justify-content: center;
            z-index: 10;
        }

        .pill-container {
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 50px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            backdrop-filter: blur(5px);
        }

        .play-pause-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            transition: color 0.3s;
        }

        .play-pause-btn:hover { color: #f26522; }

        .slider-dots {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .dot {
            width: 12px;
            height: 12px;
            background-color: transparent;
            border: 1.5px solid white;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            padding: 0;
        }

        .dot.active {
            background-color: white;
            border-color: white;
            transform: scale(1.1);
        }

        .dot:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 992px) {
            .slide p { font-size: 2rem; max-width: 100%; }
            .slide h1 { font-size: 0.95rem; }
            .slide h2 { font-size: 1.2rem; }
            :root { --hero-height: 50vh; }
        }

        @media (max-width: 576px) {
            .slide p { font-size: 1.5rem; line-height: 1.2; }
            .slide h1 { font-size: 0.85rem; }
            .slide h2 { font-size: 1.1rem; margin-bottom: 10px; }
            :root { --hero-height: 40vh; }
        }
    </style>
@endsection
@section('content')
    <!-- ======= Hero Slider ======= -->
    <section id="hero" class="hero-slider">
        @php $totalSlides = $banners->count(); @endphp
        @foreach ($banners as $banner)
            @php
                $bgImg = $banner->imagen
                    ? asset('storage/' . $banner->imagen)
                    : asset('landing/img/slide/slide-' . ($loop->index + 1) . '.jpg');

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
            @endphp

            <div class="slide @if($loop->index == 0) active @endif" style="background: url('{{ $bgImg }}') center/cover;">
                <div class="container">
                    <h1>{!! $tituloHtml !!}</h1>
                    @if($bannerDesc !== '' && strtolower($bannerDesc) !== 'especialistas en soporte técnico')
                        <p>{{ $bannerDesc }}</p>
                    @endif
                    @if($banner->link && $banner->link_nombre)
                        <a class="btn-hero" href="{{ $banner->link }}">{{ $banner->link_nombre }}</a>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="slider-controls">
            <button class="prev-slide" aria-label="Previous slide"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="next-slide" aria-label="Next slide"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <div class="slider-bottom-controls">
            <div class="pill-container">
                <button id="playPauseBtn" class="play-pause-btn" aria-label="Pause">
                    <i class="fa-solid fa-pause"></i>
                </button>
                <div class="slider-dots"></div>
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
                                <div class="card">
                                    <img class="card-img-top" src="{{ asset('producto-placeholder.png') }}" alt="Card image cap">
                                    <div class="card-body">
                                        <p class="card-text" style="color:black">Todos</p>
                                    </div>
                                </div>
                            </li>
                            @foreach ($categorias as $cat)
                                <li data-filter=".filter-{{ $cat->id }}">
                                    <div class="card">
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
                <div class="prod-carousel-wrapper">
                    <button type="button" class="prod-carousel-btn prod-carousel-prev" aria-label="Anterior">
                        <i class="bx bx-chevron-left"></i>
                    </button>
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
                                        <div class="prod-action-btn">
                                            <a href="{{ route('detallemod', $mod->id) }}"><i class='bx bx-shopping-bag'></i>
                                                Ver Catálogo</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="prod-carousel-btn prod-carousel-next" aria-label="Siguiente">
                        <i class="bx bx-chevron-right"></i>
                    </button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('#portfolio-flters li');
            const portfolioItems = document.querySelectorAll('.prod-filter-item');
            const carousel = document.querySelector('.prod-filter-container');

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

                // Volver al inicio del carrusel al filtrar
                if (carousel) carousel.scrollLeft = 0;
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

            // Controles del carrusel de productos
            const prevBtn = document.querySelector('.prod-carousel-prev');
            const nextBtn = document.querySelector('.prod-carousel-next');
            if (carousel && prevBtn && nextBtn) {
                const scrollStep = () => carousel.clientWidth * 0.85;

                prevBtn.addEventListener('click', () => {
                    carousel.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
                });
                nextBtn.addEventListener('click', () => {
                    carousel.scrollBy({ left: scrollStep(), behavior: 'smooth' });
                });

                // Arrastre con mouse
                let isDown = false, isDragging = false, startX, scrollLeft;
                carousel.addEventListener('mousedown', (e) => {
                    isDown = true;
                    isDragging = false;
                    carousel.classList.add('dragging');
                    startX = e.pageX - carousel.offsetLeft;
                    scrollLeft = carousel.scrollLeft;
                });
                carousel.addEventListener('mouseleave', () => {
                    isDown = false;
                    carousel.classList.remove('dragging');
                });
                carousel.addEventListener('mouseup', () => {
                    isDown = false;
                    setTimeout(() => { isDragging = false; }, 50);
                    carousel.classList.remove('dragging');
                });
                carousel.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - carousel.offsetLeft;
                    const walk = (x - startX) * 1.5;
                    if (Math.abs(x - startX) > 5) isDragging = true;
                    carousel.scrollLeft = scrollLeft - walk;
                });

                // Evitar que el arrastre dispare clicks en los enlaces
                carousel.addEventListener('click', (e) => {
                    if (isDragging) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                }, true);
            }
        });
    </script>
    <!-- FIN DEL SCRIPT DE LAS CATEGORIAS -->


    <!-- FIN DEL SCRIPT DEL CARRUSEL DE BANNERS -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.querySelector('.promo-banner-track');
            const items = document.querySelectorAll('.promo-banner-slide');
            const prevBtn = document.querySelector('.promo-banner-prev');
            const nextBtn = document.querySelector('.promo-banner-next');
            const dotsContainer = document.querySelector('.promo-banner-dots');

            if (!track || !prevBtn || !nextBtn || !dotsContainer || items.length === 0) {
                return;
            }

            let currentIndex = 0;
            let visibleItems = 1;
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
                if (!items[0]) return;
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

    <!-- SCRIPT HERO SLIDER -->
    <script>
        (function(){
            const slides = document.querySelectorAll('.hero-slider .slide');
            const dotsContainer = document.querySelector('.hero-slider .slider-dots');
            const playPauseBtn = document.getElementById('playPauseBtn');
            const prevBtn = document.querySelector('.prev-slide');
            const nextBtn = document.querySelector('.next-slide');
            let playPauseIcon = playPauseBtn ? playPauseBtn.querySelector('i') : null;

            let currentSlide = 0;
            let slideInterval;
            let isPlaying = true;

            if (slides.length > 0) {
                slides.forEach((_, index) => {
                    const dot = document.createElement('div');
                    dot.classList.add('dot');
                    if (index === 0) dot.classList.add('active');
                    dot.addEventListener('click', () => {
                        goToSlide(index);
                        if(isPlaying) resetInterval();
                    });
                    dotsContainer.appendChild(dot);
                });

                const dots = document.querySelectorAll('.hero-slider .dot');

                function goToSlide(index) {
                    slides[currentSlide].classList.remove('active');
                    dots[currentSlide].classList.remove('active');
                    currentSlide = index;
                    slides[currentSlide].classList.add('active');
                    dots[currentSlide].classList.add('active');
                }

                function nextSlide() {
                    let index = (currentSlide + 1) % slides.length;
                    goToSlide(index);
                }

                function prevSlide() {
                    let index = (currentSlide - 1 + slides.length) % slides.length;
                    goToSlide(index);
                }

                function startInterval() {
                    slideInterval = setInterval(nextSlide, 5000);
                }

                function resetInterval() {
                    clearInterval(slideInterval);
                    if(isPlaying) startInterval();
                }

                nextBtn?.addEventListener('click', () => {
                    nextSlide();
                    if(isPlaying) resetInterval();
                });

                prevBtn?.addEventListener('click', () => {
                    prevSlide();
                    if(isPlaying) resetInterval();
                });

                if(playPauseBtn) {
                    playPauseBtn.addEventListener('click', () => {
                        if (isPlaying) {
                            clearInterval(slideInterval);
                            playPauseIcon.classList.remove('fa-pause');
                            playPauseIcon.classList.add('fa-play');
                        } else {
                            nextSlide();
                            startInterval();
                            playPauseIcon.classList.remove('fa-play');
                            playPauseIcon.classList.add('fa-pause');
                        }
                        isPlaying = !isPlaying;
                    });
                }

                startInterval();
            }
        })();
    </script>
@endsection
