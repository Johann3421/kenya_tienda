@extends('layouts.template')
@section('app-name')
    <title>KENYA - Configuracion</title>
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
            width: 25%;
        }

        .cell-3 {
            width: 35%;
        }

        .cell-4 {
            width: 20%;
        }

        .cell-5 {
            width: 15%;
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

        .font-red {
            color: red;
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
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-address-card"></i> Configuración</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="index.html"><i class="fas fa-home"
                                    style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Configuración</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-permisos">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>LISTA DE CONFIGURACIONES</h5>
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
                                    <h5 class="mb-0">NUEVA <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(CONFIGURACIÓN)</span>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-group col-sm-12">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" id="nombre" v-model="nombre" class="form-control"
                                            :class="[errors.nombre ? 'is-invalid' : '']" :readonly="loading">
                                        <small class="form-text error-color" v-if="errors.nombre">@{{ errors.nombre[0] }}
                                        </small>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="descripcion">Descripcion</label>
                                        <input type="text" id="descripcion" v-model="descripcion" class="form-control"
                                            :class="[errors.descripcion ? 'is-invalid' : '']" :readonly="loading">
                                        <small class="form-text error-color"
                                            v-if="errors.descripcion">@{{ errors.descripcion[0] }} </small>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="file">Imagen</label>
                                        <input type="file" id="file" class="form-control"
                                            :class="[errors.file ? 'is-invalid' : '']" :readonly="loading"
                                            v-on:change="File">
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
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(CONFIGURACIÓN)</span>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <div class="form-group col-sm-12">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" id="nombre" v-model="nombre" class="form-control"
                                            :class="[errors.nombre ? 'is-invalid' : '']" readonly>
                                        <small class="form-text error-color" v-if="errors.nombre">@{{ errors.nombre[0] }}
                                        </small>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="descripcion">Descripcion</label>
                                        <input type="text" id="descripcion" v-model="descripcion"
                                            class="form-control" :class="[errors.descripcion ? 'is-invalid' : '']"
                                            :readonly="loading">
                                        <small class="form-text error-color"
                                            v-if="errors.descripcion">@{{ errors.descripcion[0] }} </small>
                                    </div>
                                    <div class="form-group col-sm-12" v-if="archivo_anterior">
                                        <label for="file">Imagen</label>
                                        <div class="input-group">
                                            <input type="text" :value="archivo_anterior" class="form-control"
                                                readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" title="Eliminar Archivo"
                                                    v-on:click="deleteFile"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-12" v-else>
                                        <label for="file">Imagen</label>
                                        <input type="file" id="file" class="form-control"
                                            :class="[errors.file ? 'is-invalid' : '']" :readonly="loading"
                                            v-on:change="File">
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

                            {{-- ELIMINAR --}}
                            <div class="modal-content" v-if="methods == 'delete'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">ELIMINAR <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(CONFIGURACIÓN)</span>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <p class="text-center">
                                        Realmente desea eliminar el Permiso <strong>"@{{ nombre }}"</strong>
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

                            {{-- IMAGE --}}
                            <div class="modal-content" v-if="methods == 'image'">
                                <div class="modal-header" style="padding: 10px 15px">
                                    <h5 class="mb-0">ARCHIVO <span
                                            style="color: #929292; font-size: 17px; font-weight: 400;">(CONFIGURACIÓN)</span>
                                    </h5>
                                    <button type="button" title="Cerrar" data-dismiss="modal" aria-label="Close"
                                        v-on:click="closeModal(methods)" class="btn btn-danger btn-xs float-right"
                                        style="padding: 0px 7px;">X</button>
                                </div>
                                <div class="modal-body" style="padding: 15px 15px;">
                                    <img :src="archivo" alt="Archivo Configuracion" style="width: 100%;">
                                </div>
                            </div>
                            {{-- IMAGE --}}
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

                            <button type="button" class="btn btn-icon btn-danger mr-2" style="min-width: 88px;"
                                v-if="active != 0" data-toggle="modal" data-target="#formularioModal"
                                v-on:click="formularioModal('modal-sm', active, 'delete', seleccion.nombre)">
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-danger disabled mr-2" style="min-width: 88px;"
                                v-else>
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>
                            <button type="button" class="btn btn-icon btn-primary mr-2" style="min-width: 88px;"
                                data-toggle="modal" data-target="#bannerModal">
                                <div style="font-size: 30px;"><i class="fas fa-plus-circle"></i></div>
                                <div>Nuevo Banner</div>
                            </button>
                        </div>
                        <!-- Modal para Crear/Editar Banner -->
                        <div class="modal fade" id="bannerModal" tabindex="-1" role="dialog" data-backdrop="static">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white py-2">
                                        <h5 class="modal-title" id="modalTitle">NUEVO BANNER</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <form id="bannerForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="_method" id="formMethod" value="POST">
                                            <input type="hidden" name="banner_id" id="banner_id" value="">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- Título -->
                                                    <div class="form-group">
                                                        <label>Título (Opcional)</label>
                                                        <input type="text" name="titulo" id="titulo"
                                                            class="form-control" placeholder="Título del banner">
                                                        <small class="text-danger" id="titulo_error"></small>
                                                    </div>

                                                    <!-- URL Destino -->
                                                    <div class="form-group">
                                                        <label>URL Destino <span class="text-danger">*</span></label>
                                                        <input type="url" name="url_destino" id="url_destino"
                                                            class="form-control" placeholder="https://ejemplo.com"
                                                            required>
                                                        <small class="text-danger" id="url_destino_error"></small>
                                                    </div>

                                                    <!-- Orden -->
                                                    <div class="form-group">
                                                        <label>Orden</label>
                                                        <input type="number" name="orden" id="orden"
                                                            class="form-control" min="0" value="0">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <!-- Imagen -->
                                                    <div class="form-group">
                                                        <label>Imagen <span class="text-danger">*</span></label>
                                                        <div id="imagePreviewContainer" class="mb-3 text-center">
                                                            <img id="imagePreview" src="" class="img-thumbnail"
                                                                style="max-height: 200px; width: auto; display: none;">
                                                        </div>
                                                        <div class="custom-file">
                                                            <input type="file" name="imagen" id="imagen"
                                                                class="custom-file-input" accept="image/*">
                                                            <label class="custom-file-label" for="imagen"
                                                                id="imagenLabel">Seleccionar imagen</label>
                                                        </div>
                                                        <small class="text-muted">Formatos: JPEG, PNG, JPG, GIF (Máx.
                                                            4MB)</small>
                                                        <small class="text-danger d-block" id="imagen_error"></small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                                    <span id="submitBtnText">Guardar</span>
                                                    <span id="submitBtnSpinner"
                                                        class="spinner-border spinner-border-sm d-none" role="status"
                                                        aria-hidden="true"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 mt-3 col-md-3">
                            <div class="p-b-10">
                                <input type="text" class="form-control" id="seacrh" v-model="search"
                                    v-on:keyup.enter="Buscar">
                            </div>
                            <button class="btn btn-secondary btn-block" v-on:click="Buscar">Buscar</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#configTab">Configuración</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#bannersTab">Banners</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body tab-content">
                            <!-- Pestaña de Configuración -->
                            <div class="tab-pane fade show active" id="configTab">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th class="cell-1 text-center">#</th>
                                                <th class="cell-2">Nombre</th>
                                                <th class="cell-3">Descripción</th>
                                                <th class="cell-4 text-center">Archivo</th>
                                                <th class="cell-5 text-center">Activo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Tu tabla existente de configuración -->
                                            <tr id="list-loading">
                                                <td colspan="7" class="text-center">
                                                    <div>
                                                        <div class="spinner-grow" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <span style="font-size: 30px; padding: 5px;">Cargando lista espere
                                                            ...</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <template v-if="listTable">
                                                <template v-if="listaRequest.length != 0">
                                                    <tr v-for="(config, index) in listaRequest"
                                                        :class="{ activado: active == config.id }"
                                                        v-on:click="Fila(config.id, config)" style="cursor: pointer;">
                                                        <td class="text-center">@{{ (index + pagination.index + 1) }}</td>
                                                        <td>@{{ config.nombre }}</td>
                                                        <td>@{{ config.descripcion }}</td>
                                                        <td class="text-center">
                                                            <a href="#" v-if="config.archivo" data-toggle="modal"
                                                                data-target="#formularioModal"
                                                                v-on:click="formularioModal('', config.id, 'image', config)"><i
                                                                    class="fa fa-image"></i> @{{ config.archivo }}</a>
                                                        </td>
                                                        <td class="text-center">
                                                            <i class="fas fa-check-circle font-green"
                                                                style="font-size: 15px;" v-if="config.activo == 'SI'"></i>
                                                            <i class="fas fa-times-circle font-red"
                                                                style="font-size: 15px;" v-else></i>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <template v-else>
                                                    <tr>
                                                        <td colspan="7" class="text-center" style="font-size: 20px;">
                                                            No existe ningun registro</td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pestaña de Banners -->
                            <div class="tab-pane fade" id="bannersTab">

                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Título</th>
                                                <th class="text-center">Imagen</th>
                                                <th>URL Destino</th>
                                                <th class="text-center">Posición</th>
                                                <th class="text-center">Orden</th>
                                                <th class="text-center">Estado</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (\App\Models\BannerMedio::orderBy('orden')->get() as $banner)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $banner->titulo ?: 'Sin título' }}</td>
                                                    <td class="text-center">
                                                        @if ($banner->imagen_path)
                                                            @php
                                                                // Obtener solo el nombre del archivo
                                                                $imageFile = basename($banner->imagen_path);
                                                                // Construir la ruta correcta (banners/nombrearchivo)
                                                                $fullPath = 'banners/' . $imageFile;
                                                            @endphp

                                                            @if (file_exists(public_path($fullPath)))
                                                                <a href="#" data-toggle="modal"
                                                                    data-target="#imageModal"
                                                                    onclick="document.getElementById('bannerImage').src='{{ asset($fullPath) }}'">
                                                                    <img src="{{ asset($fullPath) }}"
                                                                        style="max-height: 50px;" alt="Banner">
                                                                </a>
                                                            @else
                                                                <span class="text-danger">
                                                                    Archivo no encontrado<br>
                                                                    <small>Buscado en: {{ public_path($fullPath) }}</small>
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">Sin imagen</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ $banner->url_destino }}"
                                                            target="_blank">{{ $banner->url_destino }}</a>
                                                    </td>
                                                    <td class="text-center">{{ $banner->posicion }}</td>
                                                    <td class="text-center">{{ $banner->orden }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-{{ $banner->activo ? 'success' : 'danger' }}">
                                                            {{ $banner->activo ? 'Activo' : 'Inactivo' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button
                                                            onclick="loadBannerData({{ json_encode($banner->toArray()) }})"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('admin.banners.destroy', $banner->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('¿Eliminar banner?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página inicio"><i
                                            class="fas fa-step-backward"></i></a>

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
                </div>
            </div>
        </div>
    </div>
    <!-- Script para el modal -->
    <script>
        $(document).ready(function() {
            // Vista previa de la imagen al seleccionar
            $('#imagen').change(function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        $('#imagePreview').attr('src', event.target.result).show();
                        $('#imagenLabel').text(file.name);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Manejo del formulario
            $('#bannerForm').submit(function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const isEdit = $('#banner_id').val() !== '';

    // Configuración de URL y método
    const url = isEdit ? `/admin/banners/${$('#banner_id').val()}` : '/admin/banners';

    // Agrega _method para Laravel si es edición
    if (isEdit) {
        formData.append('_method', 'PUT');
    }

    $.ajax({
        url: url,
        type: 'POST', // Siempre envía como POST
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#bannerModal').modal('hide');
            toastr.success(response.message);
            location.reload();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    $(`#${key}_error`).text(value[0]);
                });
            } else {
                toastr.error('Error: ' + (xhr.responseJSON?.message || 'Error al procesar la solicitud'));
            }
        },
        complete: function() {
            $('#submitBtn').prop('disabled', false);
            $('#submitBtnSpinner').addClass('d-none');
            $('#submitBtnText').text(isEdit ? 'Actualizar' : 'Guardar');
        }
    });
});

            // Resetear formulario cuando se cierra el modal
            $('#bannerModal').on('hidden.bs.modal', function() {
                $('#bannerForm')[0].reset();
                $('#imagePreview').attr('src', '').hide();
                $('#imagenLabel').text('Seleccionar imagen');
                $('.text-danger').text('');
                $('#formMethod').val('POST');
                $('#modalTitle').text('NUEVO BANNER');
                $('#submitBtnText').text('Guardar');
            });
        });

        // Función para cargar datos en el modal de edición
        function loadBannerData(banner) {
            $('#modalTitle').text('EDITAR BANNER');
            $('#banner_id').val(banner.id);
            $('#titulo').val(banner.titulo || '');
            $('#url_destino').val(banner.url_destino);
            $('#orden').val(banner.orden);
            $('#formMethod').val('PUT');

            // Mostrar imagen actual si existe
            if (banner.imagen_path) {
                const imageUrl = "{{ asset('') }}" + banner.imagen_path;
                $('#imagePreview').attr('src', imageUrl).show();
                $('#imagenLabel').text('Imagen actual - ' + banner.imagen_path.split('/').pop());
            }

            $('#bannerModal').modal('show');
        }
    </script>
@endsection

@section('js')
    <script src="{{ asset('js/views/administrador/configuracion.js') }}"></script>
@endsection
