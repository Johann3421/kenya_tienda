@extends('layouts.landing')

@section('title', 'Reciclaje Responsable y Sostenibilidad Ambiental | KENYA Technology')
@section('meta_description', 'Programa de reciclaje tecnológico y gestión responsable de RAEE de KENYA Technology en Perú. Economía circular, valorización de componentes y cumplimiento D.S. N° 009-2019-MINAM.')
@section('meta_keywords', 'reciclaje computadoras peru, sostenibilidad tecnologia, sistema raee kenya technology, reciclaje electronico lima huanuco, economia circular computo')
@section('canonical', route('reciclaje'))
@section('og_title', 'Reciclaje Tecnológico Sostenible | KENYA Technology Perú')
@section('og_description', 'Conoce nuestro modelo de recuperación y reciclaje responsable de equipos tecnológicos en el Perú.')

@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <style>
        :root {
            --recicla-green: #15803d;
            --recicla-green-dark: #166534;
            --recicla-green-soft: #f0fdf4;
            --recicla-orange: #f26522;
            --recicla-orange-dark: #d95315;
            --recicla-navy: #1b2633;
            --recicla-ink: #0f172a;
            --recicla-text: #334155;
            --recicla-muted: #64748b;
            --recicla-line: #e2e8f0;
            --recicla-bg-card: #f8fafc;
        }

        #reciclaje-page {
            background-color: #ffffff;
            color: var(--recicla-text);
            line-height: 1.6;
            font-family: inherit;
        }

        /* ── Hero Banner ── */
        #reciclaje-page .hero-banner {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            background-image: linear-gradient(rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.65)), url('{{ asset("banersomos.png?v=2") }}');
            background-size: cover;
            background-position: right;
            color: var(--recicla-ink);
            text-align: left;
            padding: 70px 15px;
            margin-bottom: 0;
            border-bottom: 1px solid var(--recicla-line);
        }

        #reciclaje-page .hero-content {
            position: relative;
            z-index: 2; 
            max-width: 1240px;
            margin: 0 auto;
            width: 100%;
            padding: 0 10px;
        }

        #reciclaje-page .hero-eco-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--recicla-green);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 14px;
        }

        #reciclaje-page .hero-content h1 {
            font-size: 2.8rem;
            margin: 0 0 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--recicla-ink);
            line-height: 1.15;
        }

        #reciclaje-page .hero-content p {
            font-size: 1.15rem;
            font-weight: 500;
            margin: 0;
            color: var(--recicla-muted);
        }

        /* ── Contenedor Editorial ── */
        .editorial-wrapper {
            max-width: 1240px;
            margin: 0 auto;
            padding: 34px 20px 80px;
        }

        /* Migas de pan */
        .recicla-crumbs {
            font-size: 0.88rem;
            color: var(--recicla-muted);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .recicla-crumbs a {
            color: var(--recicla-muted);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .recicla-crumbs a:hover {
            color: var(--recicla-green);
        }

        .recicla-crumbs .sep {
            color: #cbd5e1;
        }

        .recicla-crumbs .current {
            color: var(--recicla-ink);
            font-weight: 600;
        }

        /* Grid a 2 columnas */
        .editorial-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
            gap: 56px;
            align-items: start;
        }

        /* Columna Izquierda: Narrativa */
        .editorial-main .eyebrow-group {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .editorial-main .tag-empresa {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--recicla-navy);
            background: #e2e8f0;
            padding: 4px 12px;
            border-radius: 4px;
        }

        .editorial-main .tag-seccion {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #ffffff;
            background: var(--recicla-green);
            padding: 4px 12px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .editorial-main h2.main-title {
            font-size: 2.1rem;
            line-height: 1.25;
            color: var(--recicla-ink);
            margin: 0 0 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .editorial-main .lead-text {
            color: var(--recicla-text);
            font-size: 1.05rem;
            line-height: 1.8;
            margin: 0 0 22px;
        }

        /* Cita destacada (Pregunta de la maqueta) */
        .recicla-question-box {
            margin: 24px 0;
            padding: 22px 24px;
            border-left: 4px solid var(--recicla-green);
            background: var(--recicla-green-soft);
            border-radius: 0 12px 12px 0;
        }

        .recicla-question-box p {
            margin: 0;
            font-size: 1.12rem;
            font-weight: 800;
            color: var(--recicla-green-dark);
            line-height: 1.45;
        }

        .recicla-question-box small {
            display: block;
            margin-top: 8px;
            font-size: 0.95rem;
            color: var(--recicla-text);
            font-weight: 500;
            line-height: 1.6;
        }

        .editorial-main .body-text {
            font-size: 1.02rem;
            line-height: 1.8;
            color: var(--recicla-text);
            margin: 0 0 32px;
        }

        /* Pilares de acción */
        .recicla-pilares {
            list-style: none;
            padding: 0;
            margin: 0 0 36px;
            border-top: 1px solid var(--recicla-line);
        }

        .recicla-pilares li {
            display: flex;
            gap: 18px;
            padding: 18px 0;
            border-bottom: 1px solid var(--recicla-line);
            align-items: flex-start;
        }

        .recicla-pilares .p-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--recicla-green-soft);
            color: var(--recicla-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .recicla-pilares .p-content strong {
            display: block;
            font-size: 1.02rem;
            color: var(--recicla-ink);
            margin-bottom: 4px;
            font-weight: 700;
        }

        .recicla-pilares .p-content p {
            margin: 0;
            font-size: 0.92rem;
            color: var(--recicla-muted);
            line-height: 1.65;
        }

        .action-buttons-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-recicla {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 22px;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-recicla-primary {
            background-color: var(--recicla-green);
            color: #ffffff;
            border: 1px solid var(--recicla-green);
        }

        .btn-recicla-primary:hover {
            background-color: var(--recicla-green-dark);
            border-color: var(--recicla-green-dark);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-recicla-orange {
            background-color: var(--recicla-orange);
            color: #ffffff;
            border: 1px solid var(--recicla-orange);
        }

        .btn-recicla-orange:hover {
            background-color: var(--recicla-orange-dark);
            border-color: var(--recicla-orange-dark);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-recicla-outline {
            background-color: transparent;
            color: var(--recicla-navy);
            border: 1px solid var(--recicla-line);
        }

        .btn-recicla-outline:hover {
            background-color: var(--recicla-bg-card);
            border-color: var(--recicla-navy);
            color: var(--recicla-ink);
        }

        /* Columna Derecha: Sidebar Fija con Imagen Limpia */
        .editorial-sidebar {
            position: sticky;
            top: 30px;
        }

        .bosque-figure-card {
            margin: 0;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--recicla-line);
            box-shadow: 0 18px 40px -18px rgba(15, 23, 42, 0.18);
        }

        .bosque-figure-card img {
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover;
            background-color: #0f172a;
        }

        .bosque-figure-caption {
            background: var(--recicla-navy);
            color: #e2e8f0;
            font-size: 0.84rem;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .bosque-figure-caption i {
            color: #4ade80;
            font-size: 1rem;
        }

        /* Tarjeta de Solicitud RAEE */
        .recicla-solicitud-card {
            margin-top: 22px;
            padding: 24px;
            border: 1px solid var(--recicla-line);
            border-left: 4px solid var(--recicla-green);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 8px 24px -12px rgba(15, 23, 42, 0.08);
        }

        .recicla-solicitud-card h3 {
            margin: 0 0 8px;
            font-size: 1.15rem;
            color: var(--recicla-ink);
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .recicla-solicitud-card h3 i {
            color: var(--recicla-green);
        }

        .recicla-solicitud-card p {
            margin: 0 0 16px;
            font-size: 0.92rem;
            color: var(--recicla-muted);
            line-height: 1.6;
        }

        .sedes-info-block {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--recicla-line);
            font-size: 0.85rem;
            color: var(--recicla-muted);
        }

        .sedes-info-block strong {
            color: var(--recicla-ink);
            display: block;
            margin-bottom: 6px;
        }

        .sedes-info-block ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sedes-info-block li {
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sedes-info-block li i {
            color: var(--recicla-orange);
            font-size: 0.8rem;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .editorial-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .editorial-sidebar {
                position: static;
                order: -1;
            }

            #reciclaje-page .hero-content h1 {
                font-size: 2.2rem;
            }

            .editorial-main h2.main-title {
                font-size: 1.7rem;
            }
        }
    </style>

    <div id="reciclaje-page">
        <!-- Hero Banner Oficial -->
        <section class="hero-banner">
            <div class="hero-content">
                <span class="hero-eco-tag">
                    <i class="fa-solid fa-seedling"></i> Gestión Ambiental RAEE · D.S. N° 009-2019-MINAM
                </span>
                <h1>Reciclaje Tecnológico</h1>
                <p>Compromiso con la economía circular y la preservación de nuestros recursos naturales</p>
            </div>
        </section>

        <!-- Bloque Editorial Principal -->
        <main class="editorial-wrapper">
            <!-- Migas de pan -->
            <nav class="recicla-crumbs" aria-label="Navegación">
                <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i> Inicio</a>
                <span class="sep">/</span>
                <span>Nuestra Empresa</span>
                <span class="sep">/</span>
                <span class="current">Reciclaje</span>
            </nav>

            <div class="editorial-grid">
                <!-- Columna Izquierda: Narrativa Fiel a la Maqueta -->
                <section class="editorial-main">
                    <div class="eyebrow-group">
                        <span class="tag-empresa">Nuestra Empresa</span>
                        <span class="tag-seccion"><i class="fa-solid fa-leaf"></i> Reciclaje</span>
                    </div>

                    <h2 class="main-title">Reciclaje responsable de la tecnología, construyamos un futuro sostenible</h2>

                    <p class="lead-text">
                        En el Perú, la tecnología forma parte cada vez más de nuestras actividades diarias. Computadoras, laptops, celulares, impresoras, servidores y otros equipos son indispensables para empresas, instituciones y hogares.
                    </p>

                    <div class="recicla-question-box">
                        <p>¿Qué hacemos con ellos cuando dejan de utilizarse?</p>
                        <small>Nuestra empresa busca convertir este desafío en una oportunidad de valor ambiental y social.</small>
                    </div>

                    <p class="body-text">
                        A través de un modelo de <strong>recuperación y reciclaje responsable de equipos tecnológicos</strong>, ayudamos a empresas e instituciones a gestionar adecuadamente los equipos que ya no necesitan, evitando que terminen junto con los residuos comunes y promoviendo el aprovechamiento de los materiales y componentes que todavía pueden tener valor.
                    </p>

                    <ul class="recicla-pilares">
                        <li>
                            <div class="p-icon"><i class="fa-solid fa-arrows-spin"></i></div>
                            <div class="p-content">
                                <strong>Economía Circular y Valorización de Componentes</strong>
                                <p>Reclasificamos partes metálicas, plásticos y tarjetas electrónicas para su reincorporación en cadenas productivas seguras.</p>
                            </div>
                        </li>
                        <li>
                            <div class="p-icon"><i class="fa-solid fa-shield-halved"></i></div>
                            <div class="p-content">
                                <strong>Manejo Certificado de RAEE (D.S. N° 009-2019-MINAM)</strong>
                                <p>Garantizamos trazabilidad ambiental total, evitando la proliferación de residuos en vertederos no autorizados.</p>
                            </div>
                        </li>
                        <li>
                            <div class="p-icon"><i class="fa-solid fa-tree"></i></div>
                            <div class="p-content">
                                <strong>Preservación de Ecosistemas Naturales</strong>
                                <p>Reducimos la extracción de materias primas vírgenes y protegemos fuentes hídricas y suelos de metales pesados.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="action-buttons-group">
                        <a class="btn-recicla btn-recicla-primary" href="mailto:acuerdos.marco@kenya.com.pe">
                            <i class="fa-solid fa-hand-holding-hand"></i> Coordinar Entrega de Equipos
                        </a>
                        <a class="btn-recicla btn-recicla-outline" href="tel:+51958021778">
                            <i class="fa-solid fa-phone"></i> Central 958 021 778
                        </a>
                    </div>
                </section>

                <!-- Columna Derecha: Tarjeta Fija con Imagen Limpia y Canales -->
                <aside class="editorial-sidebar">
                    <figure class="bosque-figure-card">
                        <img src="{{ asset('bosque-reciclaje.jpg') }}" alt="Reciclaje tecnológico y futuro sostenible con KENYA Technology" loading="lazy">
                        <figcaption class="bosque-figure-caption">
                            <i class="fa-solid fa-seedling"></i>
                            <span>Compromiso de conservación ambiental · KENYA TECHNOLOGY</span>
                        </figcaption>
                    </figure>

                    <div class="recicla-solicitud-card">
                        <h3><i class="fa-solid fa-clipboard-check"></i> ¿Tu entidad o empresa renueva equipos?</h3>
                        <p>
                            Emitimos la <strong>Constancia Oficial de Disposición de RAEE</strong> para facilitar la baja de bienes estatales y respaldar tus auditorías ambientales.
                        </p>
                        <div class="action-buttons-group">
                            <a class="btn-recicla btn-recicla-orange" style="width: 100%;" href="mailto:soporte@kenya.com.pe">
                                <i class="fa-solid fa-envelope"></i> Solicitar Certificado RAEE
                            </a>
                        </div>

                        <div class="sedes-info-block">
                            <strong><i class="fa-solid fa-location-dot"></i> Puntos de Acopio Oficiales:</strong>
                            <ul>
                                <li><i class="fa-solid fa-check"></i> <strong>Huánuco:</strong> Jr. Crespo y Castillo 480</li>
                                <li><i class="fa-solid fa-check"></i> <strong>Lima:</strong> San Isidro (atención a entidades públicas y privadas)</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
@endsection
