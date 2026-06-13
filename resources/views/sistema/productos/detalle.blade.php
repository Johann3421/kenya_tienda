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

    .spec-card {
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        cursor: default;
    }
    .spec-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        border-color: #ddd;
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
        font-family: 'Inter', sans-serif;
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
                $altText = "Imagen de " . $producto->display_name;
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

                $isDesktopOrWorkstation = !$isMonitor && !$isToner && $producto->modelo && $producto->modelo->categoria_id && in_array($producto->modelo->categoria_id, [1, 3]);

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

                    if (in_array(strtoupper($text), ['NULL', 'N/A', '-', 'NO ESPECIFICADO', 'NO APLICA'], true)) {
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
                    if ($isDesktopOrWorkstation) {
                        $topOrdered = [
                            (object) ['campo' => 'Formato', 'descripcion' => $getSpecValue(['/formato|factor|tipo de suministro|suministro/']) ?? $getProductValue(['Tipo de suministro']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'Procesador', 'descripcion' => $getSpecValue(['/procesador|cpu|intel|amd/']) ?? $getProductValue(['procesador']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'Chipset', 'descripcion' => $getSpecValue(['/chipset/']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'Controlador de Video', 'descripcion' => $getSpecValue(['/gr[aá]f|gpu|tarjeta de video|tarjeta grafica|tarjeta gráfica|video/']) ?? $getProductValue(['tarjetavideo']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'Memoria Ram', 'descripcion' => $getSpecValue(['/memoria|ram/']) ?? $getProductValue(['ram']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'Almacenamiento', 'descripcion' => $getSpecValue(['/almacenamiento|disco|hdd|ssd|nvme|storage/']) ?? $getProductValue(['almacenamiento']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'Fuente de Poder', 'descripcion' => $getSpecValue(['/fuente|psu|power supply/']) ?? 'No especificado', 'descripcion2' => ''],
                        ];
                    } else {
                        $topOrdered = [
                            (object) ['campo' => 'Procesador', 'descripcion' => $getSpecValue(['/procesador|cpu|intel|amd/']) ?? $getProductValue(['procesador']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'Memoria Ram', 'descripcion' => $getSpecValue(['/memoria|ram/']) ?? $getProductValue(['ram']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'Almacenamiento', 'descripcion' => $getSpecValue(['/almacenamiento|disco|hdd|ssd|nvme|storage/']) ?? $getProductValue(['almacenamiento']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'Gráficos', 'descripcion' => $getSpecValue(['/gr[aá]f|gpu|tarjeta de video|tarjeta grafica|tarjeta gráfica|video/']) ?? $getProductValue(['tarjetavideo']) ?? 'No especificado', 'descripcion2' => ''],
                        ];
                    }
                }
            @endphp

            <div style="margin-bottom:24px;">
                <h2 style="font-weight:800; font-size:32px; color:#1a1a1a; margin-bottom:6px; line-height:1.2;">
                    {{ $producto->display_name }}
                </h2>
                <div style="display:flex; align-items:center; flex-wrap:wrap; gap:8px; font-size:13px; color:#777;">
                    @if($producto->modelo)
                        <span style="display:inline-flex; align-items:center; gap:4px; background:#f3f3f3; padding:3px 10px; border-radius:20px;">
                            <i class="fa-solid fa-cube" style="font-size:10px;"></i> {{ $producto->modelo->descripcion ?? $producto->modelo->nombre }}
                        </span>
                    @endif
                    @if($producto->getCategoria)
                        <span style="display:inline-flex; align-items:center; gap:4px; background:#f3f3f3; padding:3px 10px; border-radius:20px;">
                            <i class="fa-solid fa-tag" style="font-size:10px;"></i> {{ $producto->getCategoria->nombre }}
                        </span>
                    @endif
                    @if($producto->nro_parte)
                        <span style="display:inline-flex; align-items:center; gap:4px; background:#f3f3f3; padding:3px 10px; border-radius:20px;">
                            <i class="fa-solid fa-hashtag" style="font-size:10px;"></i> {{ $producto->nro_parte }}
                        </span>
                    @endif
                </div>
            </div>

            <div style="text-align:right; margin-bottom:10px;">
                <button id="design-toggle" type="button" style="background:none; border:1px solid #ccc; border-radius:20px; padding:4px 14px; font-size:12px; color:#666; cursor:pointer; transition:all 0.15s;">
                    <i class="fa-solid fa-rotate" style="margin-right:4px;"></i> Ver diseño anterior
                </button>
            </div>

            <div id="design-v2">

            <div class="carousel-descripcion mb-4" style="padding:0;">
                <div class="row g-4">
                    @if($isMonitor)
                        @forelse($especificacionesResumen as $espec)
                        <div class="col-6 col-md-4">
                            <div class="spec-card" style="background:#fff; border:1px solid #eee; border-radius:12px; padding:18px 20px; height:100%;">
                                <div style="font-size:11px; color:#aaa; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">{{ $espec->campo }}</div>
                                <div style="font-size:15px; font-weight:700; color:#1a1a1a; line-height:1.3;">{{ $espec->descripcion }}</div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12"><p class="text-center text-muted py-3">Aún no tiene especificaciones</p></div>
                        @endforelse
                    @elseif($isToner)
                        @foreach($topOrdered as $espec)
                        <div class="col-6 col-md-3">
                            <div class="spec-card" style="background:#fff; border:1px solid #eee; border-radius:12px; padding:18px 20px; height:100%;">
                                <div style="font-size:11px; color:#aaa; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">{{ $espec->campo }}</div>
                                <div style="font-size:15px; font-weight:700; color:#1a1a1a; line-height:1.3;">{{ $espec->descripcion }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        @php $specCount = count($topOrdered); @endphp
                        @foreach($topOrdered as $espec)
                            @php
                                $colClass = $specCount <= 4 ? 'col-6 col-md-3' : 'col-6 col-md-4 col-lg-3';
                                $value = $espec->descripcion;
                                $icon = '';
                                $campoLower = strtolower($espec->campo);
                                if (str_contains($campoLower, 'procesador')) $icon = '🖥️';
                                elseif (str_contains($campoLower, 'memoria') || str_contains($campoLower, 'ram')) $icon = '🧠';
                                elseif (str_contains($campoLower, 'almacenamiento')) $icon = '💾';
                                elseif (str_contains($campoLower, 'gráfico') || str_contains($campoLower, 'video') || str_contains($campoLower, 'grafico')) $icon = '🎮';
                                elseif (str_contains($campoLower, 'chipset')) $icon = '⚡';
                                elseif (str_contains($campoLower, 'fuente')) $icon = '🔌';
                                elseif (str_contains($campoLower, 'formato')) $icon = '📦';
                                if ($isDesktopOrWorkstation && $campoLower === 'procesador' && !empty($producto->descripcion_2)) {
                                    $value = $espec->descripcion . ' · ' . $producto->descripcion_2;
                                }
                            @endphp
                            <div class="{{ $colClass }}">
                                <div class="spec-card" style="background:#fff; border:1px solid #eee; border-radius:12px; padding:18px 20px; height:100%;">
                                    <div style="font-size:11px; color:#aaa; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">
                                        @if($icon)<span style="margin-right:4px;">{{ $icon }}</span>@endif{{ $espec->campo }}
                                    </div>
                                    <div style="font-size:15px; font-weight:700; color:#1a1a1a; line-height:1.3;">
                                        {{ $value }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="mt-3" style="font-size:11px; color:#bbb; padding-left:2px;">* Las imágenes e información incluidas son referenciales; pueden variar por versiones, por favor consultar a su vendedor.</div>
            </div>
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; margin:20px 0 24px; padding:16px 20px; background:#fafafa; border:1px solid #eee; border-radius:12px;">
                <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                    @foreach($producto->getGarantia->skip(0)->take(1) as $gar)
                    <div style="display:flex; align-items:center; gap:6px;">
                        <img alt="Garantía" src="https://img.icons8.com/fluency/2x/guarantee.png" style="width:28px; height:28px;">
                        <span style="font-size:14px; font-weight:600; color:#222;">Garantía {{ $gar->garantia }} meses</span>
                    </div>
                    @endforeach
                    <span style="color:#ddd;">|</span>
                    <div style="display:flex; align-items:center; gap:6px;">
                        <img alt="Ficha técnica" src="https://img.icons8.com/ios-filled/2x/wordbook.png" style="width:22px; height:22px; filter:invert(0%) sepia(0%) saturate(7469%) hue-rotate(214deg) brightness(91%) contrast(107%);">
                        @if($producto->ficha_tecnica)
                            @php
                                $fichaUrl = str_starts_with($producto->ficha_tecnica, 'http')
                                    ? $producto->ficha_tecnica
                                    : asset('/storage/' . $producto->ficha_tecnica);
                            @endphp
                            <a href="{{ $fichaUrl }}" target="_blank" style="font-size:14px; font-weight:600;">Ficha técnica <iconify-icon icon="bx:download"></iconify-icon></a>
                        @else
                            <span style="font-size:14px; color:#999;">Ficha técnica no disponible</span>
                        @endif
                    </div>
                </div>
                <a target="_blank" href="https://wa.me/+51958021778?text=!Quiero Informacion sobre el producto" class="btn btn-success" style="display:flex; align-items:center; gap:6px; font-weight:600; white-space:nowrap;">
                    <i class="bx bxl-whatsapp" style="font-size:20px;"></i> Contactar
                </a>
            </div>
        </div>{{-- close #design-v2 --}}

        <div style="max-width:100%;">
            <div style="background:#fff; border:1px solid #eee; border-radius:12px; overflow:hidden;">
                <div style="background:#ee7c31; padding:16px 22px; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-clipboard-list" style="color:#1a1a1a; font-size:16px;"></i>
                    <span style="font-weight:700; font-size:16px; color:#fff;">Especificaciones Técnicas</span>
                </div>

                @if($isMonitor)
                    @php $monitorRows = $especificaciones->filter(fn($e) => trim($e->descripcion ?? '') !== ''); @endphp
                    @if($monitorRows->isNotEmpty())
                        @foreach($monitorRows as $index => $espec)
                            <div style="display:flex; padding:13px 22px; {{ $index % 2 === 0 ? 'background:#fff;' : 'background:#fafafa;' }} border-bottom:1px solid #f3f3f3;">
                                <div style="flex:0 0 200px; font-weight:600; color:#444; font-size:14px;">{{ $espec->campo }}</div>
                                <div style="flex:1; color:#1a1a1a; font-size:14px;">{{ $espec->descripcion }}</div>
                            </div>
                        @endforeach
                    @else
                        <div style="padding:20px; text-align:center; color:#999;">Aún no tiene especificaciones</div>
                    @endif
                    <div style="display:flex; padding:13px 22px; background:#fff; border-bottom:1px solid #f3f3f3;">
                        <div style="flex:0 0 200px; font-weight:600; color:#444; font-size:14px;">Número de Parte</div>
                        <div style="flex:1; color:#1a1a1a; font-size:14px;">{{ $producto->nro_parte ?? 'No especificado' }}</div>
                    </div>
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
                    @php $tonerRows = array_filter($finalRows, fn($r) => ($r['value'] ?? null) !== null); @endphp
                    @if(!empty($tonerRows))
                        @php $ti = 0; @endphp
                        @foreach($tonerRows as $fr)
                            <div style="display:flex; padding:13px 22px; {{ $ti % 2 === 0 ? 'background:#fff;' : 'background:#fafafa;' }} border-bottom:1px solid #f3f3f3;">
                                <div style="flex:0 0 200px; font-weight:600; color:#444; font-size:14px;">{{ $fr['label'] }}</div>
                                <div style="flex:1; color:#1a1a1a; font-size:14px;">{{ $fr['value'] ?? '—' }}</div>
                            </div>
                            @php $ti++; @endphp
                        @endforeach
                    @else
                        <div style="padding:20px; text-align:center; color:#999;">Aún no tiene especificaciones</div>
                    @endif
                @else
                    @php
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
                    @php $pcRows = array_filter($finalRows, fn($r) => ($r['value'] ?? null) !== null); @endphp
                    @if(!empty($pcRows))
                        @php $pi = 0; @endphp
                        @foreach($pcRows as $fr)
                            <div style="display:flex; padding:13px 22px; {{ $pi % 2 === 0 ? 'background:#fff;' : 'background:#fafafa;' }} border-bottom:1px solid #f3f3f3;">
                                <div style="flex:0 0 200px; font-weight:600; color:#444; font-size:14px;">{{ $fr['label'] }}</div>
                                <div style="flex:1; color:#1a1a1a; font-size:14px;">{{ $fr['value'] ?? '—' }}</div>
                            </div>
                            @php $pi++; @endphp
                        @endforeach
                    @else
                        <div style="padding:20px; text-align:center; color:#999;">Aún no tiene especificaciones</div>
                    @endif
                @endif
            </div>
        </div>
        {{-- ===== DISEÑO ANTERIOR ===== --}}
        <div id="design-v1" style="display:none;">
            <div class="carousel-descripcion mb-3 row" style="padding: 15px;">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                @if($isMonitor)
                                    @forelse($especificacionesResumen as $espec)
                                    <tr><td style="min-width:160px;font-weight:600">{{ $espec->campo }}</td><td>: {{ $espec->descripcion }}</td></tr>
                                    @empty
                                    <tr><td colspan="2" class="text-center text-muted">Aún no tiene especificaciones</td></tr>
                                    @endforelse
                                @elseif($isToner)
                                    @foreach($topOrdered as $espec)
                                    <tr><td style="min-width:160px;font-weight:700">{{ $espec->campo }}</td><td>: {{ $espec->descripcion }}</td></tr>
                                    @endforeach
                                @else
                                    @forelse($topOrdered as $espec)
                                    <tr>
                                        <td style="min-width:160px;font-weight:700;vertical-align:top">{{ $espec->campo }}</td>
                                        <td>
                                            @if($isDesktopOrWorkstation)
                                            <table style="width:100%;border-collapse:collapse;"><tr>
                                                <td style="padding:0;vertical-align:top;width:50%;">: {{ $espec->descripcion }}</td>
                                                <td style="padding:0 0 0 14px;vertical-align:top;width:50%;"><span style="font-size:0.85em;color:#999;font-style:italic;">
                                                    @if(strtolower($espec->campo) === 'procesador')Descripción 2: {{ $producto->descripcion_2 ?: 'Vacío' }}@else Descripción 2: Vacío @endif
                                                </span></td>
                                            </tr></table>
                                            @else
                                            : {{ $espec->descripcion }}
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="text-center text-muted">Aún no tiene especificaciones</td></tr>
                                    @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 mt-2" style="font-size: 11px;">* Las imágenes e información incluidas son referenciales; pueden variar por versiones, por favor consultar a su vendedor.</div>
            </div>
            <div>
                <table class="table"><thead><tr>
                    <th scope="col-12"><div class="grid-icons__item grid-icon">
                        @foreach($producto->getGarantia->skip(0)->take(1) as $gar)
                        <div>TIEMPO DE GARANTIA: {{$gar->garantia}} MESES @endforeach
                        <img alt="Garantía" srcset="https://img.icons8.com/fluency/2x/guarantee.png 3x"><br>FICHA TECNICA:
                        @if($producto->ficha_tecnica)
                            @php $fichaUrl = str_starts_with($producto->ficha_tecnica, 'http') ? $producto->ficha_tecnica : asset('/storage/'.$producto->ficha_tecnica); @endphp
                            <a href="{{ $fichaUrl }}" target="_blank">PDF <iconify-icon icon="bx:download"></iconify-icon></a>
                        @else <span class="text-muted">No disponible</span> @endif
                        <img alt="Ficha" srcset="https://img.icons8.com/ios-filled/2x/wordbook.png 3x" style="filter:invert(0%) sepia(0%) saturate(7469%) hue-rotate(214deg) brightness(91%) contrast(107%);">
                        </div></div>
                    </th>
                    <th><a target="_blank" href="https://wa.me/+51958021778?text=!Quiero Informacion sobre el producto" class="btn btn-block btn-success"><i class="bx bxl-whatsapp" style="font-size:24px;vertical-align:bottom;"></i> Contactar</a></th>
                </tr></thead></table>
            </div>
        </div>{{-- close #design-v1 --}}

        <script>
            (function(){
                var btn = document.getElementById('design-toggle');
                var v1  = document.getElementById('design-v1');
                var v2  = document.getElementById('design-v2');
                if (!btn || !v1 || !v2) return;
                var showingNew = true;
                btn.addEventListener('click', function(){
                    if (showingNew) {
                        v1.style.display = ''; v2.style.display = 'none';
                        btn.innerHTML = '<i class="fa-solid fa-rotate" style="margin-right:4px;"></i> Ver diseño nuevo';
                    } else {
                        v1.style.display = 'none'; v2.style.display = '';
                        btn.innerHTML = '<i class="fa-solid fa-rotate" style="margin-right:4px;"></i> Ver diseño anterior';
                    }
                    showingNew = !showingNew;
                });
                btn.addEventListener('mouseenter', function(){ btn.style.borderColor = '#ee7c31'; btn.style.color = '#ee7c31'; });
                btn.addEventListener('mouseleave', function(){ btn.style.borderColor = '#ccc'; btn.style.color = '#666'; });
            })();
        </script>

    </div>{{-- close col-lg-8 --}}
</div>{{-- close row --}}

<br>
@endsection

@section('js')
<script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
@endsection
