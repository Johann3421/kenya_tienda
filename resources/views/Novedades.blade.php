@extends('layouts.landing')

@section('title', 'Novedades')
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li class="kenya-active"><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <?php
    // Configuración inicial
    use App\Producto;
    use App\Modelo;

    // Obtener parámetros de filtro
    $busqueda = request('busqueda');
    $modeloId = request('modelo');
    $orden = request('orden', 'newest');

    // Consulta base
    $productosQuery = Producto::query();

    // Aplicar filtro de búsqueda
    if ($busqueda) {
        $productosQuery->where('descripcion', 'LIKE', "%{$busqueda}%")->orWhere('nro_parte', 'LIKE', "%{$busqueda}%");
    }

    // Aplicar filtro por modelo
    if ($modeloId) {
        $productosQuery->where('modelo_id', $modeloId);
    }

    // Aplicar ordenación
    switch ($orden) {
        case 'nombre_asc':
            $productosQuery->orderBy('descripcion', 'asc');
            break;
        case 'nombre_desc':
            $productosQuery->orderBy('descripcion', 'desc');
            break;
        case 'oldest':
            $productosQuery->orderBy('created_at', 'asc');
            break;
        case 'newest':
        default:
            $productosQuery->orderBy('created_at', 'desc');
    }

    // Obtener modelos activos para el dropdown
    $modelos = Modelo::where('activo', 'Si')->orderBy('descripcion')->get();

    // Paginar resultados (con eager loading si es necesario)
    $productos = $productosQuery->with('modelo')->paginate(9);
    ?>

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

    <section class="catalog-section">
        <div class="container">
            <!-- Hero Banner -->
            <div class="catalog-hero">
                <h1>Nuestras Novedades de Productos</h1>
                <p>Descubre nuestra amplia gama de productos de alta calidad</p>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="catalog-filters">
                <div class="row">
                    <div class="col-md-6">
                        <form method="GET" action="">
                            <div class="search-box">
                                <input type="text" name="busqueda" placeholder="Buscar productos..." class="search-input"
                                    value="{{ request('busqueda') }}">
                                <button type="submit" class="search-btn">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="" id="filters-form">
                            <input type="hidden" name="busqueda" value="{{ request('busqueda') }}">
                            <div class="filter-controls">
                                <!-- Filtro de modelos CORREGIDO -->
                                <select name="modelo" class="category-filter"
                                    onchange="document.getElementById('filters-form').submit()">
                                    <option value="">Todos los modelos</option>
                                    @foreach ($modelos as $modelo)
                                        <option value="{{ $modelo->id }}"
                                            {{ request('modelo') == $modelo->id ? 'selected' : '' }}>
                                            {{ $modelo->descripcion }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Filtro de ordenación MEJORADO -->
                                <select name="orden" class="sort-filter"
                                    onchange="document.getElementById('filters-form').submit()">
                                    <option value="newest" {{ $orden == 'newest' ? 'selected' : '' }}>Más recientes
                                    </option>
                                    <option value="oldest" {{ $orden == 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                                    <option value="nombre_asc" {{ $orden == 'nombre_asc' ? 'selected' : '' }}>Nombre (A-Z)
                                    </option>
                                    <option value="nombre_desc" {{ $orden == 'nombre_desc' ? 'selected' : '' }}>Nombre
                                        (Z-A)</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="product-grid">
                <div class="row">
                    @forelse($productos as $producto)
                        <div class="col-lg-4 col-md-4 col-sm-6 mb-4">
                            <div class="product-card">
                                @if (($producto->stock ?? 100) <= 0)
                                    <!-- Stock por defecto 100 -->
                                    <div class="product-badge out-of-stock">Agotado</div>
                                @elseif(isset($producto->created_at) && \Carbon\Carbon::parse($producto->created_at)->diffInDays(now()) <= 30)
                                    <div class="product-badge">Nuevo</div>
                                @endif

                                <div class="product-image">
                                    @php
                                        // Verificar si es modelo tonner (ID 10 o descripción contiene 'tonner')
                                        $isTonner = $producto->modelo && (
                                            $producto->modelo->id == 10 ||
                                            (isset($producto->modelo->descripcion) && stripos($producto->modelo->descripcion, 'tonner') !== false)
                                        );

                                        // Lógica de imágenes
                                        $img = $isTonner
                                            ? ($producto->imagen_1 ? asset('storage/'.$producto->imagen_1) : asset('images/products/default.jpg'))
                                            : ($producto->modelo && $producto->modelo->img_mod
                                                ? asset('storage/'.$producto->modelo->img_mod)
                                                : asset('images/products/default.jpg'));
                                    @endphp

                                    <img src="{{ $img }}" alt="{{ $producto->nombre ?? 'Producto' }}" class="img-fluid">

                                    <div class="product-actions">
                                        <button class="quick-view" data-id="{{ $producto->id }}">
                                            <i class="bx bx-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="product-info">
                                    <!-- CATEGORÍA (nombre del modelo) -->
                                    <span class="product-category">
                                        {{ $producto->modelo->nombre ?? ($producto->modelo->descripcion ?? 'Sin categoría') }}
                                    </span>

                                    <!-- NOMBRE DEL PRODUCTO -->
                                    <h3 class="product-title">{{ $producto->nombre ?? 'Nombre no disponible' }}</h3>

                                    <div class="product-details">
                                        <p><strong>Parte:</strong> {{ $producto->nro_parte ?? 'N/A' }}</p>
                                        <p><strong>Stock:</strong>
                                            @php
                                                $stock = $producto->stock ?? 100; // Valor por defecto 100
                                            @endphp
                                            @if ($stock > 0)
                                                <span class="in-stock">{{ $stock }} unidades</span>
                                            @else
                                                <span class="out-of-stock">No disponible</span>
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Botón con nueva ruta de detalles -->
                                    <button class="view-details"
    onclick="window.location.href='{{ url('producto/'.$producto->id.'/detalle') }}'">
    Ver detalles
</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning">No se encontraron productos.</div>
                        </div>
                    @endforelse
                </div>
            </div>


            @if ($productos->hasPages())
                <div class="catalog-pagination mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{-- Enlace Anterior --}}
                            <li class="page-item {{ $productos->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $productos->previousPageUrl() }}" aria-label="Anterior">
                                    &laquo;
                                </a>
                            </li>

                            {{-- Mostrar primera página --}}
                            @if ($productos->currentPage() > 3)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $productos->url(1) }}">1</a>
                                </li>
                                @if ($productos->currentPage() > 4)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                            @endif

                            {{-- Rango de páginas alrededor de la actual --}}
                            @foreach (range(max(1, $productos->currentPage() - 2), min($productos->lastPage(), $productos->currentPage() + 2)) as $page)
                                <li class="page-item {{ $productos->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $productos->url($page) }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Mostrar última página --}}
                            @if ($productos->currentPage() < $productos->lastPage() - 2)
                                @if ($productos->currentPage() < $productos->lastPage() - 3)
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $productos->url($productos->lastPage()) }}">{{ $productos->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Enlace Siguiente --}}
                            <li class="page-item {{ !$productos->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $productos->nextPageUrl() }}" aria-label="Siguiente">
                                    &raquo;
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </section>
    <style>
        :root {
            --primary-color: #ee7c31;
            --secondary-color: #ca7b46;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --border-radius: 8px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Estilos generales */
        .catalog-section {
            padding: 2rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Hero Banner */
        .catalog-hero {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .catalog-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .catalog-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Filtros */
        .catalog-filters {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }

        .search-box {
            position: relative;
            display: flex;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .search-btn {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 50px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-btn:hover {
            background: var(--secondary-color);
        }

        .filter-controls {
            display: flex;
            gap: 1rem;
        }

        .category-filter, .sort-filter {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            background-color: white;
            cursor: pointer;
            transition: var(--transition);
        }

        .category-filter:focus, .sort-filter:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        /* Product Grid */
        .product-grid {
            margin-top: 2rem;
        }

        .product-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            z-index: 2;
        }

        .product-badge.out-of-stock {
            background-color: var(--accent-color);
        }

        .product-badge {
            background-color: var(--success-color);
        }

        .product-image {
            position: relative;
            overflow: hidden;
            padding-top: 75%; /* 4:3 Aspect Ratio */
        }

        .product-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-actions {
            position: absolute;
            bottom: 10px;
            right: 10px;
            display: flex;
            gap: 0.5rem;
            z-index: 2;
        }

        .quick-view {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .quick-view:hover {
            background-color: white;
            color: var(--primary-color);
            transform: scale(1.1);
        }

        .product-info {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            font-size: 0.9rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
            font-weight: 700;
            line-height: 1.3;
        }

        .product-details {
            margin-bottom: 1.5rem;
        }

        .product-details p {
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            color: #555;
        }

        .product-details strong {
            color: var(--dark-color);
        }

        .in-stock {
            color: var(--success-color);
            font-weight: 600;
        }

        .out-of-stock {
            color: var(--accent-color);
            font-weight: 600;
        }

        .view-details {
            margin-top: auto;
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .view-details:hover {
            background-color: var(--secondary-color);
        }

        /* Paginación */
        .catalog-pagination {
            margin-top: 3rem;
        }

        .pagination {
            display: flex;
            gap: 0.5rem;
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            pointer-events: none;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .page-link {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            color: var(--dark-color);
            transition: var(--transition);
        }

        .page-link:hover {
            background-color: #f8f9fa;
            border-color: #ddd;
        }

        /* Alertas */
        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            text-align: center;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-controls {
                flex-direction: column;
                gap: 0.75rem;
            }

            .catalog-hero h1 {
                font-size: 2rem;
            }

            .product-title {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .catalog-filters .row > div {
                margin-bottom: 1rem;
            }

            .product-card {
                max-width: 320px;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
    <!-- Scripts para funcionalidad -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filtrado de productos
            const categoryFilter = document.querySelector('.category-filter');
            const sortFilter = document.querySelector('.sort-filter');
            const searchInput = document.querySelector('.search-input');

            if (categoryFilter && sortFilter && searchInput) {
                [categoryFilter, sortFilter, searchInput].forEach(element => {
                    element.addEventListener('change', filterProducts);
                });

                searchInput.addEventListener('keyup', filterProducts);
            }

            function filterProducts() {
                // Aquí iría la lógica para filtrar/ordenar los productos
                console.log('Filtrando productos...');
            }

            // Quick view
            const quickViewButtons = document.querySelectorAll('.quick-view');
            quickViewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Lógica para mostrar vista rápida del producto
                    console.log('Mostrando vista rápida');
                });
            });

            // Wishlist
            const wishlistButtons = document.querySelectorAll('.add-wishlist');
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Lógica para añadir a wishlist
                    console.log('Añadiendo a wishlist');
                });
            });
        });
    </script>

    <style>
        /* Estilos para el carrusel de banners */
        .promo-banner-section {
            width: 100%;
            position: relative;
            overflow: hidden;
            margin-top: 3rem;
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
        </style>
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
@endsection

