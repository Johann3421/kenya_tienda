@php
    // expects $productos paginator
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $productos */
@endphp
<div class="products-grid">
    @forelse($productos as $producto)
        <div class="product-card">
            @php
                $stock = $producto->modelo->stock_vigente ?? $producto->stock_inicial ?? 20;
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
                
                // Normalizadores para consistencia con los filtros
                $normalizarTV = function(string $v): string {
                    $v = preg_replace('/\b(dedicad[oa]s?|integrad[oa]s?)\b/i', '', trim($v));
                    $v = preg_replace('/\s+/', ' ', trim($v));
                    $v = preg_replace('/\s*-\s*/', '-', trim($v));
                    $v = preg_replace('/\bConectividadº?\b/i', '', trim($v));
                    if (preg_match('/^(.*?\d+\s*GB(?:\s*G?DDR\d[X]?)?)/i', $v, $m)) {
                        $v = trim($m[1]);
                    } else {
                        $v = preg_replace('/\s+(OC|GAMING|EDITION|PLUS|SUPER|BOOST|EX|AERO|EAGLE|VISION|WINDFORCE|PULSE|MECH|TWIN|TUF|ROG|STRIX|NITRO|PHANTOM|REBEL|TRIPLE|DUAL|FAN|GDDR\d+|DDR\d+|V\d+|VR|READY)\b.*/i', '', $v);
                    }
                    $v = preg_replace('/(\d+)\s*GB/i', '$1 GB', $v);
                    $v = preg_replace('/GB\s*(G?DDR\d[X]?)/i', 'GB $1', $v);
                    return trim(ltrim($v, '- '));
                };

                $normalizarCPU = function(string $v): string {
                    return trim(preg_replace('/\s+\d+(\.\d+)?\s*GHZ$/i', '', trim($v)));
                };

                $normalizarRAM = function(string $v): string {
                    $v = trim($v);
                    if (preg_match('/^(\d+\s*GB\s+DDR\d\s+\d{3,4})/i', $v, $m)) {
                        return trim(strtoupper($m[1])) . ' MHz';
                    }
                    return strtoupper(trim(preg_replace('/\s+/', ' ', $v)));
                };

                $specs = [];
                $isMonitor = (isset($producto->modelo_id) && $producto->modelo_id == 16) || 
                             (isset($producto->modelo) && str_contains(strtoupper($producto->modelo->descripcion ?? ''), 'MONITOR'));
                
                if ($isMonitor) {
                    if (!$producto->relationLoaded('especificaciones')) {
                        $producto->load('especificaciones');
                    }
                    
                    $especs = $producto->getRelation('especificaciones');
                    if ($especs) {
                        $pantalla = $especs->firstWhere('campo', 'Tamaño de Pantalla')->descripcion ?? null;
                        if ($pantalla) $specs[] = trim($pantalla);
                        
                        $resolucion = $especs->firstWhere('campo', 'Resolución')->descripcion ?? null;
                        if ($resolucion) $specs[] = trim($resolucion);
                    }
                    
                    if (!empty($producto->video_vga)) {
                        $specs[] = 'VGA: ' . trim($producto->video_vga);
                    }
                    if (!empty($producto->video_hdmi)) {
                        $specs[] = 'HDMI: ' . trim($producto->video_hdmi);
                    }
                } else {
                    if (!empty($producto->procesador)) $specs[] = $normalizarCPU($producto->procesador);
                    if (!empty($producto->ram)) $specs[] = $normalizarRAM($producto->ram);
                    if (!empty($producto->almacenamiento)) $specs[] = trim($producto->almacenamiento);
                    if (!empty($producto->sistema_operativo)) $specs[] = trim($producto->sistema_operativo);
                    if (!empty($producto->tarjetavideo)) $specs[] = $normalizarTV($producto->tarjetavideo);
                }
            @endphp

            <h3 class="product-title" title="{{ trim($cleanName) }}">{{ trim($cleanName) }}</h3>
            
            <div class="product-sku" style="background-color: #f0f4f8; padding: 4px 8px; border-radius: 4px; display: inline-block; font-weight: 600; color: #0056b3; margin-bottom: 12px; font-size: 0.75rem; width: fit-content;">
                SKU: {{ $producto->nro_parte ?? 'N/A' }}
            </div>

            @if(count($specs) > 0)
                <div class="product-specs-chips">
                    @foreach($specs as $spec)
                        <span class="spec-chip">{{ $spec }}</span>
                    @endforeach
                </div>
            @endif

            <div class="product-card-footer">
                @if(empty($producto->precio_especial))
                    <div class="price-no-especial" style="font-size: 1.1rem; font-weight: 600; color: #333; margin-top: 2px; margin-bottom: 12px; text-align: left;">
                        (A cotizar)
                    </div>
                @else
                    @if(Auth::guard('cliente')->check())
                        <div class="product-prices-wrapper" style="margin-bottom: 12px; text-align: left; width: 100%;">
                            <div class="price-especial" style="font-size: 1.25rem; font-weight: 700; color: #ee7c31; margin-bottom: 2px;">
                                $ {{ number_format($producto->precio_especial, 2) }}
                            </div>
                            <div class="price-soles" style="font-size: 0.95rem; font-weight: 600; color: #333;">
                                S/ {{ number_format($producto->precio_especial * 3.4, 2) }} <span style="font-size: 0.75rem; font-weight: normal; color: #888;">+ IGV</span>
                            </div>
                        </div>
                    @else
                        <div class="product-prices-locked" style="background: #f8f9fa; border: 1px dashed #ee7c31; border-radius: 6px; padding: 8px; margin-bottom: 12px; font-size: 0.8rem; color: #ee7c31; text-align: center; width: 100%; font-weight: 500;">
                            <i class="fa fa-lock"></i> Precios exclusivos B2B <br>
                            <a href="{{ url('/acceso-clientes') }}" style="color: #0056b3; text-decoration: underline; font-weight: 600;">Ingresa aquí</a> para ver
                        </div>
                    @endif
                @endif

                <div class="product-stock-wrapper">
                    @if ($stock !== 0 && $stock !== '0')
                        <span class="stock-status-dot available"></span>
                        @if ($isMonitor)
                            <span class="stock-text">A pedido</span>
                        @else
                            <span class="stock-text">Disponible (≥ {{ $stock }})</span>
                        @endif
                    @else
                        <span class="stock-status-dot out-of-stock"></span>
                        <span class="stock-text" style="color:#dc3545;">Agotado</span>
                    @endif
                </div>

                <button class="btn-details pill" onclick="window.location.href='{{ url('producto/'.$producto->id.'/detalle') }}'">
                    Más información
                </button>
            </div>
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
