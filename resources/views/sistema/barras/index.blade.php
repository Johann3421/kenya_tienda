@extends('layouts.template')
@section('app-name')
    <title>Grupo kenya - Barras</title>
@endsection
@section('css')
    <style>
        .oculto {
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="page-header breadcumb-sticky dash-sale" style="position: fixed;right: 25px;width: 100%;z-index: 1001;border-radius: 0;background-color: #f6f6f6;border-bottom: 4px solid #CCC;">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10" style="color: #6b6b6b;"><i class="fas fa-barcode"></i> Código de Barras</h5>
                    </div>
                    <ul class="breadcrumb" style="font-size: 15px;">
                        <li class="breadcrumb-item" style="margin-top: -3px;"><a href="index.html"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Código de Barras</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="top: 40px; position: inherit;" id="form-barras">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Crear mi Código de Barras</h5>
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
                    <div class="row">
                        <div class="col-sm-5 offset-2">
                            <div class="form-group mt-3">
                                <label for="contenido">Contenido</label>
                                <input type="text" v-model="contenido" id="contenido" class="form-control" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-sm-5" style="margin-top: 16px;">
                            <button type="button" class="btn btn-icon btn-primary mr-2" style="min-width: 88px;" @click="codigoBarra">
                                <div style="font-size: 19px;"><i class="fas fa-barcode"></i></div>
                                <div>Generar</div>
                            </button>
                        </div>
                    </div>
                    @php
                        $barras = App\Models\Configuracion::where('nombre', 'logo_codigo_barras')->first();
                    @endphp

                    <div class="row oculto" id="my_barras">
                        <div class="col-sm-12" style="margin-left: 16%;">
                            <div id="imprimir" class="mb-3" style="display: flex;">
                                <div style="padding-top: 20px;">
                                    @if ($barras->archivo)
                                        <img src="{{asset('storage/'.$barras->archivo_ruta.'/'.$barras->archivo)}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 9px; top: 4px;">
                                    @else
                                        <img src="{{asset('theme/images/kenya.png')}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 9px; top: 4px;">
                                    @endif
                                    <div id="barcode"></div>
                                </div>
                                <div style="padding-top: 20px;" class="oculto">
                                    @if ($barras->archivo)
                                        <img src="{{asset('storage/'.$barras->archivo_ruta.'/'.$barras->archivo)}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 9px; top: 4px;">
                                    @else
                                        <img src="{{asset('theme/images/kenya.png')}}" style="max-width: 136px; background-color: #fff; position: absolute; left: 9px; top: 4px;">
                                    @endif
                                    <div id="barcode1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-sm btn-primary" @click="imprimirCodigoBarra">
                                <i class="fa fa-print"> Imprimir</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/barcode.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.printarea.js')}}"></script>
    <script src="{{asset('js/views/barras.js')}}"></script>
@endsection
