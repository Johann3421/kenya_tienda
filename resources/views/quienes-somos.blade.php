@extends('layouts.landing') {{-- Asegúrate de tener tu header/footer aquí --}}

@section('title', 'Quiénes Somos')
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

        #quienes-somos-page .about-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        #quienes-somos-page .about-intro {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            margin-bottom: 70px;
        }

        #quienes-somos-page .about-text {
            flex: 1;
        }

        #quienes-somos-page .about-text h2 {
            font-size: 2.2rem;
            color: #333;
            margin-bottom: 25px;
            font-weight: 700;
        }

        #quienes-somos-page .about-description {
            position: relative;
            padding-left: 25px;
            margin-bottom: 25px;
        }

        #quienes-somos-page .about-description::before {
            content: '';
            position: absolute;
            left: 0;
            top: 5px;
            bottom: 5px;
            width: 4px;
            background-color: #f26522;
        }

        #quienes-somos-page .about-description p {
            color: #555;
            font-size: 1.05rem;
            line-height: 1.7;
        }

        #quienes-somos-page .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        #quienes-somos-page .value-card {
            background-color: #f8f8f8; 
            padding: 40px 30px;
            text-align: left;
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        #quienes-somos-page .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        #quienes-somos-page .value-card h3 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700; 
            display: flex;
            align-items: center;
        }

        #quienes-somos-page .value-card p {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        #quienes-somos-page .icon-title {
            color: #f26522; 
            margin-right: 12px;
            font-size: 1.5rem;
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
            #quienes-somos-page .about-text h2 { font-size: 1.8rem; }
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
                <!-- Parte superior: Texto e Imagen -->
                <div class="about-intro">
                    <div class="about-text">
                        <h2><i class="fa-solid fa-clock-rotate-left icon-title"></i> Nuestra Historia</h2>
                        <div class="about-description">
                            <p>Desde nuestros inicios, en <strong>KENYA TECHNOLOGY</strong> apostamos por crear computadoras de alto desempeño adaptadas a las necesidades de un mercado en constante evolución. Con una trayectoria basada en innovación, calidad y compromiso, hemos acompañado a miles de usuarios ofreciendo equipos ensamblados con tecnología moderna, excelente rendimiento y altos estándares de calidad. Hoy continuamos creciendo como una marca peruana enfocada en desarrollar computadoras confiables, eficientes y preparadas para el futuro.</p>
                            <p>Nos especializamos en la fabricación y comercialización de equipos de cómputo con componentes de calidad, diseño moderno y tecnología de última generación, ofreciendo una experiencia superior en cada equipo.</p>
                        </div>
                    </div>
                </div>

                <!-- Parte inferior: Grid de Valores, Visión y Misión -->
                <div class="values-grid">
                    <div class="value-card">
                        <h3><i class="fa-solid fa-hand-holding-heart icon-title"></i> Nuestros Valores</h3>
                        <p>Nuestros Principios Culturales nos ayudan a conectarnos con la cultura de la empresa, siendo fundamentales para que podamos seguir creciendo dentro de nuestra gran familia Kenya Computers. Nos basamos en la transparencia, el esfuerzo y el trabajo en equipo.</p>
                    </div>
                    <div class="value-card">
                        <h3><i class="fa-solid fa-eye icon-title"></i> Nuestra Visión</h3>
                        <p>"Ser la marca peruana de computadoras más reconocida y confiable a nivel nacional e internacional, destacando por nuestra innovación, calidad, rendimiento y compromiso con el medio ambiente."</p>
                    </div>
                    <div class="value-card">
                        <h3><i class="fa-solid fa-bullseye icon-title"></i> Nuestra Misión</h3>
                        <p>Desarrollar computadoras de alto rendimiento que brinden potencia, eficiencia y confiabilidad, ofreciendo a nuestros clientes la mejor experiencia tecnológica en cada equipo KENYA TECHNOLOGY.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
