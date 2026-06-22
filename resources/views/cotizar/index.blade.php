@extends('layouts.landing')

@section('css')
<style>
    :root {
        --accent: #ee7c31;
        --accent-dark: #d46820;
        --surface: #f8f8f8;
        --card-bg: #ffffff;
        --border: #ebebeb;
        --text-main: #1a1a1a;
        --text-muted: #888;
    }

    body { background: var(--surface); zoom: 0.9; }

    /* ── Header portal ── */
    .portal-hero {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 60%, #ee7c31 100%);
        padding: 28px 0 22px;
        margin-top: 70px;
    }
    .portal-hero h1 {
        color: #fff;
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 4px;
        letter-spacing: 0.3px;
    }
    .portal-hero .subtitle {
        color: rgba(255,255,255,0.65);
        font-size: 13px;
    }
    .badge-cliente {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 11px;
        color: rgba(255,255,255,0.85);
        letter-spacing: 0.3px;
    }

    /* ── Buscador ── */
    .search-bar {
        background: #fff;
        border-bottom: 1px solid var(--border);
        padding: 14px 0;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .search-bar form {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .search-bar input[type="text"] {
        flex: 1;
        padding: 9px 16px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.15s;
    }
    .search-bar input[type="text"]:focus {
        border-color: var(--accent);
    }
    .search-bar button {
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 9px 18px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
        white-space: nowrap;
    }
    .search-bar button:hover { background: var(--accent-dark); }

    /* ── Grid ── */
    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
        padding: 28px 0;
    }

    /* ── Tarjeta producto ── */
    .prod-card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        cursor: pointer;
    }
    .prod-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.1);
    }
    .prod-card .img-wrap {
        background: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 180px;
        overflow: hidden;
        border-bottom: 1px solid var(--border);
    }
    .prod-card .img-wrap img {
        max-width: 100%;
        max-height: 160px;
        object-fit: contain;
        transition: transform 0.2s;
    }
    .prod-card:hover .img-wrap img { transform: scale(1.04); }
    .prod-card .card-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .prod-card .card-cat {
        font-size: 10px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    .prod-card .card-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.35;
        flex: 1;
        margin-bottom: 12px;
    }
    .prod-card .card-nro {
        font-size: 11px;
        color: #aaa;
        margin-bottom: 12px;
    }

    /* ── Precio — el elemento estrella del portal ── */
    .price-block {
        background: linear-gradient(135deg, #fff8f4, #fff3ec);
        border: 1px solid #f5d4bb;
        border-radius: 10px;
        padding: 12px 14px;
        margin-bottom: 14px;
    }
    .price-label {
        font-size: 10px;
        color: #b0502a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
        font-weight: 600;
    }
    .price-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--accent);
        letter-spacing: -0.5px;
        line-height: 1;
    }
    .price-sin {
        font-size: 12px;
        color: #bbb;
        margin-top: 2px;
        font-style: italic;
    }
    .price-anterior {
        font-size: 13px;
        color: #ccc;
        text-decoration: line-through;
        margin-bottom: 2px;
    }
    .price-oferta-badge {
        display: inline-block;
        background: #e74c3c;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        border-radius: 4px;
        padding: 1px 6px;
        margin-left: 6px;
        vertical-align: middle;
    }

    .btn-ver {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 10px;
        background: var(--text-main);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s;
    }
    .btn-ver:hover { background: var(--accent); color: #fff; text-decoration: none; }

    /* ── Info banner ── */
    .info-banner {
        background: linear-gradient(90deg, #fff8f4, #fff);
        border: 1px solid #f5d4bb;
        border-radius: 10px;
        padding: 12px 18px;
        font-size: 13px;
        color: #b0502a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* ── Paginación ── */
    .pagination { justify-content: center; margin-top: 20px; }
    .page-link { color: var(--accent); }
    .page-item.active .page-link { background: var(--accent); border-color: var(--accent); }

    /* ── Header user info ── */
    .user-bar {
        background: #1a1a1a;
        padding: 8px 0;
    }
    .user-bar .inner {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 16px;
        font-size: 12px;
        color: rgba(255,255,255,0.7);
    }
    .user-bar a {
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        transition: color 0.15s;
    }
    .user-bar a:hover { color: var(--accent); }
</style>
@endsection

@section('hide_header_footer', true)

@section('content')

{{-- ── User bar ── --}}
<div class="user-bar">
    <div class="container inner">
        <span>
            <i class="fa-solid fa-circle-user" style="color: #ee7c31; margin-right:4px;"></i>
            {{ auth()->user()->nombres ?? auth()->user()->username }}
            <span style="background: #ee7c31; color:#fff; border-radius:4px; font-size:10px; padding:1px 6px; margin-left:6px; font-weight:700;">CLIENTE</span>
        </span>
        <span style="color: rgba(255,255,255,0.2);">|</span>
        <a href="{{ url('/') }}" target="_blank">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Ir al sitio web
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline; margin:0;">
            @csrf
            <button type="submit" style="background:none; border:none; color:rgba(255,255,255,0.6); font-size:12px; cursor:pointer; padding:0; transition:color 0.15s;"
                    onmouseover="this.style.color='#ee7c31'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
            </button>
        </form>
    </div>
</div>

{{-- ── Portal hero ── --}}
<div class="portal-hero">
    <div class="container" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <div>
            <h1><i class="fa-solid fa-tag" style="color:#ee7c31; margin-right:8px;"></i>Portal de Cotización</h1>
            <p class="subtitle">Precios exclusivos para clientes verificados</p>
        </div>
        <span class="badge-cliente">
            <i class="fa-solid fa-shield-halved"></i> Acceso verificado
        </span>
    </div>
</div>

{{-- ── Buscador ── --}}
<div class="search-bar">
    <div class="container">
        <form action="{{ route('cotizar.index') }}" method="GET" class="">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Buscar por nombre o N° de parte...">
            <button type="submit">
                <i class="fa-solid fa-magnifying-glass"></i> Buscar
            </button>
            @if(request('buscar'))
                <a href="{{ route('cotizar.index') }}"
                   style="font-size:13px; color:#888; text-decoration:none; white-space:nowrap;">
                    × Limpiar
                </a>
            @endif
        </form>
    </div>
</div>

{{-- ── Main content ── --}}
<div class="container">

    @if(request('buscar'))
        <div class="info-banner" style="margin-top:20px;">
            <i class="fa-solid fa-circle-info"></i>
            Resultados para: <strong>"{{ request('buscar') }}"</strong>
            — {{ $productos->total() }} producto(s) encontrado(s)
        </div>
    @else
        <div style="height:20px;"></div>
    @endif

    <div class="info-banner">
        <i class="fa-solid fa-lock-open" style="color:#ee7c31;"></i>
        Los precios mostrados son <strong>referenciales</strong> y pueden cambiar. Contáctanos para confirmar disponibilidad.
    </div>

    @if($productos->count())
        <div class="productos-grid">
            @foreach($productos as $producto)
                @php
                    $imagen = $producto->modelo && $producto->modelo->img_mod
                        ? asset('storage/' . $producto->modelo->img_mod)
                        : ($producto->imagen_1
                            ? asset('storage/' . $producto->imagen_1)
                            : asset('producto.jpg'));
                @endphp
                <div class="prod-card">
                    <a href="{{ route('cotizar.detalle', $producto->id) }}" class="img-wrap" tabindex="-1">
                        <img src="{{ $imagen }}"
                             alt="{{ $producto->display_name }}"
                             onerror="this.src='{{ asset('producto.jpg') }}'">
                    </a>
                    <div class="card-body">
                        <div class="card-cat">
                            {{ optional($producto->getCategoria)->nombre ?? '—' }}
                        </div>
                        <div class="card-name">{{ $producto->display_name }}</div>
                        @if($producto->nro_parte)
                            <div class="card-nro"># {{ $producto->nro_parte }}</div>
                        @endif

                        {{-- ── PRECIO (razón de ser del portal) ── --}}
                        <div class="price-block">
                            <div class="price-label">Precio referencial</div>
                            @if($producto->precio_anterior)
                                <div class="price-anterior">
                                    S/ {{ number_format($producto->precio_anterior, 2) }}
                                    <span class="price-oferta-badge">OFERTA</span>
                                </div>
                            @endif
                            @if($producto->precio_unitario)
                                <div class="price-value">
                                    S/ {{ number_format($producto->precio_unitario, 2) }}
                                </div>
                            @else
                                <div class="price-sin">Precio a consultar</div>
                            @endif
                        </div>

                        <a href="{{ route('cotizar.detalle', $producto->id) }}" class="btn-ver">
                            <i class="fa-solid fa-eye"></i> Ver ficha completa
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $productos->links() }}
        <div style="height: 32px;"></div>

    @else
        <div style="text-align:center; padding: 80px 20px; color: #aaa;">
            <i class="fa-solid fa-box-open" style="font-size:48px; margin-bottom:16px; display:block;"></i>
            <p style="font-size:16px;">No se encontraron productos{{ request('buscar') ? ' para "'.request('buscar').'"' : '' }}.</p>
            @if(request('buscar'))
                <a href="{{ route('cotizar.index') }}" style="color: #ee7c31;">Ver todos los productos</a>
            @endif
        </div>
    @endif

</div>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"
    integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
