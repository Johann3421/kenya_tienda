@extends('layouts.template')
@section('app-name')
    <title>Grupo kenya - Aside</title>
@endsection
@section('css')
    <style>
    /* Estilos existentes del producto-selector (se mantienen igual) */
    .producto-selector-wrapper {
        scrollbar-width: thin;
        scrollbar-color: #adb5bd #f8f9fa;
    }

    .producto-selector-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .producto-selector-wrapper::-webkit-scrollbar-track {
        background: #f8f9fa;
        border-radius: 4px;
    }

    .producto-selector-wrapper::-webkit-scrollbar-thumb {
        background-color: #adb5bd;
        border-radius: 4px;
    }

    .producto-selector {
        border: none !important;
    }

    .producto-selector optgroup {
        font-weight: bold;
        font-style: normal;
        color: #495057;
        background-color: #e9ecef;
        padding: 0.25rem 0.5rem;
    }

    .producto-selector option {
        padding: 0.35rem 0.7rem;
        border-bottom: 1px solid #e9ecef;
    }

    .producto-selector option:hover {
        background-color: #e9ecef !important;
    }

    .producto-selector option {
        transition: all 0.2s ease;
    }

    .producto-selector option:checked {
        background-color: #4361ee !important;
        color: white !important;
        font-weight: 600;
        position: relative;
    }

    .producto-selector option:checked::after {
        content: "✓";
        position: absolute;
        right: 10px;
    }

    .producto-selector option:hover:not(:checked) {
        background-color: #e9ecef !important;
    }

    .producto-selector option:focus-visible {
        outline: 2px solid #4361ee;
        outline-offset: -2px;
    }

    /* Nuevos estilos específicos para el modalAsignarFiltros */
    #modalAsignarFiltros .modal-content {
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    #modalAsignarFiltros .step {
        transition: all 0.3s ease;
        padding: 15px;
        border-radius: 8px;
        background-color: #f8f9fa;
        margin-bottom: 20px;
    }

    #modalAsignarFiltros .card {
        transition: transform 0.2s;
        cursor: pointer;
        height: 100%;
        margin-bottom: 15px;
    }

    #modalAsignarFiltros .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    #modalAsignarFiltros .card-header {
        font-weight: 600;
        background-color: rgba(0,0,0,0.03);
    }

    #modalAsignarFiltros #productosTable tbody tr {
        transition: background 0.2s;
    }

    #modalAsignarFiltros #productosTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    #modalAsignarFiltros .product-checkbox {
        cursor: pointer;
    }

    #modalAsignarFiltros .pagination {
        margin-bottom: 0;
    }

    #modalAsignarFiltros #btnNextStep,
    #modalAsignarFiltros #btnPrevStep {
        min-width: 120px;
    }

    #modalAsignarFiltros #btnSubmit {
        min-width: 150px;
    }

    /* Efectos específicos para los pasos */
    #modalAsignarFiltros .step-active {
        border-left: 4px solid #4361ee;
        background-color: #f1f3ff;
    }

    /* Optimización para inputs y botones */
    #modalAsignarFiltros .form-select,
    #modalAsignarFiltros .form-control {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    #modalAsignarFiltros .btn {
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    #modalAsignarFiltros .btn:active:after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 5px;
        height: 5px;
        background: rgba(255, 255, 255, 0.5);
        opacity: 0;
        border-radius: 100%;
        transform: scale(1, 1) translate(-50%);
        transform-origin: 50% 50%;
    }

    #modalAsignarFiltros .btn:focus:not(:active)::after {
        animation: ripple 0.6s ease-out;
    }

    @keyframes ripple {
        0% {
            transform: scale(0, 0);
            opacity: 0.5;
        }
        100% {
            transform: scale(20, 20);
            opacity: 0;
        }
    }

    /* Responsive específico para el modal */
    @media (max-width: 1200px) {
        #modalAsignarFiltros .modal-dialog {
            margin: 1rem auto;
        }
    }

    @media (max-width: 768px) {
        #modalAsignarFiltros .modal-dialog {
            margin: 0.5rem auto;
        }

        #modalAsignarFiltros .step {
            padding: 10px;
        }

        #modalAsignarFiltros .btn {
            min-width: auto !important;
            padding: 0.375rem 0.75rem;
        }
    }
