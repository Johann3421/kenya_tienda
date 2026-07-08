@extends('layouts.landing')

@section('title', 'Mis Cotizaciones | Kenya')

@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link">Inicio</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
<style>
    .cotiz-wrapper { max-width: 860px; margin: 60px auto; padding: 0 20px 80px; }
    .cotiz-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); overflow: hidden; }
    .cotiz-header { background: linear-gradient(135deg, #ee7c31, #f5a623); padding: 32px 36px; display: flex; align-items: center; gap: 20px; }
    .cotiz-icon { width: 64px; height: 64px; background: rgba(255,255,255,0.25); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: #fff; flex-shrink: 0; }
    .cotiz-header h2 { margin: 0; color: #fff; font-size: 1.4rem; font-weight: 700; }
    .cotiz-header p  { margin: 4px 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; }
    .cotiz-body { padding: 36px; }
    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state i { font-size: 3rem; color: #ddd; display: block; margin-bottom: 16px; }
    .empty-state h3 { color: #555; font-size: 1.15rem; margin-bottom: 8px; }
    .empty-state p  { color: #aaa; font-size: 0.92rem; margin-bottom: 28px; }
    .btn-catalogo { display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg, #ee7c31, #f5a623); color: #fff; padding: 13px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 0.95rem; transition: opacity 0.2s; }
    .btn-catalogo:hover { opacity: 0.9; color: #fff; }
    .link-perfil { display: inline-flex; align-items: center; gap: 6px; color: #ee7c31; font-weight: 600; text-decoration: none; font-size: 0.88rem; margin-top: 20px; }
    .link-perfil:hover { text-decoration: underline; }
    .info-banner { background: #fff8f0; border: 1px solid #f5d5b8; border-radius: 10px; padding: 18px 22px; margin-bottom: 28px; display: flex; gap: 14px; align-items: flex-start; }
    .info-banner i { color: #ee7c31; font-size: 1.2rem; flex-shrink: 0; margin-top: 2px; }
    .info-banner p { margin: 0; color: #7a4f2e; font-size: 0.9rem; line-height: 1.5; }
</style>

<div class="cotiz-wrapper">
    <div class="cotiz-card">
        <div class="cotiz-header">
            <div class="cotiz-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            <div>
                <h2>Mis Cotizaciones</h2>
                <p>{{ $user->nombres ?? $user->email }} &mdash; {{ $user->email }}</p>
            </div>
        </div>
        <div class="cotiz-body">
            <div class="info-banner">
                <i class="fa-solid fa-circle-info"></i>
                <p>Las cotizaciones solicitadas a través del catálogo aparecerán aquí. Para solicitar una cotización personalizada, explora el catálogo y escoge los productos que necesitas.</p>
            </div>

            {{-- ponytail: tabla cotizaciones pendiente — placeholder hasta implementar modelo --}}
            <div class="empty-state">
                <i class="fa-regular fa-folder-open"></i>
                <h3>Aún no tienes cotizaciones registradas</h3>
                <p>Navega nuestro catálogo y solicita precios especiales B2B directamente.</p>
                <a href="{{ route('catalogo') }}" class="btn-catalogo">
                    <i class="fa-solid fa-tag"></i> Ver Catálogo B2B
                </a>
                <br>
                <a href="{{ route('cliente.perfil') }}" class="link-perfil">
                    <i class="fa-solid fa-user"></i> Volver a Mi Perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
