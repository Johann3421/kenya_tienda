@extends('layouts.template')

@section('app-name')
    <title>KENYA - Vistas 360 de Modelos</title>
@endsection

@section('content')
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-cube"></i> Vistas 360° para Modelos</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="{{ route('home') }}"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Modelos 360</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="top: 40px; position: inherit;">
        <div class="col-lg-12 col-md-12">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>¡Éxito!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 text-white"><i class="fas fa-upload"></i> Administrador de Vistas 360</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Instrucciones:</h6>
                        <ol class="mb-0 pl-3">
                            <li>Selecciona un modelo de la lista.</li>
                            <li>Sube las imágenes de la vista 360. <strong>Se recomienda subir 36 imágenes (una para cada 10° de rotación).</strong></li>
                            <li>Asegúrate de seleccionarlas todas a la vez (mantén presionado Ctrl o Shift al seleccionarlas).</li>
                            <li>El sistema las ordenará automáticamente por su nombre original.</li>
                        </ol>
                    </div>

                    <form action="{{ route('modelos360.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mt-4">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="modelo_id" style="font-weight: 600;">Seleccionar Modelo</label>
                                    <select name="modelo_id" id="modelo_id" class="form-control" required>
                                        <option value="">-- Elige un modelo --</option>
                                        @foreach($modelos as $modelo)
                                            <option value="{{ $modelo->id }}">{{ $modelo->descripcion }} {{ $modelo->has_360 ? '(Ya tiene vista 360)' : '' }}</option>
                                        @endforeach
                                    </select>
                                    @error('modelo_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="imagenes" style="font-weight: 600;">Imágenes (Múltiples)</label>
                                    <input type="file" name="imagenes[]" id="imagenes" class="form-control-file" multiple accept="image/*" required>
                                    @error('imagenes') <span class="text-danger small">{{ $message }}</span> @enderror
                                    @error('imagenes.*') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="submit" class="btn btn-primary btn-block mt-3" onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm\' role=\'status\' aria-hidden=\'true\'></span> Subiendo...'; this.form.submit(); this.disabled=true;">
                                    <i class="fas fa-cloud-upload-alt"></i> Subir
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4">

                    <h5 class="mb-3">Modelos con Vista 360° Activa</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Modelo</th>
                                    <th class="text-center">Estado 360</th>
                                    <th class="text-center">Cant. Imágenes</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $hasAny = false; @endphp
                                @foreach($modelos as $modelo)
                                    @if($modelo->has_360)
                                        @php $hasAny = true; @endphp
                                        <tr>
                                            <td>{{ $modelo->id }}</td>
                                            <td><strong>{{ $modelo->descripcion }}</strong></td>
                                            <td class="text-center"><span class="badge badge-success px-2 py-1"><i class="fas fa-check"></i> Activo</span></td>
                                            <td class="text-center">{{ $modelo->count_360 }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('modelos360.delete', $modelo->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar la vista 360 de este modelo?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar vistas 360"><i class="fas fa-trash"></i> Eliminar 360</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach

                                @if(!$hasAny)
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Aún no has subido vistas 360 para ningún modelo.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
