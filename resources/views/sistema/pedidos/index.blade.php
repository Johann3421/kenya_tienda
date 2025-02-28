@extends('layouts.template')
@section('app-name')
    <title>Grupo kenya - Permisos</title>
@endsection
@section('css')
    <link rel="stylesheet" href="{{asset('css/views/soporte.css')}}">
    <style>
        .activado {
            background-color: #e8f2fc;
            color: #1c82e1;
        }
        .cell-1 { width: 5%; }
        .cell-2 { width: 15%; }
        .cell-3 { width: 35%; }
        .cell-4 { width: 15%; }
        .cell-5 { width: 15%; }

        .table.table-sm td, .table.table-sm th {
            vertical-align: middle;
        }
        .disabled {
            cursor: no-drop !important;
        }
        .font-green {
            color: green;
        }
        .lista-prov {
            position: absolute;
            border: 1px solid #929292;
            padding: 5px 0px;
            margin-top: 3px;
            background-color: #fff;
            z-index: 1;
            min-width: 91%;
        }
        .lista-prov ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .lista-prov li a {
            color: #000;
            padding: 2px 10px;
        }
        .lista-prov li:hover{
            background-color: #1c82e1;
        }
        .lista-prov li:hover a {
            color: #fff !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-address-card"></i> Perfiles</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="index.html"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Permisos</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-pedidos">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>LISTA DE PERMISOS</h5>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <ul class="list-unstyled card-option" style="display: contents;">
                                <li class="full-card"><a href="#!" class="windows-button"><span title="Maximizar"><i class="feather icon-maximize"></i> </span><span style="display:none"><i class="feather icon-minimize"></i> </span></a></li>
                                <li class="close-card"><a href="#!" class="windows-button" title="Cerrar"><i class="feather icon-x"></i> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- MODAL --}}
                    <div class="modal fade" id="formularioModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" :class="modal_size" role="document">
                            {{-- NUEVO --}}
                            <div class="modal-content" v-if="methods == 'create'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">NUEVO <span style="color: #929292; font-size: 17px; font-weight: 400;">(PEDIDO)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <template v-if="state != 'success'">
                                        <div class="row m-b-20">
                                            <div class="col-md-12">
                                                <div class="contorno-check">
                                                    <span class="contorno-texto">Registro</span>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group row m-b-0">
                                                                <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-4">FECHA REGISTRO :</span>
                                                                <div class="col-sm-8">
                                                                    {{-- <input type="date" id="fecha_registro" v-model="pedido.fecha_registro" max="{{date('Y-m-d')}}"
                                                                    class="form-control form-control-sm" :class="[errors.fecha_registro ? 'is-invalid' : '']" :readonly="loading">
                                                                    <small class="form-text error-color" v-if="errors.fecha_registro">@{{ errors.fecha_registro[0] }} </small> --}}
                                                                    <input type="text" id="fecha_registro" value="{{date('d/m/Y H:i')}}" class="form-control form-control-sm" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row m-b-0">
                                                                <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-4">FECHA ENTREGA :</span>
                                                                <div class="col-sm-8">
                                                                    <input type="date" id="fecha_entrega" v-model="pedido.fecha_entrega" min="{{date('Y-m-d')}}"
                                                                    class="form-control form-control-sm" :class="[errors.fecha_entrega ? 'is-invalid' : '']" :readonly="loading">
                                                                    <small class="form-text error-color" v-if="errors.fecha_entrega">@{{ errors.fecha_entrega[0] }} </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row m-b-0">
                                                                <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">VENDEDOR :</span>
                                                                <div class="col-sm-9">
                                                                    <select class="form-control form-control-sm" readonly>
                                                                        <option value="user">{{Auth::user()->nombres.' '.Auth::user()->ape_paterno.' '.Auth::user()->ape_materno}}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-20">
                                            <div class="col-md-5">
                                                <div class="contorno-check" style="padding: 6px 5px;">
                                                    <span class="contorno-texto">Tipo de Entrega</span>
                                                    <div style="padding-top: 5px;">
                                                        <select class="form-control form-control-sm" v-model="pedido.tipo_entrega" id="tipo_entrega" :readonly="loading">
                                                            <option value="LOCAL">ENTREGA EN LOCAL</option>
                                                            <option value="DOMICILIO">ENTREGA EN DOMICILIO</option>
                                                            <option value="AGENCIA">ENTREGA ENVIAR POR AGENCIA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-2" style="padding-right: 0px; padding-left: 0px;">
                                                <div class="contorno-check" style="padding: 6px 5px;">
                                                    <span class="contorno-texto">Forma de Envío</span>
                                                    <div style="padding-top: 4px;">
                                                        <select class="form-control form-control-sm" v-model="pedido.forma_envio" id="forma_envio">
                                                            <option value="AGENCIA">POR AGENCIA</option>
                                                            <option value="OLVA">POR OLVA</option>
                                                            <option value="OTROS">OTROS MEDIOS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-7">
                                                <div class="contorno-check" style="padding: 6px 5px;">
                                                    <span class="contorno-texto">Estado del Pedido</span>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="realizado" value="P1">
                                                        <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="realizado">REALIZADO</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="en_transito" value="P2">
                                                        <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="en_transito">EN TRANSITO</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="en_tienda" value="P3">
                                                        <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="en_tienda">EN TIENDA</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="entregado" value="P4">
                                                        <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="entregado">ENTREGADO</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="anulado" value="P5">
                                                        <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="anulado">ANULADO</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 m-t-20" v-if="pedido.tipo_entrega != 'LOCAL'">
                                                <div class="contorno-check" style="padding: 6px 5px;">
                                                    <span class="contorno-texto">Datos de Envío</span>
                                                    <div style="padding-top: 4px;">
                                                        <textarea v-model="pedido.detalle_envio" id="detalle_envio" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-20">
                                            <div class="col-md-12">
                                                <div class="contorno-check">
                                                    <span class="contorno-texto">Datos del Cliente</span>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group row m-b-0">
                                                                <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-4">DNI / RUC :</span>
                                                                <div class="col-sm-8 m-b-5">
                                                                    <input type="text" id="numero_documento" v-model="pedido.numero_documento" :readonly="loading" maxlength="11"
                                                                    class="form-control form-control-sm" :class="[errors.numero_documento ? 'is-invalid' : '']" v-on:keyup.enter="Documento"
                                                                    onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                                                    <small class="form-text error-color" v-if="errors.numero_documento">@{{ errors.numero_documento[0] }} </small>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <button type="button" class="btn btn-sm btn-info" style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;" v-on:click="Reniec"><i class="fas fa-search"></i> RENIEC</button>
                                                                    <button type="button" class="btn btn-sm btn-dark float-right" style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;" v-on:click="Sunat"><i class="fas fa-search"></i> SUNAT</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group row m-b-5">
                                                                <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">NOMBRES :</span>
                                                                <div class="col-sm-9">
                                                                    <input type="text" id="nombres" v-model="pedido.nombres" :readonly="loading"
                                                                    class="form-control form-control-sm" :class="[errors.nombres ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color" v-if="errors.nombres">@{{ errors.nombres[0] }} </small>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row m-b-0">
                                                                <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">DIRECCIÓN :</span>
                                                                <div class="col-sm-9">
                                                                    <input type="text" id="direccion" v-model="pedido.direccion" :readonly="loading"
                                                                    class="form-control form-control-sm" :class="[errors.direccion ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color" v-if="errors.direccion">@{{ errors.direccion[0] }} </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row m-b-5">
                                                                <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">EMAIL :</span>
                                                                <div class="col-sm-9">
                                                                    <input type="email" id="email" v-model="pedido.email" :readonly="loading"
                                                                    class="form-control form-control-sm" :class="[errors.email ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color" v-if="errors.email">@{{ errors.email[0] }} </small>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row m-b-0">
                                                                <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">CELULAR :</span>
                                                                <div class="col-sm-9">
                                                                    <input type="text" id="celular" v-model="pedido.celular" :readonly="loading" maxlength="9"
                                                                    onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}"
                                                                    class="form-control form-control-sm" :class="[errors.celular ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color" v-if="errors.celular">@{{ errors.celular[0] }} </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-20">
                                            <div class="col-md-12">
                                                <div class="contorno-check">
                                                    <span class="contorno-texto">Detalles del Pedido</span>
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="detalle_descripcion">Descripción</label>
                                                                <input type="text" id="detalle_descripcion" v-model="detalle_descripcion" class="form-control form-control-sm" :class="[errors.detalle_descripcion ? 'is-invalid' : '']"
                                                                autocomplete="on" :readonly="loading" v-on:keyup.enter="Next('detalle_precio')">
                                                                <small class="form-text error-color" v-if="errors.detalle_descripcion">@{{ errors.detalle_descripcion[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="detalle_precio">Precio</label>
                                                                <input type="number" id="detalle_precio" v-model="detalle_precio" class="form-control form-control-sm" :class="[errors.detalle_precio ? 'is-invalid' : '']"
                                                                :readonly="loading" v-on:keyup.enter="Next('detalle_cantidad')">
                                                                <small class="form-text error-color" v-if="errors.detalle_precio">@{{ errors.detalle_precio[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="detalle_cantidad">Cantidad</label>
                                                                <input type="number" id="detalle_cantidad" v-model="detalle_cantidad" class="form-control form-control-sm" :class="[errors.detalle_cantidad ? 'is-invalid' : '']"
                                                                :readonly="loading" v-on:keyup.enter="addDetalles">
                                                                <small class="form-text error-color" v-if="errors.detalle_cantidad">@{{ errors.detalle_cantidad[0] }} </small>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="detalle_cantidad_proveedor">Cantidad(provee.)</label>
                                                                <input type="number" id="detalle_cantidad_proveedor" v-model="detalle_cantidad_proveedor" class="form-control form-control-sm" :class="[errors.detalle_cantidad_proveedor ? 'is-invalid' : '']" :readonly="loading">
                                                                <small class="form-text error-color" v-if="errors.detalle_cantidad_proveedor">@{{ errors.detalle_cantidad_proveedor[0] }} </small>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-md-1">
                                                            <button class="btn btn-outline-dark btn-block" style="padding: 8px 0; margin: 1px 0 4px 0;" v-on:click="addDetalles" title="Agregar Producto">
                                                                <i class="fas fa-cart-plus" style="font-size: 20px;"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <table class="table table-bordered table-sm" style="width: 100%; margin-bottom: 7px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="55%">Descripción</th>
                                                                        <th width="10%" class="text-center">Precio</th>
                                                                        <th width="20%" class="text-center">Cantidad</th>
                                                                        <th width="10%" class="text-center">Importe</th>
                                                                        <th width="5%" class="text-center"><i style="color: #505050; font-size: 13px;" class="fas fa-trash"></i></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(detalle, index) in listDetalles">
                                                                        <td style="padding: 0px 6px !important;">@{{detalle.descripcion}}</td>
                                                                        <td class="text-center" style="padding: 0px 6px !important;">@{{detalle.precio}}</td>
                                                                        <td class="text-center" style="padding: 0px 6px !important;">@{{detalle.cantidad}}</td>
                                                                        <td class="text-center" style="padding: 0px 6px !important;">@{{(detalle.importe).toFixed(2)}}</td>
                                                                        <td class="text-center" style="padding: 0px 6px !important;">
                                                                            <a href="#" title="Eliminar Fila" style="color: red; padding: 0 !important; font-size: 13px;" v-on:click="deleteDetalles(index)">
                                                                                <i class="fas fa-times-circle"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr v-if="listDetalles.length == 0">
                                                                        <td colspan="6" class="text-center" style="font-size: 12px;">NO HAY REGISTROS</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="row m-t-10">
                                                        <div class="col-md-6">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="observacion">Observacion</label>
                                                                <textarea id="observacion" v-model="pedido.observacion" class="form-control" :readonly="loading"
                                                                style="max-width: 100%; padding: 0px 6px !important; font-size: 11px !important;"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="acuenta">A cuenta</label>
                                                                <input type="number" id="acuenta" v-model="pedido.acuenta" class="form-control form-control-sm" style="background-color: #fdff83; font-size: 17px !important; text-align: right;" :class="[errors.acuenta ? 'is-invalid' : '']" :readonly="loading">
                                                                <small class="form-text error-color" v-if="errors.acuenta">@{{ errors.acuenta[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="costo_total">Total</label>
                                                                <input type="number" id="costo_total" v-model="pedido.costo_total" class="form-control form-control-sm" style="background-color: #ffc883; font-size: 17px !important; text-align: right;" :class="[errors.costo_total ? 'is-invalid' : '']" readonly="true">
                                                                <small class="form-text error-color" v-if="errors.costo_total">@{{ errors.costo_total[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="saldo_total">Resta</label>
                                                                <input type="number" id="saldo_total" v-model="pedido.saldo_total" class="form-control form-control-sm" style="background-color: #cccccc; font-size: 17px !important; text-align: right;" :class="[errors.saldo_total ? 'is-invalid' : '']" readonly="true">
                                                                <small class="form-text error-color" v-if="errors.saldo_total">@{{ errors.saldo_total[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 mt-3">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <button class="btn btn-danger btn-block" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)"><i class="fas fa-times"></i> Cancelar</button>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <button class="btn btn-primary btn-block event-btn" v-on:click="Store" :disabled="loading">
                                                            <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                                            <span class="load-text" v-if="loading">Guardando...</span>
                                                            <span class="btn-text" v-if="!loading" style=""><i class="fas fa-save"></i> Guardar</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="col-sm-12 mt-3" v-else>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-icon btn-warning mr-2 btn-block" style="min-width: 88px;" v-on:click="Recibo(result_id)">
                                                    <div style="font-size: 30px;"><i class="fas fa-print"></i></div>
                                                    <div>Imprimir Recibo</div>
                                                </button>
                                                {{-- <button class="btn btn-warning btn-block"  v-on:click="Recibo(result_id)"><i class="fas fa-print"></i> Imprimir Recibo</button> --}}
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-icon btn-dark mr-2 btn-block" style="min-width: 88px;" v-on:click="codigoBarra(result_id, result_barra)">
                                                    <div style="font-size: 30px;"><i class="fas fa-barcode"></i></div>
                                                    <div>Imprimir Codigo Barras</div>
                                                </button>
                                                {{-- <button class="btn btn-dark btn-block" v-on:click="codigoBarra(result_id, result_barra)"><i class="fas fa-barcode"></i> Imprimir Codigo Barras</button> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- NUEVO --}}

                            {{-- EDITAR --}}
                            <div class="modal-content" v-if="methods == 'edit'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">
                                        EDITAR <span style="color: #929292; font-size: 17px; font-weight: 400;">(PEDIDO: @{{nombre}})</span>
                                        <button class="btn btn-warning" style="margin-left: 120px;" v-on:click="Recibo(result_id)"><i class="fas fa-print"></i> Imprimir Recibo</button>
                                        <button class="btn btn-dark" v-on:click="codigoBarra(result_id, result_barra)"><i class="fas fa-barcode"></i> Imprimir Codigo Barras</button>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <div class="contorno-check">
                                                <span class="contorno-texto">Registro</span>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row m-b-0">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-4">FECHA REGISTRO :</span>
                                                            <div class="col-sm-8">
                                                                {{-- <input type="date" id="fecha_registro" v-model="pedido.fecha_registro" max="{{date('Y-m-d')}}"
                                                                class="form-control form-control-sm" :class="[errors.fecha_registro ? 'is-invalid' : '']" :readonly="loading">
                                                                <small class="form-text error-color" v-if="errors.fecha_registro">@{{ errors.fecha_registro[0] }} </small> --}}
                                                                <input type="text" id="fecha_registro" :value="pedido.fecha_registro" class="form-control form-control-sm" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row m-b-0">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-4">FECHA ENTREGA :</span>
                                                            <div class="col-sm-8">
                                                                <input type="date" id="fecha_entrega" v-model="pedido.fecha_entrega" min="{{date('Y-m-d')}}"
                                                                class="form-control form-control-sm" :class="[errors.fecha_entrega ? 'is-invalid' : '']" :readonly="loading">
                                                                <small class="form-text error-color" v-if="errors.fecha_entrega">@{{ errors.fecha_entrega[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row m-b-0">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">VENDEDOR :</span>
                                                            <div class="col-sm-9">
                                                                <select class="form-control form-control-sm" readonly>
                                                                    <option value="user">{{Auth::user()->nombres.' '.Auth::user()->ape_paterno.' '.Auth::user()->ape_materno}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-b-20">
                                        <div class="col-md-5">
                                            <div class="contorno-check" style="padding: 6px 5px;">
                                                <span class="contorno-texto">Tipo de Entrega</span>
                                                <div style="padding-top: 5px;">
                                                    <select class="form-control form-control-sm" v-model="pedido.tipo_entrega" id="tipo_entrega" :readonly="loading">
                                                        <option value="LOCAL">ENTREGA EN LOCAL</option>
                                                        <option value="DOMICILIO">ENTREGA EN DOMICILIO</option>
                                                        <option value="AGENCIA">ENTREGA ENVIAR POR AGENCIA</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-2" style="padding-right: 0px; padding-left: 0px;">
                                            <div class="contorno-check" style="padding: 6px 5px;">
                                                <span class="contorno-texto">Forma de Envío</span>
                                                <div style="padding-top: 4px;">
                                                    <select class="form-control form-control-sm" v-model="pedido.forma_envio" id="forma_envio">
                                                        <option value="AGENCIA">POR AGENCIA</option>
                                                        <option value="OLVA">POR OLVA</option>
                                                        <option value="OTROS">OTROS MEDIOS</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-7">
                                            <div class="contorno-check" style="padding: 6px 5px;">
                                                <span class="contorno-texto">Estado del Pedido</span>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="realizado" value="P1">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="realizado">REALIZADO</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="en_transito" value="P2">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="en_transito">EN TRANSITO</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="en_tienda" value="P3">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="en_tienda">EN TIENDA</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="entregado" value="P4">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="entregado">ENTREGADO</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio" v-model="pedido.estado_entrega" id="anulado" value="P5">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 6px 4px;" for="anulado">ANULADO</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 m-t-20" v-if="pedido.tipo_entrega != 'LOCAL'">
                                            <div class="contorno-check" style="padding: 6px 5px;">
                                                <span class="contorno-texto">Datos de Envío</span>
                                                <div style="padding-top: 4px;">
                                                    <textarea v-model="pedido.detalle_envio" id="detalle_envio" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <div class="contorno-check">
                                                <span class="contorno-texto">Datos del Cliente</span>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group row m-b-0">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-4">DNI / RUC :</span>
                                                            <div class="col-sm-8 m-b-5">
                                                                <input type="text" id="numero_documento" v-model="pedido.numero_documento" :readonly="loading" maxlength="11"
                                                                class="form-control form-control-sm" :class="[errors.numero_documento ? 'is-invalid' : '']"  v-on:keyup.enter="Documento"
                                                                onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                                                <small class="form-text error-color" v-if="errors.numero_documento">@{{ errors.numero_documento[0] }} </small>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <button type="button" class="btn btn-sm btn-info" style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;" v-on:click="Reniec"><i class="fas fa-search"></i> RENIEC</button>
                                                                <button type="button" class="btn btn-sm btn-dark float-right" style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;" v-on:click="Sunat"><i class="fas fa-search"></i> SUNAT</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group row m-b-5">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">NOMBRES :</span>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="nombres" v-model="pedido.nombres" :readonly="loading"
                                                                class="form-control form-control-sm" :class="[errors.nombres ? 'is-invalid' : '']">
                                                                <small class="form-text error-color" v-if="errors.nombres">@{{ errors.nombres[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row m-b-0">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">DIRECCIÓN :</span>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="direccion" v-model="pedido.direccion" :readonly="loading"
                                                                class="form-control form-control-sm" :class="[errors.direccion ? 'is-invalid' : '']">
                                                                <small class="form-text error-color" v-if="errors.direccion">@{{ errors.direccion[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row m-b-5">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">EMAIL :</span>
                                                            <div class="col-sm-9">
                                                                <input type="email" id="email" v-model="pedido.email" :readonly="loading"
                                                                class="form-control form-control-sm" :class="[errors.email ? 'is-invalid' : '']">
                                                                <small class="form-text error-color" v-if="errors.email">@{{ errors.email[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row m-b-0">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">CELULAR :</span>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="celular" v-model="pedido.celular" :readonly="loading" maxlength="9"
                                                                onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}"
                                                                class="form-control form-control-sm" :class="[errors.celular ? 'is-invalid' : '']">
                                                                <small class="form-text error-color" v-if="errors.celular">@{{ errors.celular[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  m-b-20">
                                        <div class="col-md-12">
                                            <div class="contorno-check">
                                                <span class="contorno-texto">Detalles del Pedido</span>
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="detalle_descripcion">Descripción</label>
                                                            <input type="text" id="detalle_descripcion" v-model="detalle_descripcion" class="form-control form-control-sm" :class="[errors.detalle_descripcion ? 'is-invalid' : '']"
                                                            autocomplete="on" :readonly="loading" v-on:keyup.enter="Next('detalle_precio')">
                                                            <small class="form-text error-color" v-if="errors.detalle_descripcion">@{{ errors.detalle_descripcion[0] }} </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="detalle_precio">Precio</label>
                                                            <input type="number" id="detalle_precio" v-model="detalle_precio" class="form-control form-control-sm" :class="[errors.detalle_precio ? 'is-invalid' : '']"
                                                            :readonly="loading" v-on:keyup.enter="Next('detalle_cantidad')">
                                                            <small class="form-text error-color" v-if="errors.detalle_precio">@{{ errors.detalle_precio[0] }} </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="detalle_cantidad">Cantidad</label>
                                                            <input type="number" id="detalle_cantidad" v-model="detalle_cantidad" class="form-control form-control-sm" :class="[errors.detalle_cantidad ? 'is-invalid' : '']"
                                                            :readonly="loading"  v-on:keyup.enter="addDetalles">
                                                            <small class="form-text error-color" v-if="errors.detalle_cantidad">@{{ errors.detalle_cantidad[0] }} </small>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="detalle_cantidad_proveedor">Cantidad(provee.)</label>
                                                            <input type="number" id="detalle_cantidad_proveedor" v-model="detalle_cantidad_proveedor" class="form-control form-control-sm" :class="[errors.detalle_cantidad_proveedor ? 'is-invalid' : '']" :readonly="loading">
                                                            <small class="form-text error-color" v-if="errors.detalle_cantidad_proveedor">@{{ errors.detalle_cantidad_proveedor[0] }} </small>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-md-1">
                                                        <button class="btn btn-outline-dark btn-block" style="padding: 8px 0; margin: 1px 0 4px 0;" v-on:click="addDetallesEdit" title="Agregar Producto">
                                                            <i class="fas fa-cart-plus" style="font-size: 20px;"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table class="table table-bordered table-sm" style="width: 100%; margin-bottom: 7px;">
                                                            <thead>
                                                                <tr>
                                                                    <th width="55%">Descripción</th>
                                                                    <th width="10%" class="text-center">Precio</th>
                                                                    <th width="20%" class="text-center">Cantidad</th>
                                                                    <th width="10%" class="text-center">Importe</th>
                                                                    <th width="5%" class="text-center"><i style="color: #505050; font-size: 13px;" class="fas fa-trash"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(detalle, index) in listDetalles">
                                                                    <td style="padding: 0px 6px !important;">@{{detalle.descripcion}}</td>
                                                                    <td class="text-center" style="padding: 0px 6px !important;">@{{detalle.precio}}</td>
                                                                    <td class="text-center" style="padding: 0px 6px !important;">@{{detalle.cantidad}}</td>
                                                                    <td class="text-center" style="padding: 0px 6px !important;">@{{detalle.importe}}</td>
                                                                    <td class="text-center" style="padding: 0px 6px !important;">
                                                                        <a href="#" title="Eliminar Fila" style="color: red; padding: 0 !important; font-size: 13px;" v-on:click="deleteDetallesEdit(detalle.id, index)">
                                                                            <i class="fas fa-times-circle"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr v-if="listDetalles.length == 0">
                                                                    <td colspan="6" class="text-center" style="font-size: 12px;">NO HAY REGISTROS</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="row m-t-10">
                                                    <div class="col-md-6">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="observacion">Observacion</label>
                                                            <textarea id="observacion" v-model="pedido.observacion" class="form-control" :readonly="loading"
                                                            style="max-width: 100%; padding: 0px 6px !important; font-size: 11px !important;"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="acuenta">A cuenta</label>
                                                            <input type="number" id="acuenta" v-model="pedido.acuenta" class="form-control form-control-sm" style="background-color: #fdff83; font-size: 17px !important; text-align: right;" :class="[errors.acuenta ? 'is-invalid' : '']" :readonly="loading">
                                                            <small class="form-text error-color" v-if="errors.acuenta">@{{ errors.acuenta[0] }} </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="costo_total">Total</label>
                                                            <input type="number" id="costo_total" v-model="pedido.costo_total" class="form-control form-control-sm" style="background-color: #ffc883; font-size: 17px !important; text-align: right;" :class="[errors.costo_total ? 'is-invalid' : '']" :readonly="loading">
                                                            <small class="form-text error-color" v-if="errors.costo_total">@{{ errors.costo_total[0] }} </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="saldo_total">Resta</label>
                                                            <input type="number" id="saldo_total" v-model="pedido.saldo_total" class="form-control form-control-sm" style="background-color: #cccccc; font-size: 17px !important; text-align: right;" :class="[errors.saldo_total ? 'is-invalid' : '']" :readonly="loading" readonly="true">
                                                            <small class="form-text error-color" v-if="errors.saldo_total">@{{ errors.saldo_total[0] }} </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="border: 5px solid #c7c7c7;">
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <div class="contorno-check">
                                                <span class="contorno-texto">Datos del Proveedor</span>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group row m-b-0">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-4">RUC :</span>
                                                            <div class="col-sm-8 m-b-5">
                                                                <input type="text" id="ruc_proveedor" v-model="proveedor.ruc" :readonly="loading" maxlength="11"
                                                                class="form-control form-control-sm" :class="[errors.ruc_proveedor ? 'is-invalid' : '']" v-on:keyup.enter="Proveedor"
                                                                onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                                                <small class="form-text error-color" v-if="errors.ruc_proveedor">@{{ errors.ruc_proveedor[0] }} </small>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <button type="button" class="btn btn-sm btn-dark float-right" style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;" v-on:click="Proveedor"><i class="fas fa-search"></i> SUNAT</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group row m-b-5">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">NOMBRES :</span>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="nombres_proveedor" v-model="proveedor.nombres" :readonly="loading"
                                                                class="form-control form-control-sm" :class="[errors.nombres_proveedor ? 'is-invalid' : '']" v-on:keyup.enter="searchProveedor" v-on:click="view_listProveedores = !view_listProveedores">
                                                                <small class="form-text error-color" v-if="errors.nombres_proveedor">@{{ errors.nombres_proveedor[0] }} </small>
                                                                <div class="lista-prov" v-if="view_listProveedores && (listProveedores.length > 0)">
                                                                    <ul>
                                                                        <li v-for="iprov in listProveedores"><a href="#" v-on:click="selProveedor(iprov)">@{{iprov.nombres}}</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row m-b-0">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">DIRECCIÓN :</span>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="direccion_proveedor" v-model="proveedor.direccion" :readonly="loading"
                                                                class="form-control form-control-sm" :class="[errors.direccion_proveedor ? 'is-invalid' : '']">
                                                                <small class="form-text error-color" v-if="errors.direccion_proveedor">@{{ errors.direccion_proveedor[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group row m-b-5">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">EMAIL :</span>
                                                            <div class="col-sm-9">
                                                                <input type="email" id="email_proveedor" v-model="proveedor.email" :readonly="loading"
                                                                class="form-control form-control-sm" :class="[errors.email_proveedor ? 'is-invalid' : '']">
                                                                <small class="form-text error-color" v-if="errors.email_proveedor">@{{ errors.email_proveedor[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row m-b-0">
                                                            <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-3">CELULAR :</span>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="celular_proveedor" v-model="proveedor.celular" :readonly="loading" maxlength="9"
                                                                onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}" v-on:keyup.enter="addProveedor"
                                                                class="form-control form-control-sm" :class="[errors.celular_proveedor ? 'is-invalid' : '']">
                                                                <small class="form-text error-color" v-if="errors.celular_proveedor">@{{ errors.celular_proveedor[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-outline-dark btn-block" style="padding: 13px 0px;" title="Agregar Proveedor" v-on:click="addProveedor"><i class="fas fa-truck-loading" style="font-size: 20px;"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" v-if="detalle_proveedor.length > 0">
                                        <div class="col-md-12">
                                            <div class="contorno-check" style="background-color: #fbffc7;">
                                                <span class="contorno-texto">Detalles del Pedido del Proveedor</span>

                                                <div v-for="(detalle, index) in detalle_proveedor" class="contorno-check m-b-10 m-t-10" style="background-color: #fff;">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <p style="margin: 0;">
                                                                <i class="fas fa-truck-loading"></i>
                                                                <span v-if="detalle.ruc">| RUC: @{{detalle.ruc}}</span> | @{{detalle.nombres}} |
                                                                <small>CELULAR: @{{detalle.celular}}</small>
                                                                <a href="#" style="position: absolute; right: 5px; top: -8px; padding: 0 5px;" class="btn btn-sm btn-danger" v-on:click="deleteProveedor(index, detalle.id)" title="Eliminar Proveedor"><i class="fas fa-times"></i></a>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="row" v-if="detalle_proveedor.length == (index+1)">
                                                        <div class="col-md-7">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="dp_pedido">Pedido</label>
                                                                <select id="dp_pedido" v-model="dp_pedido" class="form-control form-control-sm" :class="[errors.dp_pedido ? 'is-invalid' : '']" :readonly="loading" v-on:change="pedidoProveedor">
                                                                    <option value=""> --- Seleccione --- </option>
                                                                    <option v-for="pedido in listPedido" :value="pedido">@{{pedido.descripcion}}</option>
                                                                </select>
                                                                <small class="form-text error-color" v-if="errors.dp_pedido">@{{ errors.dp_pedido[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="dp_precio">Precio</label>
                                                                <input type="number" id="dp_precio" v-model="dp_precio" class="form-control form-control-sm" :class="[errors.dp_precio ? 'is-invalid' : '']"
                                                                :readonly="loading" v-on:keyup.enter="Next('dp_cantidad')">
                                                                <small class="form-text error-color" v-if="errors.dp_precio">@{{ errors.dp_precio[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="dp_cantidad">Cantidad</label>
                                                                <input type="number" id="dp_cantidad" v-model="dp_cantidad" class="form-control form-control-sm" :class="[errors.dp_cantidad ? 'is-invalid' : '']"
                                                                :readonly="loading"  v-on:keyup.enter="addDetallesProveedor(index)">
                                                                <small class="form-text error-color" v-if="errors.dp_cantidad">@{{ errors.dp_cantidad[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button class="btn btn-outline-dark btn-block" style="padding: 8px 0; margin: 1px 0 4px 0;" v-on:click="addDetallesProveedor(index)" title="Agregar">
                                                                <i class="fas fa-cart-plus" style="font-size: 20px;"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="row" v-if="detalle.detalles">
                                                        <div class="col-lg-12">
                                                            <table class="table table-bordered table-sm" style="width: 100%; margin-bottom: 7px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="55%">Descripción</th>
                                                                        <th width="10%" class="text-center">Precio</th>
                                                                        <th width="20%" class="text-center">Cantidad</th>
                                                                        <th width="10%" class="text-center">Importe</th>
                                                                        <th width="5%" class="text-center"><i style="color: #505050; font-size: 13px;" class="fas fa-trash"></i></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(deta, indice) in detalle.detalles">
                                                                        <td style="padding: 0px 6px !important;">@{{deta.pedido_descripcion}}</td>
                                                                        <td class="text-center" style="padding: 0px 6px !important;">@{{deta.precio}}</td>
                                                                        <td class="text-center" style="padding: 0px 6px !important;">@{{deta.cantidad}}</td>
                                                                        <td class="text-center" style="padding: 0px 6px !important;">@{{deta.importe}}</td>
                                                                        <td class="text-center" style="padding: 0px 6px !important;">
                                                                            <a href="#" title="Eliminar Fila" style="color: red; padding: 0 !important; font-size: 13px;" v-on:click="deleteDetallesProveedor(indice, index)">
                                                                                <i class="fas fa-times-circle"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr v-if="listDetalles.length == 0">
                                                                        <td colspan="6" class="text-center" style="font-size: 12px;">NO HAY REGISTROS</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 mt-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <button class="btn btn-danger btn-block" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)"><i class="fas fa-times"></i> Cancelar</button>
                                                </div>
                                                <div class="col-sm-6">
                                                    <button class="btn btn-info btn-block event-btn" v-on:click="Update" :disabled="loading">
                                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                                        <span class="load-text" v-if="loading">Actualizando...</span>
                                                        <span class="btn-text" v-if="!loading" style=""><i class="fas fa-save"></i> Actualizar</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-info btn-block event-btn" v-on:click="Update" :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Actualizando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i class="feather icon-times"></i> Actualizar</span>
                                    </button>
                                </div> --}}
                            </div>
                            {{-- EDITAR --}}

                            {{-- ELIMINAR --}}
                            <div class="modal-content" v-if="methods == 'delete'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">ELIMINAR <span style="color: #929292; font-size: 17px; font-weight: 400;">(PERMISO)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <p class="text-center">
                                        Realmente desea eliminar el Pedido <strong>"@{{nombre}}"</strong>
                                    </p>
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-danger btn-block event-btn" v-on:click="Delete" :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Eliminando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i class="feather icon-times"></i> Eliminar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- ELIMINAR --}}
                        </div>
                    </div>
                    {{-- MODAL --}}

                    <div class="mb-3 mt-3">
                        <button type="button" class="btn btn-icon btn-primary mr-2" style="min-width: 88px;"
                        data-toggle="modal" data-target="#formularioModal" v-on:click="formularioModal('modal-lg', null, 'create', null)">
                            <div style="font-size: 30px;"><i class="fas fa-plus"></i></div>
                            <div>Nuevo</div>
                        </button>

                        <button type="button" class="btn btn-icon btn-info mr-2" style="min-width: 88px;" v-if="active != 0"
                        data-toggle="modal" data-target="#formularioModal" v-on:click="formularioModal('modal-lg', active, 'edit', seleccion)">
                            <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                            <div>Editar</div>
                        </button>
                        <button type="button" class="btn btn-icon btn-info disabled mr-2" style="min-width: 88px;" v-else>
                            <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                            <div>Editar</div>
                        </button>

                        <button type="button" class="btn btn-icon btn-warning mr-2" style="min-width: 88px;" v-if="active != 0" v-on:click="Recibo(active)">
                            <div style="font-size: 30px;"><i class="fas fa-print"></i></div>
                            <div>Recibo</div>
                        </button>
                        <button type="button" class="btn btn-icon btn-warning disabled mr-2" style="min-width: 88px;" v-else>
                            <div style="font-size: 30px;"><i class="fas fa-print"></i></div>
                            <div>Recibo</div>
                        </button>

                        <button type="button" class="btn btn-icon btn-dark mr-2" style="min-width: 88px;" v-if="active != 0"
                            v-on:click="codigoBarra(active, seleccion.codigo_barras)">
                            <div style="font-size: 30px;"><i class="fas fa-barcode"></i></div>
                            <div>Barra</div>
                        </button>
                        <button type="button" class="btn btn-icon btn-dark disabled mr-2" style="min-width: 88px;" v-else>
                            <div style="font-size: 30px;"><i class="fas fa-barcode"></i></div>
                            <div>Barra</div>
                        </button>

                        <button type="button" class="btn btn-icon btn-danger mr-2" style="min-width: 88px;" v-if="active != 0"
                        data-toggle="modal" data-target="#formularioModal" v-on:click="formularioModal('modal-sm', active, 'delete', seleccion)">
                            <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                            <div>Eliminar</div>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger disabled mr-2" style="min-width: 88px;" v-else>
                            <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                            <div>Eliminar</div>
                        </button>

                        <div style="display: inline-flex; margin-top: 1px; vertical-align: top;">
                            <ul class="estados">
                                <li><a href="#" v-on:click="SearchEstado('P1')">Realizado <span class="estado-numero">@{{estados.realizado}}</span></a></li>
                                <li><a href="#" v-on:click="SearchEstado('P2')">En transito <span class="estado-numero">@{{estados.transito}}</span></a></li>
                                <li><a href="#" v-on:click="SearchEstado('P3')">En tienda <span class="estado-numero">@{{estados.tienda}}</span></a></li>
                                <li><a href="#" v-on:click="SearchEstado('P4')">Entregado <span class="estado-numero">@{{estados.entregado}}</span></a></li>
                                <li><a href="#" v-on:click="SearchEstado('P5')">Cancelado <span class="estado-numero">@{{estados.cancelado}}</span></a></li>
                            </ul>
                        </div>

                        <div class="float-right">
                            <select v-model="search_por" id="search_por" class="form-control fc-new" style="color: #6f6f6f; font-size: 13px; font-weight: 200 !important;" disabled>
                                <option value="">---- Buscar por ----</option>
                                <option value="codigo_barras">Buscar: Código de barras</option>
                                <option value="cliente">Buscar: Cliente</option>
                                <option value="estado">Buscar: Estado</option>
                            </select>
                            <div class="input-group input-group-sm">
                                <select v-model="search" id="search" class="form-control" v-if="search_por == 'estado'" style="min-width: 210px;">
                                    <option value="">--- TODOS ----</option>
                                    <option value="P1">REALIZADO</option>
                                    <option value="P2">EN TRANSITO</option>
                                    <option value="P3">EN TIENDA</option>
                                    <option value="P4">ENTREGADO</option>
                                    <option value="P5">CANCELADO</option>
                                </select>
                                <input type="text" id="search" v-model="search" class="form-control" v-on:keyup.enter="Buscar" v-else style="min-width: 210px;">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" v-on:click="Buscar"><i class="fas fa-search"></i> &nbsp; Buscar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="cell-1 text-center">#</th>
                                    <th class="cell-2 text-center">CÓDIGO</th>
                                    <th class="cell-3">CLIENTE</th>
                                    <th class="cell-4 text-center">Fecha Registro</th>
                                    <th class="cell-4 text-center">Fecha Entrega</th>
                                    <th class="cell-5 text-center">Estado</th>
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
                                        <tr v-for="(pedido, index) in listaRequest" :class="{ activado : active == pedido.id }" v-on:dblclick="formEditar(pedido)" v-on:click="Fila(pedido.id, pedido)" style="cursor: pointer;">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td class="text-center">@{{pedido.codigo_barras}}</td>
                                            <td>@{{pedido.get_cliente.nombres}}</td>
                                            <td class="text-center">@{{FechaHora(pedido.fecha_registro)}}</td>
                                            <td class="text-center">@{{Fecha(pedido.fecha_entrega)}}</td>
                                            <td class="text-center">@{{Estado(pedido.estado_entrega)}}</td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td colspan="7" class="text-center" style="font-size: 20px;">No existe ningun registro</td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div id="list-paginator" style="display: none;" class="row">
                        <div class="col-sm-4 text-left">
                            <div style="margin: 7px; font-size: 15px;">@{{pagination.current_page+' de '+pagination.to+' Páginas '}}</div>
                        </div>
                        <div class="col-sm-4">
                            <nav class="text-center" aria-label="...">
                                <ul class="pagination" style="justify-content: center;">
                                    <a href="#" v-if="pagination.current_page > 1" class="pag-inicio-fin" title="Página inicio" v-on:click.prevent="changePage(1)"><i class="fas fa-step-backward"></i></a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página inicio"><i class="fas fa-step-backward"></i></a>

                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;" title="Anterior" v-on:click.prevent="changePage(pagination.current_page - 1)">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                    </li>
                                    <li class="page-item disabled" title="Anterior" v-else style="cursor: no-drop;"><a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;"><i class="fas fa-angle-left"></i></a></li>
                                    <li class="page-item" v-for="page in pagesNumber" :class="[ page == isActive ? 'active' : '' ]"><a href="#" class="page-link" v-on:click.prevent="changePage(page)">@{{ page }}</a></li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;" title="Siguiente" v-on:click.prevent="changePage(pagination.current_page + 1)">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>
                                    <li class="page-item disabled" title="Siguiente" v-else style="cursor: no-drop;"><a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;"><i class="fas fa-angle-right"></i></a></li>

                                    <a href="#" v-if="pagination.current_page < pagination.last_page" class="pag-inicio-fin" title="Página final" v-on:click.prevent="changePage(pagination.last_page)"><i class="fas fa-step-forward"></i></a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página final"><i class="fas fa-step-forward"></i></a>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div style="margin: 7px; font-size: 15px;" v-if="to_pagination">@{{to_pagination+' de '+pagination.total+' Registros'}}</div>
                            <div style="margin: 7px; font-size: 15px;" v-else>0 de 0 Registros</div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $barras = App\Models\Configuracion::where('nombre', 'logo_codigo_barras')->first();
            @endphp
            <div style="display:none;">
                <div id="imprimir" class="mb-3" style="display: flex; width: 100px;">
                    <div style="padding-top: 20px;">
                        @if ($barras->archivo)
                            <img src="{{asset('storage/'.$barras->archivo_ruta.'/'.$barras->archivo)}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 9px; top: 4px;">
                        @else
                            <img src="{{asset('theme/images/kenya.png')}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 9px; top: 4px;">
                        @endif
                        <div id="barcode"></div>
                    </div>
                    <div style="padding-top: 20px;">
                        @if ($barras->archivo)
                            <img src="{{asset('storage/'.$barras->archivo_ruta.'/'.$barras->archivo)}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 164px; top: 4px;">
                        @else
                            <img src="{{asset('theme/images/kenya.png')}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 164px; top: 4px;">
                        @endif
                        <div id="barcode1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/barcode.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.printarea.js')}}"></script>
    <script src="{{asset('js/views/pedidos/pedidos.js')}}"></script>
@endsection
