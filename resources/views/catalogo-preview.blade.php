@extends('layouts.landing')

@section('title', 'Catálogo Preview')
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li class="kenya-active"><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            {{-- Sorteo temporalmente oculto en producción --}}
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <?php
    // Copiado y adaptado desde Catalogo.blade.php
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
            $q->where('campo', 'Unidad Óptica')->where('descripcion', $unidad_optica);
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
            $q->where('campo', 'Ofimática')->where('descripcion', $ofimatica);
        });
    }
    if ($perifericos) {
        $productosQuery->whereHas('especificaciones', function($q) use ($perifericos) {
            $q->where('campo', 'Periféricos')->where('descripcion', $perifericos);
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
    $unidades_opticas = DB::table('especificaciones')->where('campo', 'Unidad Óptica')->distinct()->pluck('descripcion')->sort();
    $conectividades_lan = DB::table('especificaciones')->where('campo', 'Conectividad LAN')->distinct()->pluck('descripcion')->sort();
    $conectividades_wlan = DB::table('especificaciones')->where('campo', 'Conectividad WLAN')->distinct()->pluck('descripcion')->sort();
    $conectividades_usb = DB::table('especificaciones')->where('campo', 'Conectividad USB')->distinct()->pluck('descripcion')->sort();
    $conectividades_vga = DB::table('especificaciones')->where('campo', 'Conectividad VGA')->distinct()->pluck('descripcion')->sort();
    $conectividades_hdmi = DB::table('especificaciones')->where('campo', 'Conectividad HDMI')->distinct()->pluck('descripcion')->sort();
    $ofimaticas = DB::table('especificaciones')->where('campo', 'Ofimática')->distinct()->pluck('descripcion')->sort();
    $perifericos_list = DB::table('especificaciones')->where('campo', 'Periféricos')->distinct()->pluck('descripcion')->sort();
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
    $productos = $productosQuery->with('modelo')->paginate(9);
    ?>

    <section class="catalog-section">
        <div class="container">
            <!-- Hero Banner -->
            <div class="catalog-hero">
                <h1>Nuestro Catálogo de Productos (Preview)</h1>
                <p>Vista unificada - filtros de detallemod + cards del catálogo.</p>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <div style="margin-bottom:12px;">
                        <label for="preview-modelo" style="font-weight:700">Seleccionar modelo</label>
                        <select id="preview-modelo" class="form-control">
                            <option value="">-- Todos los modelos --</option>
                            @foreach($modelos as $m)
                                <option value="{{ $m->id }}" {{ (request('modelo') == $m->id) ? 'selected' : '' }}>{{ $m->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="preview-filters">
                        @include('partials.aside-detallemod', ['id' => $modeloId ?? request('modelo')])
                    </div>
                </div>
                <div class="col-lg-9">
                    {{-- Product grid (loaded via partial) --}}
                    <div id="preview-products">
                        @include('partials.catalogo-products', ['productos' => $productos])
                    </div>
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
                                @php
                                    $stock = $producto->stock ?? '≥ 20';
                                @endphp
                                @if ($stock === 0 || $stock === '0')
                                    <div class="product-badge out-of-stock">Agotado</div>
                                @elseif(isset($producto->created_at) && \Carbon\Carbon::parse($producto->created_at)->diffInDays(now()) <= 30)
                                    <div class="product-badge">Nuevo</div>
                                @endif

                                <div class="product-image">
                                    @php
                                        $modelImg = asset('producto.jpg');
                                        if ($producto->modelo && !empty($producto->modelo->img_mod)) {
                                            $modelImg = asset('storage/' . $producto->modelo->img_mod);
                                        } elseif ($producto->getCategoria && !empty($producto->getCategoria->img_cat)) {
                                            $modelImg = asset('storage/' . $producto->getCategoria->img_cat);
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
                                    @endphp

                                    <img src="{{ $img }}" alt="{{ $producto->nombre ?? 'Producto' }}" class="img-fluid"
                                        onerror="if(!this.dataset.fb){this.dataset.fb=1;this.src='{{ $imgFb }}';}else if(this.dataset.fb=='1'){this.dataset.fb=2;this.src='{{ $imgFb2 }}';}else{this.onerror=null;}">

                                    <div class="product-actions">
                                        <button class="quick-view" data-id="{{ $producto->id }}">
                                            <i class="bx bx-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="product-info">
                                    <span class="product-category">
                                        {{ $producto->modelo->nombre ?? ($producto->modelo->descripcion ?? 'Sin categoría') }}
                                    </span>

                                    <h3 class="product-title">{{ $producto->nombre ?? 'Nombre no disponible' }}</h3>

                                    <div class="product-details">
                                        <p><strong>Parte:</strong> {{ $producto->nro_parte ?? 'N/A' }}</p>
                                        <p><strong>Stock:</strong>
                                            @php
                                                $stock = $producto->stock ?? '≥ 20';
                                            @endphp
                                            @if ($stock !== 0 && $stock !== '0')
                                                <span class="in-stock">{{ $stock }} unidades</span>
                                            @else
                                                <span class="out-of-stock">No disponible</span>
                                            @endif
                                        </p>
                                    </div>

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
                            <li class="page-item {{ $productos->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $productos->previousPageUrl() }}" aria-label="Anterior">
                                    &laquo;
                                </a>
                            </li>
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

                            @foreach (range(max(1, $productos->currentPage() - 2), min($productos->lastPage(), $productos->currentPage() + 2)) as $page)
                                <li class="page-item {{ $productos->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $productos->url($page) }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            @if ($productos->lastPage() > $productos->currentPage()+2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                <li class="page-item">
                                    <a class="page-link" href="{{ $productos->url($productos->lastPage()) }}">{{ $productos->lastPage() }}</a>
                                </li>
                            @endif

                            <li class="page-item {{ !$productos->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $productos->nextPageUrl() }}">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endif
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')
    <script>
        (function(){
            const modeloSelect = document.getElementById('preview-modelo');
            const filtersContainer = document.getElementById('preview-filters');
            const productsContainer = document.getElementById('preview-products');
            const filtersUrlBase = @json(url('catalogo/filters'));
            const productsUrl = @json(url('catalogo/preview-products'));

            function fetchFilters(modeloId){
                const url = modeloId ? `${filtersUrlBase}/${modeloId}` : filtersUrlBase;
                fetch(url, { credentials: 'same-origin' }).then(r => r.text()).then(html => {
                    filtersContainer.innerHTML = html;
                }).catch(err => console.error(err));
            }

            function fetchProducts(){
                const params = new URLSearchParams(window.location.search);
                const modelo = modeloSelect.value;
                if (modelo) params.set('modelo', modelo); else params.delete('modelo');
                fetch(productsUrl + '?' + params.toString(), { credentials: 'same-origin' }).then(r => r.text()).then(html => {
                    productsContainer.innerHTML = html;
                }).catch(err => console.error(err));
            }

            if (modeloSelect){
                modeloSelect.addEventListener('change', () => {
                    fetchFilters(modeloSelect.value);
                    fetchProducts();
                });
            }

            // Initial load
            fetchFilters(modeloSelect ? modeloSelect.value : '');
        })();
    </script>
@endsection
