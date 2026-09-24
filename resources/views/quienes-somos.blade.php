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
            margin-bottom: 40px;
        }

        /* ── Sección Principal Activa ── */
        #quienes-somos-page .about-intro {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            border-top: 4px solid #f26522;
            padding: 38px 40px 34px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            transition: all 0.35s ease;
            scroll-margin-top: 110px;
        }

        #quienes-somos-page .about-intro .about-text {
            width: 100%;
        }

        #quienes-somos-page .about-intro .about-text h2 {
            display: flex;
            align-items: center;
            font-size: 1.95rem;
            color: #0f172a;
            margin-bottom: 22px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        #quienes-somos-page .about-intro .about-description {
            position: relative;
            padding-left: 0;
            margin-bottom: 0;
        }

        /* Eliminamos la barra naranja lateral duplicada */
        #quienes-somos-page .about-intro .about-description::before {
            display: none !important;
        }

        #quienes-somos-page .about-intro .about-description p {
            color: #334155;
            font-size: 1.03rem;
            line-height: 1.8;
            margin-bottom: 14px;
        }

        #quienes-somos-page .about-intro .about-description p strong {
            color: #0f172a;
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
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            align-items: stretch;
        }

        #quienes-somos-page .value-card {
            background-color: #ffffff;
            padding: 26px 24px 20px;
            text-align: left;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            scroll-margin-top: 110px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        #quienes-somos-page .value-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.08);
            border-color: #f26522;
        }

        #quienes-somos-page .value-card .about-text {
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
            flex-grow: 1;
        }

        #quienes-somos-page .value-card .about-text h2 {
            font-size: 1.25rem;
            color: #0f172a;
            margin-bottom: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        #quienes-somos-page .value-card .about-description {
            padding-left: 0;
            margin-bottom: 0;
            position: relative;
            flex-grow: 1;
        }

        #quienes-somos-page .value-card .about-description::before {
            display: none !important;
        }

        /* Si una sección con varios párrafos baja al grid, muestra solo el 1ro para nivelar altura */
        #quienes-somos-page .value-card .about-description p:nth-of-type(n+2) {
            display: none !important;
        }

        #quienes-somos-page .value-card .about-description p {
            color: #475569;
            font-size: 0.93rem;
            line-height: 1.65;
            margin-bottom: 0;
        }

        /* ── Indicador de acción en la tarjeta inferior ── */
        #quienes-somos-page .value-card .card-swap-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
            font-size: 0.84rem;
            font-weight: 600;
            color: #ea580c;
            transition: all 0.2s ease;
            width: 100%;
        }

        #quienes-somos-page .value-card:hover .card-swap-indicator {
            color: #c2410c;
        }

        #quienes-somos-page .value-card .card-swap-indicator i {
            transition: transform 0.2s ease;
        }

        #quienes-somos-page .value-card:hover .card-swap-indicator i {
            transform: translateY(-2px);
        }

        /* ── Íconos en Títulos ── */
        #quienes-somos-page .icon-title {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.25s ease;
        }

        #quienes-somos-page .about-intro .icon-title {
            width: 46px;
            height: 46px;
            background-color: #fff7ed;
            border: 1px solid #fed7aa;
            color: #ea580c;
            border-radius: 12px;
            font-size: 1.25rem;
            margin-right: 14px;
        }

        #quienes-somos-page .value-card .icon-title {
            width: 38px;
            height: 38px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #f26522;
            border-radius: 10px;
            font-size: 1.05rem;
            margin-right: 12px;
        }

        #quienes-somos-page .value-card:hover .icon-title {
            background-color: #fff7ed;
            border-color: #fed7aa;
            color: #ea580c;
        }

        /* ── Lista de Valores ── */
        #quienes-somos-page .valores-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        #quienes-somos-page .about-intro .valores-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 22px;
        }

        #quienes-somos-page .about-intro .valores-list li {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #f26522;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #1e293b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #quienes-somos-page .about-intro .valores-list li::before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #f26522;
            font-size: 0.85rem;
        }

        #quienes-somos-page .value-card .valores-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        #quienes-somos-page .value-card .valores-list li {
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            color: #475569;
            font-weight: 600;
        }

        #quienes-somos-page .value-card .valores-list li::before {
            display: none !important;
        }

        @keyframes swapFadeIn {
            0% {
                opacity: 0.4;
                transform: translateY(-6px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #quienes-somos-page .swap-animated {
            animation: swapFadeIn 0.3s ease-out;
        }

        @media (max-width: 992px) {
            #quienes-somos-page .values-grid {
                grid-template-columns: 1fr;
            }
            #quienes-somos-page .about-intro {
                padding: 30px 24px;
            }
        }

        @media (max-width: 768px) {
            #quienes-somos-page .hero-banner { padding: 50px 20px; }
            #quienes-somos-page .hero-content h1 { font-size: 2.2rem; }
            #quienes-somos-page .hero-content p { font-size: 1.1rem; }
            #quienes-somos-page .about-intro { padding: 24px 18px; }
            #quienes-somos-page .about-intro .about-text h2 { font-size: 1.6rem; }
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
                                <ul class="valores-list">
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
