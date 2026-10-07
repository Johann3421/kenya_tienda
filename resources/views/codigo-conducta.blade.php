@extends('layouts.landing')

@section('title', 'Código de Conducta e Integridad · ISO 37001:2025 | KENYA Technology')
@section('meta_description', 'Código de Conducta y Sistema de Gestión Antisoborno ISO 37001:2025 de KENYA Technology. Principios de integridad, transparencia y ética para clientes, proveedores, trabajadores y el Estado.')
@section('meta_keywords', 'codigo de conducta kenya, iso 37001 2025 kenya technology, sistema gestion antisoborno, etica corporativa peru, integridad convenio marco')
@section('canonical', route('codigo.conducta'))
@section('og_title', 'Código de Conducta · ISO 37001:2025 | KENYA Technology Perú')
@section('og_description', 'Conoce los 16 lineamientos éticos y la certificación ISO 37001:2025 de KENYA Technology.')

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

@php
    $articulos = [
        ['Objetivos', 'Definir las reglas de integridad que guían las decisiones de todas las personas que forman parte de KENYA TECHNOLOGY y prevenir cualquier conducta indebida en el desarrollo de nuestras operaciones.'],
        ['Alcance', 'Aplica a accionistas, directivos y trabajadores, y se extiende a proveedores, distribuidores y terceros que actúan en nombre de la marca o representan a la empresa ante clientes y el Estado.'],
        ['Nuestros valores', 'La base ética y operacional de cada relación con empresas privadas y entidades del Estado peruano:', ['Actitud', 'Ética', 'Transparencia', 'Responsabilidad']],
        ['Cumplimiento de las leyes', 'Cumplimos estrictamente la legislación peruana, la Ley N° 30424 sobre responsabilidad administrativa de personas jurídicas y la normativa de contrataciones del Estado. Ninguna meta comercial o de ventas justifica actuar fuera de la ley.'],
        ['Integridad y prevención del soborno', 'Está terminantemente prohibido ofrecer, prometer, entregar, solicitar o aceptar pagos indebidos, dádivas o ventajas de cualquier índole para obtener contratos, licitaciones o decisiones favorables en el sector público o privado.'],
        ['Conflictos de interés', 'Toda situación en la que un interés personal, comercial o familiar pueda interferir con el juicio objetivo y las decisiones de trabajo debe declararse formalmente y por escrito antes de realizar cualquier gestión.'],
        ['Regalos y atenciones', 'No se entregan ni aceptan regalos, viajes, agasajos o atenciones que puedan comprometer la imparcialidad o influir en una compra o adjudicación. Solo se permite material publicitario o promocional de valor simbólico e institucional.'],
        ['Relación con proveedores y clientes', 'Elegimos a nuestros proveedores mediante criterios objetivos de calidad, precio y solvencia técnica. A nuestros clientes les brindamos información técnica veraz, asesoría honesta y cumplimos rigurosamente los términos de garantía ofrecidos.'],
        ['Relación con autoridades', 'El trato con funcionarios públicos, inspectores y entidades del Estado es transparente, respetuoso, debidamente documentado por los canales formales y a cargo exclusivamente de personal expresamente autorizado por la empresa.'],
        ['Protección de información y datos', 'Resguardamos la confidencialidad de la información estratégica de la empresa y los datos de nuestros clientes conforme a la Ley N° 29733 de Protección de Datos Personales y las mejores prácticas de seguridad de la información.'],
        ['Uso de bienes y recursos de la empresa', 'Equipos de cómputo, vehículos, inventarios, instalaciones y software corporativo se destinan únicamente al desarrollo de labores profesionales autorizadas, asegurando su debido cuidado frente a pérdidas o mal uso.'],
        ['Canal de consultas y denuncias', 'Cualquier colaborador, cliente o proveedor puede consultar dudas o reportar de forma confidencial posibles incumplimientos éticos a través de nuestros canales oficiales de ética y cumplimiento.'],
        ['Prohibición de represalias', 'Garantizamos que ninguna persona que comunique una inquietud o denuncie una conducta indebida de buena fe será sometida a despidos, sanciones, hostigamiento ni perjuicio alguno en su relación con la empresa.'],
        ['Incumplimientos y medidas disciplinarias', 'Las infracciones a este código son investigadas garantizando el debido proceso y son sancionadas con proporcionalidad a su gravedad, sin perjuicio de interponer las acciones civiles y penales que la ley determine.'],
        ['Capacitación y actualización', 'Desarrollamos programas periódicos de capacitación y sensibilización en prevención del soborno y ética corporativa, revisando y actualizando continuamente nuestras políticas para asegurar su pertinencia.'],
        ['Declaración de compromiso', 'Cada colaborador, directivo y aliado comercial suscribe formalmente su conocimiento, adhesión y compromiso de cumplimiento con este Código de Conducta al integrarse a KENYA TECHNOLOGY.'],
    ];
