@extends('layouts.template')
@section('app-name')
    <title>Grupo kenya - Productos</title>
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
            width: 10%;
        }

        .cell-3 {
            width: 10%;
        }

        .cell-4 {
            width: 30%;
        }

        .cell-5 {
            width: 10%;
        }

        .cell-6 {
            width: 10%;
        }

        .cell-7 {
            width: 15%;
        }

        .cell-8 {
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

        .fc-new {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            height: 32px;
        }

        .form-group {
            margin-bottom: 0px;
        }

        .image {
            border: 1px dashed #ced4da;
            padding: 36px 10px;
            cursor: pointer;
            height: 110px;
            width: 100px;
            text-align: center;
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
    </style>
@endsection
@section('content')
    <div class="page-header breadcumb-sticky dash-sale"
        style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-archive"></i> Productos</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;">
                            <a href="index.html"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Productos</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-productos">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>LISTA DE PRODUCTOS</h5>
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
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(PRODUCTO)</span>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    @include('sistema.productos.form')
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

                            {{-- MODAL PARA AGREGAR/EDITAR ESPECIFICACIONES --}}
                            <div class="modal-content" v-if="methods == 'add_spec'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">ESPECIFICACIONES - @{{ seleccion.nombre }}</h5>
                                    <button type="button" class="btn btn-danger btn-xs float-right" data-dismiss="modal"
                                        aria-label="Close" v-on:click="closeModal(methods)"
                                        style="padding: 0px 7px;">X</button>
                                </div>

                                <form :action="'/producto/' + seleccion.id + '/especificaciones'" method="POST">
                                    @csrf
                                    <div class="modal-body" style="padding: 15px 15px;">

                                        {{-- Lista de especificaciones existentes --}}
                                        <div class="mb-4">
                                            <h6 class="mb-3">Especificaciones Actuales</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Campo</th>
                                                            <th>Descripción</th>
                                                            <th width="80px">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(espec, index) in seleccion.especificaciones"
                                                            :key="index" :data-id="espec.id">
                                                            <td class="editable-cell" data-campo="campo">
                                                                @{{ espec.campo }}</td>
                                                            <td class="editable-cell" data-campo="descripcion">
                                                                @{{ espec.descripcion }}</td>
                                                            <td class="text-center">
                                                                <button type="button"
                                                                    class="btn btn-xs btn-danger btn-eliminar-espec"
                                                                    :data-id="espec.id">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>

                                                        <tr
                                                            v-if="!seleccion.especificaciones || seleccion.especificaciones.length == 0">
                                                            <td colspan="3" class="text-center text-muted">No hay
                                                                especificaciones registradas</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <hr>

                                        {{-- Formulario para agregar nueva especificación --}}
                                        <h6 class="mb-3">Agregar Nueva Especificación</h6>
                                        <div class="form-group">
                                            <label>Campo</label>
                                            <input type="text" name="campo" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Descripción</label>
                                            <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="padding: 10px 15px;">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-plus-circle"></i> Agregar Especificación
                                        </button>
                                    </div>
                                </form>
                            </div>
                            {{-- MODAL PARA EDITAR ESPECIFICACIONES --}}

                           {{-- MODAL PARA IMPORTAR DESDE EXCEL --}}
<div class="modal-content" v-if="methods == 'import_spec'">
    <div class="modal-header" style="padding: 10px 15px">
        <h5 class="mb-0">IMPORTAR ESPECIFICACIONES</h5>
        <button type="button" class="btn btn-danger btn-xs float-right" data-dismiss="modal"
            aria-label="Close" v-on:click="closeModal(methods)" style="padding: 0px 7px;">X</button>
    </div>
    <form @submit.prevent="importarEspecificaciones">
        <div class="modal-body" style="padding: 15px 15px;">
            <div class="form-group">
                <label>Filtrar por Modelo</label>
                <select v-model="modeloSeleccionado" class="form-control" @change="resetBusqueda">
                    <option value="">Todos los Modelos</option>
                    <option v-for="modelo in listaModelos" :value="modelo.id">@{{ modelo.descripcion }}</option>
                </select>
                <small class="text-muted">Seleccione un modelo para buscar productos.</small>
            </div>
            <div class="form-group">
                <label>Buscar Producto por N° de Parte</label>
                <input type="text" v-model="busquedaParte" class="form-control" placeholder="Ingrese N° de parte y presione Enter" @keyup.enter="buscarPorParte">
                <ul v-if="resultadosBusqueda.length" class="list-group mt-2">
                    <li class="list-group-item" v-for="prod in resultadosBusqueda" :key="prod.id" @click="seleccionarProducto(prod)" style="cursor:pointer">
                        <b>@{{ prod.nro_parte }}</b> - @{{ prod.nombre }}
                    </li>
                </ul>
                <small class="text-muted">Escriba el número de parte y presione Enter. Luego seleccione el producto.</small>
            </div>
            <div class="form-group" v-if="productoSeleccionado">
                <label>Producto Seleccionado</label>
                <div class="alert alert-info">
                    <b>@{{ productoSeleccionado.nro_parte }}</b> - @{{ productoSeleccionado.nombre }}
                </div>
            </div>
            <div class="form-group" v-if="productoSeleccionado">
                <label>Archivos Excel</label>
                <input type="file" class="form-control" multiple accept=".xlsx,.xls,.csv"
                    @change="agregarArchivosExcel">
                <small class="text-muted">Formato requerido: Columna A = Campo, Columna B = Descripción</small>
            </div>
            <div class="form-group" v-if="productoSeleccionado && archivosExcel.length">
                <label>Archivos Seleccionados</label>
                <ul>
                    <li v-for="(archivo, index) in archivosExcel" :key="index">
                        @{{ archivo.name }}
                        <button type="button" class="btn btn-sm btn-danger" @click="eliminarArchivoExcel(index)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </li>
                </ul>
                <small class="text-muted">Puede agregar más archivos antes de importar.</small>
            </div>
        </div>
        <div class="modal-footer" style="padding: 10px 15px;">
            <button type="submit" class="btn btn-success btn-block" :disabled="!productoSeleccionado || archivosExcel.length == 0">
                <i class="fas fa-file-import"></i> Importar Especificaciones
            </button>
        </div>
    </form>
</div>
{{-- MODAL PARA IMPORTAR DESDE EXCEL --}}

                            {{-- EDITAR --}}
                            <div class="modal-content" v-if="methods == 'edit'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">EDITAR <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(PRODUCTO)</span>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    @include('sistema.productos.form')
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-primary btn-block event-btn" v-on:click="Update"
                                        :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                            v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Actualizar...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i
                                                class="feather icon-times"></i> Actualizar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- EDITAR --}}

                            {{-- ELIMINAR --}}

                            <div class="modal-content" v-if="methods == 'delete'" id="delete">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">ELIMINAR <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(PRODUCTO)</span>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <p class="text-center">
                                        Realmente desea eliminar el Producto <strong>"@{{ producto.nombre }}"</strong>
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

                            {{-- DUPLICAR --}}
                            <div class="modal-content" v-if="methods == 'duplicate'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">Duplicar <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(PRODUCTO)</span>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    @include('sistema.productos.form')
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-primary btn-block event-btn" v-on:click="Store"
                                        :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                            v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Actualizar...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i
                                                class="feather icon-times"></i> Duplicar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- DUPLICAR --}}
                        </div>
                    </div>
                    {{-- MODAL --}}

                    <div class="row">
                        <div class="mb-3 mt-3 col-md-6">
                            <button type="button" class="btn btn-icon btn-primary mr-2" style="min-width: 88px;"
                                data-bs-toggle="modal" data-bs-target="#formularioModal"
                                v-on:click="formularioModal('modal-lg', null, 'create', null)">
                                <div style="font-size: 30px;"><i class="fas fa-plus"></i></div>
                                <div>Nuevo</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-warning mr-2" style="min-width: 88px;"
                                data-bs-toggle="modal" data-bs-target="#formularioModal"
                                v-on:click="formularioModal('modal-lg', active, 'add_spec', seleccion)"
                                :disabled="!active">
                                <div style="font-size: 30px;"><i class="fas fa-list-alt"></i></div>
                                <div>Especificaciones</div>
                            </button>

                            <!-- filepath: c:\xampp\htdocs\kenya_tienda\resources\views\sistema\productos\index.blade.php -->
