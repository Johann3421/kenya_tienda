
<ul class="nav nav-tabs" id="modalTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General & Procesador</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="specs-tab" data-toggle="tab" href="#specs" role="tab" aria-controls="specs" aria-selected="false">Hardware & Conectividad</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="otros-tab" data-toggle="tab" href="#otros" role="tab" aria-controls="otros" aria-selected="false">Ofimática & Códigos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="imagenes-tab" data-toggle="tab" href="#imagenes" role="tab" aria-controls="imagenes" aria-selected="false">Imágenes</a>
    </li>
</ul>

<div class="tab-content mt-3" id="modalTabsContent">
    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
        <div class="form-row" style="margin-bottom: 10px;">

                                        <div class="form-group col-lg-6">
                                            <label for="nombre" class="label-sm">NOMBRE</label>
                                            <input type="text" id="nombre" v-model="producto.nombre"
                                                class="form-control fc-new" :class="[errors.nombre ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.nombre">@{{ errors.nombre[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="nombre_secundario" class="label-sm">NOMBRE SECUNDARIO</label>
                                            <input type="text" id="nombre_secundario"
                                                v-model="producto.nombre_secundario" class="form-control fc-new"
                                                :class="[errors.nombre_secundario ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.nombre_secundario">@{{ errors.nombre_secundario[0] }}</small>
                                        </div>
                                    </div>
<div class="form-row" style="margin-bottom: 10px;">
                                        <div class="form-group col-lg-4">
                                            <label for="descripcion" class="label-sm">DESCRIPCIÓN</label>
                                            <input type="email" id="descripcion" v-model="producto.descripcion"
                                                class="form-control fc-new"
                                                :class="[errors.descripcion ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.descripcion">@{{ errors.decripcion[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="nro_parte" class="label-sm">NMRO PARTE</label>
                                            <input type="email" id="nro_parte" v-model="producto.nro_parte"
                                                class="form-control fc-new" :class="[errors.nro_parte ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.nro_parte">@{{ errors.nro_parte[0] }}</small>
                                        </div>

                                        <div class="form-group col-lg-2">
                                            <label for="modelo_id" class="label-sm">MODELO</label>
                                            <select id="modelo_id" v-model="producto.modelo_id" class="form-control"
                                                :class="[errors.modelo_id ? 'border-error' : '']">
                                                @foreach ($modelos as $mod)
                                                    <option value="{{ $mod->id }}">{{ $mod['descripcion'] }}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.modelo">@{{ errors.modelo[0] }}</small>
                                        </div>

                                        <div class="col-lg-3">
                                            <div class="form-group" style="margin-bottom: 8px;">
                                                <template v-if="!new_procesador">
                                                    <label for="procesador" class="label-sm">PROCESADOR <a href="#"
                                                            title="Agergar nuevo Procesador"
                                                            v-on:click="new_procesador = !new_procesador">[+
                                                            Nuevo]</a></label>
                                                    <select id="procesador" v-model="producto.procesador"
                                                        class="form-control fc-new"
                                                        :class="[errors.procesador ? 'is-invalid' : '']"
                                                        :readonly="loading">
                                                        <option value="">--- Seleccionar ---</option>
                                                        <option v-for="proces in listaProcesadores"
                                                            :value="proces.nom_pros">@{{ proces.nom_pros }}</option>
                                                    </select>
                                                </template>
                                                <template v-else>
                                                    <label for="txt_procesador" class="label-sm">
                                                        PROCESADOR
                                                        <a href="#" title="Agergar nuevo Procesador"
                                                            v-on:click="StoreProcesador" style="color:green;">[+
                                                            Guardar]</a>
                                                        <a href="#" title="Agergar nuevo Procesador"
                                                            v-on:click="new_procesador = !new_procesador"
                                                            style="color:red;">[Cancelar]</a>
                                                    </label>
                                                    <input type="text" class="form-control fc-new" id="txt_procesador"
                                                        v-model="txt_procesador">
                                                </template>
                                                <small class="form-text error-color"
                                                    v-if="errors.procesador">@{{ errors.procesador[0] }}</small>
                                            </div>
                                        </div>
                                    </div>
    </div>
    <div class="tab-pane fade" id="specs" role="tabpanel" aria-labelledby="specs-tab">
        <div class="form-row" style="margin-bottom: 10px;">
                                        <div class="form-group col-lg-3">
                                            <template v-if="!new_ram">
                                                <label for="ram" class="label-sm">RAM <a href="#"
                                                        title="Agergar nueva Ram" v-on:click="new_ram = !new_ram">[+
                                                        Nuevo]</a></label>
                                                <select id="ram" v-model="producto.ram" class="form-control fc-new"
                                                    :class="[errors.ram ? 'is-invalid' : '']" :readonly="loading">
                                                    <option value="">--- Seleccionar ---</option>
                                                    <option v-for="ram in listaRam" :value="ram.nom_ram">
                                                        @{{ ram.nom_ram }}</option>
                                                </select>
                                            </template>
                                            <template v-else>
                                                <label for="txt_ram" class="label-sm">
                                                    RAM
                                                    <a href="#" title="Agergar nueva Ram" v-on:click="StoreRam"
                                                        style="color:green;">[+ Guardar]</a>
                                                    <a href="#" title="Agergar nueva Ram"
                                                        v-on:click="new_ram = !new_ram" style="color:red;">[Cancelar]</a>
                                                </label>
                                                <input type="text" class="form-control fc-new" id="txt_ram"
                                                    v-model="txt_ram">
                                            </template>
                                            <small class="form-text error-color"
                                                v-if="errors.ram">@{{ errors.ram[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <template v-if="!new_almacenamiento">
                                                <label for="almacenamiento" class="label-sm">ALMACENAMIENTO <a
                                                        href="#" title="Agergar nuevo Almacenamiento"
                                                        v-on:click="new_almacenamiento = !new_almacenamiento">[+
                                                        Nuevo]</a></label>
                                                <select id="almacenamiento" v-model="producto.almacenamiento"
                                                    class="form-control fc-new"
                                                    :class="[errors.procesador ? 'is-invalid' : '']"
                                                    :readonly="loading">
                                                    <option value="">--- Seleccionar ---</option>
                                                    <option v-for="alm in listaAlmacenamiento" :value="alm.cant_almcen">
                                                        @{{ alm.cant_almcen }}</option>
                                                </select>
                                            </template>
                                            <template v-else>
                                                <label for="txt_almacen" class="label-sm">
                                                    ALMACENAMIENTO
                                                    <a href="#" title="Agergar nuevo Almacenamiento"
                                                        v-on:click="StoreAlmacen" style="color:green;">[+ Guardar]</a>
                                                    <a href="#" title="Agergar nuevo Almacenamiento"
                                                        v-on:click="new_almacenamiento = !new_almacenamiento"
                                                        style="color:red;">[Cancelar]</a>
                                                </label>
                                                <input type="text" class="form-control fc-new" id="txt_almacen"
                                                    v-model="txt_almacen">
                                            </template>
                                            <small class="form-text error-color"
                                                v-if="errors.almacenamiento">@{{ errors.procesador[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="conectividad" class="label-sm">CONECTIVIDAD LAN</label>
                                            <select id="conectividad" v-model="producto.conectividad"
                                                class="form-control fc-new"
                                                :class="[errors.conectividad ? 'is-invalid' : '']" :readonly="loading">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.conectividad">@{{ errors.conectividad[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="tipo_afectacion" class="label-sm">TIPO DE AFECTACIÓN</label>
                                            <select id="tipo_afectacion" v-model="producto.tipo_afectacion"
                                                class="form-control fc-new"
                                                :class="[errors.tipo_afectacion ? 'is-invalid' : '']"
                                                :readonly="loading">
                                                <option value="10">GRAVADA</option>
                                                <option value="20">EXONERADA</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.tipo_afectacion">@{{ errors.tipo_afectacion[0] }}</small>
                                        </div>
                                    </div>
<div class="form-row" style="margin-bottom: 10px;">
                                        <div class="form-group col-lg-2">
                                            <label for="conectividad_wlan" class="label-sm">CONECTIVIDAD WLAN</label>
                                            <select id="conectividad_wlan" v-model="producto.conectividad_wlan"
                                                class="form-control fc-new"
                                                :class="[errors.conectividad_wlan ? 'is-invalid' : '']"
                                                :readonly="loading">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.conectividad_wlan">@{{ errors.conectividad_wlan[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="conectividad_usb" class="label-sm">CONECTIVIDAD USB</label>
                                            <select id="conectividad_usb" v-model="producto.conectividad_usb"
                                                class="form-control fc-new"
                                                :class="[errors.conectividad_usb ? 'is-invalid' : '']"
                                                :readonly="loading">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.conectividad_usb">@{{ errors.conectividad_usb[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="video_vga" class="label-sm">CONECTIVIDAD VGA</label>
                                            <select id="video_vga" v-model="producto.video_vga"
                                                class="form-control fc-new"
                                                :class="[errors.video_vga ? 'is-invalid' : '']" :readonly="loading">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.video_vga">@{{ errors.video_vga[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="video_hdmi" class="label-sm">VIDEO HDMI</label>
                                            <select id="video_hdmi" v-model="producto.video_hdmi"
                                                class="form-control fc-new"
                                                :class="[errors.video_hdmi ? 'is-invalid' : '']" :readonly="loading">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.video_hdmi">@{{ errors.video_hdmi[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="sistema_operativo" class="label-sm">SISTEMA OEPRATIVO</label>
                                            <input type="text" id="sistema_operativo"
                                                v-model="producto.sistema_operativo" class="form-control fc-new"
                                                :class="[errors.sistema_operativo ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.sistema_operativo">@{{ errors.sistema_operativo[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="unidad_optica" class="label-sm">UNIDAD OPTICA</label>
                                            <select id="unidad_optica" v-model="producto.unidad_optica"
                                                class="form-control fc-new"
                                                :class="[errors.unidad_optica ? 'is-invalid' : '']"
                                                :readonly="loading">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.unidad_optica">@{{ errors.unidad_optica[0] }}</small>
                                        </div>
                                    </div>
    </div>
    <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
        <div class="form-row" style="margin-bottom: 10px;">
                                        <div class="form-group col-lg-2">
                                            <label for="teclado" class="label-sm">TECLADO</label>
                                            <select id="teclado" v-model="producto.teclado" class="form-control fc-new"
                                                :class="[errors.teclado ? 'is-invalid' : '']" :readonly="loading">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.teclado">@{{ errors.teclado[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-1">
                                            <label for="mouse" class="label-sm">MOUSE</label>
                                            <select id="mouse" v-model="producto.mouse" class="form-control fc-new"
                                                :class="[errors.mouse ? 'is-invalid' : '']" :readonly="loading">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.mouse">@{{ errors.mouse[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <template v-if="!new_ofimatica">
                                                <label for="suite_ofimatica" class="label-sm">SUITE OFIMATICA <a
                                                        href="#" title="Agergar nuevo Procesador"
                                                        v-on:click="new_ofimatica = !new_ofimatica">[+ Nuevo]</a></label>
                                                <select id="suite_ofimatica" v-model="producto.suite_ofimatica"
                                                    class="form-control fc-new"
                                                    :class="[errors.suite_ofimatica ? 'is-invalid' : '']"
                                                    :readonly="loading">
                                                    <option value="">--- Seleccionar ---</option>
                                                    <option v-for="ofi in listaOfimatica" :value="ofi.ofimatica">
                                                        @{{ ofi.ofimatica }}</option>
                                                </select>
                                                <small class="form-text error-color"
                                                    v-if="errors.procesador">@{{ errors.procesador[0] }}</small>
                                            </template>
                                            <template v-else>
                                                <label for="txt_categoria" class="label-sm">
                                                    SUITE OFIMATICA
                                                    <a href="#" title="Agergar nueva Ofimatica"
                                                        v-on:click="StoreOfimatica" style="color:green;">[+ Guardar]</a>
                                                    <a href="#" title="Agergar nueva Ofimatica"
                                                        v-on:click="new_ofimatica = !new_ofimatica"
                                                        style="color:red;">[Cancelar]</a>
                                                </label>
                                                <input type="text" class="form-control fc-new" id="txt_ofimatica"
                                                    v-model="txt_ofimatica">
                                            </template>
                                            <small class="form-text error-color"
                                                v-if="errors.suite_ofimatica">@{{ errors.suite_ofimatica[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="garantia_de_fabrica" class="label-sm">GARANTIA DE FABRICA</label>
                                            <select id="garantia_de_fabrica" v-model="producto.garantia_de_fabrica"
                                                class="form-control fc-new"
                                                :class="[errors.garantia_de_fabrica ? 'is-invalid' : '']"
                                                :readonly="loading">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.garantia_de_fabrica">@{{ errors.garantia_de_fabrica[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-3">
                                            <label for="empaque_de_fabrica" class="label-sm">EMPAQUE DE FABRICA</label>
                                            <select id="empaque_de_fabrica" v-model="producto.empaque_de_fabrica"
                                                class="form-control fc-new"
                                                :class="[errors.empaque_de_fabrica ? 'is-invalid' : '']"
                                                :readonly="loading">
                                                <option value="CAJA">CAJA</option>
                                                <option value="OTRO">OTRO</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.empaque_de_fabrica">@{{ errors.empaque_de_fabrica[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="certificacion" class="label-sm">CERTIFICACION</label>
                                            <input type="text" id="certificacion" v-model="producto.certificacion"
                                                class="form-control fc-new"
                                                :class="[errors.certificacion ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.certificacion">@{{ errors.certificacion[0] }}</small>
                                        </div>
                                    </div>
<div class="form-row" style="margin-bottom: 10px;">
                                        <div class="form-group col-lg-2">
                                            <label for="certificacion" class="label-sm">CÓDIGO BARRAS</label>
                                            <input type="text" id="codigo_barras" v-model="producto.codigo_barras"
                                                class="form-control fc-new"
                                                :class="[errors.codigo_barras ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.codigo_barras">@{{ errors.codigo_barra[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="codigo_interno" class="label-sm">CÓDIGO INTERNO</label>
                                            <input type="text" id="codigo_interno" v-model="producto.codigo_interno"
                                                class="form-control fc-new"
                                                :class="[errors.codigo_interno ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.codigo_interno">@{{ errors.codigo_interno[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="codigo_sunat" class="label-sm">CÓDIGO SUNAT</label>
                                            <input type="text" id="codigo_sunat" v-model="producto.codigo_sunat"
                                                class="form-control fc-new"
                                                :class="[errors.codigo_sunat ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.codigo_sunat">@{{ errors.codigo_sunat[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="linea_producto" class="label-sm">LINEA DE PRODUCTO</label>
                                            <input type="text" id="linea_producto" v-model="producto.linea_producto"
                                                class="form-control fc-new"
                                                :class="[errors.linea_producto ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.linea_producto">@{{ errors.linea_producto[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <template v-if="!new_tarjetavideo">
                                                <label for="tarjetavideo" class="label-sm">TARJETA DE VIDEO <a
                                                        href="#" title="Agergar nuevo Almacenamiento"
                                                        v-on:click="new_tarjetavideo = !new_tarjetavideo">[+
                                                        Nuevo]</a></label>
                                                <select id="tarjetavideo" v-model="producto.tarjetavideo"
                                                    class="form-control fc-new"
                                                    :class="[errors.tarjetavideo ? 'is-invalid' : '']"
                                                    :readonly="loading">
                                                    <option value="">--- Seleccionar ---</option>
                                                    <option v-for="vid in listaTarjetavideo" :value="vid.tarjetavideo">
                                                        @{{ vid.tarjetavideo }}</option>
                                                </select>
                                            </template>
                                            <template v-else>
                                                <label for="txt_video" class="label-sm">
                                                    TARJETA DE VIDEO
                                                    <a href="#" title="Agregar nueva tarjeta de video"
                                                        v-on:click="StoreTarjetavideo" style="color:green;">[+
                                                        Guardar]</a>
                                                    <a href="#" title="Agregar nuev tarjeta de video"
                                                        v-on:click="new_tarjetavideo = !new_tarjetavideo"
                                                        style="color:red;">[Cancelar]</a>
                                                </label>
                                                <input type="text" class="form-control fc-new" id="txt_video"
                                                    v-model="txt_video">
                                            </template>
                                            <small class="form-text error-color"
                                                v-if="errors.tarjetavideo">@{{ errors.tarjetavideo[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-2">
                                            <label for="pdf_ficha" class="label-sm">FICHA TECNICA</label>
                                            <label class="image" for="file" title="Buscar Imagen">
                                                PDF
                                                <br>
                                                <i class="fa fa-plus-circle"></i>
                                                <input type="file" id="file" style="display: none;"
                                                    v-on:change="changePdf($event)" accept="pdf/*">
                                            </label>
                                            <small class="form-text error-color"
                                                v-if="errors.pdf_ficha">@{{ errors.pdf_ficha[0] }}</small>
                                        </div>
                                        <div class="form-group col-lg-12 mt-3">
                                            <label for="especificaciones" class="label-sm">ESPECIFICACIONES DEL
                                                PRODUCTO</label>
                                            <textarea id="especificaciones" v-model="producto.especificaciones" class="form-control fc-new"
                                                :class="[errors.especificaciones ? 'is-invalid' : '']" :readonly="loading"></textarea>
                                            <small class="form-text error-color"
                                                v-if="errors.especificaciones">@{{ errors.especificaciones[0] }}</small>
                                        </div>
                                    </div>
    </div>
    <div class="tab-pane fade" id="imagenes" role="tabpanel" aria-labelledby="imagenes-tab">
        <div class="form-row" style="margin-bottom: 10px;">
                                        <div class="col-lg-12 mt-2">ATRIBUTOS
                                            <hr>
                                        </div>
                                        <div class="col-lg-8">
                                            <div style="display: flex;">
                                                <div style="margin-right: 20px;">
                                                    <label class="image_show" for="file_edit_1" title="Buscar Imagen"
                                                        v-if="producto.imagen_1">
                                                        <img id="show_image_1" class="img-fluid">
                                                        <input type="file" id="file_edit_1" style="display: none;"
                                                            v-on:change="changeImagen($event, 1)" accept="image/*">
                                                    </label>
                                                    <label class="image" for="file_1" title="Buscar Imagen" v-else>
                                                        Imagen 1<br><i class="fa fa-plus-circle"></i>
                                                        <input type="file" id="file_1" style="display: none;"
                                                            v-on:change="changeImagen($event, 1)" accept="image/*">
                                                    </label>
                                                </div>
                                                <div style="margin-right: 20px;">
                                                    <label class="image_show" for="file_edit_2" title="Buscar Imagen"
                                                        v-if="producto.imagen_2">
                                                        <img id="show_image_2" class="img-fluid">
                                                        <input type="file" id="file_edit_2" style="display: none;"
                                                            v-on:change="changeImagen($event, 2)" accept="image/*">
                                                    </label>
                                                    <label class="image" for="file_2" title="Buscar Imagen" v-else>
                                                        Imagen 2<br><i class="fa fa-plus-circle"></i>
                                                        <input type="file" id="file_2" style="display: none;"
                                                            v-on:change="changeImagen($event, 2)" accept="image/*">
                                                    </label>
                                                </div>
                                                <div style="margin-right: 20px;">
                                                    <label class="image_show" for="file_edit_3" title="Buscar Imagen"
                                                        v-if="producto.imagen_3">
                                                        <img id="show_image_3" class="img-fluid">
                                                        <input type="file" id="file_edit_3" style="display: none;"
                                                            v-on:change="changeImagen($event, 3)" accept="image/*">
                                                    </label>
                                                    <label class="image" for="file_3" title="Buscar Imagen" v-else>
                                                        Imagen 3<br><i class="fa fa-plus-circle"></i>
                                                        <input type="file" id="file_3" style="display: none;"
                                                            v-on:change="changeImagen($event, 3)" accept="image/*">
                                                    </label>
                                                </div>
                                                <div style="margin-right: 20px;">
                                                    <label class="image_show" for="file_edit_4" title="Buscar Imagen"
                                                        v-if="producto.imagen_4">
                                                        <img id="show_image_4" class="img-fluid">
                                                        <input type="file" id="file_edit_4" style="display: none;"
                                                            v-on:change="changeImagen($event, 4)" accept="image/*">
                                                    </label>
                                                    <label class="image" for="file_4" title="Buscar Imagen" v-else>
                                                        Imagen 4<br><i class="fa fa-plus-circle"></i>
                                                        <input type="file" id="file_4" style="display: none;"
                                                            v-on:change="changeImagen($event, 4)" accept="image/*">
                                                    </label>
                                                </div>
                                                <div style="margin-right: 20px;">
                                                    <label class="image_show" for="file_edit_5" title="Buscar Imagen"
                                                        v-if="producto.imagen_5">
                                                        <img id="show_image_5" class="img-fluid">
                                                        <input type="file" id="file_edit_5" style="display: none;"
                                                            v-on:change="changeImagen($event, 5)" accept="image/*">
                                                    </label>
                                                    <label class="image" for="file_5" title="Buscar Imagen" v-else>
                                                        Imagen 5<br><i class="fa fa-plus-circle"></i>
                                                        <input type="file" id="file_5" style="display: none;"
                                                            v-on:change="changeImagen($event, 5)" accept="image/*">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    </div>
</div>
