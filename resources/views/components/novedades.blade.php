<!-- resources/views/components/novedades.blade.php -->
<style>
    .novedades-title-section h2 {
    font-size: clamp(1.8rem, 4vw, 2.5rem);
    font-weight: 700;
    text-transform: uppercase;
    color: white !important;
    text-align: center;
    margin: 0 auto 40px;
    padding: 8px 15px;
    position: relative;
    display: inline-block;
    background: linear-gradient(135deg, #ee7c31 0%, #e67125 100%);
    border-radius: 55px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    letter-spacing: 1.5px;
    box-shadow: 0 6px 15px rgba(238, 124, 49, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.3);
    border: 2px solid rgba(255, 255, 255, 0.2);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    transform-style: preserve-3d;
    perspective: 500px;
}
</style>
@isset($novedades)
<section id="novedades" class="novedades-section">
    <div class="novedades-container">
        <div class="novedades-title-section">
            <h2>Novedades</h2>
            <p>Nuevos Productos aumentados a nuestra lista, ¡qué esperas!</p>
        </div>

        <div class="novedades-carousel-wrapper">
            <div class="novedades-carousel-track">
                @foreach ($novedades as $prod)
                    @php
                        // SOLO usa la imagen del modelo (ignora imagen_1 completamente)
                        $imagen = $prod->modelo && $prod->modelo->img_mod
                            ? asset('storage/' . $prod->modelo->img_mod)
                            : asset('producto.jpg');
                    @endphp

                    <div class="novedades-carousel-item filter-{{ $prod->categoria_id }}">
                        <div class="novedades-product-card">
                            <span class="novedades-badge">Nuevo</span>
                            <div class="novedades-image-container">
                                <img src="{{ $imagen }}"
                                     class="novedades-product-image"
                                     alt="{{ $prod->nombre }}"
                                     loading="lazy">

                                <div class="novedades-image-overlay">
                                    @if ($prod->categoria_id && $prod->getCategoria)
                                        <h6>{{ $prod->getCategoria->nombre }}</h6>
                                    @endif
                                </div>
                            </div>

                            <div class="novedades-product-details">
                                <h6 class="novedades-product-title">
                                    <a href="{{ route('producto_detalle', $prod->id) }}">
                                        {{ Str::limit($prod->nombre, 100) }}
                                    </a>
                                </h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="novedades-carousel-prev">
                <i class='bx bx-chevron-left'></i>
            </button>
            <button class="novedades-carousel-next">
                <i class='bx bx-chevron-right'></i>
            </button>

            <div class="novedades-carousel-dots"></div>
        </div>
    </div>
</section>
@endisset
