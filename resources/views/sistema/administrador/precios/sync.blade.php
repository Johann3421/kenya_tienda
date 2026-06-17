@extends('layouts.template')

@section('app-name')
    <title>KENYA - Sincronizar Precios Google Sheets</title>
@endsection

@section('content')
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-file-excel"></i> Sincronizar Precios (Google Sheets)</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="{{ url('/home') }}"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Precios</a></li>
                        <li class="breadcrumb-item"><a href="#!">Sincronización</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="top: 40px; position: inherit;">
        <div class="col-lg-8 col-md-10 mx-auto">
            
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>¡Éxito!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white"><i class="fas fa-sync-alt"></i> Actualizar precios masivamente</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Instrucciones:</h6>
                        <ol class="mb-0 pl-3">
                            <li>Abre tu documento de Google Sheets con los precios.</li>
                            <li>Asegúrate de que la primera fila contenga los encabezados (Ej: <strong>nro_parte</strong> y <strong>precio</strong>).</li>
                            <li>Ve a <strong>Archivo > Compartir > Publicar en la Web</strong>.</li>
                            <li>En la ventana emergente, selecciona la pestaña u hoja correspondiente, y en el segundo desplegable selecciona <strong>Valores separados por comas (.csv)</strong>.</li>
                            <li>Copia el enlace generado, pégalo en el campo de abajo y haz clic en Sincronizar.</li>
                        </ol>
                    </div>

                    <form action="{{ route('precios.sync') }}" method="POST">
                        @csrf
                        <div class="form-group mt-4">
                            <label for="csv_url" style="font-weight: 600;">Enlace de Google Sheets (Formato CSV)</label>
                            <input type="url" name="csv_url" id="csv_url" class="form-control form-control-lg @error('csv_url') is-invalid @enderror" placeholder="https://docs.google.com/spreadsheets/d/e/2PACX-1v.../pub?output=csv" value="{{ old('csv_url') }}" required>
                            @error('csv_url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-success btn-lg" onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm\' role=\'status\' aria-hidden=\'true\'></span> Procesando...'; this.form.submit(); this.disabled=true;">
                                <i class="fas fa-cloud-download-alt"></i> Iniciar Sincronización
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
