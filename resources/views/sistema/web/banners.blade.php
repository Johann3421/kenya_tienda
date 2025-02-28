@extends('layouts.template')
@section('app-name')
    <title>KENYA - Web Banners</title>
@endsection
@section('css')
    <style>
        .activado {
            background-color: #e8f2fc;
            color: #1c82e1;
        }
        .cell-1 { width: 5%; }
        .cell-2 { width: 35%; }
        .cell-3 { width: 35%; }
        .cell-4 { width: 15%; }
        .cell-5 { width: 10%; }

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
    </style>
@endsection
@section('content')
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-tv"></i> Página Web</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="index.html"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Web Banners</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-banners">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>LISTA DE BANNERS DE LA PÁGINA WEB</h5>
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
                                    <h5 class="mb-0">NUEVO <span style="color: #929292; font-size: 17px; font-weight: 400;">(BANNER)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-row">
                                        <div class="form-group col-sm-8">
                                            <label for="titulo">Título</label>
                                            <input type="text" id="titulo" v-model="titulo" class="form-control" :class="[errors.titulo ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.titulo">@{{ errors.titulo[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="titulo_color">Color del Título</label>
                                            <input type="text" id="titulo_color" v-model="titulo_color" class="form-control" :class="[errors.titulo_color ? 'is-invalid' : '']" :readonly="loading" placeholder="#cdcdcd">
                                            <small class="form-text error-color" v-if="errors.titulo_color">@{{ errors.titulo_color[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="descripcion">Descripción</label>
                                            <input type="text" id="descripcion" v-model="descripcion" class="form-control" :class="[errors.descripcion ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.descripcion">@{{ errors.descripcion[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="contenido">Contenido</label>
                                            <textarea id="contenido" v-model="contenido" class="form-control" :class="[errors.contenido ? 'is-invalid' : '']" :readonly="loading" style="width: 100%;" cols="3"></textarea>
                                            <small class="form-text error-color" v-if="errors.contenido">@{{ errors.contenido[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="link_nombre">Nombre del Boton</label>
                                            <input type="text" id="link_nombre" v-model="link_nombre" class="form-control" :class="[errors.link_nombre ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.link_nombre">@{{ errors.link_nombre[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-8">
                                            <label for="link">Link</label>
                                            <input type="text" id="link" v-model="link" class="form-control" :class="[errors.link ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.link">@{{ errors.link[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="imagen">Imagen <small>(resolucion 1920x1080)</small></label>
                                            <input type="file" id="imagen" v-on:change="Imagen($event)" class="form-control" :class="[errors.imagen ? 'is-invalid' : '']" :readonly="loading"  accept="image/*">
                                            <small class="form-text error-color" v-if="errors.imagen">@{{ errors.imagen[0] }} </small>
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
                                    <h5 class="mb-0">EDITAR <span style="color: #929292; font-size: 17px; font-weight: 400;">(BANNER)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-row">
                                        <div class="form-group col-sm-8">
                                            <label for="titulo">Título</label>
                                            <input type="text" id="titulo" v-model="titulo" class="form-control" :class="[errors.titulo ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.titulo">@{{ errors.titulo[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="titulo_color">Color del Título</label>
                                            <input type="text" id="titulo_color" v-model="titulo_color" class="form-control" :class="[errors.titulo_color ? 'is-invalid' : '']" :readonly="loading" placeholder="#cdcdcd">
                                            <small class="form-text error-color" v-if="errors.titulo_color">@{{ errors.titulo_color[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="descripcion">Descripción</label>
                                            <input type="text" id="descripcion" v-model="descripcion" class="form-control" :class="[errors.descripcion ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.descripcion">@{{ errors.descripcion[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="contenido">Contenido</label>
                                            <textarea id="contenido" v-model="contenido" class="form-control" :class="[errors.contenido ? 'is-invalid' : '']" :readonly="loading" style="width: 100%;" cols="3"></textarea>
                                            <small class="form-text error-color" v-if="errors.contenido">@{{ errors.contenido[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="link_nombre">Nombre del Boton</label>
                                            <input type="text" id="link_nombre" v-model="link_nombre" class="form-control" :class="[errors.link_nombre ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.link_nombre">@{{ errors.link_nombre[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-8">
                                            <label for="link">Link</label>
                                            <input type="text" id="link" v-model="link" class="form-control" :class="[errors.link ? 'is-invalid' : '']" :readonly="loading">
                                            <small class="form-text error-color" v-if="errors.link">@{{ errors.link[0] }} </small>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="imagen">Imagen <small>(resolucion 1920x1080)</small></label>
                                            <input type="file" id="imagen" v-on:change="Imagen($event)" class="form-control" :class="[errors.imagen ? 'is-invalid' : '']" :readonly="loading"  accept="image/*">
                                            <small class="form-text error-color" v-if="errors.imagen">@{{ errors.imagen[0] }} </small>
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
                                    <h5 class="mb-0">ELIMINAR <span style="color: #929292; font-size: 17px; font-weight: 400;">(BANNER)</span></h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close" v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right" style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <p class="text-center">
                                        Realmente desea eliminar el Banner Seleccionado.
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
                            data-toggle="modal" data-target="#formularioModal" v-on:click="formularioModal('modal-sm', active, 'delete', seleccion.name)">
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
                                <input type="text" class="form-control" id="seacrh" v-model="search" placeholder="Nombre del permiso." v-on:keyup.enter="Buscar">
                            </div>
                            <button class="btn btn-secondary btn-block" v-on:click="Buscar">Buscar</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="cell-1 text-center">#</th>
                                    <th class="cell-2">Título</th>
                                    <th class="cell-3">Descripción</th>
                                    <th class="cell-4">Imagen</th>
                                    <th class="cell-5 text-center">Activo</th>
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
                                        <tr v-for="(banner, index) in listaRequest" :class="{ activado : active == banner.id }" v-on:click="Fila(banner.id, banner)" style="cursor: pointer;">
                                            <td class="text-center">@{{(index + pagination.index + 1)}}</td>
                                            <td>@{{banner.titulo}}</td>
                                            <td>@{{banner.descripcion}}</td>
                                            <td>@{{banner.imagen}}</td>
                                            <td class="text-center">
                                                <i class="fas fa-check-circle font-green" style="font-size: 15px;" v-if="banner.activo == 'SI'"></i>
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
    <script src="{{asset('js/views/web/banners.js')}}"></script>
@endsection