<button type="button" class="btn btn-icon btn-secondary mr-2" style="min-width: 88px;"
data-bs-toggle="modal" data-bs-target="#formularioModal"
v-on:click="formularioModal('modal-lg', null, 'import_spec', null)">
<div style="font-size: 30px;"><i class="fas fa-file-excel"></i></div>
<div>Importar</div>
</button>
                            <button type="button" class="btn btn-icon btn-info mr-2" style="min-width: 88px;"
                                v-if="active != 0" data-bs-toggle="modal" data-bs-target="#formularioModal"
                                v-on:click="formularioModal('modal-lg', active, 'edit', seleccion)">
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-info disabled mr-2"
                                style="min-width: 88px;" v-else>
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-success mr-2" style="min-width: 88px;"
                                v-if="active != 0" data-bs-toggle="modal" data-bs-target="#formularioModal"
                                v-on:click="formularioModal('modal-lg', active, 'duplicate', seleccion)">
                                <div style="font-size: 30px;"><i class="fas fa-clone"></i></div>
                                <div>Duplicar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-success disabled mr-2"
                                style="min-width: 88px;" v-else>
                                <div style="font-size: 30px;"><i class="fas fa-clone"></i></div>
                                <div>Duplicar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-danger mr-2" style="min-width: 88px;"
                                v-if="active != 0" data-bs-toggle="modal" data-bs-target="#formularioModal"
                                v-on:click="formularioModal('modal-sm', active, 'delete', seleccion.nombres)">
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-danger disabled mr-2"
                                style="min-width: 88px;" v-else>
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>
                        </div>
                        <div class="mb-3 mt-3 col-md-3">
                            <div class="float-right">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="search" v-model="search" class="form-control focus_this"
                                        placeholder="Buscar productos..." v-on:keyup.enter="Buscar" autofocus>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" v-on:click="Buscar">
                                            <i class="fas fa-search"></i> &nbsp; Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros de búsqueda adicionales -->
                    <div class="row mb-3 pb-3 pt-3 shadow-sm rounded" style="background-color: #f4f7fa; border: 1px solid #e1e5eb; margin-left: 0; margin-right: 0;">
                        <div class="col-md-3">
                            <label class="text-muted mb-1" style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px;"><i class="fas fa-box text-primary"></i> MODELO</label>
                            <select v-model="search_modelo" class="form-control form-control-sm border-0 shadow-sm" @change="Buscar(1)">
                                <option value="">Todos los Modelos</option>
                                <option v-for="modelo in listaModelos" :value="modelo.id">@{{ modelo.descripcion }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted mb-1" style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px;"><i class="fas fa-server text-info"></i> ORIGEN</label>
                            <select v-model="search_origen" class="form-control form-control-sm border-0 shadow-sm" @change="Buscar(1)">
                                <option value="">Todos los Orígenes</option>
                                <option value="local">Local</option>
                                <option value="api">API</option>
                                <option value="api_editado">API (Editado)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted mb-1" style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px;"><i class="fas fa-toggle-on text-success"></i> ESTADO ACTIVO</label>
                            <select v-model="search_activo" class="form-control form-control-sm border-0 shadow-sm" @change="Buscar(1)">
                                <option value="">Todos los Estados</option>
                                <option value="SI">SI (Activo)</option>
                                <option value="NO">NO (Inactivo)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted mb-1" style="font-size: 11px; font-weight: bold; letter-spacing: 0.5px;"><i class="fas fa-globe text-warning"></i> VISIBILIDAD WEB</label>
                            <select v-model="search_web" class="form-control form-control-sm border-0 shadow-sm" @change="Buscar(1)">
                                <option value="">Ambas Visibilidades</option>
                                <option value="SI">Mostrar en Web</option>
                                <option value="NO">Ocultar en Web</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="cell-1 text-center">#</th>
                                    <th class="cell-2">Nombre</th>
                                    <th class="cell-3">Num. Parte</th>
                                    <th class="cell-4 text-center">Modelo</th>
                                    <th class="cell-6 text-center">Ficha Tecnica</th>
                                    <th class="text-center">Origen Ficha</th>
                                    <th class="text-center">Activo</th>
                                    <th class="cell-8 text-center">Web</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="list-loading">
                                    <td colspan="8" class="text-center">
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
                                        <tr v-for="(producto, index) in listaRequest"
                                            :class="{ activado: active == producto.id }"
                                            v-on:click="Fila(producto.id, producto)" style="cursor: pointer;">
                                            <td class="text-center">@{{ (index + pagination.index + 1) }}</td>
                                            <td>@{{ producto.nombre }}</td>
                                            <td>@{{ producto.nro_parte }}</td>
                                            <td class="text-center">
                                                <div v-if="producto.modelo_id">@{{ producto.get_modelo.descripcion }}</div>
                                            </td>

                                            <td class="text-center">@{{ producto.ficha_tecnica }}</td>

                                            <td class="text-center">
                                                <span v-if="producto.codigo_pc && !producto.ficha_editada_localmente" class="badge badge-info">API</span>
                                                <span v-else-if="producto.codigo_pc && producto.ficha_editada_localmente" class="badge badge-warning">API (Editado)</span>
                                                <span v-else class="badge badge-secondary">Local</span>
                                            </td>
                                            <td class="text-center">
                                                <span v-if="producto.activo === 'SI'" class="badge badge-success">SI</span>
                                                <span v-else class="badge badge-danger">NO</span>
                                            </td>

                                            <td class="text-center">
                                                <a href="#" v-if="producto.pagina_web == 'SI'"
                                                    v-on:click="Web(producto.id, 'NO')" title="No mostrar en la Web"><i
                                                        class="fa fa-check-circle"
                                                        style="color: green; font-size: 16px;"></i></a>
                                                <a href="#" v-else v-on:click="Web(producto.id, 'SI')"
                                                    title="Mostrar en la Web"><i class="fa fa-times-circle"
                                                        style="color: red; font-size: 16px;"></i></a>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td colspan="8" class="text-center" style="font-size: 20px;">No existe
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
                                                class="fas fa-angle-right"></i></a></li>

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
        var mis_marcas = {!! json_encode($marcas) !!};
        var mis_categorias = {!! json_encode($categorias) !!};
        var mis_procesadores = {!! json_encode($procesador) !!};
        var mi_almacen = {!! json_encode($almacenamiento) !!};
        var mi_ram = {!! json_encode($ram) !!};
        var mi_ofimatica = {!! json_encode($ofimatica) !!};
        var mis_tarjetavideo = {!! json_encode($tarjetavideo) !!};
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = $('#formularioModal');

            modal.on('shown.bs.modal', function() {
                const vueApp = document.querySelector('#form-productos').__vue__;
                if (!vueApp || vueApp.methods !== 'add_spec' || !vueApp.seleccion?.id) return;

                // Obtener especificaciones al abrir
                axios.get(`/producto/${vueApp.seleccion.id}/especificaciones`)
                    .then(res => {
                        vueApp.seleccion.especificaciones = res.data.especificaciones || [];
                    })
                    .catch(() => {
                        vueApp.seleccion.especificaciones = [];
                    });

                // Eliminar desde DOM delegadamente
                modal.off('click', '.btn-eliminar-espec').on('click', '.btn-eliminar-espec', function() {
                    const especId = this.dataset.id;
                    if (!confirm('¿Eliminar esta especificación?')) return;

                    axios.delete(`/producto/especificaciones/${especId}`)
                        .then(() => {
                            vueApp.seleccion.especificaciones = vueApp.seleccion
                                .especificaciones.filter(e => e.id != especId);
                        });
                });

                // Activar edición inline
                modal.off('dblclick', '.editable-cell').on('dblclick', '.editable-cell', function() {
                    const td = $(this);
                    const campo = td.data('campo');
                    const id = td.closest('tr').data('id');
                    const valorOriginal = td.text().trim();

                    const input = $('<input type="text" class="form-control form-control-sm">')
                        .val(valorOriginal)
                        .on('blur keyup', function(e) {
                            if (e.type === 'blur' || e.key === 'Enter') {
                                const nuevoValor = $(this).val();
                                if (nuevoValor === valorOriginal) {
                                    td.text(valorOriginal);
                                    return;
                                }

                                const data = {
                                    [campo]: nuevoValor
                                };
                                axios.put(`/producto/especificaciones/${id}/editar`, data)
                                    .then(() => {
                                        td.text(nuevoValor);
                                        const espec = vueApp.seleccion.especificaciones
                                            .find(e => e.id == id);
                                        if (espec) espec[campo] = nuevoValor;
                                    })
                                    .catch(() => {
                                        td.text(valorOriginal);
                                        alert('Error al actualizar.');
                                    });
                            }
                        });

                    td.empty().append(input);
                    input.focus();
                });
            });
        });
    </script>

    <script type="text/javascript" src="{{ asset('js/barcode.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.printarea.js') }}"></script>
    <script src="{{ asset('js/views/productos/productos.js') }}?v={{ time() }}"></script>
@endsection
