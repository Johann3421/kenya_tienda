@extends('layouts.landing')
@section('css')
<style>
    .carousel-indicators li {
        background-color: #000;
    }

    .carousel-indicators {
        bottom: -50px;
    }

    .carousel-inner {
        /* border: 3px solid #cecece; */
        border-radius: 5px;
    }

    .carousel-detalle {
        border: 1px solid #e9ecef;
        border-radius: 5px;
    }

    .carousel-descripcion {
        /* border: 3px solid #cecece; */
        border-radius: 5px;
    }

    .p-precio-old {
        font-size: 20px;
        color: red;
        text-decoration: line-through;
    }

    .boton {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 80px;
        background: #141414;
        color: #fff;
        font-family: 'Roboto', sans-serif;
        font-size: 20px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-transform: uppercase;
        transition: .3s ease all;
        border-radius: 5px;
        position: relative;
        overflow: hidden;
    }

    .boton span {
        position: relative;
        z-index: 2;
        transition: .3s ease all;
    }

    .boton.cuatro::after {
        content: "";
        width: 1px;
        height: 1px;
        background: none;
        position: absolute;
        z-index: 1;
        top: 50%;
        left: 50%;
        transition: .3s ease-in-out all;
        border-radius: 100px;
        transform-origin: center;
    }

    .boton.cuatro:hover::after {
        transform: scale(400);
        background: #cc1010;
    }

    .boton.cuatro:hover {
        /* background: #960909; */
    }
</style>
@endsection
@section('menu')
<nav class="nav-menu float-right d-none d-lg-block">
    <ul>
        <li><a href="{{url('/')}}"><i class="bx bx-home"></i> Inicio</a></li>
        <li><a href="{{url('/')}}">Productos</a></li>
        <li><a href="{{url('/')}}">Ofertas</a></li>
        <li><a href="{{url('/')}}">Novedades</a></li>
        <li><a href="{{url('/')}}">Contáctenos</a></li>
        <!-- <li><a href="{{url('catalogo')}}">Catálogo</a></li> -->
        <li><a href="{{route('consultar.garantia')}}">Soporte</a></li>
    </ul>
</nav>
@endsection

@section('content')
<div style="height: 5px; box-shadow: inset -2px 2px 9px 0px rgb(0 0 0 / 10%);"></div>
<div style="background-color: #f1f1f1; height: 50px;">
    <div class="container">
        <div class="pt-2">
            <ul style="display: flex; flex-wrap: wrap; list-style: none; padding: 0">
                <li style="padding-right: 5px;"><a href="{{url('/')}}">Inicio </a></li>
                <li style="padding-right: 5px;"> / </li>
                <li style="padding-right: 5px;"><a href="{{url('/')}}"> Productos</a></li>
                <li style="padding-right: 5px;"> / </li>
                @if ($producto->categoria_id)
                <li style="padding-right: 5px;"><a href="#">{{$producto->getCategoria->nombre}}</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
