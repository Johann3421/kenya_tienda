@extends('layouts.template')
@section('app-name')
    <title>Grupo kenya - Aside</title>
@endsection

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-kenya text-white">
        <h5 class="mb-0">Administración de Filtros</h5>
    </div>
    <div class="card-body">
        <!-- Botón Nuevo Filtro - Versión corregida -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#nuevoAsideModal">
            <i class="fas fa-plus me-2"></i> Nuevo Filtro
        </button>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Modelo</th>
                        <th>Nombre Filtro</th>
                        <th>Subfiltros</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asides as $aside)
                    <tr>
                        <td>{{ $aside->id }}</td>
                        <td>{{ $aside->modelo->descripcion }}</td>
                        <td>{{ $aside->nombre_aside }}</td>
                        <td>
                            @if($aside->opciones)
                                <div class="d-flex flex-wrap gap-2" id="opciones-container-{{ $aside->id }}">
                                    @foreach($aside->opciones as $opcion)
                                    <span class="badge bg-secondary">{{ $opcion }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="text-end">
                            <!-- Botón Editar -->
                            <button type="button" class="btn btn-sm btn-warning mx-1" data-bs-toggle="modal"
                                data-bs-target="#editarAsideModal{{ $aside->id }}" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Botón Eliminar -->
                            <button type="button" class="btn btn-sm btn-danger mx-1" data-bs-toggle="modal"
                                data-bs-target="#eliminarAsideModal{{ $aside->id }}" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                            </button>

                            <!-- Botón Añadir Subfiltro -->
                            <button type="button" class="btn btn-sm btn-info mx-1" data-bs-toggle="modal"
                                data-bs-target="#agregarOpcionModal{{ $aside->id }}" title="Añadir Subfiltro">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Incluir Modales para cada aside -->
                    @include('sistema.aside.modals.editar', ['aside' => $aside])
                    @include('sistema.aside.modals.eliminar', ['aside' => $aside])
                    @include('sistema.aside.modals.agregar-opcion', ['aside' => $aside])
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nuevo Filtro -->
<div class="modal fade" id="nuevoAsideModal" tabindex="-1" aria-labelledby="nuevoAsideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('sistema.aside.store') }}" method="POST" id="formNuevoAside">
                @csrf
                <div class="modal-header bg-kenya text-white">
                    <h5 class="modal-title" id="nuevoAsideModalLabel">Nuevo Filtro</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modelo*</label>
                            <select name="modelo_id" class="form-select" required>
                                @foreach(\App\Modelo::where('activo', 'Si')->get() as $modelo)
                                <option value="{{ $modelo->id }}">{{ $modelo->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Filtro*</label>
                            <input type="text" name="nombre_aside" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subfiltros*</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Ej: Core i5, 16GB RAM" id="nuevaOpcionInput">
                            <button type="button" class="btn btn-outline-primary" id="agregarOpcionBtn">
                                <i class="fas fa-plus"></i> Añadir
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3" id="opcionesContainer"></div>
                        <input type="hidden" name="opciones" id="opcionesInput" value="[]" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-kenya">Guardar Filtro</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración para el modal de nuevo
        const opcionesNuevo = [];
        const opcionesContainerNuevo = document.getElementById('opcionesContainer');
        const opcionesInputNuevo = document.getElementById('opcionesInput');
        const nuevaOpcionInputNuevo = document.getElementById('nuevaOpcionInput');

        // Función para agregar opción
        function agregarOpcion() {
            const opcion = nuevaOpcionInputNuevo.value.trim();

            if(opcion !== '' && !opcionesNuevo.includes(opcion)) {
                // Agregar al array
                opcionesNuevo.push(opcion);

                // Actualizar campo oculto
                opcionesInputNuevo.value = JSON.stringify(opcionesNuevo);

                // Crear elemento visual
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary me-2 mb-2 p-2 d-flex align-items-center';
                badge.innerHTML = `
                    ${opcion}
                    <button type="button"
                            class="btn-close btn-close-white btn-sm ms-2 remove-option"
                            data-value="${opcion}"
                            aria-label="Eliminar opción"></button>
                `;
                opcionesContainerNuevo.appendChild(badge);

                // Limpiar input
                nuevaOpcionInputNuevo.value = '';
                return true;
            } else if(opcionesNuevo.includes(opcion)) {
                alert('Esta opción ya fue agregada');
                nuevaOpcionInputNuevo.value = '';
                return false;
            }
            return false;
        }

        // Evento para agregar opción al hacer click
        document.getElementById('agregarOpcionBtn').addEventListener('click', agregarOpcion);

        // Evento para agregar opción al presionar Enter
        nuevaOpcionInputNuevo.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                agregarOpcion();
            }
        });

        // Evento para eliminar opciones
        opcionesContainerNuevo.addEventListener('click', function(e) {
            if(e.target.classList.contains('remove-option')) {
                const valueToRemove = e.target.getAttribute('data-value');
                const index = opcionesNuevo.indexOf(valueToRemove);

                if(index !== -1) {
                    opcionesNuevo.splice(index, 1);
                    opcionesInputNuevo.value = JSON.stringify(opcionesNuevo);
                    e.target.closest('.badge').remove();
                }
            }
        });

        // Validación antes de enviar
        document.getElementById('formNuevoAside').addEventListener('submit', function(e) {
            if(opcionesNuevo.length === 0) {
                e.preventDefault();
                alert('Debes agregar al menos un subfiltro');
                nuevaOpcionInputNuevo.focus();
            }
        });

        // Inicializar modales de edición si existen
        @foreach($asides as $aside)
            @if(isset($aside->id))
            initEditModal({{ $aside->id }}, @json($aside->opciones ?? []));
            @endif
        @endforeach
    });

    // Función para inicializar modales de edición (mantenida para compatibilidad)
    function initEditModal(asideId, opcionesIniciales) {
        const opciones = [...opcionesIniciales];
        const opcionesContainer = document.getElementById(`opcionesContainer${asideId}`);
        const opcionesInput = document.getElementById(`opcionesInput${asideId}`);
        const nuevaOpcionInput = document.getElementById(`nuevaOpcionInput${asideId}`);

        document.querySelector(`button[data-aside-id="${asideId}"]`).addEventListener('click', function() {
            const opcion = nuevaOpcionInput.value.trim();

            if(opcion !== '' && !opciones.includes(opcion)) {
                opciones.push(opcion);
                opcionesInput.value = JSON.stringify(opciones);

                const badge = document.createElement('span');
                badge.className = 'badge bg-primary me-2 mb-2 p-2 d-flex align-items-center';
                badge.innerHTML = `
                    ${opcion}
                    <button type="button"
                            class="btn-close btn-close-white btn-sm ms-2 remove-option"
                            data-value="${opcion}"
                            data-aside-id="${asideId}"
                            aria-label="Eliminar opción"></button>
                `;
                opcionesContainer.appendChild(badge);
                nuevaOpcionInput.value = '';
            } else if(opciones.includes(opcion)) {
                alert('Esta opción ya fue agregada');
                nuevaOpcionInput.value = '';
            }
        });

        opcionesContainer.addEventListener('click', function(e) {
            if(e.target.classList.contains('remove-option')) {
                const valueToRemove = e.target.getAttribute('data-value');
                const index = opciones.indexOf(valueToRemove);

                if(index !== -1) {
                    opciones.splice(index, 1);
                    opcionesInput.value = JSON.stringify(opciones);
                    e.target.closest('.badge').remove();
                }
            }
        });
    }
</script>
@endsection
@endsection
