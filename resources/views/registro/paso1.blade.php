@extends('layouts.landing')

@section('title', 'Registro de Clientes')

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
<!-- Añadido espaciado extra superior para que no choque con el navbar del landing layout -->
<div class="container mb-5" style="padding-top: 120px;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="text-center mb-4" style="font-weight: 700; color: #333;">Únete a Kenya</h2>
            <p class="text-center mb-5" style="color: #666;">Selecciona tu perfil para acceder a precios exclusivos y cotizaciones.</p>
            
            <div class="row text-center">
                <!-- Cliente Regular -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; transition: transform 0.2s;">
                        <div class="card-body p-4 d-flex flex-column">
                            <h4 class="card-title font-weight-bold">Regular</h4>
                            <p class="card-text text-muted mb-4">Para empresas, profesionales y público en general.</p>
                            <a href="{{ url('registro/paso2?tipo=regular') }}" class="btn btn-primary mt-auto" style="border-radius: 50px;">Solicitar registro</a>
                        </div>
                    </div>
                </div>

                <!-- Cliente Gubernamental -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; transition: transform 0.2s;">
                        <div class="card-body p-4 d-flex flex-column">
                            <h4 class="card-title font-weight-bold">Gubernamental</h4>
                            <p class="card-text text-muted mb-4">Para entidades del estado y compradores de Perú Compras.</p>
                            <a href="{{ url('registro/paso2?tipo=gubernamental') }}" class="btn btn-outline-primary mt-auto" style="border-radius: 50px;">Pendiente (Próximamente)</a>
                        </div>
                    </div>
                </div>

                <!-- Canal Kenya -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; transition: transform 0.2s;">
                        <div class="card-body p-4 d-flex flex-column">
                            <h4 class="card-title font-weight-bold">Canal Kenya</h4>
                            <p class="card-text text-muted mb-4">Para distribuidores y partners estratégicos.</p>
                            <a href="{{ url('registro/paso2?tipo=canal') }}" class="btn btn-outline-primary mt-auto" style="border-radius: 50px;">Pendiente (Próximamente)</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ponytail: simplification -->
            <div class="mt-5 text-center text-muted small">
                <p><strong>Nota técnica (Ponytail):</strong> Se ha implementado únicamente la interfaz del Paso 1 para desatascar la ejecución mientras se definen las validaciones reales (SUNAT/Correo) que bloquean la creación del Paso 2.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
</style>
@endsection
