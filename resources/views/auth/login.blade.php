<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>

    <head>
        <title>Gradient Able bootstrap admin template by codedthemes</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <style>
            .is-invalid {
                color: red;
            }
            .incorrecto {
                border: 1px solid red;
            }
        </style>
        <link rel="icon" href="{{asset('theme/images/favicon.ico')}}" type="image/x-icon">

        <link rel="stylesheet" href="{{asset('theme/css/style.css')}}">
    </head>
    <body>
        <div class="auth-wrapper">
            <div class="auth-content">
                <div class="card">
                    <div class="row align-items-center text-center">
                        <div class="col-md-12">
                            <div class="card-body">
                                @php
                                    $logo_sistema = App\Models\Configuracion::where('nombre', 'logo_sistema')->first();
                                @endphp
                                @if ($logo_sistema->archivo)
                                    <img src="{{asset('storage/'.$logo_sistema->archivo_ruta.'/'.$logo_sistema->archivo)}}" alt="" class="img-fluid mb-4" style="width: 140px;">
                                @else
                                    <img src="{{asset('theme/images/kenya.png')}}" alt="Vasco" class="img-fluid mb-4" style="width: 76%;">
                                @endif
                                {{-- <img src="{{asset('theme/images/logo-dark.png')}}" alt="" class="img-fluid mb-4"> --}}
                                <h4 class="mb-3 f-w-400">Iniciar Sesión</h4>
                                <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <input type="text" name="hidden_username" autocomplete="username" style="display:none">
    
    <div class="mb-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-user"></i></span>
            </div>
            <input type="text" placeholder="Nombre de Usuario" name="username" value="{{ old('username') }}"
                   class="form-control @error('username') is-invalid @enderror" autocomplete="username" autofocus>
            @error('username')
                <small class="form-text invalid-feedback" role="alert">{{ $message }} </small>
            @enderror
        </div>
    </div>
                                    <div class="mb-4">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                            </div>
                                            <input type="password" placeholder="Contraseña" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror" autocomplete="current-password">
                                            @error('password')
                                                <small class="form-text invalid-feedback" role="alert">{{ $message }} </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group text-left mt-2">
                                        <div class="checkbox checkbox-primary d-inline">
                                            <input class="form-check-input" type="checkbox" name="remember" id="checkbox-fill-a1" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="cr" for="checkbox-fill-a1">
                                                {{ __('Recordarme') }}
                                            </label>
                                        </div>
                                    </div>
                                    <button class="btn btn-block btn-primary mb-4">Ingresar</button>
                                </form>
                                <p class="mb-2 text-muted">Forgot password? <a href="auth-reset-password.html" class="f-w-400">Reset</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{asset('theme/js/vendor-all.min.js')}}"></script>
        <script src="{{asset('theme/js/plugins/bootstrap.min.js')}}"></script>
        <script src="{{asset('theme/js/waves.min.js')}}"></script>
    </body>
</html>
