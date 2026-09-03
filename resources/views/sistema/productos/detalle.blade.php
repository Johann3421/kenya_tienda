@extends('layouts.landing')

@php
    $catNombre = optional($producto->getCategoria)->nombre ?? 'Computadora';
    $catPrefix = (stripos($catNombre, 'computadora') !== false || stripos($catNombre, 'pc') !== false)
        ? 'Computadora de Escritorio PC'
        : (stripos($catNombre, 'laptop') !== false ? 'Laptop Portátil' : $catNombre);

    $seoTitle = $catPrefix . ' ' . $producto->display_name . ($producto->nro_parte ? ' (PN: ' . $producto->nro_parte . ')' : '') . ' | KENYA Perú';
    $seoDesc = 'Computadora ' . $producto->display_name . ' en Perú. Especificaciones: ' . ($producto->procesador ? 'Procesador: ' . $producto->procesador . ', ' : '') . ($producto->ram ? 'RAM: ' . $producto->ram . ', ' : '') . ($producto->almacenamiento ? 'Almacenamiento: ' . $producto->almacenamiento . '. ' : '') . 'Venta de computadoras, PCs de escritorio y laptops con 36 meses de garantía On-Site.';
    $seoImage = $producto->imagen_1 ? (str_starts_with($producto->imagen_1, 'http') ? $producto->imagen_1 : asset('storage/' . $producto->imagen_1)) : asset('theme/images/kenya.png');
    $seoUrl = route('cotizar.detalle', $producto->id);
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDesc)
@section('meta_keywords', 'computadoras, pcs, pcs de escritorio, computadora de escritorio, laptops, equipos de computo, ' . strtolower($producto->display_name) . ', ' . strtolower($producto->nro_parte ?? '') . ', kenya peru')
@section('canonical', $seoUrl)

@section('og_type', 'product')
@section('og_title', $seoTitle)
@section('og_description', $seoDesc)
@section('og_image', $seoImage)
@section('og_url', $seoUrl)