</style>
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
                                    <!-- Botón Duplicar Filtro -->
                                    <button type="button" class="btn btn-sm btn-secondary mx-1" data-bs-toggle="modal"
                                        data-bs-target="#duplicarAsideModal" title="Duplicar Filtro">
                                        <i class="fas fa-copy"></i>
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
    <!-- Modal Duplicar Filtro -->
    <div class="modal fade" id="duplicarAsideModal" tabindex="-1" aria-labelledby="duplicarAsideModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="duplicarAsideForm" autocomplete="off">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="duplicarAsideModalLabel">Duplicar Filtro</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Selecciona el filtro a duplicar:</label>
                        <select class="form-select mb-3" name="aside_id" id="selectAsideDuplicar" required>
                            <option value="">-- Selecciona un filtro --</option>
                            @foreach ($asides as $aside)
                                <option value="{{ $aside->id }}">
                                    [{{ $aside->modelo->descripcion ?? 'Sin modelo' }}] {{ $aside->nombre_aside }}
                                </option>
                            @endforeach
                        </select>
                        <div id="duplicarConfirmMsg" class="alert alert-info d-none">
                            ¿Seguro que deseas duplicar este filtro? Se creará una copia con el mismo nombre y subfiltros.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-secondary" id="btnConfirmarDuplicar" disabled>
                            <i class="fas fa-copy"></i> Duplicar
                        </button>
                    </div>
                </form>
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
        $modelosMap = App\Modelo::where('activo', 'Si')->pluck('id', 'descripcion')->toArray();
    @endphp

    <!-- Modal Asignar Filtros -->
    <div class="modal fade" id="modalAsignarFiltros" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('productos.asignar-filtros.generico') }}" method="POST" id="filtrosForm">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Asignación Masiva de Filtros</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Paso 1: Selección de Modelo -->
                        <div class="step" id="step1">
                            <h5 class="mb-3"><i class="fas fa-filter me-2"></i> Paso 1: Selecciona un Modelo</h5>
                            {{-- Paso 1: Selección de Modelo --}}
                            <select class="form-select mb-3" id="selectorModelo" required>
                                <option value="">-- Seleccionar Modelo --</option>
                                @foreach (\App\Modelo::where('activo', 'Si')->get() as $modelo)
                                    <option value="{{ $modelo->id }}">{{ $modelo->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Paso 2: Selección de Filtros -->
                        <div class="step d-none" id="step2">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><i class="fas fa-tags me-2"></i> Paso 2: Configura los Filtros</h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2"
                                        id="btnSelectAllFilters">
                                        <i class="fas fa-check-square me-1"></i> Todos
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        id="btnDeselectAllFilters">
                                        <i class="fas fa-times-circle me-1"></i> Ninguno
                                    </button>
                                </div>
                            </div>

                            <div class="row" id="filtrosContainer">
                                <!-- Filtros se cargarán dinámicamente -->
                            </div>
                        </div>

                        <!-- Paso 3: Selección de Productos -->
                        <div class="step d-none" id="step3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5><i class="fas fa-boxes me-2"></i> Paso 3: Selecciona Productos</h5>
                                <div class="input-group" style="width: 300px;">
                                    <input type="text" class="form-control" id="buscarProducto"
                                        placeholder="Buscar producto...">
                                    <button class="btn btn-outline-secondary" type="button" id="btnBuscarProducto">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="productosTable">
                                    <thead>
                                        <tr>
                                            <th width="50px">
                                                <input type="checkbox" id="selectAllProducts">
                                            </th>
                                            <th>Producto</th>
                                            <th>Especificaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Productos se cargarán dinámicamente -->
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination pagination-sm" id="pagination">
                                            <!-- Paginación se generará dinámicamente -->
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btnPrevStep" disabled>Anterior</button>
                        <button type="button" class="btn btn-primary" id="btnNextStep">Siguiente</button>
                        <button type="submit" class="btn btn-success d-none" id="btnSubmit">Guardar Asignación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-toggle-collapse').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const target = btn.getAttribute('data-target');
                    const collapseEl = document.querySelector(target);

                    if (!collapseEl._bsCollapse) {
                        collapseEl._bsCollapse = new bootstrap.Collapse(collapseEl, {
                            toggle: false
                        });
                    }
                    const isShown = collapseEl.classList.contains('show');
                    if (isShown) {
                        collapseEl._bsCollapse.hide();
                        btn.textContent = 'Ver';
                    } else {
                        collapseEl._bsCollapse.show();
                        btn.textContent = 'Ocultar';
                    }
                });
            });

            /*********************
             * SECCIÓN DUPLICAR *
             *********************/
            const selectAsideDuplicar = document.getElementById('selectAsideDuplicar');
            const duplicarConfirmMsg = document.getElementById('duplicarConfirmMsg');
            const btnConfirmarDuplicar = document.getElementById('btnConfirmarDuplicar');
            const duplicarAsideForm = document.getElementById('duplicarAsideForm');
            let selectedAside = '';

            if (selectAsideDuplicar && duplicarAsideForm) {
                selectAsideDuplicar.addEventListener('change', function() {
                    selectedAside = this.value;
                    if (selectedAside) {
                        duplicarConfirmMsg.classList.remove('d-none');
                        btnConfirmarDuplicar.disabled = false;
                    } else {
                        duplicarConfirmMsg.classList.add('d-none');
                        btnConfirmarDuplicar.disabled = true;
                    }
                });

                duplicarAsideForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!selectedAside) return;

                    btnConfirmarDuplicar.disabled = true;
                    btnConfirmarDuplicar.innerHTML =
                        '<span class="spinner-border spinner-border-sm"></span> Duplicando...';

                    fetch("{{ route('sistema.aside.duplicar') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                aside_id: selectedAside
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                btnConfirmarDuplicar.innerHTML =
                                    '<i class="fas fa-check"></i> ¡Duplicado!';
                                setTimeout(() => window.location.reload(), 800);
                            } else {
                                btnConfirmarDuplicar.innerHTML = '<i class="fas fa-copy"></i> Duplicar';
                                btnConfirmarDuplicar.disabled = false;
                                alert('No se pudo duplicar el filtro.');
                            }
                        })
                        .catch(() => {
                            btnConfirmarDuplicar.innerHTML = '<i class="fas fa-copy"></i> Duplicar';
                            btnConfirmarDuplicar.disabled = false;
                            alert('Error al duplicar el filtro.');
                        });
                });
            }

            /**************************************
             * 2. SECCIÓN ASIGNACIÓN MASIVA (WIZARD)
             **************************************/
            const modalAsignar = document.getElementById('modalAsignarFiltros');
            if (modalAsignar) {
                // Variables de estado
                let currentStep = 1;
                let selectedModel = null;
                let selectedFilters = {};
                let selectedProducts = [];
                const productsPerPage = 10;
                let currentPage = 1;
                let allProducts = [];

                // Inicializar modal
                modalAsignar.addEventListener('shown.bs.modal', resetWizard);

                // Navegación por pasos
                document.getElementById('btnNextStep')?.addEventListener('click', nextStep);
                document.getElementById('btnPrevStep')?.addEventListener('click', prevStep);

                // Selección de modelo
                document.getElementById('selectorModelo')?.addEventListener('change', function() {
                    selectedModel = this.value;
                    if (selectedModel && currentStep === 1) {
                        nextStep();
                    }
                });

                // Buscar productos
                document.getElementById('btnBuscarProducto')?.addEventListener('click', function() {
                    loadProducts(1);
                });
                document.getElementById('buscarProducto')?.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') loadProducts(1);
                });

                // Selección masiva de productos
                document.getElementById('selectAllProducts')?.addEventListener('change', function() {
                    document.querySelectorAll('.product-checkbox').forEach(cb => {
                        cb.checked = this.checked;
                    });
                });

                // Envío del formulario de asignación

