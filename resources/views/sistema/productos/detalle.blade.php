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

            @php
                $has360 = false;
                $totalFrames = 0;
                $modelo360Id = $producto->modelo_id ?? null;
                $url360Prefix = '';
                
                if ($modelo360Id && \Illuminate\Support\Facades\Storage::exists('public/modelos_360/' . $modelo360Id)) {
                    $files = \Illuminate\Support\Facades\Storage::files('public/modelos_360/' . $modelo360Id);
                    $totalFrames = count($files);
                    if ($totalFrames > 0) {
                        $has360 = true;
                        $url360Prefix = asset('storage/modelos_360/' . $modelo360Id . '/');
                    }
                }
            @endphp

            @if($has360)
                <div class="product-image-container 3d-viewer" id="viewer-3d" style="cursor: ew-resize; user-select: none; position: relative;">
                    <img id="img-3d" src="{{ $url360Prefix }}/1.jpg" class="img-fluid w-100" alt="Vista 3D 360°" style="border-radius:8px;">
                    
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
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const viewer = document.getElementById('viewer-3d');
                        if (!viewer) return;
                        
                        const img = document.getElementById('img-3d');
                        const totalFrames = {{ $totalFrames }};
                        let currentFrame = 1;
                        let isDragging = false;
                        let startX = 0;
                        let playInterval = null;

                        const baseUrl = `{{ $url360Prefix }}/`;

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
                                playInterval = setInterval(nextFrame, 60); // Speed: 60ms per frame
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
                            if (Math.abs(deltaX) > 12) {
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
            
            {{-- ── PRECIO (solo clientes verificados) ── --}}
            @auth
                @hasrole('cliente_web')
                <div style="background:linear-gradient(135deg,#fff8f4,#fff3ec); border:1.5px solid #f5d4bb; border-radius:12px; padding:18px 22px; margin:0 0 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
                    <div>
                        <div style="font-size:11px; color:#b0502a; text-transform:uppercase; letter-spacing:0.5px; font-weight:700; margin-bottom:4px;">
                            <i class="fa-solid fa-tag" style="margin-right:4px;"></i>Precio referencial (cliente verificado)
                        </div>
                        @if($producto->precio_anterior)
                            <div style="font-size:14px; color:#ccc; text-decoration:line-through; margin-bottom:2px;">
                                S/ {{ number_format($producto->precio_anterior, 2) }}
                                <span style="background:#e74c3c; color:#fff; font-size:10px; font-weight:700; border-radius:4px; padding:1px 6px; margin-left:4px;">OFERTA</span>
                            </div>
                        @endif
                        @if($producto->precio_unitario)
                            <div style="font-size:30px; font-weight:800; color:#ee7c31; letter-spacing:-1px; line-height:1;">
                                S/ {{ number_format($producto->precio_unitario, 2) }}
                            </div>
                        @else
                            <div style="font-size:16px; color:#bbb; font-style:italic;">Precio a consultar</div>
                        @endif
                    </div>
                    <a target="_blank"
                       href="https://wa.me/+51958021778?text=Hola, soy cliente verificado y quiero cotizar: {{ urlencode($producto->display_name) }}"
                       class="btn btn-success" style="display:flex; align-items:center; gap:6px; font-weight:600;">
                        <i class="bx bxl-whatsapp" style="font-size:20px;"></i> Solicitar cotización
                    </a>
                </div>
                @endhasrole
            @endauth

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
                @guest
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <a target="_blank" href="https://wa.me/+51958021778?text=!Quiero Informacion sobre el producto" class="btn btn-success" style="display:flex; align-items:center; justify-content:center; gap:6px; font-weight:600; white-space:nowrap;">
                        <i class="bx bxl-whatsapp" style="font-size:20px;"></i> Contactar
                    </a>
                    <a href="{{ route('login-cliente.show', ['redirect' => route('cotizar.detalle', $producto->id, false)]) }}" style="display:flex; align-items:center; justify-content:center; gap:6px; font-weight:600; white-space:nowrap; border:1px solid #ee7c31; color:#ee7c31; background:transparent; padding:6px 12px; border-radius:4px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#ee7c31'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#ee7c31';">
                        <i class="fa-solid fa-tag"></i> Ver precio
                    </a>
                </div>
                @else
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <a target="_blank" href="https://wa.me/+51958021778?text=!Quiero Informacion sobre el producto" class="btn btn-success" style="display:flex; align-items:center; justify-content:center; gap:6px; font-weight:600; white-space:nowrap;">
                        <i class="bx bxl-whatsapp" style="font-size:20px;"></i> Contactar
                    </a>
                    <a href="{{ route('cotizar.detalle', $producto->id, false) }}" style="display:flex; align-items:center; justify-content:center; gap:6px; font-weight:600; white-space:nowrap; border:1px solid #ee7c31; color:#ee7c31; background:transparent; padding:6px 12px; border-radius:4px; text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='#ee7c31'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#ee7c31';">
                        <i class="fa-solid fa-tag"></i> Ver precio
                    </a>
                </div>
                @endguest
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
