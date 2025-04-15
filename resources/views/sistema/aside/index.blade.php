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
            <!-- Botón para asignar filtros al producto -->
            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAsignarFiltros">
                <i class="fas fa-tags me-2"></i> Asignar Filtros a Producto
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
                        @foreach ($asides as $aside)
                            <tr>
                                <td>{{ $aside->id }}</td>
                                <td>{{ $aside->modelo->descripcion }}</td>
                                <td>{{ $aside->nombre_aside }}</td>
                                <td>
                                    @if ($aside->opciones)
                                        <div class="d-flex flex-wrap gap-2" id="opciones-container-{{ $aside->id }}">
                                            @foreach ($aside->opciones as $opcion)
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
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Modelo*</label>
                                <select name="modelo_id" class="form-select" required>
                                    @foreach (\App\Modelo::where('activo', 'Si')->get() as $modelo)
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
                                <input type="text" class="form-control" placeholder="Ej: Core i5, 16GB RAM"
                                    id="nuevaOpcionInput">
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

    @php
    use App\Producto;
    use App\Models\Aside;

    $productos = Producto::with(['modelo', 'filtros'])
        ->get()
        ->groupBy('modelo.descripcion');

    $asides = Aside::with(['modelo', 'productos'])->get();

    // Mapeo de nombres de modelo a IDs para el filtrado
    $modelosMap = App\Modelo::where('activo', 'Si')
        ->pluck('id', 'descripcion')
        ->toArray();
@endphp

<!-- Modal Asignar Filtros -->
<div class="modal fade" id="modalAsignarFiltros" tabindex="-1" aria-labelledby="modalAsignarFiltrosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('productos.asignar-filtros.generico') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Asignar Filtros al Producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Seleccionar Modelo</label>
                            <select class="form-select" id="modeloSelector">
                                <option value="">-- Todos --</option>
                                @foreach ($productos->keys() as $modelo)
                                    <option value="{{ $modelosMap[$modelo] ?? '' }}">{{ $modelo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Seleccionar Producto</label>
                            <select name="producto_id" class="form-select" required id="productoSelector">
                                @foreach ($productos as $modelo => $grupo)
                                    <optgroup label="{{ $modelo ?? 'Sin modelo' }}">
                                        @foreach ($grupo as $producto)
                                            <option value="{{ $producto->id }}" data-modelo="{{ $modelo }}" data-modelo-id="{{ $producto->modelo_id }}">{{ $producto->nombre }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="filtrosContainer">
                        @foreach ($asides as $aside)
                            @if ($aside->opciones)
                                <div class="card mb-3 filtro-card" data-modelo-id="{{ $aside->modelo_id }}">
                                    <div class="card-header bg-light">
                                        <strong>{{ $aside->nombre_aside }}</strong>
                                        <small class="text-muted float-end">{{ $aside->modelo->descripcion ?? 'Todos' }}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($aside->opciones as $opcion)
                                                <div class="col-md-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input filtro-option" type="checkbox"
                                                            name="filtros[{{ $aside->id }}][]"
                                                            value="{{ $opcion }}"
                                                            id="filtro_{{ $aside->id }}_{{ Str::slug($opcion) }}"
                                                            data-aside="{{ $aside->id }}"
                                                            data-opcion="{{ $opcion }}">
                                                        <label class="form-check-label"
                                                            for="filtro_{{ $aside->id }}_{{ Str::slug($opcion) }}">
                                                            {{ $opcion }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Filtros</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const filtrosAsignados = @json(
        Producto::with('filtros')->get()->mapWithKeys(function ($p) {
            return [$p->id => $p->filtros->groupBy('pivot.aside_id')->map->pluck('pivot.opcion')];
        }));

    document.addEventListener('DOMContentLoaded', () => {
        const productoSelector = document.getElementById('productoSelector');
        const modeloSelector = document.getElementById('modeloSelector');
        const filtrosContainer = document.getElementById('filtrosContainer');

        const checkAsignados = () => {
            const prodId = productoSelector.value;
            const asignados = filtrosAsignados[prodId] || {};

            document.querySelectorAll('.filtro-option').forEach(input => {
                const aside = input.dataset.aside;
                const opcion = input.dataset.opcion;
                input.checked = asignados[aside]?.includes(opcion) ?? false;
            });
        };

        const filtrarElementos = () => {
            const modeloIdSeleccionado = modeloSelector.value;

            // Filtrar productos
            Array.from(productoSelector.options).forEach(option => {
                if (!modeloIdSeleccionado || option.dataset.modeloId === modeloIdSeleccionado) {
                    option.hidden = false;
                } else {
                    option.hidden = true;
                }
            });

            // Filtrar filtros
            document.querySelectorAll('.filtro-card').forEach(card => {
                const cardModeloId = card.dataset.modeloId;
                if (!modeloIdSeleccionado || cardModeloId == modeloIdSeleccionado) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });

            // Seleccionar el primer producto visible y actualizar checks
            const visibleOptions = Array.from(productoSelector.options).filter(opt => !opt.hidden);
            if (visibleOptions.length > 0) {
                productoSelector.value = visibleOptions[0].value;
                checkAsignados();
            }
        };

        modeloSelector.addEventListener('change', filtrarElementos);
        productoSelector.addEventListener('change', checkAsignados);

        // Inicializar
        filtrarElementos();
    });
</script>

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

                if (opcion !== '' && !opcionesNuevo.includes(opcion)) {
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
                } else if (opcionesNuevo.includes(opcion)) {
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
                if (e.key === 'Enter') {
                    e.preventDefault();
                    agregarOpcion();
                }
            });

            // Evento para eliminar opciones
            opcionesContainerNuevo.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-option')) {
                    const valueToRemove = e.target.getAttribute('data-value');
                    const index = opcionesNuevo.indexOf(valueToRemove);

                    if (index !== -1) {
                        opcionesNuevo.splice(index, 1);
                        opcionesInputNuevo.value = JSON.stringify(opcionesNuevo);
                        e.target.closest('.badge').remove();
                    }
                }
            });

            // Validación antes de enviar
            document.getElementById('formNuevoAside').addEventListener('submit', function(e) {
                if (opcionesNuevo.length === 0) {
                    e.preventDefault();
                    alert('Debes agregar al menos un subfiltro');
                    nuevaOpcionInputNuevo.focus();
                }
            });

            // Inicializar modales de edición si existen
            @foreach ($asides as $aside)
                @if (isset($aside->id))
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

                if (opcion !== '' && !opciones.includes(opcion)) {
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
                } else if (opciones.includes(opcion)) {
                    alert('Esta opción ya fue agregada');
                    nuevaOpcionInput.value = '';
                }
            });

            opcionesContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-option')) {
                    const valueToRemove = e.target.getAttribute('data-value');
                    const index = opciones.indexOf(valueToRemove);

                    if (index !== -1) {
                        opciones.splice(index, 1);
                        opcionesInput.value = JSON.stringify(opciones);
                        e.target.closest('.badge').remove();
                    }
                }
            });
        }
    </script>
@endsection
