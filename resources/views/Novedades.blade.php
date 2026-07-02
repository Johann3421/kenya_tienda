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
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0px 0px;
            border-radius: 6px;
            box-shadow: 0 0px 0px rgba(0,0,0,0.00);
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
            border: 0px solid #eaeaea;
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
        }

        /* Estilos de tarjeta actualizados según la imagen */
        .product-title {
            font-size: 1.05rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 700;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }

        .product-details {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 8px;
        }

        .product-details strong {
            font-weight: normal; 
        }

        .stock-green {
            color: #20c997; 
            font-weight: 700;
            font-size: 0.95rem;
        }

        .btn-details {
            background: linear-gradient(90deg, #ff6200, #ff7d00);
            color: #ffffff;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 30px; 
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.3s ease;
        }

        .btn-details:hover {
            background-color: #d66836; 
        }

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
            .hero-content h1 { font-size: 2rem; }
            .hero-content p { font-size: 1rem; }
            
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box {
                max-width: 100%;
            }
            .filter-dropdowns {
                flex-direction: column;
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
            <form method="GET" action="" style="display: flex; width: 100%; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
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

                    if (!empty($producto->imagen_1)) {
                        $img    = asset('storage/' . $producto->imagen_1);
                        $imgFb  = asset($producto->imagen_1);
                        $imgFb2 = $modelImg;
                    } elseif (!empty($producto->imagen)) {
                        $img    = asset('storage/' . $producto->imagen);
                        $imgFb  = $modelImg;
                        $imgFb2 = asset('producto.jpg');
                    } else {
                        $img    = $modelImg;
                        $imgFb  = asset('producto.jpg');
                        $imgFb2 = asset('producto.jpg');
                    }
                    $stock = $producto->stock ?? 100;
                @endphp
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ $img }}" alt="{{ $producto->display_name ?? 'Producto' }}" 
                            onerror="if(!this.dataset.fb){this.dataset.fb=1;this.src='{{ $imgFb }}';}else if(this.dataset.fb=='1'){this.dataset.fb=2;this.src='{{ $imgFb2 }}';}else{this.onerror=null;}">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $producto->display_name ?? 'Nombre no disponible' }}</h3>
                        <p class="product-details">N&deg; de parte: {{ $producto->nro_parte ?? 'N/A' }}</p>
                        <p class="product-details">Stock: 
                            @if($stock > 0)
                                <span class="stock-green">&ge; {{ $stock }} unidades</span>
                            @else
                                <span class="stock-red" style="color: #d9534f; font-weight: 500;">Agotado</span>
                            @endif
                        </p>
                        <button class="btn-details" onclick="window.location.href='{{ url('/producto/' . $producto->id . '/detalle') }}'">VER DETALLES</button>
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
