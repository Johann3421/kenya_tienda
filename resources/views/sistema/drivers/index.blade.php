@extends('layouts.template')
@section('app-name')
    <title>KENYA - DRIVERS</title>
@endsection
@section('css')
    <style>
        .activado {
            background-color: #e8f2fc;
            color: #1c82e1;
        }

        .cell-1 {
            width: 5%;
        }

        .cell-2 {
            width: 15%;
        }

        .cell-3 {
            width: 35%;
        }

        .cell-4 {
            width: 15%;
        }

        .cell-5 {
            width: 20%;
        }

        .cell-6 {
            width: 10%;
        }

        .table.table-sm td,
        .table.table-sm th {
            vertical-align: middle;
        }

        .disabled {
            cursor: no-drop !important;
        }

        .font-green {
            color: green;
        }

        .label-sm {
            font-size: 11px;
            margin: 0;
        }

        .form_producto {
            position: relative;
        }

        .listProductos {
            position: absolute;
            z-index: 100;
            width: 100%;
            margin: 0;
            padding: 0;
            top: 58px;
            left: 0;
            /* border-top: 1px solid rgba(0,0,0,.2);
                                                border-radius: 10px; */
        }

        .item_producto {
            list-style: none;
            padding: 12px 18px 12px 18px;
            border-bottom: 1px solid rgba(0, 0, 0, .2);
            border-right: 1px solid rgba(0, 0, 0, .2);
            border-left: 1px solid rgba(0, 0, 0, .2);
            background-color: #ffffff;
            cursor: pointer;
        }

        .listProductos .item_producto:first-child {
            border-top: 1px solid rgba(0, 0, 0, .2);
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .listProductos .item_producto:last-child {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .item_producto:hover {
            background-color: #e3e4e5;
        }

        .displaynone {
            display: none;
        }

        .image_show {
            border: 1px dashed #ced4da;
            cursor: pointer;
            height: 110px;
            width: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .img-fluid {
            max-height: 100%;
        }

        .image {
            border: 1px dashed #ced4da;
            padding: 36px 10px;
            cursor: pointer;
            height: 110px;
            width: 100px;
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <div class="page-header breadcumb-sticky dash-sale"
        style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-truck-loading"></i>Drivers</h5>

                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="../"><i class="fas fa-home"
                                    style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="../">Drivers</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-driver">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>LISTA DE DRIVERS</h5>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <ul class="list-unstyled card-option" style="display: contents;">
                                <li class="full-card">
                                    <a href="#!" class="windows-button">
                                        <span title="Maximizar"><i class="feather icon-maximize"></i> </span>
                                        <span style="display:none"><i class="feather icon-minimize"></i> </span>
                                    </a>
                                </li>
                                <li class="close-card"><a href="#!" class="windows-button" title="Cerrar"><i
                                            class="feather icon-x"></i> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- MODAL --}}
                    <div class="modal fade" id="formularioModal" tabindex="-1" role="dialog" data-backdrop="static"
                        data-keyboard="false">
                        <div class="modal-dialog" :class="modal_size" role="document">
                            {{-- NUEVO --}}
                            <div class="modal-content" v-if="methods == 'create'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">NUEVO <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(DRIVER)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-row">
                                        <div class="form-group col-md-12 form_producto">
                                            <label for="search_producto" class="label-sm">PRODUCTO</label>

                                            <input type="text" id="search_producto" v-model="search_producto"
                                                class="form-control" :class="[errors.search_producto ? 'is-invalid' : '']"
                                                :readonly="loading" v-on:keyup="AutoBuscar">

                                            <small class="form-text error-color"
                                                v-if="errors.search_producto">@{{ errors.search_producto[0] }}</small>
                                            <ul v-if="productos.length !== 0 && search_producto.length !== 0"
                                                v-show="mostrar" class="listProductos">
                                                <li v-for="producto in productos" :value="producto.id"
                                                    class="item_producto"
                                                    v-on:click="Fila_producto(producto.id, producto, 'nuevo')">
                                                    @{{ producto.nombre }}</li>
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="categoria" class="label-sm">CATEGORIA</label>
                                            <select id="categoria" v-model="categoria" class="form-control fc-new"
                                                :class="[errors.categoria ? 'is-invalid' : '']">
                                                <option disabled value="">Seleccione un categoria</option>
                                                <option>AUDIO</option>
                                                <option>CHIPSET</option>
                                                <option>LAN</option>
                                                <option>SATA RAID</option>
                                                <option>VGA</option>
                                                <option>WIRELESS</option>
                                                <option>BLUETOOTH</option>
                                            </select>
                                            <small class="form-text error-color"
                                                v-if="errors.driver_nombre">@{{ errors.categoria[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="driver_nombre" class="label-sm">NOMBRE DEL DRIVER</label>
                                            <input type="text" id="driver_nombre" v-model="driver_nombre"
                                                class="form-control" :class="[errors.driver_nombre ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.driver_nombre">@{{ errors.driver_nombre[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="version" class="label-sm">VERSION</label>
                                            <input type="text" id="version" v-model="version" class="form-control"
                                                :class="[errors.version ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.version">@{{ errors.version[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="liberado" class="label-sm">LIBERADO</label>
                                            <input type="date" id="liberado" v-model="liberado" class="form-control"
                                                :class="[errors.liberado ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.liberado">@{{ errors.liberado[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="tamano" class="label-sm">TAMAÑO</label>
                                            <input type="number" id="tamano" v-model="tamano" class="form-control"
                                                :class="[errors.tamano ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.tamano">@{{ errors.tamano[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="unidad" class="label-sm">UNIDAD</label>
                                            <select id="unidad" v-model="unidad" class="form-control fc-new">
                                                <option disabled value="">Seleccione una unidad</option>
                                                <option>Bits</option>
                                                <option>KB</option>
                                                <option>MB</option>
                                                <option>GB</option>
                                                <option>TB</option>
                                                <option>PB</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="gravedad" class="label-sm">GRAVEDAD</label>
                                            <select id="gravedad" v-model="gravedad" class="form-control fc-new">
                                                <option disabled value="">Seleccione la importancia</option>
                                                <option>Crítico</option>
                                                <option>Recomendado</option>
                                                <option>Leve</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="estado" class="label-sm">ESTADO</label>
                                            <select id="estado" v-model="estado" class="form-control fc-new">
                                                <option disabled value="">Seleccione una opción</option>
                                                <option>Si</option>
                                                <option>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="link" class="label-sm">LINK</label>
                                            <input type="text" id="link" v-model="link" class="form-control"
                                                :class="[errors.link ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.link">@{{ errors.link[0] }}</small>
                                        </div>
                                        {{-- <div class="form-group col-lg-2">
                                            <label for="pdf_driver" class="label-sm">DRIVER</label>
                                            <label class="image" for="file" title="Buscar Imagen">
                                                ZIP
                                                <br>
                                                <i class="fa fa-plus-circle"></i>
                                                <input type="file" id="file" style="display: none;"
                                                    v-on:change="changeZip($event)" accept="DRIVERS/*">
                                            </label>
                                            <small class="form-text error-color"
                                                v-if="errors.pdf_driver">@{{ errors.pdf_driver[0] }}</small>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-primary btn-block event-btn" v-on:click="Store"
                                        :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Guardando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i
                                                class="feather icon-times"></i> Guardar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- NUEVO --}}

                            {{-- EDITAR --}}
                            <div class="modal-content" v-if="methods == 'edit'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">EDITAR <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(DRIVER)</span>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-row">
                                        <div class="form-group col-md-12 form_producto">
                                            <label for="search_producto" class="label-sm">PRODUCTO</label>
                                            <input type="text" id="search_producto" v-model="search_producto"
                                                class="form-control" :class="[errors.search_producto ? 'is-invalid' : '']"
                                                :readonly="loading" v-on:keyup="AutoBuscar">

                                            <input type="hidden" id="producto_id_actualizar"
                                                v-model="producto_id_actualizar" class="form-control"
                                                :class="[errors.producto_id_actualizar ? 'is-invalid' : '']"
                                                :readonly="loading">

                                            <ul v-if="productos.length !== 0 && search_producto.length !== 0"
                                                v-show="mostrar" class="listProductos">
                                                <li v-for="producto in productos" :value="producto.id"
                                                    class="item_producto"
                                                    v-on:click="Fila_producto(producto.id, producto, 'edit')">
                                                    @{{ producto.nombre }}</li>
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="categoria" class="label-sm">CATEGORIA</label>
                                            <select id="categoria" v-model="categoria" class="form-control fc-new">
                                                <option disabled value="">Seleccione un categoria</option>
                                                <option>AUDIO</option>
                                                <option>CHIPSET</option>
                                                <option>LAN</option>
                                                <option>SATA RAID</option>
                                                <option>VGA</option>
                                                <option>WIRELESS</option>
                                                <option>BLUETOOTH</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="driver_nombre" class="label-sm">DRIVER</label>
                                            <input type="text" id="driver_nombre" v-model="driver_nombre"
                                                class="form-control" :class="[errors.driver_nombre ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.driver_nombre">@{{ errors.driver_nombre[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="version" class="label-sm">VERSION</label>
                                            <input type="text" id="version" v-model="version" class="form-control"
                                                :class="[errors.version ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.version">@{{ errors.version[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="liberado" class="label-sm">LIBERADO</label>
                                            <input type="date" id="liberado" v-model="liberado" class="form-control"
                                                :class="[errors.liberado ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.liberado">@{{ errors.liberado[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="tamano" class="label-sm">TAMAÑO</label>
                                            <input type="number" id="tamano" v-model="tamano" class="form-control"
                                                :class="[errors.tamano ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.tamano">@{{ errors.tamano[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="unidad" class="label-sm">UNIDAD</label>
                                            <select id="unidad" v-model="unidad" class="form-control fc-new">
                                                <option disabled value="">Seleccione una unidad</option>
                                                <option>Bits</option>
                                                <option>KB</option>
                                                <option>MB</option>
                                                <option>GB</option>
                                                <option>TB</option>
                                                <option>PB</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="gravedad" class="label-sm">GRAVEDAD</label>
                                            <select id="gravedad" v-model="gravedad" class="form-control fc-new">
                                                <option disabled value="">Seleccione la importancia</option>
                                                <option>Crítico</option>
                                                <option>Recomendado</option>
                                                <option>Leve</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="estado" class="label-sm">ESTADO</label>
                                            <select id="estado" v-model="estado" class="form-control fc-new">
                                                <option disabled value="">Seleccione una opción</option>
                                                <option>Si</option>
                                                <option>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="link" class="label-sm">LINK</label>
                                            <input type="text" id="link" v-model="link" class="form-control"
                                                :class="[errors.link ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.link">@{{ errors.link[0] }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-info btn-block event-btn" v-on:click="Update"
                                        :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Actualizando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i
                                                class="feather icon-times"></i> Actualizar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- EDITAR --}}
                            {{-- DUPLICAR --}}
                            <div class="modal-content" v-if="methods == 'duplicate'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">DUPLICAR <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(DRIVER)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-row">
                                        <div class="form-group col-md-12 form_producto">
                                            <label for="search_producto" class="label-sm">PRODUCTO</label>
                                            <input type="text" id="search_producto" v-model="search_producto"
                                                class="form-control" :class="[errors.search_producto ? 'is-invalid' : '']"
                                                :readonly="loading" v-on:keyup="AutoBuscar">

                                            <input type="hidden" id="producto_id_actualizar"
                                                v-model="producto_id_actualizar" class="form-control"
                                                :class="[errors.producto_id_actualizar ? 'is-invalid' : '']"
                                                :readonly="loading">

                                            <ul v-if="productos.length !== 0 && search_producto.length !== 0"
                                                v-show="mostrar" class="listProductos">
                                                <li v-for="producto in productos" :value="producto.id"
                                                    class="item_producto"
                                                    v-on:click="Fila_producto(producto.id, producto, 'edit')">
                                                    @{{ producto.nombre }}</li>
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="categoria" class="label-sm">CATEGORIA</label>
                                            <select id="categoria" v-model="categoria" class="form-control fc-new">
                                                <option disabled value="">Seleccione un categoria</option>
                                                <option>AUDIO</option>
                                                <option>CHIPSET</option>
                                                <option>LAN</option>
                                                <option>SATA RAID</option>
                                                <option>VGA</option>
                                                <option>WIRELESS</option>
                                                <option>BLUETOOTH</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="driver_nombre" class="label-sm">DRIVER</label>
                                            <input type="text" id="driver_nombre" v-model="driver_nombre"
                                                class="form-control" :class="[errors.driver_nombre ? 'is-invalid' : '']"
                                                :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.driver_nombre">@{{ errors.driver_nombre[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="version" class="label-sm">VERSION</label>
                                            <input type="text" id="version" v-model="version" class="form-control"
                                                :class="[errors.version ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.version">@{{ errors.version[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="liberado" class="label-sm">LIBERADO</label>
                                            <input type="date" id="liberado" v-model="liberado" class="form-control"
                                                :class="[errors.liberado ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.liberado">@{{ errors.liberado[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="tamano" class="label-sm">TAMAÑO</label>
                                            <input type="number" id="tamano" v-model="tamano" class="form-control"
                                                :class="[errors.tamano ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.tamano">@{{ errors.tamano[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="unidad" class="label-sm">UNIDAD</label>
                                            <select id="unidad" v-model="unidad" class="form-control fc-new">
                                                <option disabled value="">Seleccione una unidad</option>
                                                <option>Bits</option>
                                                <option>KB</option>
                                                <option>MB</option>
                                                <option>GB</option>
                                                <option>TB</option>
                                                <option>PB</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="gravedad" class="label-sm">GRAVEDAD</label>
                                            <select id="gravedad" v-model="gravedad" class="form-control fc-new">
                                                <option disabled value="">Seleccione la importancia</option>
                                                <option>Crítico</option>
                                                <option>Recomendado</option>
                                                <option>Leve</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="estado" class="label-sm">ESTADO</label>
                                            <select id="estado" v-model="estado" class="form-control fc-new">
                                                <option disabled value="">Seleccione una opción</option>
                                                <option>Si</option>
                                                <option>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="link" class="label-sm">LINK</label>
                                            <input type="text" id="link" v-model="link" class="form-control"
                                                :class="[errors.link ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color"
                                                v-if="errors.link">@{{ errors.link[0] }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-info btn-block event-btn" v-on:click="Duplicate"
                                        :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Actualizando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i
                                                class="feather icon-times"></i> Duplicar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- DUPLICAR --}}
                            {{-- ELIMINAR --}}
                            <div class="modal-content" v-if="methods == 'delete'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">ELIMINAR <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(DRIVER)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <p class="text-center">
                                        Realmente desea eliminar el Driver <strong>"@{{ seleccion.driver_nombre }}"</strong>
                                    </p>
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-danger btn-block event-btn" v-on:click="Delete"
                                        :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Eliminando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i
                                                class="feather icon-times"></i> Eliminar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- ELIMINAR --}}
                        </div>
                    </div>
                    {{-- MODAL --}}

                    <div class="row">
                        <div class="mb-3 mt-3 col-md-9">
                            <button type="button" class="btn btn-icon btn-primary mr-2" style="min-width: 88px;"
                                data-toggle="modal" data-target="#formularioModal"
                                v-on:click="formularioModal('', null, 'create', null)">
                                <div style="font-size: 30px;"><i class="fas fa-plus"></i></div>
                                <div>Nuevo</div>
                            </button>

                            <button type="button" class="btn btn-icon btn-info mr-2" style="min-width: 88px;"
                                v-if="active != 0" data-toggle="modal" data-target="#formularioModal"
                                v-on:click="formularioModal('', active, 'edit', seleccion)">
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-info disabled mr-2" style="min-width: 88px;"
                                v-else>
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-success mr-2" style="min-width: 88px;"
                                v-if="active != 0" data-toggle="modal" data-target="#formularioModal"
                                v-on:click="formularioModal('', active, 'duplicate', seleccion)">
                                <div style="font-size: 30px;"><i class="fas fa-clone"></i></div>
                                <div>Duplicar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-success disabled mr-2"
                                style="min-width: 88px;" v-else>
                                <div style="font-size: 30px;"><i class="fas fa-clone"></i></div>
                                <div>Duplicar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-warning mr-2" style="min-width: 88px;"
                                v-if="active != 0" data-toggle="modal" data-target="#asignarSerieModal"
                                v-on:click="abrirAsignarSerie(seleccion)">
                                <div style="font-size: 30px;"><i class="fas fa-link"></i></div>
                                <div>Asignar Serie</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-warning disabled mr-2"
                                style="min-width: 88px;" v-else>
                                <div style="font-size: 30px;"><i class="fas fa-link"></i></div>
                                <div>Asignar Serie</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-danger mr-2" style="min-width: 88px;"
                                v-if="(active != 0)" data-toggle="modal" data-target="#formularioModal"
                                v-on:click="formularioModal('modal-sm', active, 'delete', seleccion.driver_nombre)">
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-danger disabled mr-2" style="min-width: 88px;"
                                v-else>
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>

                        </div>
                        <div class="mb-3 mt-3 col-md-3">
                            <div class="p-b-10">
                                <input type="text" class="form-control" id="seacrh" v-model="search"
                                    placeholder="Nombres del Driver." v-on:keyup.enter="Buscar">
                            </div>
                            <button class="btn btn-secondary btn-block" v-on:click="Buscar">Buscar</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="cell-1 text-center">#</th>
                                    <th class="cell-2 ">Producto</th>
                                    <th class="cell-3 text-center">Categoria</th>
                                    <th class="cell-4 text-center"><i class="fas fa-solid fa-screwdriver"></i> Driver</th>
                                    <th class="cell-5 text-center">Version</th>
                                    <th class="cell-6 text-center">Liberado</th>
                                    <th class="cell-7 text-center">Tamaño</th>
                                    <th class="cell-8 text-center">Gravedad</th>
                                    <th class="cell-9 text-center">Estado</th>
                                    <th class="cell-10 "><i class="fas fa-solid fa-download"></i>Descargar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="list-loading">
                                    <td colspan="7" class="text-center">
                                        <div>
                                            <div class="spinner-grow" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <span style="font-size: 30px; padding: 5px;">Cargando lista espere ...</span>
                                        </div>
                                    </td>
                                </tr>
                                <template v-if="listTable">
                                    <template v-if="listaRequest.length != 0">
                                        <tr v-for="(driver, index) in listaRequest"
                                            :class="{ activado: active == driver.id }"
                                            v-on:click="Fila_driver(driver.id, driver)" style="cursor: pointer;">
                                            <td class="text-center">@{{ (index + pagination.index + 1) }}</td>
                                            <td class="displaynone">
                                                @{{ driver.producto_id }}
                                            </td>
                                            <td>
                                                @{{ driver.producto_descripcion }}
                                            </td>
                                            <td class="text-center">
                                                @{{ driver.categoria }}
                                            </td>
                                            <td class="text-center">
                                                @{{ driver.driver_nombre }}
                                            </td>
                                            <td class="text-center">
                                                @{{ driver.version }}
                                            </td>
                                            <td class="text-center">
                                                @{{ driver.liberado }}
                                            </td>
                                            <td class="text-center">
                                                @{{ driver.tamano }}@{{ driver.unidad }}
                                            </td>
                                            <td class="text-center">
                                                @{{ driver.gravedad }}
                                            </td>
                                            <td class="text-center">
                                                @{{ driver.activo }}
                                            </td>
                                            <td>
                                                @{{ driver.link }}
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td colspan="7" class="text-center" style="font-size: 20px;">No existe
                                                ningun registro</td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div id="list-paginator" style="display: none;" class="row">
                        <div class="col-sm-4 text-left">
                            <div style="margin: 7px; font-size: 15px;">@{{ pagination.current_page + ' de ' + pagination.to + ' Páginas ' }}</div>
                        </div>
                        <div class="col-sm-4">
                            <nav class="text-center" aria-label="...">
                                <ul class="pagination" style="justify-content: center;">
                                    <a href="#" v-if="pagination.current_page > 1" class="pag-inicio-fin"
                                        title="Página inicio" v-on:click.prevent="changePage(1)">
                                        <i class="fas fa-step-backward"></i>
                                    </a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página inicio"><i
                                            class="fas fa-step-backward"></i>
                                    </a>
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;" title="Anterior"
                                            v-on:click.prevent="changePage(pagination.current_page - 1)">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item disabled" title="Anterior" v-else style="cursor: no-drop;"><a
                                            href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;"><i
                                                class="fas fa-angle-left"></i></a></li>
                                    <li class="page-item" v-for="page in pagesNumber"
                                        :class="[page == isActive ? 'active' : '']"><a href="#" class="page-link"
                                            v-on:click.prevent="changePage(page)">@{{ page }}</a></li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;" title="Siguiente"
                                            v-on:click.prevent="changePage(pagination.current_page + 1)">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>
                                    <li class="page-item disabled" title="Siguiente" v-else style="cursor: no-drop;"><a
                                            href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;"><i
                                                class="fas fa-angle-right"></i></a></li>

                                    <a href="#" v-if="pagination.current_page < pagination.last_page"
                                        class="pag-inicio-fin" title="Página final"
                                        v-on:click.prevent="changePage(pagination.last_page)"><i
                                            class="fas fa-step-forward"></i></a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página final"><i
                                            class="fas fa-step-forward"></i></a>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div style="margin: 7px; font-size: 15px;" v-if="to_pagination">@{{ to_pagination + ' de ' + pagination.total + ' Registros' }}</div>
                            <div style="margin: 7px; font-size: 15px;" v-else>0 de 0 Registros</div>
                        </div>
                    </div>
                    <!-- filepath: resources/views/sistema/drivers/index.blade.php -->
<!-- Modal Asignar Serie -->
<div class="modal fade" id="asignarSerieModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px 15px">
                <h5 class="mb-0">Asignar Series al Driver</h5>
                <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                    v-on:click="cerrarAsignarSerie" class="btn btn-danger btn-xs float-right"
                    style="padding: 0px 7px;">X</button>
            </div>
            <div class="modal-body" style="padding: 15px 15px;">
                <div class="form-group">
                    <label for="serie_asignar" class="label-sm">Números de Serie</label>
                    <textarea id="serie_asignar" v-model="serie_asignar" class="form-control" rows="4"
                        placeholder="Ejemplo:&#10;ABC123456&#10;DEF789012"></textarea>
                    <small class="form-text text-muted">
                        Ingresa <b>una serie por línea</b>. Puedes pegar varias a la vez.<br>
                        <span style="color:#888;">(Solo se mostrará este driver para las series indicadas)</span>
                    </small>
                </div>
            </div>
            <div class="modal-footer" style="padding: 10px 15px;">
                <button class="btn btn-warning btn-block event-btn" v-on:click="guardarAsignarSerie" :disabled="loading">
                    <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                    <span class="load-text" v-if="loading">Asignando...</span>
                    <span class="btn-text" v-if="!loading"><i class="fas fa-link"></i> Asignar</span>
                </button>
            </div>
        </div>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/views/drivers/driver.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/barcode.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.printarea.js') }}"></script>
@endsection
