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

        #quienes-somos-page .about-intro {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            border-top: 4px solid #f26522;
            padding: 38px 40px 34px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            margin-bottom: 35px;
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

        /* ── Estilos de Tarjetas en el Grid ── */
        #quienes-somos-page .values-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            align-items: stretch;
        }

        #quienes-somos-page .value-card {
            background-color: #ffffff;
            padding: 28px 26px 24px;
            text-align: left;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            scroll-margin-top: 110px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
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

        #quienes-somos-page .value-card .about-description p {
            color: #475569;
            font-size: 0.93rem;
            line-height: 1.65;
            margin-bottom: 0;
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
            margin: 14px 0 0 0;
            padding: 0;
            list-style: none;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        #quienes-somos-page .valores-list li {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #f26522;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #1e293b;
            font-weight: 600;
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
             SECCIÓN INFORMACIÓN Y VALORES
             ========================================== -->
        <section class="about-section">
            <div class="about-container">
                <!-- Parte superior: Nuestra Historia -->
                <div class="about-intro" id="historia">
                    <div class="about-text">
                        <h2><i class="fa-solid fa-clock-rotate-left icon-title"></i> Nuestra Historia</h2>
                        <div class="about-description">
                            <p>Desde nuestros inicios, en <strong>KENYA TECHNOLOGY</strong> apostamos por crear computadoras de alto desempeño adaptadas a las necesidades Gubernamentales del mercado nacional en crecimiento.</p>
                            <p>Con una trayectoria basada en innovación, calidad y compromiso, hemos acompañado a nuestros usuarios ofreciendo equipos Informáticos con la más avanzada tecnología, excelente rendimiento y altos estándares de calidad.</p>
                            <p>Hoy continuamos creciendo como una marca orgullosamente peruana enfocada en desarrollar computadoras confiables, eficientes y preparadas para resolver los distintos retos geográficos de Costa, Sierra y Selva de nuestro Perú.</p>
                            <p>Nos especializamos en la fabricación y comercialización de equipos de cómputo con componentes de la más alta calidad y garantía, diseño moderno y tecnología de última generación, ofreciendo una experiencia superior en cada equipo.</p>
                        </div>
                    </div>
                </div>

                <!-- Grid inferior: Misión, Visión y Valores -->
                <div class="values-grid">
                    <div class="value-card" id="mision">
                        <div class="about-text">
                            <h2><i class="fa-solid fa-bullseye icon-title"></i> Nuestra Misión</h2>
                            <div class="about-description">
                                <p>Desarrollar computadoras de alto rendimiento que brinden potencia, eficiencia y confiabilidad, ofreciendo a nuestros clientes la mejor experiencia tecnológica en cada equipo KENYA TECHNOLOGY.</p>
                            </div>
                        </div>
                    </div>
                    <div class="value-card" id="vision">
                        <div class="about-text">
                            <h2><i class="fa-solid fa-eye icon-title"></i> Nuestra Visión</h2>
                            <div class="about-description">
                                <p>Ser la marca peruana de computadoras más reconocida y confiable a nivel nacional e internacional, destacando por nuestra innovación, calidad, rendimiento y compromiso con el medio ambiente.</p>
                            </div>
                        </div>
                    </div>
                    <div class="value-card" id="valores">
                        <div class="about-text">
                            <h2><i class="fa-solid fa-hand-holding-heart icon-title"></i> Nuestros Valores</h2>
                            <div class="about-description">
                                <p>Nuestros principios como marca Kenya Technology nos ayudan a conectarnos con la cultura de las empresas privadas y gubernamentales, basándonos en la:</p>
                                <ul class="valores-list">
                                    <li>Actitud</li>
                                    <li>Ética</li>
                                    <li>Transparencia</li>
                                    <li>Responsabilidad</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
