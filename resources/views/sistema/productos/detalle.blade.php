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
                    stripos($producto->modelo->descripcion ?? '', 'tonner') !== false ||
                    stripos($producto->modelo->descripcion ?? '', 'toner') !== false
                );

                if (!$isTonner) {
                    $categoriaNombre = optional($producto->getCategoria)->nombre ?? '';
                    $isTonner = stripos($categoriaNombre, 'tonner') !== false
                        || stripos($categoriaNombre, 'toner') !== false
                        || stripos($producto->nombre ?? '', 'tonner') !== false
                        || stripos($producto->nombre ?? '', 'toner') !== false;
                }

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

                $isToner = false;
                if ($producto->modelo && $producto->modelo->id == 10) {
                    $isToner = true;
                }
                if (!$isToner) {
                    $modeloNombre = optional($producto->modelo)->descripcion ?? '';
                    $categoriaNombre = optional($producto->getCategoria)->nombre ?? '';
                    $productoNombre = $producto->nombre ?? '';

                    $isToner = stripos($modeloNombre, 'tonner') !== false
                        || stripos($modeloNombre, 'toner') !== false
                        || stripos($categoriaNombre, 'tonner') !== false
                        || stripos($categoriaNombre, 'toner') !== false
                        || stripos($productoNombre, 'tonner') !== false
                        || stripos($productoNombre, 'toner') !== false;
                }

                // Normalizar y filtrar especificaciones válidas
                $specsList = [];
                foreach ($especificaciones as $s) {
                    $desc = trim(strtolower($s->descripcion ?? ''));
                    if ($desc === '') continue;
                    $specsList[] = $s;
                }

                $findSpec = function($pattern) use ($specsList) {
                    foreach ($specsList as $sp) {
                        if (preg_match($pattern, strtolower($sp->campo))) return $sp;
                    }
                    return null;
                };

                $cleanValue = function($value) {
                    if ($value === null) {
                        return null;
                    }

                    $text = trim((string) $value);
                    if ($text === '') {
                        return null;
                    }

                    return $text;
                };

                $getProductValue = function(array $fields) use ($producto, $cleanValue) {
                    foreach ($fields as $field) {
                        $value = $cleanValue($producto->{$field} ?? null);
                        if ($value !== null) {
                            return $value;
                        }
                    }
                    return null;
                };

                $getSpecValue = function(array $patterns) use ($findSpec, $cleanValue) {
                    foreach ($patterns as $pattern) {
                        $found = $findSpec($pattern);
                        if ($found) {
                            $value = $cleanValue($found->descripcion ?? null);
                            if ($value !== null) {
                                return $value;
                            }
                        }
                    }
                    return null;
                };

                // Top summary: para PCs (no monitores) ordenar Procesador, Memoria, Almacenamiento, Graficos
                $topOrdered = [];
                if ($isToner) {
                    $topOrdered = [
                        (object) [
                            'campo' => 'Tipo de suministro',
                            'descripcion' => $getSpecValue(['/tipo de suministro|suministro|formato/'])
                                ?? $getProductValue(['Tipo de suministro'])
                                ?? 'No especificado',
                        ],
                        (object) [
                            'campo' => 'Color',
                            'descripcion' => $getSpecValue(['/^color$/', '/color/'])
                                ?? $getProductValue(['Color'])
                                ?? 'No especificado',
                        ],
                        (object) [
                            'campo' => 'Rendimiento',
                            'descripcion' => $getSpecValue(['/rendimiento|p[aá]ginas|paginas/'])
                                ?? $getProductValue(['Rendimiento'])
                                ?? 'No especificado',
                        ],
                        (object) [
                            'campo' => 'Garantia',
                            'descripcion' => $getSpecValue(['/garant[ií]a|g\.\s*f/'])
                                ?? $getProductValue(['garantia_de_fabrica', 'Garantia'])
                                ?? 'No especificado',
                        ],
                    ];
                } elseif (!$isMonitor) {
                    $topOrdered = [
                        (object) [
                            'campo' => 'Procesador',
                            'descripcion' => $getSpecValue(['/procesador|cpu|intel|amd/'])
                                ?? $getProductValue(['procesador'])
                                ?? 'No especificado',
                        ],
                        (object) [
                            'campo' => 'Memoria Ram',
                            'descripcion' => $getSpecValue(['/memoria|ram/'])
                                ?? $getProductValue(['ram'])
                                ?? 'No especificado',
                        ],
                        (object) [
                            'campo' => 'Almacenamiento',
                            'descripcion' => $getSpecValue(['/almacenamiento|disco|hdd|ssd|nvme|storage/'])
                                ?? $getProductValue(['almacenamiento'])
                                ?? 'No especificado',
                        ],
                        (object) [
                            'campo' => 'Gráficos',
                            'descripcion' => $getSpecValue(['/gr[aá]f|gpu|tarjeta de video|tarjeta grafica|tarjeta gráfica|video/'])
                                ?? $getProductValue(['tarjetavideo'])
                                ?? 'No especificado',
                        ],
                    ];
                }
            @endphp

            <h2 class="" style="font-weight: bold; font-size: 36px; font-family: Arial">
                @if($isMonitor) MONITOR @elseif($isToner) TONER @endif {{ $producto->nombre }}
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
                @if($isMonitor)
                    @forelse($especificaciones as $espec)
                    <tr>
                        <td style="min-width:200px;font-weight:600">{{ $espec->campo }}</td>
                        <td></td>
                        <td>{{ $espec->descripcion }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Aún no tiene especificaciones</td>
                    </tr>
                    @endforelse

                    <tr>
                        <td style="min-width:200px;font-weight:600">Número de Parte</td>
                        <td></td>
                        <td>{{ $producto->nro_parte ?? 'No especificado' }}</td>
                    </tr>
                @elseif($isToner)
                    @php
                        $finalRows = [
                            ['label' => 'Numero de Parte', 'value' => $getProductValue(['nro_parte', 'Número de parte']) ?? $getSpecValue(['/n[uú]mero de parte|nro\.?\s*parte|nro\.?\s*de\s*parte/'])],
                            ['label' => 'Modelo', 'value' => $getSpecValue(['/^modelo$/', '/modelo/']) ?? $getProductValue(['Modelo']) ?? optional($producto->modelo)->descripcion],
                            ['label' => 'Tipo de suministro', 'value' => $getSpecValue(['/tipo de suministro|suministro|formato/']) ?? $getProductValue(['Tipo de suministro'])],
                            ['label' => 'Color', 'value' => $getSpecValue(['/^color$/', '/color/']) ?? $getProductValue(['Color'])],
                            ['label' => 'Descripción', 'value' => $getSpecValue(['/descrip/']) ?? $getProductValue(['Descripción'])],
                            ['label' => 'Rendimiento', 'value' => $getSpecValue(['/rendimiento|p[aá]ginas|paginas/']) ?? $getProductValue(['Rendimiento'])],
                            ['label' => 'Garantia', 'value' => $getSpecValue(['/garant[ií]a|g\.\s*f/']) ?? $getProductValue(['garantia_de_fabrica', 'Garantia'])],
                            ['label' => 'Sistema RAEE', 'value' => $getSpecValue(['/raee|manejo/']) ?? $getProductValue(['Sistema RAEE'])],
                            ['label' => 'Certificaciones', 'value' => $getSpecValue(['/certific|iso/']) ?? $getProductValue(['Certificaciones'])],
                            ['label' => 'Empaque', 'value' => $getSpecValue(['/empaque|caja\s*x/']) ?? $getProductValue(['Empaque'])],
                            ['label' => 'Unidad', 'value' => $getSpecValue(['/^unidad$/', '/unidad\s*caja|caja\s*x/'])],
                            ['label' => 'Dimensiones', 'value' => $getSpecValue(['/dimensi/']) ?? $getProductValue(['Dimensiones'])],
                        ];
                    @endphp

                    @forelse($finalRows as $fr)
                    <tr>
                        <td style="min-width:200px;font-weight:600">{{ $fr['label'] }}</td>
                        <td></td>
                        <td>{{ $fr['value'] ?? 'No especificado' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Aún no tiene especificaciones</td>
                    </tr>
                    @endforelse
                @else
                    @php
                        // Datos que hoy llegan del sync API:
                        // procesador, ram, almacenamiento, graficos, conectividad, conectividad_wlan,
                        // conectividad_usb, video_vga, video_hdmi, sistema_operativo,
                        // suite_ofimatica, garantia_de_fabrica (+ tarjetavideo legacy)
                        $finalRows = [
                            ['label' => 'Numero de Parte', 'value' => $getProductValue(['nro_parte', 'Número de parte'])],
                            ['label' => 'Modelo', 'value' => optional($producto->modelo)->nombre ?? optional($producto->modelo)->descripcion ?? $getProductValue(['Modelo'])],
                            ['label' => 'Formato', 'value' => $getSpecValue(['/formato|factor|tipo de suministro|suministro/']) ?? $getProductValue(['Tipo de suministro'])],
                            ['label' => 'Procesador', 'value' => $getSpecValue(['/procesador|cpu|intel|amd/']) ?? $getProductValue(['procesador'])],
                            ['label' => 'Memoria Ram', 'value' => $getSpecValue(['/memoria|ram/']) ?? $getProductValue(['ram'])],
                            ['label' => 'Almacenamiento', 'value' => $getSpecValue(['/almacenamiento|disco|hdd|ssd|nvme|storage/']) ?? $getProductValue(['almacenamiento'])],
                            ['label' => 'Sistema Operativo', 'value' => $getSpecValue(['/sistema operativo|\bos\b|windows|linux/']) ?? $getProductValue(['sistema_operativo'])],
                            ['label' => 'Suite Ofimática', 'value' => $getSpecValue(['/ofim[aá]tica|office|suite/']) ?? $getProductValue(['suite_ofimatica'])],
                            ['label' => 'Gráficos', 'value' => $getSpecValue(['/gr[aá]f|gpu|tarjeta de video|tarjeta grafica|tarjeta gráfica|video/']) ?? $getProductValue(['tarjetavideo'])],
                            ['label' => 'Sonido', 'value' => $getSpecValue(['/sonido|audio/'])],
                            ['label' => 'Chipset', 'value' => $getSpecValue(['/chipset/'])],
                            ['label' => 'Lan', 'value' => $getSpecValue(['/\blan\b|ethernet/']) ?? $getProductValue(['conectividad'])],
                            ['label' => 'Wlan', 'value' => $getSpecValue(['/\bwlan\b|wifi|wireless/']) ?? $getProductValue(['conectividad_wlan'])],
                            ['label' => 'Puertos Mínimos', 'value' => $getSpecValue(['/puertos|minimo|m[ií]nimo/']) ?? $getProductValue(['conectividad_usb'])],
                            ['label' => 'Slot de Expansión', 'value' => $getSpecValue(['/slot|expansi|pci|m\.2/'])],
                            ['label' => 'Fuente de Poder', 'value' => $getSpecValue(['/fuente|psu|power supply/'])],
                            ['label' => 'Garantia', 'value' => $getSpecValue(['/garant[ií]a de f[aá]brica|garant[ií]a|garantia/']) ?? $getProductValue(['garantia_de_fabrica', 'Garantia'])],
                            ['label' => 'Empaque', 'value' => $getSpecValue(['/empaque|packag/']) ?? $getProductValue(['Empaque'])],
                            ['label' => 'Certificaciones', 'value' => $getSpecValue(['/certific|iso/']) ?? $getProductValue(['Certificaciones'])],
                            ['label' => 'Accesorios y Otros', 'value' => $getSpecValue(['/accesorio|otros|observaciones|incluye/'])],
                        ];
                    @endphp

                    @forelse($finalRows as $fr)
                    <tr>
                        <td style="min-width:200px;font-weight:600">{{ $fr['label'] }}</td>
                        <td></td>
                        <td>{{ $fr['value'] ?? 'No especificado' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Aún no tiene especificaciones</td>
                    </tr>
                    @endforelse
                @endif
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
