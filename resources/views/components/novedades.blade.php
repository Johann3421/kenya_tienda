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

        .novedades-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .novedades-title-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .novedades-title-section h2 {
            font-size: 2.5rem;
            color: #212121;
            margin-bottom: 15px;
        }

        .novedades-title-section p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Contenedor del carrusel */
        .novedades-carousel-wrapper {
            position: relative;
            overflow: hidden;
        }

        /* Pista del carrusel */
        .novedades-carousel-track {
            display: flex;
            transition: transform 0.5s ease;
            gap: 20px;
        }

        /* Items del carrusel */
        .novedades-carousel-item {
            flex: 0 0 calc(25% - 15px);
            min-width: calc(25% - 15px);
            padding: 0 5px;
        }

        /* Tarjeta de producto */
        .novedades-product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .novedades-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Badge "Nuevo" */
        .novedades-badge {
            background: #ee7c31;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            z-index: 2;
        }

        /* Imagen del producto */
        .novedades-image-container {
            position: relative;
            width: 100%;
            aspect-ratio: 4/3;
            overflow: hidden;
        }

        .novedades-product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .novedades-product-card:hover .novedades-product-image {
            transform: scale(1.05);
        }

        /* Overlay de imagen */
        .novedades-image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            color: white;
        }

        .novedades-image-overlay h6 {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Detalles del producto */
        .novedades-product-details {
            padding: 15px;
        }

        .novedades-product-title {
            margin: 0;
            font-size: 1rem;
            text-align: center;
        }

        .novedades-product-title a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .novedades-product-title a:hover {
            color: #ee7c31;
        }

        /* Controles de navegación */
        .novedades-carousel-prev,
        .novedades-carousel-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: white;
            border: none;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .novedades-carousel-prev {
            left: 0;
        }

        .novedades-carousel-next {
            right: 0;
        }

        .novedades-carousel-prev:hover,
        .novedades-carousel-next:hover {
            background: #ee7c31;
            color: white;
        }

        /* Indicadores */
        .novedades-carousel-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .novedades-carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .novedades-carousel-dot.active {
            background: #ee7c31;
            transform: scale(1.2);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .novedades-carousel-item {
                flex: 0 0 calc(50% - 10px);
                min-width: calc(50% - 10px);
            }

            .novedades-image-container {
                aspect-ratio: 16/9;
            }
        }

        @media (max-width: 768px) {
            .novedades-carousel-wrapper {
                padding: 0 30px;
            }

            .novedades-carousel-item {
                flex: 0 0 calc(50% - 10px);
                min-width: calc(50% - 10px);
            }
        }

        @media (max-width: 576px) {
            .novedades-carousel-item {
                flex: 0 0 100%;
                min-width: 100%;
            }

            .novedades-carousel-wrapper {
                padding: 0 20px;
            }

            .novedades-image-container {
                aspect-ratio: 3/2;
            }

            .novedades-carousel-prev,
            .novedades-carousel-next {
                width: 30px;
                height: 30px;
            }
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
                                     alt="{{ $prod->display_name }}"
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
                                        {{ Str::limit($prod->display_name, 100) }}
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
