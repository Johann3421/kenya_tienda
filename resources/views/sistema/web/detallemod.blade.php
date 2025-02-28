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
            flex: 1 1 30%;
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
            <!-- <li class="activo"><a href="{{ url('catalogo') }}">Catálogo</a></li> -->
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
                        <div class="seccion_filtro" id="chec2">
                            <!-- <div class="filter-content">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item"
                                        v-on:click="Filtrar(null, 'categoria_id')">TODOS</a>
                                </div>
                            </div> -->
                            {{-- <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('marcas')">
                                <button>Marcas</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.marcas">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.marcas">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div> --}}
                            <ul v-if="marcas_filtro.length !== 0" v-show="desplegar.marcas" class="lista" id="">
                                <li v-for="elemento in marcas_filtro" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="marca" class="checkmark"
                                        v-on:change="Filtrar(elemento.marca, 'marcas', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.marca }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('procesadores')">
                                <button>Procesador</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.procesadores">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.procesadores">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="procesadores_filtro.length !== 0" v-show="desplegar.procesadores" class="lista" id="">
                                <li v-for="elemento in procesadores_filtro" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="procesador"
                                        v-on:change="Filtrar(elemento.procesador, 'procesadores', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.procesador }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('ram')">
                                <button>Memoria Ram</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.ram">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.ram">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="ram_filtro.length !== 0" v-show="desplegar.ram" class="lista" id="">
                                <li v-for="elemento in ram_filtro" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="procesador"
                                        v-on:change="Filtrar(elemento.ram, 'ram', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.ram }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('sistema_operativo')">
                                <button>Sistema Operativo</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.sistema_operativo">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.sistema_operativo">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="sistemas_operativos_filtro.length !== 0" v-show="desplegar.sistema_operativo" class="lista" id="">
                                <li v-for="elemento in sistemas_operativos_filtro" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="sistema_operativo"
                                        v-on:change="Filtrar(elemento.sistema_operativo, 'sistema_operativo', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.sistema_operativo }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('almacenamiento')">
                                <button>Almacenamiento</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.almacenamiento">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.almacenamiento">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="almacenamiento_filtro.length !== 0" v-show="desplegar.almacenamiento" class="lista" id="">
                                <li v-for="elemento in almacenamiento_filtro" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="almacenamiento"
                                        v-on:change="Filtrar(elemento.almacenamiento, 'almacenamiento', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.almacenamiento }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('conectividad')">
                                <button>Conectividad Lan</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.conectividad">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.conectividad">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="producto_lan.length !== 0" v-show="desplegar.conectividad" class="lista" id="">
                                <li v-for="elemento in producto_lan" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="conectividad"
                                        v-on:change="Filtrar(elemento.conectividad, 'conectividad', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.conectividad }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('conectividad_wlan')">
                                <button>Conectividad Wlan</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.conectividad_wlan">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.conectividad_wlan">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="producto_wlan.length !== 0" v-show="desplegar.conectividad_wlan" class="lista" id="">
                                <li v-for="elemento in producto_wlan" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="conectividad_wlan"
                                        v-on:change="Filtrar(elemento.conectividad_wlan, 'conectividad_wlan', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.conectividad_wlan }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('conectividad_usb')">
                                <button>Conectividad Usb</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.conectividad_usb">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.conectividad_usb">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="producto_usb.length !== 0" v-show="desplegar.conectividad_usb" class="lista" id="">
                                <li v-for="elemento in producto_usb" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="conectividad_usb"
                                        v-on:change="Filtrar(elemento.conectividad_usb, 'conectividad_usb', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.conectividad_usb }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('video_vga')">
                                <button>Video Vga</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.video_vga">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.video_vga">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="producto_vga.length !== 0" v-show="desplegar.video_vga" class="lista" id="">
                                <li v-for="elemento in producto_vga" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="video_vga"
                                        v-on:change="Filtrar(elemento.video_vga, 'video_vga', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.video_vga }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('video_hdmi')">
                                <button>Video HDMI</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.video_hdmi">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.video_hdmi">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="producto_hdmi.length !== 0" v-show="desplegar.video_hdmi" class="lista" id="">
                                <li v-for="elemento in producto_hdmi" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="video_hdmi"
                                        v-on:change="Filtrar(elemento.video_hdmi, 'video_hdmi', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.video_hdmi }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('unidades_opticas')">
                                <button>Unidad Optica</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.unidades_opticas">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.unidades_opticas">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="unidades_opticas_filtro.length !== 0" v-show="desplegar.unidades_opticas" class="lista" id="">
                                <li v-for="elemento in unidades_opticas_filtro" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="unidad_optica"
                                        v-on:change="Filtrar(elemento.unidad_optica, 'unidades_opticas', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.unidad_optica }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('teclados')">
                                <button>Teclado</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.teclados">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.teclados">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="teclados_filtro.length !== 0" v-show="desplegar.teclados" class="lista" id="">
                                <li v-for="elemento in teclados_filtro" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="teclado"
                                        v-on:change="Filtrar(elemento.teclado, 'teclados', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.teclado }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('mouses')">
                                <button>Mouse</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.mouses">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.mouses">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="mouses_filtro.length !== 0" v-show="desplegar.mouses" class="lista" id="">
                                <li v-for="elemento in mouses_filtro" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="mouse"
                                        v-on:change="Filtrar(elemento.mouse, 'mouses', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.mouse }}</label>
                                </li>
                            </ul>
                        </div>

                        <div class="seccion_filtro">
                            <div class="boton_filtros" style="font-size: 13.5px;" v-on:click="Desplegar('suites')">
                                <button>Suite Ofimatica</button>
                                <div class="icon_boton_filtros">
                                    <div v-show="!desplegar.suites">
                                        <i class="fa-solid fa-plus"></i>
                                    </div>
                                    <div v-show="desplegar.suites">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                </div>
                            </div>
                            <ul v-if="suites_filtro.length !== 0" v-show="desplegar.suites" class="lista" id="">
                                <li v-for="elemento in suites_filtro" class="item_producto" style="font-weight: bold">
                                    <input type="checkbox" name="suite_ofimatica"
                                        v-on:change="Filtrar(elemento.suite_ofimatica, 'suites', $event)">
                                    <label style="font-size: 14px;" for="check">@{{ elemento.suite_ofimatica }}</label>
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>


                <div class="col-lg-9">
                    {{-- <div class="row listTable">
                        <template v-if="listTable">
                            <template v-if="listaRequest.length != 0">
                                <div class="productoItem" v-for="prod in listaRequest">
                                    <div>
                                        <div v-if="prod.pagina_web == 'SI'">
                                            <div class="contorno">
                                                <div class="portfolio-wrap" style="margin: 0 auto;">
                                                    <img v-if="prod.imagen_1" :src="'../../storage/' + prod.imagen_1"
                                                        class=""alt="">
                                                    <img v-else src="{{ asset('producto.jpg') }}" class=""
                                                        alt="">
                                                    <div class="descripcion">
                                                        <div class="text-center">
                                                            <h6>@{{ (prod.nombre).substring(0, 100) }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="botones">
                                                        <a :href="'../../producto/' + prod.id + '/detalle'">Detalles</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </template>
                        </template>
                    </div> --}}
                    <div class="row listTable">
                        <template v-if="listTable">
                            <template v-if="listaRequest.length != 0">
                                <div class="productoItem" v-for="prod in listaRequest">
                                    <div v-if="prod.pagina_web == 'SI'">
                                        <div class="contorno">
                                            <div class="portfolio-wrap" style="margin: 0 auto;">
                                                <img v-if="prod.imagen_1" :src="'../../storage/' + prod.imagen_1" class=""
                                                    alt="">
                                                <img v-else src="{{ asset('producto.jpg') }}" class=""
                                                    alt="">
                                            </div>
                                            <div class="descripcion">
                                                <div class="text-center">
                                                    <h6>@{{ (prod.nombre).substring(0, 100) }}</h6>
                                                </div>
                                            </div>
                                            <div class="botones">
                                                <a :href="'../../producto/' + prod.id + '/detalle'"><i class="fa-solid fa-plus"></i> Detalles</a>
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
        const productos_marcas = {!! json_encode($productos_marcas) !!};
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
                    procesadores : [],
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
                    video_vga:false,
                    video_hdmi:false,
                    unidades_opticas:false,
                    teclados:false,
                    mouses:false,
                    suites:false,
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
                    video_vga:false,
                    video_hdmi:false,
                    unidades_opticas: false,
                    teclados:false,
                    mouses:false,
                    suites:false,
                },
                //Objetos - Filtros
                marcas_filtro: productos_marcas,
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
                Filtrar(value, campo, elemento) {

                    if (campo === 'nombre') {
                        this.filtros.nombre = value;
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
                        })
                    }else if(campo !== 'nombre' && campo !== " "){
                        if (elemento.target.checked) {
                            this.filtros[campo].push(value);
                        } else {
                            let index = this.filtros[campo].findIndex(e => e === value)

                            this.filtros[campo][index] = undefined

                            this.filtros[campo] = this.filtros[campo].filter(Boolean)
                        }

                        this.Buscar({
                                modelo_id: $modelo_id,
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
                            })
                    }
                },
                Soles(num) {
                    $soles = Number.parseFloat(num).toFixed(2)
                    return $soles;
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
                        video_hdmi:video_hdmi,
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
                        this.pagination = response.data.pagination;

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
