@extends('layouts.landing')
@section('css')
    <style>
        .col1 { width: 10%; }
        .col2 { width: 10%; }
        .col3 { width: 20%; }
        .col4 { width: 45%; }
        .col5 { width: 15%; }

        .table-sm td {
            vertical-align: middle !important;
        }

        .E1, .E2, .E3, .E4, .E5, .E6 {
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }

        .E1 { background-color: red; }
        .E2 { background-color: #00c1c1; }
        .E3 { background-color: purple; }
        .E4 { background-color: orange; }
        .E5 { background-color: green; }
        .E6 { background-color: #0077ff; }

        pre {
            font-family: 'Raleway', sans-serif;
            padding: 5px 10px;
            margin-bottom: 0;
        }
    </style>
@endsection
@section('content')
    <main id="main">

        <!-- ======= Breadcrumbs Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                <h2>Buscar Pedido</h2>
                    <ol>
                        <li><a href="{{url('/')}}"><i class="bx bx-home"></i> Inicio</a></li>
                        <li>Consultar</li>
                    </ol>
                </div>

            </div>
        </section><!-- Breadcrumbs Section -->

        <!-- ======= Portfolio Details Section ======= -->
        <section class="portfolio-details" id="pedido">
            <div class="container">
                <div class="d-flex justify-content-center">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" v-model="search" class="form-control" placeholder="Código del pedido" v-on:keyup.enter="Buscar"
                            maxlength="11" :class="[errors.search ? 'is-invalid' : '']" style="text-transform: uppercase;">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" v-on:click="Buscar"><i class="bx bx-search"></i> Buscar</button>
                            </div>
                        </div>
                        <div style="font-size: 11px; color: red;" v-if="errors.search">@{{errors.search[0]}}</div>
                    </div>
                </div>

                <div class="portfolio-description">
                    <div v-if="loading" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span style="font-size: 25px; padding-left: 10px;">Buscando Pedido ....</span>
                    </div>
                    
                    <div v-if="state">
                        <table class="table table-sm table-bordered table-condensed" v-if="state == 'success'">
                            <thead>
                                <tr style="background-color: #428bca; color: #fff; font-size: 12px;">
                                    <th class="text-center col1">CÓDIGO</th>
                                    <th class="text-center col2">ESTADO</th>
                                    <th class="text-center col3">CLIENTE</th>
                                    <th class="text-center col4">DETALLES DEL PEDIDO</th>
                                    <th class="text-center col5">CONTACTAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="font-size: 12px;">
                                    <td class="text-center">@{{pedido.codigo_barras+zeroFill(pedido.id, 4)}}</td>
                                    <td class="text-center"><div :class="pedido.estado">@{{Estado(pedido.estado_entrega)}}</div></td>
                                    <td>@{{pedido.get_cliente.nombres}}</td>
                                    <td>
                                        <ul>
                                            <li v-for="detalle in pedido.get_detalles">@{{detalle.descripcion}}</li>
                                        </ul>
                                    </td>
                                    <td class="text-center"><a :href="'https://wa.me/51'+whatsapp.descripcion+'?text=Hola%20quisiera%20saber%20sobre%20el%20estado%20de%20mi%20pedido:%20*'+pedido.codigo_barras+zeroFill(pedido.id, 4)+'*'" target="_blank" class="btn btn-success"> <i class="bx bxl-whatsapp"></i> Soporte</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else class="text-center">
                            No se encontro el pedido <strong style="text-transform: uppercase;">[ @{{search}} ]</strong>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End Portfolio Details Section -->

    </main><!-- End #main -->
@endsection
@section('js')
    <script>
        var my_whatsapp = {!! json_encode($whatsapp) !!};
    </script>
    <script src="{{asset('js/consultar/pedido.js')}}"></script>
@endsection