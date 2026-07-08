@extends('layouts.landing')

@section('title', 'Mi Perfil | Kenya')

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
    .perfil-wrapper { max-width: 680px; margin: 60px auto; padding: 0 20px 60px; }
    .perfil-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); overflow: hidden; }
    .perfil-header { background: linear-gradient(135deg, #ee7c31, #f5a623); padding: 32px 36px; display: flex; align-items: center; gap: 20px; }
    .perfil-avatar { width: 72px; height: 72px; background: rgba(255,255,255,0.25); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #fff; flex-shrink: 0; }
    .perfil-header h2 { margin: 0; color: #fff; font-size: 1.4rem; font-weight: 700; }
    .perfil-header p { margin: 4px 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; }
    .perfil-body { padding: 36px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: 600; font-size: 0.88rem; color: #555; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.4px; }
    .form-control { width: 100%; padding: 11px 14px; border: 1.5px solid #e5e5e5; border-radius: 8px; font-size: 0.95rem; outline: none; transition: border-color 0.2s; box-sizing: border-box; }
    .form-control:focus { border-color: #ee7c31; box-shadow: 0 0 0 3px rgba(238,124,49,0.1); }
    .btn-save { background: linear-gradient(135deg, #ee7c31, #f5a623); color: #fff; border: none; padding: 13px 36px; border-radius: 8px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: opacity 0.2s; }
    .btn-save:hover { opacity: 0.9; }
    .alert-success { background: #d4edda; color: #155724; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: 0.92rem; }
    .text-danger { color: #dc3545; font-size: 0.82rem; display: block; margin-top: 4px; }
    .divider { border: none; border-top: 1px solid #eee; margin: 28px 0; }
    .section-title { font-weight: 700; font-size: 0.85rem; color: #aaa; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px; }
    .link-cotizaciones { display: inline-flex; align-items: center; gap: 8px; color: #ee7c31; font-weight: 600; text-decoration: none; font-size: 0.92rem; }
    .link-cotizaciones:hover { text-decoration: underline; }
</style>

<div class="perfil-wrapper">
    <div class="perfil-card">
        <div class="perfil-header">
            <div class="perfil-avatar"><i class="fa-solid fa-user"></i></div>
            <div>
                <h2>{{ $user->nombres ?? $user->email }}</h2>
                <p>{{ $user->email }}</p>
            </div>
        </div>
        <div class="perfil-body">
            @if(session('success'))
                <div class="alert-success">✓ {{ session('success') }}</div>
            @endif

            <div class="section-title">Información personal</div>
            <form method="POST" action="{{ route('cliente.perfil.update') }}">
                @csrf
                <div class="form-group">
                    <label>Nombres</label>
                    <input type="text" name="nombres" class="form-control" value="{{ old('nombres', $user->nombres) }}" required>
                    @error('nombres')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono) }}" placeholder="Opcional">
                </div>

                <hr class="divider">
                <div class="section-title">Cambiar contraseña <span style="font-weight:400;color:#bbb;">(dejar en blanco para conservar)</span></div>

                <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" name="password" class="form-control" autocomplete="new-password">
                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                </div>

                <hr class="divider">
                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
                    <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button>
                    <a href="{{ route('cliente.cotizaciones') }}" class="link-cotizaciones"><i class="fa-solid fa-file-invoice-dollar"></i> Ver Mis Cotizaciones</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
