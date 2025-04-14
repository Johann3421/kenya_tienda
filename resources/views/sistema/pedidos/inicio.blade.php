@extends('layouts.template')

@section('app-name')
    <title>KENYA - Pedidos</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/views/soporte.css')}}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('content')
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-tools"></i> Pedidos</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="#"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Pedidos</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-pedido">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>REPORTE DE PEDIDOS</h5>
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
                                <li class="close-card"><a href="#" class="windows-button" title="Cerrar"><i class="feather icon-x"></i> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="mb-3 mt-3 col-md-9">
                            <button type="button" class="btn btn-icon btn-primary mr-2" style="min-width: 88px;" @click="mdlNuevoPedido">
                                <div style="font-size: 30px;"><i class="fas fa-plus"></i></div>
                                <div>Nuevo</div>
                            </button>

                            <button type="button" class="btn btn-icon btn-info btn-pedido disabled mr-2" style="min-width: 88px;">
                                <div style="font-size: 30px;"><i class="fas fa-edit"></i></div>
                                <div>Editar</div>
                            </button>

                            <button type="button" class="btn btn-icon btn-danger btn-pedido disabled mr-2" style="min-width: 88px;">
                                <div style="font-size: 30px;"><i class="fas fa-trash-alt"></i></div>
                                <div>Eliminar</div>
                            </button>

                            <button type="button" class="btn btn-icon btn-warning btn-pedido disabled mr-2" style="min-width: 88px;"
                                disabled @click="mdlMostrarRecibo">
                                <div style="font-size: 30px;"><i class="fas fa-print"></i></div>
                                <div>Recibo</div>
                            </button>

                        </div>
                        <div class="mb-3 mt-3 col-md-3">
                            <div class="p-b-10">
                                <input type="text" class="form-control" placeholder="Buscar Pedido" v-model="frase"
                                    v-on:keyup.enter="buscarPedidos">
                            </div>
                            <button class="btn btn-secondary btn-block" v-on:click="buscarPedidos">Buscar</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center cell-1">#</th>
                                    <th class="cell-2 text-center">Recibo</th>
                                    <th class="cell-3 text-center">Doc. Cliente</th>
                                    <th class="cell-4">Cliente</th>
                                    <th class="cell-5 text-center">Total</th>
                                    <th class="cell-6 text-center">Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="list-loading">
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div>
                                            <div class="spinner-grow" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <span style="font-size: 30px; padding: 5px;">Cargando lista espere ...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody id="list-pedidos">
                                <tr v-if="!pedidos.data || pedidos.data.length == 0">
                                    <td colspan="6" class="text-center" style="font-size: 20px;">No existe ningun registro</td>
                                </tr>
                                <tr v-else v-for="(pedido, index) in pedidos.data" style="cursor: pointer;"
                                    :class="{ activado: pedido_activado == pedido.id }"
                                    v-on:click="seleccionarPedido(pedido.id)"
                                >
                                    <td class="text-center">@{{ (index + 1 + ((pedidos.current_page - 1) * pedidos.per_page)) }}</td>
                                    <td class="text-center">@{{ pedido.serie_numeracion }}</td>
                                    <td>@{{ pedido.documento_cliente }}</td>
                                    <td>@{{ pedido.cliente }}</td>
                                    <td class="text-center">@{{ parseFloat(pedido.total).toFixed(2) }}</td>
                                    <td class="text-center">@{{ pedido.fecha }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="list-pedidos" class="row">
                        <div class="col-sm-4 text-left">
                            <div style="margin: 7px; font-size: 15px;">
                                @{{pedidos.current_page+' de '+pedidos.last_page+' Páginas '}}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <nav class="text-center" aria-label="...">
                                <ul class="pagination" style="justify-content: center;">

                                    <a href="#" v-if="pedidos.current_page > 1" class="pag-inicio-fin" title="Página inicio"
                                        v-on:click.prevent="changePage(1)">
                                        <i class="fas fa-step-backward"></i>
                                    </a>

                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página inicio">
                                        <i class="fas fa-step-backward"></i>
                                    </a>

                                    <li class="page-item" v-if="pedidos.current_page > 1">
                                        <a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;"
                                            title="Anterior" v-on:click.prevent="changePage(pedidos.current_page - 1)">
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

                                    <li class="page-item" v-if="pedidos.current_page < pedidos.last_page">
                                        <a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;"
                                            title="Siguiente" v-on:click.prevent="changePage(pedidos.current_page + 1)">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>

                                    <li class="page-item disabled" title="Siguiente" v-else style="cursor: no-drop;">
                                        <a href="#" class="page-link" style="padding: 6px 10px 4px 10px; font-size: 18px;">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    </li>

                                    <a href="#" v-if="pedidos.current_page < pedidos.last_page" class="pag-inicio-fin"
                                        title="Página final" v-on:click.prevent="changePage(pedidos.last_page)">
                                        <i class="fas fa-step-forward"></i>
                                    </a>

                                    <a href="#" v-else class="pag-inicio-fin desabilitado" title="Página final">
                                        <i class="fas fa-step-forward"></i>
                                    </a>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-sm-4 text-right">
                            <div style="margin: 7px; font-size: 15px;" v-if="pedidos.to">
                                @{{pedidos.to+' de '+pedidos.total+' Registros'}}
                            </div>
                            <div style="margin: 7px; font-size: 15px;" v-else>0 de 0 Registros</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('sistema.pedidos.modals.mdlMostrarRecibo')
@endsection

@section('js')
    <script src="{{asset('js/views/pedidos/inicio.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@endsection
