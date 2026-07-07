@php
    // expects $productos paginator
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $productos */
@endphp
<div class="products-grid">
    @forelse($productos as $producto)
        <div class="product-card">
            @php
                $stock = 20;
                if(isset($producto->procesador)) {
                    $proc = strtolower($producto->procesador);
                    if(str_contains($proc, 'ultra')) $stock = 20;
                    elseif(str_contains($proc, '14') || str_contains($proc, '14th')) $stock = 20;
                    elseif(str_contains($proc, '13') || str_contains($proc, '13th')) $stock = 3;
                    elseif(str_contains($proc, '12') || str_contains($proc, '12th')) $stock = 10;
                }
            @endphp
            @if(isset($producto->created_at) && \Carbon\Carbon::parse($producto->created_at)->diffInDays(now()) <= 30)
                <div class="badge-nuevo">Nuevo</div>
            @endif

            <div class="product-image-wrapper">
                @php
                    $modelImg = asset('producto.jpg');
                    if ($producto->modelo && !empty($producto->modelo->img_mod)) {
                        $modelImg = asset('storage/' . $producto->modelo->img_mod);
                    } elseif ($producto->getCategoria && !empty($producto->getCategoria->img_url)) {
                        $modelImg = $producto->getCategoria->img_url;
                    }

                    $img = $modelImg;
                    if (!empty($producto->imagen_1)) {
                        if (file_exists(public_path('storage/' . $producto->imagen_1))) {
                            $img = asset('storage/' . $producto->imagen_1);
                        } elseif (file_exists(public_path($producto->imagen_1))) {
                            $img = asset($producto->imagen_1);
                        }
                    } 
                    if ($img === $modelImg && !empty($producto->imagen)) {
                        if (file_exists(public_path('storage/' . $producto->imagen))) {
                            $img = asset('storage/' . $producto->imagen);
                        } elseif (file_exists(public_path($producto->imagen))) {
                            $img = asset($producto->imagen);
                        }
                    }
                    $imgFb  = $modelImg;
                    $imgFb2 = asset('producto.jpg');
                @endphp

                <img src="{{ $img }}" alt="{{ $producto->display_name ?? $producto->nombre ?? 'Producto' }}"
                     onerror="if(!this.dataset.fb){this.dataset.fb=1;this.src='{{ $imgFb }}';}else if(this.dataset.fb=='1'){this.dataset.fb=2;this.src='{{ $imgFb2 }}';}else{this.onerror=null;}">
            </div>

            @php
                $rawName = $producto->display_name ?? $producto->nombre ?? 'Nombre no disponible';
                $cleanName = preg_replace('/\s*\([A-Z0-9\-\.]+\)\s*$/i', '', $rawName);
            @endphp

            <h3 class="product-title">{{ trim($cleanName) }}</h3>
            <p class="product-pn">N&deg; de parte: {{ $producto->nro_parte ?? 'N/A' }}</p>

            <p class="product-stock">Stock: 
            @if ($stock !== 0 && $stock !== '0')
                <span>≥ {{ $stock }}</span>
            @else
                <span style="color:#f26522;">Agotado</span>
            @endif
            </p>

            <button class="btn-details" onclick="window.location.href='{{ url('producto/'.$producto->id.'/detalle') }}'">
                Ver detalles
            </button>
        </div>
    @empty
        <div class="col-12" style="grid-column: 1 / -1;">
            <div class="alert alert-warning" style="text-align: center; padding: 20px; background: #fff3cd; border-radius: 8px;">No se encontraron productos.</div>
        </div>
    @endforelse
</div>

@php $total = $productos->total(); @endphp
@if ($total > 0)
    <div class="pagination-container">
        @if ($productos->hasPages())
            <div class="pagination">
                @if ($productos->onFirstPage())
                    <span class="page-item dots disabled">&laquo;&laquo;</span>
                    <span class="page-item dots disabled">&lsaquo;</span>
                @else
                    <a class="page-item" href="{{ $productos->url(1) }}">&laquo;&laquo;</a>
                    <a class="page-item" href="{{ $productos->previousPageUrl() }}">&lsaquo;</a>
                @endif

                @if ($productos->currentPage() > 3)
                    <a class="page-item" href="{{ $productos->url(1) }}">1</a>
                    @if ($productos->currentPage() > 4)
                        <span class="page-item dots">&hellip;</span>
                    @endif
                @endif

                @foreach (range(max(1, $productos->currentPage() - 2), min($productos->lastPage(), $productos->currentPage() + 2)) as $page)
                    @if ($productos->currentPage() == $page)
                        <span class="page-item active">{{ $page }}</span>
                    @else
                        <a class="page-item" href="{{ $productos->url($page) }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($productos->currentPage() < $productos->lastPage() - 2)
                    @if ($productos->currentPage() < $productos->lastPage() - 3)
                        <span class="page-item dots">&hellip;</span>
                    @endif
                    <a class="page-item" href="{{ $productos->url($productos->lastPage()) }}">{{ $productos->lastPage() }}</a>
                @endif

                @if (!$productos->hasMorePages())
                    <span class="page-item dots disabled">&rsaquo;</span>
                    <span class="page-item dots disabled">&raquo;&raquo;</span>
                @else
                    <a class="page-item" href="{{ $productos->nextPageUrl() }}">&rsaquo;</a>
                    <a class="page-item" href="{{ $productos->url($productos->lastPage()) }}">&raquo;&raquo;</a>
                @endif
            </div>
        @endif
        <span class="products-count">
            {{ $total }} producto{{ $total != 1 ? 's' : '' }}
        </span>
    </div>
@endif
