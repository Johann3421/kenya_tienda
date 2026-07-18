<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .modal-body-dynamic .card {
        border-radius: 6px;
    }
    .modal-body-dynamic .label-sm {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        text-transform: uppercase;
    }
</style>

<div class="modal-body-dynamic" style="background-color: #f1f4f6; padding: 15px; border-radius: 5px;">

    <!-- CARD 1: SELECCION DE CATEGORIA Y MARCA -->
    <div class="card shadow-sm mb-3 border-primary">
        <div class="card-header bg-white py-2 border-bottom-0">
            <h6 class="mb-0 text-primary font-weight-bold"><i class="fa fa-tag mr-1"></i> 1. Tipo de Producto y Marca</h6>
        </div>
        <div class="card-body pt-0 pb-3">
            <div class="form-row">
                <div class="form-group col-lg-6 mb-0">
                    <label for="categoria" class="label-sm text-muted">CATEGORÍA PRINCIPAL <span class="text-danger">*</span></label>
                    <select id="categoria" v-model="producto.categoria" class="form-control form-control-lg border-primary shadow-sm" style="font-size: 16px; font-weight: bold;" :class="[errors.categoria ? 'is-invalid' : '']" :readonly="loading">
                        <option value="">--- ¿Qué vas a registrar? ---</option>
                        <option v-for="cat in categoriasFiltradas" :value="cat.id">@{{ cat.nombre }}</option>
                    </select>
                    <small class="form-text error-color" v-if="errors.categoria">@{{ errors.categoria[0] }}</small>
                </div>
                <div class="form-group col-lg-6 mb-0">
                    <label for="modelo_id" class="label-sm text-muted">MODELO ASIGNADO <span class="text-danger">*</span></label>
                    <select id="modelo_id" v-model="producto.modelo_id" class="form-control form-control-lg shadow-sm" style="font-size: 15px;" :class="[errors.modelo_id ? 'border-error' : '']" :disabled="!producto.categoria">
                        <option value="">--- Seleccionar Modelo ---</option>
                        <option v-for="mod in modelosFiltradosForm" :value="mod.id">@{{ mod.descripcion }}</option>
                    </select>
                    <small class="form-text text-muted" v-if="!producto.categoria" style="font-size: 10px;">Selecciona una categoría primero</small>
                    <small class="form-text error-color" v-if="errors.modelo_id">@{{ errors.modelo_id[0] }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- WRAPPER FOR REST OF THE FORM (ONLY SHOWS IF CATEGORY IS SELECTED) -->
    <div v-if="producto.categoria" style="animation: fadeIn 0.4s ease-out;">
        
        <!-- CARD 2: INFORMACION BASICA -->
        <div class="card shadow-sm mb-3 border-0">
            <div class="card-header bg-white py-2 border-bottom">
                <h6 class="mb-0 font-weight-bold text-dark"><i class="fa fa-info-circle mr-1 text-info"></i> 2. Información General</h6>
            </div>
            <div class="card-body pb-1">
                <div class="form-row">
                    <div class="form-group col-lg-9">
                        <label for="nombre" class="label-sm text-muted">NOMBRE / TÍTULO DEL PRODUCTO <span class="text-danger">*</span></label>
                        <input type="text" id="nombre" v-model="producto.nombre" class="form-control fc-new font-weight-bold" :class="[errors.nombre ? 'is-invalid' : '']" :readonly="loading" placeholder="Ej: Laptop Lenovo ThinkPad E14...">
                        <small class="form-text error-color" v-if="errors.nombre">@{{ errors.nombre[0] }}</small>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="nro_parte" class="label-sm text-muted">NRO. PARTE (PN)</label>
                        <input type="text" id="nro_parte" v-model="producto.nro_parte" class="form-control fc-new" :readonly="loading">
                    </div>
                </div>

            </div>
        </div>

        <!-- CARD 3: ESPECIFICACIONES (DYNAMIC) -->
        <div class="card shadow-sm mb-3 border-0" :class="{'border-info': esPC || esMonitor || esToner}">
            <div class="card-header py-2 border-bottom" :class="(esPC || esMonitor || esToner) ? 'bg-info text-white' : 'bg-white text-dark'">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fa fa-microchip mr-1" :class="(esPC || esMonitor || esToner) ? '' : 'text-primary'"></i> 3. Especificaciones Técnicas 
                    <span class="badge badge-light text-info ml-2">Campos Dinámicos</span>
                </h6>
            </div>
            <div class="card-body pb-3">
                <div class="row mb-2">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="agregarEspecificacion()">
                            <i class="fa fa-plus"></i> Agregar Campo
                        </button>
                    </div>
                </div>

                {{-- LOOP DINÁMICO DE ESPECIFICACIONES --}}
                <div v-if="producto.especificaciones_raw && producto.especificaciones_raw.length > 0">
                    <div class="form-row align-items-end mb-2 pb-2 border-bottom" v-for="(spec, index) in producto.especificaciones_raw" :key="index">
                        <div class="form-group col-lg-4 mb-0">
                            <label class="label-sm text-muted" v-if="index === 0">NOMBRE DEL CAMPO</label>
                            <input type="text" v-model="spec.campo" class="form-control fc-new font-weight-bold" placeholder="Ej: Procesador, RAM, Resolución..." :readonly="loading">
                        </div>
                        <div class="form-group col-lg-7 mb-0">
                            <label class="label-sm text-muted" v-if="index === 0">VALOR</label>
                            <input type="text" v-model="spec.descripcion" class="form-control fc-new" placeholder="Valor de la especificación..." :readonly="loading">
                        </div>
                        <div class="form-group col-lg-1 mb-0 text-center">
                            <button type="button" class="btn btn-sm btn-danger mt-4" @click="eliminarEspecificacion(index)" title="Eliminar campo">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-4 text-muted">
                    <i class="fa fa-list-alt fa-2x mb-2"></i>
                    <p class="mb-0">No hay especificaciones agregadas.</p>
                    <small>Haz clic en "Agregar Campo" para definir características técnicas.</small>
                </div>


            </div>
        </div>



        <!-- CARD 5: IMAGENES Y ARCHIVOS -->
        <div class="card shadow-sm mb-3 border-0">
             <div class="card-header bg-white py-2 border-bottom">
                <h6 class="mb-0 font-weight-bold text-dark"><i class="fa fa-image mr-1 text-warning"></i> 5. Multimedia e Imágenes</h6>
            </div>
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-lg-12 mb-4">
                        <label class="label-sm text-muted d-block font-weight-bold">FICHA TÉCNICA (PDF)</label>
                        <div class="d-flex align-items-center p-2 rounded" style="background-color: #fafafa; border: 1px dashed #ccc;">
                            <label class="btn btn-primary btn-sm mb-0 mr-3" for="file" title="Seleccionar PDF" style="cursor:pointer;">
                                <i class="fa fa-upload mr-1"></i> Subir PDF
                                <input type="file" id="file" style="display: none;" v-on:change="changePdf($event)" accept="application/pdf">
                            </label>
                            <a v-if="producto.pdf_ficha" :href="getPdfUrl(producto.pdf_ficha)" target="_blank" class="btn btn-outline-danger btn-sm mb-0 mr-2">
                                <i class="fa fa-file-pdf-o mr-1"></i> Ver PDF Actual
                            </a>
                            <span v-if="producto.pdf_ficha" class="text-success small font-weight-bold"><i class="fa fa-check-circle"></i> Ficha Adjunta</span>
                            <span v-else class="text-muted small"><i class="fa fa-info-circle"></i> No hay ficha técnica asociada al producto.</span>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <label class="label-sm text-muted d-block font-weight-bold mb-2">GALERÍA DE IMÁGENES</label>
                        <div class="d-flex flex-wrap" style="gap: 15px;">
                            @for ($i = 1; $i <= 5; $i++)
                            <div class="text-center">
                                <label class="image_show d-block rounded overflow-hidden" style="width: 100px; height: 100px; border: 1px solid #ddd; cursor:pointer;" for="file_edit_{{ $i }}" title="Cambiar imagen {{ $i }}" v-if="producto.imagen_{{ $i }}">
                                    <img id="show_image_{{ $i }}" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                                    <input type="file" id="file_edit_{{ $i }}" style="display: none;" v-on:change="changeImagen($event, {{ $i }})" accept="image/*">
                                </label>
                                <label class="image d-flex flex-column justify-content-center align-items-center rounded bg-light" style="width: 100px; height: 100px; border: 1px dashed #bbb; cursor:pointer; color: #888;" for="file_{{ $i }}" title="Agregar imagen {{ $i }}" v-else>
                                    <i class="fa fa-camera fa-2x mb-1"></i>
                                    <span style="font-size: 10px;">IMG {{ $i }}</span>
                                    <input type="file" id="file_{{ $i }}" style="display: none;" v-on:change="changeImagen($event, {{ $i }})" accept="image/*">
                                </label>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
                
                <hr class="mt-4">
                {{-- Switch: Ficha editada localmente --}}
                <div class="alert alert-warning py-2 px-3 mb-0 d-flex align-items-center border-warning" style="background-color: #fff9e6;">
                    <div class="custom-control custom-switch mb-0">
                        <input type="checkbox" class="custom-control-input" id="ficha_editada_localmente_form" v-model="producto.ficha_editada_localmente">
                        <label class="custom-control-label font-weight-bold text-dark" for="ficha_editada_localmente_form">
                            Ficha Editada Localmente (Excluir de la API de Catálogo Oficial)
                        </label>
                    </div>
                    <small class="ml-3 text-muted" style="line-height: 1.2;">Al activar, la API <strong>no sobreescribirá</strong> ni borrará este producto en la próxima sincronización con Perú Compras.</small>
                </div>
            </div>
        </div>

    </div>
    
    <!-- PLACEHOLDER WHEN NO CATEGORY IS SELECTED -->
    <div v-else class="text-center py-5 text-muted" style="animation: fadeIn 0.3s ease-out;">
        <i class="fa fa-hand-pointer-o fa-3x mb-3 text-primary opacity-50"></i>
        <h5 class="font-weight-bold text-secondary">Configuración Dinámica</h5>
        <p class="mb-0">Para comenzar, selecciona la <strong>Categoría Principal</strong> en la parte superior.</p>
        <p class="small">El formulario se adaptará para mostrar solo los campos necesarios.</p>
    </div>
</div>
