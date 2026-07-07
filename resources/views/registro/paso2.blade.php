@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
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
                            <input type="text" name="ruc" class="form-control" placeholder="Ingrese su RUC de 11 dígitos" required minlength="11" maxlength="11" style="border-radius: 8px;">
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" placeholder="nombre@empresa.com" required style="border-radius: 8px;">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block py-2" style="border-radius: 50px; font-weight: 600;">Validar Datos</button>
                    </form>

                    <!-- ponytail: simplification -->
                    <div class="mt-4 text-center text-muted small">
                        <p><strong>Nota ponytail:</strong> Cualquier RUC de 11 dígitos y correo válido será "aprobado" temporalmente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
