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
                            } elseif ($producto->getCategoria && !empty($producto->getCategoria->img_cat)) {
                                $modelImg = asset('storage/' . $producto->getCategoria->img_cat);
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

                        <img src="{{ $img }}" alt="{{ $producto->nombre ?? 'Producto' }}" class="img-fluid"
                             onerror="if(!this.dataset.fb){this.dataset.fb=1;this.src='{{ $imgFb }}';}else if(this.dataset.fb=='1'){this.dataset.fb=2;this.src='{{ $imgFb2 }}';}else{this.onerror=null;}">

                        <div class="product-actions">
                            <button class="quick-view" data-id="{{ $producto->id }}">
                                <i class="bx bx-search"></i>
                            </button>
                        </div>
                    </div>

                    <div class="product-info">
                        <span class="product-category">
                            {{ $producto->modelo->nombre ?? ($producto->modelo->descripcion ?? 'Sin categoría') }}
                        </span>

                        <h3 class="product-title">{{ $producto->nombre ?? 'Nombre no disponible' }}</h3>

                        <div class="product-details">
                            <p><strong>Parte:</strong> {{ $producto->nro_parte ?? 'N/A' }}</p>
                            <p><strong>Stock:</strong>
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

    @if ($productos->hasPages())
        <div class="catalog-pagination mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $productos->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $productos->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                    </li>

                    @foreach (range(max(1, $productos->currentPage() - 2), min($productos->lastPage(), $productos->currentPage() + 2)) as $page)
                        <li class="page-item {{ $productos->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $productos->url($page) }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <li class="page-item {{ !$productos->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $productos->nextPageUrl() }}">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    @endif
</div>
