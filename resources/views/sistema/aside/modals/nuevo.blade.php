<div class="modal fade" id="nuevoAsideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('sistema.aside.store') }}" method="POST" id="formNuevoAside">
                @csrf
                <div class="modal-header bg-kenya text-white">
                    <h5 class="modal-title">Nuevo Filtro</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modelo*</label>
                            <select name="modelo_id" class="form-select" required>
                                @foreach($modelos as $modelo)
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
                            <button class="btn btn-outline-primary" type="button" id="agregarOpcionBtn">
                                <i class="fas fa-plus"></i> Añadir
                            </button>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3" id="opcionesContainer">
                            <!-- Aquí aparecerán los badges de las opciones -->
                        </div>
                        <!-- Campo oculto que almacenará las opciones como JSON -->
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
        // Array para almacenar las opciones temporalmente
        let opciones = [];

        // Botón para añadir opciones
        document.getElementById('agregarOpcionBtn').addEventListener('click', function() {
            const input = document.getElementById('nuevaOpcionInput');
            const opcionesContainer = document.getElementById('opcionesContainer');
            const opcion = input.value.trim();

            if(opcion !== '' && !opciones.includes(opcion)) {
                // Añadir al array
                opciones.push(opcion);

                // Actualizar el campo oculto
                document.getElementById('opcionesInput').value = JSON.stringify(opciones);

                // Crear y mostrar el badge
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary me-2 mb-2 p-2 d-flex align-items-center';
                badge.innerHTML = `
                    ${opcion}
                    <button type="button"
                            class="btn-close btn-close-white btn-sm ms-2 remove-option"
                            data-value="${opcion}"
                            aria-label="Eliminar opción"></button>
                `;
                opcionesContainer.appendChild(badge);

                // Limpiar el input
                input.value = '';
                input.focus();
            } else if(opciones.includes(opcion)) {
                alert('Esta opción ya fue agregada');
                input.value = '';
            }
        });

        // Eliminar opciones
        document.getElementById('opcionesContainer').addEventListener('click', function(e) {
            if(e.target.classList.contains('remove-option')) {
                const valueToRemove = e.target.getAttribute('data-value');
                const index = opciones.indexOf(valueToRemove);

                if(index !== -1) {
                    // Eliminar del array
                    opciones.splice(index, 1);

                    // Actualizar campo oculto
                    document.getElementById('opcionesInput').value = JSON.stringify(opciones);

                    // Eliminar visualmente
                    e.target.closest('.badge').remove();
                }
            }
        });

        // Validación antes de enviar
        document.getElementById('formNuevoAside').addEventListener('submit', function(e) {
            if(opciones.length === 0) {
                e.preventDefault();
                alert('Debes agregar al menos un subfiltro');
                document.getElementById('nuevaOpcionInput').focus();
            }
        });
    });
</script>
@endsection
