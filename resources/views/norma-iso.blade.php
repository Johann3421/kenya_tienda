@extends('layouts.landing')

@section('title', $norma['seo_title'])
@section('meta_description', $norma['meta_description'])
@section('meta_keywords', $norma['meta_keywords'])
@section('canonical', route($norma['route_name']))
@section('og_title', $norma['og_title'])
@section('og_description', $norma['og_description'])

@section('content')
    <style>
        #iso-page {
            --iso-accent: #f26522;
            --iso-navy: #1b2633;
            --iso-ink: #111827;
            --iso-text: #374151;
            --iso-muted: #6b7280;
            --iso-rule: #e5e7eb;
            background: #fff;
            color: var(--iso-text);
            line-height: 1.7;
        }

        #iso-page .iso-hero {
            background-image: linear-gradient(rgba(255,255,255,.7), rgba(255,255,255,.7)), url('{{ asset("banersomos.png?v=2") }}');
            background-size: cover;
            background-position: right;
            padding: 64px 15px;
            border-bottom: 1px solid var(--iso-rule);
        }
        #iso-page .iso-hero-inner { max-width: 1400px; margin: 0 auto; padding: 0 10px; }
        #iso-page .iso-hero h1 {
            margin: 0 0 6px;
            font-size: 2.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--iso-ink);
            line-height: 1.15;
        }
        #iso-page .iso-hero p { margin: 0; font-size: 1.1rem; color: var(--iso-muted); }

        #iso-page .iso-wrap { max-width: 1400px; margin: 0 auto; padding: 28px 20px 96px; }

        #iso-page .iso-crumbs { font-size: .85rem; color: var(--iso-muted); margin-bottom: 36px; }
        #iso-page .iso-crumbs a { color: var(--iso-muted); text-decoration: none; }
        #iso-page .iso-crumbs a:hover { color: var(--iso-accent); }
        #iso-page .iso-crumbs span { margin: 0 6px; color: #d1d5db; }

        #iso-page .iso-head {
            padding-bottom: 28px;
            border-bottom: 2px solid var(--iso-ink);
        }
        #iso-page .iso-kicker {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--iso-accent);
            margin-bottom: 12px;
        }
        #iso-page .iso-head h2 {
            margin: 0 0 14px;
            font-size: 2.3rem;
            line-height: 1.2;
            font-weight: 800;
            color: var(--iso-ink);
            letter-spacing: -.02em;
        }
        #iso-page .iso-head .iso-lead {
            margin: 0;
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 72ch;
            color: #4b5563;
        }

        #iso-page .iso-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 320px));
            margin: 0 0 54px;
            border-bottom: 1px solid var(--iso-rule);
        }
        #iso-page .iso-meta div { padding: 18px 28px 18px 0; }
        #iso-page .iso-meta div + div { padding-left: 28px; border-left: 1px solid var(--iso-rule); }
        #iso-page .iso-meta dt {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--iso-muted);
            margin-bottom: 4px;
        }
        #iso-page .iso-meta dd { margin: 0; font-weight: 600; color: var(--iso-ink); font-size: .95rem; }

        #iso-page .iso-body {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 380px;
            gap: 56px;
            align-items: start;
        }

        #iso-page .iso-content { min-width: 0; }

        #iso-page .iso-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        #iso-page .iso-item {
            display: flex;
            align-items: baseline;
            gap: 20px;
            padding: 18px 0;
            border-bottom: 1px solid var(--iso-rule);
        }
        #iso-page .iso-item:first-child { padding-top: 0; }
        #iso-page .iso-num {
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1;
            color: #9ca3af;
            font-variant-numeric: tabular-nums;
            min-width: 28px;
            flex-shrink: 0;
        }
        #iso-page .iso-item-text {
            margin: 0;
            font-size: 1.06rem;
            font-weight: 400;
            color: var(--iso-text);
            line-height: 1.5;
        }
        #iso-page .iso-item-text strong {
            font-weight: 700;
            color: var(--iso-ink);
        }

        #iso-page .iso-btn {
            display: inline-block;
            background: var(--iso-navy);
            color: #fff;
            padding: 13px 24px;
            font-weight: 600;
            font-size: .95rem;
            text-decoration: none;
            border-radius: 2px;
            transition: background .15s ease;
        }
        #iso-page .iso-btn:hover { background: var(--iso-accent); color: #fff; }

        #iso-page .iso-sidebar {
            position: sticky;
            top: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        #iso-page .iso-card {
            margin: 0;
            background: #ffffff;
            border: 1px solid var(--iso-rule);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 10px 25px -10px rgba(17, 24, 39, 0.08);
        }
        #iso-page .iso-card-media {
            background: #fafaf9;
        }
        #iso-page .iso-card-media img {
            display: block;
            width: 100%;
            height: auto;
            padding: 18px;
            box-sizing: border-box;
        }
        #iso-page .iso-card figcaption {
            background: var(--iso-navy);
            color: #f3f4f6;
            padding: 14px 18px;
            font-size: .84rem;
            display: flex;
            flex-direction: column;
            gap: 4px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        #iso-page .iso-card figcaption strong {
            color: #fff;
            font-weight: 700;
            letter-spacing: .02em;
            font-size: .92rem;
        }
        #iso-page .iso-card figcaption span.iso-subtitle {
            color: #9ca3af;
            font-size: .8rem;
        }

        #iso-page .iso-sidebar-box {
            background: #fafaf9;
            border: 1px solid var(--iso-rule);
            border-left: 3px solid var(--iso-accent);
            border-radius: 4px;
            padding: 20px;
        }
        #iso-page .iso-sidebar-box-title {
            margin: 0 0 6px;
            font-size: .95rem;
            font-weight: 700;
            color: var(--iso-ink);
        }
        #iso-page .iso-sidebar-box-desc {
            margin: 0 0 14px;
            font-size: .85rem;
            color: var(--iso-muted);
            line-height: 1.5;
        }
        #iso-page .iso-sidebar-contacts {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }
        #iso-page .iso-sidebar-contacts a {
            font-size: .84rem;
            color: var(--iso-ink);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        #iso-page .iso-sidebar-contacts a:hover { color: var(--iso-accent); }
        #iso-page .iso-sidebar-contacts a i {
            color: var(--iso-accent);
            font-size: .8rem;
            width: 14px;
        }
        #iso-page .iso-btn-sm {
            display: block;
            text-align: center;
            padding: 10px 14px;
            font-size: .86rem;
            width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 991px) {
            #iso-page .iso-hero h1 { font-size: 2rem; }
            #iso-page .iso-head h2 { font-size: 1.9rem; }
            #iso-page .iso-meta { grid-template-columns: 1fr 1fr; }
            #iso-page .iso-body { grid-template-columns: 1fr; gap: 40px; }
            #iso-page .iso-sidebar { position: static; max-width: 480px; margin: 0 auto; width: 100%; }
        }
    </style>

    <div id="iso-page">
        <section class="iso-hero">
            <div class="iso-hero-inner">
                <h1>{{ $norma['hero_title'] }}</h1>
                <p>{{ $norma['hero_subtitle'] }}</p>
            </div>
        </section>

        <main class="iso-wrap">
            <nav class="iso-crumbs" aria-label="Ruta de navegación">
                <a href="{{ url('/') }}">Inicio</a><span>/</span>Nuestra Empresa<span>/</span><strong>{{ $norma['breadcrumb'] }}</strong>
            </nav>

            <header class="iso-head">
                <span class="iso-kicker">{{ $norma['kicker'] }}</span>
                <h2>{{ $norma['heading'] }}</h2>
                <p class="iso-lead">{{ $norma['lead'] }}</p>
            </header>

            <dl class="iso-meta">
                <div>
                    <dt>{{ $norma['meta_label'] }}</dt>
                    <dd>{{ $norma['code'] }}</dd>
                </div>
                <div>
                    <dt>{{ $norma['lineamientos_label'] }}</dt>
                    <dd>{{ $norma['lineamientos_count'] }}</dd>
                </div>
            </dl>

            <div class="iso-body">
                <div class="iso-content">
                    <ol class="iso-list">
                        @foreach ($norma['items'] as $i => $item)
                            <li class="iso-item" id="lineamiento-{{ $i + 1 }}">
                                <span class="iso-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="iso-item-text">
                                    <strong>{{ $item['titulo'] }}</strong>@if(!empty($item['detalle'])) {{ $item['detalle'] }}@endif
                                </h3>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <aside class="iso-sidebar">
                    <figure class="iso-card">
                        <div class="iso-card-media">
                            <img src="{{ asset($norma['image']) }}" alt="{{ $norma['image_alt'] }}" loading="lazy">
                        </div>
                        <figcaption>
                            <strong>{{ $norma['card_title'] }}</strong>
                            <span class="iso-subtitle">{{ $norma['card_subtitle'] }}</span>
                        </figcaption>
                    </figure>

                    <div class="iso-sidebar-box">
                        <p class="iso-sidebar-box-title">{{ $norma['contact_title'] }}</p>
                        <p class="iso-sidebar-box-desc">{{ $norma['contact_desc'] }}</p>
                        <div class="iso-sidebar-contacts">
                            <a href="mailto:acuerdos.marco@kenya.com.pe"><i class="fa-solid fa-envelope"></i> acuerdos.marco@kenya.com.pe</a>
                            <a href="tel:+51958021778"><i class="fa-solid fa-phone"></i> 958 021 778</a>
                        </div>
                        <a class="iso-btn iso-btn-sm" href="{{ route($norma['contact_cta_route']) }}">{{ $norma['contact_cta_label'] }}</a>
                    </div>
                </aside>
            </div>
        </main>
    </div>
@endsection
