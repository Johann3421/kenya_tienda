@extends('layouts.landing') {{-- Asegúrate de tener tu header/footer aquí --}}

@section('title', 'Quiénes Somos | Fabricante y Distribuidor de Computadoras en Perú | KENYA Technology')
@section('meta_description', 'Conoce la historia y trayectoria de KENYA Technology (IMPORTACIONES KENYA). Fabricante y distribuidor de computadoras de escritorio, laptops y soluciones B2B con 36 meses de garantía On-Site en todo el Perú.')
@section('meta_keywords', 'quienes somos kenya, importaciones kenya, kenya technology, fabricante computadoras peru, empresa computo peru')
@section('canonical', route('quienes.somos'))
@section('og_title', 'Quiénes Somos | KENYA Technology Perú')
@section('og_description', 'Fabricante y distribuidor peruano de computadoras de escritorio y soluciones tecnológicas B2B.')
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li class="kenya-active"><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catalogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            {{-- Sorteo temporalmente oculto en producción --}}
            {{-- <li><a href="{{ route('serial.draw') }}" class="kenya-nav-link">🎁 Sorteo</a></li> --}}
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <style>
        #quienes-somos-page {
            background-color: #ffffff;
            color: #333;
            line-height: 1.6;
        }

        #quienes-somos-page .hero-banner {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('{{ asset("banersomos.png?v=2") }}');
            background-size: cover;
            background-position: right;
            color: #000000;
            text-align: left;
            padding: 80px 5px;
            margin-bottom: 0px;
        }

        #quienes-somos-page .hero-content {
            position: relative;
            z-index: 2; 
            padding-left: 20px; 
            display: flex;
            flex-direction: column;
            align-items: flex-start; 
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        #quienes-somos-page .hero-content h1 {
            font-size: 3rem;
            margin-bottom: -3px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
        }

        #quienes-somos-page .hero-content p {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 20px; 
        }

        #quienes-somos-page .about-section {
            padding: 70px 0;
            background-color: #ffffff;
        }

        html {
            scroll-behavior: smooth;
        }

        #quienes-somos-page .about-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        #quienes-somos-page .about-main-slot {
            width: 100%;
            margin-bottom: 50px;
        }

        #quienes-somos-page .about-intro {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            scroll-margin-top: 110px;
            border-radius: 12px;
            background-color: #fffaf7;
            padding: 35px 35px 30px;
            border-left: 4px solid #f26522;
            box-shadow: 0 8px 24px rgba(242, 101, 34, 0.08);
            transition: all 0.35s ease;
        }

        #quienes-somos-page .about-intro .about-text {
            flex: 1;
        }

        #quienes-somos-page .about-intro .about-text h2 {
            font-size: 2.2rem;
            color: #111;
            margin-bottom: 22px;
            font-weight: 700;
        }

        #quienes-somos-page .about-intro .about-description {
            position: relative;
            padding-left: 20px;
            margin-bottom: 0;
        }

        #quienes-somos-page .about-intro .about-description::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 3px;
            background-color: #f26522;
        }

        #quienes-somos-page .about-intro .about-description p {
            color: #444;
            font-size: 1.05rem;
            line-height: 1.75;
            margin-bottom: 12px;
        }

        #quienes-somos-page .about-intro .about-description p:last-child {
            margin-bottom: 0;
        }

        #quienes-somos-page .about-intro .card-swap-indicator {
            display: none !important;
        }

        /* ── Estilos de Tarjetas en el Grid ── */
        #quienes-somos-page .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        #quienes-somos-page .value-card {
            background-color: #f8f8f8;
            padding: 35px 30px;
            text-align: left;
            border-radius: 12px;
            border: 2px solid #eeeeee;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease, background-color 0.3s ease;
            scroll-margin-top: 110px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        #quienes-somos-page .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-color: #f26522;
            background-color: #ffffff;
        }

        #quienes-somos-page .value-card .about-text h2 {
            font-size: 1.3rem;
            color: #111;
            margin-bottom: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        #quienes-somos-page .value-card .about-description {
            padding-left: 0;
            margin-bottom: 0;
            position: relative;
        }

        #quienes-somos-page .value-card .about-description::before {
            display: none !important;
        }

        #quienes-somos-page .value-card .about-description p {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        #quienes-somos-page .value-card .about-description p:last-child {
            margin-bottom: 0;
        }

        #quienes-somos-page .value-card .card-swap-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 18px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #f26522;
            transition: all 0.2s ease;
        }

        #quienes-somos-page .value-card:hover .card-swap-indicator {
            color: #d96b20;
            transform: translateX(4px);
        }

        #quienes-somos-page .icon-title {
            color: #f26522;
            margin-right: 12px;
            font-size: 1.5rem;
        }

        @keyframes swapFadeIn {
            0% {
                opacity: 0.3;
                transform: translateY(-8px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #quienes-somos-page .swap-animated {
            animation: swapFadeIn 0.35s ease-out;
        }

        @media (max-width: 992px) {
            #quienes-somos-page .about-intro {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            #quienes-somos-page .hero-banner { padding: 50px 20px; }
            #quienes-somos-page .hero-content h1 { font-size: 2.2rem; }
            #quienes-somos-page .hero-content p { font-size: 1.1rem; }
            #quienes-somos-page .about-intro { padding: 25px 20px; }
            #quienes-somos-page .about-intro .about-text h2 { font-size: 1.8rem; }
        }
    </style>

    <div id="quienes-somos-page">
        <!-- ==========================================
             BANNER "QUIÉNES SOMOS"
             ========================================== -->
        <section class="hero-banner">
            <div class="hero-content">
                <h1>¿Quiénes Somos?</h1>
                <p>Innovación, confianza y tecnología al alcance de todos.</p>
            </div>
        </section>
        
        <!-- ==========================================
             SECCIÓN INFORMACIÓN Y VALORES (SWAP DINÁMICO)
             ========================================== -->
        <section class="about-section">
            <div class="about-container">
                <!-- Contenedor del elemento principal -->
                <div class="about-main-slot" id="about-main-slot">
                    <div class="about-item about-intro" id="historia">
                        <div class="about-text">
                            <h2><i class="fa-solid fa-clock-rotate-left icon-title"></i> Nuestra Historia</h2>
                            <div class="about-description">
                                <p>Desde nuestros inicios, en <strong>KENYA TECHNOLOGY</strong> apostamos por crear computadoras de alto desempeño adaptadas a las necesidades Gubernamentales del mercado nacional en crecimiento.</p>
                                <p>Con una trayectoria basada en innovación, calidad y compromiso, hemos acompañado a nuestros usuarios ofreciendo equipos Informáticos con la más avanzada tecnología, excelente rendimiento y altos estándares de calidad.</p>
                                <p>Hoy continuamos creciendo como una marca orgullosamente peruana enfocada en desarrollar computadoras confiables, eficientes y preparadas para resolver los distintos retos geográficos de Costa, Sierra y Selva de nuestro Perú.</p>
                                <p>Nos especializamos en la fabricación y comercialización de equipos de cómputo con componentes de la más alta calidad y garantía, diseño moderno y tecnología de última generación, ofreciendo una experiencia superior en cada equipo.</p>
                            </div>
                            <div class="card-swap-indicator">
                                <span>Ver en sección principal <i class="fa-solid fa-arrow-up"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid inferior de tarjetas -->
                <div class="values-grid" id="about-values-grid">
                    <div class="about-item value-card" id="mision">
                        <div class="about-text">
                            <h2><i class="fa-solid fa-bullseye icon-title"></i> Nuestra Misión</h2>
                            <div class="about-description">
                                <p>Desarrollar computadoras de alto rendimiento que brinden potencia, eficiencia y confiabilidad, ofreciendo a nuestros clientes la mejor experiencia tecnológica en cada equipo KENYA TECHNOLOGY.</p>
                            </div>
                            <div class="card-swap-indicator">
                                <span>Ver en sección principal <i class="fa-solid fa-arrow-up"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="about-item value-card" id="vision">
                        <div class="about-text">
                            <h2><i class="fa-solid fa-eye icon-title"></i> Nuestra Visión</h2>
                            <div class="about-description">
                                <p>Ser la marca peruana de computadoras más reconocida y confiable a nivel nacional e internacional, destacando por nuestra innovación, calidad, rendimiento y compromiso con el medio ambiente.</p>
                            </div>
                            <div class="card-swap-indicator">
                                <span>Ver en sección principal <i class="fa-solid fa-arrow-up"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="about-item value-card" id="valores">
                        <div class="about-text">
                            <h2><i class="fa-solid fa-hand-holding-heart icon-title"></i> Nuestros Valores</h2>
                            <div class="about-description">
                                <p>Nuestros principios como marca Kenya Technology nos ayudan a conectarnos con la cultura de las empresas privadas y/o gubernamentales, siendo fundamentales para que podamos seguir creciendo dentro de nuestra gran familia, siempre basándonos en la:</p>
                                <ul class="valores-list" style="margin: 12px 0 0 18px; padding: 0; color: #444; font-size: 0.95rem; line-height: 1.8;">
                                    <li><strong>Actitud.</strong></li>
                                    <li><strong>Ética.</strong></li>
                                    <li><strong>Transparencia.</strong></li>
                                    <li><strong>Responsabilidad.</strong></li>
                                </ul>
                            </div>
                            <div class="card-swap-indicator">
                                <span>Ver en sección principal <i class="fa-solid fa-arrow-up"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainSlot = document.getElementById('about-main-slot');
            const valuesGrid = document.getElementById('about-values-grid');

            if (!mainSlot || !valuesGrid) return;

            function activateSection(targetId, shouldScroll = true) {
                if (!targetId) return;
                const target = document.getElementById(targetId);
                if (!target) return;

                const currentMain = mainSlot.firstElementChild;
                if (!currentMain) return;

                if (target !== currentMain) {
                    // Placeholder temporal en la posición exacta del grid
                    const placeholder = document.createElement('div');
                    valuesGrid.insertBefore(placeholder, target);

                    // Mover target al slot principal
                    mainSlot.appendChild(target);
                    target.classList.remove('value-card');
                    target.classList.add('about-intro', 'swap-animated');

                    // Mover el elemento principal anterior a la posición que ocupaba el target
                    valuesGrid.insertBefore(currentMain, placeholder);
                    placeholder.remove();
                    currentMain.classList.remove('about-intro', 'swap-animated');
                    currentMain.classList.add('value-card');
                } else {
                    target.classList.remove('swap-animated');
                    void target.offsetWidth;
                    target.classList.add('swap-animated');
                }

                if (shouldScroll) {
                    const header = document.querySelector('.site-header');
                    const headerOffset = (header ? header.offsetHeight : 80) + 20;
                    const rect = mainSlot.getBoundingClientRect();
                    const targetY = window.pageYOffset + rect.top - headerOffset;
                    window.scrollTo({
                        top: Math.max(0, targetY),
                        behavior: 'smooth'
                    });
                }
            }

            // Clic en tarjetas del grid para ascender a principal
            valuesGrid.addEventListener('click', function(e) {
                const card = e.target.closest('.about-item');
                if (card && card.id) {
                    activateSection(card.id, true);
                    history.replaceState(null, '', '#' + card.id);
                }
            });

            // Procesar hash de la URL
            function checkHash(shouldScroll) {
                const rawHash = window.location.hash ? window.location.hash.substring(1) : '';
                if (rawHash && ['historia', 'mision', 'vision', 'valores'].includes(rawHash)) {
                    activateSection(rawHash, shouldScroll);
                }
            }

            if (window.location.hash) {
                setTimeout(function() {
                    checkHash(true);
                }, 150);
            }

            window.addEventListener('hashchange', function() {
                checkHash(true);
            });
        });
    </script>
@endsection
