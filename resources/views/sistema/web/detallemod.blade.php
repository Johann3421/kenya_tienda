@extends('layouts.landing')
<link rel="stylesheet" href="{{ asset('css/detallemod.css') }}">
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i
                        class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catalogo</a></li>
            <li class="kenya-active"><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection
@section('content')
    @php
        use App\Modelo;
        use Illuminate\Support\Str;

        $modeloId = request()->route('id') ?? request()->route('modelo');
        $modelo = Modelo::with(['productos.filtros', 'productos.modelo'])->findOrFail($modeloId);

        $filtrosActivos = collect(request()->except('page'));

        // Consulta base con paginación
        $productosQuery = $modelo
            ->productos()
            ->where('pagina_web', 'SI')
            ->with(['modelo', 'filtros']);

        // Aplicar filtros si existen
        if ($filtrosActivos->isNotEmpty()) {
            foreach ($filtrosActivos as $filtroNombre => $valores) {
                $valores = is_array($valores) ? $valores : explode(',', $valores);

                $productosQuery->whereHas('filtros', function ($q) use ($filtroNombre, $valores) {
                    $q->whereIn('opcion', $valores)->whereRaw('LOWER(nombre_aside) = ?', [strtolower($filtroNombre)]);
                });
            }
        }

        $productos = $productosQuery->paginate(9)->appends(request()->query());
    @endphp
    @php
    $modeloId = request()->route('id') ?? request()->route('modelo');
    $modelo = Modelo::with(['productos.filtros', 'productos.modelo'])->findOrFail($modeloId);

    $filtrosActivos = collect(request()->except('page', 'nombre')); // Excluir 'nombre' del resto de filtros

    // Consulta base con paginación
    $productosQuery = $modelo
        ->productos()
        ->where('pagina_web', 'SI')
        ->with(['modelo', 'filtros']);

    // Aplicar filtros si existen
    if ($filtrosActivos->isNotEmpty()) {
        foreach ($filtrosActivos as $filtroNombre => $valores) {
            $valores = is_array($valores) ? $valores : explode(',', $valores);

            $productosQuery->whereHas('filtros', function ($q) use ($filtroNombre, $valores) {
                $q->whereIn('opcion', $valores)->whereRaw('LOWER(nombre_aside) = ?', [strtolower($filtroNombre)]);
            });
        }
    }

    // Filtro por nombre del producto
    if (request()->filled('nombre')) {
        $productosQuery->where('nombre', 'LIKE', '%' . request('nombre') . '%');
    }

    $productos = $productosQuery->paginate(9)->appends(request()->query());
@endphp

    <div style="background-color: #f1f1f1; height: 50px; margin-top: 72px;">
        <div class="container">
            <div class="pt-2">
                <ul style="display: flex; flex-wrap: wrap; list-style: none; padding: 0">
                    <li style="padding-right: 5px;"><a href="{{ url('/') }}">Inicio</a></li>
                    <li style="padding-right: 5px;"> / </li>
                    <li style="padding-right: 5px;"><a href="{{ url('/') }}">Catálogo</a></li>
                    <li style="padding-right: 5px;"> / </li>
                    <li style="padding-right: 5px;"><a href="{{ url('/') }}">Modelo</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section id="from-modelo" class="portfolio">
        <br>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <input type="text" id="search" v-model="search" class="form-control"
    placeholder="Ingrese el nombre" v-on:keyup="Filtrar(search, 'nombre')">
                    </div>
                    <aside>
                        @include('partials.aside-normal')
                    </aside>

                </div>

                <div class="col-lg-9">
                    <div class="row listTable">
                        @forelse ($productos as $prod)
                            <div class="productoItem">
                                <div class="contorno">
                                    <div class="portfolio-wrap" style="margin: 0 auto;">
    @php
        $isModelo10 = $prod->modelo && $prod->modelo->id == 10;
        $isTonner = $prod->modelo && stripos($prod->modelo->descripcion ?? '', 'tonner') !== false;

        $img = ($isModelo10 || $isTonner)
            ? ($prod->imagen_1 ? asset('storage/' . $prod->imagen_1) : asset('producto.jpg'))
            : ($prod->modelo && $prod->modelo->img_mod
                ? asset('storage/' . $prod->modelo->img_mod)
                : asset('producto.jpg'));
    @endphp

    <img src="{{ $img }}"
         class="img-fluid"
         alt="Imagen de {{ $prod->nombre }}"
         loading="lazy"
         onerror="this.onerror=null;this.src='{{ asset('producto.jpg') }}'">
