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
    use App\Producto;
    use App\Modelo;

    $busqueda = request('busqueda');
    $modeloId = request('modelo');
    $orden = request('orden', 'newest');

    $productosQuery = Producto::query()
        ->where('pagina_web', 'SI')
        ->noSuspendido();

    if ($busqueda) {
        $productosQuery->where('descripcion', 'LIKE', "%{$busqueda}%")->orWhere('nro_parte', 'LIKE', "%{$busqueda}%");
    }

    if ($modeloId) {
        $productosQuery->where('modelo_id', $modeloId);
    }

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

    $modelos = Modelo::whereRaw("UPPER(activo) = 'SI'")
        ->whereHas('getProducto', function ($q) {
            $q->where('pagina_web', 'SI')->noSuspendido();
        })
        ->orderBy('descripcion')
        ->get();

    $productos = $productosQuery->with('modelo')->paginate(12);
    ?>

    <style>
.hero-banner {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('{{ asset("banernovedades.png?v=2") }}');
            background-size: cover;
            background-position: right;
            color: #000000;
            text-align: left;
            padding: 80px 5%;
            margin-bottom: 0px;
        }

        .hero-content {
            position: relative;
            z-index: 2; 
            padding-left: 0px; 
            display: flex;
            flex-direction: column;
            align-items: flex-start; 
        }

        .hero-content h1 {
            font-size: 2.8rem;
            font-weight: 400;
            margin-bottom: -3px;
        }

        .hero-content p {
            font-size: 1.3rem;
            font-weight: 300;
            margin-bottom: 20px; 
        }

        /* ==========================================
           SECCIÓN DE PRODUCTOS Y FILTROS
           ========================================== */
        .products-section {
            padding: 40px 0;
        }

        /* Barra de Filtros */
        .filter-bar {
            padding: 0px 0px;
            border-radius: 6px;
            box-shadow: 0 0px 0px rgba(0,0,0,0.00);
            margin-bottom: 30px;
            border: 0px solid #eaeaea;
        }

        .filter-form {
            display: flex;
            width: 100%;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .search-box {
            display: flex;
            flex: 1;
            max-width: 500px;
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #eaeaea;
            border-radius: 4px 0 0 4px;
            outline: none;
            font-size: 0.95rem;
        }

        .search-box button {
            background: linear-gradient(90deg, #ff6200, #ff7d00);
            color: white;
            border: none;
            padding: 0 25px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-box button:hover {
            background-color: #d9561b;
        }

        .filter-dropdowns {
            display: flex;
            gap: 15px;
        }

        .filter-dropdowns select {
            padding: 12px 15px;
            border: 1px solid #eaeaea;
            border-radius: 10px;
            outline: none;
            background-color: #fff;
            min-width: 200px;
            font-size: 0.95rem;
            color: #333;
            cursor: pointer;
        }

        /* Cuadrícula de Productos */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        /* Tarjeta de Producto */
        .product-card {
            background-color: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 12px;
            padding: 25px;
            position: relative;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transform: translateY(-3px);
        }

        .product-image {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .product-image img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }        /* Estilos de tarjeta actualizados */
        .product-specs-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 12px;
        }

        .spec-chip {
            background-color: #f8f9fa;
            border: 1px solid #eaeaea;
            color: #555;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
        }

        .product-title {
            font-size: 0.95rem;
            color: #222;
            margin-bottom: 4px;
            line-height: 1.4;
            font-weight: 600;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-sku {
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 12px;
        }

        .product-card-footer {
            margin-top: auto;
            border-top: 1px solid #f1f1f1;
            padding-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .product-stock-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stock-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .stock-status-dot.available {
            background: #2ecca6;
        }

        .stock-status-dot.out-of-stock {
            background: #dc3545;
        }

        .stock-text {
            font-size: 0.85rem;
            color: #444;
            font-weight: 600;
        }

        .btn-details.pill {
            background: linear-gradient(90deg, #ff6200, #ff7d00);
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 50px; 
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            margin-top: auto;
            transition: background 0.3s ease;
        }

        .btn-details.pill:hover {
            background: #d66836; 
        }    }

        /* ==========================================
           FOOTER 
           ========================================== */
        .site-footer {
            background-color: #222; 
            color: #ccc; 
            font-size: 0.9rem;
            border-top: 0px solid #f26522; 
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            padding: 4rem 0% 2rem;
        }
        .footer-col h4 {
            color: #fff;
            font-size: 1.05rem;
            margin-bottom: 1.2rem;
            position: relative;
            padding-bottom: 0.8rem;
        }
        .footer-col h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 1px;
            background-color: #f26522;
        }
        .footer-col ul { list-style: none; padding: 0; }
        .footer-col ul li { margin-bottom: 0.8rem; }
        .footer-col ul li a { color: #aaa; text-decoration: none; transition: color 0.3s ease; }
        .footer-col ul li a:hover { color: #f26522; }
        
        .contact-info li { display: flex; align-items: flex-start; gap: 10px; color: #fff; }
        .contact-info i { color: #f26522; margin-top: 4px; }

        .footer-bottom-wrapper {
            border-top: 1px solid #333;
        }
        .footer-bottom {
            padding: 1.5rem 0%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .footer-extras { display: flex; align-items: center; gap: 20px; }
        .libro-reclamaciones { color: #aaa; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: color 0.3s; }
        .libro-reclamaciones:hover { color: #fff; }
        .social-icons { display: flex; gap: 10px; align-items: center; }
        .social-icons span { color: #aaa; margin-right: 5px; }
        .social-icons a {
            display: flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; background-color: #444; color: #fff;
            border-radius: 50%; text-decoration: none; transition: background 0.3s;
        }
        .social-icons a:hover { background-color: #f26522; }

        /* ==========================================
           PAGINACIÓN
           ========================================== */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 8px;
        }

        .pagination li a, 
        .pagination li span {
            display: flex;
            justify-content: center;
            align-items: center;
            min-width: 42px;
            height: 42px;
            padding: 0 12px;
            border: 1px solid #eaeaea;
            border-radius: 6px;
            background-color: #ffffff;
            color: #555;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .pagination li a:hover {
            border-color: #ef7b45;
            color: #ef7b45;
        }

        .pagination li a.active {
            background-color: #ef7b45;
            color: #ffffff;
            border-color: #ef7b45;
            font-weight: 600;
        }

        .pagination-info {
            color: #666;
            font-size: 0.95rem;
        }

        /* ==========================================
           RESPONSIVIDAD
           ========================================== */
        @media (max-width: 1300px) {
            .header-nav ul { gap: 20px; }
            .header-nav ul li a { font-size: 0.8rem; }
            .header-search { margin: 0 20px; }
        }

        @media (max-width: 992px) {
            .header-content { 
                flex-wrap: wrap; 
                height: auto; 
                padding-top: 15px; 
                padding-bottom: 15px; 
                gap: 15px;
            }
            .header-left { flex: none; width: 100%; justify-content: center; }
            .header-search { flex: none; width: 100%; justify-content: center; order: 2; max-width: 100%; margin: 0; }
            .header-search-wrapper { max-width: 100%; }
            .header-nav { flex: none; width: 100%; order: 3; justify-content: center; }
            .header-nav ul { flex-wrap: wrap; justify-content: center; gap: 15px;}
        }

        @media (max-width: 768px) {
            .hero-banner { padding: 40px 5%; } 
            .hero-content h1 { font-size: 1.8rem; line-height: 1.2; }
            .hero-content p { font-size: 1rem; }
            
            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box {
                max-width: 100%;
            }
            .filter-dropdowns {
                flex-direction: column;
            }
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    
    </style>

    <section class="hero-banner">
        <div class="container hero-content">
            <h1>Nuestras Novedades</h1>
            <p>Descubre nuestra amplia gama de equipos de alta calidad</p>
        </div>
    </section>

    <section class="products-section container">
        <div class="filter-bar">
            <form method="GET" action="" class="filter-form">
                <div class="search-box">
                    <input type="text" name="busqueda" placeholder="Buscar productos..." value="{{ request('busqueda') }}">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="filter-dropdowns">
                    <select name="modelo" onchange="this.form.submit()">
                        <option value="">Todos los modelos</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->id }}" {{ request('modelo') == $modelo->id ? 'selected' : '' }}>
                                {{ $modelo->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    <select name="orden" onchange="this.form.submit()">
                        <option value="newest" {{ $orden == 'newest' ? 'selected' : '' }}>Más recientes</option>
                        <option value="oldest" {{ $orden == 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                        <option value="nombre_asc" {{ $orden == 'nombre_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                        <option value="nombre_desc" {{ $orden == 'nombre_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="product-grid">
            @forelse($productos as $producto)
                @php
                    $modelImg = asset('producto.jpg');
                    if ($producto->modelo && !empty($producto->modelo->img_mod)) {
                        $modelImg = asset('storage/' . $producto->modelo->img_mod);
                    } elseif ($producto->getCategoria && !empty($producto->getCategoria->img_url)) {
                        $modelImg = $producto->getCategoria->img_url;
                    }

                    $img = $modelImg;
                    if (!empty($producto->imagen_1)) {
                        if (file_exists(public_path('storage/' . $producto->imagen_1))) {
                            $img = asset('storage/' . $producto->imagen_1);
                        } elseif (file_exists(public_path($producto->imagen_1))) {
                            $img = asset($producto->imagen_1);
                        }
                    } 
                    if ($img === $modelImg && !empty($producto->imagen)) {
                        if (file_exists(public_path('storage/' . $producto->imagen))) {
                            $img = asset('storage/' . $producto->imagen);
                        } elseif (file_exists(public_path($producto->imagen))) {
                            $img = asset($producto->imagen);
                        }
                    }
                    $imgFb  = $modelImg;
                    $imgFb2 = asset('producto.jpg');
                    $stock = $producto->stock ?? 100;
                @endphp
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ $img }}" alt="{{ $producto->display_name ?? 'Producto' }}" 
                            onerror="if(!this.dataset.fb){this.dataset.fb=1;this.src='{{ $imgFb }}';}else if(this.dataset.fb=='1'){this.dataset.fb=2;this.src='{{ $imgFb2 }}';}else{this.onerror=null;}">
                    </div>
                    <div class="product-info">
                        @php
                            $rawName = $producto->display_name ?? $producto->nombre ?? 'Nombre no disponible';
                            $cleanName = preg_replace('/\s*\([A-Z0-9\-\.]+\)\s*$/i', '', $rawName);
                            
                            $specs = [];
                            if (!empty($producto->procesador)) $specs[] = trim($producto->procesador);
                            if (!empty($producto->ram)) $specs[] = trim($producto->ram);
                            if (!empty($producto->almacenamiento)) $specs[] = trim($producto->almacenamiento);
                            if (!empty($producto->sistema_operativo)) $specs[] = trim($producto->sistema_operativo);
                            if (!empty($producto->tarjetavideo)) $specs[] = trim($producto->tarjetavideo);
                        @endphp

                        <h3 class="product-title" title="{{ trim($cleanName) }}">{{ trim($cleanName) }}</h3>
                        <div class="product-sku" style="background-color: #f0f4f8; padding: 4px 8px; border-radius: 4px; display: inline-block; font-weight: 600; color: #0056b3; margin-bottom: 12px; font-size: 0.75rem; width: fit-content;">SKU: {{ $producto->nro_parte ?? 'N/A' }}</div>

                        @if(count($specs) > 0)
                            <div class="product-specs-chips">
                                @foreach($specs as $spec)
                                    <span class="spec-chip">{{ $spec }}</span>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="product-card-footer">
                            <div class="product-price-placeholder" style="display:none;"></div>
                            
                            <div class="product-stock-wrapper">
                                @if($stock > 0)
                                    <span class="stock-status-dot available"></span>
                                    <span class="stock-text">Disponible (≥ {{ $stock }})</span>
                                @else
                                    <span class="stock-status-dot out-of-stock"></span>
                                    <span class="stock-text" style="color: #dc3545;">Agotado</span>
                                @endif
                            </div>
                            <button class="btn-details pill" onclick="window.location.href='{{ url('/producto/' . $producto->id . '/detalle') }}'">Más información</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12" style="grid-column: 1 / -1;">
                    <div class="alert alert-warning" style="padding: 20px; background-color: #fff3cd; color: #856404; border-radius: 8px;">No se encontraron productos.</div>
                </div>
            @endforelse
        </div>

        @if ($productos->hasPages())
            <div class="pagination-container" style="margin-top: 40px; display: flex; justify-content: center;">
                {{ $productos->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
