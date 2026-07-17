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
                        <option v-for="cat in listaCategorias" :value="cat.id">@{{ cat.nombre }}</option>
                    </select>
                    <small class="form-text error-color" v-if="errors.categoria">@{{ errors.categoria[0] }}</small>
                </div>
                <div class="form-group col-lg-6 mb-0">
                    <label for="marca" class="label-sm text-muted">MARCA <span class="text-danger">*</span></label>
                    <select id="marca" v-model="producto.marca" class="form-control form-control-lg shadow-sm" style="font-size: 15px;" :class="[errors.marca ? 'is-invalid' : '']" :readonly="loading">
                        <option value="">--- Selecciona la Marca ---</option>
                        <option v-for="marca in listaMarcas" :value="marca.id">@{{ marca.nombre }}</option>
                    </select>
                    <small class="form-text error-color" v-if="errors.marca">@{{ errors.marca[0] }}</small>
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
                    <div class="form-group col-lg-6">
                        <label for="nombre" class="label-sm text-muted">NOMBRE DEL PRODUCTO <span class="text-danger">*</span></label>
                        <input type="text" id="nombre" v-model="producto.nombre" class="form-control fc-new font-weight-bold" :class="[errors.nombre ? 'is-invalid' : '']" :readonly="loading" placeholder="Ej: Laptop Lenovo ThinkPad E14...">
                        <small class="form-text error-color" v-if="errors.nombre">@{{ errors.nombre[0] }}</small>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="nombre_secundario" class="label-sm text-muted">NOMBRE SECUNDARIO / COMERCIAL</label>
                        <input type="text" id="nombre_secundario" v-model="producto.nombre_secundario" class="form-control fc-new" :class="[errors.nombre_secundario ? 'is-invalid' : '']" :readonly="loading">
                        <small class="form-text error-color" v-if="errors.nombre_secundario">@{{ errors.nombre_secundario[0] }}</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-3">
                        <label for="modelo_id" class="label-sm text-muted">MODELO ASIGNADO <span class="text-danger">*</span></label>
                        <select id="modelo_id" v-model="producto.modelo_id" class="form-control fc-new" :class="[errors.modelo_id ? 'border-error' : '']">
                            @foreach ($modelos as $mod)
                                <option value="{{ $mod->id }}">{{ $mod['descripcion'] }}</option>
                            @endforeach
                        </select>
                        <small class="form-text error-color" v-if="errors.modelo">@{{ errors.modelo[0] }}</small>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="nro_parte" class="label-sm text-muted">NRO. PARTE (PN)</label>
                        <input type="text" id="nro_parte" v-model="producto.nro_parte" class="form-control fc-new font-weight-bold" :class="[errors.nro_parte ? 'is-invalid' : '']" :readonly="loading">
                        <small class="form-text error-color" v-if="errors.nro_parte">@{{ errors.nro_parte[0] }}</small>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="descripcion" class="label-sm text-muted">BREVE DESCRIPCIÓN O RESUMEN</label>
                        <input type="text" id="descripcion" v-model="producto.descripcion" class="form-control fc-new" :class="[errors.descripcion ? 'is-invalid' : '']" :readonly="loading">
                        <small class="form-text error-color" v-if="errors.descripcion">@{{ errors.descripcion[0] }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 3: ESPECIFICACIONES (DYNAMIC) -->
        <div class="card shadow-sm mb-3 border-0" :class="{'border-info': esPC || esMonitor || esToner}">
            <div class="card-header py-2 border-bottom" :class="(esPC || esMonitor || esToner) ? 'bg-info text-white' : 'bg-white text-dark'">
                <h6 class="mb-0 font-weight-bold">
                    <i class="fa fa-microchip mr-1" :class="(esPC || esMonitor || esToner) ? '' : 'text-primary'"></i> 3. Especificaciones Técnicas 
                    <span v-if="esPC" class="badge badge-light text-info ml-2">Computo</span>
                    <span v-if="esMonitor" class="badge badge-light text-info ml-2">Pantalla</span>
                    <span v-if="esToner" class="badge badge-light text-info ml-2">Suministro</span>
                </h6>
            </div>
            <div class="card-body pb-1">
                
                {{-- SECCION PC/LAPTOP --}}
                <div v-if="esPC">
                    <div class="form-row">
                        <div class="form-group col-lg-4">
                            <template v-if="!new_procesador">
                                <label for="procesador" class="label-sm text-muted">PROCESADOR <a href="#" @click.prevent="new_procesador = !new_procesador" class="text-info ml-1">[+ Nuevo]</a></label>
                                <select id="procesador" v-model="producto.procesador" class="form-control fc-new" :class="[errors.procesador ? 'is-invalid' : '']" :readonly="loading">
                                    <option value="">--- Seleccionar ---</option>
                                    <option v-for="proces in listaProcesadores" :value="proces.nom_pros">@{{ proces.nom_pros }}</option>
                                </select>
                            </template>
                            <template v-else>
                                <label for="txt_procesador" class="label-sm text-muted">PROCESADOR
                                    <a href="#" @click.prevent="StoreProcesador" class="text-success ml-1">[Guardar]</a>
                                    <a href="#" @click.prevent="new_procesador = !new_procesador" class="text-danger ml-1">[Cancelar]</a>
                                </label>
                                <input type="text" class="form-control fc-new" id="txt_procesador" v-model="txt_procesador">
                            </template>
                            <small class="form-text error-color" v-if="errors.procesador">@{{ errors.procesador[0] }}</small>
                        </div>
                        <div class="form-group col-lg-2">
                            <template v-if="!new_ram">
                                <label for="ram" class="label-sm text-muted">MEMORIA RAM <a href="#" @click.prevent="new_ram = !new_ram" class="text-info ml-1">[+ Nuevo]</a></label>
                                <select id="ram" v-model="producto.ram" class="form-control fc-new" :class="[errors.ram ? 'is-invalid' : '']" :readonly="loading">
                                    <option value="">--- Seleccionar ---</option>
                                    <option v-for="ram in listaRam" :value="ram.nom_ram">@{{ ram.nom_ram }}</option>
                                </select>
                            </template>
                            <template v-else>
                                <label for="txt_ram" class="label-sm text-muted">MEMORIA RAM
                                    <a href="#" @click.prevent="StoreRam" class="text-success ml-1">[Guardar]</a>
                                    <a href="#" @click.prevent="new_ram = !new_ram" class="text-danger ml-1">[X]</a>
                                </label>
                                <input type="text" class="form-control fc-new" id="txt_ram" v-model="txt_ram">
                            </template>
                        </div>
                        <div class="form-group col-lg-3">
                            <template v-if="!new_almacenamiento">
                                <label for="almacenamiento" class="label-sm text-muted">ALMACENAMIENTO <a href="#" @click.prevent="new_almacenamiento = !new_almacenamiento" class="text-info ml-1">[+ Nuevo]</a></label>
                                <select id="almacenamiento" v-model="producto.almacenamiento" class="form-control fc-new" :class="[errors.almacenamiento ? 'is-invalid' : '']" :readonly="loading">
                                    <option value="">--- Seleccionar ---</option>
                                    <option v-for="alm in listaAlmacenamiento" :value="alm.cant_almcen">@{{ alm.cant_almcen }}</option>
                                </select>
                            </template>
                            <template v-else>
                                <label for="txt_almacen" class="label-sm text-muted">ALMACENAMIENTO
                                    <a href="#" @click.prevent="StoreAlmacen" class="text-success ml-1">[Guardar]</a>
                                    <a href="#" @click.prevent="new_almacenamiento = !new_almacenamiento" class="text-danger ml-1">[X]</a>
                                </label>
                                <input type="text" class="form-control fc-new" id="txt_almacen" v-model="txt_almacen">
                            </template>
                        </div>
                        <div class="form-group col-lg-3">
                            <template v-if="!new_tarjetavideo">
                                <label for="tarjetavideo" class="label-sm text-muted">T. VIDEO <a href="#" @click.prevent="new_tarjetavideo = !new_tarjetavideo" class="text-info ml-1">[+ Nuevo]</a></label>
                                <select id="tarjetavideo" v-model="producto.tarjetavideo" class="form-control fc-new" :class="[errors.tarjetavideo ? 'is-invalid' : '']" :readonly="loading">
                                    <option value="">--- Seleccionar ---</option>
                                    <option v-for="vid in listaTarjetavideo" :value="vid.tarjetavideo">@{{ vid.tarjetavideo }}</option>
                                </select>
                            </template>
                            <template v-else>
                                <label for="txt_video" class="label-sm text-muted">T. VIDEO
                                    <a href="#" @click.prevent="StoreTarjetavideo" class="text-success ml-1">[Guardar]</a>
                                    <a href="#" @click.prevent="new_tarjetavideo = !new_tarjetavideo" class="text-danger ml-1">[X]</a>
                                </label>
                                <input type="text" class="form-control fc-new" id="txt_video" v-model="txt_video">
                            </template>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-3">
                            <label for="sistema_operativo" class="label-sm text-muted">SISTEMA OPERATIVO</label>
                            <input type="text" id="sistema_operativo" v-model="producto.sistema_operativo" class="form-control fc-new" :readonly="loading">
                        </div>
                        <div class="form-group col-lg-9">
                            <label class="label-sm text-muted d-block">PUERTOS Y PERIFÉRICOS INCLUIDOS</label>
                            <div class="d-flex" style="gap: 15px; flex-wrap: wrap;">
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_lan" v-model="producto.conectividad" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_lan">LAN</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_wlan" v-model="producto.conectividad_wlan" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_wlan">WIFI (WLAN)</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_usb" v-model="producto.conectividad_usb" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_usb">USB</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_hdmi" v-model="producto.video_hdmi" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_hdmi">HDMI</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_vga" v-model="producto.video_vga" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_vga">VGA</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_optica" v-model="producto.unidad_optica" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_optica">UNIDAD ÓPTICA</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_teclado" v-model="producto.teclado" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_teclado">TECLADO</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_mouse" v-model="producto.mouse" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_mouse">MOUSE</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCION MONITOR --}}
                <div v-if="esMonitor">
                    <div class="form-row">
                        <div class="form-group col-lg-4">
                            <label for="tipo_panel" class="label-sm text-muted">TIPO DE PANEL</label>
                            <input type="text" id="tipo_panel" v-model="producto.tipo_panel" class="form-control fc-new" placeholder="Ej: IPS, VA, TN, OLED" :readonly="loading">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="dimensiones" class="label-sm text-muted">DIMENSIONES (TAMAÑO DE PANTALLA)</label>
                            <input type="text" id="dimensiones" v-model="producto.dimensiones" class="form-control fc-new" placeholder="Ej: 24 pulgadas, 27''" :readonly="loading">
                        </div>
                        <div class="form-group col-lg-4">
                            <label class="label-sm text-muted d-block">CONECTORES DE VIDEO</label>
                            <div class="d-flex" style="gap: 15px;">
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_hdmi_mon" v-model="producto.video_hdmi" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_hdmi_mon">Tiene HDMI</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-1">
                                    <input type="checkbox" class="custom-control-input" id="chk_vga_mon" v-model="producto.video_vga" true-value="SI" false-value="NO">
                                    <label class="custom-control-label" for="chk_vga_mon">Tiene VGA</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCION SUMINISTRO/TONER --}}
                <div v-if="esToner">
                    <div class="form-row">
                        <div class="form-group col-lg-3">
                            <label for="tipo_suministro" class="label-sm text-muted">TIPO DE SUMINISTRO</label>
                            <input type="text" id="tipo_suministro" v-model="producto.tipo_suministro" class="form-control fc-new" placeholder="Ej: Tóner, Tinta, Cinta, Tambor" :readonly="loading">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="color" class="label-sm text-muted">COLOR</label>
                            <input type="text" id="color" v-model="producto.color" class="form-control fc-new" placeholder="Ej: Negro, Cian, Magenta, Amarillo" :readonly="loading">
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="rendimiento" class="label-sm text-muted">RENDIMIENTO</label>
                            <input type="text" id="rendimiento" v-model="producto.rendimiento" class="form-control fc-new" placeholder="Ej: 5000 páginas" :readonly="loading">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="sistema_raee" class="label-sm text-muted">SISTEMA RAEE</label>
                            <input type="text" id="sistema_raee" v-model="producto.sistema_raee" class="form-control fc-new" placeholder="Normativa RAEE" :readonly="loading">
                        </div>
                    </div>
                </div>

                {{-- CAMPO TEXTAREA PARA TODOS --}}
                <div class="form-row">
                    <div class="form-group col-lg-12">
                        <label for="especificaciones" class="label-sm text-muted mt-2">NOTAS / ESPECIFICACIONES ADICIONALES (TEXTO LIBRE)</label>
                        <textarea id="especificaciones" v-model="producto.especificaciones" class="form-control fc-new" rows="3" :class="[errors.especificaciones ? 'is-invalid' : '']" :readonly="loading" placeholder="Cualquier otra especificación que deba saber el cliente..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- CARD 4: VENTAS, CONFIGURACION Y EXTRAS -->
        <div class="card shadow-sm mb-3 border-0">
            <div class="card-header bg-white py-2 border-bottom">
                <h6 class="mb-0 font-weight-bold text-dark"><i class="fa fa-shopping-cart mr-1 text-success"></i> 4. Configuración Comercial y Códigos</h6>
            </div>
            <div class="card-body pb-1">
                <div class="form-row">
                    <div class="form-group col-lg-3">
                        <label for="tipo_afectacion" class="label-sm text-muted">TIPO DE AFECTACIÓN TRIBUTARIA <span class="text-danger">*</span></label>
                        <select id="tipo_afectacion" v-model="producto.tipo_afectacion" class="form-control fc-new font-weight-bold" :class="[errors.tipo_afectacion ? 'is-invalid' : '']" :readonly="loading">
                            <option value="10">GRAVADA (APLICA IGV)</option>
                            <option value="20">EXONERADA (NO APLICA IGV)</option>
                        </select>
                        <small class="form-text error-color" v-if="errors.tipo_afectacion">@{{ errors.tipo_afectacion[0] }}</small>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="garantia_de_fabrica" class="label-sm text-muted">TIENE GARANTÍA DE FÁBRICA</label>
                        <select id="garantia_de_fabrica" v-model="producto.garantia_de_fabrica" class="form-control fc-new" :readonly="loading">
                            <option value="SI">SÍ</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="empaque_de_fabrica" class="label-sm text-muted">TIPO DE EMPAQUE</label>
                        <select id="empaque_de_fabrica" v-model="producto.empaque_de_fabrica" class="form-control fc-new" :readonly="loading">
                            <option value="CAJA">CAJA</option>
                            <option value="OTRO">OTRO</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <template v-if="!new_ofimatica">
                            <label for="suite_ofimatica" class="label-sm text-muted">SUITE OFIMÁTICA <a href="#" @click.prevent="new_ofimatica = !new_ofimatica" class="text-info ml-1">[+ Nuevo]</a></label>
                            <select id="suite_ofimatica" v-model="producto.suite_ofimatica" class="form-control fc-new" :class="[errors.suite_ofimatica ? 'is-invalid' : '']" :readonly="loading">
                                <option value="">--- Seleccionar ---</option>
                                <option v-for="ofi in listaOfimatica" :value="ofi.ofimatica">@{{ ofi.ofimatica }}</option>
                            </select>
                        </template>
                        <template v-else>
                            <label for="txt_ofimatica" class="label-sm text-muted">SUITE OFIMÁTICA
                                <a href="#" @click.prevent="StoreOfimatica" class="text-success ml-1">[Guardar]</a>
                                <a href="#" @click.prevent="new_ofimatica = !new_ofimatica" class="text-danger ml-1">[X]</a>
                            </label>
                            <input type="text" class="form-control fc-new" id="txt_ofimatica" v-model="txt_ofimatica">
                        </template>
                    </div>
                </div>
                <div class="form-row border-top pt-2 mt-1">
                    <div class="form-group col-lg-3">
                        <label for="codigo_barras" class="label-sm text-muted">CÓDIGO DE BARRAS (EAN/UPC)</label>
                        <input type="text" id="codigo_barras" v-model="producto.codigo_barras" class="form-control fc-new" :readonly="loading">
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="codigo_interno" class="label-sm text-muted">CÓDIGO INTERNO (SKU)</label>
                        <input type="text" id="codigo_interno" v-model="producto.codigo_interno" class="form-control fc-new" :readonly="loading">
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="codigo_sunat" class="label-sm text-muted">CÓDIGO SUNAT</label>
                        <input type="text" id="codigo_sunat" v-model="producto.codigo_sunat" class="form-control fc-new" :readonly="loading">
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="linea_producto" class="label-sm text-muted">LÍNEA PRODUCTO</label>
                        <input type="text" id="linea_producto" v-model="producto.linea_producto" class="form-control fc-new" :readonly="loading">
                    </div>
                </div>
                <div class="form-row mt-2">
                    <div class="form-group col-lg-4">
                        <label for="certificacion" class="label-sm text-muted">CERTIFICACIÓN</label>
                        <input type="text" id="certificacion" v-model="producto.certificacion" class="form-control fc-new" :readonly="loading">
                    </div>
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
