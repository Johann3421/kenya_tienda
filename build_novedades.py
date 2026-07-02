import re

with open('D:/SISTEMAS 02/Downloads/prueba4.html', 'r', encoding='utf-8') as f:
    html = f.read()

# Extract CSS
css_match = re.search(r'<style>(.*?)</style>', html, re.DOTALL)
if css_match:
    css = css_match.group(1)
    
    # We remove the body, * {}, header and footer parts
    # A simple way is just to take everything from .hero-banner onwards
    css_start = css.find('.hero-banner {')
    if css_start != -1:
        css = css[css_start:]
    else:
        css = ''
else:
    css = ''

# The rest of the blade logic
blade_content = '''@extends('layouts.landing')

@section('title', 'Novedades')
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li class="kenya-active"><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <?php
    use App\Producto;
    use App\Modelo;

    $busqueda = request('busqueda');
    $modeloId = request('modelo');
    $orden = request('orden', 'newest');

    $productosQuery = Producto::query()
        ->where('pagina_web', 'SI')
        ->noSuspendido();

    if ($busqueda) {
        $productosQuery->where('descripcion', 'LIKE', "%{$busqueda}%")->orWhere('nro_parte', 'LIKE', "%{$busqueda}%");
    }

    if ($modeloId) {
        $productosQuery->where('modelo_id', $modeloId);
    }

    switch ($orden) {
        case 'nombre_asc':
            $productosQuery->orderBy('descripcion', 'asc');
            break;
        case 'nombre_desc':
            $productosQuery->orderBy('descripcion', 'desc');
            break;
        case 'oldest':
            $productosQuery->orderBy('created_at', 'asc');
            break;
        case 'newest':
        default:
            $productosQuery->orderBy('created_at', 'desc');
    }

    $modelos = Modelo::whereRaw("UPPER(activo) = 'SI'")
        ->whereHas('getProducto', function ($q) {
            $q->where('pagina_web', 'SI')->noSuspendido();
        })
        ->orderBy('descripcion')
        ->get();

    $productos = $productosQuery->with('modelo')->paginate(12);
    ?>

    <style>
''' + css + '''
    </style>

    <section class="hero-banner">
        <div class="container hero-content">
            <h1>Nuestras Novedades</h1>
            <p>Descubre nuestra amplia gama de equipos de alta calidad</p>
        </div>
    </section>

    <section class="products-section container">
        <div class="filter-bar">
            <form method="GET" action="" style="display: flex; width: 100%; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                <div class="search-box">
                    <input type="text" name="busqueda" placeholder="Buscar productos..." value="{{ request('busqueda') }}">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <div class="filter-dropdowns">
                    <select name="modelo" onchange="this.form.submit()">
                        <option value="">Todos los modelos</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->id }}" {{ request('modelo') == $modelo->id ? 'selected' : '' }}>
                                {{ $modelo->descripcion }}
                            </option>
                        @endforeach
                    </select>
                    <select name="orden" onchange="this.form.submit()">
                        <option value="newest" {{ $orden == 'newest' ? 'selected' : '' }}>Más recientes</option>
                        <option value="oldest" {{ $orden == 'oldest' ? 'selected' : '' }}>Más antiguos</option>
                        <option value="nombre_asc" {{ $orden == 'nombre_asc' ? 'selected' : '' }}>Nombre (A-Z)</option>
                        <option value="nombre_desc" {{ $orden == 'nombre_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="product-grid">
            @forelse($productos as $producto)
                @php
                    $modelImg = asset('producto.jpg');
                    if ($producto->modelo && !empty($producto->modelo->img_mod)) {
                        $modelImg = asset('storage/' . $producto->modelo->img_mod);
                    } elseif ($producto->getCategoria && !empty($producto->getCategoria->img_url)) {
                        $modelImg = $producto->getCategoria->img_url;
                    }

                    if (!empty($producto->imagen_1)) {
                        $img    = asset('storage/' . $producto->imagen_1);
                        $imgFb  = asset($producto->imagen_1);
                        $imgFb2 = $modelImg;
                    } elseif (!empty($producto->imagen)) {
                        $img    = asset('storage/' . $producto->imagen);
                        $imgFb  = $modelImg;
                        $imgFb2 = asset('producto.jpg');
                    } else {
                        $img    = $modelImg;
                        $imgFb  = asset('producto.jpg');
                        $imgFb2 = asset('producto.jpg');
                    }
                    $stock = $producto->stock ?? 100;
                @endphp
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ $img }}" alt="{{ $producto->display_name ?? 'Producto' }}" 
                            onerror="if(!this.dataset.fb){this.dataset.fb=1;this.src='{{ $imgFb }}';}else if(this.dataset.fb=='1'){this.dataset.fb=2;this.src='{{ $imgFb2 }}';}else{this.onerror=null;}">
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $producto->display_name ?? 'Nombre no disponible' }}</h3>
                        <p class="product-details">N&deg; de parte: {{ $producto->nro_parte ?? 'N/A' }}</p>
                        <p class="product-details">Stock: 
                            @if($stock > 0)
                                <span class="stock-green">&ge; {{ $stock }} unidades</span>
                            @else
                                <span class="stock-red" style="color: #d9534f; font-weight: 500;">Agotado</span>
                            @endif
                        </p>
                        <button class="btn-details" onclick="window.location.href='{{ url('/producto/' . $producto->id . '/detalle') }}'">VER DETALLES</button>
                    </div>
                </div>
            @empty
                <div class="col-12" style="grid-column: 1 / -1;">
                    <div class="alert alert-warning" style="padding: 20px; background-color: #fff3cd; color: #856404; border-radius: 8px;">No se encontraron productos.</div>
                </div>
            @endforelse
        </div>

        @if ($productos->hasPages())
            <div class="pagination-container" style="margin-top: 40px; display: flex; justify-content: center;">
                {{ $productos->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
'''

with open('resources/views/Novedades.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade_content)

print("Novedades updated!")
