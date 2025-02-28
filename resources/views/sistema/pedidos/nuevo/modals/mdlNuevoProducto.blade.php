<div class="modal fade" id="mdlNuevoProducto">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="busyNuevoProducto">
            <div class="modal-header" style="padding: 10px 15px">
                <h5 class="mb-0">NUEVO <span style="color: #929292; font-size: 17px; font-weight: 400;">PRODUCTO</span></h5>
                <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
            </div>
            <div class="modal-body" style="padding: 15px 15px;">
                <div class="row m-b-20">
                    <div class="col-md-12">
                        <div class="contorno-check">
                            <span class="contorno-texto">Producto</span>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">NOMBRE: </span>
                                        <div class="col-sm-9" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.nombre">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">NOMBRE SECUNDARIO: </span>
                                        <div class="col-sm-8" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.nombre_secundario">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">DESCRIPCIÓN: </span>
                                        <div class="col-sm-9" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.descripcion">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">UNIDAD: </span>
                                        <div class="col-sm-8" style="padding-left: 0px;">
                                            <select class="form-control form-control-sm" v-model="form_producto.unidad">
                                                <option value="NIU">NIU</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">MONEDA: </span>
                                        <div class="col-sm-8" style="padding-left: 0px;">
                                            <select class="form-control form-control-sm" v-model="form_producto.moneda">
                                                <option value="PEN">PEN</option>
                                                <option value="USD">USD</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-6">PRECIO UNITARIO: </span>
                                        <div class="col-sm-6" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.precio_unitario">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">TIPO AFECTACIÓN: </span>
                                        <div class="col-sm-8" style="padding-left: 0px;">
                                            <select class="form-control form-control-sm" v-model="form_producto.tipo_afectacion">
                                                <option value="10">GRAVADO</option>
                                                <option value="20">EXONERADO</option>
                                                <option value="30">INAFECTO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">CATEGORÍA: </span>
                                        <div class="col-sm-8" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.categoria">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">MARCA: </span>
                                        <div class="col-sm-8" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.marca">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">MODELO: </span>
                                        <div class="col-sm-8" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.modelo">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="incluye-igv" v-model="form_producto.incluye_igv">
                                                <label class="form-check-label" for="incluye-igv">Incluye IGV</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-6">CÓDIGO INTERNO: </span>
                                        <div class="col-sm-6" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.codigo_interno">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-5">CÓDIGO SUNAT: </span>
                                        <div class="col-sm-7" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.codigo_sunat">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-5">STOCK INICIAL: </span>
                                        <div class="col-sm-7" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.stock_inicial">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-5">STOCK MÍNIMO: </span>
                                        <div class="col-sm-7" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.stock_minimo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="maneja-series" v-model="form_producto.maneja_series">
                                                <label class="form-check-label" for="maneja-series">¿Maneja series?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-5">LINEA DE PRODUCTO: </span>
                                        <div class="col-sm-7" style="padding-left: 0px;">
                                            <input type="text" class="form-control form-control-sm" v-model="form_producto.linea_producto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div v-if="!imagen" style="border: 1px solid; border-radius: 5px; width: 100%; height: 100px;">
                                            </div>
                                            <img id="mostrar-imagen" class="img-fluid img-thumbnail rounded"
                                                alt="Busque una imagen" v-else>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group row">
                                        <span style="font-size: 11px; padding-right: 0;" class="col-sm-2">BUSCAR IMAGEN: </span>
                                        <div class="col-sm-10" style="padding-left: 0px;">
                                            <input type="file" accept="image/*" id="fle-imagen"
                                                class="form-control form-control-sm" @change="changeImagen">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-secondary" data-dismiss="modal">
                    <i class="fa fa-ban"> Cerrar</i>
                </button>
                <button class="btn btn-sm btn-primary" @click="guardarProducto">
                    <i class="fa fa-save"> Guardar</i>
                </button>
            </div>
        </div>
    </div>
</div>