</div>


                                    <div class="descripcion">
                                        <div class="text-center">
                                            <h6>{{ Str::limit($prod->nombre, 100) }}</h6>
                                        </div>
                                    </div>

                                    <div class="botones">
                                        <a href="{{ url('producto/' . $prod->id . '/detalle') }}">
                                            <i class="fa-solid fa-plus"></i> Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center">
                                <p>No se encontraron productos con esos filtros.</p>
                            </div>
                        @endforelse
                    </div>

                    @if ($productos->hasPages())
                        <div class="row align-items-center mt-3">
                            <!-- Izquierda: Página actual -->
                            <div class="col-md-4 text-left">
                                <div class="pagination-info" style="margin: 7px; font-size: 14px;">
                                    Página {{ $productos->currentPage() }} de {{ $productos->lastPage() }}
                                </div>
                            </div>

                            <!-- Centro: Botones numerados -->
                            <div class="col-md-4">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-sm justify-content-center m-0">
                                        <!-- Primera -->
                                        <li class="page-item {{ $productos->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $productos->url(1) }}">
                                                <i class="fas fa-angle-double-left"></i>
                                            </a>
                                        </li>

                                        <!-- Anterior -->
                                        <li class="page-item {{ $productos->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $productos->previousPageUrl() }}">
                                                <i class="fas fa-angle-left"></i>
                                            </a>
                                        </li>

                                        @php
                                            $start = max($productos->currentPage() - 2, 1);
                                            $end = min($productos->lastPage(), $start + 3);
                                            if ($start > 1) {
                                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                            }
                                        @endphp

                                        @for ($i = $start; $i <= $end; $i++)
                                            <li class="page-item {{ $i == $productos->currentPage() ? 'active' : '' }}">
                                                <a class="page-link"
                                                    href="{{ $productos->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor

                                        @if ($end < $productos->lastPage())
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $productos->url($productos->lastPage()) }}">
                                                    {{ $productos->lastPage() }}
                                                </a>
                                            </li>
                                        @endif

                                        <!-- Siguiente -->
                                        <li class="page-item {{ !$productos->hasMorePages() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $productos->nextPageUrl() }}">
                                                <i class="fas fa-angle-right"></i>
                                            </a>
                                        </li>

                                        <!-- Última -->
                                        <li class="page-item {{ !$productos->hasMorePages() ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ $productos->url($productos->lastPage()) }}">
                                                <i class="fas fa-angle-double-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                            <!-- Derecha: Cantidad total -->
                            <div class="col-md-4 text-right">
                                <div class="pagination-info" style="margin: 7px; font-size: 14px;">
                                    Mostrando {{ $productos->firstItem() }}-{{ $productos->lastItem() }} de
                                    {{ $productos->total() }} productos
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
    @include('components.novedades', ['novedades' => $novedades])
    <script src="{{ asset('js/detallemod.js') }}"></script>

@endsection

@section('js')
    <script>
        var mis_categorias = {!! json_encode($categorias) !!};
        const productos_procesador = {!! json_encode($productos_procesador) !!};
        const productos_ram = {!! json_encode($productos_ram) !!};
        const productos_sistema_operativo = {!! json_encode($productos_sistema_operativo) !!};
        const producto_almacenamiento = {!! json_encode($producto_almacenamiento) !!};
        const producto_lan = {!! json_encode($producto_lan) !!};
        const producto_wlan = {!! json_encode($producto_wlan) !!};
        const producto_usb = {!! json_encode($producto_usb) !!};
        const producto_vga = {!! json_encode($producto_vga) !!};
        const producto_hdmi = {!! json_encode($producto_hdmi) !!};
        const producto_unidades_opticas = {!! json_encode($producto_unidades_opticas) !!};
        const producto_teclados = {!! json_encode($producto_teclados) !!};
        const producto_mouses = {!! json_encode($producto_mouses) !!};
        const producto_suites = {!! json_encode($producto_suites) !!};
        const $modelo_id = {!! json_encode($id) !!};

        var exe = 0;
        new Vue({
            el: '#from-modelo',
            data: {
                page: null,
                active: 0,
                listTable: false,
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,
                to_pagination: 0,
                categorias: mis_categorias,
                productos: null,

                listaRequest: null,
                mostrar: true,
                // categoria_id: null,

                //Filtros
                campo: null,
                search: '',

            },
            created() {
            },
            computed: {
                isActive: function() {
                    return this.pagination.current_page;
                },
                pagesNumber: function() {
                    if (!this.pagination.to) {
                        return [];
                    }

                    var from = this.pagination.current_page - this.offset;
                    if (from < 1) {
                        from = 1;
                    }

                    var to = from + (this.offset * 2);
                    if (to >= this.pagination.last_page) {
                        to = this.pagination.last_page;
                    }

                    var pagesArray = [];
                    while (from <= to) {
                        pagesArray.push(from);
                        from++;
                    }
                    return pagesArray;
                }
            },
            methods: {
                Filtrar(search, campo) {
        const query = new URLSearchParams(window.location.search);

        // Actualiza el parámetro de búsqueda
        if (search) {
            query.set(campo, search);
        } else {
            query.delete(campo);
        }

        // Actualiza la URL y recarga la página
        const newUrl = `${window.location.pathname}?${query.toString()}`;
        window.history.pushState({}, '', newUrl);
        window.location.reload(); // Recargar para que Laravel procese el filtro
    }
            },
        });
    </script>
@endsection
