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
        #rc-page {
            --rc-green: #166534;
            --rc-green-mid: #15803d;
            --rc-navy: #1b2633;
            --rc-accent: #f26522;
            --rc-ink: #111827;
            --rc-text: #374151;
            --rc-muted: #6b7280;
            --rc-rule: #e5e7eb;
            --rc-paper: #f7f7f5;
            background: #fff;
            color: var(--rc-text);
            line-height: 1.7;
        }

        /* Hero (patrón común del sitio) */
        #rc-page .rc-hero {
            background-image: linear-gradient(rgba(255,255,255,.7), rgba(255,255,255,.7)), url('{{ asset("banersomos.png?v=2") }}');
            background-size: cover;
            background-position: right;
            padding: 64px 15px;
            border-bottom: 1px solid var(--rc-rule);
        }
        #rc-page .rc-hero-inner { max-width: 1200px; margin: 0 auto; padding: 0 10px; }
        #rc-page .rc-hero h1 {
            margin: 0 0 6px;
            font-size: 2.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--rc-ink);
            line-height: 1.15;
        }
        #rc-page .rc-hero p { margin: 0; font-size: 1.1rem; color: var(--rc-muted); }

        #rc-page .rc-wrap { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        #rc-page .rc-crumbs { font-size: .85rem; color: var(--rc-muted); padding: 28px 0 44px; }
        #rc-page .rc-crumbs a { color: var(--rc-muted); text-decoration: none; }
        #rc-page .rc-crumbs a:hover { color: var(--rc-green-mid); }
        #rc-page .rc-crumbs span { margin: 0 6px; color: #d1d5db; }

        #rc-page .rc-kicker {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--rc-green-mid);
            margin-bottom: 14px;
        }
        #rc-page .rc-h2 {
            margin: 0 0 18px;
            font-size: 2.1rem;
            line-height: 1.18;
            font-weight: 800;
            color: var(--rc-ink);
            letter-spacing: -.02em;
        }

        /* 1. Introducción */
        #rc-page .rc-intro {
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(0, .95fr);
            gap: 72px;
            align-items: start;
            padding-bottom: 80px;
        }
        #rc-page .rc-intro .rc-h2 { font-size: 2.4rem; max-width: 20ch; }
        #rc-page .rc-intro p { margin: 0 0 18px; font-size: 1.05rem; max-width: 60ch; }
        #rc-page .rc-quote {
            margin: 30px 0;
            padding: 0 0 0 22px;
            border-left: 3px solid var(--rc-green-mid);
        }
        #rc-page .rc-quote p { margin: 0 0 6px !important; font-size: 1.45rem !important; line-height: 1.35; font-weight: 700; color: var(--rc-ink); }
        #rc-page .rc-quote cite { font-style: normal; font-size: .95rem; color: var(--rc-muted); }
        #rc-page .rc-intro figure { margin: 0; }
        #rc-page .rc-intro img { display: block; width: 100%; height: auto; aspect-ratio: 4 / 5; object-fit: cover; }
        #rc-page .rc-intro figcaption { margin-top: 10px; font-size: .82rem; color: var(--rc-muted); }

        /* 2. Banner: Reciclar de manera responsable */
        #rc-page .rc-banner {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(0, 0.95fr);
            gap: 48px;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-top: 4px solid var(--rc-green-mid);
            border-radius: 8px;
            padding: 44px 48px;
            margin-bottom: 96px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }
        #rc-page .rc-banner-media {
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
            aspect-ratio: 4 / 3;
            width: 100%;
            background: #e2e8f0;
        }
        #rc-page .rc-banner-media img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #rc-page .rc-banner-body { padding: 0; }
        #rc-page .rc-banner .rc-kicker { color: var(--rc-green-mid); margin-bottom: 10px; }
        #rc-page .rc-banner h2 {
            margin: 0 0 14px;
            font-size: 2.1rem;
            line-height: 1.2;
            font-weight: 800;
            color: var(--rc-ink);
            letter-spacing: -.02em;
        }
        #rc-page .rc-banner p {
            margin: 0 0 20px;
            font-size: 1.05rem;
            line-height: 1.65;
            max-width: 50ch;
            color: #475569;
        }
        #rc-page .rc-banner-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 16px;
            margin: 0 0 26px;
            padding: 0;
            list-style: none;
        }
        #rc-page .rc-banner-features li {
            font-size: .88rem;
            font-weight: 600;
            color: #334155;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        #rc-page .rc-banner-features li span {
            color: var(--rc-green-mid);
            font-weight: 700;
        }
        #rc-page .rc-banner-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        #rc-page .rc-btn {
            display: inline-block;
            background: var(--rc-accent);
            color: #fff;
            padding: 13px 28px;
            font-weight: 700;
            font-size: .95rem;
            text-decoration: none;
            border-radius: 4px;
            transition: background .15s ease, transform .15s ease;
        }
        #rc-page .rc-btn:hover { background: #d9541a; color: #fff; transform: translateY(-1px); }
        #rc-page .rc-banner-tel {
            color: #475569;
            font-size: .95rem;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid #cbd5e1;
            transition: color .15s ease, border-color .15s ease;
        }
        #rc-page .rc-banner-tel:hover { color: var(--rc-ink); border-color: var(--rc-ink); }

        /* 3. Cómo funciona */
        #rc-page .rc-steps-head { display: flex; justify-content: space-between; align-items: end; gap: 40px; margin-bottom: 36px; }
        #rc-page .rc-steps-head p { margin: 0; max-width: 46ch; color: var(--rc-muted); }
        #rc-page .rc-steps {
            list-style: none;
            margin: 0 0 96px;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            counter-reset: paso;
        }
        #rc-page .rc-steps li { border-top: 2px solid var(--rc-ink); padding-top: 18px; counter-increment: paso; }
        #rc-page .rc-steps li::before {
            content: counter(paso, decimal-leading-zero);
            display: block;
            font-size: 2.2rem;
            font-weight: 300;
            line-height: 1;
            color: var(--rc-green-mid);
            margin-bottom: 14px;
            font-variant-numeric: tabular-nums;
        }
        #rc-page .rc-steps h3 { margin: 0 0 6px; font-size: 1.05rem; font-weight: 700; color: var(--rc-ink); }
        #rc-page .rc-steps p { margin: 0; font-size: .94rem; }

        /* 4. Políticas y Sistema RAEE */
        #rc-page .rc-docs-band { background: var(--rc-paper); padding: 80px 0; margin-bottom: 96px; }
        #rc-page .rc-docs { display: grid; grid-template-columns: 1fr 1fr; }
        #rc-page .rc-doc { padding: 0 56px 0 0; }
        #rc-page .rc-doc + .rc-doc { padding: 0 0 0 56px; border-left: 1px solid #d6d3d1; }
        #rc-page .rc-doc h3 { margin: 0 0 6px; font-size: 1.55rem; font-weight: 800; color: var(--rc-ink); letter-spacing: -.01em; }
        #rc-page .rc-doc-sub { margin: 0 0 22px; color: var(--rc-muted); }
        #rc-page .rc-doc ol { margin: 0 0 24px; padding: 0; list-style: none; counter-reset: punto; }
        #rc-page .rc-doc ol li {
            position: relative;
            padding: 12px 0 12px 36px;
            border-top: 1px solid #e7e5e4;
            counter-increment: punto;
            font-size: .96rem;
        }
        #rc-page .rc-doc ol li::before {
            content: counter(punto);
            position: absolute;
            left: 0;
            top: 12px;
            font-weight: 700;
            color: var(--rc-green-mid);
            font-variant-numeric: tabular-nums;
        }
        #rc-page .rc-doc ol li strong { color: var(--rc-ink); }

        #rc-page .rc-doc details summary {
            display: inline-block;
            cursor: pointer;
            list-style: none;
            font-weight: 700;
            color: var(--rc-ink);
            padding-bottom: 2px;
            border-bottom: 2px solid var(--rc-green-mid);
        }
        #rc-page .rc-doc details summary::-webkit-details-marker { display: none; }
        #rc-page .rc-doc details summary::after { content: ' +'; color: var(--rc-green-mid); }
        #rc-page .rc-doc details[open] summary::after { content: ' −'; }
        #rc-page .rc-doc details[open] summary .rc-open-label { display: none; }
        #rc-page .rc-doc details summary .rc-close-label { display: none; }
        #rc-page .rc-doc details[open] summary .rc-close-label { display: inline; }
        #rc-page .rc-doc-more { margin-top: 18px; padding: 20px 22px; background: #fff; border: 1px solid #e7e5e4; font-size: .93rem; }
        #rc-page .rc-doc-more p { margin: 0 0 10px; }
        #rc-page .rc-doc-more p:last-child { margin: 0; }

        /* 5. Preguntas frecuentes + acopio */
        #rc-page .rc-help { display: grid; grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr); gap: 72px; padding-bottom: 96px; }
        #rc-page .rc-faq details { border-bottom: 1px solid var(--rc-rule); }
        #rc-page .rc-faq details:first-of-type { border-top: 1px solid var(--rc-ink); }
        #rc-page .rc-faq summary {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 18px 0;
            cursor: pointer;
            list-style: none;
            font-weight: 600;
            color: var(--rc-ink);
        }
        #rc-page .rc-faq summary::-webkit-details-marker { display: none; }
        #rc-page .rc-faq summary::after { content: '+'; font-weight: 400; font-size: 1.3rem; line-height: 1; color: var(--rc-muted); }
        #rc-page .rc-faq details[open] summary::after { content: '−'; color: var(--rc-green-mid); }
        #rc-page .rc-faq details p { margin: 0 0 20px; max-width: 62ch; }

        #rc-page .rc-points {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-top: 3px solid var(--rc-accent);
            border-radius: 8px;
            padding: 28px 24px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        }
        #rc-page .rc-points h3 {
            margin: 0 0 18px;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--rc-ink);
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        #rc-page .rc-points dl { margin: 0 0 24px; }
        #rc-page .rc-points dt {
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 3px;
        }
        #rc-page .rc-points dd {
            margin: 0 0 16px;
            color: #1e293b;
            font-size: .95rem;
            line-height: 1.45;
        }
        #rc-page .rc-points a {
            color: #1e293b;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid #cbd5e1;
            transition: color .15s ease, border-color .15s ease;
        }
        #rc-page .rc-points a:hover {
            color: var(--rc-accent);
            border-color: var(--rc-accent);
        }
        #rc-page .rc-btn-dark {
            display: block;
            width: 100%;
            text-align: center;
            background: var(--rc-accent);
            color: #fff;
            border: 1px solid var(--rc-accent);
            border-radius: 4px;
            padding: 13px 20px;
            font-size: .92rem;
            font-weight: 700;
            letter-spacing: .01em;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(242, 101, 34, 0.2);
            transition: background .15s ease, border-color .15s ease, transform .15s ease;
        }
        #rc-page .rc-btn-dark:hover {
            background: #d9541a;
            border-color: #d9541a;
            color: #fff;
            transform: translateY(-1px);
        }

        @media (max-width: 991px) {
            #rc-page .rc-hero h1 { font-size: 2rem; }
            #rc-page .rc-intro, #rc-page .rc-help { grid-template-columns: 1fr; gap: 40px; }
            #rc-page .rc-intro .rc-h2 { font-size: 1.9rem; }
            #rc-page .rc-intro img { aspect-ratio: 16 / 10; }
            #rc-page .rc-banner { grid-template-columns: 1fr; padding: 32px 24px; gap: 32px; }
            #rc-page .rc-banner-media { aspect-ratio: 16 / 10; max-height: 320px; }
            #rc-page .rc-banner-body { padding: 0; }
            #rc-page .rc-steps-head { display: block; }
            #rc-page .rc-steps-head p { margin-top: 8px; }
            #rc-page .rc-steps { grid-template-columns: 1fr 1fr; gap: 28px 24px; }
            #rc-page .rc-docs { grid-template-columns: 1fr; }
            #rc-page .rc-doc, #rc-page .rc-doc + .rc-doc { padding: 0; border: 0; }
            #rc-page .rc-doc + .rc-doc { margin-top: 56px; padding-top: 40px; border-top: 1px solid #d6d3d1; }
        }
        @media (max-width: 575px) {
            #rc-page .rc-steps { grid-template-columns: 1fr; }
        }
    </style>

    <div id="rc-page">
        <section class="rc-hero">
            <div class="rc-hero-inner">
                <h1>Reciclaje Tecnológico</h1>
                <p>Compromiso con la economía circular y la preservación de nuestros recursos naturales</p>
            </div>
        </section>

        <div class="rc-wrap">
            <nav class="rc-crumbs" aria-label="Ruta de navegación">
                <a href="{{ url('/') }}">Inicio</a><span>/</span>Nuestra Empresa<span>/</span><strong>Reciclaje</strong>
            </nav>

            <!-- Introducción -->
            <section class="rc-intro">
                <div>
                    <span class="rc-kicker">Nuestra Empresa · Reciclaje</span>
                    <h2 class="rc-h2">Reciclaje responsable de la tecnología, construyamos un futuro sostenible</h2>
                    <p>
                        En el Perú, la tecnología forma parte cada vez más de nuestras actividades diarias. Computadoras, laptops, celulares, impresoras, servidores y otros equipos son indispensables para empresas, instituciones y hogares.
                    </p>
                    <blockquote class="rc-quote">
                        <p>¿Qué hacemos con ellos cuando dejan de utilizarse?</p>
                        <cite>Nuestra empresa busca convertir este desafío en una oportunidad de valor ambiental y social.</cite>
                    </blockquote>
                    <p>
                        A través de un modelo de <strong>recuperación y reciclaje responsable de equipos tecnológicos</strong>, ayudamos a empresas e instituciones a gestionar adecuadamente los equipos que ya no necesitan, evitando que terminen junto con los residuos comunes y promoviendo el aprovechamiento de los materiales y componentes que todavía pueden tener valor.
                    </p>
                </div>
                <figure>
                    <img src="{{ asset('bosque-reciclaje.jpg') }}" alt="Familia caminando en un bosque, símbolo del compromiso ambiental de KENYA Technology" loading="lazy">
                    <figcaption>Cada equipo bien reciclado reduce la extracción de materias primas y protege suelos y fuentes de agua.</figcaption>
                </figure>
            </section>
        </div>

        <!-- Banner: Reciclar de manera responsable -->
        <div class="rc-wrap">
            <section class="rc-banner" id="reciclar">
                <div class="rc-banner-body">
                    <span class="rc-kicker">Servicio institucional gratuito</span>
                    <h2>Reciclar de manera responsable</h2>
                    <p>
                        Recicla tu equipo de TI que ya no usas, de cualquier marca y en cualquier estado, de manera responsable y sin costo con KENYA TECHNOLOGY.
                    </p>
                    <ul class="rc-banner-features">
                        <li><span>✓</span> Recepción multimarca y cualquier estado</li>
                        <li><span>✓</span> 100% gratuito sin costo oculto</li>
                        <li><span>✓</span> Constancia oficial de disposición RAEE</li>
                    </ul>
                    <div class="rc-banner-actions">
                        <a class="rc-btn" href="mailto:acuerdos.marco@kenya.com.pe?subject=Solicitud%20de%20reciclaje%20de%20equipos">Comenzar</a>
                        <a class="rc-banner-tel" href="tel:+51958021778">o llama al 958 021 778</a>
                    </div>
                </div>
                <div class="rc-banner-media">
                    <img src="https://www.kenya.com.pe/reciclaje-equipo-box.jpg" alt="Técnico empacando equipos informáticos en desuso para su reciclaje" loading="lazy">
                </div>
            </section>

            <!-- Cómo funciona -->
            <div class="rc-steps-head">
                <div>
                    <span class="rc-kicker">Paso a paso</span>
                    <h2 class="rc-h2" style="margin:0;">Cómo entregar tus equipos</h2>
                </div>
                <p>Un proceso corto y documentado, pensado tanto para personas como para entidades públicas y empresas.</p>
            </div>
            <ol class="rc-steps">
                <li>
                    <h3>Solicitud</h3>
                    <p>Escríbenos indicando qué equipos tienes y en qué cantidad. Te respondemos con las indicaciones.</p>
                </li>
                <li>
                    <h3>Entrega o recojo</h3>
                    <p>Llévalos a nuestros puntos de acopio en Huánuco o Lima. Para lotes institucionales coordinamos el recojo.</p>
                </li>
                <li>
                    <h3>Tratamiento</h3>
                    <p>Borramos la información, separamos componentes y derivamos cada material a su proceso de valorización.</p>
                </li>
                <li>
                    <h3>Constancia</h3>
                    <p>Emitimos la constancia de disposición de RAEE, útil para auditorías y para la baja de bienes del Estado.</p>
                </li>
            </ol>
        </div>

        <!-- Políticas y Sistema RAEE -->
        <section class="rc-docs-band">
            <div class="rc-wrap rc-docs">
                <article class="rc-doc" id="politicas">
                    <span class="rc-kicker">Política</span>
                    <h3>Nuestras políticas sobre reciclaje</h3>
                    <p class="rc-doc-sub">Nuestras políticas y posturas sobre el reciclaje.</p>
                    <ol>
                        <li><strong>Cero vertederos comunes.</strong> Todo lo recibido se clasifica y se canaliza para su valorización.</li>
                        <li><strong>Manejo seguro de sustancias peligrosas.</strong> Baterías, condensadores y piezas con plomo o mercurio se aíslan del resto.</li>
                        <li><strong>Privacidad de tu información.</strong> Los discos y unidades de almacenamiento se borran o destruyen antes de cualquier proceso.</li>
                        <li><strong>Economía circular.</strong> Metales, plásticos y tarjetas electrónicas vuelven a cadenas productivas.</li>
                    </ol>
                    <details>
                        <summary><span class="rc-open-label">Lectura</span><span class="rc-close-label">Cerrar</span></summary>
                        <div class="rc-doc-more">
                            <p><strong>Recepción.</strong> Registramos cada equipo por número de serie y peso en el punto de acopio.</p>
                            <p><strong>Desensamble.</strong> Separamos chasis, fuentes, placas, cableado y unidades de almacenamiento.</p>
                            <p><strong>Cierre.</strong> Documentamos el destino final y entregamos la constancia correspondiente.</p>
                        </div>
                    </details>
                </article>

                <article class="rc-doc" id="sistema-raee">
                    <span class="rc-kicker">Normativa</span>
                    <h3>Sistema RAEE</h3>
                    <p class="rc-doc-sub">Regulaciones de WEEE aplicadas en el Perú.</p>
                    <ol>
                        <li><strong>D.S. N° 009-2019-MINAM.</strong> Régimen especial de gestión y manejo de Residuos de Aparatos Eléctricos y Electrónicos.</li>
                        <li><strong>Baja de bienes estatales.</strong> Procedimiento para entidades públicas que renuevan su parque informático.</li>
                        <li><strong>Constancia de disposición.</strong> Respaldo documental ante auditorías y fiscalización ambiental.</li>
                        <li><strong>Categoría de equipos.</strong> Computadoras, laptops, servidores, monitores, impresoras y periféricos.</li>
                    </ol>
                    <details>
                        <summary><span class="rc-open-label">Acceso</span><span class="rc-close-label">Cerrar</span></summary>
                        <div class="rc-doc-more">
                            <p><strong>¿Quiénes deben cumplirla?</strong> Ministerios, gobiernos regionales, municipalidades y empresas privadas que desechan equipos.</p>
                            <p><strong>¿Qué es RAEE / WEEE?</strong> Son las siglas en español e inglés de los residuos de aparatos eléctricos y electrónicos.</p>
                            <p><strong>¿Te ayudamos con el trámite?</strong> Sí, te orientamos con la documentación técnica para la baja patrimonial.</p>
                        </div>
                    </details>
                </article>
            </div>
        </section>

        <!-- Preguntas frecuentes y puntos de acopio -->
        <div class="rc-wrap">
            <section class="rc-help">
                <div class="rc-faq">
                    <span class="rc-kicker">Dudas comunes</span>
                    <h2 class="rc-h2">Preguntas frecuentes</h2>
                    <details>
                        <summary>¿Tiene algún costo reciclar mis equipos?</summary>
                        <p>No. La recepción y el reciclaje de equipos de TI son gratuitos.</p>
                    </details>
                    <details>
                        <summary>¿Solo reciben equipos de la marca KENYA?</summary>
                        <p>No. Recibimos equipos de cualquier marca y en cualquier estado, funcionen o no.</p>
                    </details>
                    <details>
                        <summary>¿Qué pasa con la información de mis discos?</summary>
                        <p>Las unidades de almacenamiento se borran o se destruyen antes del desensamble, para que nadie pueda recuperar tus datos.</p>
                    </details>
                    <details>
                        <summary>Soy una entidad pública, ¿me sirve para dar de baja bienes?</summary>
                        <p>Sí. Emitimos la constancia de disposición de RAEE que respalda el proceso de baja y las auditorías ambientales.</p>
                    </details>
                    <details>
                        <summary>Tengo muchos equipos, ¿pueden recogerlos?</summary>
                        <p>Sí. Para lotes de empresas e instituciones coordinamos el recojo. Escríbenos indicando cantidad y ubicación.</p>
                    </details>
                </div>

                <aside class="rc-points">
                    <h3>Puntos de acopio</h3>
                    <dl>
                        <dt>Huánuco</dt>
                        <dd>Jr. Crespo y Castillo 480</dd>
                        <dt>Lima</dt>
                        <dd>San Isidro — atención a entidades públicas y privadas</dd>
                        <dt>Correo</dt>
                        <dd><a href="mailto:acuerdos.marco@kenya.com.pe">acuerdos.marco@kenya.com.pe</a></dd>
                        <dt>Teléfono</dt>
                        <dd><a href="tel:+51958021778">958 021 778</a></dd>
                    </dl>
                    <a class="rc-btn rc-btn-dark" href="mailto:soporte@kenya.com.pe?subject=Solicitud%20de%20constancia%20RAEE">Solicitar constancia RAEE</a>
                </aside>
            </section>
        </div>
    </div>
@endsection
