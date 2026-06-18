@php
    // expects $productos paginator
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $productos */
@endphp
<div class="product-grid">
    <div class="row">
        @forelse($productos as $producto)
            <div class="col-lg-4 col-md-4 col-sm-6 mb-4">
                <div class="product-card">
                    @php
                        $stock = $producto->stock ?? '≥ 20';
                    @endphp
                    @if ($stock === 0 || $stock === '0')
                        <div class="product-badge out-of-stock">Agotado</div>
                    @elseif(isset($producto->created_at) && \Carbon\Carbon::parse($producto->created_at)->diffInDays(now()) <= 30)
                        <div class="product-badge">Nuevo</div>
                    @endif

                    <div class="product-image">
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
                        @endphp

                        <img src="{{ $img }}" alt="{{ $producto->display_name ?? $producto->nombre ?? 'Producto' }}" class="img-fluid"
                             onerror="if(!this.dataset.fb){this.dataset.fb=1;this.src='{{ $imgFb }}';}else if(this.dataset.fb=='1'){this.dataset.fb=2;this.src='{{ $imgFb2 }}';}else{this.onerror=null;}">

                        <div class="product-actions">
                            <button class="quick-view" data-id="{{ $producto->id }}">
                                <i class="bx bx-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="product-info">
                        @php
                            $rawName = $producto->display_name ?? $producto->nombre ?? 'Nombre no disponible';
                            // Eliminar número de parte entre paréntesis del nombre (ya se muestra abajo)
                            $cleanName = preg_replace('/\s*\([A-Z0-9\-\.]+\)\s*$/i', '', $rawName);
                        @endphp

                        <h3 class="product-title">{{ trim($cleanName) }}</h3>

                        <div class="product-details">
                            <p class="detail-row">
                                <span class="detail-label">N° de parte:</span>
                                <span class="detail-value">{{ $producto->nro_parte ?? 'N/A' }}</span>
                            </p>
                            <p class="detail-row">
                                <span class="detail-label">Stock:</span>
                                @php
                                    $stock = $producto->stock ?? '≥ 20';
                                @endphp
                                @if ($stock !== 0 && $stock !== '0')
                                    <span class="in-stock">{{ $stock }} unidades</span>
                                @else
                                    <span class="out-of-stock">No disponible</span>
                                @endif
                            </p>
                        </div>

                        <button class="view-details"
                                onclick="window.location.href='{{ url('producto/'.$producto->id.'/detalle') }}'">
                            Ver detalles
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">No se encontraron productos.</div>
            </div>
        @endforelse
    </div>

    @php $total = $productos->total(); @endphp
    @if ($total > 0)
        <div class="catalog-pagination mt-4" style="display:flex; align-items:center; justify-content:center; position:relative; flex-wrap:wrap; gap:12px;">
            @if ($productos->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item {{ $productos->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $productos->url(1) }}" aria-label="Primera">&laquo;&laquo;</a>
                        </li>
                        <li class="page-item {{ $productos->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $productos->previousPageUrl() }}" aria-label="Anterior">&lsaquo;</a>
                        </li>

                        @if ($productos->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $productos->url(1) }}">1</a>
                            </li>
                            @if ($productos->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                            @endif
                        @endif

                        @foreach (range(max(1, $productos->currentPage() - 2), min($productos->lastPage(), $productos->currentPage() + 2)) as $page)
                            <li class="page-item {{ $productos->currentPage() == $page ? 'active' : '' }}">
                                <a class="page-link" href="{{ $productos->url($page) }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        @if ($productos->currentPage() < $productos->lastPage() - 2)
                            @if ($productos->currentPage() < $productos->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $productos->url($productos->lastPage()) }}">{{ $productos->lastPage() }}</a>
                            </li>
                        @endif

                        <li class="page-item {{ !$productos->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $productos->nextPageUrl() }}" aria-label="Siguiente">&rsaquo;</a>
                        </li>
                        <li class="page-item {{ !$productos->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $productos->url($productos->lastPage()) }}" aria-label="Última">&raquo;&raquo;</a>
                        </li>
                    </ul>
                </nav>
            @endif
            <span class="catalog-total" style="font-size:13px; color:#999; font-weight:600; white-space:nowrap; {{ $productos->hasPages() ? '' : 'margin:0 auto;' }}">
                {{ $total }} producto{{ $total != 1 ? 's' : '' }}
            </span>
        </div>
    @endif
</div>
