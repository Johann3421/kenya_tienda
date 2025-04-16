@extends('layouts.landing')
<link rel="stylesheet" href="{{ asset('css/detallemod.css') }}">
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
@section('content')
@php
use App\Modelo;
use Illuminate\Support\Str;

$modeloId = request()->route('id') ?? request()->route('modelo');
$modelo = Modelo::with(['productos.filtros', 'productos.modelo'])->findOrFail($modeloId);

$filtrosActivos = collect(request()->except('page'));

// Consulta base con paginación
$productosQuery = $modelo->productos()
                ->where('pagina_web', 'SI')
                ->with(['modelo', 'filtros']);

// Aplicar filtros si existen
if ($filtrosActivos->isNotEmpty()) {
    foreach ($filtrosActivos as $filtroNombre => $valores) {
        $valores = is_array($valores) ? $valores : explode(',', $valores);

        $productosQuery->whereHas('filtros', function ($q) use ($filtroNombre, $valores) {
            $q->whereIn('opcion', $valores)
              ->whereRaw('LOWER(nombre_aside) = ?', [strtolower($filtroNombre)]);
        });
    }
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
                                            $img = $prod->modelo && $prod->modelo->img_mod
                                                ? asset('storage/' . $prod->modelo->img_mod)
                                                : asset('producto.jpg');
                                        @endphp

                                        <img src="{{ $img }}" class="img-fluid"
                                             alt="Imagen del modelo de {{ $prod->nombre }}">
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

                    @if($productos->hasPages())
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
                        <a class="page-link" href="{{ $productos->url($i) }}">{{ $i }}</a>
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
            Mostrando {{ $productos->firstItem() }}-{{ $productos->lastItem() }} de {{ $productos->total() }} productos
        </div>
    </div>
</div>
@endif

                </div>
            </div>
            @include('components.novedades', ['novedades' => $novedades])

        </div>
    </section>
    <script src="{{ asset('js/detallemod.js') }}"></script>
@endsection

@section('js')
    <script>
        var mis_categorias = {!! json_encode($categorias) !!};
        // var mis_productos = {!! json_encode($productos) !!};
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
                filtros: {
                    nombre: null,
                    marcas: [],
                    procesadores: [],
                    ram: [],
                    categoria_id: null,
                    sistema_operativo: [],
                    almacenamiento: [],
                    conectividad: [],
                    conectividad_wlan: [],
                    conectividad_usb: [],
                    video_vga: [],
                    video_hdmi: [],
                    unidades_opticas: [],
                    teclados: [],
                    mouses: [],
                    suites: [],
                },
                desplegar: {
                    marcas: false,
                    procesadores: false,
                    ram: false,
                    sistema_operativo: false,
                    almacenamiento: false,
                    conectividad: false,
                    conectividad_wlan: false,
                    conectividad_usb: false,
                    video_vga: false,
                    video_hdmi: false,
                    unidades_opticas: false,
                    teclados: false,
                    mouses: false,
                    suites: false,
                },
                boolean: {
                    marcas: false,
                    procesadores: false,
                    ram: false,
                    sistema_operativo: false,
                    almacenamiento: false,
                    conectividad: false,
                    conectividad_wlan: false,
                    conectividad_usb: false,
                    video_vga: false,
                    video_hdmi: false,
                    unidades_opticas: false,
                    teclados: false,
                    mouses: false,
                    suites: false,
                },
                //Objetos - Filtros
                procesadores_filtro: productos_procesador,
                ram_filtro: productos_ram,
                sistemas_operativos_filtro: productos_sistema_operativo,
                almacenamiento_filtro: producto_almacenamiento,
                producto_lan: producto_lan,
                producto_wlan: producto_wlan,
                producto_usb: producto_usb,
                producto_vga: producto_vga,
                producto_hdmi: producto_hdmi,
                unidades_opticas_filtro: producto_unidades_opticas,
                teclados_filtro: producto_teclados,
                mouses_filtro: producto_mouses,
                suites_filtro: producto_suites,

            },
            created() {
                this.Buscar({
                    modelo_id: $modelo_id,
                    nombre: '',
                    marcas: this.filtros.marcas,
                    procesadores: this.filtros.procesadores,
                    ram: this.filtros.ram,
                    sistema_operativo: this.filtros.sistema_operativo,
                    almacenamiento: this.filtros.almacenamiento,
                    conectividad: this.filtros.conectividad,
                    conectividad_wlan: this.filtros.conectividad_wlan,
                    conectividad_usb: this.filtros.conectividad_usb,
                    video_vga: this.filtros.video_vga,
                    video_hdmi: this.filtros.video_hdmi,
                    unidades_opticas: this.filtros.unidades_opticas,
                    teclados: this.filtros.teclados,
                    mouses: this.filtros.mouses,
                    suites: this.filtros.suites,
                    categoria_id: null,
                });
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

                changePage(page) {
                    this.page = page;
                    this.pagination.current_page = page;
                    this.active = 0;
                    this.seleccion = null;
                    this.Buscar({
                        modelo_id: $modelo_id,
                        nombre: this.filtros.nombre,
                        marcas: this.filtros.marcas,
                        procesadores: this.filtros.procesadores,
                        ram: this.filtros.ram,
                        sistema_operativo: this.filtros.sistema_operativo,
                        almacenamiento: this.filtros.almacenamiento,
                        conectividad: this.filtros.conectividad,
                        conectividad_wlan: this.filtros.conectividad_wlan,
                        conectividad_usb: this.filtros.conectividad_usb,
                        video_vga: this.filtros.video_vga,
                        video_hdmi: this.filtros.video_hdmi,
                        unidades_opticas: this.filtros.unidades_opticas,
                        teclados: this.filtros.teclados,
                        mouses: this.filtros.mouses,
                        suites: this.filtros.suites,
                        categoria_id: this.filtros.categoria_id,
                    }, page);
                },
                Buscar({
                    modelo_id,
                    nombre,
                    marcas,
                    procesadores,
                    ram,
                    sistema_operativo,
                    almacenamiento,
                    conectividad,
                    conectividad_wlan,
                    conectividad_usb,
                    video_vga,
                    video_hdmi,
                    unidades_opticas,
                    teclados,
                    mouses,
                    suites,
                    categoria_id,
                }, page) {
                    this.page = page;
                    this.active = 0;
                    urlBuscar = '../../catalogo/buscar?page=' + page;
                    axios.post(urlBuscar, {
                        modelo_id: modelo_id,
                        search: this.search,
                        search_por: this.search_por,
                        categoria: this.search_categoria,
                        web: this.search_web,
                        categoria_id: categoria_id,

                        nombre: nombre,
                        marcas: marcas,
                        procesadores: procesadores,
                        ram: ram,
                        sistema_operativo: sistema_operativo,
                        almacenamiento: almacenamiento,
                        conectividad: conectividad,
                        conectividad_wlan: conectividad_wlan,
                        conectividad_usb: conectividad_usb,
                        video_vga: video_vga,
                        video_hdmi: video_hdmi,
                        unidades_opticas,
                        teclados,
                        mouses,
                        suites,
                    }).then(response => {
                        console.log(response)
                        if (exe == 0) {
                            $('#list-loading').hide();
                            this.listTable = true;
                            $('#list-paginator').show();
                            exe++;
                        }
                        this.listaRequest = response.data.productos.data;
                        this.to_pagination = response.data.productos.to;
                        this.pagination = {
                            total: response.data.productos.total,
                            current_page: response.data.productos.current_page,
                            per_page: response.data.productos.per_page,
                            last_page: response.data.productos.last_page,
                            from: response.data.productos.from,
                            to: response.data.productos.to
                        };


                        $(".focus_this").focus();
                    }).catch(error => {
                        alert(error + ". Por favor contacte al Administrador del Sistema.");
                    });
                },
                Desplegar(campo) {
                    if (campo !== 'nombre') {
                        this.ChangeBoolean(campo)
                    }
                },
                ChangeBoolean(value) {
                    if (!this.boolean[value]) {
                        this.desplegar[value] = true;
                        this.boolean[value] = true;
                    } else {
                        this.desplegar[value] = false;
                        this.boolean[value] = false;
                    }
                },
            },
        });

    </script>
@endsection
