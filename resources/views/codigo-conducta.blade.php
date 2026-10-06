@extends('layouts.landing')

@section('title', 'Código de Conducta | Integridad y Ética Empresarial | KENYA Technology')
@section('meta_description', 'Conoce el Código de Conducta y Ética Empresarial de KENYA Technology (IMPORTACIONES KENYA). Principios de integridad, transparencia, anticorrupción y cumplimiento normativo en Perú.')
@section('meta_keywords', 'codigo conducta kenya, etica empresarial kenya technology, integridad convenio marco, politicas corporativas peru')
@section('canonical', route('codigo.conducta'))
@section('og_title', 'Código de Conducta | KENYA Technology Perú')
@section('og_description', 'Compromiso ético, transparencia y buenas prácticas comerciales de KENYA Technology en el sector público y corporativo.')

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
        #codigo-conducta-page {
            background-color: #ffffff;
            color: #333;
            line-height: 1.6;
        }

        #codigo-conducta-page .hero-banner {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            background-image: linear-gradient(rgba(255, 255, 255, 0.55), rgba(255, 255, 255, 0.55)), url('{{ asset("banersomos.png?v=2") }}');
            background-size: cover;
            background-position: right;
            color: #000000;
            text-align: left;
            padding: 80px 5px;
            margin-bottom: 0;
        }

        #codigo-conducta-page .hero-content {
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

        #codigo-conducta-page .hero-content h1 {
            font-size: 3rem;
            margin-bottom: -3px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
        }

        #codigo-conducta-page .hero-content p {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 20px; 
        }

        #codigo-conducta-page .conducta-section {
            padding: 70px 0;
            background-color: #ffffff;
        }

        #codigo-conducta-page .conducta-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ── Bloque Principal Superior ── */
        #codigo-conducta-page .policy-intro {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            border-top: 4px solid #f26522;
            padding: 38px 40px 34px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            margin-bottom: 35px;
        }

        #codigo-conducta-page .policy-intro h2 {
            display: flex;
            align-items: center;
            font-size: 1.95rem;
            color: #0f172a;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        #codigo-conducta-page .policy-intro p {
            color: #334155;
            font-size: 1.03rem;
            line-height: 1.8;
            margin-bottom: 14px;
        }

        #codigo-conducta-page .policy-intro p:last-child {
            margin-bottom: 0;
        }

        /* ── Grid de Pilares ── */
        #codigo-conducta-page .policy-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 35px;
        }

        #codigo-conducta-page .policy-card {
            background-color: #ffffff;
            padding: 28px 26px 24px;
            text-align: left;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100%;
        }

        #codigo-conducta-page .policy-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.08);
            border-color: #f26522;
        }

        #codigo-conducta-page .policy-card h2 {
            font-size: 1.25rem;
            color: #0f172a;
            margin-bottom: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        #codigo-conducta-page .policy-card p {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.65;
            margin: 0;
        }

        /* ── Bloque Inferior de Canal Ético ── */
        #codigo-conducta-page .policy-contact-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 30px 32px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        #codigo-conducta-page .policy-contact-box h2 {
            font-size: 1.35rem;
            color: #0f172a;
            font-weight: 700;
            display: flex;
            align-items: center;
            margin: 0;
        }

        #codigo-conducta-page .policy-contact-box p {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.7;
            margin: 0;
        }

        #codigo-conducta-page .policy-contact-channels {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 8px;
        }

        #codigo-conducta-page .policy-contact-item {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: 0.9rem;
            color: #1e293b;
            font-weight: 600;
        }

        #codigo-conducta-page .policy-contact-item i {
            color: #f26522;
            font-size: 1.1rem;
        }

        #codigo-conducta-page .policy-contact-item a {
            color: #1e293b;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        #codigo-conducta-page .policy-contact-item a:hover {
            color: #f26522;
        }

        /* ── Íconos en Títulos ── */
        #codigo-conducta-page .icon-title {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.25s ease;
        }

        #codigo-conducta-page .policy-intro .icon-title {
            width: 46px;
            height: 46px;
            background-color: #fff7ed;
            border: 1px solid #fed7aa;
            color: #ea580c;
            border-radius: 12px;
            font-size: 1.25rem;
            margin-right: 14px;
        }

        #codigo-conducta-page .policy-card .icon-title,
        #codigo-conducta-page .policy-contact-box .icon-title {
            width: 38px;
            height: 38px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #f26522;
            border-radius: 10px;
            font-size: 1.05rem;
            margin-right: 12px;
        }

        #codigo-conducta-page .policy-card:hover .icon-title {
            background-color: #fff7ed;
            border-color: #fed7aa;
            color: #ea580c;
        }

        @media (max-width: 992px) {
            #codigo-conducta-page .policy-grid {
                grid-template-columns: 1fr;
            }
            #codigo-conducta-page .policy-intro {
                padding: 30px 24px;
            }
        }

        @media (max-width: 768px) {
            #codigo-conducta-page .hero-banner { padding: 50px 20px; }
            #codigo-conducta-page .hero-content h1 { font-size: 2.2rem; }
            #codigo-conducta-page .hero-content p { font-size: 1.1rem; }
            #codigo-conducta-page .policy-intro { padding: 24px 18px; }
            #codigo-conducta-page .policy-intro h2 { font-size: 1.6rem; }
            #codigo-conducta-page .policy-contact-channels { flex-direction: column; }
        }
    </style>

    <div id="codigo-conducta-page">
        <!-- ==========================================
             BANNER "CÓDIGO DE CONDUCTA"
             ========================================== -->
        <section class="hero-banner">
            <div class="hero-content">
                <h1>Código de Conducta</h1>
                <p>Integridad, transparencia y responsabilidad ética en cada decisión.</p>
            </div>
        </section>
        
        <!-- ==========================================
             SECCIÓN CONTENIDO Y PILARES
             ========================================== -->
        <section class="conducta-section">
            <div class="conducta-container">
                <!-- Bloque Principal Superior -->
                <div class="policy-intro">
                    <h2><i class="fa-solid fa-shield-halved icon-title"></i> Nuestro Compromiso Ético y Cumplimiento</h2>
                    <p>En <strong>KENYA TECHNOLOGY</strong> (IMPORTACIONES KENYA), entendemos que el desarrollo de tecnología de alto desempeño para el mercado peruano debe estar sustentado en valores sólidos, una conducta ética intachable y la máxima transparencia en todas nuestras operaciones.</p>
                    <p>Este Código de Conducta define los principios rectores que orientan las decisiones y acciones de nuestros colaboradores, directivos y aliados estratégicos. Aplica a todas nuestras actividades comerciales, procesos de contratación pública (Convenio Marco, OSCE) y relaciones corporativas en todo el territorio nacional.</p>
                </div>

                <!-- Grid de Pilares de Conducta -->
                <div class="policy-grid">
                    <div class="policy-card">
                        <h2><i class="fa-solid fa-scale-balanced icon-title"></i> Integridad y Cero Tolerancia a la Corrupción</h2>
                        <p>Prohibición estricta de cualquier forma de soborno, pago indebido, dádiva o práctica anticompetitiva en procesos de licitación pública y contrataciones corporativas. Actuamos con rectitud, veracidad y estricta rendición de cuentas en cada transacción comercial.</p>
                    </div>

                    <div class="policy-card">
                        <h2><i class="fa-solid fa-users icon-title"></i> Respeto a las Personas y Derechos Laborales</h2>
                        <p>Fomentamos un ambiente de trabajo seguro, digno e inclusivo, libre de discriminación y hostigamiento. Promovemos la igualdad de oportunidades, condiciones laborales equitativas y el crecimiento profesional y personal de nuestro equipo humano.</p>
                    </div>

                    <div class="policy-card">
                        <h2><i class="fa-solid fa-lock icon-title"></i> Confidencialidad y Protección de Datos</h2>
                        <p>Resguardamos con el más alto rigor la confidencialidad de la información y la propiedad intelectual de nuestros clientes, proveedores y socios comerciales, en estricto cumplimiento de la Ley N° 29733 (Ley de Protección de Datos Personales del Perú).</p>
                    </div>

                    <div class="policy-card">
                        <h2><i class="fa-solid fa-handshake-simple icon-title"></i> Calidad, Garantía y Veracidad Comercial</h2>
                        <p>Respaldamos cada uno de nuestros equipos con especificaciones técnicas verídicas, componentes certificados y garantía On-Site de 36 meses a nivel nacional. Mantenemos una comunicación honesta y un servicio posventa confiable para el usuario final.</p>
                    </div>
                </div>

                <!-- Bloque de Canal Ético y Consultas -->
                <div class="policy-contact-box">
                    <h2><i class="fa-solid fa-envelope-open-text icon-title"></i> Canal de Integridad y Consultas</h2>
                    <p>Ponemos a disposición de nuestros clientes, colaboradores y público en general canales directos y confidenciales para realizar consultas éticas o reportar cualquier conducta contraria a nuestros principios y a la legislación vigente. Garantizamos confidencialidad absoluta y política de no represalias.</p>
                    <div class="policy-contact-channels">
                        <div class="policy-contact-item">
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:acuerdos.marco@kenya.com.pe">acuerdos.marco@kenya.com.pe</a>
                        </div>
                        <div class="policy-contact-item">
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:soporte@kenya.com.pe">soporte@kenya.com.pe</a>
                        </div>
                        <div class="policy-contact-item">
                            <i class="fa-solid fa-phone"></i>
                            <span>Central: +51 958 021 778</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
