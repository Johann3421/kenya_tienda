@extends('layouts.landing')

@section('title', $norma['seo_title'])
@section('meta_description', $norma['meta_description'])
@section('meta_keywords', $norma['meta_keywords'])
@section('canonical', route($norma['route_name']))
@section('og_title', $norma['og_title'])
@section('og_description', $norma['og_description'])

@section('content')
    <style>
        :root {
            --iso-orange-primary: #ef5a24;
            --iso-text-dark: #0f172a;
            --iso-border-light: #e2e8f0;
            --iso-font-heading: 'Gilroy-Bold', 'Inter', sans-serif;
            --iso-layout-max-width: 1320px;
        }

        .iso-view {
            background-color: #ffffff;
            color: var(--iso-text-dark);
            -webkit-font-smoothing: antialiased;
        }

        /* ==================== BANNER SUPERIOR ISO ==================== */
        .iso-hero-banner {
            position: relative;
            width: 100%;
            min-height: 220px;
            background-image:
                linear-gradient(
                    90deg,
                    rgba(248, 250, 252, 0.98) 0%,
                    rgba(248, 250, 252, 0.93) 40%,
                    rgba(241, 245, 249, 0.65) 68%,
                    rgba(226, 232, 240, 0.35) 100%
                ),
                url('{{ asset($norma['hero_bg']) }}');
            background-size: cover;
            background-position: center 45%;
            border-bottom: 1px solid var(--iso-border-light);
            display: flex;
            align-items: center;
            padding: 44px 24px;
        }

        .iso-hero-banner.iso-14001-banner {
            background-position: center 50%;
        }

        .iso-hero-container {
            width: 100%;
            max-width: var(--iso-layout-max-width);
            margin: 0 auto;
        }

        .iso-hero-text {
            max-width: 680px;
        }

        .iso-hero-text h1 {
            font-family: var(--iso-font-heading);
            font-size: 34px;
            font-weight: 800;
            color: #0b0f19;
            letter-spacing: -0.4px;
            margin-bottom: 8px;
            text-transform: uppercase;
            line-height: 1.15;
        }

        .iso-hero-text p {
            font-size: 15px;
            color: #475569;
            font-weight: 400;
            margin: 0;
            line-height: 1.5;
        }

        /* ==================== DISEÑO CORPORATIVO ARQUITECTÓNICO ==================== */
        .iso-main-content {
            padding: 76px 24px 116px;
            background-color: #ffffff;
        }

        .iso-container {
            width: 100%;
            max-width: var(--iso-layout-max-width);
            margin: 0 auto;
        }

        .iso-executive-split {
            display: grid;
            grid-template-columns: 460px minmax(0, 1fr);
            gap: 56px;
            align-items: stretch;
        }

        /* --- COLUMNA IZQUIERDA CON DIVISOR VERTICAL --- */
        .iso-left-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 24px;
            padding-right: 48px;
            border-right: 1px solid #e2e8f0;
        }

        .iso-intro-box {
            padding: 0;
            border: none;
        }

        .iso-overline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--iso-font-heading);
            font-size: 10.5px;
            font-weight: 800;
            color: var(--iso-orange-primary);
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .iso-overline::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 1px;
            background-color: var(--iso-orange-primary);
            flex-shrink: 0;
        }

        .iso-description {
            font-size: 15px;
            line-height: 1.76;
            color: #334155;
            font-weight: 400;
            margin: 0;
        }

        /* Metadatos en 2 Tarjetas Gemelas Independientes */
        .iso-meta-bar {
            display: grid;
            grid-template-columns: 1.35fr 1fr;
            gap: 12px;
            border: none;
            padding: 0;
            background: transparent;
        }

        .iso-meta-col {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px 14px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 6px;
        }

        .iso-meta-label {
            font-size: 9.5px;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.9px;
            text-transform: uppercase;
        }

        .iso-meta-value {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            font-family: var(--iso-font-heading);
            font-size: 14.5px;
            color: #0f172a;
        }

        .badge-verified {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background-color: #ffffff;
            color: #059669;
            border: 1px solid #a7f3d0;
            font-family: 'Inter', sans-serif;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 4px;
        }

        /* Credencial Ejecutiva Oscura Refinada */
        .iso-accreditation-card {
            background-color: #0f172a;
            color: #ffffff;
            border-radius: 10px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            border: 1px solid #1e293b;
            box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.12);
        }

        .accreditation-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .iso-accreditation-card h4 {
            font-family: var(--iso-font-heading);
            font-size: 15.5px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.2px;
            margin: 0 0 3px;
        }

        .iso-accreditation-card p {
            font-size: 12px;
            color: #94a3b8;
            margin: 0;
        }

        .accreditation-badge {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(239, 90, 36, 0.14);
            color: var(--iso-orange-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .iaf-verify-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 11px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #f8fafc;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .iaf-verify-btn:hover {
            border-color: #10b981;
            background-color: rgba(16, 185, 129, 0.1);
            color: #ffffff;
        }

        .iaf-left {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .iaf-left i {
            color: #10b981;
            font-size: 13.5px;
        }

        .iaf-ext {
            color: #94a3b8;
            font-size: 11px;
        }

        /* --- COLUMNA DERECHA: Módulos con Contraste Suave y Elevación Activa --- */
        .iso-right-panel {
            display: flex;
            flex-direction: column;
        }

        .iso-list {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
        }

        .iso-list-item {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 18px;
            background-color: #f8fafc;
            border: 1px solid #edf2f7;
            border-left: 3px solid transparent;
            border-radius: 8px;
            padding: 16px 24px;
            cursor: pointer;
            position: relative;
            transition: all 0.22s ease;
        }

        .iso-list-item::after {
            content: "\f054";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 11px;
            color: #cbd5e1;
            margin-left: auto;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .iso-list-item:hover {
            background-color: #ffffff;
            border-color: #e2e8f0;
            border-left-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        }

        .iso-list-item.active {
            background-color: #ffffff;
            border-color: #e2e8f0;
            border-left: 3px solid var(--iso-orange-primary);
            box-shadow: 0 8px 22px -4px rgba(15, 23, 42, 0.06);
        }

        .iso-list-item.active::after,
        .iso-list-item:hover::after {
            color: var(--iso-orange-primary);
            transform: translateX(2px);
        }

        .iso-item-num {
            font-family: var(--iso-font-heading);
            font-size: 12.5px;
            font-weight: 800;
            color: #64748b;
            width: 34px;
            height: 34px;
            border-radius: 6px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .iso-list-item:hover .iso-item-num {
            color: var(--iso-orange-primary);
            border-color: rgba(239, 90, 36, 0.3);
        }

        .iso-list-item.active .iso-item-num {
            background-color: var(--iso-orange-primary);
            border-color: var(--iso-orange-primary);
            color: #ffffff;
        }

        .iso-item-content {
            flex: 1;
            padding-right: 12px;
        }

        .iso-item-title {
            font-size: 14.5px;
            font-weight: 400;
            color: #475569;
            line-height: 1.5;
        }

        .iso-item-title strong {
            font-weight: 700;
            color: #0f172a;
            margin-right: 4px;
        }

        /* Responsivo */
        @media (max-width: 992px) {
            .iso-executive-split {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .iso-left-panel {
                padding-right: 0;
                padding-bottom: 36px;
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }
        }

        @media (max-width: 576px) {
            .iso-hero-banner {
                min-height: 180px;
                padding: 32px 18px;
            }
            .iso-hero-text h1 {
                font-size: 26px;
            }
            .iso-main-content {
                padding: 44px 18px 72px;
            }
            .iso-meta-bar {
                grid-template-columns: 1fr;
            }
            .iso-list-item {
                padding: 14px 16px;
                gap: 12px;
            }
        }
    </style>

    <main class="iso-view">
        <!-- Banner Superior con Imagen Corporativa ISO -->
        <section class="iso-hero-banner @if(isset($norma['code']) && str_contains($norma['code'], '14001')) iso-14001-banner @endif">
            <div class="iso-hero-container">
                <div class="iso-hero-text">
                    <h1>{{ $norma['hero_title'] }}</h1>
                    <p>{{ $norma['hero_subtitle'] }}</p>
                </div>
            </div>
        </section>

        <!-- Apartado Principal: Misma Composición de 2 Columnas de la Maqueta -->
        <section class="iso-main-content">
            <div class="iso-container">
                <div class="iso-executive-split">

                    <!-- COLUMNA IZQUIERDA: Introducción + Metadatos + Acreditación IAF -->
                    <div class="iso-left-panel">
                        <div class="iso-intro-box">
                            <span class="iso-overline">{{ $norma['kicker'] }}</span>
                            <p class="iso-description">{{ $norma['lead'] }}</p>
                        </div>

                        <!-- Bloque de Certificado y Estructura con Bordes Compartidos -->
                        <div class="iso-meta-bar">
                            <div class="iso-meta-col first">
                                <span class="iso-meta-label">{{ $norma['meta_label'] }}</span>
                                <div class="iso-meta-value">
                                    <strong>{{ $norma['code'] }}</strong>
                                    <span class="badge-verified">
                                        <i class="fa-solid fa-shield-halved"></i> Verificado
                                    </span>
                                </div>
                            </div>

                            <div class="iso-meta-col">
                                <span class="iso-meta-label">{{ $norma['lineamientos_label'] }}</span>
                                <div class="iso-meta-value">
                                    <strong>{{ $norma['lineamientos_count'] }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta Oscura Corporativa de Acreditación con Enlace IAF -->
                        <aside class="iso-accreditation-card">
                            <div class="accreditation-header">
                                <div>
                                    <h4>{{ $norma['card_title'] }}</h4>
                                    <p>{{ $norma['card_subtitle'] }}</p>
                                </div>
                                <span class="accreditation-badge">
                                    <i class="fa-solid {{ $norma['badge_icon'] }}"></i>
                                </span>
                            </div>

                            <a href="https://www.iafcertsearch.org/" target="_blank" rel="noopener" class="iaf-verify-btn" aria-label="Verificar acreditación en IAF CertSearch">
                                <span class="iaf-left">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Verificado en IAF CertSearch
                                </span>
                                <i class="fa-solid fa-arrow-up-right-from-square iaf-ext"></i>
                            </a>
                        </aside>
                    </div>

                    <!-- COLUMNA DERECHA: Las Cláusulas en Matriz Interactiva -->
                    <div class="iso-right-panel">
                        <div class="iso-list" id="isoList">
                            @foreach ($norma['items'] as $index => $item)
                                <div class="iso-list-item @if($index === 0) active @endif" role="button" tabindex="0">
                                    <span class="iso-item-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="iso-item-content">
                                        <span class="iso-item-title">
                                            <strong>{{ $item['titulo'] }}</strong>@if(!empty($item['detalle'])) {{ $item['detalle'] }}@endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isoItems = document.querySelectorAll('.iso-list-item');
            isoItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    isoItems.forEach(function(el) { el.classList.remove('active'); });
                    item.classList.add('active');
                });
                item.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        isoItems.forEach(function(el) { el.classList.remove('active'); });
                        item.classList.add('active');
                    }
                });
            });
        });
    </script>
@endsection
