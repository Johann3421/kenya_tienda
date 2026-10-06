@extends('layouts.landing')

@section('title', 'Sostenibilidad y Reciclaje de Equipos (RAEE) | KENYA Technology')
@section('meta_description', 'Programa de reciclaje y gestión ambiental de Residuos de Aparatos Eléctricos y Electrónicos (RAEE) de KENYA Technology en Perú. Cumplimiento D.S. N° 009-2019-MINAM.')
@section('meta_keywords', 'reciclaje computadoras peru, sistema raee kenya technology, manejo raee colectivo, reciclaje electronico lima huanuco, sostenibilidad computo')
@section('canonical', route('reciclaje'))
@section('og_title', 'Reciclaje y Sostenibilidad Ambiental | KENYA Technology')
@section('og_description', 'Compromiso ambiental, economía circular y gestión responsable de residuos electrónicos de KENYA Technology en Perú.')

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
        #reciclaje-page {
            background-color: #ffffff;
            color: #333;
            line-height: 1.6;
        }

        #reciclaje-page .hero-banner {
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

        #reciclaje-page .hero-content {
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

        #reciclaje-page .hero-content h1 {
            font-size: 3rem;
            margin-bottom: -3px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
        }

        #reciclaje-page .hero-content p {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 20px; 
        }

        #reciclaje-page .reciclaje-section {
            padding: 70px 0;
            background-color: #ffffff;
        }

        #reciclaje-page .reciclaje-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ── Bloque Principal Superior ── */
        #reciclaje-page .reciclaje-intro {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            border-top: 4px solid #f26522;
            padding: 38px 40px 34px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            margin-bottom: 35px;
        }

        #reciclaje-page .reciclaje-intro h2 {
            display: flex;
            align-items: center;
            font-size: 1.95rem;
            color: #0f172a;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        #reciclaje-page .reciclaje-intro p {
            color: #334155;
            font-size: 1.03rem;
            line-height: 1.8;
            margin-bottom: 14px;
        }

        #reciclaje-page .reciclaje-intro p:last-child {
            margin-bottom: 0;
        }

        /* ── Grid de Pilares Ambientales ── */
        #reciclaje-page .reciclaje-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 35px;
        }

        #reciclaje-page .reciclaje-card {
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

        #reciclaje-page .reciclaje-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.08);
            border-color: #f26522;
        }

        #reciclaje-page .reciclaje-card h2 {
            font-size: 1.25rem;
            color: #0f172a;
            margin-bottom: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        #reciclaje-page .reciclaje-card p {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.65;
            margin: 0;
        }

        /* ── Procedimiento y Puntos de Acopio ── */
        #reciclaje-page .acopio-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 34px 36px;
        }

        #reciclaje-page .acopio-box h2 {
            font-size: 1.4rem;
            color: #0f172a;
            font-weight: 700;
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        #reciclaje-page .acopio-box > p {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 22px;
        }

        #reciclaje-page .acopio-steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }

        #reciclaje-page .acopio-step-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 22px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        #reciclaje-page .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #fff7ed;
            color: #ea580c;
            font-weight: 700;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        #reciclaje-page .acopio-step-item h3 {
            font-size: 1.05rem;
            color: #0f172a;
            font-weight: 700;
            margin: 0;
        }

        #reciclaje-page .acopio-step-item p {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        #reciclaje-page .acopio-locations {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 10px;
        }

        #reciclaje-page .location-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #1e293b;
        }

        #reciclaje-page .location-tag i {
            color: #f26522;
            font-size: 1.05rem;
        }

        /* ── Íconos en Títulos ── */
        #reciclaje-page .icon-title {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.25s ease;
        }

        #reciclaje-page .reciclaje-intro .icon-title {
            width: 46px;
            height: 46px;
            background-color: #fff7ed;
            border: 1px solid #fed7aa;
            color: #ea580c;
            border-radius: 12px;
            font-size: 1.25rem;
            margin-right: 14px;
        }

        #reciclaje-page .reciclaje-card .icon-title,
        #reciclaje-page .acopio-box .icon-title {
            width: 38px;
            height: 38px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #f26522;
            border-radius: 10px;
            font-size: 1.05rem;
            margin-right: 12px;
        }

        #reciclaje-page .reciclaje-card:hover .icon-title {
            background-color: #fff7ed;
            border-color: #fed7aa;
            color: #ea580c;
        }

        @media (max-width: 992px) {
            #reciclaje-page .reciclaje-grid,
            #reciclaje-page .acopio-steps {
                grid-template-columns: 1fr;
            }
            #reciclaje-page .reciclaje-intro {
                padding: 30px 24px;
            }
            #reciclaje-page .acopio-box {
                padding: 24px 20px;
            }
        }

        @media (max-width: 768px) {
            #reciclaje-page .hero-banner { padding: 50px 20px; }
            #reciclaje-page .hero-content h1 { font-size: 2.2rem; }
            #reciclaje-page .hero-content p { font-size: 1.1rem; }
            #reciclaje-page .reciclaje-intro { padding: 24px 18px; }
            #reciclaje-page .reciclaje-intro h2 { font-size: 1.6rem; }
            #reciclaje-page .acopio-locations { flex-direction: column; }
        }
    </style>

    <div id="reciclaje-page">
        <!-- ==========================================
             BANNER "RECICLAJE Y SOSTENIBILIDAD"
             ========================================== -->
        <section class="hero-banner">
            <div class="hero-content">
                <h1>Reciclaje y Sostenibilidad</h1>
                <p>Gestión responsable de residuos electrónicos para proteger el medio ambiente del Perú.</p>
            </div>
        </section>
        
        <!-- ==========================================
             SECCIÓN CONTENIDO Y SISTEMA RAEE
             ========================================== -->
        <section class="reciclaje-section">
            <div class="reciclaje-container">
                <!-- Bloque Principal Superior -->
                <div class="reciclaje-intro">
                    <h2><i class="fa-solid fa-recycle icon-title"></i> Sistema de Manejo y Gestión Ambiental de RAEE</h2>
                    <p>En <strong>KENYA TECHNOLOGY</strong> (IMPORTACIONES KENYA), como fabricante y distribuidor peruano de computadoras de escritorio, laptops y soluciones informáticas, asumimos la responsabilidad ambiental durante todo el ciclo de vida de nuestros equipos.</p>
                    <p>En estricto cumplimiento del <strong>Decreto Supremo N° 009-2019-MINAM</strong> (Régimen Especial de Gestión y Manejo de Residuos de Aparatos Eléctricos y Electrónicos - RAEE), participamos activamente en un <strong>Sistema Colectivo de Manejo de RAEE</strong> certificado, asegurando que los componentes en desuso sean recolectados, tratados y valorizados con procesos técnicos y ambientales seguros.</p>
                </div>

                <!-- Grid de Pilares Ambientales -->
                <div class="reciclaje-grid">
                    <div class="reciclaje-card">
                        <h2><i class="fa-solid fa-leaf icon-title"></i> Componentes Seguros y Cumplimiento RoHS</h2>
                        <p>Diseñamos y ensamblamos nuestros equipos seleccionando componentes que cumplen rigurosamente las directivas ambientales internacionales (RoHS, CE, FCC), restringiendo el uso de sustancias peligrosas como plomo, mercurio y cadmio para facilitar su posterior reciclaje seguro.</p>
                    </div>

                    <div class="reciclaje-card">
                        <h2><i class="fa-solid fa-arrows-spin icon-title"></i> Economía Circular y Revalorización</h2>
                        <p>Promovemos la separación técnica de materiales reaprovechables presentes en chasis, placas electrónicas, cables y fuentes de poder. Facilitamos la recuperación de metales y plásticos técnicos para reintegrarlos a cadenas productivas y reducir la extracción de recursos vírgenes.</p>
                    </div>

                    <div class="reciclaje-card">
                        <h2><i class="fa-solid fa-shield-halved icon-title"></i> Disposición Final Controlada</h2>
                        <p>Aquellas fracciones no reciclables o componentes de manejo especial (baterías, capacitores) son canalizados a operadores autorizados por el MINAM y OEFA para su disposición en rellenos de seguridad ambiental, protegiendo las fuentes de agua y suelos del país.</p>
                    </div>
                </div>

                <!-- Procedimiento y Puntos de Acopio -->
                <div class="acopio-box">
                    <h2><i class="fa-solid fa-boxes-stacked icon-title"></i> ¿Cómo participar en nuestro programa de reciclaje RAEE?</h2>
                    <p>Si eres usuario particular, empresa privada o institución del Estado con computadoras o componentes KENYA en desuso, te facilitamos la entrega responsable para su adecuada gestión ambiental:</p>
                    
                    <div class="acopio-steps">
                        <div class="acopio-step-item">
                            <span class="step-number">01</span>
                            <h3>Identificación del Equipo</h3>
                            <p>Separa los equipos de cómputo, monitores, periféricos o fuentes que hayan culminado su tiempo de vida útil.</p>
                        </div>

                        <div class="acopio-step-item">
                            <span class="step-number">02</span>
                            <h3>Puntos de Entrega</h3>
                            <p>Acércate a nuestras sedes autorizadas para depositar tus equipos en nuestros contenedores seguros de RAEE.</p>
                        </div>

                        <div class="acopio-step-item">
                            <span class="step-number">03</span>
                            <h3>Tratamiento Certificado</h3>
                            <p>Los componentes son transportados y valorizados a través de nuestro Sistema Colectivo de Manejo de RAEE.</p>
                        </div>
                    </div>

                    <div class="acopio-locations">
                        <div class="location-tag">
                            <i class="fa-solid fa-location-dot"></i>
                            <span><strong>Huánuco:</strong> Jr. Huallayco N° 1135</span>
                        </div>
                        <div class="location-tag">
                            <i class="fa-solid fa-location-dot"></i>
                            <span><strong>Lima (San Isidro):</strong> Av. Pablo Carriquiry N° 455</span>
                        </div>
                        <div class="location-tag">
                            <i class="fa-solid fa-envelope"></i>
                            <span><strong>Coordinación RAEE B2B:</strong> <a href="mailto:soporte@kenya.com.pe" style="color:#1e293b; text-decoration:none;">soporte@kenya.com.pe</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
