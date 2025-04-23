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
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i
                        class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catalogo</a></li>
            <li class="kenya-active"><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="#contact" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
<div style="height: 5px; box-shadow: inset -2px 2px 9px 0px rgb(0 0 0 / 10%);"></div>
<div style="background-color: #f1f1f1; height: 50px; margin-top: 70px;">
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
    <br>
    <div class="row">
        <div class="col-lg-4 mb-5">
            @php
                // Determinar la imagen a mostrar (prioridad: imagen del modelo, luego imagen del producto, luego imagen por defecto)
                $imagen = $producto->modelo && $producto->modelo->img_mod
                    ? asset('storage/' . $producto->modelo->img_mod)
                    : ($producto->imagen_1
                        ? asset('storage/' . $producto->imagen_1)
                        : asset('producto.jpg'));

                $altText = $producto->modelo
                    ? "Imagen del modelo " . ($producto->modelo->nombre ?? '')
                    : "Imagen del producto " . $producto->nombre;
            @endphp

            <div class="product-image-container">
                <img src="{{ $imagen }}" class="img-fluid w-100" alt="{{ $altText }}">
            </div>
        </div>

        <div class="col-lg-8" id="producto_detalle">
            <h2 class="" style="font-weight: bold; font-size: 36px; font-family: Arial">{{$producto->nombre}}</h2>
            <div class="carousel-descripcion mb-3 row" style="padding: 15px;">
                <div class="col-md-8">
                    <div>
                        <table>
                            <tbody>
                                @forelse($especificaciones->take(4) as $espec)
                                <tr>
                                    <td>{{ $espec->campo }}</td>
                                    <td>: {{ $espec->descripcion }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Aún no tiene especificaciones</td>
                                </tr>
                                @endforelse

                                <!-- Campo fijo -->
                                <tr>
                                    <td>Número de Parte</td>
                                    <td>: {{ $producto->nro_parte ?? 'No especificado' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 mt-2" style="font-size: 11px;">* Las imágenes e información incluidas son referenciales; pueden variar por versiones, por favor consultar a su vendedor.</div>

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
                        </tr>
                    </thead>
                </table>
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
            @forelse($especificaciones as $espec)
            <tr>
                <td>{{ $espec->campo }}</td>
                <td></td>
                <td>{{ $espec->descripcion }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted">Aún no tiene especificaciones</td>
            </tr>
            @endforelse

            <!-- Campo fijo -->
            <tr>
                <td>Número de Parte</td>
                <td></td>
                <td>{{ $producto->nro_parte ?? 'No especificado' }}</td>
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
