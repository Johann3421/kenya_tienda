@extends('layouts.landing')

@section('title', 'Código de Conducta e Integridad · ISO 37001:2016 | KENYA Technology')
@section('meta_description', 'Código de Conducta y Política Antisoborno ISO 37001:2016 de KENYA Technology (IMPORTACIONES KENYA). Reglas de integridad, ética y transparencia para relaciones con clientes, proveedores, trabajadores y autoridades.')
@section('meta_keywords', 'codigo de conducta kenya, iso 37001 2016 kenya technology, sistema gestion antisoborno, etica corporativa peru, integridad convenio marco')
@section('canonical', route('codigo.conducta'))
@section('og_title', 'Código de Conducta y Ética · ISO 37001:2016 | KENYA Technology Perú')
@section('og_description', 'Conoce los 16 principios rectores y el Sistema de Gestión Antisoborno ISO 37001 de KENYA Technology.')

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
            color: #1e293b;
            line-height: 1.6;
        }

        /* ── Hero Banner ── */
        #codigo-conducta-page .hero-banner {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            background-image: linear-gradient(rgba(255, 255, 255, 0.60), rgba(255, 255, 255, 0.60)), url('{{ asset("banersomos.png?v=2") }}');
            background-size: cover;
            background-position: right;
            color: #0f172a;
            text-align: left;
            padding: 75px 10px;
            margin-bottom: 0;
            border-bottom: 1px solid #e2e8f0;
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

        #codigo-conducta-page .hero-iso-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #ea580c;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        #codigo-conducta-page .hero-content h1 {
            font-size: 2.8rem;
            margin-bottom: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
        }

        #codigo-conducta-page .hero-content p {
            font-size: 1.15rem;
            font-weight: 500;
            color: #475569;
            max-width: 900px;
            margin-bottom: 0;
        }

        /* ── Sección General ── */
        #codigo-conducta-page .conducta-section {
            padding: 60px 0 80px;
            background-color: #ffffff;
        }

        #codigo-conducta-page .conducta-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ── Panel Superior: Declaración + ISO 37001 ── */
        #codigo-conducta-page .intro-split-grid {
            display: grid;
            grid-template-columns: 1.25fr 0.95fr;
            gap: 32px;
            margin-bottom: 55px;
            align-items: stretch;
        }

        #codigo-conducta-page .intro-card {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            border-top: 4px solid #f26522;
            padding: 36px 38px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        #codigo-conducta-page .intro-card h2 {
            font-size: 1.85rem;
            color: #0f172a;
            margin-bottom: 18px;
            font-weight: 800;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
        }

        #codigo-conducta-page .intro-card p {
            color: #334155;
            font-size: 1.02rem;
            line-height: 1.8;
            margin-bottom: 14px;
        }

        #codigo-conducta-page .intro-quote {
            background: #f8fafc;
            border-left: 3px solid #f26522;
            padding: 14px 18px;
            border-radius: 0 8px 8px 0;
            margin-top: 10px;
            font-size: 0.95rem;
            color: #1e293b;
            font-weight: 600;
            line-height: 1.6;
        }

        #codigo-conducta-page .iso-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 32px 30px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        #codigo-conducta-page .iso-card-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 20px;
        }

        #codigo-conducta-page .iso-badge-icon {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #ea580c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        #codigo-conducta-page .iso-card-header h3 {
            font-size: 1.35rem;
            color: #0f172a;
            font-weight: 800;
            margin: 0 0 4px 0;
            line-height: 1.2;
        }

        #codigo-conducta-page .iso-card-header span {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #codigo-conducta-page .iso-pillars-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        #codigo-conducta-page .iso-pillar-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        #codigo-conducta-page .iso-pillar-item .pillar-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #codigo-conducta-page .iso-pillar-item .pillar-title i {
            color: #f26522;
            font-size: 0.8rem;
        }

        #codigo-conducta-page .iso-pillar-item .pillar-desc {
            font-size: 0.78rem;
            color: #64748b;
            line-height: 1.4;
            margin: 0;
        }

        /* ── Título de Sección y Controles ── */
        #codigo-conducta-page .section-header-wrap {
            margin-bottom: 25px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        #codigo-conducta-page .section-header-title {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 12px;
        }

        #codigo-conducta-page .section-header-title h2 {
            font-size: 1.9rem;
            color: #0f172a;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #codigo-conducta-page .section-header-title p {
            color: #64748b;
            font-size: 0.98rem;
            margin: 4px 0 0;
        }

        /* ── Barra de Filtros ── */
        #codigo-conducta-page .filter-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 12px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        #codigo-conducta-page .filter-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        #codigo-conducta-page .filter-btn {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        #codigo-conducta-page .filter-btn:hover {
            border-color: #f26522;
            color: #f26522;
            background: #fff7ed;
        }

        #codigo-conducta-page .filter-btn.active {
            background: #f26522;
            border-color: #f26522;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(242, 101, 34, 0.25);
        }

        #codigo-conducta-page .filter-toggle-all {
            background: transparent;
            border: none;
            color: #ea580c;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            transition: color 0.2s ease;
        }

        #codigo-conducta-page .filter-toggle-all:hover {
            color: #c2410c;
            text-decoration: underline;
        }

        /* ── Grid de los 16 Principios ── */
        #codigo-conducta-page .conducta-items-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
            margin-bottom: 55px;
        }

        #codigo-conducta-page .conducta-item-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 24px 26px;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        #codigo-conducta-page .conducta-item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px -2px rgba(15, 23, 42, 0.07);
            border-color: #cbd5e1;
        }

        #codigo-conducta-page .conducta-item-card.highlight-card {
            border-left: 3px solid #f26522;
        }

        #codigo-conducta-page .item-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        #codigo-conducta-page .item-badge-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #codigo-conducta-page .item-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 800;
            color: #f26522;
        }

        #codigo-conducta-page .item-cat-tag {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 4px;
        }

        #codigo-conducta-page .item-icon {
            color: #94a3b8;
            font-size: 1rem;
        }

        #codigo-conducta-page .conducta-item-card h3 {
            font-size: 1.15rem;
            color: #0f172a;
            font-weight: 700;
            margin: 0 0 10px 0;
            line-height: 1.35;
        }

        #codigo-conducta-page .conducta-item-card p {
            color: #475569;
            font-size: 0.93rem;
            line-height: 1.65;
            margin: 0;
        }

        #codigo-conducta-page .conducta-item-card ul {
            margin: 10px 0 0 16px;
            padding: 0;
            color: #475569;
            font-size: 0.91rem;
            line-height: 1.6;
        }

        #codigo-conducta-page .conducta-item-card ul li {
            margin-bottom: 4px;
        }

        /* ── Bloque Inferior: Canal Ético y Denuncias ── */
        #codigo-conducta-page .canal-etico-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-top: 3px solid #f26522;
            border-radius: 16px;
            padding: 36px 40px;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 36px;
            align-items: center;
        }

        #codigo-conducta-page .canal-info h2 {
            font-size: 1.6rem;
            color: #0f172a;
            font-weight: 800;
            margin: 0 0 12px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #codigo-conducta-page .canal-info p {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 14px;
        }

        #codigo-conducta-page .canal-guarantees {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
        }

        #codigo-conducta-page .guarantee-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            color: #1e293b;
            font-weight: 600;
        }

        #codigo-conducta-page .guarantee-item i {
            color: #16a34a;
            font-size: 0.9rem;
        }

        #codigo-conducta-page .canal-cards {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        #codigo-conducta-page .contact-card-link {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 14px 18px;
            border-radius: 12px;
            text-decoration: none;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        #codigo-conducta-page .contact-card-link:hover {
            border-color: #f26522;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        #codigo-conducta-page .contact-card-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #fff7ed;
            color: #ea580c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        #codigo-conducta-page .contact-card-texts {
            display: flex;
            flex-direction: column;
        }

        #codigo-conducta-page .contact-card-texts .label {
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
        }

        #codigo-conducta-page .contact-card-texts .val {
            font-size: 0.95rem;
            color: #0f172a;
            font-weight: 700;
        }

        /* ── Íconos en Títulos ── */
        #codigo-conducta-page .icon-title {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            background-color: #fff7ed;
            border: 1px solid #fed7aa;
            color: #ea580c;
            border-radius: 12px;
            font-size: 1.25rem;
            margin-right: 14px;
        }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            #codigo-conducta-page .intro-split-grid {
                grid-template-columns: 1fr;
            }
            #codigo-conducta-page .conducta-items-grid {
                grid-template-columns: 1fr;
            }
            #codigo-conducta-page .canal-etico-box {
                grid-template-columns: 1fr;
                padding: 28px 24px;
            }
            #codigo-conducta-page .intro-card {
                padding: 28px 24px;
            }
        }

        @media (max-width: 768px) {
            #codigo-conducta-page .hero-banner { padding: 45px 15px; }
            #codigo-conducta-page .hero-content h1 { font-size: 2rem; }
            #codigo-conducta-page .hero-content p { font-size: 1rem; }
            #codigo-conducta-page .filter-bar { flex-direction: column; align-items: stretch; }
            #codigo-conducta-page .filter-chips { justify-content: flex-start; }
            #codigo-conducta-page .iso-pillars-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div id="codigo-conducta-page">
        <!-- ==========================================
             BANNER "CÓDIGO DE CONDUCTA : ISO 37001-2016"
             ========================================== -->
        <section class="hero-banner">
            <div class="hero-content">
                <span class="hero-iso-tag">
                    <i class="fa-solid fa-certificate"></i> Estándar Internacional ISO 37001:2016
                </span>
                <h1>Código de Conducta</h1>
                <p>Prevenir conductas indebidas, promover la integridad y establecer reglas claras para las relaciones con clientes, proveedores, trabajadores, autoridades y otros grupos de interés.</p>
            </div>
        </section>
        
        <!-- ==========================================
             SECCIÓN CONTENIDO PRINCIPAL
             ========================================== -->
        <section class="conducta-section">
            <div class="conducta-container">

                <!-- Bloque Superior Dividido: Propósito Institucional + Sistema ISO 37001 -->
                <div class="intro-split-grid">
                    
                    <!-- Tarjeta Izquierda: Propósito Institucional -->
                    <div class="intro-card">
                        <div>
                            <h2><i class="fa-solid fa-shield-halved icon-title"></i> Código de Conducta de KENYA TECHNOLOGY</h2>
                            <p>En <strong>KENYA TECHNOLOGY</strong> (IMPORTACIONES KENYA), como fabricante y distribuidor peruano de computadoras de alto desempeño, nuestra reputación y liderazgo en el mercado nacional se construyen sobre una conducta ética intachable, transparencia irrestricta y cumplimiento cabal de la ley.</p>
                            <p>El presente Código de Conducta establece las directrices fundamentales para prevenir cualquier conducta indebida, promover la integridad y consolidar relaciones justas y transparentes con clientes corporativos, entidades del sector público (Convenio Marco / OSCE), colaboradores y la sociedad civil.</p>
                        </div>
                        <div class="intro-quote">
                            <i class="fa-solid fa-check-circle" style="color: #f26522; margin-right: 6px;"></i>
                            Cero tolerancia frente al soborno y la corrupción en todas las actividades comerciales y procesos de contratación pública y privada.
                        </div>
                    </div>

                    <!-- Tarjeta Derecha: Certificación y Sistema ISO 37001:2016 -->
                    <div class="iso-card">
                        <div class="iso-card-header">
                            <div class="iso-badge-icon">
                                <i class="fa-solid fa-award"></i>
                            </div>
                            <div>
                                <h3>Norma ISO 37001:2016</h3>
                                <span>Sistema de Gestión Antisoborno</span>
                            </div>
                        </div>

                        <div class="iso-pillars-grid">
                            <div class="iso-pillar-item">
                                <span class="pillar-title"><i class="fa-solid fa-magnifying-glass"></i> Debida Diligencia</span>
                                <p class="pillar-desc">Evaluación rigurosa de socios comerciales, proveedores y personal en puestos críticos.</p>
                            </div>
                            <div class="iso-pillar-item">
                                <span class="pillar-title"><i class="fa-solid fa-hand-holding-dollar"></i> Cero Dádivas</span>
                                <p class="pillar-desc">Prohibición de pagos indebidos, atenciones excesivas o ventajas no autorizadas.</p>
                            </div>
                            <div class="iso-pillar-item">
                                <span class="pillar-title"><i class="fa-solid fa-file-shield"></i> Controles Financieros</span>
                                <p class="pillar-desc">Transparencia contable y registro fidedigno de cada transacción comercial.</p>
                            </div>
                            <div class="iso-pillar-item">
                                <span class="pillar-title"><i class="fa-solid fa-user-shield"></i> Protección Total</span>
                                <p class="pillar-desc">Canal de denuncias 100% confidencial con estricta política de no represalias.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Encabezado de los 16 Principios -->
                <div class="section-header-wrap">
                    <div class="section-header-title">
                        <div>
                            <h2><i class="fa-solid fa-list-check" style="color: #f26522; font-size: 1.5rem;"></i> Lineamientos del Código de Conducta</h2>
                            <p>Desglose oficial de los 16 principios rectores que rigen a KENYA TECHNOLOGY en todo el Perú.</p>
                        </div>
                    </div>

                    <!-- Barra de Filtros Temáticos -->
                    <div class="filter-bar">
                        <div class="filter-chips" id="conducta-filter-chips">
                            <button type="button" class="filter-btn active" data-filter="all">Todos (16)</button>
                            <button type="button" class="filter-btn" data-filter="marco">1. Marco y Principios (1-4)</button>
                            <button type="button" class="filter-btn" data-filter="antisoborno">2. Integridad Antisoborno (5-7)</button>
                            <button type="button" class="filter-btn" data-filter="relaciones">3. Relaciones Comerciales (8-9)</button>
                            <button type="button" class="filter-btn" data-filter="activos">4. Activos e Información (10-11)</button>
                            <button type="button" class="filter-btn" data-filter="gobernanza">5. Gobernanza y Denuncias (12-16)</button>
                        </div>
                    </div>
                </div>

                <!-- ==========================================
                     GRID OFICIAL DE LOS 16 PRINCIPIOS
                     ========================================== -->
                <div class="conducta-items-grid" id="conducta-items-grid">

                    <!-- 01. Objetivos -->
                    <div class="conducta-item-card" data-category="marco">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">01</span>
                                <span class="item-cat-tag">Marco General</span>
                            </div>
                            <i class="fa-solid fa-bullseye item-icon"></i>
                        </div>
                        <h3>1. Objetivos</h3>
                        <p>Establecer las directrices de integridad, principios éticos y normas obligatorias que guían el comportamiento y las decisiones de todos los miembros de KENYA TECHNOLOGY, asegurando un entorno laboral digno, legal y alineado con los más altos estándares contra el soborno y la corrupción.</p>
                    </div>

                    <!-- 02. Alcance -->
                    <div class="conducta-item-card" data-category="marco">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">02</span>
                                <span class="item-cat-tag">Marco General</span>
                            </div>
                            <i class="fa-solid fa-users item-icon"></i>
                        </div>
                        <h3>2. Alcance</h3>
                        <p>El presente código es de aplicación y observancia obligatoria para todos los accionistas, directivos, colaboradores técnicos, administrativos y comerciales de la empresa. Asimismo, sus principios vinculan a proveedores, contratistas, distribuidores y terceros que actúen en representación de la marca.</p>
                    </div>

                    <!-- 03. Nuestros valores -->
                    <div class="conducta-item-card" data-category="marco">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">03</span>
                                <span class="item-cat-tag">Cultura</span>
                            </div>
                            <i class="fa-solid fa-hand-holding-heart item-icon"></i>
                        </div>
                        <h3>3. Nuestros valores</h3>
                        <p>Nuestros principios como marca Kenya Technology nos ayudan a conectarnos con la cultura de empresas privadas e instituciones del Estado, basándonos en:</p>
                        <ul>
                            <li><strong>Actitud:</strong> Proactividad y vocación de servicio tecnológico de excelencia.</li>
                            <li><strong>Ética:</strong> Rectitud innegociable en cada relación comercial y profesional.</li>
                            <li><strong>Transparencia:</strong> Veracidad y rendición de cuentas oportuna.</li>
                            <li><strong>Responsabilidad:</strong> Compromiso con la calidad, garantía y el medio ambiente.</li>
                        </ul>
                    </div>

                    <!-- 04. Cumplimiento de las leyes -->
                    <div class="conducta-item-card" data-category="marco">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">04</span>
                                <span class="item-cat-tag">Legal</span>
                            </div>
                            <i class="fa-solid fa-scale-balanced item-icon"></i>
                        </div>
                        <h3>4. Cumplimiento de las leyes</h3>
                        <p>Respeto estricto a la legislación peruana vigente, la Ley N° 30424 (Responsabilidad Administrativa de las Personas Jurídicas), las normativas del OSCE / Convenio Marco y las regulaciones laborales, tributarias y ambientales. Ninguna meta económica justifica actuar al margen de la ley.</p>
                    </div>

                    <!-- 05. Integridad y prevención del soborno -->
                    <div class="conducta-item-card highlight-card" data-category="antisoborno">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">05</span>
                                <span class="item-cat-tag" style="background:#fff7ed;color:#ea580c;">ISO 37001:2016</span>
                            </div>
                            <i class="fa-solid fa-shield-halved item-icon" style="color:#ea580c;"></i>
                        </div>
                        <h3>5. Integridad y prevención del soborno</h3>
                        <p>Tolerancia Cero al soborno y la extorsión. En conformidad con la norma ISO 37001:2016, queda estrictamente prohibido ofrecer, prometer, autorizar, conceder, solicitar o recibir pagos indebidos, coimas, comisiones ilícitas o beneficios no justificados para favorecer contrataciones o resoluciones administrativas.</p>
                    </div>

                    <!-- 06. Conflictos de interés -->
                    <div class="conducta-item-card" data-category="antisoborno">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">06</span>
                                <span class="item-cat-tag">Antisoborno</span>
                            </div>
                            <i class="fa-solid fa-code-branch item-icon"></i>
                        </div>
                        <h3>6. Conflictos de interés</h3>
                        <p>Los colaboradores deben anteponer los intereses institucionales a los personales o financieros. Cualquier situación en la que intereses propios, familiares o de terceros puedan comprometer la objetividad o el juicio profesional debe ser declarada inmediatamente a la administración.</p>
                    </div>

                    <!-- 07. Regalos y atenciones -->
                    <div class="conducta-item-card" data-category="antisoborno">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">07</span>
                                <span class="item-cat-tag">Antisoborno</span>
                            </div>
                            <i class="fa-solid fa-gift item-icon"></i>
                        </div>
                        <h3>7. Regalos y atenciones</h3>
                        <p>Prohibición de entregar o aceptar obsequios, viajes, pagos de atenciones o cortesías de clientes, proveedores o funcionarios del Estado que puedan influir —o aparentar influir— en decisiones de compra o adjudicación. Solo se autorizan artículos promocionales de valor simbólico institucional.</p>
                    </div>

                    <!-- 08. Relación con proveedores y clientes -->
                    <div class="conducta-item-card" data-category="relaciones">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">08</span>
                                <span class="item-cat-tag">Comercial</span>
                            </div>
                            <i class="fa-solid fa-handshake item-icon"></i>
                        </div>
                        <h3>8. Relación con proveedores y clientes</h3>
                        <p>Trato transparente, equitativo y respetuoso. La selección de proveedores se realiza sobre criterios objetivos de calidad técnica, precio de mercado y solvencia moral. A nuestros clientes les brindamos asesoría técnica honesta, garantía real y cumplimiento escrupuloso de los acuerdos pactados.</p>
                    </div>

                    <!-- 09. Relación con autoridades -->
                    <div class="conducta-item-card" data-category="relaciones">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">09</span>
                                <span class="item-cat-tag">Institucional</span>
                            </div>
                            <i class="fa-solid fa-building-columns item-icon"></i>
                        </div>
                        <h3>9. Relación con autoridades</h3>
                        <p>Toda interacción con funcionarios del Estado, inspectores gubernamentales (SUNAT, OEFA, MINAM, SUNAFIL) y entidades contratantes en Convenio Marco se conduce con máxima transparencia, veracidad y respeto. Las comunicaciones formales se canalizan exclusivamente por los voceros acreditados.</p>
                    </div>

                    <!-- 10. Protección de información y datos -->
                    <div class="conducta-item-card" data-category="activos">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">10</span>
                                <span class="item-cat-tag">Seguridad</span>
                            </div>
                            <i class="fa-solid fa-lock item-icon"></i>
                        </div>
                        <h3>10. Protección de información y datos</h3>
                        <p>Custodia rigurosa de la información confidencial, secretos industriales y propiedad intelectual de KENYA TECHNOLOGY y de nuestros clientes. Cumplimiento de la Ley N° 29733 (Ley de Protección de Datos Personales), implementando medidas de ciberseguridad para evitar filtraciones indebidas.</p>
                    </div>

                    <!-- 11. Uso de bienes y recursos de la empresa -->
                    <div class="conducta-item-card" data-category="activos">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">11</span>
                                <span class="item-cat-tag">Activos</span>
                            </div>
                            <i class="fa-solid fa-computer item-icon"></i>
                        </div>
                        <h3>11. Uso de bienes y recursos de la empresa</h3>
                        <p>Los activos tangibles (maquinaria, computadoras, vehículos e inventarios) e intangibles (marcas, licencias y software) están destinados exclusivamente al cumplimiento de las funciones laborales. Todo colaborador debe resguardarlos frente a daños, pérdidas, despilfarro o usos no autorizados.</p>
                    </div>

                    <!-- 12. Canal de consultas y denuncias -->
                    <div class="conducta-item-card highlight-card" data-category="gobernanza">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">12</span>
                                <span class="item-cat-tag" style="background:#fff7ed;color:#ea580c;">Línea Ética</span>
                            </div>
                            <i class="fa-solid fa-envelope-open-text item-icon" style="color:#ea580c;"></i>
                        </div>
                        <h3>12. Canal de consultas y denuncias</h3>
                        <p>Canal formal, directo y confidencial puesto a disposición de colaboradores, proveedores, clientes y la ciudadanía para realizar consultas éticas o reportar incumplimientos a este código, garantizando recepción oportuna, análisis imparcial y resolución fundamentada.</p>
                    </div>

                    <!-- 13. Prohibición de represalias -->
                    <div class="conducta-item-card" data-category="gobernanza">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">13</span>
                                <span class="item-cat-tag">Protección</span>
                            </div>
                            <i class="fa-solid fa-shield-virus item-icon"></i>
                        </div>
                        <h3>13. Prohibición de represalias</h3>
                        <p>KENYA TECHNOLOGY garantiza que ningún colaborador o tercero que denuncie de buena fe cualquier acto irregular será objeto de hostigamiento, despido, degradación laboral o perjuicio directo o indirecto. Cualquier intento de represalia será castigado con la máxima sanción.</p>
                    </div>

                    <!-- 14. Incumplimientos y medidas disciplinarias -->
                    <div class="conducta-item-card" data-category="gobernanza">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">14</span>
                                <span class="item-cat-tag">Disciplina</span>
                            </div>
                            <i class="fa-solid fa-gavel item-icon"></i>
                        </div>
                        <h3>14. Incumplimientos y medidas disciplinarias</h3>
                        <p>Las infracciones al Código de Conducta constituyen faltas graves. De acuerdo a la gravedad y respetando el debido proceso, se aplicarán medidas proporcionales que abarcan amonestaciones, suspensiones o el despido por falta grave, además de las acciones civiles y penales que correspondan ante la justicia.</p>
                    </div>

                    <!-- 15. Capacitación y actualización -->
                    <div class="conducta-item-card" data-category="gobernanza">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">15</span>
                                <span class="item-cat-tag">Formación</span>
                            </div>
                            <i class="fa-solid fa-graduation-cap item-icon"></i>
                        </div>
                        <h3>15. Capacitación y actualización</h3>
                        <p>Desarrollo de capacitaciones periódicas obligatorias sobre ética empresarial, dilemas de conducta y prevención del soborno bajo el estándar ISO 37001:2016. La empresa revisa y actualiza este código continuamente para reflejar las mejores prácticas del mercado.</p>
                    </div>

                    <!-- 16. Declaración de compromiso -->
                    <div class="conducta-item-card" data-category="gobernanza">
                        <div class="item-top-row">
                            <div class="item-badge-wrap">
                                <span class="item-num">16</span>
                                <span class="item-cat-tag">Compromiso</span>
                            </div>
                            <i class="fa-solid fa-file-signature item-icon"></i>
                        </div>
                        <h3>16. Declaración de compromiso</h3>
                        <p>Todo trabajador, directivo y aliado comercial suscribe una declaración formal de conocimiento, aceptación y adhesión al Código de Conducta de KENYA TECHNOLOGY al iniciar sus funciones y en evaluaciones periódicas, ratificando su compromiso activo con la integridad corporativa.</p>
                    </div>

                </div>

                <!-- ==========================================
                     BLOQUE DE CANAL ÉTICO Y DENUNCIAS
                     ========================================== -->
                <div class="canal-etico-box">
                    <div class="canal-info">
                        <h2><i class="fa-solid fa-envelope-open-text" style="color:#f26522;"></i> Canal de Consultas y Denuncias Éticas</h2>
                        <p>Si eres colaborador, cliente, proveedor o ciudadano y tienes conocimiento de alguna conducta contraria a este código, a la norma ISO 37001:2016 o a la legislación peruana, repórtalo con total confianza a través de nuestros canales oficiales:</p>
                        <div class="canal-guarantees">
                            <div class="guarantee-item">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Garantía de confidencialidad y reserva de identidad.</span>
                            </div>
                            <div class="guarantee-item">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Estricta prohibición y sanción a cualquier intento de represalia.</span>
                            </div>
                            <div class="guarantee-item">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Investigación objetiva y oportuna por el Comité de Integridad.</span>
                            </div>
                        </div>
                    </div>

                    <div class="canal-cards">
                        <a href="mailto:acuerdos.marco@kenya.com.pe" class="contact-card-link">
                            <div class="contact-card-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="contact-card-texts">
                                <span class="label">Canal Oficial Convenio Marco / Ética</span>
                                <span class="val">acuerdos.marco@kenya.com.pe</span>
                            </div>
                        </a>

                        <a href="mailto:soporte@kenya.com.pe" class="contact-card-link">
                            <div class="contact-card-icon">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <div class="contact-card-texts">
                                <span class="label">Consultas Generales y Soporte</span>
                                <span class="val">soporte@kenya.com.pe</span>
                            </div>
                        </a>

                        <div class="contact-card-link" style="cursor: default;">
                            <div class="contact-card-icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="contact-card-texts">
                                <span class="label">Línea Telefónica Directa</span>
                                <span class="val">+51 958 021 778</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- Script de filtrado interactivo -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('#conducta-filter-chips .filter-btn');
            const items = document.querySelectorAll('#conducta-items-grid .conducta-item-card');

            if (!filterBtns.length || !items.length) return;

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    items.forEach(item => {
                        const category = item.getAttribute('data-category');
                        if (filter === 'all' || category === filter) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
@endsection
