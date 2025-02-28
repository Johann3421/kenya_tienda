@extends('layouts.landing')
@section('css')
    <style>
        .listTable {
            display: flex;
            flex-direction: row;
            gap: 2rem;
            padding: 2rem 2rem 2rem 2rem;
            transition-delay: 3s;
        }

        .productoItem {
            /* background-color: #1965a7; */
            flex: 1 1 30%;
            /* width: 50%; */
            /* display: flex; */
            /* flex-direction: row; */
            /* gap: 1px; */
        }

        .contorno {
            max-width: 250px;
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            background-color: #ffffff;
            box-shadow: 0px 0px 25px .5px rgba(114, 114, 114, 0.281);
            padding-bottom: 1rem;
            border-bottom-left-radius: .5rem;
            border-bottom-right-radius: .5rem;
        }

        .portfolio-wrap {
            height: 100%;
            width: 100%;
            max-height: 250px;
            max-width: 250px;
            min-height: 100px;
            min-width: 100px;

        }

        .portfolio-wrap img {
            object-fit: cover;
            max-width: 100%;
            height: auto;
            border: none;
            outline: none;
        }

        .descripcion>div>h6 {
            font-size: 1rem;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .descripcion>div {
            margin: 1rem 0;
            position: relative;
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

        .team {
            background-color: #f2fff0;
        }

        .price-labled {
            position: absolute;
            top: 16px;
            right: -30px;
            width: 120px;
            height: 26px;
            line-height: 26px;
            background-color: #EF9614;
            color: #fff;
            transform: rotate(45deg);
        }

        .buscador {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 1.5rem 0 0 0;
            padding: 0 0 0 19rem;
        }

        .buscador>div {
            width: 40rem;
        }

        .portfolio {
            padding: 0;
        }

        /* Sección filtros */
        .form_producto {
            position: relative;
        }

        .lista {
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 0;
        }

        .lista input[type="checkbox"]+label {
            color: #444;
            margin: 0;
            margin-left: 1rem;
        }

        .lista input[type="checkbox"] {
            text-align: center;
            border-bottom: 1px solid rgba(0, 0, 0, .2);
        }

        .item_producto {
            list-style: none;
            padding: .5rem 8px .5rem 1rem;
            background-color: #ffffff;
            cursor: pointer;
        }

        .item_producto:hover {
            background-color: #e3e4e5;
        }

        aside {
            display: flex;
            flex-direction: column;
        }

        .seccion_filtro {
            width: 100%;
        }

        .seccion_filtro .boton_filtros {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: .5rem;
            margin: .1rem 0 .1rem 0;
        }

        .seccion_filtro .boton_filtros>button {
            border: none;
            background-color: transparent;
            cursor: default;
        }

        .seccion_filtro svg {
            font-size: 1.2rem;
            cursor: pointer;
        }

        .card {
            margin: 1rem 0 2rem 0;
        }
        .card input {
            padding: 1.3rem;
            border: none;
            border: 1px solid #2869a1;
        }
    </style>
@endsection
@section('menu')
    <nav class="nav-menu float-right d-none d-lg-block">
        <ul>
            <li><a href="{{ url('/') }}"><i class="bx bx-home"></i> Inicio</a></li>
            <li><a href="{{ url('/') }}">Productos</a></li>
            <li><a href="{{ url('/') }}">Ofertas</a></li>
            <li><a href="{{ url('/') }}">Novedades</a></li>
            <li><a href="{{ url('/') }}">Contáctenos</a></li>
            <li class="activo"><a href="{{ url('catalogo') }}">Catálogo</a></li>
            <li><a href="{{ route('consultar.garantia') }}">Soporte</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <div style="background-color: #f1f1f1; height: 50px;">
        <div class="container">
            <div class="pt-2">
                <ul style="display: flex; flex-wrap: wrap; list-style: none; padding: 0">
                    <li style="padding-right: 5px;"><a href="{{ url('/') }}">Inicio</a></li>
                    <li style="padding-right: 5px;"> / </li>
                    <li style="padding-right: 5px;"><a href="{{ url('/') }}">Catálogo</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section id="from-catalogo" class="portfolio">
        <br>
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <input type="text" id="search" v-model="search" class="form-control"
                            placeholder="Ingrese el nombre" v-on:keyup="Filtrar(search, 'nombre')">
                    </div>
                    {{-- <div class="card">
                        <article class="card-group-item">
                            <div class="filter-content">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item"
                                        v-on:click="Filtrar(null, 'categoria_id')">TODOS</a>
                                    <a v-for="categoria in categorias" href="#" class="list-group-item"
                                        v-on:click="Filtrar(categoria.id, 'categoria_id')">@{{ categoria.nombre }} <span
                                            class="float-right badge badge-light round">@{{ categoria.productos.length }}</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div> --}}
                    <aside>
                        <div class="seccion_filtro">
                            <div class="filter-content">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item"
                                        v-on:click="Filtrar(null, 'categoria_id')">TODOS</a>
                                </div>
                            </div>
                            <div class="boton_filtros" v-on:click="Desplegar('marca')">
                                <button>Marca</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.marca">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.marca">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="marcas_filtro.length !== 0" v-show="desplegar.marca" class="lista"  id="marca">
                                <li v-for="elemento in marcas_filtro" class="item_producto">
                                    <input type="checkbox" name="marca" v-on:change="Filtrar(elemento.marca, 'marca', $event)">
                                    <label for="check">@{{ elemento.marca }}</label>
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>

                <div class="col-lg-9">
                    <div class="row listTable">
                        <template v-if="listTable">
                            <template v-if="listaRequest.length != 0">
                                <div class="productoItem" v-for="prod in listaRequest">
                                    <div v-if="prod.pagina_web == 'SI'">
                                        <div class="contorno">
                                            <div class="portfolio-wrap" style="margin: 0 auto;">
                                                <img v-if="prod.imagen_1" src="{{ asset('../storage/prod.imagen_1') }}" class=""
                                                    alt="">
                                                <img v-else src="{{ asset('producto.jpg') }}" class=""
                                                    alt="">
                                                {{-- <div class="portfolio-info">
                                                    <h6 style="color: #fff;" v-if="prod.categoria_id">@{{ prod.id }}</h6>
                                                </div> --}}
                                                <!-- <div class="price-labled" style="text-align:center ;">S/. @{{ Soles(prod.precio_unitario) }}</div> -->
                                            </div>
                                            <div class="descripcion">
                                                <div class="text-center">
                                                    <h6>@{{ (prod.nombre).substring(0, 100) }}</h6>
                                                </div>
                                                <!-- <div class="text-center">
                                                                                                    <b class="p-precio">S/. @{{ Soles(prod.precio_unitario) }}</b>
                                                                                                    <b class="p-precio-old" v-if="prod.precio_anterior"> S/.@{{ Soles(prod.precio_anterior) }}</b>
                                                                                                </div> -->
                                            </div>
                                            {{-- class="btn btn-primary btn-sm" --}}
                                            <div class="botones">
                                                <a :href="'producto/' + prod.id + '/detalle'">Detalles</a>
                                                <!-- <a target="blank"
                                                                    href="https://wa.me/+51987592655?text=!Quiero Informacion sobre el producto">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                        fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z">
                                                                        </path>
                                                                    </svg>
                                                                </a> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                    <div id="list-paginator" style="display: none;" class="row">
                        <div class="col-sm-4 text-left">
                            <div style="margin: 7px; font-size: 15px;">@{{ pagination.current_page + ' de ' + pagination.to + ' Páginas ' }}</div>
                        </div>
                        <div class="col-sm-4">
                            <nav class="text-center" aria-label="...">
                                <ul class="pagination" style="justify-content: center;">
                                    <a href="#" v-if="pagination.current_page > 1" class="pag-inicio-fin"
                                        title="Página inicio" v-on:click.prevent="changePage(1)"><i
                                            class="fas fa-step-backward"></i></a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página inicio"><i
                                            class="fas fa-step-backward"></i></a>

                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;" title="Anterior"
                                            v-on:click.prevent="changePage(pagination.current_page - 1)">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item disabled" title="Anterior" v-else style="cursor: no-drop;"><a
                                            href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;"><i
                                                class="fas fa-angle-left"></i></a></li>
                                    <li class="page-item" v-for="page in pagesNumber"
                                        :class="[page == isActive ? 'active' : '']"><a href="#" class="page-link"
                                            v-on:click.prevent="changePage(page)">@{{ page }}</a></li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;" title="Siguiente"
                                            v-on:click.prevent="changePage(pagination.current_page + 1)">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>
                                    <li class="page-item disabled" title="Siguiente" v-else style="cursor: no-drop;"><a
                                            href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;"><i
                                                class="fas fa-angle-right"></i></a></li>

                                    <a href="#" v-if="pagination.current_page < pagination.last_page"
                                        class="pag-inicio-fin" title="Página final"
                                        v-on:click.prevent="changePage(pagination.last_page)"><i
                                            class="fas fa-step-forward"></i></a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página final"><i
                                            class="fas fa-step-forward"></i></a>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div style="margin: 7px; font-size: 15px;" v-if="to_pagination">@{{ to_pagination + ' de ' + pagination.total + ' Registros' }}</div>
                            <div style="margin: 7px; font-size: 15px;" v-else>0 de 0 Registros</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        var mis_categorias = {!! json_encode($categorias) !!};
        // var mis_productos = {!! json_encode($productos) !!};
        let productos_marcas = {!! json_encode($productos_marcas) !!};
        var exe = 0;
        new Vue({
            el: '#from-catalogo',
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
                    marca: null,
                    sistema_operativo: null,
                    categoria_id: null,
                },
                desplegar: {
                    marca: false,
                    sistema_operativo: false,
                },
                boolean: {
                    marca: false,
                    sistema_operativo: false,
                },
                //Objetos - Filtros
                marcas_filtro: productos_marcas,
            },
            created() {
                this.Buscar({
                    categoria_id: null,
                    marca: null,
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
                        categoria_id: this.filtros.categoria_id,
                        marca: this.filtros.marca,
                        nombre: this.filtros.nombre,
                    }, page);
                },
                Filtrar(value, campo, elemento) {

                    console.log(value, campo, elemento)
                    if (campo === 'marca') {
                        if (elemento.target.checked) {
                            this.filtros.marca = value;
                            this.Buscar({
                                categoria_id: null,
                                marca: this.filtros.marca,
                            })
                        } else {
                            this.filtros.marca = null;
                            this.Buscar({
                                categoria_id: null,
                                marca: this.filtros.marca,
                            })
                        }
                    }

                    if (campo === 'sistema_operativo') {
                        this.filtros.sistema_operativo = value;
                        this.Buscar({
                            categoria_id: null,
                            marca: this.filtros.sistema_operativo,
                        })
                    }

                    if (campo === 'nombre') {
                        this.filtros.nombre = value;
                        this.Buscar({
                            categoria_id: null,
                            nombre: this.filtros.nombre,
                        })
                    }

                    if (campo === 'categoria_id') {
                        this.filtros.categoria_id = value;
                        this.Buscar({
                            categoria_id: this.filtros.categoria_id,
                            nombre: this.filtros.nombre,
                        })
                    }

                    // console.log(value, campo)
                },
                Soles(num) {
                    $soles = Number.parseFloat(num).toFixed(2)
                    return $soles;
                },
                Buscar({
                    nombre,
                    categoria_id,
                    marca
                }, page) {
                    this.page = page;
                    this.active = 0;
                    urlBuscar = 'catalogo/buscar?page=' + page;
                    axios.post(urlBuscar, {
                        search: this.search,
                        search_por: this.search_por,
                        categoria: this.search_categoria,
                        web: this.search_web,
                        categoria_id: categoria_id,
                        marca: marca,
                        nombre: nombre,
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
                        this.pagination = response.data.pagination;
                        $(".focus_this").focus();
                    }).catch(error => {
                        alert(error + ". Por favor contacte al Administrador del Sistema.");
                    });
                },
                BuscarMarca() {
                    this.active = 0;
                    urlBuscar = 'catalogo/buscar';
                    axios
                        .post(urlBuscar, {
                            search: this.search_producto,
                        })
                        .then((response) => {
                            console.log(response)
                            this.listaRequest = response.data.producto;
                        })
                        .catch((error) => {
                            alert(
                                error +
                                ". Por favor contacte al Administrador del Sistema."
                            );
                        });
                },
                Desplegar(campo) {
                    if (campo === 'marca') {
                        this.ChangeBoolean(campo)
                        console.log({
                            boolean_marca: this.boolean.marca,
                            desplegar_marca: this.desplegar.marca
                        })
                    }

                    if (campo === 'sistema_operativo') {
                        this.ChangeBoolean(campo)
                        console.log({
                            boolean_marca: this.boolean.marca,
                            desplegar_sistema_operativo: this.desplegar.sistema_operativo
                        })
                    }
                    // this.ChangeBoolean(this.desplegar.marca);

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
