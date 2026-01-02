@extends('layouts.template')
@section('app-name')
    <title>Grupo kenya - Servicio Técnico</title>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/views/soporte.css') }}">
@endsection
@section('content')
    <div class="page-header breadcumb-sticky dash-sale"
        style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-tools"></i> Servicio Técnico</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="index.html"><i class="fas fa-home"
                                    style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Servicio Técnico</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-servicio">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>REPORTE DE SERVICIO TÉCNICO</h5>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <ul class="list-unstyled card-option" style="display: contents;">
                                <li class="full-card"><a href="#!" class="windows-button"><span title="Maximizar"><i
                                                class="feather icon-maximize"></i> </span><span style="display:none"><i
                                                class="feather icon-minimize"></i> </span></a></li>
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
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(SOPORTE
                                            TÉCNICO)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
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
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-4">FECHA REGISTRO :</span>
                                                                <div class="col-sm-8">
                                                                    <input type="text" id="fecha_registro"
                                                                        value="{{ date('d/m/Y H:i') }}"
                                                                        class="form-control form-control-sm" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row m-b-0">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-4">FECHA ENTREGA :</span>
                                                                <div class="col-sm-8">
                                                                    <input type="datetime-local" id="fecha_entrega"
                                                                        v-model="fecha_entrega"
                                                                        min="{{ date('Y-m-d') . 'T00:00' }}"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.fecha_entrega ? 'is-invalid' : '']"
                                                                        :readonly="loading">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.fecha_entrega">@{{ errors.fecha_entrega[0] }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row m-b-0">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-3">TÉCNICO :</span>
                                                                <div class="col-sm-9">
                                                                    <select class="form-control form-control-sm" readonly>
                                                                        <option value="user">
                                                                            {{ Auth::user()->nombres . ' ' . Auth::user()->ape_paterno . ' ' . Auth::user()->ape_materno }}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- NUEVO CAMPO: Número de Caso -->
                                                        <div class="col-md-4">
                                                            <div class="form-group row m-b-0">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0; padding-top: 3px;"
                                                                    class="col-sm-4">N° CASO :</span>
                                                                <div class="col-sm-8">
                                                                    <input type="text" id="numero_caso"
                                                                        v-model="numero_caso" :readonly="loading"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.numero_caso ? 'is-invalid' : '']"
                                                                        placeholder="Ingrese N° Caso">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.numero_caso">@{{ errors.numero_caso[0] }}</small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-20">
                                            <div class="col-md-3">

                                                <div class="contorno-check" style="padding: 5px 0px 3px 8px;">
                                                    <span class="contorno-texto">Tipo de Servicio</span>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="tipo_servicio" id="soporte" value="SOPORTE">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="soporte">SOPORTE</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="tipo_servicio" id="garantia" value="GARANTIA">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="garantia">GARANTIA</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="tipo_servicio" id="ingreso" value="INGRESO">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="ingreso">INGRESO</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="contorno-check" style="padding: 5px 0px 3px 8px;">
                                                    <span class="contorno-texto">Estado del Servicio</span>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="estado_servicio" id="pendiente" value="E1">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="pendiente">PENDIENTE</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="estado_servicio" id="diagnostico" value="E2">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="diagnostico">DIAGNOSTICO</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="estado_servicio" id="devolver" value="E3">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;" for="devolver">SIN
                                                            SOLUCIÓN - DEVOLVER</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="estado_servicio" id="reparando" value="E4">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="reparando">REPARANDO</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="estado_servicio" id="listo" value="E5">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="listo">LISTO</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="estado_servicio" id="entregado" value="E6">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="entregado">ENTREGADO</label>
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
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-4">DNI / RUC :</span>
                                                                <div class="col-sm-8 m-b-5">
                                                                    <input type="text" id="numero_documento"
                                                                        v-model="numero_documento" :readonly="loading"
                                                                        maxlength="11"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.numero_documento ? 'is-invalid' : '']"
                                                                        v-on:keyup.enter="Documento"
                                                                        onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.numero_documento">@{{ errors.numero_documento[0] }}
                                                                    </small>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <button type="button" class="btn btn-sm btn-info"
                                                                        style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;"
                                                                        v-on:click="Reniec"><i class="fas fa-search"></i>
                                                                        RENIEC</button>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-dark float-right"
                                                                        style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;"
                                                                        v-on:click="Sunat"><i class="fas fa-search"></i>
                                                                        SUNAT</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="form-group row m-b-5">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-3">NOMBRES :</span>
                                                                <div class="col-sm-9">
                                                                    <input type="text" id="nombres"
                                                                        v-model="nombres" :readonly="loading"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.nombres ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.nombres">@{{ errors.nombres[0] }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row m-b-0">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-3">DIRECCIÓN :</span>
                                                                <div class="col-sm-9">
                                                                    <input type="text" id="direccion"
                                                                        v-model="direccion" :readonly="loading"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.direccion ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.direccion">@{{ errors.direccion[0] }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group row m-b-5">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-3">EMAIL :</span>
                                                                <div class="col-sm-9">
                                                                    <input type="email" id="email" v-model="email"
                                                                        :readonly="loading"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.email ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.email">@{{ errors.email[0] }} </small>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row m-b-0">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-3">CELULAR :</span>
                                                                <div class="col-sm-9">
                                                                    <input type="text" id="celular"
                                                                        v-model="celular" :readonly="loading"
                                                                        maxlength="9"
                                                                        onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.celular ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.celular">@{{ errors.celular[0] }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="col-md-12">
                                                <div class="contorno-check">
                                                    <span class="contorno-texto">Detalles del Equipo</span>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group row m-b-0">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-4">EQUIPO :</span>
                                                                <div class="col-sm-8 m-b-5">
                                                                    <input type="text" id="equipo" v-model="equipo"
                                                                        :readonly="loading" autocomplete="on"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.equipo ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.equipo">@{{ errors.equipo[0] }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group row m-b-0">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-4">MARCA :</span>
                                                                <div class="col-sm-8 m-b-5">
                                                                    <input type="text" id="marca" v-model="marca"
                                                                        :readonly="loading" autocomplete="on"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.marca ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.marca">@{{ errors.marca[0] }} </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group row m-b-0">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-4">MODELO :</span>
                                                                <div class="col-sm-8 m-b-5">
                                                                    <input type="text" id="modelo" v-model="modelo"
                                                                        :readonly="loading"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.modelo ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.modelo">@{{ errors.modelo[0] }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group row m-b-0">
                                                                <span
                                                                    style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                    class="col-sm-4">SERIE :</span>
                                                                <div class="col-sm-8 m-b-5">
                                                                    <input type="text" id="serie" v-model="serie"
                                                                        :readonly="loading"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.serie ? 'is-invalid' : '']">
                                                                    <small class="form-text error-color"
                                                                        v-if="errors.serie">@{{ errors.serie[0] }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
    <div class="form-group row m-b-0">
        <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-2">Opciones :</span>
        <div class="col-sm-10 m-b-5">
            <div v-for="(item, idx) in descripcion_falla" :key="idx" class="input-group mb-2">
                <input type="text" v-model="item.titulo" class="form-control form-control-sm" placeholder="Nombre de la opción (ej: Falla principal)">
                <textarea v-model="item.texto" class="form-control form-control-sm ml-2" placeholder="Descripción"></textarea>
                <div class="input-group-append">
                    <button class="btn btn-danger btn-sm" v-if="descripcion_falla.length > 1" @click="removeDescripcion(idx)">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <button class="btn btn-success btn-sm mt-2" @click="addDescripcion"><i class="fas fa-plus"></i> Opción</button>
            <small class="form-text error-color" v-if="errors.descripcion">@{{ errors.descripcion[0] }}</small>
        </div>
    </div>
</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="col-md-12">
                                                <div class="contorno-check" style="padding: 5px 0px 3px 8px;">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;">ACCESORIOS
                                                                :</span>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input class="form-check-input check-xl" type="checkbox"
                                                                    id="sin_accesorios" v-model="sin_accesorios"
                                                                    true-value="SI" false-value="NO"
                                                                    :readonly="loading">
                                                                <label class="form-check-label" for="sin_accesorios"
                                                                    style="font-size: 11px; margin: 5px;">SIN
                                                                    ACCESORIOS</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input class="form-check-input check-xl" type="checkbox"
                                                                    id="cargador" v-model="cargador" true-value="SI"
                                                                    false-value="NO" :readonly="loading">
                                                                <label class="form-check-label" for="cargador"
                                                                    style="font-size: 11px; margin: 5px;">CARGADOR</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input class="form-check-input check-xl" type="checkbox"
                                                                    id="cable_usb" v-model="cable_usb" true-value="SI"
                                                                    false-value="NO" :readonly="loading">
                                                                <label class="form-check-label" for="cable_usb"
                                                                    style="font-size: 11px; margin: 5px;">CABLE USB</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                <input class="form-check-input check-xl" type="checkbox"
                                                                    id="cable_poder" v-model="cable_poder"
                                                                    true-value="SI" false-value="NO"
                                                                    :readonly="loading">
                                                                <label class="form-check-label" for="cable_poder"
                                                                    style="font-size: 11px; margin: 5px;">CABLE
                                                                    PODER</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline"
                                                                style="margin-right: 0;">
                                                                <input type="text" id="otros" v-model="otros"
                                                                    class="form-control form-control-sm" autocomplete="on"
                                                                    placeholder="Otros" style="min-width: 280px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-b-10">
                                            <div class="col-md-10">
                                                <h5>PROFORMA DE REPARACIÓN</h5>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="contorno-check">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0"
                                                                    for="detalle_descripcion">Descripción</label>
                                                                <input type="text" id="detalle_descripcion"
                                                                    v-model="detalle_descripcion"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.detalle_descripcion ? 'is-invalid' : '']"
                                                                    autocomplete="on" :readonly="loading"
                                                                    v-on:keyup.enter="Next('detalle_precio')">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.detalle_descripcion">@{{ errors.detalle_descripcion[0] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="detalle_precio">Precio</label>
                                                                <input type="number" id="detalle_precio"
                                                                    v-model="detalle_precio"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.detalle_precio ? 'is-invalid' : '']"
                                                                    :readonly="loading"
                                                                    v-on:keyup.enter="Next('detalle_descuento')">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.detalle_precio">@{{ errors.detalle_precio[0] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="detalle_descuento">N°de
                                                                    Serie</label>
                                                                <input type="text" id="detalle_descuento"
                                                                    v-model="detalle_descuento"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.detalle_descuento ? 'is-invalid' : '']"
                                                                    :readonly="loading"
                                                                    v-on:keyup.enter="Next('detalle_cantidad')">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.detalle_descuento">@{{ errors.detalle_descuento[0] }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0"
                                                                    for="detalle_cantidad">Cantidad</label>
                                                                <input type="number" id="detalle_cantidad"
                                                                    v-model="detalle_cantidad"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.detalle_cantidad ? 'is-invalid' : '']"
                                                                    :readonly="loading" v-on:keyup.enter="addDetalles">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.detalle_cantidad">@{{ errors.detalle_cantidad[0] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button class="btn btn-success btn-block"
                                                                style="padding: 8px 0; margin: 1px 0 4px 0;"
                                                                v-on:click="addDetalles" title="Agregar">
                                                                <i class="fas fa-plus" style="font-size: 20px;"></i>
                                                            </button>
                                                            {{-- <button class="btn btn-success btn-sm" style="padding: 0.14rem 0.5rem;" v-on:click="addDetalles"><i class="fas fa-plus"></i></button> --}}
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <table class="table table-bordered table-sm"
                                                                style="width: 100%; margin-bottom: 7px;">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="55%">Descripción</th>
                                                                        <th width="10%" class="text-center">Precio</th>
                                                                        <th width="10%" class="text-center">N° de Serie
                                                                        </th>
                                                                        <th width="10%" class="text-center">Cantidad
                                                                        </th>
                                                                        <th width="10%" class="text-center">Importe
                                                                        </th>
                                                                        <th width="5%" class="text-center"><i
                                                                                style="color: #505050; font-size: 13px;"
                                                                                class="fas fa-trash"></i></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(detalle, index) in listDetalles">
                                                                        <td style="padding: 0px 6px !important;">
                                                                            @{{ detalle.descripcion }}</td>
                                                                        <td class="text-center"
                                                                            style="padding: 0px 6px !important;">
                                                                            @{{ detalle.precio }}</td>
                                                                        <td class="text-center"
                                                                            style="padding: 0px 6px !important;">
                                                                            @{{ detalle.descuento }}</td>
                                                                        <td class="text-center"
                                                                            style="padding: 0px 6px !important;">
                                                                            @{{ detalle.cantidad }}</td>
                                                                        <td class="text-center"
                                                                            style="padding: 0px 6px !important;">
                                                                            @{{ detalle.importe }}</td>
                                                                        <td class="text-center"
                                                                            style="padding: 0px 6px !important;">
                                                                            <a href="#" title="Eliminar Fila"
                                                                                style="color: red; padding: 0 !important; font-size: 13px;"
                                                                                v-on:click="deleteDetalles(index)">
                                                                                <i class="fas fa-times-circle"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr v-if="listDetalles.length == 0">
                                                                        <td colspan="6" class="text-center"
                                                                            style="font-size: 12px;">NO HAY REGISTROS</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="row m-t-10">
                                                        <div class="col-md-6">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0"
                                                                    for="observacion">Observacion</label>
                                                                <textarea id="observacion" v-model="observacion" class="form-control" :readonly="loading"
                                                                    style="max-width: 100%; padding: 0px 6px !important; font-size: 11px !important;"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="reporte_tecnico"><i
                                                                        class="fas fa-chalkboard-teacher"></i> Reporte
                                                                    Técnico</label>
                                                                <textarea id="reporte_tecnico" v-model="reporte_tecnico" class="form-control" :readonly="loading"
                                                                    style="max-width: 100%; padding: 0px 6px !important; font-size: 11px !important;"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row m-t-10">
                                                        <div class="col-md-6">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="pdf_file"><i
                                                                        class="fas fa-file-pdf"></i> Documento PDF</label>
                                                                <input type="file" id="pdf_file"
                                                                    @change="handlePdfUpload" class="form-control-file"
                                                                    accept=".pdf" :disabled="loading">
                                                                <small class="text-muted">Tamaño máximo: 5MB</small>
                                                                <small class="form-text error-color"
                                                                    v-if="errors.pdf_link">@{{ errors.pdf_link[0] }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row m-t-10">
                                                        <div class="col-md-6"></div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="acuenta">A cuenta</label>
                                                                <input type="number" id="acuenta" v-model="acuenta"
                                                                    class="form-control form-control-sm"
                                                                    style="background-color: #fdff83; font-size: 17px !important; text-align: right;"
                                                                    :class="[errors.acuenta ? 'is-invalid' : '']"
                                                                    :readonly="loading">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.acuenta">@{{ errors.acuenta[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="costo_servicio">Total</label>
                                                                <input type="number" id="costo_servicio"
                                                                    v-model="costo_servicio"
                                                                    class="form-control form-control-sm"
                                                                    style="background-color: #ffc883; font-size: 17px !important; text-align: right;"
                                                                    :class="[errors.costo_servicio ? 'is-invalid' : '']"
                                                                    :readonly="loading">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.costo_servicio">@{{ errors.costo_servicio[0] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group m-b-0">
                                                                <label class="m-b-0" for="saldo_total">Resta</label>
                                                                <input type="number" id="saldo_total"
                                                                    v-model="saldo_total"
                                                                    class="form-control form-control-sm"
                                                                    style="background-color: #cccccc; font-size: 17px !important; text-align: right;"
                                                                    :class="[errors.saldo_total ? 'is-invalid' : '']"
                                                                    :readonly="loading" readonly="true">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.saldo_total">@{{ errors.saldo_total[0] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2" style="padding-left: 0;">
                                                <div class="mb-2">
                                                    <div class="contorno-check" style="padding: 5px 0px 3px 8px;">
                                                        <span style="color: #0075ff;">Confirmar Reparación</span>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input class="form-check-input check-xl" type="radio"
                                                                v-model="confirmar_reparacion" id="si_confirmar"
                                                                value="SI">
                                                            <label class="form-check-label"
                                                                style="font-size: 11px; margin: 4px;"
                                                                for="si_confirmar">SI</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input class="form-check-input check-xl" type="radio"
                                                                v-model="confirmar_reparacion" id="no_confirmar"
                                                                value="NO">
                                                            <label class="form-check-label"
                                                                style="font-size: 11px; margin: 4px;"
                                                                for="no_confirmar">NO</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-2" v-if="confirmar_reparacion == 'SI'">
                                                    <div class="reparar">REPARAR</div>
                                                </div>
                                                <div class="mb-2" v-else-if="confirmar_reparacion == 'NO'">
                                                    <div class="proforma">PROFORMA</div>
                                                </div>
                                                <div class="mb-2">
                                                    <div class="contorno-check" style="padding: 5px 0px 3px 8px;">
                                                        <span style="color: #0075ff;">Equipo no se reparo solo se
                                                            diagnostico</span>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input class="form-check-input check-xl" type="checkbox"
                                                                v-model="solo_diagnostico" id="si_confirmar"
                                                                true-value="SI" false-value="NO">
                                                            <label class="form-check-label"
                                                                style="font-size: 11px; margin: 4px;"
                                                                for="si_confirmar">SI</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <div :class="estado_servicio" style="border-radius: 20px;">
                                                        @{{ Estado(estado_servicio) }}</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 mt-2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <button class="btn btn-danger btn-block" data-dismiss="modal"
                                                            aria-label="Close" v-on:click="closeModal(methods)"><i
                                                                class="fas fa-times"></i> Cancelar</button>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <button class="btn btn-primary btn-block event-btn"
                                                            v-on:click="Store" :disabled="loading">
                                                            <span class="spinner-grow spinner-grow-sm" role="status"
                                                                v-if="loading"></span>
                                                            <span class="load-text" v-if="loading">Guardando...</span>
                                                            <span class="btn-text" v-if="!loading" style=""><i
                                                                    class="fas fa-save"></i> Guardar</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="col-sm-12 mt-2" v-else>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-icon btn-warning mr-2 btn-block"
                                                    style="min-width: 88px;" v-on:click="Recibo(result_id)">
                                                    <div style="font-size: 30px;"><i class="fas fa-print"></i></div>
                                                    <div>Imprimir Recibo</div>
                                                </button>
                                                {{-- <button class="btn btn-warning btn-block"  v-on:click="Recibo(result_id)"><i class="fas fa-print"></i> Imprimir Recibo</button> --}}
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-icon btn-dark mr-2 btn-block"
                                                    style="min-width: 88px;"
                                                    v-on:click="codigoBarra(result_id, result_barra)">
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
                                        EDITAR <span style="color: #929292; font-size: 17px; font-weight: 400;">(SOPORTE
                                            TÉCNICO) </span> : @{{ zeroFill(id, 4) }}
                                        <button class="btn btn-warning" style="margin-left: 50px;"
                                            v-on:click="Recibo(result_id)"><i class="fas fa-print"></i> Imprimir
                                            Recibo</button>
                                        <button class="btn btn-dark" v-on:click="codigoBarra(result_id, result_barra)"><i
                                                class="fas fa-barcode"></i> Imprimir Codigo Barras</button>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <div class="contorno-check">
                                                <span class="contorno-texto">Registro</span>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-4">FECHA REGISTRO :</span>
                                                            <div class="col-sm-8">
                                                                <input type="text" id="fecha_registro"
                                                                    :value="fecha_registro"
                                                                    class="form-control form-control-sm" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-4">FECHA ENTREGA :</span>
                                                            <div class="col-sm-8">
                                                                <input type="datetime-local" id="fecha_entrega"
                                                                    v-model="fecha_entrega"
                                                                    min="{{ date('Y-m-d') . 'T00:00' }}"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.fecha_entrega ? 'is-invalid' : '']"
                                                                    :readonly="loading">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.fecha_entrega">@{{ errors.fecha_entrega[0] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-3">TÉCNICO :</span>
                                                            <div class="col-sm-9">
                                                                <select class="form-control form-control-sm" readonly>
                                                                    <option value="user">
                                                                        {{ Auth::user()->nombres . ' ' . Auth::user()->ape_paterno . ' ' . Auth::user()->ape_materno }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- NUEVO CAMPO: Número de Caso -->
                                                    <div class="col-md-4">
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0; padding-top: 3px;"
                                                                class="col-sm-4">N° CASO :</span>
                                                            <div class="col-sm-8">
                                                                <input type="text" id="numero_caso"
                                                                    v-model="numero_caso" :readonly="loading"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.numero_caso ? 'is-invalid' : '']"
                                                                    placeholder="Ingrese N° Caso">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.numero_caso">@{{ errors.numero_caso[0] }}</small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-b-20">
                                        <div class="col-md-3">
                                            {{-- ...CREAR Y EDITAR... --}}

