@extends('layouts.template')

@section('app-name')
    <title>KENYA - Clientes Web (Portal de Cotizaciones)</title>
@endsection

@section('css')
    <style>
        .activado { background-color: #e8f2fc; color: #1c82e1; }
        .cell-1 { width: 5%; }
        .cell-2 { width: 18%; }
        .cell-3 { width: 32%; }
        .cell-4 { width: 15%; }
        .cell-5 { width: 15%; }
        .cell-6 { width: 10%; }
        .cell-7 { width: 5%; }
        .table.table-sm td, .table.table-sm th { vertical-align: middle; }
        .label-sm { font-size: 11px; margin: 0; }
        .font-green { color: green; }
        .font-red   { color: red; }
        .badge-cliente { background: linear-gradient(135deg,#ee7c31,#f3a468); color:#fff; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; }
    </style>
@endsection

@section('content')
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-user-tag"></i> Clientes Web — Portal de Cotizaciones</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="{{ route('home') }}"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Clientes Web</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="top: 40px; position: inherit;" id="app-clientes-web">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header" style="background:linear-gradient(135deg,#ee7c31,#f3a468);">
                    <h5 style="color:#fff; margin:0;"><i class="fas fa-user-tag"></i> LISTA DE CLIENTES VERIFICADOS <span class="badge-cliente ml-2">cliente_web</span></h5>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <ul class="list-unstyled card-option" style="display: contents;">
                                <li class="full-card"><a href="#!" class="windows-button"><span title="Maximizar"><i class="feather icon-maximize"></i></span><span style="display:none"><i class="feather icon-minimize"></i></span></a></li>
                                <li class="close-card"><a href="#!" class="windows-button" title="Cerrar"><i class="feather icon-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    {{-- MODAL --}}
                    <div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" :class="modal_size" role="document">

                            {{-- NUEVO CLIENTE --}}
                            <div class="modal-content" v-if="methods == 'create'">
                                <div class="modal-header" style="padding: 10px 15px; background:linear-gradient(135deg,#ee7c31,#f3a468);">
                                    <h5 class="mb-0 text-white">NUEVO <span style="font-size: 14px; font-weight: 400; opacity:0.9;">(CLIENTE WEB)</span></h5>
                                    <button type="button" data-dismiss="modal" aria-label="Close" v-on:click="closeModal()" class="btn btn-sm btn-light float-right">X</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">NOMBRES</label>
                                            <input type="text" v-model="nombres" class="form-control" :class="[errors.nombres ? 'is-invalid' : '']" :readonly="loading" placeholder="Nombres del cliente">
                                            <small class="form-text error-color" v-if="errors.nombres">@{{ errors.nombres[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">APELLIDO PATERNO</label>
                                            <input type="text" v-model="paterno" class="form-control" :class="[errors.paterno ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.paterno">@{{ errors.paterno[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">APELLIDO MATERNO</label>
                                            <input type="text" v-model="materno" class="form-control" :class="[errors.materno ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.materno">@{{ errors.materno[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">TELÉFONO</label>
                                            <input type="text" v-model="telefono" class="form-control" :class="[errors.telefono ? 'is-invalid' : '']" :readonly="loading" maxlength="9" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                            <small class="form-text error-color" v-if="errors.telefono">@{{ errors.telefono[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="label-sm">EMAIL</label>
                                            <input type="email" v-model="email" class="form-control" :class="[errors.email ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.email">@{{ errors.email[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">USUARIO (login)</label>
                                            <input type="text" v-model="username" class="form-control" :class="[errors.username ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.username">@{{ errors.username[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">CONTRASEÑA</label>
                                            <div class="input-group">
                                                <input :type="password_show ? 'text' : 'password'" v-model="password" class="form-control" :class="[errors.password ? 'is-invalid' : '']" :readonly="loading">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="cursor:pointer;" v-on:click="password_show = !password_show">
                                                        <i class="fas" :class="password_show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <small class="form-text error-color" v-if="errors.password">@{{ errors.password[0] }}</small>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="alert alert-info py-1 px-2 mb-0" style="font-size:12px;">
                                                <i class="fas fa-info-circle"></i> El rol <strong>cliente_web</strong> se asignará automáticamente. Este usuario podrá ver precios en el portal de cotizaciones.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-primary btn-block event-btn" v-on:click="Store" :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span v-if="loading">Guardando...</span>
                                        <span v-else><i class="fas fa-save"></i> Guardar Cliente</span>
                                    </button>
                                </div>
                            </div>

                            {{-- EDITAR CLIENTE --}}
                            <div class="modal-content" v-if="methods == 'edit'">
                                <div class="modal-header" style="padding: 10px 15px; background:#1c82e1;">
                                    <h5 class="mb-0 text-white">EDITAR <span style="font-size: 14px; font-weight: 400; opacity:0.9;">(CLIENTE WEB)</span></h5>
                                    <button type="button" data-dismiss="modal" aria-label="Close" v-on:click="closeModal()" class="btn btn-sm btn-light float-right">X</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">NOMBRES</label>
                                            <input type="text" v-model="nombres" class="form-control" :class="[errors.nombres ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.nombres">@{{ errors.nombres[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">APELLIDO PATERNO</label>
                                            <input type="text" v-model="paterno" class="form-control" :class="[errors.paterno ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.paterno">@{{ errors.paterno[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">APELLIDO MATERNO</label>
                                            <input type="text" v-model="materno" class="form-control" :class="[errors.materno ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.materno">@{{ errors.materno[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">TELÉFONO</label>
                                            <input type="text" v-model="telefono" class="form-control" :class="[errors.telefono ? 'is-invalid' : '']" :readonly="loading" maxlength="9">
                                            <small class="form-text error-color" v-if="errors.telefono">@{{ errors.telefono[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="label-sm">EMAIL</label>
                                            <input type="email" v-model="email" class="form-control" :class="[errors.email ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.email">@{{ errors.email[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">USUARIO (login)</label>
                                            <input type="text" v-model="username" class="form-control" :class="[errors.username ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.username">@{{ errors.username[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">NUEVA CONTRASEÑA <small class="text-muted">(dejar vacío para no cambiar)</small></label>
                                            <div class="input-group">
                                                <input :type="password_show ? 'text' : 'password'" v-model="password" class="form-control" :readonly="loading" placeholder="••••••••">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="cursor:pointer;" v-on:click="password_show = !password_show">
                                                        <i class="fas" :class="password_show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="label-sm">ESTADO</label>
                                            <select v-model="activo" class="form-control">
                                                <option value="SI">Activo</option>
                                                <option value="NO">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-info btn-block event-btn" v-on:click="Update" :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span v-if="loading">Actualizando...</span>
                                        <span v-else><i class="fas fa-save"></i> Actualizar Cliente</span>
                                    </button>
                                </div>
                            </div>

                            {{-- ELIMINAR CLIENTE --}}
                            <div class="modal-content" v-if="methods == 'delete'">
                                <div class="modal-header" style="padding: 10px 15px; background:#e74c3c;">
                                    <h5 class="mb-0 text-white">ELIMINAR (CLIENTE WEB)</h5>
                                    <button type="button" data-dismiss="modal" v-on:click="closeModal()" class="btn btn-sm btn-light float-right">X</button>
                                </div>
                                <div class="modal-body text-center">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                    <p>¿Realmente desea eliminar al cliente <strong>"@{{username}}"</strong>?</p>
                                    <p class="text-muted small">Esta acción eliminará al usuario y revocará su acceso al portal de cotizaciones.</p>
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-danger btn-block event-btn" v-on:click="Delete" :disabled="loading">
                                        <span v-if="loading">Eliminando...</span>
                                        <span v-else><i class="fas fa-trash-alt"></i> Confirmar Eliminación</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- /MODAL --}}

                    <div class="row">
                        <div class="mb-3 mt-3 col-md-9">
                            <button type="button" class="btn btn-icon mr-2" style="min-width:88px; background:#ee7c31; color:#fff;"
                                data-bs-toggle="modal" data-bs-target="#modalCliente"
                                v-on:click="formularioModal('', null, 'create')">
                                <div style="font-size: 30px;"><i class="fas fa-plus"></i></div>
                                <div>Nuevo</div>
                            </button>

                            <button type="button" class="btn btn-icon btn-info mr-2" style="min-width:88px;" v-if="active != 0"
                                data-bs-toggle="modal" data-bs-target="#modalCliente"
                                v-on:click="formularioModal('', active, 'edit', seleccion)">
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-info disabled mr-2" style="min-width:88px;" v-else>
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>

                            <button type="button" class="btn btn-icon btn-danger mr-2" style="min-width:88px;" v-if="active != 0"
                                data-bs-toggle="modal" data-bs-target="#modalCliente"
                                v-on:click="formularioModal('modal-sm', active, 'delete', seleccion.username)">
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-danger disabled mr-2" style="min-width:88px;" v-else>
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>
                        </div>
                        <div class="mb-3 mt-3 col-md-3">
                            <div class="p-b-10">
                                <input type="text" class="form-control" v-model="search" placeholder="Buscar por nombre, email o usuario..." v-on:keyup.enter="Buscar">
                            </div>
                            <button class="btn btn-secondary btn-block" v-on:click="Buscar">Buscar</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="cell-1 text-center">#</th>
                                    <th class="cell-2 text-center">Usuario</th>
                                    <th class="cell-3">Datos</th>
                                    <th class="cell-4 text-center">Email</th>
                                    <th class="cell-5 text-center">Fecha Alta</th>
                                    <th class="cell-6 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="list-loading">
                                    <td colspan="6" class="text-center">
                                        <div>
                                            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
                                            <span style="font-size: 20px; padding: 5px;">Cargando clientes...</span>
                                        </div>
                                    </td>
                                </tr>
                                <template v-if="listTable">
                                    <template v-if="listaRequest.length != 0">
                                        <tr v-for="(cliente, index) in listaRequest"
                                            :class="{ activado : active == cliente.id }"
                                            v-on:click="Fila(cliente.id, cliente)" style="cursor:pointer;">
                                            <td class="text-center">@{{ index + pagination.index + 1 }}</td>
                                            <td class="text-center"><strong>@{{ cliente.username }}</strong></td>
                                            <td>
                                                <div><i class="fas fa-user"></i> @{{ cliente.nombres + ' ' + cliente.ape_paterno + ' ' + cliente.ape_materno }}</div>
                                                <div><i class="fas fa-phone-square"></i> @{{ cliente.telefono }}</div>
                                            </td>
                                            <td class="text-center"><small>@{{ cliente.email }}</small></td>
                                            <td class="text-center"><small>@{{ Fecha(cliente.created_at) }}</small></td>
                                            <td class="text-center">
                                                <i class="fas fa-check-circle font-green" style="font-size:15px;" v-if="cliente.activo == 'SI'"></i>
                                                <i class="fas fa-times-circle font-red" style="font-size:15px;" v-else></i>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr><td colspan="6" class="text-center" style="font-size:18px; padding:30px; color:#999;">No hay clientes verificados registrados todavía.</td></tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div id="list-paginator" style="display:none;" class="row">
                        <div class="col-sm-4 text-left">
                            <div style="margin: 7px; font-size: 15px;">@{{ pagination.current_page + ' de ' + pagination.to + ' Páginas' }}</div>
                        </div>
                        <div class="col-sm-4">
                            <nav class="text-center" aria-label="...">
                                <ul class="pagination" style="justify-content: center;">
                                    <a href="#" v-if="pagination.current_page > 1" class="pag-inicio-fin" v-on:click.prevent="changePage(1)"><i class="fas fa-step-backward"></i></a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado"><i class="fas fa-step-backward"></i></a>
                                    <li class="page-item" v-if="pagination.current_page > 1"><a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;" v-on:click.prevent="changePage(pagination.current_page - 1)"><i class="fas fa-angle-left"></i></a></li>
                                    <li class="page-item disabled" v-else><a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;"><i class="fas fa-angle-left"></i></a></li>
                                    <li class="page-item" v-for="page in pagesNumber" :class="[page == isActive ? 'active' : '']"><a href="#" class="page-link" v-on:click.prevent="changePage(page)">@{{ page }}</a></li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page"><a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;" v-on:click.prevent="changePage(pagination.current_page + 1)"><i class="fas fa-angle-right"></i></a></li>
                                    <li class="page-item disabled" v-else><a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;"><i class="fas fa-angle-right"></i></a></li>
                                    <a href="#" v-if="pagination.current_page < pagination.last_page" class="pag-inicio-fin" v-on:click.prevent="changePage(pagination.last_page)"><i class="fas fa-step-forward"></i></a>
                                    <a href="#" v-else class="pag-inicio-fin desabilitado"><i class="fas fa-step-forward"></i></a>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div style="margin: 7px; font-size: 15px;" v-if="to_pagination">@{{ to_pagination + ' de ' + pagination.total + ' Clientes' }}</div>
                            <div style="margin: 7px; font-size: 15px;" v-else>0 de 0 Clientes</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
new Vue({
    el: '#app-clientes-web',
    data: {
        methods: '',
        modal_size: '',
        active: 0,
        seleccion: null,
        loading: false,
        listTable: false,
        listaRequest: [],
        pagination: {},
        isActive: 1,
        pagesNumber: [],
        to_pagination: 0,
        search: '',
        errors: {},
        // Campos
        nombres: '', paterno: '', materno: '', email: '',
        telefono: '', username: '', password: '', activo: 'SI',
        password_show: false,
    },
    mounted() {
        this.Buscar();
    },
    methods: {
        Fecha(val) {
            if (!val) return '';
            return new Date(val).toLocaleDateString('es-PE');
        },
        formularioModal(size, id, method, data) {
            this.methods    = method;
            this.modal_size = size;
            this.errors     = {};
            if (method === 'create') {
                this.nombres = ''; this.paterno = ''; this.materno = '';
                this.email = ''; this.telefono = ''; this.username = '';
                this.password = ''; this.activo = 'SI'; this.password_show = false;
            }
            if ((method === 'edit') && data) {
                this.active   = id;
                this.nombres  = data.nombres;
                this.paterno  = data.ape_paterno;
                this.materno  = data.ape_materno;
                this.email    = data.email;
                this.telefono = data.telefono;
                this.username = data.username;
                this.activo   = data.activo;
                this.password = '';
                this.password_show = false;
            }
            if (method === 'delete') {
                this.active   = id;
                this.username = data;
            }
            $('#modalCliente').modal('show');
        },
        closeModal() {
            $('#modalCliente').modal('hide');
        },
        Fila(id, data) {
            if (this.active === id) {
                this.active = 0; this.seleccion = null;
            } else {
                this.active = id; this.seleccion = data;
            }
        },
        Buscar(page = 1) {
            $('#list-loading').show();
            this.listTable = false;
            axios.post('/clientes-web/buscar?page=' + page, { search: this.search, _token: '{{ csrf_token() }}' })
                .then(res => {
                    this.listaRequest  = res.data.clientes.data;
                    this.pagination    = res.data.pagination;
                    this.isActive      = res.data.pagination.current_page;
                    this.to_pagination = res.data.clientes.data.length;
                    this.pagesNumber   = this.calcularPaginas(res.data.pagination);
                    this.listTable     = true;
                    $('#list-loading').hide();
                    if (res.data.pagination.total > 0) $('#list-paginator').show();
                    else $('#list-paginator').hide();
                });
        },
        calcularPaginas(pag) {
            let pages = [], from = pag.current_page - 2, to = pag.current_page + 2;
            if (from < 1) from = 1;
            if (to > pag.last_page) to = pag.last_page;
            for (let i = from; i <= to; i++) pages.push(i);
            return pages;
        },
        changePage(page) { this.Buscar(page); },
        toast(res) {
            Swal.fire({ icon: res.type === 'success' ? 'success' : 'error', title: res.title, text: res.message, timer: 3000, showConfirmButton: false });
        },
        Store() {
            this.loading = true; this.errors = {};
            axios.post('/clientes-web/store', {
                nombres: this.nombres, paterno: this.paterno, materno: this.materno,
                email: this.email, telefono: this.telefono, username: this.username,
                password: this.password, _token: '{{ csrf_token() }}'
            }).then(res => {
                this.loading = false;
                if (res.data.errors) { this.errors = res.data.errors; return; }
                this.closeModal(); this.Buscar(); this.toast(res.data);
            }).catch(err => {
                this.loading = false;
                if (err.response && err.response.data.errors) this.errors = err.response.data.errors;
            });
        },
        Update() {
            this.loading = true; this.errors = {};
            axios.post('/clientes-web/update', {
                id: this.active, nombres: this.nombres, paterno: this.paterno, materno: this.materno,
                email: this.email, telefono: this.telefono, username: this.username,
                password: this.password, activo: this.activo, _token: '{{ csrf_token() }}'
            }).then(res => {
                this.loading = false;
                if (res.data.errors) { this.errors = res.data.errors; return; }
                this.closeModal(); this.Buscar(); this.toast(res.data);
            }).catch(err => {
                this.loading = false;
                if (err.response && err.response.data.errors) this.errors = err.response.data.errors;
            });
        },
        Delete() {
            this.loading = true;
            axios.post('/clientes-web/delete', { id: this.active, _token: '{{ csrf_token() }}' })
                .then(res => {
                    this.loading = false;
                    this.active = 0; this.seleccion = null;
                    this.closeModal(); this.Buscar(); this.toast(res.data);
                });
        }
    }
});
</script>
@endsection
