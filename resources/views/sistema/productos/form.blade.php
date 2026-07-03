{{-- Tabs de navegacion - Vue driven (sin dependencia de Bootstrap tab plugin) --}}
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link" :class="{ active: activeTab === 'general' }"
            href="#" @click.prevent="activeTab = 'general'">General &amp; CPU</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" :class="{ active: activeTab === 'specs' }"
            href="#" @click.prevent="activeTab = 'specs'">Hardware</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" :class="{ active: activeTab === 'otros' }"
            href="#" @click.prevent="activeTab = 'otros'">Ofimática &amp; Códigos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" :class="{ active: activeTab === 'imagenes' }"
            href="#" @click.prevent="activeTab = 'imagenes'">Imágenes &amp; Ficha</a>
    </li>
</ul>

<div class="tab-content mt-2">

    {{-- TAB 1: General & CPU --}}
    <div class="tab-pane" :class="{ 'show active': activeTab === 'general' }" style="display: block;" v-show="activeTab === 'general'">
        <div class="form-row mt-2">
            <div class="form-group col-lg-6">
                <label for="nombre" class="label-sm">NOMBRE <span class="text-danger">*</span></label>
                <input type="text" id="nombre" v-model="producto.nombre"
                    class="form-control fc-new" :class="[errors.nombre ? 'is-invalid' : '']"
                    :readonly="loading">
                <small class="form-text error-color" v-if="errors.nombre">@{{ errors.nombre[0] }}</small>
            </div>
            <div class="form-group col-lg-6">
                <label for="nombre_secundario" class="label-sm">NOMBRE SECUNDARIO</label>
                <input type="text" id="nombre_secundario" v-model="producto.nombre_secundario"
                    class="form-control fc-new" :class="[errors.nombre_secundario ? 'is-invalid' : '']"
                    :readonly="loading">
                <small class="form-text error-color" v-if="errors.nombre_secundario">@{{ errors.nombre_secundario[0] }}</small>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-4">
                <label for="descripcion" class="label-sm">DESCRIPCIÓN</label>
                <input type="text" id="descripcion" v-model="producto.descripcion"
                    class="form-control fc-new" :class="[errors.descripcion ? 'is-invalid' : '']"
                    :readonly="loading">
                <small class="form-text error-color" v-if="errors.descripcion">@{{ errors.descripcion[0] }}</small>
            </div>
            <div class="form-group col-lg-3">
                <label for="nro_parte" class="label-sm">NRO. PARTE</label>
                <input type="text" id="nro_parte" v-model="producto.nro_parte"
                    class="form-control fc-new" :class="[errors.nro_parte ? 'is-invalid' : '']"
                    :readonly="loading">
                <small class="form-text error-color" v-if="errors.nro_parte">@{{ errors.nro_parte[0] }}</small>
            </div>
            <div class="form-group col-lg-2">
                <label for="modelo_id" class="label-sm">MODELO <span class="text-danger">*</span></label>
                <select id="modelo_id" v-model="producto.modelo_id" class="form-control"
                    :class="[errors.modelo_id ? 'border-error' : '']">
                    @foreach ($modelos as $mod)
                        <option value="{{ $mod->id }}">{{ $mod['descripcion'] }}</option>
                    @endforeach
                </select>
                <small class="form-text error-color" v-if="errors.modelo">@{{ errors.modelo[0] }}</small>
            </div>
            <div class="form-group col-lg-3">
                <template v-if="!new_procesador">
                    <label for="procesador" class="label-sm">PROCESADOR
                        <a href="#" @click.prevent="new_procesador = !new_procesador">[+ Nuevo]</a>
                    </label>
                    <select id="procesador" v-model="producto.procesador"
                        class="form-control fc-new" :class="[errors.procesador ? 'is-invalid' : '']"
                        :readonly="loading">
                        <option value="">--- Seleccionar ---</option>
                        <option v-for="proces in listaProcesadores" :value="proces.nom_pros">@{{ proces.nom_pros }}</option>
                    </select>
                </template>
                <template v-else>
                    <label for="txt_procesador" class="label-sm">
                        PROCESADOR
                        <a href="#" @click.prevent="StoreProcesador" style="color:green;">[+ Guardar]</a>
                        <a href="#" @click.prevent="new_procesador = !new_procesador" style="color:red;">[Cancelar]</a>
                    </label>
                    <input type="text" class="form-control fc-new" id="txt_procesador" v-model="txt_procesador">
                </template>
                <small class="form-text error-color" v-if="errors.procesador">@{{ errors.procesador[0] }}</small>
            </div>
        </div>
    </div>

    {{-- TAB 2: Hardware & Conectividad --}}
    <div v-show="activeTab === 'specs'">
        <div class="form-row mt-2">
            <div class="form-group col-lg-3">
                <template v-if="!new_ram">
                    <label for="ram" class="label-sm">RAM
                        <a href="#" @click.prevent="new_ram = !new_ram">[+ Nuevo]</a>
                    </label>
                    <select id="ram" v-model="producto.ram" class="form-control fc-new"
                        :class="[errors.ram ? 'is-invalid' : '']" :readonly="loading">
                        <option value="">--- Seleccionar ---</option>
                        <option v-for="ram in listaRam" :value="ram.nom_ram">@{{ ram.nom_ram }}</option>
                    </select>
                </template>
                <template v-else>
                    <label for="txt_ram" class="label-sm">
                        RAM
                        <a href="#" @click.prevent="StoreRam" style="color:green;">[+ Guardar]</a>
                        <a href="#" @click.prevent="new_ram = !new_ram" style="color:red;">[Cancelar]</a>
                    </label>
                    <input type="text" class="form-control fc-new" id="txt_ram" v-model="txt_ram">
                </template>
                <small class="form-text error-color" v-if="errors.ram">@{{ errors.ram[0] }}</small>
            </div>
            <div class="form-group col-lg-3">
                <template v-if="!new_almacenamiento">
                    <label for="almacenamiento" class="label-sm">ALMACENAMIENTO
                        <a href="#" @click.prevent="new_almacenamiento = !new_almacenamiento">[+ Nuevo]</a>
                    </label>
                    <select id="almacenamiento" v-model="producto.almacenamiento"
                        class="form-control fc-new" :class="[errors.almacenamiento ? 'is-invalid' : '']"
                        :readonly="loading">
                        <option value="">--- Seleccionar ---</option>
                        <option v-for="alm in listaAlmacenamiento" :value="alm.cant_almcen">@{{ alm.cant_almcen }}</option>
                    </select>
                </template>
                <template v-else>
                    <label for="txt_almacen" class="label-sm">
                        ALMACENAMIENTO
                        <a href="#" @click.prevent="StoreAlmacen" style="color:green;">[+ Guardar]</a>
                        <a href="#" @click.prevent="new_almacenamiento = !new_almacenamiento" style="color:red;">[Cancelar]</a>
                    </label>
                    <input type="text" class="form-control fc-new" id="txt_almacen" v-model="txt_almacen">
                </template>
                <small class="form-text error-color" v-if="errors.almacenamiento">@{{ errors.almacenamiento[0] }}</small>
            </div>
            <div class="form-group col-lg-3">
                <template v-if="!new_tarjetavideo">
                    <label for="tarjetavideo" class="label-sm">TARJETA DE VIDEO
                        <a href="#" @click.prevent="new_tarjetavideo = !new_tarjetavideo">[+ Nuevo]</a>
                    </label>
                    <select id="tarjetavideo" v-model="producto.tarjetavideo"
                        class="form-control fc-new" :class="[errors.tarjetavideo ? 'is-invalid' : '']"
                        :readonly="loading">
                        <option value="">--- Seleccionar ---</option>
                        <option v-for="vid in listaTarjetavideo" :value="vid.tarjetavideo">@{{ vid.tarjetavideo }}</option>
                    </select>
                </template>
                <template v-else>
                    <label for="txt_video" class="label-sm">
                        TARJETA DE VIDEO
                        <a href="#" @click.prevent="StoreTarjetavideo" style="color:green;">[+ Guardar]</a>
                        <a href="#" @click.prevent="new_tarjetavideo = !new_tarjetavideo" style="color:red;">[Cancelar]</a>
                    </label>
                    <input type="text" class="form-control fc-new" id="txt_video" v-model="txt_video">
                </template>
                <small class="form-text error-color" v-if="errors.tarjetavideo">@{{ errors.tarjetavideo[0] }}</small>
            </div>
            <div class="form-group col-lg-3">
                <label for="tipo_afectacion" class="label-sm">AFECTACIÓN <span class="text-danger">*</span></label>
                <select id="tipo_afectacion" v-model="producto.tipo_afectacion"
                    class="form-control fc-new" :class="[errors.tipo_afectacion ? 'is-invalid' : '']"
                    :readonly="loading">
                    <option value="10">GRAVADA</option>
                    <option value="20">EXONERADA</option>
                </select>
                <small class="form-text error-color" v-if="errors.tipo_afectacion">@{{ errors.tipo_afectacion[0] }}</small>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-2">
                <label for="conectividad" class="label-sm">LAN</label>
                <select id="conectividad" v-model="producto.conectividad" class="form-control fc-new" :readonly="loading">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="conectividad_wlan" class="label-sm">WLAN</label>
                <select id="conectividad_wlan" v-model="producto.conectividad_wlan" class="form-control fc-new" :readonly="loading">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="conectividad_usb" class="label-sm">USB</label>
                <select id="conectividad_usb" v-model="producto.conectividad_usb" class="form-control fc-new" :readonly="loading">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="video_vga" class="label-sm">VGA</label>
                <select id="video_vga" v-model="producto.video_vga" class="form-control fc-new" :readonly="loading">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="video_hdmi" class="label-sm">HDMI</label>
                <select id="video_hdmi" v-model="producto.video_hdmi" class="form-control fc-new" :readonly="loading">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="unidad_optica" class="label-sm">UNID. ÓPTICA</label>
                <select id="unidad_optica" v-model="producto.unidad_optica" class="form-control fc-new" :readonly="loading">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-4">
                <label for="sistema_operativo" class="label-sm">SISTEMA OPERATIVO</label>
                <input type="text" id="sistema_operativo" v-model="producto.sistema_operativo"
                    class="form-control fc-new" :readonly="loading">
            </div>
            <div class="form-group col-lg-2">
                <label for="teclado" class="label-sm">TECLADO</label>
                <select id="teclado" v-model="producto.teclado" class="form-control fc-new" :readonly="loading">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
            <div class="form-group col-lg-2">
                <label for="mouse" class="label-sm">MOUSE</label>
                <select id="mouse" v-model="producto.mouse" class="form-control fc-new" :readonly="loading">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
        </div>
    </div>

    {{-- TAB 3: Ofimática & Códigos --}}
    <div v-show="activeTab === 'otros'">
        <div class="form-row mt-2">
            <div class="form-group col-lg-4">
                <template v-if="!new_ofimatica">
                    <label for="suite_ofimatica" class="label-sm">SUITE OFIMÁTICA
                        <a href="#" @click.prevent="new_ofimatica = !new_ofimatica">[+ Nuevo]</a>
                    </label>
                    <select id="suite_ofimatica" v-model="producto.suite_ofimatica"
                        class="form-control fc-new" :class="[errors.suite_ofimatica ? 'is-invalid' : '']"
                        :readonly="loading">
                        <option value="">--- Seleccionar ---</option>
                        <option v-for="ofi in listaOfimatica" :value="ofi.ofimatica">@{{ ofi.ofimatica }}</option>
                    </select>
                </template>
                <template v-else>
                    <label for="txt_ofimatica" class="label-sm">
                        SUITE OFIMÁTICA
                        <a href="#" @click.prevent="StoreOfimatica" style="color:green;">[+ Guardar]</a>
                        <a href="#" @click.prevent="new_ofimatica = !new_ofimatica" style="color:red;">[Cancelar]</a>
                    </label>
                    <input type="text" class="form-control fc-new" id="txt_ofimatica" v-model="txt_ofimatica">
                </template>
                <small class="form-text error-color" v-if="errors.suite_ofimatica">@{{ errors.suite_ofimatica[0] }}</small>
            </div>
            <div class="form-group col-lg-2">
                <label for="garantia_de_fabrica" class="label-sm">GARANTÍA</label>
                <select id="garantia_de_fabrica" v-model="producto.garantia_de_fabrica"
                    class="form-control fc-new" :readonly="loading">
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>
            <div class="form-group col-lg-3">
                <label for="empaque_de_fabrica" class="label-sm">EMPAQUE</label>
                <select id="empaque_de_fabrica" v-model="producto.empaque_de_fabrica"
                    class="form-control fc-new" :readonly="loading">
                    <option value="CAJA">CAJA</option>
                    <option value="OTRO">OTRO</option>
                </select>
            </div>
            <div class="form-group col-lg-3">
                <label for="certificacion" class="label-sm">CERTIFICACIÓN</label>
                <input type="text" id="certificacion" v-model="producto.certificacion"
                    class="form-control fc-new" :readonly="loading">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-3">
                <label for="codigo_barras" class="label-sm">CÓDIGO BARRAS</label>
                <input type="text" id="codigo_barras" v-model="producto.codigo_barras"
                    class="form-control fc-new" :readonly="loading">
            </div>
            <div class="form-group col-lg-3">
                <label for="codigo_interno" class="label-sm">CÓDIGO INTERNO</label>
                <input type="text" id="codigo_interno" v-model="producto.codigo_interno"
                    class="form-control fc-new" :readonly="loading">
            </div>
            <div class="form-group col-lg-3">
                <label for="codigo_sunat" class="label-sm">CÓDIGO SUNAT</label>
                <input type="text" id="codigo_sunat" v-model="producto.codigo_sunat"
                    class="form-control fc-new" :readonly="loading">
            </div>
            <div class="form-group col-lg-3">
                <label for="linea_producto" class="label-sm">LÍNEA PRODUCTO</label>
                <input type="text" id="linea_producto" v-model="producto.linea_producto"
                    class="form-control fc-new" :readonly="loading">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-lg-12">
                <label for="especificaciones" class="label-sm">ESPECIFICACIONES DEL PRODUCTO</label>
                <textarea id="especificaciones" v-model="producto.especificaciones"
                    class="form-control fc-new" rows="4"
                    :class="[errors.especificaciones ? 'is-invalid' : '']" :readonly="loading"></textarea>
            </div>
        </div>
    </div>

    {{-- TAB 4: Imágenes & Ficha Técnica --}}
    <div v-show="activeTab === 'imagenes'">
        <div class="form-row mt-2">
            {{-- PDF Ficha Técnica --}}
            <div class="col-lg-12 mb-3">
                <label class="label-sm d-block">FICHA TÉCNICA (PDF)</label>
                <div class="d-flex align-items-center">
                    <label class="btn btn-outline-secondary btn-sm mr-2 mb-0" for="file" title="Seleccionar PDF">
                        <i class="fa fa-file-pdf-o mr-1"></i> Seleccionar PDF
                        <input type="file" id="file" style="display: none;"
                            v-on:change="changePdf($event)" accept="application/pdf">
                    </label>
                    <a v-if="producto.pdf_ficha" :href="getPdfUrl(producto.pdf_ficha)"
                        target="_blank" class="btn btn-outline-info btn-sm mb-0">
                        <i class="fa fa-eye mr-1"></i> Ver PDF actual
                    </a>
                    <span v-else class="text-muted small ml-2">Sin ficha adjunta</span>
                </div>
            </div>

            {{-- Imágenes --}}
            <div class="col-lg-12">
                <label class="label-sm d-block mb-2">IMÁGENES DEL PRODUCTO</label>
                <div class="d-flex flex-wrap" style="gap: 12px;">
                    @for ($i = 1; $i <= 5; $i++)
                    <div>
                        <label class="image_show" for="file_edit_{{ $i }}" title="Cambiar imagen {{ $i }}"
                            v-if="producto.imagen_{{ $i }}">
                            <img id="show_image_{{ $i }}" class="img-fluid">
                            <input type="file" id="file_edit_{{ $i }}" style="display: none;"
                                v-on:change="changeImagen($event, {{ $i }})" accept="image/*">
                        </label>
                        <label class="image" for="file_{{ $i }}" title="Agregar imagen {{ $i }}" v-else>
                            Imagen {{ $i }}<br><i class="fa fa-plus-circle"></i>
                            <input type="file" id="file_{{ $i }}" style="display: none;"
                                v-on:change="changeImagen($event, {{ $i }})" accept="image/*">
                        </label>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Switch: Ficha editada localmente --}}
        <div class="form-row mt-3">
            <div class="col-lg-12">
                <div class="alert alert-warning py-2 px-3 mb-0 d-flex align-items-center">
                    <div class="custom-control custom-switch mb-0">
                        <input type="checkbox" class="custom-control-input" id="ficha_editada_localmente_form"
                            v-model="producto.ficha_editada_localmente">
                        <label class="custom-control-label font-weight-bold" for="ficha_editada_localmente_form">
                            Ficha Editada Localmente
                        </label>
                    </div>
                    <small class="ml-3 text-muted">Al activar, la API <strong>no sobreescribirá</strong> este producto en la próxima sincronización.</small>
                </div>
            </div>
        </div>
    </div>

</div>