<div class="contorno-check" style="padding: 5px 0px 3px 8px;">
    <span class="contorno-texto">Tipo de Servicio</span>
    <div class="custom-control custom-radio custom-control-inline">
        <input class="form-check-input check-xl" type="radio"
            v-model="tipo_servicio" id="soporte" value="SOPORTE">
        <label class="form-check-label"
            style="font-size: 11px; margin: 4px;"
            for="soporte">SOPORTE</label>
    </div>
    <div class="custom-control custom-radio custom-control-inline">
        <input class="form-check-input check-xl" type="radio"
            v-model="tipo_servicio" id="garantia" value="GARANTIA">
        <label class="form-check-label"
            style="font-size: 11px; margin: 4px;"
            for="garantia">GARANTIA</label>
    </div>
    <div class="custom-control custom-radio custom-control-inline">
        <input class="form-check-input check-xl" type="radio"
            v-model="tipo_servicio" id="ingreso" value="INGRESO">
        <label class="form-check-label"
            style="font-size: 11px; margin: 4px;"
            for="ingreso">INGRESO</label>
    </div>
</div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="contorno-check" style="padding: 5px 0px 3px 8px;">
                                                <span class="contorno-texto">Estado del Servicio</span>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio"
                                                        v-model="estado_servicio" id="pendiente" value="E1">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 4px;"
                                                        for="pendiente">PENDIENTE</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio"
                                                        v-model="estado_servicio" id="diagnostico" value="E2">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 4px;"
                                                        for="diagnostico">DIAGNOSTICO</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio"
                                                        v-model="estado_servicio" id="devolver" value="E3">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 4px;"
                                                        for="devolver">SIN SOLUCIÓN - DEVOLVER</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio"
                                                        v-model="estado_servicio" id="reparando" value="E4">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 4px;"
                                                        for="reparando">REPARANDO</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio"
                                                        v-model="estado_servicio" id="listo" value="E5">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 4px;"
                                                        for="listo">LISTO</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input class="form-check-input check-xl" type="radio"
                                                        v-model="estado_servicio" id="entregado" value="E6">
                                                    <label class="form-check-label" style="font-size: 11px; margin: 4px;"
                                                        for="entregado">ENTREGADO</label>
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
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-4">DNI / RUC :</span>
                                                            <div class="col-sm-8 m-b-5">
                                                                <input type="text" id="numero_documento"
                                                                    v-model="numero_documento" :readonly="loading"
                                                                    maxlength="11" class="form-control form-control-sm"
                                                                    :class="[errors.numero_documento ? 'is-invalid' : '']"
                                                                    v-on:keyup.enter="Documento"
                                                                    onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.numero_documento">@{{ errors.numero_documento[0] }}
                                                                </small>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <button type="button" class="btn btn-sm btn-info"
                                                                    style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;"
                                                                    v-on:click="Reniec"><i class="fas fa-search"></i>
                                                                    RENIEC</button>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-dark float-right"
                                                                    style="min-width: 80px; padding: .14rem .5rem; font-size: 11px;"
                                                                    v-on:click="Sunat"><i class="fas fa-search"></i>
                                                                    SUNAT</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group row m-b-5">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-3">NOMBRES :</span>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="nombres" v-model="nombres"
                                                                    :readonly="loading"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.nombres ? 'is-invalid' : '']">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.nombres">@{{ errors.nombres[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-3">DIRECCIÓN :</span>
                                                            <div class="col-sm-9">
                                                                <input type="text" id="direccion" v-model="direccion"
                                                                    :readonly="loading"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.direccion ? 'is-invalid' : '']">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.direccion">@{{ errors.direccion[0] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row m-b-5">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-3">EMAIL :</span>
                                                            <div class="col-sm-9">
                                                                <input type="email" id="email" v-model="email"
                                                                    :readonly="loading"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.email ? 'is-invalid' : '']">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.email">@{{ errors.email[0] }} </small>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-3">CELULAR :</span>
                                                            <div class="col-sm-9">
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" id="celular"
                                                                        v-model="celular" :readonly="loading"
                                                                        maxlength="9"
                                                                        onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}"
                                                                        class="form-control form-control-sm"
                                                                        :class="[errors.celular ? 'is-invalid' : '']">
                                                                    <div class="input-group-append">
                                                                        <a :href="'https://wa.me/' + celular + '?text=Hola%20' +
                                                                            Espacios(nombres) + ',%20' +
                                                                            whatsapp_soporte"
                                                                            target="_blank" class="btn btn-whatsapp"
                                                                            style="padding: 0px 5px;"
                                                                            title="Enviar Whatsapp al Cliente"><i
                                                                                class="fab fa-whatsapp"></i> </a>
                                                                    </div>
                                                                </div>

                                                                <small class="form-text error-color"
                                                                    v-if="errors.celular">@{{ errors.celular[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-b-10">
                                        <div class="col-md-12">
                                            <div class="contorno-check">
                                                <span class="contorno-texto">Detalles del Equipo</span>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-4">EQUIPO :</span>
                                                            <div class="col-sm-8 m-b-5">
                                                                <input type="text" id="equipo" v-model="equipo"
                                                                    :readonly="loading" autocomplete="on"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.equipo ? 'is-invalid' : '']">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.equipo">@{{ errors.equipo[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-4">MARCA :</span>
                                                            <div class="col-sm-8 m-b-5">
                                                                <input type="text" id="marca" v-model="marca"
                                                                    :readonly="loading" autocomplete="on"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.marca ? 'is-invalid' : '']">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.marca">@{{ errors.marca[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-4">MODELO :</span>
                                                            <div class="col-sm-8 m-b-5">
                                                                <input type="text" id="modelo" v-model="modelo"
                                                                    :readonly="loading"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.modelo ? 'is-invalid' : '']">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.modelo">@{{ errors.modelo[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group row m-b-0">
                                                            <span
                                                                style="font-size: 11px; padding-right: 0;padding-top: 3px;"
                                                                class="col-sm-4">SERIE :</span>
                                                            <div class="col-sm-8 m-b-5">
                                                                <input type="text" id="serie" v-model="serie"
                                                                    :readonly="loading"
                                                                    class="form-control form-control-sm"
                                                                    :class="[errors.serie ? 'is-invalid' : '']">
                                                                <small class="form-text error-color"
                                                                    v-if="errors.serie">@{{ errors.serie[0] }} </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
    <div class="form-group row m-b-0">
        <span style="font-size: 11px; padding-right: 0;padding-top: 3px;" class="col-sm-2">Opciones  :</span>
        <div class="col-sm-10 m-b-5">
            <div v-for="(item, idx) in descripcion_falla" :key="idx" class="input-group mb-2">
                <input type="text" v-model="item.titulo" class="form-control form-control-sm" placeholder="Nombre de la opción (ej: Falla principal)">
                <textarea v-model="item.texto" class="form-control form-control-sm ml-2" placeholder="Descripción"></textarea>
                <div class="input-group-append">
                    <button class="btn btn-danger btn-sm" v-if="descripcion_falla.length > 1" @click="removeDescripcion(idx)">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <button class="btn btn-success btn-sm mt-2" @click="addDescripcion"><i class="fas fa-plus"></i> Opción</button>
            <small class="form-text error-color" v-if="errors.descripcion">@{{ errors.descripcion[0] }}</small>
        </div>
    </div>
</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-b-10">
                                        <div class="col-md-12">
                                            <div class="contorno-check" style="padding: 5px 0px 3px 8px;">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <span
                                                            style="font-size: 11px; padding-right: 0;padding-top: 3px;">ACCESORIOS
                                                            :</span>
                                                    </div>
                                                    <div class="col-md-10">
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input class="form-check-input check-xl" type="checkbox"
                                                                id="sin_accesorios" v-model="sin_accesorios"
                                                                true-value="SI" false-value="NO" :readonly="loading">
                                                            <label class="form-check-label" for="sin_accesorios"
                                                                style="font-size: 11px; margin: 5px;">SIN
                                                                ACCESORIOS</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input class="form-check-input check-xl" type="checkbox"
                                                                id="cargador" v-model="cargador" true-value="SI"
                                                                false-value="NO" :readonly="loading">
                                                            <label class="form-check-label" for="cargador"
                                                                style="font-size: 11px; margin: 5px;">CARGADOR</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input class="form-check-input check-xl" type="checkbox"
                                                                id="cable_usb" v-model="cable_usb" true-value="SI"
                                                                false-value="NO" :readonly="loading">
                                                            <label class="form-check-label" for="cable_usb"
                                                                style="font-size: 11px; margin: 5px;">CABLE USB</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input class="form-check-input check-xl" type="checkbox"
                                                                id="cable_poder" v-model="cable_poder" true-value="SI"
                                                                false-value="NO" :readonly="loading">
                                                            <label class="form-check-label" for="cable_poder"
                                                                style="font-size: 11px; margin: 5px;">CABLE PODER</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline"
                                                            style="margin-right: 0;">
                                                            <input type="text" id="otros" v-model="otros"
                                                                class="form-control form-control-sm" autocomplete="on"
                                                                placeholder="Otros" style="min-width: 280px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-b-10">
                                        <div class="col-md-10">
                                            <h5>PROFORMA DE REPARACIÓN</h5>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="contorno-check">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0"
                                                                for="detalle_descripcion">Descripción</label>
                                                            <input type="text" id="detalle_descripcion"
                                                                v-model="detalle_descripcion"
                                                                class="form-control form-control-sm"
                                                                :class="[errors.detalle_descripcion ? 'is-invalid' : '']"
                                                                :readonly="loading"
                                                                v-on:keyup.enter="Next('detalle_precio')">
                                                            <small class="form-text error-color"
                                                                v-if="errors.detalle_descripcion">@{{ errors.detalle_descripcion[0] }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="detalle_precio">Precio</label>
                                                            <input type="number" id="detalle_precio"
                                                                v-model="detalle_precio"
                                                                class="form-control form-control-sm"
                                                                :class="[errors.detalle_precio ? 'is-invalid' : '']"
                                                                :readonly="loading"
                                                                v-on:keyup.enter="Next('detalle_descuento')">
                                                            <small class="form-text error-color"
                                                                v-if="errors.detalle_precio">@{{ errors.detalle_precio[0] }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="detalle_descuento">N°de
                                                                Serie</label>
                                                            <input type="text" id="detalle_descuento"
                                                                v-model="detalle_descuento"
                                                                class="form-control form-control-sm"
                                                                :class="[errors.detalle_descuento ? 'is-invalid' : '']"
                                                                :readonly="loading"
                                                                v-on:keyup.enter="Next('detalle_cantidad')">
                                                            <small class="form-text error-color"
                                                                v-if="errors.detalle_descuento">@{{ errors.detalle_descuento[0] }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0"
                                                                for="detalle_cantidad">Cantidad</label>
                                                            <input type="number" id="detalle_cantidad"
                                                                v-model="detalle_cantidad"
                                                                class="form-control form-control-sm"
                                                                :class="[errors.detalle_cantidad ? 'is-invalid' : '']"
                                                                :readonly="loading"
                                                                v-on:keyup.enter="addDetallesEdit">
                                                            <small class="form-text error-color"
                                                                v-if="errors.detalle_cantidad">@{{ errors.detalle_cantidad[0] }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button class="btn btn-outline-dark btn-block"
                                                            style="padding: 8px 0; margin: 1px 0 4px 0;"
                                                            v-on:click="addDetallesEdit" title="Agregar">
                                                            <i class="fas fa-plus" style="font-size: 20px;"></i>
                                                        </button>
                                                        {{-- <button class="btn btn-success btn-sm" style="padding: 0.14rem 0.5rem;" v-on:click="addDetallesEdit"><i class="fas fa-plus"></i></button> --}}
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <table class="table table-bordered table-sm"
                                                            style="width: 100%; margin-bottom: 7px;">
                                                            <thead>
                                                                <tr>
                                                                    <th width="55%">Descripción</th>
                                                                    <th width="10%" class="text-center">Precio</th>
                                                                    <th width="10%" class="text-center">N° de Serie
                                                                    </th>
                                                                    <th width="10%" class="text-center">Cantidad</th>
                                                                    <th width="10%" class="text-center">Importe</th>
                                                                    <th width="5%" class="text-center"><i
                                                                            style="color: #505050; font-size: 13px;"
                                                                            class="fas fa-trash"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="(detalle, index) in listDetalles">
                                                                    <td style="padding: 0px 6px !important;">
                                                                        @{{ detalle.descripcion }}</td>
                                                                    <td class="text-center"
                                                                        style="padding: 0px 6px !important;">
                                                                        @{{ detalle.precio }}</td>
                                                                    <td class="text-center"
                                                                        style="padding: 0px 6px !important;">
                                                                        @{{ detalle.descuento }}</td>
                                                                    <td class="text-center"
                                                                        style="padding: 0px 6px !important;">
                                                                        @{{ detalle.cantidad }}</td>
                                                                    <td class="text-center"
                                                                        style="padding: 0px 6px !important;">
                                                                        @{{ detalle.importe }}</td>
                                                                    <td class="text-center"
                                                                        style="padding: 0px 6px !important;">
                                                                        <a href="#" title="Eliminar Detalle"
                                                                            style="color: red; padding: 0 !important; font-size: 13px;"
                                                                            v-on:click="deleteDetallesEdit(detalle.id, index)">
                                                                            <i class="fas fa-trash"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <tr v-if="listDetalles.length == 0">
                                                                    <td colspan="6" class="text-center"
                                                                        style="font-size: 14px;">NO HAY RESGITROS</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="observacion">Observacion</label>
                                                            <textarea id="observacion" v-model="observacion" class="form-control" :readonly="loading"
                                                                style="max-width: 100%; padding: 0px 6px !important; font-size: 11px !important;"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="reporte_tecnico"><i
                                                                    class="fas fa-chalkboard-teacher"></i> Reporte
                                                                Técnico</label>
                                                            <textarea id="reporte_tecnico" v-model="reporte_tecnico" class="form-control" :readonly="loading"
                                                                style="max-width: 100%; padding: 0px 6px !important; font-size: 11px !important;"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row m-t-10">
                                                    <div class="col-md-6">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="pdf_file"><i
                                                                    class="fas fa-file-pdf"></i> Documento PDF</label>
                                                            <input type="file" id="pdf_file"
                                                                @change="pdf_file = $event.target.files[0]"
                                                                class="form-control-file" accept=".pdf"
                                                                :disabled="loading">
                                                            <small class="text-muted">Tamaño máximo: 5MB</small>
                                                            <div v-if="pdf_link" class="mt-2">
                                                                <a :href="pdf_link" target="_blank"
                                                                    class="btn btn-sm btn-info">
                                                                    <i class="fas fa-eye"></i> Ver PDF actual
                                                                </a>
                                                                <button @click="pdf_link = null; pdf_file = null"
                                                                    class="btn btn-sm btn-danger ml-2">
                                                                    <i class="fas fa-trash"></i> Eliminar
                                                                </button>
                                                            </div>
                                                            <small class="form-text error-color"
                                                                v-if="errors.pdf_link">@{{ errors.pdf_link[0] }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row m-t-10">
                                                    <div class="col-md-6"></div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="acuenta">A cuenta</label>
                                                            <input type="number" id="acuenta" v-model="acuenta"
                                                                class="form-control form-control-sm"
                                                                style="background-color: #fdff83; font-size: 17px !important; text-align: right;"
                                                                :class="[errors.acuenta ? 'is-invalid' : '']"
                                                                :readonly="loading">
                                                            <small class="form-text error-color"
                                                                v-if="errors.acuenta">@{{ errors.acuenta[0] }} </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="costo_servicio">Total</label>
                                                            <input type="number" id="costo_servicio"
                                                                v-model="costo_servicio"
                                                                class="form-control form-control-sm"
                                                                style="background-color: #ffc883; font-size: 17px !important; text-align: right;"
                                                                :class="[errors.costo_servicio ? 'is-invalid' : '']"
                                                                :readonly="loading">
                                                            <small class="form-text error-color"
                                                                v-if="errors.costo_servicio">@{{ errors.costo_servicio[0] }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group m-b-0">
                                                            <label class="m-b-0" for="saldo_total">Resta</label>
                                                            <input type="number" id="saldo_total"
                                                                v-model="saldo_total"
                                                                class="form-control form-control-sm"
                                                                style="background-color: #cccccc; font-size: 17px !important; text-align: right;"
                                                                :class="[errors.saldo_total ? 'is-invalid' : '']"
                                                                :readonly="loading" readonly="true">
                                                            <small class="form-text error-color"
                                                                v-if="errors.saldo_total">@{{ errors.saldo_total[0] }} </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="padding-left: 0;">
                                            <div class="mb-2">
                                                <div class="contorno-check" style="padding: 5px 0px 3px 8px;">
                                                    <span style="color: #0075ff;">Confirmar Reparación</span>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="confirmar_reparacion" id="si_confirmar"
                                                            value="SI">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="si_confirmar">SI</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="radio"
                                                            v-model="confirmar_reparacion" id="no_confirmar"
                                                            value="NO">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="no_confirmar">NO</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2" v-if="confirmar_reparacion == 'SI'">
                                                <div class="reparar">REPARAR</div>
                                            </div>
                                            <div class="mb-2" v-else-if="confirmar_reparacion == 'NO'">
                                                <div class="proforma">PROFORMA</div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="contorno-check" style="padding: 5px 0px 3px 8px;">
                                                    <span style="color: #0075ff;">Equipo no se reparo solo se
                                                        diagnostico</span>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input class="form-check-input check-xl" type="checkbox"
                                                            v-model="solo_diagnostico" id="si_confirmar"
                                                            true-value="SI" false-value="NO">
                                                        <label class="form-check-label"
                                                            style="font-size: 11px; margin: 4px;"
                                                            for="si_confirmar">SI</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <div :class="estado_servicio" style="border-radius: 20px;">
                                                    @{{ Estado(estado_servicio) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mt-2">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <button class="btn btn-danger btn-block" data-dismiss="modal"
                                                        aria-label="Close" v-on:click="closeModal(methods)"><i
                                                            class="fas fa-times"></i> Cancelar</button>
                                                </div>
                                                <div class="col-sm-6">
                                                    <button class="btn btn-primary btn-block event-btn"
                                                        v-on:click="Update" :disabled="loading">
                                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                                            v-if="loading"></span>
                                                        <span class="load-text" v-if="loading">Actualizando...</span>
                                                        <span class="btn-text" v-if="!loading" style=""><i
                                                                class="fas fa-save"></i> Actualizar</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- EDITAR --}}

                            {{-- ELIMINAR --}}
                            <div class="modal-content" v-if="methods == 'delete'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">ELIMINAR</h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <p class="text-center">
                                        Realmente desea eliminar el Soporte Técnico <strong>"N°
                                            @{{ zeroFill(id, 4) }}"</strong>
                                    </p>
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-danger btn-block event-btn" v-on:click="Delete"
                                        :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                            v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Eliminando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i
                                                class="feather icon-times"></i> Eliminar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- ELIMINAR --}}

                            {{-- FACTURAR --}}
                            <div class="modal-content" v-if="methods == 'facturar'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">FACTURAR <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(SOPORTE
                                            TÉCNICO)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-row" v-if="state != 'alert-success'">
                                        <div class="form-group col-12">
                                            <label for="codigo" style="font-size: 11px;" class="mb-0">Código de
                                                Soporte</label>
                                            <input type="text" id="codigo" v-model="factura.codigo"
                                                class="form-control form-control-sm" readonly>
                                        </div>
                                        <div class="col-12 mb-3" style="border-bottom: 1px solid #7a7a7a;">
                                            <div>
                                                <strong>DATOS DE FACTURACIÓN </strong>
                                                <i class="fas fa-minus float-right"
                                                    v-on:click="factura.datos = !factura.datos"
                                                    v-if="factura.datos"></i>
                                                <i class="fas fa-plus float-right"
                                                    v-on:click="factura.datos = !factura.datos" v-else></i>
                                            </div>
                                        </div>

                                        <template v-if="factura.datos">
                                            <div style="margin-bottom: 10px;" class="form-group col-6">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="tipo_documento">Tipo documento</label>
                                                <select id="tipo_documento" v-model="factura.tipo_documento"
                                                    class="form-control form-control-sm" :readonly="loading">
                                                    <option value="6">RUC - Registro Único de Contribuyentes</option>
                                                    <option value="1">DNI - Documento Nacional de Identidad</option>
                                                </select>
                                            </div>
                                            <div style="margin-bottom: 10px;" class="form-group col-6">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="numero_documento">N° Documento</label>
                                                <input type="text" id="numero_documento"
                                                    v-model="factura.numero_documento"
                                                    class="form-control form-control-sm"
                                                    onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}"
                                                    :readonly="loading">
                                            </div>
                                            <div style="margin-bottom: 10px;" class="form-group col-12">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="denominacion">Razón Social</label>
                                                <input type="text" id="denominacion" v-model="factura.denominacion"
                                                    class="form-control form-control-sm" :readonly="loading">
                                            </div>
                                            <div style="margin-bottom: 10px;" class="form-group col-12">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="direccion">Dirección</label>
                                                <input type="text" id="direccion" v-model="factura.direccion"
                                                    class="form-control form-control-sm" :readonly="loading">
                                            </div>

                                            <div style="margin-bottom: 10px;" class="form-group col-4">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="distrito">Distrito</label>
                                                <input type="text" id="distrito" v-model="factura.distrito"
                                                    class="form-control form-control-sm" :readonly="loading"
                                                    v-on:click="factura.mostrar_ubigeo = !factura.mostrar_ubigeo"
                                                    v-on:keyup="Ubigeo">
                                                <div v-if="factura.mostrar_ubigeo" class="mi-ubigeo">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Distrito</th>
                                                                <th class="text-center">Provincia</th>
                                                                <th class="text-center">Departamento</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="ubigeo in listaDistritos"
                                                                style="cursor: pointer;" v-on:click="selUbigeo(ubigeo)">
                                                                <td>@{{ ubigeo.description }}</td>
                                                                <td class="text-center">@{{ ubigeo.get_province.description }}</td>
                                                                <td class="text-center">@{{ ubigeo.get_province.get_department.description }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div style="margin-bottom: 10px;" class="form-group col-4">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="provincia">Provincia</label>
                                                <input type="text" id="provincia" v-model="factura.provincia"
                                                    class="form-control form-control-sm" readonly>
                                            </div>
                                            <div style="margin-bottom: 10px;" class="form-group col-4">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="departamento">Departamento</label>
                                                <input type="text" id="departamento" v-model="factura.departamento"
                                                    class="form-control form-control-sm" readonly>
                                            </div>

                                            <div style="margin-bottom: 10px;" class="form-group col-6">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="serie">Serie</label>
                                                <select id="serie" class="form-control form-control-sm"
                                                    :class="[errors.serie ? 'incorrecto' : '']" :readonly="loading"
                                                    v-on:change="selSerie($event)">
                                                    <option value="">Seleccion la Serie</option>
                                                    @foreach ($series as $serie)
                                                        <option value="{{ $serie->id . '/' . $serie->affected_igv }}">
                                                            {{ $serie->serie . ' - ' . $serie->descripcion . ' (' . $serie->forma . ')' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text is-invalid"
                                                    v-if="errors.serie">@{{ errors.serie[0] }}</small>
                                            </div>
                                        </template>

                                        <div class="col-12 mb-3 mt-2" style="border-bottom: 1px solid #7a7a7a;">
                                            <div>
                                                <strong>DETALLES DEL PAGO</strong>
                                                <i class="fas fa-minus float-right"
                                                    v-on:click="factura.detalles = !factura.detalles"
                                                    v-if="factura.detalles"></i>
                                                <i class="fas fa-plus float-right"
                                                    v-on:click="factura.detalles = !factura.detalles" v-else></i>
                                            </div>
                                        </div>
                                        <template v-if="factura.detalles">
                                            <div style="margin-bottom: 10px;" class="form-group col-4">
                                                <label style="font-size: 11px;" class="m-b-0" for="monto">Monto
                                                    (Soles)</label>
                                                <input type="text" id="monto" v-model="factura.monto"
                                                    class="form-control form-control-sm" :readonly="loading"
                                                    :clas="[errors.monto ? 'incorrecto' : '']">
                                                <small class="form-text is-invalid"
                                                    v-if="errors.monto">@{{ errors.monto[0] }}</small>
                                            </div>
                                            <div style="margin-bottom: 10px;" class="form-group col-8">
                                                <label style="font-size: 11px;" class="m-b-0" for="fecha_pago">Fecha
                                                    pago</label>
                                                <input type="date" id="fecha_pago" v-model="factura.fecha"
                                                    class="form-control form-control-sm" :readonly="loading"
                                                    :class="[errors.fecha_pago ? 'incorrecto' : '']"
                                                    max="{{ date('Y-m-d') }}">
                                                <small class="form-text is-invalid"
                                                    v-if="errors.fecha_pago">@{{ errors.fecha_pago[0] }}</small>
                                            </div>
                                            <div style="margin-bottom: 10px;" class="form-group col-4">
                                                <label style="font-size: 11px;" class="m-b-0" for="sub_total">Sub
                                                    total</label>
                                                <input type="number" id="sub_total" v-model="factura.sub_total"
                                                    class="form-control form-control-sm" readonly
                                                    :class="[errors.sub_total ? 'incorrecto' : '']">
                                                <small class="form-text is-invalid"
                                                    v-if="errors.sub_total">@{{ errors.sub_total[0] }}</small>
                                            </div>
                                            <div style="margin-bottom: 10px;" class="form-group col-4">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="igv">IGV</label>
                                                <input type="number" id="igv" v-model="factura.igv"
                                                    class="form-control form-control-sm" readonly
                                                    :class="[errors.igv ? 'incorrecto' : '']">
                                                <small class="form-text is-invalid"
                                                    v-if="errors.igv">@{{ errors.igv[0] }}</small>
                                            </div>
                                            <div style="margin-bottom: 10px;" class="form-group col-4">
                                                <label style="font-size: 11px;" class="m-b-0"
                                                    for="total">Total</label>
                                                <input type="number" id="total" v-model="factura.total"
                                                    class="form-control form-control-sm" readonly
                                                    :class="[errors.total ? 'incorrecto' : '']">
                                                <small class="form-text is-invalid"
                                                    v-if="errors.total">@{{ errors.total[0] }}</small>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-danger btn-block event-btn" v-on:click="Facturar"
                                        :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                            v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Facturando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i
                                                class="feather icon-times"></i> Facturar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- FACTURAR --}}

                            {{-- AVISOS --}}
                            <div class="modal-content" v-if="methods == 'avisos'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">Servicios Pendientes</h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <table class="table table-bordered table-sm">
                                        <tbody>
                                            <tr>
                                                <td colspan="2" class="text-center"
                                                    style="background-color: #ffc48e">SERVICIOS A VENCERSE</td>
                                            </tr>
                                            <tr v-for="avencer in listAVencer">
                                                <td class="text-center">
                                                    <a href="#"
                                                        v-on:click="searchBarras(avencer.codigo_barras+zeroFill(avencer.id, 4))">@{{ avencer.codigo_barras + zeroFill(avencer.id, 4) }}</a>
                                                </td>
                                                <td class="text-center">@{{ Fecha3(avencer.fecha_entrega) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-center"
                                                    style="background-color: #ff6b6b">SERVICIOS VENCIDOS</td>
                                            </tr>
                                            <tr v-for="vencidos in listVencidos">
                                                <td class="text-center">
                                                    <a href="#"
                                                        v-on:click="searchBarras(vencidos.codigo_barras+zeroFill(vencidos.id, 4))">@{{ vencidos.codigo_barras + zeroFill(vencidos.id, 4) }}</a>
                                                </td>
                                                <td class="text-center">@{{ Fecha3(vencidos.fecha_entrega) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- AVISOS --}}
                        </div>
                    </div>
                    {{-- MODAL --}}
                    <div class="mb-3 mt-3">
                        <button type="button" class="btn btn-icon btn-primary mr-2" style="min-width: 88px;"
                            data-toggle="modal" data-target="#formularioModal"
                            v-on:click="formularioModal('modal-lg', null, 'create', null)">
                            <div style="font-size: 30px;"><i class="fas fa-plus"></i></div>
                            <div>Nuevo</div>
                        </button>

                        <button type="button" class="btn btn-icon btn-warning mr-2" style="min-width: 88px;"
                            v-if="active != 0" v-on:click="Recibo(active)">
                            <div style="font-size: 30px;"><i class="fas fa-print"></i></div>
                            <div>Recibo</div>
                        </button>
                        <button type="button" class="btn btn-icon btn-warning disabled mr-2" style="min-width: 88px;"
                            v-else>
                            <div style="font-size: 30px;"><i class="fas fa-print"></i></div>
                            <div>Recibo</div>
                        </button>

                        <button type="button" class="btn btn-icon btn-dark mr-2" style="min-width: 88px;"
                            v-if="active != 0" v-on:click="codigoBarra(active, seleccion.codigo_barras)">
                            <div style="font-size: 30px;"><i class="fas fa-barcode"></i></div>
                            <div>Barra</div>
                        </button>
                        <button type="button" class="btn btn-icon btn-dark disabled mr-2" style="min-width: 88px;"
                            v-else>
                            <div style="font-size: 30px;"><i class="fas fa-barcode"></i></div>
                            <div>Barra</div>
                        </button>

                        <button type="button" class="btn btn-icon btn-success mr-2" style="min-width: 88px;"
                            v-if="active != 0" data-toggle="modal" data-target="#formularioModal"
                            v-on:click="formularioModal('', active, 'facturar', seleccion)">
                            <div style="font-size: 30px;"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div>Facturar</div>
                        </button>
                        <button type="button" class="btn btn-icon btn-success disabled mr-2" style="min-width: 88px;"
                            v-else>
                            <div style="font-size: 30px;"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div>Facturar</div>
                        </button>
                        <button type="button" class="btn btn-icon btn-danger mr-2" style="min-width: 88px;"
                            data-toggle="modal" data-target="#formularioModal"
                            v-on:click="formularioModal('', active, 'avisos', '')">
                            <div style="font-size: 30px;"><i class="fa fa-exclamation-triangle"></i></div>
                            <div>Avisos</div>
                        </button>

                        <div style="display: inline-flex; margin-top: -6px; vertical-align: top;">

                        </div>

                        <div class="float-right">
                            <div class="input-group input-group-sm">
                                <input type="text" id="search" v-model="search" class="form-control"
                                    placeholder="Buscar por número de serie" v-on:keyup.enter="Buscar">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" v-on:click="Buscar">
                                        <i class="fas fa-search"></i> &nbsp; Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center cell-1">#</th>
                                    <th class="cell-2 text-center">N° Caso</th>
                                    <th class="cell-3 text-center">Estado</th>
                                    <th class="cell-4">Cliente</th>
                                    <th class="cell-5 text-center">Fecha Registro</th>
                                    <th class="cell-6 text-center">Fecha Entrega</th>
                                    <th class="cell-7 text-center">Serie</th> <!-- Nueva columna para Serie -->
                                    <th class="cell-8">Detalles del Equipo</th>
                                    <th class="cell-9 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(soporte, index) in listaRequest" :class="{ activado: active == soporte.id }"
                                    v-on:click="Fila(soporte.id, soporte)" style="cursor: pointer;">
                                    <td class="text-center">@{{ (index + pagination.index + 1) }}</td>
                                    <td class="text-center text-uppercase">@{{ soporte.numero_caso ?? '' }}</td>
                                    <td>
                                        <div :class="soporte.estado">@{{ Estado(soporte.estado) }}</div>
                                    </td>
                                    <td>@{{ soporte.get_cliente.nombres }}</td>
                                    <td class="text-center">@{{ Fecha2(soporte.fecha_registro) + ' ' + Hora(soporte.fecha_registro) }}</td>
                                    <td class="text-center">@{{ Fecha2(soporte.fecha_entrega) + ' ' + Hora(soporte.fecha_entrega) }}</td>
                                    <td class="text-center">@{{ soporte.serie }}</td>
                                    <!-- Serie en su propia columna -->
                                    <td>
                                        <strong style="text-decoration: underline">EQUIPO:</strong>
                                        @{{ soporte.equipo }}<br>
                                        <strong style="text-decoration: underline;">MODELO:</strong>
                                        @{{ soporte.modelo }}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary" @click.stop="formEditar(soporte)"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" @click.stop="confirmarEliminar(soporte)"
                                            title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                                        title="Página inicio" v-on:click.prevent="changePage(1)"><i
                                            class="fas fa-step-backward"></i></a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado"
                                        title="Página inicio"><i class="fas fa-step-backward"></i></a>

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
                                        :class="[page == isActive ? 'active' : '']"><a href="#"
                                            class="page-link"
                                            v-on:click.prevent="changePage(page)">@{{ page }}</a></li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;" title="Siguiente"
                                            v-on:click.prevent="changePage(pagination.current_page + 1)">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>
                                    <li class="page-item disabled" title="Siguiente" v-else style="cursor: no-drop;">
                                        <a href="#" class="page-link"
                                            style="padding: 6px 10px 4px 10px; font-size: 18px;"><i
                                                class="fas fa-angle-right"></i></a>
                                    </li>

                                    <a href="#" v-if="pagination.current_page < pagination.last_page"
                                        class="pag-inicio-fin" title="Página final"
                                        v-on:click.prevent="changePage(pagination.last_page)"><i
                                            class="fas fa-step-forward"></i></a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado"
                                        title="Página final"><i class="fas fa-step-forward"></i></a>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div style="margin: 7px; font-size: 15px;" v-if="to_pagination">@{{ to_pagination + ' de ' + pagination.total + ' Registros' }}
                            </div>
                            <div style="margin: 7px; font-size: 15px;" v-else>0 de 0 Registros</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @include('sistema.servicio.modals.mdlCodigoBarra') --}}
            @php
                $barras = App\Models\Configuracion::where('nombre', 'logo_codigo_barras')->first();
            @endphp
            <div style="display:none;">
                <div id="imprimir" class="mb-3" style="display: flex; width: 100px;">
                    <div style="padding-top: 20px;">
                        @if ($barras->archivo)
                            <img src="{{ asset('storage/' . $barras->archivo_ruta . '/' . $barras->archivo) }}"
                                style="max-width: 136px; background-color: #fff; position: absolute; left: 9px; top: 4px;">
                        @else
                            <img src="{{ asset('theme/images/kenya.png') }}"
                                style="max-width: 136px; background-color: #fff; position: absolute; left: 9px; top: 4px;">
                        @endif
                        <div id="barcode"></div>
                    </div>
                    <div style="padding-top: 20px;">
                        @if ($barras->archivo)
                            <img src="{{ asset('storage/' . $barras->archivo_ruta . '/' . $barras->archivo) }}"
                                style="max-width: 136px; background-color: #fff; position: absolute; left: 164px; top: 4px;">
                        @else
                            <img src="{{ asset('theme/images/kenya.png') }}"
                                style="max-width: 136px; background-color: #fff; position: absolute; left: 164px; top: 4px;">
                        @endif
                        <div id="barcode1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var my_mw_soporte = {!! json_encode($mw_soporte) !!};
    </script>
    <script type="text/javascript" src="{{ asset('js/barcode.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.printarea.js') }}"></script>
    <script src="{{ asset('js/views/servicio/tecnico.js') }}"></script>
@endsection
