<!-- components/novedades.blade.php — carousel from welcome page -->
<style>
    .comp-novedades-section {
        width: 100%;
        padding: 60px 0;
        background-color: #f8f9fa;
        margin: 0;
    }
    .comp-novedades-title {
        text-align: center;
        margin-bottom: 10px;
    }
    .comp-novedades-title h2 {
        font-size: 2.2rem;
        font-weight: 700;
        text-transform: uppercase;
        color: white;
        display: inline-block;
        background: linear-gradient(135deg, #f26522 0%, #e67125 100%);
        padding: 8px 25px;
        border-radius: 55px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        letter-spacing: 1.5px;
        box-shadow: 0 6px 15px rgba(242,101,34,0.4);
        border: 2px solid rgba(255,255,255,0.2);
        margin: 0;
    }
    .comp-novedades-subtitle {
        text-align: center;
        font-size: 1rem;
        color: #666;
        margin: 10px 0 30px;
    }
    .comp-novedades-wrapper {
        position: relative;
        padding: 0 50px;
    }
    .comp-novedades-grid {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        padding: 10px 5px 20px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .comp-novedades-grid::-webkit-scrollbar { display: none; }
    .comp-novedad-card {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        min-width: 0;
        flex: 0 0 calc(25% - 15px);
        scroll-snap-align: start;
        display: flex;
        flex-direction: column;
        text-align: left;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        position: relative;
        overflow: visible;
        height: auto;
    }
    .comp-novedad-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.13);
        transform: translateY(-4px);
    }
    .comp-novedad-badge {
        display: inline-block;
        align-self: flex-start;
        background-color: #f26522;
        color: #fff;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 15px;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }
    .comp-novedad-imagen {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 15px;
    }
    .comp-novedad-imagen img {
        width: 100%;
        height: 200px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .comp-novedad-card:hover .comp-novedad-imagen img {
        transform: scale(1.04);
    }
    .comp-novedad-info {
        padding: 0;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
        justify-content: flex-start;
        align-items: flex-start;
    }
    .comp-novedad-titulo {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #111;
        text-align: left;
    }
    .comp-novedad-titulo a {
        color: #111;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .comp-novedad-titulo a:hover { color: #f26522; }
    .comp-novedad-btn-detalle {
        display: block;
        background-color: #f26522;
        color: #fff;
        text-align: center;
        padding: 10px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: bold;
        font-size: 0.9rem;
        transition: background 0.3s;
        width: 100%;
        margin-top: 12px;
        border: none;
        cursor: pointer;
    }
    .comp-novedad-btn-detalle:hover { background-color: #444; }
    .comp-novedades-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: #fff;
        border: 1px solid #eaeaea;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f26522;
        font-size: 1.1rem;
        transition: all 0.3s;
        padding: 0;
    }
    .comp-novedades-btn:hover {
        background-color: #f26522;
        color: #fff;
    }
    .comp-novedades-prev { left: 0; }
    .comp-novedades-next { right: 0; }

    @media (max-width: 992px) {
        .comp-novedad-card { flex: 0 0 calc(33.333% - 14px); }
    }
    @media (max-width: 768px) {
        .comp-novedades-wrapper { padding: 0 35px; }
        .comp-novedad-card { flex: 0 0 calc(50% - 10px); }
    }
    @media (max-width: 576px) {
        .comp-novedades-wrapper { padding: 0 35px; }
        .comp-novedad-card { flex: 0 0 100%; min-width: 260px; }
        .comp-novedades-prev { left: 2px; }
        .comp-novedades-next { right: 2px; }
    }
</style>

<section class="comp-novedades-section">
    <div class="section-container" style="max-width: 1400px; margin: 0 auto; padding: 0 15px;">
        <div class="comp-novedades-title">
            <h2>Novedades</h2>
        </div>
        <p class="comp-novedades-subtitle">Nuevos productos en nuestra lista, ¡qué esperas!</p>

        @if(isset($novedades) && $novedades->count() > 0)
            <div class="comp-novedades-wrapper">
                <button class="comp-novedades-btn comp-novedades-prev" aria-label="Anterior" onclick="this.nextElementSibling.scrollBy({left:-300,behavior:'smooth'})">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="comp-novedades-grid">
                    @foreach($novedades as $novedad)
                        @php
                            $novedadImg = $novedad->imagen ?: $novedad->imagen_1;
                            $imgUrl = asset('producto.jpg');
                            if ($novedadImg) {
                                if (str_starts_with($novedadImg, 'http://') || str_starts_with($novedadImg, 'https://')) {
                                    $imgUrl = $novedadImg;
                                } else {
                                    $imgUrl = asset('storage/' . $novedadImg);
                                }
                            } elseif ($novedad->modelo && !empty($novedad->modelo->img_mod)) {
                                $imgUrl = asset('storage/' . $novedad->modelo->img_mod);
                            } elseif ($novedad->getCategoria && !empty($novedad->getCategoria->img_url)) {
                                $imgUrl = $novedad->getCategoria->img_url;
                            }
                            $novedadNombre = $novedad->nombre ?: $novedad->descripcion;
                            $cleanName = preg_replace('/\s*\([A-Z0-9\-\.]+\)\s*$/i', '', $novedadNombre ?? '');
                            $cleanName = trim($cleanName);
                            $novedadUrl = $novedad->modelo ? route('detallemod', $novedad->modelo->id) : '#';
                            $novedadPartNumber = $novedad->nro_parte ?? $novedad->{'Número de parte'} ?? 'N/A';
                            $novedadStock = $novedad->stock ?? '≥ 20';
                        @endphp
                        <div class="comp-novedad-card">
                            <span class="comp-novedad-badge">Nuevo</span>
                            <div class="comp-novedad-imagen">
                                <img src="{{ $imgUrl }}" alt="{{ $cleanName }}" onerror="this.onerror=null; this.src='{{ asset('producto.jpg') }}';">
                            </div>
                            <div class="comp-novedad-info">
                                <h5 class="comp-novedad-titulo">
                                    <a href="{{ $novedadUrl }}"><strong>{{ $cleanName }}</strong></a>
                                </h5>
                                <ul style="list-style-type: none; padding-left: 0; margin: 10px 0; width: 100%;">
                                    <li style="font-size: 0.85rem; color: #555; margin-bottom: 6px; line-height: 1.4;">
                                        <strong>N° PARTE:</strong> {{ $novedadPartNumber }}
                                    </li>
                                    <li style="font-size: 0.85rem; color: #555; margin-bottom: 6px; line-height: 1.4;">
                                        <strong>STOCK:</strong>
                                        @if($novedadStock !== 0 && $novedadStock !== '0')
                                            <span style="color: #2e7d32; font-weight: 600;">{{ $novedadStock }} unidades</span>
                                        @else
                                            <span style="color: #c62828; font-weight: 600;">No disponible</span>
                                        @endif
                                    </li>
                                </ul>
                                <a href="{{ $novedadUrl }}" class="comp-novedad-btn-detalle" style="margin-top: auto;">Ver detalles</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="comp-novedades-btn comp-novedades-next" aria-label="Siguiente" onclick="this.previousElementSibling.scrollBy({left:300,behavior:'smooth'})">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        @else
            <p style="text-align: center; color: #999;">No hay novedades por el momento.</p>
        @endif
    </div>
</section>