<div class="container">
    <!-- <div class="mt-5">
        <h2 class="" style="color:#0062cc; font-weight: bold; font-size: 36px;">{{$producto->nombre}}</h2>
    </div> -->
    <br>
    <div class="row">
        <div class="col-lg-4 mb-5">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    @if ($producto->imagen_2)
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    @endif
                    @if ($producto->imagen_3)
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    @endif
                    @if ($producto->imagen_4)
                    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                    @endif
                    @if ($producto->imagen_5)
                    <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                    @endif
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        @if ($producto->imagen_1)
                        <img src="{{asset('storage/'.$producto->imagen_1)}}" class="d-block w-100">
                        @else
                        <img src="{{asset('producto.jpg')}}" class="d-block w-100">
                        @endif
                    </div>
                    @if ($producto->imagen_2)
                    <div class="carousel-item">
                        <img src="{{asset('storage/'.$producto->imagen_2)}}" class="d-block w-100">
                    </div>
                    @endif
                    @if ($producto->imagen_3)
                    <div class="carousel-item">
                        <img src="{{asset('storage/'.$producto->imagen_3)}}" class="d-block w-100">
                    </div>
                    @endif
                    @if ($producto->imagen_4)
                    <div class="carousel-item">
                        <img src="{{asset('storage/'.$producto->imagen_4)}}" class="d-block w-100">
                    </div>
                    @endif
                    @if ($producto->imagen_5)
                    <div class="carousel-item">
                        <img src="{{asset('storage/'.$producto->imagen_5)}}" class="d-block w-100">
                    </div>
                    @endif
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <div class="col-lg-8" id="producto_detalle">
            <h2 class="" style="font-weight: bold; font-size: 36px; font-family: Arial">{{$producto->nombre}}</h2>
            <div class="carousel-descripcion mb-3 row" style="padding: 15px;">
                <div class="col-md-8">
                    <!-- <div style="font-size: 36px;">
                        S/. {{number_format($producto->precio_unitario, 2, '.', ' ')}}
                        @if ($producto->precio_anterior)
                        <small class="p-precio-old">S/.{{number_format($producto->precio_anterior, 2, '.', ' ')}}</small>
                        @endif
                    </div> -->
                    <div>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Procesador</td>
                                    <td>: {{$producto->procesador}}</td>
                                </tr>
                                <tr>
                                    <td>Memoria RAM</td>
                                    <td>: {{$producto->ram}}</td>
                                </tr>
                                <tr>
                                    <td>Almacenamiento</td>
                                    <td>: {{$producto->almacenamiento}}</td>
                                </tr>
                                <tr>
                                    <td>Sistema Operativo</td>
                                    <td>: {{$producto->sistema_operativo}}</td>
                                </tr>
                                <tr>
                                    <td>Software Ofimatica</td>
                                    <td>: {{$producto->suite_ofimatica}}</td>
                                </tr>
                                <tr>
                                    <td>Teclado:</td>
                                    <td>: {{$producto->teclado}}</td>
                                </tr>
                                <tr>
                                    <td>Mouse:</td>
                                    <td>: {{$producto->mouse}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 mt-2" style="font-size: 11px;">* Las imágenes e información incluidas son referenciales; pueden variar por versiones, por favor consultar a su vendedor.</div>
                <!-- <div class="col-md-12 mt-2" style="font-weight: 900;"><i class="bx bxs-book-content"></i> Guias & Manuales</div>
                @foreach ($producto->getManual as $manual)
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="background-color:#f8f7f7;width: 10rem;">
                            <div class="card-body">
                                <p class="card-text" style="text-align: center;font-size: 13px;font-weight: 600;">{{$manual->descripcion}}</p>
                                <p class="card-text" style="text-align: center;font-size: 13px;font-weight: 600;">
                                    <a href="{{$manual->link}}" Target=_blank class="link-danger">
                                        View PDF <i class="bx bx-right-arrow-alt"></i>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach -->
            </div>
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col-12">
                                <div class="grid-icons__item grid-icon" data-v-563d1590="" data-v-e15b3258="">
                                    @foreach($producto->getGarantia->skip(0)->take(1) as $gar)
                                    <div draggable="true" class="app-icon grid-icon__icon is-dotty" data-v-563d1590="">TIEMPO DE GARANTIA: {{$gar->garantia}} MESES
                                        @endforeach
                                        <img alt="Tarjeta de garantía icon" srcset="https://img.icons8.com/fluency/2x/guarantee.png 3x">
                                        <br>
                                        FICHA TECNICA:
                                        <a href="{{asset("/storage/$producto->ficha_tecnica")}}" target="_blank">
                                            PDF <iconify-icon icon="bx:download"></iconify-icon>
                                        </a>
                                        <img alt="Tarjeta de garantía icon" srcset="https://img.icons8.com/ios-filled/2x/wordbook.png 3x" style="text-align:center;filter:invert(0%) sepia(0%) saturate(7469%) hue-rotate(214deg) brightness(91%) contrast(107%);">
                                    </div>
                                </div>
                            </th>
                            <th>
                            <a target="blank" href="https://wa.me/+51958021778?text=!Quiero Informacion sobre el producto" class="btn btn-block btn-success"><i class="bx bxl-whatsapp" style="font-size: 24px; vertical-align: bottom;"></i> Contactar</a>

                            </th>
                            <!-- <th scope="col">
                                    <div class="grid-icons__item grid-icon" data-v-563d1590="" data-v-e15b3258="">
                                        <div draggable="true" class="app-icon grid-icon__icon is-dotty" data-v-563d1590="">
                                            <img alt="Tarjeta de garantía icon" srcset="https://img.icons8.com/ios-filled/2x/wordbook.png 3x" style="text-align:center;filter:invert(0%) sepia(0%) saturate(7469%) hue-rotate(214deg) brightness(91%) contrast(107%);">
                                            <div id="i8-tooltip-container" class="i8-tooltip-container grid-icon__title" data-v-58cd4ab7="" data-v-563d1590="">
                                                <p style="font-size: 12px;">
                                                    <a href="{{$producto->ficha_tecnica}}">
                                                        Descargar PDF <iconify-icon icon="bx:download"></iconify-icon>
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </th> -->
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="carousel-detalle" style="padding: 15px;" v-if="detalle">
                <div v-if="detalle == 1">
                    <h4>Especificaciones</h4>
                    <!-- {{$producto->nombre}} -->
                    <table class="" style="font-size: 11px;">
                        <tbody>
                            <tr>
                                <td scope="col" width="15%" style="font-weight: bold;">MARCA</td>
                                <td>{{$producto->modelo}}</td>
                                <td scope="col" width="15%" style="font-weight: bold;">MODELO</td>
                                <td>{{$producto->modelo}}</td>
                                <td scope="col" width="15%" style="font-weight: bold;">PROCESADOR</td>
                                <td>{{$producto->procesador}}</td>
                            </tr>
                            <tr>
                                <td scope="col" style="font-weight: bold;">ALMACEN</td>
                                <td>{{$producto->almacenamiento}}</td>
                                <td scope="col" style="font-weight: bold;">LAN</td>
                                <td>{{$producto->conectividad}}</td>
                                <td scope="col" style="font-weight: bold;">WLAN</td>
                                <td>{{$producto->conectividad_wlan}}</td>
                            </tr>
                            <tr>
                                <td scope="col" style="font-weight: bold;">USB</td>
                                <td>{{$producto->conectividad_usb}}</td>
                                <td scope="col" style="font-weight: bold;">HDMI</td>
                                <td>{{$producto->video_hdmi}}</td>
                                <td scope="col" style="font-weight: bold;">VGA</td>
                                <td>{{$producto->video_vga}}</td>
                            </tr>
                            <tr>
                                <td scope="col" style="font-weight: bold;">SISTEMA OEPRATIVO</td>
                                <td>{{$producto->sistema_operativo}}</td>
                                <td scope="col" style="font-weight: bold;">UNIDAD OPTICA</td>
                                <td>{{$producto->unidad_optica}}</td>
                                <td scope="col" style="font-weight: bold;">TECLADO</td>
                                <td>{{$producto->teclado}}</td>
                            </tr>
                            <tr>
                                <td scope="col" style="font-weight: bold;">MOUSE</td>
                                <td>{{$producto->mouse}}</td>
                                <td scope="col" style="font-weight: bold;">SUIT OFIMATICA</td>
                                <td>{{$producto->suite_ofimatica}}</td>
                                <td scope="col" style="font-weight: bold;">GARANTIA</td>
                                <td>{{$producto->garantia_de_fabrica}}</td>
                            </tr>
                            <tr>
                                <td scope="col" style="font-weight: bold;">EMPAQUE</td>
                                <td>{{$producto->empaque_de_fabrica}}</td>
                                <td scope="col" style="font-weight: bold;">CERTIFICACION</td>
                                <td>{{$producto->certificacion}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else style="width: 100%;">
                    <h4>Drivers</h4>
                    @foreach ($producto->getDrivers as $prod)
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th style="font-size: 11px;">Nombre</th>
                                <th style="font-size: 11px;">Version</th>
                                <th style="font-size: 11px;">Liberado</th>
                                <th style="font-size: 11px;">Tamaño</th>
                                <th style="font-size: 11px;">Gravedad</th>
                                <th style="font-size: 11px;">Descargar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th style="font-size: 11px;font-weight: 500;">{{$prod->nombre}}</th>
                                <th style="font-size: 11px;font-weight: 500;">{{$prod->version}}</th>
                                <th style="font-size: 11px;font-weight: 500;">{{$prod->liberado}}</th>
                                <th style="font-size: 11px;font-weight: 500;">{{$prod->tamano}}</th>
                                <th style="font-size: 11px;font-weight: 500;">{{$prod->gravedad}}</th>
                                <th style="font-weight: 500;">
                                    <a href="{{$prod->link}}" Target=_blank class="link-danger">
                                        <iconify-icon icon="bx:download"></iconify-icon>
                                    </a>
                                </th>
                                <!-- <th style="font-size: 11px;font-weight: 500;">{{$prod->link}}</th> -->
                            </tr>
                        </tbody>
                    </table>
                    @endforeach
                    <!--@if ($producto->categoria_id)
                        <li style="padding-right: 5px;"><a href="#">{{$producto->getCategoria->nombre}}</a></li>
                    @endif -->
                    <!-- @php
                        $lineas = explode("<br>",nl2br($producto->nombre));
                    @endphp -->
                    <!-- <pre style="font-family: 'Raleway', sans-serif;">{{$producto->nombre}}</pre> -->
                </div>
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr style="background-color: #EF9614;">
                <th><i class="fa-solid fa-box"></i> Especificaciones Técnicas</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Procesador</td>
                <td></td>
                <td>{{$producto->procesador}}</td>
            </tr>
            <tr>
                <td>Memoria RAM</td>
                <td></td>
                <td>{{$producto->ram}}</td>
            </tr>
            <tr>
                <td>Almacenamiento</td>
                <td></td>
                <td>{{$producto->almacenamiento}}</td>
            </tr>
            <tr>
                <td>Unidad Óptica</td>
                <td></td>
                <td>{{$producto->unidad_optica}}</td>
            </tr>
            <tr>
                <td>Conectividad LAN</td>
                <td></td>
                <td>{{$producto->conectividad}}</td>
            </tr>
            <tr>
                <td>Conectividad WLAN</td>
                <td></td>
                <td>{{$producto->conectividad_wlan}}</td>
            </tr>
            <tr>
                <td>Conectividad USB</td>
                <td></td>
                <td>{{$producto->conectividad_usb}}</td>
            </tr>
            <tr>
                <td>Conectividad VGA</td>
                <td></td>
                <td>{{$producto->video_vga}}</td>
            </tr>
            <tr>
                <td>Conectividad HDMI</td>
                <td></td>
                <td>{{$producto->video_hdmi}}</td>
            </tr>
            <tr>
                <td>Sistema Operativo</td>
                <td></td>
                <td>{{$producto->sistema_operativo}}</td>
            </tr>
            <tr>
                <td>Software de Ofimática</td>
                <td></td>
                <td>{{$producto->suite_ofimatica}}</td>
            </tr>
            <tr>
                <td>Periféricos</td>
                <td></td>
                @if($producto->teclado == 'SI')
                <td>Mouse y Teclado</td>
                @else($producto->teclado == 'NO')
                <td>No</td>
                @endif
            </tr>
            <tr>
                <td>Número de Parte</td>
                <td></td>
                <td>{{$producto->nro_parte}}</td>
            </tr>
        </tbody>
    </table>
</div>
<br>
@endsection

@section('js')
<script>
    new Vue({
        el: '#producto_detalle',
        data: {
            detalle: null,
        },
    });
</script>
<script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
@endsection
