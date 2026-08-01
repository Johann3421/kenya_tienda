@extends('layouts.landing')

@section('title', 'Catálogo de Computadoras, PCs de Escritorio y Laptops en Perú | KENYA')
@section('meta_description', 'Explora nuestro catálogo general de computadoras de escritorio, PCs, laptops, monitores y tóner en Perú. Filtra por modelo, procesador, memoria RAM y almacenamiento. Garantía de 36 meses On-Site.')
@section('meta_keywords', 'computadoras, pcs, pcs de escritorio, catalogo de computadoras, laptops, equipos de computo, monitores, toner, kenya peru')
@section('canonical', route('catalogo'))

@section('og_title', 'Catálogo de Computadoras y Equipos Cómputo B2B | KENYA Technology')
@section('og_description', 'Encuentra las mejores opciones en computadoras de escritorio, laptops y suministros corporativos con garantía de 36 meses On-Site.')
@section('og_image', asset('theme/images/kenya.png'))
@section('og_url', route('catalogo'))
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li class="kenya-active"><a href="{{ route('catalogo') }}" class="kenya-nav-link">Cat&aacute;logo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</            {{-- Sorteo temporalmente oculto en producción --}}
            {{-- <li><a href="{{ route('serial.draw') }}" class="kenya-nav-link">🎁  Sorteo</a></li> --}}
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Cont&aacute;ctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <?php
    // Configuración inicial
    use App\Producto;
    use App\Modelo;
    use Illuminate\Support\Facades\DB;

    // Obtener parámetros de filtro
    $busqueda = request('busqueda');
    $modeloId = request('modelo');
    $orden = request('orden', 'newest');

    // Nuevos filtros por especificaciones técnicas
    $procesador = request('procesador');
    $memoria_ram = request('memoria_ram');
    $almacenamiento = request('almacenamiento');
    $sistema_operativo = request('sistema_operativo');
    $unidad_optica = request('unidad_optica');
    $conectividad_lan = request('conectividad_lan');
    $conectividad_wlan = request('conectividad_wlan');
    $conectividad_usb = request('conectividad_usb');
    $conectividad_vga = request('conectividad_vga');
    $conectividad_hdmi = request('conectividad_hdmi');
    $ofimatica = request('ofimatica');
    $perifericos = request('perifericos');
    $tarjeta_video = request('tarjeta_video');

    // Consulta base
    $productosQuery = Producto::query()
        ->where('pagina_web', 'SI')
        ->noSuspendido();

    // Aplicar filtro de búsqueda
    if ($busqueda) {
        $productosQuery->where('descripcion', 'LIKE', "%{$busqueda}%")->orWhere('nro_parte', 'LIKE', "%{$busqueda}%");
    }

    // Aplicar filtro por modelo
    if ($modeloId) {
        $productosQuery->where('modelo_id', $modeloId);
    }

    // Aplicar filtros por especificaciones técnicas usando la tabla especificaciones
    if ($procesador) {
        $productosQuery->whereHas('especificaciones', function($q) use ($procesador) {
            $q->where('campo', 'Procesador')->where('descripcion', $procesador);
        });
    }
    if ($memoria_ram) {
        $productosQuery->whereHas('especificaciones', function($q) use ($memoria_ram) {
            $q->where('campo', 'Memoria Ram')->where('descripcion', $memoria_ram);
        });
    }
    if ($almacenamiento) {
        $productosQuery->whereHas('especificaciones', function($q) use ($almacenamiento) {
            $q->where('campo', 'Almacenamiento')->where('descripcion', $almacenamiento);
        });
    }
    if ($sistema_operativo) {
        $productosQuery->whereHas('especificaciones', function($q) use ($sistema_operativo) {
            $q->where('campo', 'Sistema Operativo')->where('descripcion', $sistema_operativo);
        });
    }
    if ($unidad_optica) {
        $productosQuery->whereHas('especificaciones', function($q) use ($unidad_optica) {
            $q->whereIn('campo', ['Unidad Óptica', 'Unidad Ã“ptica'])->where('descripcion', $unidad_optica);
        });
    }
    if ($conectividad_lan) {
        $productosQuery->whereHas('especificaciones', function($q) use ($conectividad_lan) {
            $q->where('campo', 'Conectividad LAN')->where('descripcion', $conectividad_lan);
        });
    }
    if ($conectividad_wlan) {
        $productosQuery->whereHas('especificaciones', function($q) use ($conectividad_wlan) {
            $q->where('campo', 'Conectividad WLAN')->where('descripcion', $conectividad_wlan);
        });
    }
    if ($conectividad_usb) {
        $productosQuery->whereHas('especificaciones', function($q) use ($conectividad_usb) {
            $q->where('campo', 'Conectividad USB')->where('descripcion', $conectividad_usb);
        });
    }
    if ($conectividad_vga) {
        $productosQuery->whereHas('especificaciones', function($q) use ($conectividad_vga) {
            $q->where('campo', 'Conectividad VGA')->where('descripcion', $conectividad_vga);
        });
    }
    if ($conectividad_hdmi) {
        $productosQuery->whereHas('especificaciones', function($q) use ($conectividad_hdmi) {
            $q->where('campo', 'Conectividad HDMI')->where('descripcion', $conectividad_hdmi);
        });
    }
    if ($ofimatica) {
        $productosQuery->whereHas('especificaciones', function($q) use ($ofimatica) {
            $q->whereIn('campo', ['Ofimática', 'OfimÃ¡tica'])->where('descripcion', $ofimatica);
        });
    }
    if ($perifericos) {
        $productosQuery->whereHas('especificaciones', function($q) use ($perifericos) {
            $q->whereIn('campo', ['Periféricos', 'PerifÃ©ricos'])->where('descripcion', $perifericos);
        });
    }
    if ($tarjeta_video) {
        $productosQuery->where(function ($query) use ($tarjeta_video) {
            $query->whereHas('especificaciones', function ($q) use ($tarjeta_video) {
                $q->where(function ($specQ) {
                    $specQ->whereRaw("LOWER(TRIM(campo)) IN ('gráficos', 'graficos', 'tarjeta de video')")
                          ->orWhereRaw("LOWER(TRIM(campo)) LIKE '%gráf%'")
                          ->orWhereRaw("LOWER(TRIM(campo)) LIKE '%graf%'")
                          ->orWhereRaw("LOWER(TRIM(campo)) LIKE '%tarjeta%video%'");
                })->where('descripcion', $tarjeta_video);
            })->orWhere('tarjetavideo', $tarjeta_video);
        });
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
    $modelos = Modelo::whereRaw("UPPER(activo) = 'SI'")
        ->whereHas('getProducto', function ($q) {
            $q->where('pagina_web', 'SI')->noSuspendido();
        })
        ->orderBy('descripcion')
        ->get();

    // Obtener opciones para filtros de especificaciones desde la tabla especificaciones
    $procesadores = DB::table('especificaciones')->where('campo', 'Procesador')->distinct()->pluck('descripcion')->sort();
    $memorias_ram = DB::table('especificaciones')->where('campo', 'Memoria Ram')->distinct()->pluck('descripcion')->sort();
    $almacenamientos = DB::table('especificaciones')->where('campo', 'Almacenamiento')->distinct()->pluck('descripcion')->sort();
    $sistemas_operativos = DB::table('especificaciones')->where('campo', 'Sistema Operativo')->distinct()->pluck('descripcion')->sort();
    $unidades_opticas = DB::table('especificaciones')->whereIn('campo', ['Unidad Óptica', 'Unidad Ã“ptica'])->distinct()->pluck('descripcion')->sort();
    $conectividades_wlan = DB::table('especificaciones')->where('campo', 'Conectividad WLAN')->distinct()->pluck('descripcion')->sort();
    $conectividades_vga = DB::table('especificaciones')->where('campo', 'Conectividad VGA')->distinct()->pluck('descripcion')->sort();
    $conectividades_hdmi = DB::table('especificaciones')->where('campo', 'Conectividad HDMI')->distinct()->pluck('descripcion')->sort();
    $ofimaticas = DB::table('especificaciones')->whereIn('campo', ['Ofimática', 'OfimÃ¡tica'])->distinct()->pluck('descripcion')->sort();
    $perifericos_list = DB::table('especificaciones')->whereIn('campo', ['Periféricos', 'PerifÃ©ricos'])->distinct()->pluck('descripcion')->sort();
    $tarjetas_video_specs = DB::table('especificaciones')
        ->join('productos', 'especificaciones.producto_id', '=', 'productos.id')
        ->whereRaw("UPPER(productos.pagina_web) = 'SI'")
        ->where(function ($q) {
            $q->whereNull('productos.vigencia')
              ->orWhereNotIn('productos.vigencia', ['SUSPENDIDA', 'INACTIVA', 'ANULADA']);
        })
        ->where(function ($q) {
            $q->whereRaw("LOWER(TRIM(especificaciones.campo)) IN ('gráficos', 'graficos', 'tarjeta de video')")
              ->orWhereRaw("LOWER(TRIM(especificaciones.campo)) LIKE '%gráf%'")
              ->orWhereRaw("LOWER(TRIM(especificaciones.campo)) LIKE '%graf%'")
              ->orWhereRaw("LOWER(TRIM(especificaciones.campo)) LIKE '%tarjeta%video%'");
        })nes.campo)) LIKE '%tarjeta%video%'");
        })
        ->whereNotNull('especificaciones.descripcion')
        ->whereRaw("TRIM(especificaciones.descripcion) != ''")
        ->whereRaw("LOWER(TRIM(especificaciones.descripcion)) NOT IN ('no', 'n/a', 'no aplica')")
        ->distinct()
        ->orderBy('especificaciones.descripcion')
        ->pluck('especificaciones.descripcion');

    $tarjetas_video_columna = Producto::query()
        ->whereRaw("UPPER(pagina_web) = 'SI'")
        ->noSuspendido()
        ->whereNotNull('tarjetavideo')
        ->whereRaw("TRIM(tarjetavideo) != ''")
        ->pluck('tarjetavideo');

    $tarjetas_video = $tarjetas_video_specs
        ->merge($tarjetas_video_columna)
        ->map(fn($v) => trim((string) $v))
        ->filter(fn($v) => $v !== '' && !in_array(mb_strtolower($v), ['no', 'n/a', 'no aplica'], true))
        ->unique()
        ->sort()
        ->values();

    // Paginar resultados (con eager loading si es necesario)
    $productos = $productosQuery->with('modelo')->paginate(8);

    // Obtener Novedades para el componente de abajo
    $novedades = Producto::with('modelo')
        ->orderBy('created_at', 'DESC')
        ->where('pagina_web', 'SI')
        ->noSuspendido()
        ->whereNull('precio_anterior')
        ->take(16)
        ->get();
    ?>

    <style>
        .catalog-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            border: 1px solid #eaeaea;
        }

        .catalog-main-search {
            position: relative;
            flex-grow: 1;
            max-width: 500px;
        }

        .catalog-main-search input {
            width: 100%;
            padding: 10px 40px 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }

        .catalog-main-search input:focus {
            border-color: #f26522;
        }

        .catalog-main-search i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
        }

        .catalog-sort {
            margin-left: 20px;
        }

        .custom-sort-select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 14px;
            color: #444;
            outline: none;
            background: #fafafa;
            cursor: pointer;
            transition: border-color 0.3s;
            min-width: 210px;
        }

        .custom-sort-select:focus {
            border-color: #f26522;
        }

        @media (max-width: 600px) {
            .catalog-top-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }
            .catalog-sort {
                margin-left: 0;
            }
            .custom-sort-select {
                min-width: 100%;
                width: 100%;
            }
        }

        .page-banner {
            position: relative;
            width: 100%;
            height: 284px; 
            background-color: #333;
            background-image: url('{{ asset("banercatalogo.png?v=2") }}'); 
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0;
        }

        .page-banner::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.35); 
            z-index: 1;
        }

        .page-banner .banner-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 0 20px;
        }

        .page-banner h1 {
            color: #000000;
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
            text-shadow: 0px 0px 0px rgba(0,0,0,0.0);
        }

        @media (max-width: 768px) {
            .page-banner { height: 100px; }
            .page-banner h1 { font-size: 1.5rem; }
        }

        .catalog-section {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 20px;
            padding: 40px 0;
            align-items: start;
        }

        .catalog-sidebar {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            position: static;
        }

        .filter-group { margin-bottom: 25px; }
        .filter-group label { display: block; font-size: 0.9rem; color: #555; margin-bottom: 8px; font-weight: 500; }
        .custom-select {
            width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px;
            font-size: 0.9rem; color: #333; outline: none; appearance: none;
            background: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="10" viewBox="0 0 14 10"><path fill="%23333" d="M7 10L0 0h14z"/></svg>') no-repeat right 15px center;
            background-size: 10px; background-color: #fff; cursor: pointer; transition: border-color 0.3s;
        }
        .custom-select:focus { border-color: #f26522; }

        .filter-title {
            font-size: 1.1rem; color: #333; padding-bottom: 10px; margin-bottom: 15px;
            border-bottom: 3px solid #f26522; font-weight: 600;
        }

        /* --- Contenido Principal (Productos) --- */
        .catalog-main { display: flex; flex-direction: column; gap: 20px; }
        
        .search-box { position: relative; }
        .search-input {
            width: 100%; padding: 15px 20px; border: 1px solid #ddd; border-radius: 8px;
            font-size: 1rem; outline: none; transition: border-color 0.3s, box-shadow 0.3s;
        }
        .search-input:focus { border-color: #f26522; box-shadow: 0 0 8px rgba(242, 101, 34, 0.15); }
        .search-btn {
            position: absolute; right: 15px; top: 50%; transform: translateY(-50%);
            background: none; border: none; font-size: 1.2rem; color: #777; cursor: pointer;
        }
        .search-btn:hover { color: #f26522; }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(253px, 1fr));
            gap: 12px;
        }

        .product-card {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #eaeaea;
            padding: 15px 15px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-color: #ddd;
        }

        .product-image-wrapper {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-image-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .product-logos {
            position: absolute;
            width: 100%;
            display: flex;
            justify-content: space-between;
            top: 50%;
            transform: translateY(-50%);
            padding: 0 10px;
            pointer-events: none;
        }

        .product-logos img {
            height: 25px;
            opacity: 0.8;
        }

        .product-title {
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.4;
            font-weight: 700;
        }

        .product-pn {
            font-size: 0.85rem;
            color: #777;
            margin-bottom: 0px;
        }

        .product-stock {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 20px;
            flex-grow: 1; 
        }

        .product-stock span {
            color: #2ecca6; 
            font-weight: 700;
        }

        .btn-details {
            width: 100%;
            background-color: #f07b3f; 
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 100px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            transition: background-color 0.3s, transform 0.1s;
        }

        .btn-details:hover {
            background-color: #d96225;
        }
        
        .btn-details:active {
            transform: scale(0.98);
        }

        /* ==========================================
           PAGINACIÓN
           ========================================== */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
            margin-bottom: 0px;
            flex-wrap: wrap;
        }

        .pagination {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .page-item {
            display: flex;
            justify-content: center;
            align-items: center;
            min-width: 40px;
            height: 40px;
            padding: 0 10px;
            background-color: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 6px;
            color: #777;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .page-item:hover:not(.dots) {
            border-color: #f26522;
            color: #f26522;
        }

        .page-item.active {
            background-color: #f07b3f;
            color: #ffffff;
            border-color: #f07b3f;
            font-weight: 600;
        }

        .page-item.dots {
            cursor: default;
        }

        .products-count {
            color: #777;
            font-size: 1rem;
            font-weight: 500;
        }

        @media (max-width: 992px) {
            .catalog-section { grid-template-columns: 1fr; }
            .catalog-sidebar { margin-bottom: 20px; }
            .catalog-sidebar #filtros-form { 
                display: grid; 
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); 
                gap: 15px; 
                align-items: end;
            }
            .catalog-sidebar .filter-title { grid-column: 1 / -1; margin-bottom: 5px; padding-bottom: 5px; }
            .catalog-sidebar .filter-group { margin-bottom: 0; }
        }

        @media (max-width: 576px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 10px;
            }
            .product-card {
                padding: 10px;
            }
            .product-title {
                font-size: 0.9rem;
            }
        }
    </style>

    <div class="page-banner">
        <div class="banner-content">
            <h1>Catálogo Electrónico de Acuerdo Marco</h1>
        </div>
    </div>

    <main class="container catalog-section">
        
        <aside class="catalog-sidebar">
            @include('partials.aside-catalogo', [
                'procesadores' => $procesadores,
                'memorias_ram' => $memorias_ram,
                'almacenamientos' => $almacenamientos,
                'sistemas_operativos' => $sistemas_operativos,
                'unidades_opticas' => $unidades_opticas,
                'conectividades_wlan' => $conectividades_wlan,
                'conectividades_vga' => $conectividades_vga,
                'conectividades_hdmi' => $conectividades_hdmi,
                'ofimaticas' => $ofimaticas,
                'perifericos_list' => $perifericos_list,
                'tarjetas_video' => $tarjetas_video
            ])
        </aside>

        <div class="catalog-main">
            <!-- Barra Superior de Catálogo -->
            <form method="GET" action="" id="catalog-form">
                <div class="catalog-top-bar">
                    <div class="catalog-main-search">
                        <input type="text" name="busqueda" placeholder="Buscar productos por nombre o parte..." value="{{ request('busqueda') }}">
                        <button type="submit" style="background:none;border:none;padding:0;">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    
                    <div class="catalog-sort">
                        <select name="orden" class="custom-sort-select" onchange="document.getElementById('catalog-form').submit()">
                            <option value="newest" {{ $orden == 'newest' ? 'selected' : '' }}>Más recientes</option>
                            <option value="oldest" {{ $orden == 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                            <option value="nombre_asc" {{ $orden == 'nombre_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                            <option value="nombre_desc" {{ $orden == 'nombre_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                        </select>
                    </div>

                    <!-- Filtro oculto de modelo para mantener el estado -->
                    @if(request('modelo'))
                        <input type="hidden" name="modelo" value="{{ request('modelo') }}">
                    @endif
                </div>
            </form>

            <!-- Listado de productos -->
            <div class="row">
                <div class="col-md-12">
                              @include('partials.catalogo-products', ['productos' => $productos])
        </div>
    </main>

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

    @include('components.novedades', ['novedades' => $novedades])

@endsection



