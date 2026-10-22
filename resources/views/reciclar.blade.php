@extends('layouts.landing')

@section('title', 'Iniciar Reciclaje Tecnológico | KENYA Technology')
@section('meta_description', 'Coordinación express para la entrega o recojo de equipos de cómputo en desuso en Huánuco y Lima. Servicio gratuito para personas y gestión RAEE oficial con constancia para empresas.')
@section('meta_keywords', 'entregar computadoras reciclaje peru, reciclar laptops huanuco, punto de acopio raee lima, constancia raee minam')
@section('canonical', route('reciclar'))
@section('og_title', 'Iniciar Reciclaje Tecnológico | KENYA Technology')
@section('og_description', 'Coordina en 2 simples pasos la entrega de tus equipos informáticos en desuso. Servicio gratuito para personas y constancia RAEE para empresas.')



@section('content')
    <style>
        #reciclar-app {
            --ra-green: #15803d;
            --ra-green-dark: #166534;
            --ra-accent: #f26522;
            --ra-accent-dark: #d9541a;
            --ra-ink: #0f172a;
            --ra-text: #334155;
            --ra-muted: #64748b;
            --ra-border: #e2e8f0;
            --ra-bg-alt: #f8fafc;
            background: #ffffff;
            color: var(--ra-text);
            line-height: 1.6;
        }

        /* Hero */
        #reciclar-app .ra-hero {
            background-image: linear-gradient(rgba(255,255,255,.75), rgba(255,255,255,.75)), url('{{ asset("banersomos.png?v=2") }}');
            background-size: cover;
            background-position: right center;
            padding: 56px 15px;
            border-bottom: 1px solid var(--ra-border);
        }
        #reciclar-app .ra-container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 20px;
        }
        #reciclar-app .ra-hero-title {
            margin: 0 0 8px;
            font-size: 2.3rem;
            font-weight: 800;
            color: var(--ra-ink);
            letter-spacing: -.02em;
            text-transform: uppercase;
        }
        #reciclar-app .ra-hero-subtitle {
            margin: 0;
            font-size: 1.05rem;
            color: var(--ra-muted);
            max-width: 65ch;
        }

        /* Breadcrumbs */
        #reciclar-app .ra-crumbs {
            font-size: .84rem;
            color: var(--ra-muted);
            padding: 24px 0 28px;
        }
        #reciclar-app .ra-crumbs a {
            color: var(--ra-muted);
            text-decoration: none;
        }
        #reciclar-app .ra-crumbs a:hover {
            color: var(--ra-green);
        }
        #reciclar-app .ra-crumbs span {
            margin: 0 8px;
            color: #cbd5e1;
        }

        /* Contenedor del módulo */
        #reciclar-app .ra-card-main {
            background: #ffffff;
            border: 1px solid var(--ra-border);
            border-radius: 12px;
            padding: 44px 40px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.06);
            margin-bottom: 48px;
        }

        #reciclar-app .ra-header {
            text-align: center;
            margin-bottom: 36px;
        }
        #reciclar-app .ra-kicker {
            display: inline-block;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--ra-green);
            margin-bottom: 10px;
        }
        #reciclar-app .ra-h2 {
            margin: 0 0 10px;
            font-size: 2rem;
            font-weight: 800;
            color: var(--ra-ink);
            letter-spacing: -.02em;
        }
        #reciclar-app .ra-lead {
            margin: 0 auto;
            max-width: 62ch;
            color: var(--ra-muted);
            font-size: 1rem;
        }

        /* Tabs de selección de perfil */
        #reciclar-app .ra-tabs-wrap {
            display: flex;
            justify-content: center;
            margin-top: 24px;
        }
        #reciclar-app .ra-tabs {
            display: inline-flex;
            background: #f1f5f9;
            padding: 5px;
            border-radius: 10px;
            gap: 6px;
            max-width: 100%;
        }
        #reciclar-app .ra-tab {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            color: #475569;
            transition: all .15s ease;
            text-align: left;
        }
        #reciclar-app .ra-tab i {
            font-size: 1.3rem;
            color: #64748b;
            transition: color .15s ease;
        }
        #reciclar-app .ra-tab-content {
            display: flex;
            flex-direction: column;
        }
        #reciclar-app .ra-tab-title {
            font-size: .95rem;
            font-weight: 700;
            line-height: 1.25;
            color: #1e293b;
        }
        #reciclar-app .ra-tab-subtitle {
            font-size: .78rem;
            color: #64748b;
        }
        #reciclar-app .ra-tab.active {
            background: #ffffff;
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        }
        #reciclar-app .ra-tab.active i {
            color: var(--ra-accent);
        }
        #reciclar-app .ra-tab.active .ra-tab-title {
            color: var(--ra-ink);
        }

        /* Pasos del flujo */
        #reciclar-app .ra-step-block {
            margin-bottom: 34px;
        }
        #reciclar-app .ra-step-label {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--ra-ink);
        }
        #reciclar-app .ra-step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--ra-ink);
            color: #ffffff;
            font-size: .84rem;
            font-weight: 700;
        }

        /* Grid de Dispositivos */
        #reciclar-app .ra-device-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        #reciclar-app .ra-dev-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 24px 18px;
            background: var(--ra-bg-alt);
            border: 2px solid var(--ra-border);
            border-radius: 10px;
            cursor: pointer;
            transition: all .15s ease;
            font-family: inherit;
        }
        #reciclar-app .ra-dev-card:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
            transform: translateY(-2px);
        }
        #reciclar-app .ra-dev-card.active {
            background: #ffffff;
            border-color: var(--ra-accent);
            box-shadow: 0 4px 14px rgba(242, 101, 34, 0.15);
        }
        #reciclar-app .ra-dev-card i {
            font-size: 2.1rem;
            color: #64748b;
            margin-bottom: 12px;
            transition: color .15s ease;
        }
        #reciclar-app .ra-dev-card.active i {
            color: var(--ra-accent);
        }
        #reciclar-app .ra-dev-card strong {
            font-size: .95rem;
            color: var(--ra-ink);
            margin-bottom: 4px;
        }
        #reciclar-app .ra-dev-card span {
            font-size: .78rem;
            color: var(--ra-muted);
            line-height: 1.35;
        }

        /* Grid de Sedes */
        #reciclar-app .ra-location-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        #reciclar-app .ra-loc-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px 20px;
            background: var(--ra-bg-alt);
            border: 2px solid var(--ra-border);
            border-radius: 10px;
            cursor: pointer;
            text-align: left;
            transition: all .15s ease;
            font-family: inherit;
        }
        #reciclar-app .ra-loc-card:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
        }
        #reciclar-app .ra-loc-card.active {
            background: #ffffff;
            border-color: var(--ra-green);
            box-shadow: 0 4px 14px rgba(21, 128, 61, 0.12);
        }
        #reciclar-app .ra-loc-card i {
            font-size: 1.35rem;
            color: #64748b;
            margin-top: 2px;
        }
        #reciclar-app .ra-loc-card.active i {
            color: var(--ra-green);
        }
        #reciclar-app .ra-loc-card strong {
            display: block;
            font-size: .95rem;
            color: var(--ra-ink);
            margin-bottom: 2px;
        }
        #reciclar-app .ra-loc-card span {
            font-size: .8rem;
            color: var(--ra-muted);
            line-height: 1.35;
        }

        /* Acción WhatsApp */
        #reciclar-app .ra-action-box {
            margin-top: 36px;
            padding-top: 30px;
            border-top: 1px solid var(--ra-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        #reciclar-app .ra-summary-preview {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: var(--ra-green-dark);
            padding: 9px 18px;
            border-radius: 6px;
            font-size: .92rem;
            margin-bottom: 20px;
        }
        #reciclar-app .ra-cta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: var(--ra-green);
            color: #ffffff;
            font-size: 1.05rem;
            font-weight: 700;
            padding: 16px 36px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(21, 128, 61, 0.25);
            transition: all .15s ease;
            font-family: inherit;
        }
        #reciclar-app .ra-cta-btn:hover {
            background: var(--ra-green-dark);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(21, 128, 61, 0.35);
        }
        #reciclar-app .ra-cta-btn i {
            font-size: 1.4rem;
        }
        #reciclar-app .ra-hint {
            margin: 14px 0 0;
            font-size: .84rem;
            color: var(--ra-muted);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* B2B / Empresas */
        #reciclar-app .ra-b2b-intro {
            background: var(--ra-bg-alt);
            border: 1px solid var(--ra-border);
            border-left: 4px solid var(--ra-accent);
            padding: 22px 26px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        #reciclar-app .ra-b2b-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: var(--ra-ink);
            font-size: .94rem;
            margin-bottom: 6px;
        }
        #reciclar-app .ra-b2b-badge i {
            color: var(--ra-accent);
        }
        #reciclar-app .ra-b2b-intro p {
            margin: 0;
            font-size: .93rem;
            color: #475569;
            line-height: 1.55;
        }
        #reciclar-app .ra-form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 24px;
        }
        #reciclar-app .ra-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            text-align: left;
        }
        #reciclar-app .ra-field label {
            font-size: .85rem;
            font-weight: 700;
            color: var(--ra-ink);
        }
        #reciclar-app .ra-field input,
        #reciclar-app .ra-field select {
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: .95rem;
            font-family: inherit;
            color: var(--ra-ink);
            background: #ffffff;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        #reciclar-app .ra-field input:focus,
        #reciclar-app .ra-field select:focus {
            outline: none;
            border-color: var(--ra-accent);
            box-shadow: 0 0 0 3px rgba(242, 101, 34, 0.15);
        }
        #reciclar-app .ra-or-mail {
            margin-top: 14px;
            font-size: .88rem;
            color: var(--ra-muted);
        }
        #reciclar-app .ra-or-mail a {
            color: var(--ra-ink);
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 3px;
        }
        #reciclar-app .ra-or-mail a:hover {
            color: var(--ra-accent);
        }

        /* Bloque de confianza y sedes */
        #reciclar-app .ra-info-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 48px;
        }
        #reciclar-app .ra-info-card {
            background: #f8fafc;
            border: 1px solid var(--ra-border);
            border-radius: 10px;
            padding: 24px;
        }
        #reciclar-app .ra-info-card h4 {
            margin: 0 0 8px;
            font-size: 1rem;
            font-weight: 700;
            color: var(--ra-ink);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        #reciclar-app .ra-info-card h4 i {
            color: var(--ra-green);
        }
        #reciclar-app .ra-info-card p {
            margin: 0;
            font-size: .88rem;
            color: #475569;
            line-height: 1.5;
        }

        /* Banner de retorno institucional */
        #reciclar-app .ra-return-banner {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 24px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 72px;
        }
        #reciclar-app .ra-return-banner p {
            margin: 0;
            font-size: .92rem;
            color: #334155;
        }
        #reciclar-app .ra-return-banner a {
            color: var(--ra-ink);
            font-weight: 700;
            font-size: .9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            white-space: nowrap;
            transition: all .15s ease;
        }
        #reciclar-app .ra-return-banner a:hover {
            border-color: var(--ra-green);
            color: var(--ra-green);
        }

        @media (max-width: 991px) {
            #reciclar-app .ra-hero-title { font-size: 1.8rem; }
            #reciclar-app .ra-card-main { padding: 32px 20px; }
            #reciclar-app .ra-device-grid { grid-template-columns: repeat(2, 1fr); }
            #reciclar-app .ra-location-grid { grid-template-columns: 1fr; }
            #reciclar-app .ra-form-grid { grid-template-columns: 1fr; }
            #reciclar-app .ra-tabs { flex-direction: column; width: 100%; }
            #reciclar-app .ra-info-strip { grid-template-columns: 1fr; }
            #reciclar-app .ra-return-banner { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 575px) {
            #reciclar-app .ra-device-grid { grid-template-columns: 1fr; }
            #reciclar-app .ra-cta-btn { width: 100%; padding: 14px 20px; font-size: .95rem; }
        }
    </style>

    <div id="reciclar-app">
        <!-- Hero -->
        <section class="ra-hero">
            <div class="ra-container">
                <h1 class="ra-hero-title">Entrega y Reciclaje de Equipos</h1>
                <p class="ra-hero-subtitle">Gestión responsable de RAEE. Servicio 100% gratuito para personas e instituciones con fines ambientales.</p>
            </div>
        </section>

        <div class="ra-container">
            <!-- Breadcrumbs -->
            <nav class="ra-crumbs" aria-label="Ruta de navegación">
                <a href="{{ url('/') }}">Inicio</a><span>/</span>
                <a href="{{ route('reciclaje') }}">Reciclaje Tecnológico</a><span>/</span>
                <strong>Iniciar Entrega</strong>
            </nav>

            <!-- Card Principal de Acción -->
            <div class="ra-card-main">
                <div class="ra-header">
                    <span class="ra-kicker">Autoservicio express</span>
                    <h2 class="ra-h2">¿Qué deseas reciclar hoy?</h2>
                    <p class="ra-lead">Elige tu perfil para coordinar la entrega en tienda o programar el recojo en menos de 1 minuto, sin registros complicados.</p>

                    <div class="ra-tabs-wrap">
                        <div class="ra-tabs" role="tablist">
                            <button type="button" class="ra-tab active" id="tab-btn-persona" onclick="switchRecycleTab('persona')">
                                <i class="fa-solid fa-user"></i>
                                <div class="ra-tab-content">
                                    <span class="ra-tab-title">Personas y Hogar</span>
                                    <span class="ra-tab-subtitle">1 a 3 equipos · Gratuito</span>
                                </div>
                            </button>
                            <button type="button" class="ra-tab" id="tab-btn-empresa" onclick="switchRecycleTab('empresa')">
                                <i class="fa-solid fa-building"></i>
                                <div class="ra-tab-content">
                                    <span class="ra-tab-title">Empresas e Instituciones</span>
                                    <span class="ra-tab-subtitle">Lotes · Con Constancia RAEE</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Panel 1: Personas y Hogar -->
                <div class="ra-panel" id="panel-persona">
                    <div class="ra-step-block">
                        <label class="ra-step-label">
                            <span class="ra-step-badge">1</span>
                            <span>Selecciona el tipo de equipo:</span>
                        </label>
                        <div class="ra-device-grid">
                            <button type="button" class="ra-dev-card active" data-type="Laptop / Computadora" onclick="selectDevice(this)">
                                <i class="fa-solid fa-laptop"></i>
                                <strong>Laptop / PC</strong>
                                <span>CPUs, portátiles, todo en uno</span>
                            </button>
                            <button type="button" class="ra-dev-card" data-type="Monitor / Pantalla" onclick="selectDevice(this)">
                                <i class="fa-solid fa-desktop"></i>
                                <strong>Monitor / Pantalla</strong>
                                <span>LED, LCD o CRT en desuso</span>
                            </button>
                            <button type="button" class="ra-dev-card" data-type="Celular / Tablet" onclick="selectDevice(this)">
                                <i class="fa-solid fa-mobile-screen"></i>
                                <strong>Celular / Tablet</strong>
                                <span>Móviles, baterías y cargadores</span>
                            </button>
                            <button type="button" class="ra-dev-card" data-type="Impresora / Accesorios" onclick="selectDevice(this)">
                                <i class="fa-solid fa-print"></i>
                                <strong>Impresora / Partes</strong>
                                <span>Teclados, fuentes, cables</span>
                            </button>
                        </div>
                    </div>

                    <div class="ra-step-block">
                        <label class="ra-step-label">
                            <span class="ra-step-badge">2</span>
                            <span>¿Dónde deseas entregarlo?</span>
                        </label>
                        <div class="ra-location-grid">
                            <button type="button" class="ra-loc-card active" data-location="Sede Huánuco (Jr. Crespo y Castillo 480)" onclick="selectLocation(this)">
                                <i class="fa-solid fa-location-dot"></i>
                                <div>
                                    <strong>Sede Huánuco</strong>
                                    <span>Jr. Crespo y Castillo 480 · Lun a Sáb</span>
                                </div>
                            </button>
                            <button type="button" class="ra-loc-card" data-location="Sede Lima (San Isidro)" onclick="selectLocation(this)">
                                <i class="fa-solid fa-location-dot"></i>
                                <div>
                                    <strong>Sede Lima</strong>
                                    <span>San Isidro · Coordinación directa</span>
                                </div>
                            </button>
                            <button type="button" class="ra-loc-card" data-location="Consulta de recojo a domicilio" onclick="selectLocation(this)">
                                <i class="fa-solid fa-truck"></i>
                                <div>
                                    <strong>Consulta de Recojo</strong>
                                    <span>Para casos especiales o cercanos</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div class="ra-action-box">
                        <div class="ra-summary-preview">
                            <i class="fa-solid fa-circle-check"></i>
                            <span id="persona-summary-text">Reciclar <strong>Laptop / Computadora</strong> en <strong>Sede Huánuco</strong></span>
                        </div>
                        <a href="#" id="btn-whatsapp-persona" target="_blank" rel="noopener noreferrer" class="ra-cta-btn">
                            <i class="fa-brands fa-whatsapp"></i>
                            <span>Coordinar entrega por WhatsApp</span>
                        </a>
                        <p class="ra-hint">
                            <i class="fa-solid fa-shield-halved"></i> Servicio 100% gratuito. Borrado seguro de datos antes de la valorización.
                        </p>
                    </div>
                </div>

                <!-- Panel 2: Empresas e Instituciones -->
                <div class="ra-panel" id="panel-empresa" style="display: none;">
                    <div class="ra-b2b-intro">
                        <div class="ra-b2b-badge">
                            <i class="fa-solid fa-file-contract"></i>
                            <span>Baja Patrimonial Oficial · Ley D.S. N° 009-2019-MINAM</span>
                        </div>
                        <p>
                            Para empresas privadas, colegios y entidades públicas. Recogemos en tus instalaciones y emitimos la <strong>Constancia Oficial de Disposición RAEE</strong> con valor probatorio para auditorías ambientales y contables.
                        </p>
                    </div>

                    <form id="form-reciclaje-empresa" onsubmit="submitEmpresaRecycling(event)">
                        <div class="ra-form-grid">
                            <div class="ra-field">
                                <label for="empresa_nombre">Empresa o Institución *</label>
                                <input type="text" id="empresa_nombre" name="empresa" placeholder="Ej. Minera Los Andes SAC / Municipalidad" required>
                            </div>
                            <div class="ra-field">
                                <label for="empresa_ruc">RUC (para la constancia oficial)</label>
                                <input type="text" id="empresa_ruc" name="ruc" maxlength="11" placeholder="Ej. 20601234567">
                            </div>
                            <div class="ra-field">
                                <label for="empresa_cantidad">Volumen estimado de equipos *</label>
                                <select id="empresa_cantidad" name="cantidad" required>
                                    <option value="5 a 15 equipos">5 a 15 equipos (Lote pequeño)</option>
                                    <option value="16 a 50 equipos">16 a 50 equipos (Lote mediano)</option>
                                    <option value="Más de 50 equipos">Más de 50 equipos (Baja patrimonial masiva)</option>
                                </select>
                            </div>
                            <div class="ra-field">
                                <label for="empresa_ciudad">Ciudad / Ubicación *</label>
                                <select id="empresa_ciudad" name="ciudad" required>
                                    <option value="Huánuco y alrededores">Huánuco y alrededores</option>
                                    <option value="Lima Metropolitana">Lima Metropolitana</option>
                                    <option value="Otra región del Perú">Otra región del Perú</option>
                                </select>
                            </div>
                        </div>

                        <div class="ra-action-box">
                            <button type="submit" class="ra-cta-btn">
                                <i class="fa-brands fa-whatsapp"></i>
                                <span>Solicitar Recojo y Constancia RAEE</span>
                            </button>
                            <div class="ra-or-mail">
                                o envía un correo oficial a <a href="mailto:acuerdos.marco@kenya.com.pe?subject=Solicitud%20de%20Recojo%20Institucional%20RAEE">acuerdos.marco@kenya.com.pe</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información de Confianza y Sedes -->
            <div class="ra-info-strip">
                <div class="ra-info-card">
                    <h4><i class="fa-solid fa-store"></i> Punto Huánuco</h4>
                    <p>Jr. Crespo y Castillo 480. Recepción en mostrador de lunes a sábado de 9:00 am a 8:00 pm.</p>
                </div>
                <div class="ra-info-card">
                    <h4><i class="fa-solid fa-building"></i> Sede Lima</h4>
                    <p>San Isidro. Atención institucional a entidades públicas y empresas bajo coordinación directa.</p>
                </div>
                <div class="ra-info-card">
                    <h4><i class="fa-solid fa-headset"></i> Atención Telefónica</h4>
                    <p>Línea directa: <a href="tel:+51958021778" style="color:var(--ra-ink); font-weight:700;">958 021 778</a> · Dudas técnicas sobre lotes y componentes.</p>
                </div>
            </div>

            <!-- Banner hacia la página institucional -->
            <div class="ra-return-banner">
                <div>
                    <p><strong>¿Deseas conocer más sobre nuestras políticas y marco normativo?</strong></p>
                    <p style="color:var(--ra-muted); margin-top:2px;">Revisa las 4 fases de tratamiento, cumplimiento D.S. N° 009-2019-MINAM y preguntas frecuentes.</p>
                </div>
                <a href="{{ route('reciclaje') }}">
                    <span>Ver Información Institucional RAEE</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var currentDevice = 'Laptop / Computadora';
            var currentLocation = 'Sede Huánuco (Jr. Crespo y Castillo 480)';
            var phone = '51958021778';

            function updatePersonaWhatsapp() {
                var summary = document.getElementById('persona-summary-text');
                var btn = document.getElementById('btn-whatsapp-persona');
                if (!summary || !btn) return;

                summary.innerHTML = 'Reciclar <strong>' + currentDevice + '</strong> en <strong>' + currentLocation + '</strong>';

                var msg = 'Hola KENYA TECHNOLOGY, deseo reciclar ' + currentDevice + ' en ' + currentLocation + '. ¿En qué horario puedo entregar el equipo?';
                btn.href = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(msg);
            }

            window.switchRecycleTab = function(tab) {
                var tabPersona = document.getElementById('tab-btn-persona');
                var tabEmpresa = document.getElementById('tab-btn-empresa');
                var panelPersona = document.getElementById('panel-persona');
                var panelEmpresa = document.getElementById('panel-empresa');
                if (!tabPersona || !tabEmpresa || !panelPersona || !panelEmpresa) return;

                if (tab === 'persona') {
                    tabPersona.classList.add('active');
                    tabEmpresa.classList.remove('active');
                    panelPersona.style.display = 'block';
                    panelEmpresa.style.display = 'none';
                } else {
                    tabEmpresa.classList.add('active');
                    tabPersona.classList.remove('active');
                    panelPersona.style.display = 'none';
                    panelEmpresa.style.display = 'block';
                }
            };

            window.selectDevice = function(btn) {
                var cards = document.querySelectorAll('.ra-dev-card');
                cards.forEach(function(c) { c.classList.remove('active'); });
                btn.classList.add('active');
                currentDevice = btn.getAttribute('data-type') || 'Equipo informático';
                updatePersonaWhatsapp();
            };

            window.selectLocation = function(btn) {
                var cards = document.querySelectorAll('.ra-loc-card');
                cards.forEach(function(c) { c.classList.remove('active'); });
                btn.classList.add('active');
                currentLocation = btn.getAttribute('data-location') || 'Sede Huánuco';
                updatePersonaWhatsapp();
            };

            window.submitEmpresaRecycling = function(e) {
                e.preventDefault();
                var emp = document.getElementById('empresa_nombre').value.trim();
                var ruc = document.getElementById('empresa_ruc').value.trim();
                var cant = document.getElementById('empresa_cantidad').value;
                var ciu = document.getElementById('empresa_ciudad').value;

                var rucText = ruc ? ' (RUC: ' + ruc + ')' : '';
                var msg = 'Hola KENYA TECHNOLOGY, represento a ' + emp + rucText + '. Requerimos coordinar el recojo institucional de ' + cant + ' en ' + ciu + ' y la emisión de la Constancia Oficial de Disposición RAEE.';

                var url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(msg);
                window.open(url, '_blank');
            };

            document.addEventListener('DOMContentLoaded', function() {
                updatePersonaWhatsapp();
            });
        })();
    </script>
@endsection
