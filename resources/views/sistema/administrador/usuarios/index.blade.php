@extends('layouts.template')
@section('app-name')
    <title>KENYA - Usuarios</title>
@endsection
@section('css')
    <style>
        .activado {
            background-color: #e8f2fc;
            color: #1c82e1;
        }
        .cell-1 { width: 5%; }
        .cell-2 { width: 20%; }
        .cell-3 { width: 35%; }
        .cell-4 { width: 15%; }
        .cell-5 { width: 15%; }
        .cell-6 { width: 10%; }

        .table.table-sm td, .table.table-sm th {
            vertical-align: middle;
        }
        .disabled {
            cursor: no-drop !important;
        }
        .font-green {
            color: green;
        }
        .font-red {
            color: red;
        }
        .label-sm {
            font-size: 11px;
            margin: 0;
        }
    </style>
@endsection
@section('content')
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-user-shield"></i> Usuarios</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="index.html"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Usuarios</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-user">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>LISTA DE USUARIOS</h5>
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
                                    <h5 class="mb-0">NUEVO <span style="color: #929292; font-size: 17px; font-weight: 400;">(USUARIO)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="dni" class="label-sm">DNI</label>
                                            <input type="text" id="dni" v-model="dni" class="form-control" :class="[errors.dni ? 'is-invalid' : '']" :readonly="loading"
                                            maxlength="8" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                            <small class="form-text error-color" v-if="errors.dni">@{{ errors.dni[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="nombres" class="label-sm">NOMBRES</label>
                                            <input type="text" id="nombres" v-model="nombres" class="form-control" :class="[errors.nombres ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.nombres">@{{ errors.nombres[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="paterno" class="label-sm">APELLIDO PATERNO</label>
                                            <input type="text" id="paterno" v-model="paterno" class="form-control" :class="[errors.paterno ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.paterno">@{{ errors.paterno[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="materno" class="label-sm">APELLIDO MATERNO</label>
                                            <input type="text" id="materno" v-model="materno" class="form-control" :class="[errors.materno ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.materno">@{{ errors.materno[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="telefono" class="label-sm">TELEFONO</label>
                                            <input type="text" id="telefono" v-model="telefono" class="form-control" :class="[errors.telefono ? 'is-invalid' : '']" :readonly="loading"
                                            maxlength="9" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                            <small class="form-text error-color" v-if="errors.telefono">@{{ errors.telefono[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="email" class="label-sm">EMAIL</label>
                                            <input type="email" id="email" v-model="email" class="form-control" :class="[errors.email ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.email">@{{ errors.email[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="username" class="label-sm">NOMBRE USUARIO</label>
                                            <input type="text" id="username" v-model="username" class="form-control" :class="[errors.username ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.username">@{{ errors.username[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="password" class="label-sm">CONTRASEÑA</label>
                                            <div class="input-group">
                                                <input type="password" id="password" v-model="password" class="form-control" :class="[errors.password ? 'is-invalid' : '']" :readonly="loading">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="padding: 0px 10px; border-radius: 1px; cursor: pointer;" v-on:click="showPassword">
                                                        <i class="fas fa-eye" v-if="!password_show"></i>
                                                        <i class="fas fa-eye-slash" v-else></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <small class="form-text error-color" v-if="errors.password">@{{ errors.password[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="perfil" class="label-sm">PERFIL</label>
                                            <select id="perfil" v-model="perfil" class="form-control" :class="[errors.perfil ? 'is-invalid' : '']" :readonly="loading">
                                                <option v-for="perfil in listaRoles" :value="perfil.name">@{{perfil.name}}</option>
                                            </select>
                                            <small class="form-text error-color" v-if="errors.perfil">@{{ errors.perfil[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="activo" class="label-sm">ACTIVO</label>
                                            <input type="text" value="SI" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-primary btn-block event-btn" v-on:click="Store" :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Guardando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i class="feather icon-times"></i> Guardar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- NUEVO --}}

                            {{-- EDITAR --}}
                            <div class="modal-content" v-if="methods == 'edit'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">EDITAR <span style="color: #929292; font-size: 17px; font-weight: 400;">(USUARIO)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="dni" class="label-sm">DNI</label>
                                            <input type="text" id="dni" v-model="dni" class="form-control" :class="[errors.dni ? 'is-invalid' : '']" :readonly="loading"
                                            maxlength="8" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                            <small class="form-text error-color" v-if="errors.dni">@{{ errors.dni[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="nombres" class="label-sm">NOMBRES</label>
                                            <input type="text" id="nombres" v-model="nombres" class="form-control" :class="[errors.nombres ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.nombres">@{{ errors.nombres[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="paterno" class="label-sm">APELLIDO PATERNO</label>
                                            <input type="text" id="paterno" v-model="paterno" class="form-control" :class="[errors.paterno ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.paterno">@{{ errors.paterno[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="materno" class="label-sm">APELLIDO MATERNO</label>
                                            <input type="text" id="materno" v-model="materno" class="form-control" :class="[errors.materno ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.materno">@{{ errors.materno[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="telefono" class="label-sm">TELEFONO</label>
                                            <input type="text" id="telefono" v-model="telefono" class="form-control" :class="[errors.telefono ? 'is-invalid' : '']" :readonly="loading"
                                            maxlength="9" onkeypress="if (event.keyCode < 48 || event.keyCode > 57) { event.returnValue = false}">
                                            <small class="form-text error-color" v-if="errors.telefono">@{{ errors.telefono[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="email" class="label-sm">EMAIL</label>
                                            <input type="email" id="email" v-model="email" class="form-control" :class="[errors.email ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.email">@{{ errors.email[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="username" class="label-sm">NOMBRE USUARIO</label>
                                            <input type="text" id="username" v-model="username" class="form-control" :class="[errors.username ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.username">@{{ errors.username[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="password" class="label-sm">CONTRASEÑA</label>
                                            <div class="input-group">
                                                <input type="password" id="password" v-model="password" class="form-control" :class="[errors.password ? 'is-invalid' : '']" :readonly="loading">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="padding: 0px 10px; border-radius: 1px; cursor: pointer;" v-on:click="showPassword">
                                                        <i class="fas fa-eye" v-if="!password_show"></i>
                                                        <i class="fas fa-eye-slash" v-else></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <small class="form-text error-color" v-if="errors.password">@{{ errors.password[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="perfil" class="label-sm">PERFIL</label>
                                            <select id="perfil" v-model="perfil" class="form-control" :class="[errors.perfil ? 'is-invalid' : '']" :readonly="loading" v-on:change="PerfilUpdate">
                                                <option v-for="perfil in listaRoles" :value="perfil.name">@{{perfil.name}}</option>
                                            </select>
                                            <small class="form-text error-color" v-if="errors.perfil">@{{ errors.perfil[0] }}</small>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="activo" class="label-sm">ACTIVO</label>
                                            <select v-model="activo" id="activo" class="form-control">
                                                <option value="SI">SI</option>
                                                <option value="NO">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer" style="padding: 10px 15px;">
                                    <button class="btn btn-info btn-block event-btn" v-on:click="Update" :disabled="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" v-if="loading"></span>
                                        <span class="load-text" v-if="loading">Actualizando...</span>
                                        <span class="btn-text" v-if="!loading" style=""><i class="feather icon-times"></i> Actualizar</span>
                                    </button>
                                </div>
                            </div>
                            {{-- EDITAR --}}

                            {{-- ELIMINAR --}}
                            <div class="modal-content" v-if="methods == 'delete'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">ELIMINAR <span style="color: #929292; font-size: 17px; font-weight: 400;">(USUARIO)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <p class="text-center">
                                        Realmente desea eliminar al Usuario <strong>"@{{username}}"</strong>
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

                    <div class="row">
                        <div class="mb-3 mt-3 col-md-9">
                            <button type="button" class="btn btn-icon btn-primary mr-2" style="min-width: 88px;"
                            data-toggle="modal" data-target="#formularioModal" v-on:click="formularioModal('', null, 'create', null)">
                                <div style="font-size: 30px;"><i class="fas fa-plus"></i></div>
                                <div>Nuevo</div>
                            </button>

                            <button type="button" class="btn btn-icon btn-info mr-2" style="min-width: 88px;" v-if="active != 0"
                            data-toggle="modal" data-target="#formularioModal" v-on:click="formularioModal('', active, 'edit', seleccion)">
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-info disabled mr-2" style="min-width: 88px;" v-else>
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>

                            <button type="button" class="btn btn-icon btn-danger mr-2" style="min-width: 88px;" v-if="active != 0"
                            data-toggle="modal" data-target="#formularioModal" v-on:click="formularioModal('modal-sm', active, 'delete', seleccion.username)">
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-danger disabled mr-2" style="min-width: 88px;" v-else>
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>
                        </div>
                        <div class="mb-3 mt-3 col-md-3">
                            <div class="p-b-10">
                                <input type="text" class="form-control" id="seacrh" v-model="search" placeholder="Nombres del Usuario." v-on:keyup.enter="Buscar">
                            </div>
                            <button class="btn btn-secondary btn-block" v-on:click="Buscar">Buscar</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="cell-1 text-center">#</th>
                                    <th class="cell-2 text-center">Nombre de Usuario</th>
                                    <th class="cell-3">Datos Completos</th>
                                    <th class="cell-4 text-center">Perfil</th>
                                    <th class="cell-5 text-center">Fecha Actualizacion</th>
                                    <th class="cell-6 text-center">Activo</th>
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
                                        <tr v-for="(user, index) in listaRequest" :class="{ activado : active == user.id }" v-on:click="Fila(user.id, user)" style="cursor: pointer;">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td class="text-center">@{{user.username}}</td>
                                            <td>
                                                <div><i class="fas fa-user"></i> @{{user.nombres+' '+user.ape_paterno+' '+user.ape_materno}}</div>
                                                <div>
                                                    <i class="fas fa-id-card"></i> @{{user.dni}} &nbsp;| &nbsp;
                                                    <i class="fas fa-phone-square"></i> @{{user.telefono}} &nbsp; | &nbsp;
                                                    <i class="fas fa-envelope"></i> @{{user.email}}
                                                </div>

                                            </td>
                                            <td class="text-center" style="text-transform: uppercase;"><span v-if="user.roles">@{{user.roles[0].name}}</span></td>
                                            <td class="text-center">@{{Fecha(user.updated_at)}}</td>
                                            <td class="text-center">
                                                <i class="fas fa-check-circle font-green" style="font-size: 15px;" v-if="user.activo == 'SI'"></i>
                                                <i class="fas fa-times-circle font-red" style="font-size: 15px;" v-else></i>
                                            </td>
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
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('js/views/administrador/usuarios.js')}}"></script>
@endsection
