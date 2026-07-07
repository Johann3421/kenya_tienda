<!DOCTYPE html>
<html lang="es">

<head>
    <title>Portal de Clientes - KENYA</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style>
        .is-invalid { color: red; }
        body { background: #f4f6f8; font-family: 'Inter', sans-serif; }
        .auth-wrapper { display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .card { border: none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; width: 100%; max-width: 400px; margin: 20px; background: #fff; }
        .card-body { padding: 40px; }
        .logo-box { text-align: center; margin-bottom: 30px; }
        .logo-box img { max-width: 140px; }
        h4 { text-align: center; font-weight: 700; color: #333; margin-bottom: 5px; font-size: 20px; }
        p.subtitle { text-align: center; color: #888; font-size: 13px; margin-bottom: 30px; }
        .form-control { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 15px; outline: none; box-sizing: border-box; }
        .form-control:focus { border-color: #ee7c31; }
        .btn-primary { width: 100%; padding: 12px; background: #ee7c31; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-primary:hover { background: #d46820; }
        .invalid-feedback { font-size: 12px; color: red; display: block; margin-top: -10px; margin-bottom: 10px; }
        .back-link { display: block; text-align: center; margin-top: 20px; font-size: 13px; color: #ee7c31; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="logo-box">
                    @php
                        $logo_sistema = App\Models\Configuracion::where('nombre', 'logo_sistema')->first();
                    @endphp
                    @if ($logo_sistema && $logo_sistema->archivo)
                        <img src="{{asset('storage/'.$logo_sistema->archivo_ruta.'/'.$logo_sistema->archivo)}}" alt="Kenya Logo">
                    @else
                        <img src="{{asset('theme/images/kenya.png')}}" alt="Kenya Logo">
                    @endif
                </div>

                <h4>Portal de Cotizaciones</h4>
                <p class="subtitle">Acceso exclusivo para clientes verificados</p>

                <form method="POST" action="{{ route('login-cliente.post') }}">
                    @csrf
                    
                    <input type="text" placeholder="Nombre de Usuario" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" autocomplete="username" autofocus>
                    @error('username')
                        <small class="invalid-feedback" role="alert">{{ $message }}</small>
                    @enderror

                    <input type="password" placeholder="Contraseña" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password">
                    @error('password')
                        <small class="invalid-feedback" role="alert">{{ $message }}</small>
                    @enderror

                    <button type="submit" class="btn-primary">Ingresar</button>
                </form>

                <div style="text-align: center; margin-top: 20px; font-size: 14px;">
                    ¿No tienes una cuenta? <a href="{{ url('/registro/paso1') }}" style="color: #ee7c31; font-weight: 600; text-decoration: none;">Solicita tu acceso aquí</a>
                </div>
                <a href="{{ url('/') }}" class="back-link">← Volver a la tienda</a>
            </div>
        </div>
    </div>
</body>
</html>
