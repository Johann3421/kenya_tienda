<div class="modal fade" id="editarAsideModal{{ $aside->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('sistema.aside.update', $aside->id) }}" method="POST" id="formEditarAside{{ $aside->id }}">
                @csrf
                @method('PUT')
                <div class="modal-header bg-kenya text-white">
                    <h5 class="modal-title">Editar Filtro: {{ $aside->nombre_aside }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modelo*</label>
                            <select name="modelo_id" class="form-select" required>
                                @foreach(\App\Modelo::where('activo', 'Si')->get() as $modelo)
                                <option value="{{ $modelo->id }}" {{ $aside->modelo_id == $modelo->id ? 'selected' : '' }}>
                                    {{ $modelo->descripcion }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Filtro*</label>
                            <input type="text" name="nombre_aside" class="form-control" value="{{ $aside->nombre_aside }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subfiltros*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Ej: Core i5, 16GB RAM" id="nuevaOpcionInput{{ $aside->id }}">
                            <button type="button" class="btn btn-outline-primary agregar-opcion-btn" data-aside-id="{{ $aside->id }}">
                                <i class="fas fa-plus"></i> Añadir
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3" id="opcionesContainer{{ $aside->id }}">
                            @foreach($aside->opciones as $opcion)
                            <span class="badge bg-primary me-2 mb-2 p-2 d-flex align-items-center">
                                {{ $opcion }}
                                <button type="button" class="btn-close btn-close-white btn-sm ms-2 remove-option"
                                        data-value="{{ $opcion }}" data-aside-id="{{ $aside->id }}"></button>
                            </span>
                            @endforeach
                        </div>
                        <input type="hidden" name="opciones" id="opcionesInput{{ $aside->id }}" value="{{ json_encode($aside->opciones) }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-kenya">Actualizar Filtro</button>
                </div>
            </form>
        </div>
    </div>
</div>