@section('schema_org')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ e($producto->display_name) }}",
  "image": ["{{ e($seoImage) }}"],
  "description": "{{ e($seoDesc) }}",
  "sku": "{{ e($producto->nro_parte ?? $producto->id) }}",
  "mpn": "{{ e($producto->nro_parte ?? $producto->id) }}",
  "brand": {
    "@type": "Brand",
    "name": "KENYA Technology"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ e($seoUrl) }}",
    "priceCurrency": "USD",
    "price": "{{ $producto->precio_especial ? number_format($producto->precio_especial, 2, '.', '') : '0.00' }}",
    "availability": "{{ $producto->pagina_web === 'SI' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
    "seller": {
      "@type": "Organization",
      "name": "KENYA Technology"
    }
  }
}
</script>
@endsection

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

    /* Specs Block Container */
    .specs-table-container {
        background: #fff;
        border-top: 1px solid #EAEAEA;
        padding: 0;
        margin-bottom: 0;
    }

    /* Mobile: Layout vertical (Icon + Label in top row, Value below with ~4px margin) */
    .spec-row-item {
        display: grid;
        grid-template-columns: 20px 1fr;
        grid-template-rows: auto auto;
        column-gap: 8px;
        row-gap: 4px;
        padding: 12px 0;
        border-bottom: 1px solid #EAEAEA;
        box-sizing: border-box;
        transition: background-color 0.15s ease;
    }
    .spec-row-item:hover {
        background-color: #fafbfc;
    }
    .spec-col-icon {
        grid-column: 1;
        grid-row: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }
    .spec-icon {
        color: #b97737;
        font-size: 15px;
        line-height: 1;
        text-align: center;
    }
    .spec-col-label {
        grid-column: 2;
        grid-row: 1;
        display: flex;
        align-items: center;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #9c9c9c;
        line-height: 1.2;
    }
    .spec-col-value {
        grid-column: 2;
        grid-row: 2;
        font-size: 14px;
        font-weight: 800;
        color: #111111;
        line-height: 1.35;
        margin-top: 2px;
    }

    /* Desktop (>= 768px): Layout horizontal 3 columnas fijas (ícono | label | valor) */
    @media (min-width: 768px) {
        .spec-row-item {
            grid-template-columns: 24px 160px 1fr;
            grid-template-rows: auto;
            column-gap: 16px;
            row-gap: 0;
            align-items: center;
            padding: 18px 0;
        }
        .spec-col-icon {
            grid-column: 1;
            grid-row: 1;
            width: 24px;
            height: 24px;
        }
        .spec-icon {
            font-size: 16px;
        }
        .spec-col-label {
            grid-column: 2;
            grid-row: 1;
            font-size: 11px;
            letter-spacing: 0.8px;
            color: #9c9c9c;
        }
        .spec-col-value {
            grid-column: 3;
            grid-row: 1;
            font-size: 15px;
            font-weight: 800;
            color: #111111;
            margin-top: 0;
        }
    }

    /* Disclaimer */
    .specs-disclaimer {
        margin-top: 22px;
        margin-bottom: 26px;
        font-size: 11px;
        color: #999999;
        line-height: 1.5;
        font-style: italic;
        padding-left: 2px;
    }

    /* Desktop Action Card (Matching Image 2 exactly) */
    .desktop-action-card {
        display: none;
    }

    @media (min-width: 768px) {
        .desktop-action-card {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: #ffffff;
            border: 1px solid #f3e5d8;
            border-radius: 14px;
            padding: 16px 24px;
            margin-bottom: 28px;
        }

        .desktop-ficha-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none !important;
            color: #8e623a !important;
            cursor: pointer;
            transition: opacity 0.15s ease;
        }
        .desktop-ficha-btn:hover {
            opacity: 0.85;
        }

        .desktop-b2b-box {
            border: 2px solid #ee7c31;
            border-radius: 8px;
            background: #ffffff;
            padding: 9px 20px;
            display: inline-flex;
            align-items: center;
            gap: 14px;
        }

        .desktop-wsp-btn {
            background: #1ebd5b !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 8px;
            padding: 10px 22px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(30, 189, 91, 0.25);
            text-decoration: none !important;
            transition: background-color 0.15s ease, box-shadow 0.15s ease;
        }
        .desktop-wsp-btn:hover {
            background: #18a850 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(30, 189, 91, 0.35);
        }
    }

    /* Mobile Buttons Stack */
    .mobile-actions-stack {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 26px;
    }
    .mobile-action-btn {
        width: 100%;
        height: 48px;
        min-height: 48px;
        padding: 0 18px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
        box-sizing: border-box;
        transition: all 0.15s ease;
    }
    .mobile-action-white {
        background: #ffffff;
        color: #1a1a1a;
        border: 1px solid #e0d7cf;
    }
    .mobile-action-white:hover {
        background: #f8f9fa;
        color: #000;
        border-color: #ccc;
    }
    .mobile-action-wsp {
        background: #25D366 !important;
        color: #ffffff !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
    }
    .mobile-action-wsp:hover {
        background: #20ba59 !important;
        color: #ffffff !important;
    }

    @media (min-width: 768px) {
        .mobile-actions-stack {
            display: none !important;
        }
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

            @php
                $has360 = false;
                $totalFrames = 0;
                $modelo360Id = $producto->modelo_id ?? null;
                $url360Prefix = '';
                
                // Allow disabling 3D via URL parameter ?3d=0
                if (request('3d') !== '0') {
                    // 1. Check if it's a dynamic 360 model uploaded via admin
                    if ($modelo360Id && \Illuminate\Support\Facades\Storage::exists('public/modelos_360/' . $modelo360Id)) {
                        $files = \Illuminate\Support\Facades\Storage::files('public/modelos_360/' . $modelo360Id);
                        $totalFrames = count($files);
                        if ($totalFrames > 0) {
                            $has360 = true;
                            $url360Prefix = asset('storage/modelos_360/' . $modelo360Id . '/');
                        }
                    }
                    
                    // 2. Fallback to static "genwork" directory we pushed earlier if no dynamic model exists
                    if (!$has360) {
                        $isGenwork = stripos($producto->display_name ?? $producto->nombre, 'genwork') !== false 
                                  || stripos(optional($producto->modelo)->descripcion, 'genwork') !== false;
                        
                        if ($isGenwork) {
                            $has360 = true;
                            $totalFrames = 36;
                            $url360Prefix = asset('Diseño_3d_case/XTQ-209_'); // Base without the number
                        }
                    }
                }
            @endphp

            @if($has360)
                <div id="container-3d-view">
                    <div class="product-image-container 3d-viewer" id="viewer-3d" style="cursor: ew-resize; user-select: none; position: relative;">
                        @php
                            // First image load depends on if it's dynamic or static
                            $firstImage = stripos($url360Prefix, 'Diseño_3d_case') !== false ? $url360Prefix . '1.jpg' : $url360Prefix . '/1.jpg';
                        @endphp
                        <img id="img-3d" src="{{ $firstImage }}" class="img-fluid w-100" alt="Vista 3D 360°" style="border-radius:8px;">
                        
                        <div id="v360-menu-btns" style="margin-top:15px; display:flex; justify-content:center; align-items:center; color:#555;">
                            <div class="v360-navigate-btns" style="display:flex; gap:25px; background:#f8f9fa; padding:12px 25px; border-radius:30px; border:1px solid #ddd; box-shadow:0 4px 6px rgba(0,0,0,0.05);">
                                <div class="v360-menu-btns" id="btn-play" style="cursor:pointer; font-size:18px; transition:color 0.2s;" onmouseover="this.style.color='#ee7c31'" onmouseout="this.style.color=''"><i class="fa fa-play"></i></div>
                                <div class="v360-menu-btns" id="btn-drag" style="cursor:pointer; font-size:18px; color:#ee7c31;" title="Arrastrar"><i class="fa fa-hand-paper"></i></div>
                                <div class="v360-menu-btns" id="btn-prev" style="cursor:pointer; font-size:18px; transition:color 0.2s;" onmouseover="this.style.color='#ee7c31'" onmouseout="this.style.color=''"><i class="fa fa-chevron-left"></i></div>
                                <div class="v360-menu-btns" id="btn-next" style="cursor:pointer; font-size:18px; transition:color 0.2s;" onmouseover="this.style.color='#ee7c31'" onmouseout="this.style.color=''"><i class="fa fa-chevron-right"></i></div>
                                <div class="v360-menu-btns" id="btn-reset" style="cursor:pointer; font-size:18px; transition:color 0.2s;" onmouseover="this.style.color='#ee7c31'" onmouseout="this.style.color=''"><i class="fa fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" id="btn-toggle-view" class="btn btn-outline-secondary btn-sm" style="border-radius:20px;">
                            <i class="fas fa-image"></i> Ver Imagen Normal (2D)
                        </button>
                    </div>
                </div>

                <!-- 2D Container (Hidden by default) -->
                <div id="container-2d-view" style="display:none;">
                    <div class="product-image-container">
                        <img src="{{ $imagen }}" class="img-fluid w-100" alt="{{ $altText }}"
                             onerror="this.src='{{ asset('producto.jpg') }}'">
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" id="btn-toggle-view-back" class="btn btn-outline-primary btn-sm" style="border-radius:20px;">
                            <i class="fas fa-cube"></i> Volver a Vista 3D
                        </button>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const viewer = document.getElementById('viewer-3d');
                        const container3d = document.getElementById('container-3d-view');
                        const container2d = document.getElementById('container-2d-view');
                        const btnToggle = document.getElementById('btn-toggle-view');
                        const btnToggleBack = document.getElementById('btn-toggle-view-back');

                        btnToggle.addEventListener('click', () => {
                            container3d.style.display = 'none';
                            container2d.style.display = 'block';
                        });

                        btnToggleBack.addEventListener('click', () => {
                            container2d.style.display = 'none';
                            container3d.style.display = 'block';
                        });

                        if (!viewer) return;
                        
                        const img = document.getElementById('img-3d');
                        const totalFrames = {{ $totalFrames }};
                        let currentFrame = 1;
                        let isDragging = false;
                        let startX = 0;
                        let playInterval = null;

                        // Check if it's dynamic storage or static genwork
                        const isStaticGenwork = {{ stripos($url360Prefix, 'Diseño_3d_case') !== false ? 'true' : 'false' }};
                        const baseUrl = isStaticGenwork ? `{{ $url360Prefix }}` : `{{ $url360Prefix }}/`;

                        const setFrame = (frame) => {
                            currentFrame = frame;
                            img.src = `${baseUrl}${currentFrame}.jpg`;
                        };

                        const nextFrame = () => {
                            setFrame(currentFrame < totalFrames ? currentFrame + 1 : 1);
                        };

                        const prevFrame = () => {
                            setFrame(currentFrame > 1 ? currentFrame - 1 : totalFrames);
                        };

                        // Buttons
                        const btnPlay = document.getElementById('btn-play');
                        const btnPrev = document.getElementById('btn-prev');
                        const btnNext = document.getElementById('btn-next');
                        const btnReset = document.getElementById('btn-reset');

                        const togglePlay = () => {
                            if (playInterval) {
                                clearInterval(playInterval);
                                playInterval = null;
                                btnPlay.innerHTML = '<i class="fa fa-play"></i>';
                            } else {
                                playInterval = setInterval(nextFrame, 120); // Speed: 120ms per frame
                                btnPlay.innerHTML = '<i class="fa fa-pause"></i>';
                            }
                        };

                        btnPlay.addEventListener('click', togglePlay);
                        
                        btnNext.addEventListener('click', () => { 
                            if(playInterval) togglePlay(); 
                            nextFrame(); 
                        });
                        
                        btnPrev.addEventListener('click', () => { 
                            if(playInterval) togglePlay(); 
                            prevFrame(); 
                        });
                        
                        btnReset.addEventListener('click', () => { 
                            if(playInterval) togglePlay(); 
                            setFrame(1); 
                        });

                        // Mouse/Touch drag functionality
                        const updateFrameDrag = (deltaX) => {
                            if (Math.abs(deltaX) > 24) {
                                if (deltaX > 0) prevFrame(); // Drag right -> rotate left
                                else nextFrame(); // Drag left -> rotate right
                                return true;
                            }
                            return false;
                        };

                        const stopPlayIfActive = () => {
                            if (playInterval) togglePlay();
                        };

                        viewer.addEventListener('mousedown', (e) => {
                            if (e.target.closest('#v360-menu-btns')) return; // Ignore if clicking buttons
                            stopPlayIfActive();
                            isDragging = true;
                            startX = e.clientX;
                            viewer.style.cursor = 'grabbing';
                            e.preventDefault();
                        });

                        document.addEventListener('mouseup', () => {
                            isDragging = false;
                            viewer.style.cursor = 'ew-resize';
                        });

                        document.addEventListener('mousemove', (e) => {
                            if (!isDragging) return;
                            if (updateFrameDrag(e.clientX - startX)) startX = e.clientX;
                        });

                        viewer.addEventListener('touchstart', (e) => {
                            if (e.target.closest('#v360-menu-btns')) return;
                            stopPlayIfActive();
                            isDragging = true;
                            startX = e.touches[0].clientX;
                        });
                        
                        document.addEventListener('touchend', () => { isDragging = false; });
                        
                        viewer.addEventListener('touchmove', (e) => {
                            if (!isDragging) return;
                            e.preventDefault();
                            if (updateFrameDrag(e.touches[0].clientX - startX)) startX = e.touches[0].clientX;
                        }, { passive: false });
                        
                        // Preload all images
                        for(let i=1; i<=totalFrames; i++) {
                            const preloadImg = new Image();
                            preloadImg.src = `${baseUrl}${i}.jpg`;
                        }
                    });
                </script>
            @else
                <div class="product-image-container">
                    <img src="{{ $imagen }}" class="img-fluid w-100" alt="{{ $altText }}"
                         onerror="this.src='{{ asset('producto.jpg') }}'">
                </div>
            @endif
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

                $cleanSpecText = function($text) {
                    if ($text === null) return 'No especificado';
                    $str = trim((string)$text);
                    if ($str === '' || in_array(strtoupper($str), ['NULL', 'N/A', '-', 'NO ESPECIFICADO', 'NO APLICA'], true)) {
                        return 'No especificado';
                    }
                    // Clean SmallForm Factor -> Small Form Factor
                    $str = preg_replace('/SmallForm\s*Factor/i', 'Small Form Factor', $str);
                    // Clean trailing dashes, colons, dots, or degree symbols
                    $str = preg_replace('/[\s\-\:\.\º\–\—]+$/u', '', $str);
                    // Clean 'Intel ®' -> 'Intel'
                    if (preg_match('/^intel\s*®?$/i', $str)) {
                        return 'Intel';
                    }
                    // Clean video trailing 'Conectividad.*'
                    $str = preg_replace('/\s*Conectividad.*$/i', '', $str);
                    // Clean multiple spaces
                    $str = preg_replace('/\s+/', ' ', $str);
                    return trim($str);
                };

                $getIconForField = function($fieldName) {
                    $name = strtolower((string)$fieldName);
                    if (str_contains($name, 'formato') || str_contains($name, 'chasis') || str_contains($name, 'factor')) return 'fa-solid fa-desktop';
                    if (str_contains($name, 'procesador') || str_contains($name, 'cpu')) return 'fa-solid fa-microchip';
                    if (str_contains($name, 'chipset')) return 'fa-solid fa-layer-group';
                    if (str_contains($name, 'video') || str_contains($name, 'gráfico') || str_contains($name, 'grafico') || str_contains($name, 'gpu') || str_contains($name, 'controlador')) return 'fa-solid fa-gamepad';
                    if (str_contains($name, 'memoria') || str_contains($name, 'ram')) return 'fa-solid fa-memory';
                    if (str_contains($name, 'almacenamiento') || str_contains($name, 'disco') || str_contains($name, 'ssd') || str_contains($name, 'nvme') || str_contains($name, 'hdd') || str_contains($name, 'storage')) return 'fa-solid fa-hard-drive';
                    if (str_contains($name, 'fuente') || str_contains($name, 'poder') || str_contains($name, 'psu')) return 'fa-solid fa-plug';
                    if (str_contains($name, 'pantalla') || str_contains($name, 'tamaño') || str_contains($name, 'panel') || str_contains($name, 'monitor')) return 'fa-solid fa-display';
                    if (str_contains($name, 'hdmi') || str_contains($name, 'displayport') || str_contains($name, 'vga') || str_contains($name, 'video_hdmi')) return 'fa-solid fa-tv';
                    if (str_contains($name, 'garant') || str_contains($name, 'tiempo')) return 'fa-solid fa-shield-halved';
                    if (str_contains($name, 'suministro') || str_contains($name, 'tipo')) return 'fa-solid fa-box-archive';
                    if (str_contains($name, 'color')) return 'fa-solid fa-palette';
                    if (str_contains($name, 'rendimiento') || str_contains($name, 'paginas')) return 'fa-solid fa-gauge-high';
                    if (str_contains($name, 'sistema') || str_contains($name, 'operativo') || str_contains($name, 'so')) return 'fa-solid fa-laptop-code';
                    if (str_contains($name, 'conectividad') || str_contains($name, 'red') || str_contains($name, 'wlan') || str_contains($name, 'wifi')) return 'fa-solid fa-wifi';
                    return 'fa-solid fa-microchip';
                };

                // Top summary: para PCs (no monitores) ordenar Procesador, Memoria, Almacenamiento, Graficos
                $topOrdered = [];
                if ($isToner) {
                    $topOrdered = [
                        (object) [
                            'campo' => 'TIPO DE SUMINISTRO',
                            'descripcion' => $getSpecValue(['/tipo de suministro|suministro|formato/'])
                                ?? $getProductValue(['Tipo de suministro'])
                                ?? 'No especificado',
                        ],
                        (object) [
                            'campo' => 'COLOR',
                            'descripcion' => $getSpecValue(['/^color$/', '/color/'])
                                ?? $getProductValue(['Color'])
                                ?? 'No especificado',
                        ],
                        (object) [
                            'campo' => 'RENDIMIENTO',
                            'descripcion' => $getSpecValue(['/rendimiento|p[aá]ginas|paginas/'])
                                ?? $getProductValue(['Rendimiento'])
                                ?? 'No especificado',
                        ],
                        (object) [
                            'campo' => 'GARANTÍA',
                            'descripcion' => $getSpecValue(['/garant[ií]a|g\.\s*f/'])
                                ?? $getProductValue(['garantia_de_fabrica', 'Garantia'])
                                ?? 'No especificado',
                        ],
                    ];
                } elseif (!$isMonitor) {
                    if ($isDesktopOrWorkstation) {
                        $topOrdered = [
                            (object) ['campo' => 'FORMATO', 'descripcion' => $getSpecValue(['/formato|factor|chasis|tipo de suministro|suministro/']) ?? $getProductValue(['Tipo de suministro']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'PROCESADOR', 'descripcion' => $getSpecValue(['/procesador|cpu|intel|amd/']) ?? $getProductValue(['procesador']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'CHIPSET', 'descripcion' => $getSpecValue(['/chipset/']) ?? $getProductValue(['chipset']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'VIDEO', 'descripcion' => $getSpecValue(['/gr[aá]f|gpu|controlador de video|tarjeta de video|tarjeta grafica|tarjeta gráfica|video/']) ?? $getProductValue(['tarjetavideo']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'MEMORIA RAM', 'descripcion' => $getSpecValue(['/memoria|ram/']) ?? $getProductValue(['ram']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'ALMACENAMIENTO', 'descripcion' => $getSpecValue(['/almacenamiento|disco|hdd|ssd|nvme|storage/']) ?? $getProductValue(['almacenamiento']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'FUENTE PODER', 'descripcion' => $getSpecValue(['/fuente|psu|power supply/']) ?? $getProductValue(['fuente_poder']) ?? 'No especificado', 'descripcion2' => ''],
                        ];
                    } else {
                        $topOrdered = [
                            (object) ['campo' => 'PROCESADOR', 'descripcion' => $getSpecValue(['/procesador|cpu|intel|amd/']) ?? $getProductValue(['procesador']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'MEMORIA RAM', 'descripcion' => $getSpecValue(['/memoria|ram/']) ?? $getProductValue(['ram']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'ALMACENAMIENTO', 'descripcion' => $getSpecValue(['/almacenamiento|disco|hdd|ssd|nvme|storage/']) ?? $getProductValue(['almacenamiento']) ?? 'No especificado', 'descripcion2' => ''],
                            (object) ['campo' => 'VIDEO', 'descripcion' => $getSpecValue(['/gr[aá]f|gpu|tarjeta de video|tarjeta grafica|tarjeta gráfica|video/']) ?? $getProductValue(['tarjetavideo']) ?? 'No especificado', 'descripcion2' => ''],
                        ];
                    }
                }
            @endphp

            <div style="margin-bottom:24px;">
                <h2 style="font-weight:800; font-size:28px; color:#1a1a1a; margin-bottom:12px; line-height:1.2;">
                    {{ $producto->display_name }}
                </h2>
                <div style="display:flex; align-items:center; flex-wrap:wrap; gap:8px; font-size:13px;">
                    @if($producto->modelo)
                        <span style="display:inline-flex; align-items:center; gap:6px; background:#eaedf0; color:#495057; font-size:11.5px; font-weight:700; padding:4px 10px; border-radius:3px; text-transform:uppercase;">
                            <i class="fa-solid fa-cube" style="font-size:11px; color:#6c757d;"></i> {{ $producto->modelo->descripcion ?? $producto->modelo->nombre }}
                        </span>
                    @endif
                    @if($producto->nro_parte)
                        <span style="display:inline-flex; align-items:center; gap:4px; background:#eaedf0; color:#495057; font-size:11.5px; font-weight:700; padding:4px 10px; border-radius:3px; text-transform:uppercase;">
                            <span style="color:#6c757d; font-weight:800;">#</span> {{ $producto->nro_parte }}
                        </span>
                    @endif
                    @if($producto->getCategoria)
                        <span style="display:inline-flex; align-items:center; gap:5px; background:#eaedf0; color:#495057; font-size:11.5px; font-weight:700; padding:4px 10px; border-radius:3px; text-transform:uppercase;">
                            <i class="fa-solid fa-tag" style="font-size:10px; color:#6c757d;"></i> {{ $producto->getCategoria->nombre }}
                        </span>
                    @endif
                </div>
            </div>

            <div id="design-v2">

                <div class="specs-table-container">
                    @php
                        $specsToRender = [];
                        if ($isMonitor) {
                            $specsToRender = $especificacionesResumen ?? [];
                        } elseif ($isToner || !$isDesktopOrWorkstation) {
                            $specsToRender = $topOrdered ?? [];
                        } else {
                            $specsToRender = $topOrdered ?? [];
                        }
                    @endphp

                    @forelse($specsToRender as $espec)
                        @php
                            $iconClass = $getIconForField($espec->campo ?? '');
                            $val = $cleanSpecText($espec->descripcion ?? '');
                            if ($isDesktopOrWorkstation && in_array(strtolower($espec->campo ?? ''), ['procesador', 'cpu']) && !empty($producto->descripcion_2)) {
                                $val = $val . ' · ' . $producto->descripcion_2;
                            }
                        @endphp
                        <div class="spec-row-item">
                            <div class="spec-col-icon">
                                <i class="{{ $iconClass }} spec-icon"></i>
                            </div>
                            <div class="spec-col-label">
                                {{ $espec->campo }}
                            </div>
                            <div class="spec-col-value">
                                {{ $val }}
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-muted">Aún no tiene especificaciones</div>
                    @endforelse
                </div>

                <div class="specs-disclaimer">
                    * Las imágenes e información incluidas son referenciales; pueden variar por versiones, por favor consultar a su vendedor.
                </div>

                {{-- Action Box Escritorio (>= 768px, idéntico a Imagen 2) --}}
                <div class="desktop-action-card">
                    {{-- 1. Ficha técnica (plana, sin caja de botón) --}}
                    @if($producto->ficha_tecnica)
                        @php
                            $fichaUrl = str_starts_with($producto->ficha_tecnica, 'http')
                                ? $producto->ficha_tecnica
                                : asset('/storage/' . $producto->ficha_tecnica);
                        @endphp
                        <a href="{{ $fichaUrl }}" target="_blank" class="desktop-ficha-btn">
                            <i class="fa-regular fa-file-lines" style="font-size:24px; color:#b97737;"></i>
                            <div style="font-size:14px; font-weight:700; color:#8e623a; line-height:1.15; text-align:left;">
                                <div>Ficha</div>
                                <div>técnica</div>
                            </div>
                            <i class="fa-solid fa-download" style="font-size:11px; color:#b97737; margin-left:4px;"></i>
                        </a>
                    @else
                        <div class="desktop-ficha-btn" style="opacity:0.6; cursor:not-allowed;">
                            <i class="fa-regular fa-file-lines" style="font-size:24px; color:#bbb;"></i>
                            <div style="font-size:14px; font-weight:700; color:#888; line-height:1.15; text-align:left;">
                                <div>Ficha</div>
                                <div>técnica</div>
                            </div>
                        </div>
                    @endif

                    {{-- 2. Precios B2B (caja con borde naranja sólido de 2px) --}}
                    <div class="desktop-b2b-box">
                        <i class="fa-solid fa-lock" style="font-size:14px; color:#b97737;"></i>
                        <div style="font-size:13.5px; font-weight:600; color:#222; line-height:1.2; text-align:left;">
                            <div>Precios exclusivos</div>
                            <div style="text-align:center;">B2B</div>
                        </div>
                        @if(Auth::guard('cliente')->check())
                            <div style="border-left:1px solid #f0f0f0; padding-left:12px; font-weight:800; color:#ee7c31; font-size:15px;">
                                $ {{ number_format($producto->precio_especial, 2) }}
                            </div>
                        @else
                            <a href="{{ route('login-cliente.show', ['redirect' => route('cotizar.detalle', $producto->id, false)]) }}" style="color:#8e623a; text-decoration:underline; font-weight:700; font-size:13px; line-height:1.2; text-align:center;">
                                <div>Ingresa</div>
                                <div>aquí</div>
                            </a>
                        @endif
                    </div>

                    {{-- 3. Cotizar por WhatsApp (verde #1ebd5b con texto apilado en 2 líneas) --}}
                    <a target="_blank" href="https://wa.me/+51958021778?text={{ urlencode('¡Hola KENYA Technology! Solicito cotización para el producto: ' . $producto->display_name . ($producto->nro_parte ? ' (PN: ' . $producto->nro_parte . ')' : '') . '. URL: ' . url()->current()) }}" class="desktop-wsp-btn">
                        <i class="bx bxl-whatsapp" style="font-size:26px; line-height:1;"></i>
                        <div style="font-size:15px; font-weight:800; color:#ffffff; line-height:1.15; text-align:left;">
                            <div>Cotizar por</div>
                            <div>WhatsApp</div>
                        </div>
                    </a>
                </div>

                {{-- Action Box Móvil (< 768px, botones apilados según diseño de referencia) --}}
                <div class="mobile-actions-stack">
                    {{-- 1. Ficha técnica --}}
                    @if($producto->ficha_tecnica)
                        @php
                            $fichaUrl = str_starts_with($producto->ficha_tecnica, 'http')
                                ? $producto->ficha_tecnica
                                : asset('/storage/' . $producto->ficha_tecnica);
                        @endphp
                        <a href="{{ $fichaUrl }}" target="_blank" class="mobile-action-btn mobile-action-white">
                            <i class="fa-regular fa-file-lines" style="font-size:16px;"></i>
                            <span>Ficha técnica</span>
                        </a>
                    @else
                        <button type="button" disabled class="mobile-action-btn mobile-action-white" style="opacity:0.6; cursor:not-allowed;">
                            <i class="fa-regular fa-file-lines" style="font-size:16px;"></i>
                            <span>Ficha técnica</span>
                        </button>
                    @endif

                    {{-- 2. Precios B2B --}}
                    @if(Auth::guard('cliente')->check())
                        <div class="mobile-action-btn mobile-action-white" style="cursor:default; justify-content:space-between; padding:0 18px;">
                            <span style="display:inline-flex; align-items:center; gap:8px;">
                                <i class="fa-solid fa-lock" style="font-size:14px; color:#ee7c31;"></i> Precios B2B
                            </span>
                            <span style="color:#ee7c31; font-weight:800; font-size:15px;">$ {{ number_format($producto->precio_especial, 2) }}</span>
                        </div>
                    @else
                        <a href="{{ route('login-cliente.show', ['redirect' => route('cotizar.detalle', $producto->id, false)]) }}" class="mobile-action-btn mobile-action-white">
                            <i class="fa-solid fa-lock" style="font-size:14px;"></i>
                            <span>Precios B2B</span>
                        </a>
                    @endif

                    {{-- 3. Cotizar por WhatsApp (Verde #25D366) --}}
                    <a target="_blank" href="https://wa.me/+51958021778?text={{ urlencode('¡Hola KENYA Technology! Solicito cotización para el producto: ' . $producto->display_name . ($producto->nro_parte ? ' (PN: ' . $producto->nro_parte . ')' : '') . '. URL: ' . url()->current()) }}" class="mobile-action-btn mobile-action-wsp">
                        <i class="bx bxl-whatsapp" style="font-size:22px;"></i>
                        <span>Cotizar por WhatsApp</span>
                    </a>
                </div>
            </div>{{-- close #design-v2 --}}

        {{-- ===== DISEÑO ANTERIOR ===== --}}
        <div id="design-v1" style="display:none;">
            <div style="background:#fff; border:1px solid #eee; border-radius:12px; padding:24px 28px; margin-bottom:20px;">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0" style="width:100%;">
                        <tbody>
                            @if($isMonitor)
                                @forelse($especificacionesResumen as $espec)
                                <tr>
                                    <td style="min-width:180px; font-weight:600; color:#444; padding:10px 16px 10px 0; vertical-align:top; width:35%;">{{ $espec->campo }}</td>
                                    <td style="padding:10px 0; color:#1a1a1a; vertical-align:top;">: {{ $espec->descripcion }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">Aún no tiene especificaciones</td></tr>
                                @endforelse
                            @elseif($isToner)
                                @foreach($topOrdered as $espec)
                                <tr>
                                    <td style="min-width:180px; font-weight:700; color:#444; padding:10px 16px 10px 0; vertical-align:top; width:35%;">{{ $espec->campo }}</td>
                                    <td style="padding:10px 0; color:#1a1a1a; vertical-align:top;">: {{ $espec->descripcion }}</td>
                                </tr>
                                @endforeach
                            @else
                                @forelse($topOrdered as $espec)
                                <tr>
                                    <td style="min-width:180px; font-weight:700; color:#444; padding:10px 16px 10px 0; vertical-align:top; width:35%;">{{ $espec->campo }}</td>
                                    <td style="padding:10px 0; color:#1a1a1a; vertical-align:top;">
                                        @if($isDesktopOrWorkstation)
                                        <div style="display:flex; gap:16px; align-items:flex-start;">
                                            <div style="flex:1;">: {{ $espec->descripcion }}</div>
                                            <div style="flex:1; font-size:0.85em; color:#999; font-style:italic;">
                                                @if(strtolower($espec->campo) === 'procesador')Descripción 2: {{ $producto->descripcion_2 ?: 'Vacío' }}@else Descripción 2: Vacío @endif
                                            </div>
                                        </div>
                                        @else
                                        : {{ $espec->descripcion }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">Aún no tiene especificaciones</td></tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3" style="font-size:11px; color:#bbb; padding-left:2px;">* Las imágenes e información incluidas son referenciales; pueden variar por versiones, por favor consultar a su vendedor.</div>
            </div>

            <div style="background:#fafafa; border:1px solid #eee; border-radius:12px; padding:18px 24px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
                <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
                    @foreach($producto->getGarantia->skip(0)->take(1) as $gar)
                    <div style="display:flex; align-items:center; gap:8px;">
                        <img alt="Garantía" src="https://img.icons8.com/fluency/2x/guarantee.png" style="width:28px; height:28px;">
                        <span style="font-size:14px; font-weight:600; color:#222;">TIEMPO DE GARANTIA: {{ $gar->garantia }} MESES</span>
                    </div>
                    @endforeach
                    <span style="color:#ddd;">|</span>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <img alt="Ficha técnica" src="https://img.icons8.com/ios-filled/2x/wordbook.png" style="width:22px; height:22px; filter:invert(0%) sepia(0%) saturate(7469%) hue-rotate(214deg) brightness(91%) contrast(107%);">
                        <span style="font-size:14px; font-weight:600; color:#222;">FICHA TECNICA:</span>
                        @if($producto->ficha_tecnica)
                            @php
                                $fichaUrl = str_starts_with($producto->ficha_tecnica, 'http')
                                    ? $producto->ficha_tecnica
                                    : asset('/storage/' . $producto->ficha_tecnica);
                            @endphp
                            <a href="{{ $fichaUrl }}" target="_blank" style="font-size:14px; font-weight:600; color:#1a1a1a;">PDF <iconify-icon icon="bx:download"></iconify-icon></a>
                        @else
                            <span style="font-size:14px; color:#999;">No disponible</span>
                        @endif
                    </div>
                </div>
                <a target="_blank" href="https://wa.me/+51958021778?text=!Quiero Informacion sobre el producto" class="btn btn-success" style="display:flex; align-items:center; gap:6px; font-weight:600; white-space:nowrap; font-size:14px;">
                    <i class="bx bxl-whatsapp" style="font-size:20px;"></i> Contactar
                </a>
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

{{-- ===== ESPECIFICACIONES TÉCNICAS (ancho completo) ===== --}}
<div style="background:#fff; border:1px solid #eee; border-radius:14px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.04); margin-bottom:20px;">
    <div style="background:linear-gradient(135deg, #ee7c31 0%, #f39548 100%); padding:18px 24px; display:flex; align-items:center; gap:10px;">
        <i class="fa-solid fa-clipboard-list" style="color:#fff; font-size:18px;"></i>
        <span style="font-weight:700; font-size:17px; color:#fff; letter-spacing:0.3px;">Especificaciones Técnicas</span>
    </div>

    <div style="padding:8px 0;">
        @if($isMonitor)
            @forelse($especificaciones as $espec)
            <div style="display:flex; align-items:center; padding:14px 24px; border-bottom:1px solid #f5f5f5; transition:background 0.15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                <div style="flex:0 0 220px; font-weight:600; color:#555; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-circle" style="font-size:5px; color:#ee7c31;"></i>
                    {{ $espec->campo }}
                </div>
                <div style="flex:1; color:#1a1a1a; font-size:14px;">{{ $espec->descripcion }}</div>
            </div>
            @empty
            <div style="padding:32px; text-align:center; color:#999; font-size:14px;">Aún no tiene especificaciones</div>
            @endforelse
            <div style="display:flex; align-items:center; padding:14px 24px; background:#fafafa; transition:background 0.15s;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='#fafafa'">
                <div style="flex:0 0 220px; font-weight:600; color:#555; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-circle" style="font-size:5px; color:#ee7c31;"></i>
                    Número de Parte
                </div>
                <div style="flex:1; color:#1a1a1a; font-size:14px;">{{ $producto->nro_parte ?? 'No especificado' }}</div>
            </div>
        @elseif($isToner)
            @php
                $oldTonerRows = [
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
                    ['label' => 'Dimensiones', 'value' => $getSpecValue(['/dimensi/']) ?? $getProductValue(['Dimensiones'])]
                ];
            @endphp
            @forelse($oldTonerRows as $fr)
            <div style="display:flex; align-items:center; padding:14px 24px; border-bottom:1px solid #f5f5f5; transition:background 0.15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                <div style="flex:0 0 220px; font-weight:600; color:#555; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-circle" style="font-size:5px; color:#ee7c31;"></i>
                    {{ $fr['label'] }}
                </div>
                <div style="flex:1; color:#1a1a1a; font-size:14px;">{{ $fr['value'] ?? 'No especificado' }}</div>
            </div>
            @empty
            <div style="padding:32px; text-align:center; color:#999; font-size:14px;">Aún no tiene especificaciones</div>
            @endforelse
        @else
            @php
                $oldPcRows = [
                    ['label' => 'Numero de Parte', 'value' => $getProductValue(['nro_parte', 'Número de parte'])],
                    ['label' => 'Modelo', 'value' => optional($producto->modelo)->nombre ?? optional($producto->modelo)->descripcion ?? $getProductValue(['Modelo'])],
                    ['label' => 'Formato', 'value' => $getSpecValue(['/formato|factor|chasis|tipo de suministro|suministro/']) ?? $getProductValue(['Tipo de suministro'])],
                    ['label' => 'Procesador', 'value' => $getSpecValue(['/procesador|cpu|intel|amd/']) ?? $getProductValue(['procesador'])],
                    ['label' => 'Memoria Ram', 'value' => $getSpecValue(['/memoria|ram/']) ?? $getProductValue(['ram'])],
                    ['label' => 'Almacenamiento', 'value' => $getSpecValue(['/almacenamiento|disco|hdd|ssd|nvme|storage/']) ?? $getProductValue(['almacenamiento'])],
                    ['label' => 'Sistema Operativo', 'value' => $getSpecValue(['/sistema operativo|\bos\b|windows|linux/']) ?? $getProductValue(['sistema_operativo'])],
                    ['label' => 'Suite Ofimática', 'value' => $getSpecValue(['/ofim[aá]tica|office|suite/']) ?? $getProductValue(['suite_ofimatica'])],
                    ['label' => 'Gráficos', 'value' => $getSpecValue(['/gr[aá]f|gpu|controlador de video|tarjeta de video|tarjeta grafica|tarjeta gráfica|video/']) ?? $getProductValue(['tarjetavideo'])],
                    ['label' => 'Sonido', 'value' => $getSpecValue(['/sonido|audio/']) ?? $getProductValue(['sonido'])],
                    ['label' => 'Chipset', 'value' => $getSpecValue(['/chipset/']) ?? $getProductValue(['chipset'])],
                    ['label' => 'Lan', 'value' => $getSpecValue(['/\blan\b|ethernet/']) ?? $getProductValue(['conectividad'])],
                    ['label' => 'Wlan', 'value' => $getSpecValue(['/\bwlan\b|wifi|wireless/']) ?? $getProductValue(['conectividad_wlan'])],
                    ['label' => 'Puertos Mínimos', 'value' => $getSpecValue(['/puertos|minimo|m[ií]nimo/']) ?? $getProductValue(['conectividad_usb'])],
                    ['label' => 'Slot de Expansión', 'value' => $getSpecValue(['/slot|expansi|pci|m\.2|ranura/'])],
                    ['label' => 'Fuente de Poder', 'value' => $getSpecValue(['/fuente|psu|power supply/']) ?? $getProductValue(['fuente_poder'])],
                    ['label' => 'Garantia', 'value' => $getSpecValue(['/garant[ií]a de f[aá]brica|garant[ií]a|garantia/']) ?? $getProductValue(['garantia_de_fabrica', 'Garantia'])],
                    ['label' => 'Empaque', 'value' => $getSpecValue(['/empaque|packag/']) ?? $getProductValue(['Empaque', 'empaque_de_fabrica'])],
                    ['label' => 'Certificaciones', 'value' => $getSpecValue(['/certific|iso/']) ?? $getProductValue(['Certificaciones', 'certificacion'])],
                    ['label' => 'Accesorios y Otros', 'value' => $getSpecValue(['/accesorio|otros|observaciones|incluye/']) ?? $getProductValue(['accesorios'])],
                ];
            @endphp
            @forelse($oldPcRows as $fr)
            <div style="display:flex; align-items:center; padding:14px 24px; border-bottom:1px solid #f5f5f5; transition:background 0.15s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='transparent'">
                <div style="flex:0 0 220px; font-weight:600; color:#555; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-circle" style="font-size:5px; color:#ee7c31;"></i>
                    {{ $fr['label'] }}
                </div>
                <div style="flex:1; color:#1a1a1a; font-size:14px;">{{ $fr['value'] ?? 'No especificado' }}</div>
            </div>
            @empty
            <div style="padding:32px; text-align:center; color:#999; font-size:14px;">Aún no tiene especificaciones</div>
            @endforelse
        @endif
    </div>
</div>
</div>{{-- close container --}}

<br>
@endsection

@section('js')
<script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
@endsection
