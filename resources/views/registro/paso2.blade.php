@extends('layouts.landing')

@section('title', 'Registro de Clientes - Paso 2')

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
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 font-weight-bold">Validación de Datos</h3>
                    <p class="text-muted text-center mb-4">Perfil seleccionado: <span class="badge badge-primary">{{ strtoupper($tipo) }}</span></p>
                    
                    <form action="{{ url('registro/validar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipo" value="{{ $tipo }}">
                        
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">RUC</label>
                            <input type="text" name="documento" class="form-control @error('documento') is-invalid @enderror" placeholder="Ingrese su RUC (11 dígitos)" value="{{ old('documento') }}" required minlength="11" maxlength="11" pattern="^(10|15|17|20)\d{9}$" title="El RUC debe tener 11 dígitos y empezar con 10, 15, 17 o 20" style="border-radius: 8px;">
                            @error('documento')
                                <div class="invalid-feedback" style="display:block; color:#dc3545; font-size:0.85rem; margin-top:4px;">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="form-text text-muted mt-2">
                                <i class="fa fa-info-circle"></i> Su RUC será validado para otorgarle acceso a condiciones especiales.
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" placeholder="usuario@hotmail.com" value="{{ old('correo') }}" required pattern="^.+@(hotmail\.com|gmail\.com|outlook\.com)$" title="Solo se permiten correos de @hotmail.com, @gmail.com o @outlook.com" style="border-radius: 8px;">
                            @error('correo')
                                <div class="invalid-feedback" style="display:block; color:#dc3545; font-size:0.85rem; margin-top:4px;">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                    @if(str_contains($message, 'ya está registrado'))
                                        — <a href="{{ url('/acceso-clientes') }}" style="color:#ee7c31; font-weight:600;">Ingresar al portal</a>
                                    @endif
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block py-2" style="border-radius: 50px; font-weight: 600;">Validar Datos</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
