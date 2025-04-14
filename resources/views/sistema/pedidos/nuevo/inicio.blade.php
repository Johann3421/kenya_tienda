@extends('layouts.template')

@section('app-name')
    <title>KENYA - Nuevo Pedido</title>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/busy-load@0.1.2/dist/app.min.css">
    <link rel="stylesheet" href="https://unpkg.com/vue-select@3.0.0/dist/vue-select.css">
    <link rel="stylesheet" href="{{asset('css/views/soporte.css')}}">
    <style>
        .page-header.breadcumb-sticky.dash-sale {
            position: fixed;
            right: 25px;
            width: 100%;
            z-index: 1001;
            border-radius: 0;
            background-color: #f6f6f6;
            border-bottom: 4px solid #CCC;
        }
    </style>
@endsection

@section('content')
    <div class="page-header breadcumb-sticky dash-sale">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-tools"></i> Nuevo Pedido</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="#"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Nuevo Pedido</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-pedido">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>NUEVO PEDIDO</h5>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <ul class="list-unstyled card-option" style="display: contents;">
                                <li class="full-card">
                                    <a href="#" class="windows-button">
                                        <span title="Maximizar">
                                            <i class="feather icon-maximize"></i>
                                        </span>
                                        <span style="display:none">
                                            <i class="feather icon-minimize"></i>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding-top: 20px;">

                    <div class="row m-b-20">
                        <div class="col-md-12">
                            <div class="contorno-check">
                                <span class="contorno-texto">Pedido</span>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-5">COMPROBANTE :</span>
                                            <div class="col-sm-7" style="padding-left: 0px;">
                                                <select id="input-comprobante" class="form-control form-control-sm" @change="buscarSeries"
                                                    v-model="form.tipo_comprobante"
                                                >
                                                    <option value="CI">Recibo</option>
                                                    <option value="B">Boleta</option>
                                                    <option value="F">Factura</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">SERIE :</span>
                                            <div class="col-sm-6" style="padding-left: 0px;">
                                                <select id="input-serie" class="form-control form-control-sm" @change="buscarNumeracion"
                                                    v-model="form.serie"
                                                >
                                                    <option v-for="serie in series" :value="serie.id">@{{ serie.serie }}</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3" style="padding-left: 0px;">
                                                <input type="text" class="form-control form-control-sm" readonly v-model="form.numeracion">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">FECHA :</span>
                                            <div class="col-sm-8" style="padding-left: 0px;">
                                                <input type="date" class="form-control form-control-sm" v-model="form.fecha">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">VENDEDOR :</span>
                                            <div class="col-sm-8" style="padding-left: 0px;">
                                                <select id="input-vendedor" class="form-control form-control-sm" v-model="form.vendedor">
                                                    <option v-for="vendedor in vendedores" :value="vendedor.id">
                                                        @{{ vendedor.nombres_apellidos }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-20" v-if="form.cliente">
                        <div class="col-md-12">
                            <div class="contorno-check">
                                <span class="contorno-texto">Datos del Cliente</span>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">DNI / RUC :</span>
                                            <div class="col-sm-8 m-b-5">
                                                <input type="text" v-model="form.cliente.documento" maxlength="11"
                                                class="form-control form-control-sm"
                                                onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                            </div>
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-sm btn-info"
                                                    style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;" @click="buscarReniec">
                                                    <i class="fas fa-search"></i> RENIEC
                                                </button>
                                                <button type="button" class="btn btn-sm btn-dark float-right"
                                                    style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;" @click="buscarSunat">
                                                    <i class="fas fa-search"></i> SUNAT
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group row m-b-10">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">NOMBRES :</span>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="form.cliente.nombres_apellidos"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">DIRECCIÓN :</span>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="form.cliente.direccion" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row m-b-10">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">EMAIL :</span>
                                            <div class="col-sm-9">
                                                <input type="email" v-model="form.cliente.email" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">CELULAR :</span>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="form.cliente.celular" maxlength="9"
                                                onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}"
                                                class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-20" v-if="form.proveedor">
                        <div class="col-md-12">
                            <div class="contorno-check">
                                <span class="contorno-texto">Datos del Proveedor</span>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-4">RUC: </span>
                                            <div class="col-sm-8 m-b-5">
                                                <input type="text" v-model="form.proveedor.documento" maxlength="11"
                                                class="form-control form-control-sm" @change="buscarProveedor" @keyup.enter="buscarProveedor"
                                                onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                            </div>
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-sm btn-info"
                                                    style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;" @click="mdlNuevoProveedor">
                                                    <i class="fas fa-plus"></i> NUEVO PROVEEDOR
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group row m-b-10">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">NOMBRES :</span>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="form.proveedor.nombre" readonly
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">DIRECCIÓN :</span>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="form.proveedor.direccion" readonly
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row m-b-10">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">EMAIL :</span>
                                            <div class="col-sm-9">
                                                <input type="email" v-model="form.proveedor.email" readonly
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-0">
                                            <span style="font-size: 11px; padding-right: 0;" class="col-sm-3">CELULAR :</span>
                                            <div class="col-sm-9">
                                                <input type="text" v-model="form.proveedor.telefono" maxlength="9" readonly
                                                onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}"
                                                class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row m-b-10">
                        <div class="col-md-10">
                            <div class="contorno-check">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group m-b-0">
                                            <label class="m-b-0">Descripción</label>
                                            <v-select :options="productos" @search="buscarProductos" v-model="detalle.descripcion"
                                                @input="escogerProducto">
                                                <template slot="no-options">Buscar Producto</template>
                                            </v-select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-b-0">
                                            <label class="m-b-0">Precio Unit.</label>
                                            <input type="number" v-model="detalle.precio_unitario" class="form-control form-control-sm"
                                                style="height: 30px !important; border-radius: 4px !important;">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-b-0">
                                            <label class="m-b-0">Cant. Cli.</label>
                                            <input type="number" v-model="detalle.cantidad_cliente" class="form-control form-control-sm"
                                                style="height: 30px !important; border-radius: 4px !important;">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-b-0">
                                            <label class="m-b-0">Cant. Prov.</label>
                                            <input type="number" v-model="detalle.cantidad_proveedor" class="form-control form-control-sm"
                                                style="height: 30px !important; border-radius: 4px !important;">
                                        </div>
                                    </div>
                                    <div class="col-md-1 p-t-10">
                                        <button class="btn btn-success btn-sm" style="padding: 0.14rem 0.5rem;"
                                            @click="agregarDetalle">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-5 p-t-10">
                                        <button class="btn btn-primary btn-sm" style="padding: 0.14rem 0.5rem;"
                                            @click="mdlNuevoProducto">
                                            <i class="fas fa-plus"> Nuevo producto</i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 p-t-10">
                                        <table class="table table-bordered table-sm" style="width: 100%; margin-bottom: 7px;">
                                            <thead>
                                                <tr>
                                                    <th width="30%" style="padding: 0 5px !important;">Descripción</th>
                                                    <th width="13%" style="padding: 0 5px !important;" class="text-center">Precio Unitario</th>
                                                    <th width="13%" style="padding: 0 5px !important;" class="text-center">Cant. Cli.</th>
                                                    <th width="13%" style="padding: 0 5px !important;" class="text-center">Importe Total</th>
                                                    <th width="13%" style="padding: 0 5px !important;" class="text-center">Cant. Prov.</th>
                                                    <th width="5%" style="padding: 0 5px !important;" class="text-center">
                                                        <i style="color: red;" class="fas fa-trash"></i>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(detalle, index) in form.detalles">
                                                    <td style="padding: 0px 6px !important;">@{{detalle.descripcion.toUpperCase()}}</td>
                                                    <td class="text-center" style="padding: 0px 6px !important;">@{{detalle.precio_unitario}}</td>
                                                    <td class="text-center" style="padding: 0px 6px !important;">@{{detalle.cantidad_cliente}}</td>
                                                    <td class="text-center" style="padding: 0px 6px !important;">@{{detalle.total_detalle}}</td>
                                                    <td class="text-center" style="padding: 0px 6px !important;">@{{detalle.cantidad_proveedor}}</td>
                                                    <td class="text-center" style="padding: 0px 6px !important;">
                                                        <a href="#" title="Eliminar Fila" style="color: red; padding: 0 !important; font-size: 13px;"
                                                            @click="quitarDetalle(index)">
                                                            <i class="fas fa-times-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr v-if="form.detalles && form.detalles.length == 0">
                                                    <td colspan="6" class="text-center" style="font-size: 12px;">NO HAY REGISTROS</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group m-b-0">
                                            <label class="m-b-0">Observacion</label>
                                            <input type="text" v-model="form.observacion" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-b-0">
                                            <label class="m-b-0">A cuenta</label>
                                            <input type="number" v-model="form.acuenta" class="form-control form-control-sm" style="background-color: #fdff83;">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-b-0">
                                            <label class="m-b-0">Total</label>
                                            <input type="text" :value="calcularTotal" class="form-control form-control-sm"
                                                style="background-color: #ffc883;" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group m-b-0">
                                            <label class="m-b-0">Saldo Total</label>
                                            <input type="text" v-model="parseFloat(form.total - form.acuenta).toFixed(2)"
                                                class="form-control form-control-sm" style="background-color: #cccccc;" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <template>
                                <button type="button" class="btn btn-icon btn-primary mr-2 btn-block" @click="guardarPedido">
                                    <template>
                                        <div style="font-size: 30px;"><i class="feather icon-save"></i></div>
                                        <div>Guardar</div>
                                    </template>
                                </button>
                                <button class="btn btn-danger btn-block" data-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
            @include('sistema.pedidos.nuevo.modals.mdlNuevoProducto')
            @include('sistema.pedidos.nuevo.modals.mdlNuevoProveedor')
            @include('sistema.pedidos.modals.mdlMostrarRecibo')
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/busy-load@0.1.2/dist/app.min.js"></script>
    <script src="https://unpkg.com/vue-select@3.0.0"></script>
    <script src="{{ asset('/js/views/pedidos/nuevo.js') }}"></script>
@endsection
