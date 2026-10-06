@extends('layouts.landing')

@section('title', 'Código de Conducta e Integridad · ISO 37001:2016 | KENYA Technology')
@section('meta_description', 'Código de Conducta y Sistema de Gestión Antisoborno ISO 37001:2016 de KENYA Technology. Principios de integridad, transparencia y ética para clientes, proveedores, trabajadores y el Estado.')
@section('meta_keywords', 'codigo de conducta kenya, iso 37001 2016 kenya technology, sistema gestion antisoborno, etica corporativa peru, integridad convenio marco')
@section('canonical', route('codigo.conducta'))
@section('og_title', 'Código de Conducta · ISO 37001:2016 | KENYA Technology Perú')
@section('og_description', 'Conoce los 16 lineamientos éticos y la certificación ISO 37001:2016 de KENYA Technology.')

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
            --conducta-orange: #f26522;
            --conducta-orange-dark: #d95315;
            --conducta-orange-soft: #fff4ed;
            --conducta-navy: #1b2633;
            --conducta-ink: #0f172a;
            --conducta-text: #334155;
            --conducta-muted: #64748b;
            --conducta-line: #e2e8f0;
            --conducta-bg-card: #f8fafc;
        }

        #codigo-conducta-page {
            background-color: #ffffff;
            color: var(--conducta-text);
            line-height: 1.6;
            font-family: inherit;
        }

        /* ── Hero Banner ── */
        #codigo-conducta-page .hero-banner {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            background-image: linear-gradient(rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.65)), url('{{ asset("banersomos.png?v=2") }}');
            background-size: cover;
            background-position: right;
            color: var(--conducta-ink);
            text-align: left;
            padding: 70px 15px;
            margin-bottom: 0;
            border-bottom: 1px solid var(--conducta-line);
        }

        #codigo-conducta-page .hero-content {
            position: relative;
            z-index: 2; 
            max-width: 1240px;
            margin: 0 auto;
            width: 100%;
            padding: 0 10px;
        }

        #codigo-conducta-page .hero-iso-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--conducta-navy);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 14px;
        }

        #codigo-conducta-page .hero-iso-tag i {
            color: var(--conducta-orange);
        }

        #codigo-conducta-page .hero-content h1 {
            font-size: 2.8rem;
            margin: 0 0 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--conducta-ink);
            line-height: 1.15;
        }

        #codigo-conducta-page .hero-content p {
            font-size: 1.15rem;
            font-weight: 500;
            margin: 0;
            color: var(--conducta-muted);
        }

        /* ── Contenedor Editorial ── */
        .editorial-wrapper {
            max-width: 1240px;
            margin: 0 auto;
            padding: 34px 20px 80px;
        }

        /* Migas de pan */
        .conducta-crumbs {
            font-size: 0.88rem;
            color: var(--conducta-muted);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .conducta-crumbs a {
            color: var(--conducta-muted);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .conducta-crumbs a:hover {
            color: var(--conducta-orange);
        }

        .conducta-crumbs .sep {
            color: #cbd5e1;
        }

        .conducta-crumbs .current {
            color: var(--conducta-ink);
            font-weight: 600;
        }

        /* Grid a 2 columnas */
        .editorial-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, 0.9fr);
            gap: 56px;
            align-items: start;
        }

        /* Columna Izquierda: Contenido y Lineamientos */
        .editorial-main .conducta-eyebrow {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--conducta-orange);
            margin-bottom: 8px;
        }

        .editorial-main h2.main-title {
            font-size: 1.95rem;
            line-height: 1.25;
            color: var(--conducta-ink);
            margin: 0 0 16px;
            font-weight: 800;
        }

        .editorial-main .lead-text {
            color: var(--conducta-text);
            font-size: 1.05rem;
            line-height: 1.75;
            margin: 0 0 26px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--conducta-line);
        }

        /* Cabecera de controles del acordeón */
        .accordion-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .accordion-toolbar .count-tag {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--conducta-muted);
        }

        .btn-toggle-all {
            background: none;
            border: 0;
            color: var(--conducta-orange);
            font-weight: 700;
            cursor: pointer;
            padding: 6px 0;
            font-size: 0.88rem;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.15s ease;
        }

        .btn-toggle-all:hover {
            color: var(--conducta-orange-dark);
            text-decoration: underline;
        }

        /* Lista Acordeón */
        .conducta-accordion-list {
            list-style: none;
            margin: 0;
            padding: 0;
            border-top: 1px solid var(--conducta-line);
        }

        .conducta-accordion-item {
            border-bottom: 1px solid var(--conducta-line);
            transition: background-color 0.2s ease;
        }

        .conducta-accordion-item details {
            width: 100%;
        }

        .conducta-accordion-item summary {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 8px;
            cursor: pointer;
            list-style: none;
            user-select: none;
        }

        .conducta-accordion-item summary::-webkit-details-marker {
            display: none;
        }

        .conducta-accordion-item .num-badge {
            font-variant-numeric: tabular-nums;
            font-weight: 800;
            color: var(--conducta-orange);
            min-width: 28px;
            font-size: 0.98rem;
            letter-spacing: -0.5px;
        }

        .conducta-accordion-item .item-title {
            flex: 1;
            font-weight: 700;
            font-size: 1.02rem;
            color: var(--conducta-ink);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.15s ease;
        }

        .conducta-accordion-item .item-title i.item-icon {
            font-size: 0.9rem;
            color: var(--conducta-muted);
            transition: color 0.15s ease;
        }

        .conducta-accordion-item summary i.chevron-icon {
            color: #94a3b8;
            font-size: 0.85rem;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), color 0.15s;
        }

        .conducta-accordion-item summary:hover .item-title {
            color: var(--conducta-orange);
        }

        .conducta-accordion-item summary:hover .item-icon {
            color: var(--conducta-orange);
        }

        .conducta-accordion-item details[open] {
            background-color: var(--conducta-bg-card);
        }

        .conducta-accordion-item details[open] summary .item-title {
            color: var(--conducta-orange-dark);
        }

        .conducta-accordion-item details[open] summary .item-icon {
            color: var(--conducta-orange);
        }

        .conducta-accordion-item details[open] summary i.chevron-icon {
            transform: rotate(180deg);
            color: var(--conducta-orange);
        }

        /* Contenido interior del acordeón */
        .conducta-accordion-item .accordion-body {
            padding: 0 12px 20px 52px;
            font-size: 0.96rem;
            line-height: 1.75;
            color: var(--conducta-text);
        }

        .conducta-accordion-item .accordion-body p {
            margin: 0;
        }

        .conducta-accordion-item .valores-list {
            margin: 10px 0 0;
            padding-left: 20px;
            list-style-type: square;
        }

        .conducta-accordion-item .valores-list li {
            margin-bottom: 4px;
            font-weight: 600;
            color: var(--conducta-ink);
        }

        /* Resalte para punto 05 (Prevención del soborno e ISO 37001) */
        .conducta-accordion-item.iso-highlight {
            border-left: 3px solid var(--conducta-orange);
            padding-left: 6px;
        }

        .conducta-accordion-item.iso-highlight .badge-iso-chip {
            display: inline-block;
            background: var(--conducta-orange-soft);
            color: var(--conducta-orange-dark);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 6px;
            text-transform: uppercase;
        }

        /* Columna Derecha: Sticky Sidebar */
        .editorial-sidebar {
            position: sticky;
            top: 30px;
        }

        .iso-figure-card {
            margin: 0;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--conducta-line);
            box-shadow: 0 16px 36px -16px rgba(15, 23, 42, 0.15);
        }

        .iso-figure-card img {
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover;
            background-color: #f1f5f9;
        }

        .iso-figure-caption {
            background: var(--conducta-navy);
            color: #e2e8f0;
            font-size: 0.84rem;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .iso-figure-caption i {
            color: var(--conducta-orange);
            font-size: 1rem;
        }

        /* Tarjeta de Denuncias / Canal Ético */
        .canal-reporte-card {
            margin-top: 22px;
            padding: 24px;
            border: 1px solid var(--conducta-line);
            border-left: 4px solid var(--conducta-orange);
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 8px 24px -12px rgba(15, 23, 42, 0.08);
        }

        .canal-reporte-card h3 {
            margin: 0 0 8px;
            font-size: 1.15rem;
            color: var(--conducta-ink);
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .canal-reporte-card h3 i {
            color: var(--conducta-orange);
        }

        .canal-reporte-card p {
            margin: 0 0 18px;
            font-size: 0.92rem;
            color: var(--conducta-muted);
            line-height: 1.6;
        }

        .canal-reporte-card .canal-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .canal-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 11px 18px;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .canal-btn-primary {
            background-color: var(--conducta-orange);
            color: #ffffff;
            border: 1px solid var(--conducta-orange);
        }

        .canal-btn-primary:hover {
            background-color: var(--conducta-orange-dark);
            border-color: var(--conducta-orange-dark);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .canal-btn-outline {
            background-color: transparent;
            color: var(--conducta-navy);
            border: 1px solid var(--conducta-line);
        }

        .canal-btn-outline:hover {
            background-color: var(--conducta-bg-card);
            border-color: var(--conducta-navy);
            color: var(--conducta-ink);
        }

        .canal-seguridad-note {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            font-size: 0.8rem;
            color: var(--conducta-muted);
        }

        .canal-seguridad-note i {
            color: #10b981;
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

            #codigo-conducta-page .hero-content h1 {
                font-size: 2.2rem;
            }

            .editorial-main h2.main-title {
                font-size: 1.65rem;
            }

            .conducta-accordion-item .accordion-body {
                padding-left: 20px;
            }
        }
    </style>

    <div id="codigo-conducta-page">
        <!-- Hero Banner Oficial -->
        <section class="hero-banner">
            <div class="hero-content">
                <span class="hero-iso-tag">
                    <i class="fa-solid fa-award"></i> Certificación ISO 37001:2016
                </span>
                <h1>Código de Conducta</h1>
                <p>Sistema de Gestión Antisoborno y Principios de Integridad Empresarial</p>
            </div>
        </section>

        <!-- Bloque Editorial Principal -->
        <main class="editorial-wrapper">
            <!-- Migas de pan -->
            <nav class="conducta-crumbs" aria-label="Navegación">
                <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i> Inicio</a>
                <span class="sep">/</span>
                <span>Nuestra Empresa</span>
                <span class="sep">/</span>
                <span class="current">Código de Conducta</span>
            </nav>

            <div class="editorial-grid">
                <!-- Columna Izquierda: Introducción y 16 Principios -->
                <section class="editorial-main">
                    <span class="conducta-eyebrow">Nuestra Empresa</span>
                    <h2 class="main-title">Código de Conducta de KENYA TECHNOLOGY</h2>
                    <p class="lead-text">
                        Prevenir conductas indebidas, promover la integridad y establecer reglas claras para las relaciones con clientes, proveedores, trabajadores, autoridades y otros grupos de interés, teniendo en cuenta:
                    </p>

                    <div class="accordion-toolbar">
                        <span class="count-tag"><i class="fa-solid fa-list-check"></i> 16 lineamientos institucionales</span>
                        <button type="button" class="btn-toggle-all" id="btnToggleAll">
                            <i class="fa-solid fa-up-down-left-right"></i> <span id="toggleText">Mostrar todos los detalles</span>
                        </button>
                    </div>

                    <ol class="conducta-accordion-list" id="conductaAccordionList">
                        <!-- 01 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">01</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-bullseye item-icon"></i>
                                        Objetivos
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Definir las reglas de integridad que guían las decisiones de todas las personas que forman parte de KENYA TECHNOLOGY y prevenir cualquier conducta indebida en el desarrollo de nuestras operaciones.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 02 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">02</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-users item-icon"></i>
                                        Alcance
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Aplica a accionistas, directivos y trabajadores, y se extiende a proveedores, distribuidores y terceros que actúan en nombre de la marca o representan a la empresa ante clientes y el Estado.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 03 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">03</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-hand-holding-heart item-icon"></i>
                                        Nuestros valores
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>La base ética y operacional de cada relación con empresas privadas y entidades del Estado peruano:</p>
                                    <ul class="valores-list">
                                        <li>Actitud</li>
                                        <li>Ética</li>
                                        <li>Transparencia</li>
                                        <li>Responsabilidad</li>
                                    </ul>
                                </div>
                            </details>
                        </li>

                        <!-- 04 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">04</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-scale-balanced item-icon"></i>
                                        Cumplimiento de las leyes
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Cumplimos estrictamente la legislación peruana, la Ley N° 30424 sobre responsabilidad administrativa de personas jurídicas y la normativa de contrataciones del Estado. Ninguna meta comercial o de ventas justifica actuar fuera de la ley.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 05 (Destacado ISO 37001) -->
                        <li class="conducta-accordion-item iso-highlight">
                            <details>
                                <summary>
                                    <span class="num-badge">05</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-shield-halved item-icon" style="color: var(--conducta-orange);"></i>
                                        Integridad y prevención del soborno
                                        <span class="badge-iso-chip">ISO 37001</span>
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Está terminantemente prohibido ofrecer, prometer, entregar, solicitar o aceptar pagos indebidos, dádivas o ventajas de cualquier índole para obtener contratos, licitaciones o decisiones favorables en el sector público o privado.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 06 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">06</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-code-branch item-icon"></i>
                                        Conflictos de interés
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Toda situación en la que un interés personal, comercial o familiar pueda interferir con el juicio objetivo y las decisiones de trabajo debe declararse formalmente y por escrito antes de realizar cualquier gestión.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 07 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">07</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-gift item-icon"></i>
                                        Regalos y atenciones
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>No se entregan ni aceptan regalos, viajes, agasajos o atenciones que puedan comprometer la imparcialidad o influir en una compra o adjudicación. Solo se permite material publicitario o promocional de valor simbólico e institucional.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 08 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">08</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-handshake item-icon"></i>
                                        Relación con proveedores y clientes
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Elegimos a nuestros proveedores mediante criterios objetivos de calidad, precio y solvencia técnica. A nuestros clientes les brindamos información técnica veraz, asesoría honesta y cumplimos rigurosamente los términos de garantía ofrecidos.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 09 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">09</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-building-columns item-icon"></i>
                                        Relación con autoridades
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>El trato con funcionarios públicos, inspectores y entidades del Estado es transparente, respetuoso, debidamente documentado por los canales formales y a cargo exclusivamente de personal expresamente autorizado por la empresa.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 10 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">10</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-lock item-icon"></i>
                                        Protección de información y datos
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Resguardamos la confidencialidad de la información estratégica de la empresa y los datos de nuestros clientes conforme a la Ley N° 29733 de Protección de Datos Personales y las mejores prácticas de seguridad de la información.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 11 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">11</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-computer item-icon"></i>
                                        Uso de bienes y recursos de la empresa
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Equipos de cómputo, vehículos, inventarios, instalaciones y software corporativo se destinan únicamente al desarrollo de labores profesionales autorizadas, asegurando su debido cuidado frente a pérdidas o mal uso.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 12 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">12</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-envelope-open-text item-icon"></i>
                                        Canal de consultas y denuncias
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Cualquier colaborador, cliente o proveedor puede consultar dudas o reportar de forma confidencial posibles incumplimientos éticos a través de nuestros canales oficiales de ética y cumplimiento.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 13 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">13</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-user-shield item-icon"></i>
                                        Prohibición de represalias
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Garantizamos que ninguna persona que comunique una inquietud o denuncie una conducta indebida de buena fe será sometida a despidos, sanciones, hostigamiento ni perjuicio alguno en su relación con la empresa.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 14 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">14</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-gavel item-icon"></i>
                                        Incumplimientos y medidas disciplinarias
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Las infracciones a este código son investigadas garantizando el debido proceso y son sancionadas con proporcionalidad a su gravedad, sin perjuicio de interponer las acciones civiles y penales que la ley determine.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 15 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">15</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-graduation-cap item-icon"></i>
                                        Capacitación y actualización
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Desarrollamos programas periódicos de capacitación y sensibilización en prevención del soborno y ética corporativa, revisando y actualizando continuamente nuestras políticas para asegurar su pertinencia.</p>
                                </div>
                            </details>
                        </li>

                        <!-- 16 -->
                        <li class="conducta-accordion-item">
                            <details>
                                <summary>
                                    <span class="num-badge">16</span>
                                    <span class="item-title">
                                        <i class="fa-solid fa-file-signature item-icon"></i>
                                        Declaración de compromiso
                                    </span>
                                    <i class="fa-solid fa-chevron-down chevron-icon"></i>
                                </summary>
                                <div class="accordion-body">
                                    <p>Cada colaborador, directivo y aliado comercial suscribe formalmente su conocimiento, adhesión y compromiso de cumplimiento con este Código de Conducta al integrarse a KENYA TECHNOLOGY.</p>
                                </div>
                            </details>
                        </li>
                    </ol>
                </section>

                <!-- Columna Derecha: Tarjeta Fija con Imagen ISO 37001 y Canal Ético -->
                <aside class="editorial-sidebar">
                    <figure class="iso-figure-card">
                        <img src="{{ asset('iso-37001.png') }}" alt="Sistema de Gestión Antisoborno ISO 37001 de KENYA TECHNOLOGY" loading="lazy">
                        <figcaption class="iso-figure-caption">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Sistema Antisoborno alineado a la norma ISO 37001:2016</span>
                        </figcaption>
                    </figure>

                    <div class="canal-reporte-card">
                        <h3><i class="fa-solid fa-bullhorn"></i> ¿Viste una conducta indebida?</h3>
                        <p>
                            Reporta cualquier sospecha de soborno, fraude o incumplimiento de forma confidencial. Nadie será perjudicado por denunciar de buena fe.
                        </p>
                        <div class="canal-actions">
                            <a class="canal-btn canal-btn-primary" href="mailto:acuerdos.marco@kenya.com.pe">
                                <i class="fa-solid fa-envelope"></i> acuerdos.marco@kenya.com.pe
                            </a>
                            <a class="canal-btn canal-btn-outline" href="tel:+51958021778">
                                <i class="fa-solid fa-phone"></i> 958 021 778
                            </a>
                            <a class="canal-btn canal-btn-outline" href="mailto:soporte@kenya.com.pe">
                                <i class="fa-solid fa-headset"></i> soporte@kenya.com.pe
                            </a>
                        </div>
                        <div class="canal-seguridad-note">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>Canal protegido con reserva de identidad</span>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var btnToggle = document.getElementById('btnToggleAll');
            var toggleText = document.getElementById('toggleText');
            var list = document.getElementById('conductaAccordionList');

            if (btnToggle && list) {
                btnToggle.addEventListener('click', function () {
                    var detailsList = list.querySelectorAll('details');
                    var isCurrentlyOpen = btnToggle.getAttribute('data-open') === 'true';
                    var newState = !isCurrentlyOpen;

                    detailsList.forEach(function (det) {
                        det.open = newState;
                    });

                    btnToggle.setAttribute('data-open', newState ? 'true' : 'false');
                    toggleText.textContent = newState ? 'Ocultar detalles' : 'Mostrar todos los detalles';
                });
            }
        });
    </script>
@endsection
