@extends('layouts.landing')

@section('title', 'Registro de Clientes - Resultado')

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
<div class="container mb-5" style="padding-top: 120px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-5 text-center">
                    @if($estado === 'aprobado')
                        <div class="mb-4">
                            <i class="fa fa-check-circle" style="font-size: 4rem; color: #2ecca6;"></i>
                        </div>
                        <h3 class="font-weight-bold mb-3">¡Validación Exitosa!</h3>
                        <p class="text-muted mb-4">El RUC <strong>{{ $ruc }}</strong> y correo <strong>{{ $correo }}</strong> han sido validados para el perfil <strong>{{ strtoupper($tipo) }}</strong>.</p>
                        
                        <div class="alert alert-success" style="border-radius: 8px;">
                            <strong>¡Excelente!</strong> Ahora tienes acceso a nuestros precios exclusivos e información técnica completa.
                        </div>

                        <a href="{{ url('/catalogo') }}" class="btn btn-primary mt-3 px-5 py-2" style="border-radius: 50px; font-weight: 600;">Ir al Catálogo</a>
                    @else
                        <div class="mb-4">
                            <i class="fa fa-times-circle" style="font-size: 4rem; color: #dc3545;"></i>
                        </div>
                        <h3 class="font-weight-bold mb-3">No pudimos validar tus datos</h3>
                        <p class="text-muted mb-4">Lamentablemente, los datos proporcionados no cumplen con los requisitos para acceder a precios especiales.</p>
                        
                        <div class="alert alert-warning" style="border-radius: 8px;">
                            Solo podrás acceder a nuestros precios referenciales de lista.
                        </div>

                        <a href="{{ url('/catalogo') }}" class="btn btn-outline-primary mt-3 px-5 py-2" style="border-radius: 50px; font-weight: 600;">Ver precios públicos</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
