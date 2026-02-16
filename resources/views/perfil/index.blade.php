@extends('layouts.template')

@section('content')
<div class="pcoded-content" style="padding: 0px;">
    <!-- CONTENIDO -->
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-user"></i> Perfil</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="javascript:void(0)"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Perfil</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-servicio">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>PERFIL DE USUARIO</h5>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <ul class="list-unstyled card-option" style="display: contents;">
                                <li class="full-card"><a href="javascript:void(0)" class="windows-button"><span title="Maximizar"><i class="feather icon-maximize"></i> </span><span style="display:none"><i class="feather icon-minimize"></i> </span></a></li>
                                <li class="close-card"><a href="javascript:void(0)" class="windows-button" title="Cerrar"><i class="feather icon-x"></i> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('perfil.update') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label>Documento (DNI/RUC)</label>
                            <input type="text" name="dni" class="form-control" value="{{ old('dni', $user->dni) }}">
                            @error('dni')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Nombres</label>
                            <input type="text" name="nombres" class="form-control" value="{{ old('nombres', $user->nombres) }}" required>
                            @error('nombres')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Apellido Paterno</label>
                                    <input type="text" name="ape_paterno" class="form-control" value="{{ old('ape_paterno', $user->ape_paterno) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Apellido Materno</label>
                                    <input type="text" name="ape_materno" class="form-control" value="{{ old('ape_materno', $user->ape_materno) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Usuario (username)</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}">
                            @error('username')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Contraseña (dejar en blanco para conservar)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label>Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button class="btn btn-primary">Guardar cambios</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle con Popper -->

</div>
@endsection
