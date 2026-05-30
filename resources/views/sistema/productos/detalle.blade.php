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
            {{-- Sorteo temporalmente oculto en producción --}}
            {{-- <li><a href="{{ route('serial.draw') }}" class="kenya-nav-link">🎁 Sorteo</a></li> --}}
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
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
                // Detección de modelos tonner (ID 10 o descripción contiene 'tonner')
                $isTonner = $producto->modelo && (
                    $producto->modelo->id == 10 ||
                    stripos($producto->modelo->descripcion ?? '', 'tonner') !== false
                );

                // Lógica de imágenes priorizada
                $imagen = $isTonner
                    ? ($producto->imagen_1
                        ? asset('storage/' . $producto->imagen_1)
                        : asset('producto.jpg'))
                    : ($producto->modelo && $producto->modelo->img_mod
                        ? asset('storage/' . $producto->modelo->img_mod)
                        : ($producto->imagen_1
                            ? asset('storage/' . $producto->imagen_1)
                            : asset('producto.jpg')));

                // Texto alt mejorado
                $altText = $isTonner
                    ? "Imagen del producto " . $producto->nombre
                    : ($producto->modelo
                        ? "Imagen del modelo " . ($producto->modelo->nombre ?? '')
                        : "Imagen del producto " . $producto->nombre);
            @endphp

            <div class="product-image-container">
                <img src="{{ $imagen }}" class="img-fluid w-100" alt="{{ $altText }}"
                     onerror="this.src='{{ asset('producto.jpg') }}'">
            </div>
        </div>

        <div class="col-lg-8" id="producto_detalle">
            @php
                $isMonitor = false;
                $specTam = $especificaciones->firstWhere('campo', 'Tamaño de Pantalla') ?? null;
                if ($specTam) {
                    $isMonitor = true;
                } elseif (optional($producto->getCategoria)->nombre) {
                    $isMonitor = stripos(optional($producto->getCategoria)->nombre, 'monitor') !== false;
                }

                // Normalizar y filtrar especificaciones válidas
                $specsList = [];
                foreach ($especificaciones as $s) {
                    $desc = trim(strtolower($s->descripcion ?? ''));
                    if ($desc === 'no' || $desc === '') continue;
                    $specsList[] = $s;
                }

                $findSpec = function($pattern) use ($specsList) {
                    foreach ($specsList as $sp) {
                        if (preg_match($pattern, strtolower($sp->campo))) return $sp;
                    }
                    return null;
                };

                // Top summary: para PCs (no monitores) ordenar Procesador, Memoria, Almacenamiento, Graficos
                $topOrdered = [];
                if (!$isMonitor) {
                    $topPatterns = [
                        'Procesador' => '/procesador|cpu|intel|amd/',
                        'Memoria RAM' => '/memoria|ram/',
                        'Almacenamiento' => '/almacenamiento|disco|hdd|ssd|nvme|storage/',
                        'Gráficos' => '/graf|gpu|tarjeta|video|tarjeta de video|tarjeta gráfica/',
                    ];

                    foreach ($topPatterns as $label => $pat) {
                        $found = $findSpec($pat);
                        if ($found) $topOrdered[] = (object)['campo' => $label, 'descripcion' => $found->descripcion];
                    }

                    // Rellenar con los primeros specs disponibles hasta 4 items
                    foreach ($specsList as $s) {
                        if (count($topOrdered) >= 4) break;
                        $exists = false;
                        foreach ($topOrdered as $t) {
                            if (strtolower($t->campo) === strtolower($s->campo)) { $exists = true; break; }
                        }
                        if (!$exists) $topOrdered[] = (object)['campo' => $s->campo, 'descripcion' => $s->descripcion];
                    }
                }
            @endphp

            <h2 class="" style="font-weight: bold; font-size: 36px; font-family: Arial">
                @if($isMonitor) MONITOR @endif {{ $producto->nombre }}
            </h2>

            <div class="carousel-descripcion mb-3 row" style="padding: 15px;">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                @if($isMonitor)
                                    @forelse($especificacionesResumen as $espec)
                                    <tr>
                                        <td style="min-width:160px;font-weight:600">{{ $espec->campo }}</td>
                                        <td>: {{ $espec->descripcion }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Aún no tiene especificaciones</td>
                                    </tr>
                                    @endforelse
                                @else
                                    @forelse($topOrdered as $espec)
                                    <tr>
                                        <td style="min-width:160px;font-weight:700">{{ $espec->campo }}</td>
                                        <td>: {{ $espec->descripcion }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Aún no tiene especificaciones</td>
                                    </tr>
                                    @endforelse
                                @endif
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
                                        @if($producto->ficha_tecnica)
                                            @php
                                                $fichaUrl = str_starts_with($producto->ficha_tecnica, 'http')
                                                    ? $producto->ficha_tecnica
                                                    : asset('/storage/' . $producto->ficha_tecnica);
                                            @endphp
                                            <a href="{{ $fichaUrl }}" target="_blank">
                                                PDF <iconify-icon icon="bx:download"></iconify-icon>
                                            </a>
                                        @else
                                            <span class="text-muted">No disponible</span>
                                        @endif
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
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr style="background-color: #EF9614;">
                    <th><i class="fa-solid fa-box"></i> Especificaciones Técnicas</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Orden completo solicitado
                    $fullOrder = [
                        ['label' => 'Número de Parte', 'type' => 'product', 'field' => 'nro_parte'],
                        ['label' => 'Modelo', 'type' => 'product_model'],
                        ['label' => 'Formato', 'pattern' => '/formato|factor|formato/'],
                        ['label' => 'Procesador', 'pattern' => '/procesador|cpu|intel|amd/'],
                        ['label' => 'Memoria Ram', 'pattern' => '/memoria|ram/'],
                        ['label' => 'Almacenamiento', 'pattern' => '/almacenamiento|disco|hdd|ssd|nvme|storage/'],
                        ['label' => 'Sistema Operativo', 'pattern' => '/sistema operativo|os|windows|linux/'],
                        ['label' => 'Suite Ofimática', 'pattern' => '/ofimatica|office|suite/'],
                        ['label' => 'Gráficos', 'pattern' => '/graf|gpu|tarjeta|video|tarjeta de video|tarjeta gráfica/'],
                        ['label' => 'Sonido', 'pattern' => '/sonido|audio/'],
                        ['label' => 'Chipset', 'pattern' => '/chipset/'],
                        ['label' => 'Lan', 'pattern' => '/lan|ethernet|red/'],
                        ['label' => 'Wlan', 'pattern' => '/wlan|wifi|wireless/'],
                        ['label' => 'Puertos Mínimos', 'pattern' => '/puertos|minimo|puertos minimos|puertos m[ií]nimos/'],
                        ['label' => 'Slot de Expansión', 'pattern' => '/slot|expansi|pci|m\.2/'],
                        ['label' => 'Fuente de Poder', 'pattern' => '/fuente|psu|power supply/'],
                        ['label' => 'Garantia', 'pattern' => '/garantia|garant[ií]a/'],
                        ['label' => 'Empaque', 'pattern' => '/empaque|packag/'],
                        ['label' => 'Certificaciones', 'pattern' => '/certific|iso/'],
                        ['label' => 'Accesorios y Otros', 'pattern' => '/accesorio|otros|observaciones|incluye/'],
                    ];

                    $used = [];
                    $finalRows = [];

                    foreach ($fullOrder as $row) {
                        $value = null;
                        if (isset($row['type']) && $row['type'] === 'product') {
                            $value = $producto->{$row['field']} ?? null;
                        } elseif (isset($row['type']) && $row['type'] === 'product_model') {
                            $value = optional($producto->modelo)->nombre ?? optional($producto->modelo)->descripcion ?? null;
                        } else {
                            $found = $findSpec($row['pattern']);
                            if ($found) {
                                $value = $found->descripcion;
                                $used[] = $found->id ?? ($found->campo . '_' . $found->descripcion);
                            }
                        }

                        // fallbacks
                        if (!$value && $row['label'] === 'Gráficos') {
                            $value = $producto->tarjetavideo ?? null;
                        }

                        $finalRows[] = ['label' => $row['label'], 'value' => $value ?: 'No especificado'];
                    }

                    // Añadir especificaciones no listadas al final bajo Accesorios y Otros
                    foreach ($specsList as $s) {
                        $key = $s->id ?? ($s->campo . '_' . $s->descripcion);
                        if (!in_array($key, $used)) {
                            $finalRows[] = ['label' => $s->campo, 'value' => $s->descripcion];
                        }
                    }
                @endphp

                @forelse($finalRows as $fr)
                <tr>
                    <td style="min-width:200px;font-weight:600">{{ $fr['label'] }}</td>
                    <td></td>
                    <td>{{ $fr['value'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">Aún no tiene especificaciones</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
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
