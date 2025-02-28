@extends('layouts.template')

@section('app-name')
    <title>KENYA - Productos</title>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/busy-load@0.1.2/dist/app.min.css">
    <link rel="stylesheet" href="{{asset('css/views/soporte.css')}}">
@endsection

@section('content')
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-tools"></i> Productos</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="#"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Productos</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="app-vasco">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>REPORTE DE PRODUCTOS</h5>
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
                <div class="card-body">

                    <div class="row">

                        <div class="mb-3 mt-3 col-md-9">

                            <button type="button" class="btn btn-icon btn-primary mr-2" style="min-width: 88px;"
                                @click="mdlNuevoProducto">
                                <div style="font-size: 30px;"><i class="fas fa-plus"></i></div>
                                <div>Nuevo</div>
                            </button>

                            <button type="button" class="btn btn-icon disabled btn-producto btn-info mr-2" style="min-width: 88px;"
                                disabled @click="verProducto">
                                <div style="font-size: 30px;"><i class="fas fa-eye"></i></div>
                                <div>Ver</div>
                            </button>

                            <button type="button" class="btn btn-icon disabled btn-producto btn-warning mr-2" style="min-width: 88px;"
                                disabled @click="mdlEditarProducto">
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>

                            <button type="button" class="btn btn-icon disabled btn-producto btn-danger mr-2" style="min-width: 88px;"
                                disabled @click="mdlEliminarProducto">
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>

                        </div>
                        <div class="mb-3 mt-3 col-md-3">
                            <div class="p-b-10">
                                <input type="text" class="form-control" placeholder="Buscar Producto"
                                    v-on:keyup.enter="buscarProductos">
                            </div>
                            <button class="btn btn-secondary btn-block" v-on:click="todos">Buscar</button>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="cell-1 text-center">#</th>
                                    <th class="cell-2 text-center">Código</th>
                                    <th class="cell-3 text-center">Nombre</th>
                                    <th class="cell-4 text-center">Stock Inicial</th>
                                    <th class="cell-5 text-center">Precio</th>
                                    <th class="cell-6 text-center">Linea</th>
                                    <th class="cell-7 text-center">Categoría</th>
                                    <th class="cell-8 text-center">Marca</th>
                                    <th class="cell-9 text-center">Modelo</th>
                                </tr>
                            </thead>
                            <tbody id="list-loading">
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div>
                                            <div class="spinner-grow" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <span style="font-size: 30px; padding: 5px;">Cargando lista espere ...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody id="list-productos">
                                <tr v-if="!productos.data || productos.data.length == 0">
                                    <td colspan="9" class="text-center" style="font-size: 20px;">No existe ningun registro</td>
                                </tr>
                                <tr v-else v-for="(producto, index) in productos.data" style="cursor: pointer;"
                                    :class="{ activado: producto_activado == producto.id }"
                                    v-on:click="seleccionarProducto(producto.id)"
                                >
                                    <td class="text-center">@{{ (index + 1 + ((productos.current_page - 1) * productos.per_page)) }}</td>
                                    <td class="text-center">@{{ producto.codigo_interno }}</td>
                                    <td>@{{ producto.nombre }}</td>
                                    <td class="text-center">@{{ producto.stock_inicial }}</td>
                                    <td class="text-center">@{{ parseFloat(producto.precio_unitario).toFixed(2) }}</td>
                                    <td class="text-center">@{{ producto.linea_producto }}</td>
                                    <td class="text-center">@{{ producto.categoria }}</td>
                                    <td class="text-center">@{{ producto.marca }}</td>
                                    <td class="text-center">@{{ producto.modelo }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="list-productos" class="row">
                        <div class="col-sm-4 text-left">
                            <div style="margin: 7px; font-size: 15px;">
                                @{{productos.current_page+' de '+productos.last_page+' Páginas '}}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <nav class="text-center" aria-label="...">
                                <ul class="pagination" style="justify-content: center;">

                                    <a href="#" v-if="productos.current_page > 1" class="pag-inicio-fin" title="Página inicio"
                                        v-on:click.prevent="changePage(1)">
                                        <i class="fas fa-step-backward"></i>
                                    </a>

                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página inicio">
                                        <i class="fas fa-step-backward"></i>
                                    </a>

                                    <li class="page-item" v-if="productos.current_page > 1">
                                        <a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;"
                                            title="Anterior" v-on:click.prevent="changePage(productos.current_page - 1)">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                    </li>

                                    <li class="page-item disabled" title="Anterior" v-else style="cursor: no-drop;">
                                        <a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                    </li>

                                    <li class="page-item" v-for="page in paginas" :class="[ page == activo ? 'active' : '' ]">
                                        <a href="#" class="page-link" v-on:click.prevent="changePage(page)">@{{ page }}</a>
                                    </li>

                                    <li class="page-item" v-if="productos.current_page < productos.last_page">
                                        <a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;"
                                            title="Siguiente" v-on:click.prevent="changePage(productos.current_page + 1)">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>

                                    <li class="page-item disabled" title="Siguiente" v-else style="cursor: no-drop;">
                                        <a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>

                                    <a href="#" v-if="productos.current_page < productos.last_page" class="pag-inicio-fin"
                                        title="Página final" v-on:click.prevent="changePage(productos.last_page)">
                                        <i class="fas fa-step-forward"></i>
                                    </a>

                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página final">
                                        <i class="fas fa-step-forward"></i>
                                    </a>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div style="margin: 7px; font-size: 15px;" v-if="productos.to">
                                @{{productos.to+' de '+productos.total+' Registros'}}
                            </div>
                            <div style="margin: 7px; font-size: 15px;" v-else>0 de 0 Registros</div>
                        </div>
                    </div>
                </div>
            </div>
            @include('sistema.productos.modals.mdlNuevoProducto')
            @include('sistema.productos.modals.mdlVerProducto')
            @include('sistema.productos.modals.mdlEditarProducto')
            @include('sistema.productos.modals.mdlEliminarProducto')
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/busy-load@0.1.2/dist/app.min.js"></script>
    <script src="{{asset('/js/views/productos/inicio.js')}}"></script>
@endsection