document.getElementById('filtrosForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

// Validación
if (selectedProducts.length === 0) {
    alert('Selecciona al menos un producto');
    return;
}
const filterCheckboxes = document.querySelectorAll('.filter-option:checked');
if (filterCheckboxes.length === 0) {
    alert('Selecciona al menos un filtro');
    return;
}

// Eliminar inputs ocultos previos
this.querySelectorAll('input[type="hidden"][name^="productos["]').forEach(el => el.remove());

// Agrupar filtros seleccionados
const filtrosPorProducto = {};
selectedProducts.forEach(prodId => {
    filtrosPorProducto[prodId] = {};
    filterCheckboxes.forEach(cb => {
        const asideId = cb.dataset.aside;
        const opcion = cb.dataset.opcion;
        if (!filtrosPorProducto[prodId][asideId]) filtrosPorProducto[prodId][asideId] = [];
        filtrosPorProducto[prodId][asideId].push(opcion);
    });
});

// Crear un input oculto por producto
Object.keys(filtrosPorProducto).forEach(prodId => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = `productos[${prodId}]`;
    input.value = JSON.stringify(filtrosPorProducto[prodId]);
    this.appendChild(input);
});

this.submit();
});

                // Funciones auxiliares del wizard
                function nextStep() {
                    if (validateStep(currentStep)) {
                        currentStep++;
                        updateWizard();
                    }
                }

                function prevStep() {
                    currentStep--;
                    updateWizard();
                }

                function validateStep(step) {
                    switch (step) {
                        case 1:
                            if (!selectedModel) {
                                alert('Selecciona un modelo primero');
                                return false;
                            }
                            return true;
                        case 2:
                            const hasFilters = document.querySelectorAll('.filter-option:checked').length > 0;
                            if (!hasFilters) {
                                alert('Selecciona al menos un filtro');
                                return false;
                            }
                            return true;
                        default:
                            return true;
                    }
                }

                function updateWizard() {
                    // Ocultar todos los pasos
                    document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
                    // Mostrar paso actual
                    document.getElementById(`step${currentStep}`).classList.remove('d-none');
                    // Actualizar botones
                    document.getElementById('btnPrevStep').disabled = currentStep === 1;
                    document.getElementById('btnNextStep').classList.toggle('d-none', currentStep === 3);
                    document.getElementById('btnSubmit').classList.toggle('d-none', currentStep !== 3);
                    // Cargar datos según el paso
                    if (currentStep === 2) loadFilters();
                    if (currentStep === 3) loadProducts();
                }

                // Cargar filtros según modelo
                function loadFilters() {
                    const container = document.getElementById('filtrosContainer');
                    container.innerHTML = '';
                    const asides = @json($asides);
                    const filteredAsides = asides.filter(a => a.modelo_id == selectedModel);
                    if (!filteredAsides.length) {
                        container.innerHTML =
                            '<div class="alert alert-warning">No hay filtros para este modelo.</div>';
                        return;
                    }
                    filteredAsides.forEach(aside => {
                        if (aside.opciones?.length) {
                            const card = document.createElement('div');
                            card.className = 'col-md-6 mb-3';
                            card.innerHTML = `
                        <div class="card h-100">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <strong>${aside.nombre_aside}</strong>
                                <small class="badge bg-info">${aside.modelo?.descripcion || 'Sin modelo'}</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    ${aside.opciones.map(opt => `
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input filter-option" type="checkbox"
                                                        data-aside="${aside.id}" data-opcion="${opt}"
                                                        id="filtro_${aside.id}_${opt.replace(/\s+/g, '_')}">
                                                    <label class="form-check-label" for="filtro_${aside.id}_${opt.replace(/\s+/g, '_')}">
                                                        ${opt}
                                                    </label>
                                                </div>
                                            </div>
                                        `).join('')}
                                </div>
                            </div>
                        </div>
                    `;
                            container.appendChild(card);
                        }
                    });
                }

                // Cargar productos según modelo y búsqueda
                function loadProducts(page = 1) {
                    currentPage = page;
                    const searchTerm = document.getElementById('buscarProducto').value.toLowerCase().trim();
                    const productos = @json($productos);
                    let modelProducts = [];
                    Object.values(productos).forEach(arr => {
                        arr.forEach(prod => {
                            if (prod.modelo_id == selectedModel) modelProducts.push(prod);
                        });
                    });
                    // Búsqueda
                    const filteredProducts = searchTerm ?
                        modelProducts.filter(p => {
                            const fields = [
                                p.nombre || '',
                                p.procesador || '',
                                p.ram || '',
                                p.almacenamiento || ''
                            ].join(' ').toLowerCase();
                            return searchTerm.split(' ').every(word => fields.includes(word));
                        }) :
                        modelProducts;
                    // Paginación
                    const totalPages = Math.ceil(filteredProducts.length / productsPerPage);
                    const paginated = filteredProducts.slice(
                        (page - 1) * productsPerPage,
                        page * productsPerPage
                    );
                    // Renderizar productos
                    // ...dentro de loadProducts()...
                    const tbody = document.querySelector('#productosTable tbody');
tbody.innerHTML = '';
paginated.forEach(prod => {
    const checked = selectedProducts.includes(prod.id) ? 'checked' : '';
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="checkbox" class="product-checkbox" value="${prod.id}" ${checked}></td>
        <td>${prod.nombre}</td>
        <td>
            ${[
                prod.procesador ? 'Proc: ' + prod.procesador : '',
                prod.ram ? 'RAM: ' + prod.ram : '',
                prod.almacenamiento ? 'Alm: ' + prod.almacenamiento : ''
            ].filter(Boolean).join(' | ')}
        </td>
    `;
    tbody.appendChild(row);
});

// --- NUEVO BLOQUE ROBUSTO DE SINCRONIZACIÓN ---
function syncSelectedProducts() {
    // Lee todos los checkboxes de todas las páginas (solo los visibles)
    const allCheckboxes = document.querySelectorAll('.product-checkbox');
    selectedProducts = Array.from(allCheckboxes)
        .filter(cb => cb.checked)
        .map(cb => Number(cb.value));
}

// Evento para checkboxes individuales
tbody.querySelectorAll('.product-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        syncSelectedProducts();
        // Actualiza el estado del checkbox "Seleccionar todos"
        const allChecked = Array.from(tbody.querySelectorAll('.product-checkbox')).every(cb => cb.checked);
        document.getElementById('selectAllProducts').checked = allChecked;
    });
});

// Evento para "Seleccionar todos"
document.getElementById('selectAllProducts').onchange = function() {
    tbody.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
    syncSelectedProducts();
};

                    // Delegación de eventos para checkboxes individuales
                    tbody.onclick = function(e) {
                        if (e.target.classList.contains('product-checkbox')) {
                            const id = Number(e.target.value);
                            if (e.target.checked) {
                                if (!selectedProducts.includes(id)) selectedProducts.push(id);
                            } else {
                                selectedProducts = selectedProducts.filter(pid => pid !== id);
                            }
                            // Actualiza el estado del checkbox "Seleccionar todos"
                            const allChecked = paginated.length > 0 && paginated.every(prod => selectedProducts
                                .includes(prod.id));
                            document.getElementById('selectAllProducts').checked = allChecked;
                        }
                    };

                    // ...dentro de loadProducts()...
                    document.getElementById('selectAllProducts').onchange = function() {
                        tbody.querySelectorAll('.product-checkbox').forEach(cb => {
                            cb.checked = this.checked;
                            const id = Number(cb.value);
                            if (this.checked) {
                                if (!selectedProducts.includes(id)) selectedProducts.push(id);
                            } else {
                                selectedProducts = selectedProducts.filter(pid => pid !== id);
                            }
                        });
                    };

                    // Renderizar paginación
                    renderPagination(totalPages, page);
                }

                // Renderizar paginación
                function renderPagination(totalPages, currentPage) {
                    const pagination = document.getElementById('pagination');
                    pagination.innerHTML = '';
                    if (totalPages <= 1) return;
                    // Botón Anterior
                    const prevLi = document.createElement('li');
                    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                    prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">Anterior</a>`;
                    pagination.appendChild(prevLi);
                    // Páginas
                    const startPage = Math.max(1, currentPage - 2);
                    const endPage = Math.min(totalPages, startPage + 4);
                    for (let i = startPage; i <= endPage; i++) {
                        const pageLi = document.createElement('li');
                        pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                        pageLi.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                        pagination.appendChild(pageLi);
                    }
                    // Botón Siguiente
                    const nextLi = document.createElement('li');
                    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                    nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">Siguiente</a>`;
                    pagination.appendChild(nextLi);
                    // Eventos de paginación
                    pagination.querySelectorAll('.page-link').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            loadProducts(parseInt(this.dataset.page));
                        });
                    });
                }

                // Resetear wizard al abrir modal
                function resetWizard() {
                    currentStep = 1;
                    selectedModel = null;
                    selectedFilters = {};
                    selectedProducts = [];
                    currentPage = 1;
                    document.getElementById('selectorModelo').value = '';
                    document.getElementById('buscarProducto').value = '';
                    document.getElementById('filtrosContainer').innerHTML = '';
                    document.querySelector('#productosTable tbody').innerHTML = '';
                    document.getElementById('pagination').innerHTML = '';
                    updateWizard();
                }
            }

            /********************
             * SECCIÓN OPCIONES *
             ********************/
            const opcionesContainerNuevo = document.getElementById('opcionesContainer');
            const opcionesInputNuevo = document.getElementById('opcionesInput');
            const nuevaOpcionInputNuevo = document.getElementById('nuevaOpcionInput');

            if (opcionesContainerNuevo && opcionesInputNuevo && nuevaOpcionInputNuevo) {
                const opcionesNuevo = [];

                function agregarOpcion() {
                    const opcion = nuevaOpcionInputNuevo.value.trim();

                    if (opcion !== '' && !opcionesNuevo.includes(opcion)) {
                        opcionesNuevo.push(opcion);
                        opcionesInputNuevo.value = JSON.stringify(opcionesNuevo);

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
                        nuevaOpcionInputNuevo.value = '';
                        return true;
                    } else if (opcionesNuevo.includes(opcion)) {
                        alert('Esta opción ya fue agregada');
                        nuevaOpcionInputNuevo.value = '';
                        return false;
                    }
                    return false;
                }

                document.getElementById('agregarOpcionBtn').addEventListener('click', agregarOpcion);

                nuevaOpcionInputNuevo.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        agregarOpcion();
                    }
                });

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

                document.getElementById('formNuevoAside').addEventListener('submit', function(e) {
                    if (opcionesNuevo.length === 0) {
                        e.preventDefault();
                        alert('Debes agregar al menos un subfiltro');
                        nuevaOpcionInputNuevo.focus();
                    }
                });

                // Inicializar modales de edición
                @foreach ($asides as $aside)
                    @if (isset($aside->id))
                        initEditModal({{ $aside->id }}, @json($aside->opciones ?? []));
                    @endif
                @endforeach
            }

            function initEditModal(asideId, opcionesIniciales) {
                const opcionesContainer = document.getElementById(`opcionesContainer${asideId}`);
                const opcionesInput = document.getElementById(`opcionesInput${asideId}`);
                const nuevaOpcionInput = document.getElementById(`nuevaOpcionInput${asideId}`);

                if (opcionesContainer && opcionesInput && nuevaOpcionInput) {
                    const opciones = [...opcionesIniciales];

                    document.querySelector(`button[data-aside-id="${asideId}"]`).addEventListener('click',
                        function() {
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
            }
        });
    </script>
@endsection
