@extends('layouts.landing')
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li class="kenya-active"><a href="{{ url('/') }}" class="kenya-nav-link"><i
                        class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="#" class="kenya-nav-link">Catalogo</a></li>
            <li><a href="#ofertas" class="kenya-nav-link">Ofertas</a></li>
            <li><a href="#novedades" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="#contact" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('css')
    <style>
        .contorno {
            border: 1px solid #cecece;
            border-radius: 2px;
            background-color: #fff
        }

        .descripcion {
            padding: 7px 9px;
        }

        .p-nombre {
            font-family: "Raleway", sans-serif;
            color: #444;
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
        }

        .prod-main-image {
            height: 100%;
            object-fit: cover;
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
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 100%);
            padding: 15px;
            color: white;
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
            margin: 0;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            width: 100%;
            text-align: center;
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

            .prod-image-wrapper {}
        }

        @media (max-width: 768px) {
            .prod-filter-item {
                flex: 0 0 calc(50% - 15px);
                max-width: calc(50% - 15px);
            }

            .prod-image-wrapper {}
        }

        @media (max-width: 576px) {
            .prod-filter-item {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .prod-image-wrapper {}
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
            .promo-banner-track {

            }
        }

        @media (max-width: 768px) {
            .promo-banner-track {

            }

            .promo-banner-content {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .promo-banner-track {
            }

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
            padding: 60px 0;
            background-color: #f8f9fa;
        }

        .novedades-container {
            max-width: 1230px;
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
            height: 200px;
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
    </style>
@endsection
@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero">
        <div class="hero-container">
            <div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel">

                <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

                <div class="carousel-inner" role="listbox" style="text-align: center;">

                    @foreach ($banners as $banner)
                        @if ($banner->imagen)
                            <div class="carousel-item @if ($loop->index == 0) active @endif"
                                style="background-image: url('storage/{{ $banner->imagen }}');">
                            @else
                                <div class="carousel-item @if ($loop->index == 0) active @endif"
                                    style="background-image: url('landing/img/slide/slide-{{ $loop->index + 1 }}.jpg');">
                        @endif
                        <div class="carousel-container" style="text-align: center;">
                            <div class="carousel-content container">
                                <!-- Título PRINCIPAL (Enorme y destacado) -->
                                <h2 style="text-align: center; font-size: 4.5rem; font-weight: 900; text-shadow: 3px 3px 6px rgba(0,0,0,0.7); line-height: 1.1; margin-bottom: 20px;"
                                    class="animate__animated animate__fadeInDown">
                                    <span style="color: {{ $banner->titulo_color }};">{{ $banner->titulo }}</span>
                                </h2>

                                <!-- Subtítulo (Blanco y muy legible) -->
                                <h3 style="text-align: center; font-size: 2.5rem; font-weight: 700; color: #ffffff; margin-top: 10px; text-shadow: 2px 2px 5px rgba(0,0,0,0.8); letter-spacing: 0.8px;"
                                    class="animate__animated animate__fadeInDown">
                                    {{ $banner->descripcion }}
                                </h3>

                                <!-- Descripción (Texto grande y claro) -->
                                <p style="text-align: center; font-size: 1.8rem; font-weight: 400; color: #f8f8f8; margin-top: 25px; max-width: 900px; margin-left: auto; margin-right: auto; text-shadow: 1px 1px 3px rgba(0,0,0,0.6); line-height: 1.4;"
                                    class="animate__animated animate__fadeInDown">
                                    {{ $banner->contenido }}
                                </p>

                                @if ($banner->link)
                                    <!-- Botón (Naranja vibrante y llamativo) -->
                                    <a href="#"
                                        class="btn-get-started animate__animated animate__fadeInUp scrollto"
                                        style="background-color: #ee7c31; border: none; padding: 15px 40px; font-size: 1.3rem; margin-top: 35px; font-weight: 700; border-radius: 6px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.3s ease; display: inline-block;">
                                        {{ $banner->link_nombre }}
                                    </a>
                                @endif
                            </div>
                        </div>
                </div>
                @endforeach
            </div>

            <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon icofont-rounded-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon icofont-rounded-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

        </div>
        </div>
    </section><!-- End Hero -->

    <main id="main">
        <!-- ======= About Us Section ======= -->
        <section id="productos" class="portfolio section-bg">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2>CATEGORIAS</h2>
                        </div>
                        <ul id="portfolio-flters">
                            <li data-filter="*" class="filter-active">
                                <div class="card" style="width: 8rem;">
                                    <img class="card-img-top" src="{{ asset('pord.jpg') }}" alt="Card image cap">
                                    <div class="card-body">
                                        <p class="card-text" style="color:black">Todos</p>
                                    </div>
                                </div>
                            </li>
                            @foreach ($categorias as $cat)
                                <li data-filter=".filter-{{ $cat->id }}">
                                    <div class="card" style="width: 8rem;">
                                        @if ($cat->img_cat)
                                            <img class="card-img-top" src="{{ asset('storage/' . $cat->img_cat) }}"
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
                        <div class="col-lg-3 col-md-4 prod-filter-item filter-{{ $mod->categoria_id }}">
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
                                            <h6 class="prod-overlay-text">{{ $mod->descripcion }}</h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="prod-details">
                                    <div class="prod-title-container">
                                        <p class="prod-title">{{ $mod->descripcion }}</p>
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
            @foreach(\App\Models\BannerMedio::where('activo', true)->orderBy('orden')->get() as $banner)
            <div class="promo-banner-slide">
                <div class="promo-banner-content">
                    <a href="{{ $banner->url_destino }}" target="_blank">
                        <img src="{{ asset($banner->imagen_path) }}" alt="{{ $banner->titulo ?? 'Banner promocional' }}" class="img-fluid">
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
            @foreach(\App\Models\BannerMedio::where('activo', true)->orderBy('orden')->get() as $index => $banner)
            <button class="promo-banner-dot {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}"></button>
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

    <!-- SCRIPT DEL CARRUSEL DE BANNERS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionar elementos del carrusel
            const track = document.querySelector('.promo-banner-track');
            const slides = document.querySelectorAll('.promo-banner-slide');
            const prevBtn = document.querySelector('.promo-banner-prev');
            const nextBtn = document.querySelector('.promo-banner-next');
            const dotsContainer = document.querySelector('.promo-banner-dots');

            let currentIndex = 0;
            let slideInterval;
            const slideCount = slides.length;

            // Crear indicadores
            slides.forEach((_, index) => {
                const dot = document.createElement('div');
                dot.classList.add('promo-banner-dot');
                if (index === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(index));
                dotsContainer.appendChild(dot);
            });

            const dots = document.querySelectorAll('.promo-banner-dot');

            // Función para mover el carrusel
            function goToSlide(index) {
                currentIndex = (index + slideCount) % slideCount;
                track.style.transform = `translateX(-${currentIndex * 100}%)`;

                // Actualizar indicadores
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === currentIndex);
                });

                // Reiniciar el intervalo
                resetInterval();
            }

            // Navegación
            function nextSlide() {
                goToSlide(currentIndex + 1);
            }

            function prevSlide() {
                goToSlide(currentIndex - 1);
            }

            // Event listeners
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);

            // Auto-desplazamiento
            function startInterval() {
                slideInterval = setInterval(nextSlide, 5000);
            }

            function resetInterval() {
                clearInterval(slideInterval);
                startInterval();
            }

            // Iniciar el carrusel
            function initCarousel() {
                startInterval();

                // Pausar al hacer hover
                track.addEventListener('mouseenter', () => {
                    clearInterval(slideInterval);
                });

                track.addEventListener('mouseleave', startInterval);
            }

            initCarousel();

            // Touch events para móviles
            let touchStartX = 0;
            let touchEndX = 0;

            track.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                clearInterval(slideInterval);
            }, {
                passive: true
            });

            track.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
                startInterval();
            }, {
                passive: true
            });

            function handleSwipe() {
                const diff = touchStartX - touchEndX;
                if (diff > 50) nextSlide(); // Swipe izquierda
                if (diff < -50) prevSlide(); // Swipe derecha
            }
        });
    </script>
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
                }, { passive: true });

                track.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                    startAutoSlide();
                }, { passive: true });

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
    <!-- FIN DEL SCRIPT DEL CARRUSEL DE NOVEDADES -->
@endsection
<script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>