@endphp

@section('content')
    <style>
        #cc-page {
            --cc-accent: #f26522;
            --cc-accent-dark: #c9501a;
            --cc-navy: #1b2633;
            --cc-ink: #111827;
            --cc-text: #374151;
            --cc-muted: #6b7280;
            --cc-rule: #e5e7eb;
            --cc-paper: #fafaf9;
            background: #fff;
            color: var(--cc-text);
            line-height: 1.7;
        }

        /* Hero (patrón unificado del sitio) */
        #cc-page .cc-hero {
            background-image: linear-gradient(rgba(255,255,255,.7), rgba(255,255,255,.7)), url('{{ asset("banersomos.png?v=2") }}');
            background-size: cover;
            background-position: right;
            padding: 64px 15px;
            border-bottom: 1px solid var(--cc-rule);
        }
        #cc-page .cc-hero-inner { max-width: 1400px; margin: 0 auto; padding: 0 10px; }
        #cc-page .cc-hero h1 {
            margin: 0 0 6px;
            font-size: 2.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--cc-ink);
            line-height: 1.15;
        }
        #cc-page .cc-hero p { margin: 0; font-size: 1.1rem; color: var(--cc-muted); }

        #cc-page .cc-wrap { max-width: 1400px; margin: 0 auto; padding: 28px 20px 96px; }

        #cc-page .cc-crumbs { font-size: .85rem; color: var(--cc-muted); margin-bottom: 36px; }
        #cc-page .cc-crumbs a { color: var(--cc-muted); text-decoration: none; }
        #cc-page .cc-crumbs a:hover { color: var(--cc-accent); }
        #cc-page .cc-crumbs span { margin: 0 6px; color: #d1d5db; }

        /* Cabecera del documento */
        #cc-page .cc-head {
            padding-bottom: 28px;
            border-bottom: 2px solid var(--cc-ink);
        }
        #cc-page .cc-kicker {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--cc-accent);
            margin-bottom: 12px;
        }
        #cc-page .cc-head h2 {
            margin: 0 0 14px;
            font-size: 2.3rem;
            line-height: 1.2;
            font-weight: 800;
            color: var(--cc-ink);
            letter-spacing: -.02em;
        }
        #cc-page .cc-head .cc-lead {
            margin: 0;
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 72ch;
            color: #4b5563;
        }

        /* Ficha técnica del documento */
        #cc-page .cc-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 320px));
            margin: 0 0 54px;
            border-bottom: 1px solid var(--cc-rule);
        }
        #cc-page .cc-meta div { padding: 18px 28px 18px 0; }
        #cc-page .cc-meta div + div { padding-left: 28px; border-left: 1px solid var(--cc-rule); }
        #cc-page .cc-meta dt {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--cc-muted);
            margin-bottom: 4px;
        }
        #cc-page .cc-meta dd { margin: 0; font-weight: 600; color: var(--cc-ink); font-size: .95rem; }

        /* Cuerpo principal a 2 columnas: Artículos (izquierda) + Sidebar con Imagen Lateral (derecha) */
        #cc-page .cc-body {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 380px;
            gap: 56px;
            align-items: start;
        }

        /* Columna Izquierda: Artículos */
        #cc-page .cc-content {
            min-width: 0;
        }

        #cc-page .cc-art-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        #cc-page .cc-art-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 16px 0;
            border-bottom: 1px solid var(--cc-rule);
        }
        #cc-page .cc-art-item:first-child { padding-top: 0; }
        #cc-page .cc-art-num {
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1;
            color: #9ca3af;
            font-variant-numeric: tabular-nums;
            min-width: 28px;
        }
        #cc-page .cc-art-title {
            margin: 0;
            font-size: 1.08rem;
            font-weight: 700;
            color: var(--cc-ink);
            line-height: 1.35;
        }
        #cc-page .cc-art-item.is-key {
            background: var(--cc-paper);
            margin: 0 -16px;
            padding-left: 16px;
            padding-right: 16px;
            border-left: 3px solid var(--cc-accent);
        }
        #cc-page .cc-art-item.is-key .cc-art-num { color: var(--cc-accent); }
        #cc-page .cc-art-item.is-key .cc-art-title { color: var(--cc-ink); }

        /* Canal de denuncias en bloque final */
        #cc-page .cc-report {
            margin-top: 64px;
            padding-top: 32px;
            border-top: 2px solid var(--cc-ink);
            scroll-margin-top: 24px;
        }
        #cc-page .cc-report h3 { margin: 0 0 10px; font-size: 1.55rem; font-weight: 800; color: var(--cc-ink); letter-spacing: -.01em; }
        #cc-page .cc-report > p { margin: 0 0 24px; max-width: 66ch; }
        #cc-page .cc-table { width: 100%; border-collapse: collapse; font-size: .95rem; margin-bottom: 24px; }
        #cc-page .cc-table th {
            text-align: left;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--cc-muted);
            padding: 0 16px 10px 0;
            border-bottom: 1px solid var(--cc-ink);
        }
        #cc-page .cc-table td { padding: 14px 16px 14px 0; border-bottom: 1px solid var(--cc-rule); vertical-align: top; }
        #cc-page .cc-table td:first-child { font-weight: 600; color: var(--cc-ink); white-space: nowrap; }
        #cc-page .cc-table a { color: var(--cc-ink); text-decoration: underline; text-decoration-color: var(--cc-accent); text-underline-offset: 3px; }
        #cc-page .cc-table a:hover { color: var(--cc-accent); }

        #cc-page .cc-btn {
            display: inline-block;
            background: var(--cc-navy);
            color: #fff;
            padding: 13px 24px;
            font-weight: 600;
            font-size: .95rem;
            text-decoration: none;
            border-radius: 2px;
            transition: background .15s ease;
        }
        #cc-page .cc-btn:hover { background: var(--cc-accent); color: #fff; }
        #cc-page .cc-fine { margin: 14px 0 0; font-size: .84rem; color: var(--cc-muted); }

        /* Columna Derecha: Sidebar Lateral Fija (Sticky) con la Imagen ISO 37001 Prominente */
        #cc-page .cc-sidebar {
            position: sticky;
            top: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        #cc-page .cc-iso-card {
            margin: 0;
            background: #ffffff;
            border: 1px solid var(--cc-rule);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 10px 25px -10px rgba(17, 24, 39, 0.08);
        }
        #cc-page .cc-iso-card img {
            display: block;
            width: 100%;
            height: auto;
            background: #fafaf9;
            padding: 18px;
            box-sizing: border-box;
        }
        #cc-page .cc-iso-card figcaption {
            background: var(--cc-navy);
            color: #f3f4f6;
            padding: 12px 18px;
            font-size: .84rem;
            display: flex;
            flex-direction: column;
            gap: 2px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        #cc-page .cc-iso-card figcaption strong {
            color: #fff;
            font-weight: 700;
            letter-spacing: .02em;
        }
        #cc-page .cc-iso-card figcaption span {
            color: #9ca3af;
            font-size: .8rem;
        }

        #cc-page .cc-sidebar-box {
            background: var(--cc-paper);
            border: 1px solid var(--cc-rule);
            border-left: 3px solid var(--cc-accent);
            border-radius: 4px;
            padding: 20px;
        }
        #cc-page .cc-sidebar-box-title {
            margin: 0 0 6px;
            font-size: .95rem;
            font-weight: 700;
            color: var(--cc-ink);
        }
        #cc-page .cc-sidebar-box-desc {
            margin: 0 0 14px;
            font-size: .85rem;
            color: var(--cc-muted);
            line-height: 1.5;
        }
        #cc-page .cc-sidebar-contacts {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }
        #cc-page .cc-sidebar-contacts a {
            font-size: .84rem;
            color: var(--cc-ink);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        #cc-page .cc-sidebar-contacts a:hover {
            color: var(--cc-accent);
        }
        #cc-page .cc-sidebar-contacts a i {
            color: var(--cc-accent);
            font-size: .8rem;
            width: 14px;
        }
        #cc-page .cc-btn-sm {
            display: block;
            text-align: center;
            padding: 10px 14px;
            font-size: .86rem;
            width: 100%;
            box-sizing: border-box;
        }


        /* ── Responsive ── */
        @media (max-width: 991px) {
            #cc-page .cc-hero h1 { font-size: 2rem; }
            #cc-page .cc-head h2 { font-size: 1.9rem; }
            #cc-page .cc-meta { grid-template-columns: 1fr 1fr; }
            #cc-page .cc-body { grid-template-columns: 1fr; gap: 40px; }
            #cc-page .cc-sidebar { position: static; max-width: 480px; margin: 0 auto; width: 100%; }
        }
        @media (max-width: 575px) {
            #cc-page .cc-table thead { display: none; }
            #cc-page .cc-table td { display: block; padding: 2px 0; border: 0; }
            #cc-page .cc-table tr { display: block; padding: 12px 0; border-bottom: 1px solid var(--cc-rule); }
        }
    </style>

    <div id="cc-page">
        <section class="cc-hero">
            <div class="cc-hero-inner">
                <h1>Código de Conducta</h1>
                <p>Sistema de Gestión Antisoborno y Principios de Integridad Empresarial</p>
            </div>
        </section>

        <main class="cc-wrap">
            <nav class="cc-crumbs" aria-label="Ruta de navegación">
                <a href="{{ url('/') }}">Inicio</a><span>/</span>Nuestra Empresa<span>/</span><strong>Código de Conducta</strong>
            </nav>

            <header class="cc-head">
                <span class="cc-kicker">Nuestra Empresa · Integridad Corporativa</span>
                <h2>Código de Conducta de KENYA TECHNOLOGY</h2>
                <p class="cc-lead">
                    Prevenir conductas indebidas, promover la integridad y establecer reglas claras para las relaciones con clientes, proveedores, trabajadores, autoridades y otros grupos de interés, teniendo en cuenta los siguientes lineamientos institucionales.
                </p>
            </header>

            <dl class="cc-meta">
                <div><dt>Norma de referencia</dt><dd>ISO 37001:2025</dd></div>
                <div><dt>Lineamientos</dt><dd>{{ count($articulos) }} artículos</dd></div>
            </dl>

            <div class="cc-body">
                <!-- Columna Izquierda: Los 16 Artículos + Detalle del Canal -->
                <div class="cc-content">
                    <ol class="cc-art-list">
                        @foreach ($articulos as $i => $art)
                            <li class="cc-art-item {{ $i === 4 ? 'is-key' : '' }}" id="art-{{ $i + 1 }}">
                                <span class="cc-art-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="cc-art-title">{{ $art[0] }}</h3>
                            </li>
                        @endforeach
                    </ol>

                    <section class="cc-report" id="canal-denuncias">
                        <h3>¿Viste una conducta indebida?</h3>

                        <table class="cc-table">
                            <thead>
                                <tr><th>Canal</th><th>Contacto</th><th>Úsalo para</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Correo de cumplimiento</td>
                                    <td><a href="mailto:acuerdos.marco@kenya.com.pe">acuerdos.marco@kenya.com.pe</a></td>
                                    <td>Denuncias y consultas sobre ética e integridad</td>
                                </tr>
                                <tr>
                                    <td>Central telefónica</td>
                                    <td><a href="tel:+51958021778">958 021 778</a></td>
                                    <td>Orientación previa antes de presentar un reporte</td>
                                </tr>
                                <tr>
                                    <td>Soporte</td>
                                    <td><a href="mailto:soporte@kenya.com.pe">soporte@kenya.com.pe</a></td>
                                    <td>Consultas generales sobre este documento</td>
                                </tr>
                            </tbody>
                        </table>

                        <a class="cc-btn" href="mailto:acuerdos.marco@kenya.com.pe?subject=Reporte%20confidencial%20-%20C%C3%B3digo%20de%20Conducta">Enviar un reporte confidencial</a>
                        <p class="cc-fine">Indica, si puedes, fecha, lugar y personas involucradas. No es obligatorio identificarte.</p>
                    </section>
                </div>

                <!-- Columna Derecha: Sidebar Lateral Fija con la Imagen ISO 37001 Prominente -->
                <aside class="cc-sidebar">
                    <figure class="cc-iso-card">
                        <img src="{{ asset('iso-37001.png') }}" alt="Sistema de Gestión Antisoborno ISO 37001:2025 de KENYA TECHNOLOGY" loading="lazy">
                        <figcaption>
                            <strong>Acreditación ISO 37001:2025</strong>
                            <span>Sistema de Gestión Antisoborno</span>
                        </figcaption>
                    </figure>

                    <div class="cc-sidebar-box">
                        <p class="cc-sidebar-box-title">Línea Ética y Confidencial</p>
                        <p class="cc-sidebar-box-desc">
                            Canal directo para reportar posibles incumplimientos con reserva de identidad garantizada.
                        </p>
                        <div class="cc-sidebar-contacts">
                            <a href="mailto:acuerdos.marco@kenya.com.pe"><i class="fa-solid fa-envelope"></i> acuerdos.marco@kenya.com.pe</a>
                            <a href="tel:+51958021778"><i class="fa-solid fa-phone"></i> 958 021 778</a>
                        </div>
                        <a class="cc-btn cc-btn-sm" href="mailto:acuerdos.marco@kenya.com.pe?subject=Reporte%20confidencial%20-%20C%C3%B3digo%20de%20Conducta">Enviar reporte confidencial</a>
                    </div>
                </aside>
            </div>
        </main>
    </div>
@endsection
