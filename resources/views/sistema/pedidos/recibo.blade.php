<!DOCTYPE html>
<head>
    <title>PEDIDO N° {{$pedido->codigo_barras.str_pad($pedido->id, 4, '0', STR_PAD_LEFT)}}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            font-size: 12px;
        }

        pre {
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            padding: 0;
            margin: 0;
            /*padding: 5px 10px;
            border: 1px solid #a7a7a7;
            border-radius: 5px;*/
        }
        table {
            font-size: 11px;
        }
        @page { margin: 90px 50px; } 
        #header { position: fixed; left: 0px; top: -70px; right: 0px; height: 50px; text-align: center; } 
        #footer { position: fixed; left: 0px; bottom: -20px; right: 0px; height: 50px;} 
        /* #footer .page:after { content: counter(page) }  */

        .text-center {
            text-align: center !important;
        }
        .text-left {
            text-align: left !important;
        }
        .text-right {
            text-align: right !important;
        }

        .float-right {
            float: right !important;
        }
        .float-left {
            float: left !important;
        }
        .text-justify {
            text-align: justify !important;   
        }
        /*.my-table {
            border-collapse: collapse;
        }
        .my-table > tbody > tr > td {
            border-bottom: 1px solid #a7a7a7;
            text-align: center;
            padding: 2px 5px;
        }
        .my-table > thead > tr > th {
            border-bottom: 2px solid #a7a7a7;
            border-right: 0px;
            text-align: center;
            padding: 2px 5px;
        }
        .my-table > tfoot > tr > td {
            border-top: 2px solid #a7a7a7;
            border-right: 0px;
            text-align: center;
            padding: 2px 5px;
        }*/
        .tabla-reporte {
            width: 100%;
        }
        .tabla-reporte td {
            border-bottom: 0.01em solid #000;
            padding: 3px 10px;
        }
        .tabla-reporte tr:last-child td {
            border-bottom: none;
        }
        .tabla1 {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
        }
        .tabla1 thead th, .tabla1 tbody td, .tabla1 tfoot td {
            padding: 4px 10px;
        }
        .tabla1 thead tr th, .tabla1 tbody tr td{
            border-bottom: 0.01em solid #000;
        }
        .tabla1 tbody tr td:first-child {
            border-left: 0.01em solid #000;
        }
        .tabla1 tbody tr td:last-child {
            border-right: 0.01em solid #000;
        }
    </style>
</head>
<body>
    @php
        $email = App\Models\Configuracion::where('nombre', 'contacto_email')->first();
        $telefono = App\Models\Configuracion::where('nombre', 'contacto_telefono')->first();
        $direccion = App\Models\Configuracion::where('nombre', 'contacto_direccion')->first();
        $titulo = App\Models\Configuracion::where('nombre', 'boleta_titulo')->first();
        $ruc = App\Models\Configuracion::where('nombre', 'ruc_empresa')->first();
    @endphp
    <div class="mi_plantilla">
        <div id="header">
            <table width="100%">
                <tr>
                    <td class="text-left" style="width: 65%;">
                        <img src="{{asset('theme/images/membrete.png')}}" alt="recibo-logo"  style="width: 95%;">
                    </td>
                    <td class="text-right" style="width: 35%;">
                        {{-- <div class="text-center" style="border: 1px solid #000; font-size: 13px; height: 50px; padding-top: 10px">
                            PEDIDO<br>
                            N° {{$pedido->codigo_barras}}
                        </div> --}}
                        <div class="text-center" style="border: 2px solid #000; border-radius: 7px; font-size: 14px; height: 90px; padding-top: 0px;">
                            <div style="padding: 3px 0;">RUC &nbsp; @if ($ruc) {{$ruc->descripcion}} @endif</div>
                            <h3 style="background-color: #218825; color: #fff; margin: 2px 0 0 0; padding: 5px 15px;">PEDIDO</h3>
                            <div>N° {{str_pad($pedido->id, 4, '0', STR_PAD_LEFT)}}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <div id="content" style="margin-top: 40px;">
            <div class="text-center" style="margin-bottom: 30px; font-weight: 700;">
                {{-- @if ($email && $telefono && $direccion)
                    {{$direccion->descripcion}}<br>
                    Central telefónica: {{$telefono->descripcion}}, Email: {{$email->descripcion}}<br>
                @endif

                @if ($titulo)
                    <h2>{{$titulo->descripcion}}</h2>
                @endif --}}
                <h2>SOPORTE TÉCNICO ESPECIALIZADO<br>DE EQUIPOS DE COMPUTO Y SUMINISTROS EN GENERAL</h2>
            </div>

            <div style="width: 50%; border: 1px solid #000; border-radius: 7px; margin-bottom: 10px;">
                <table class="tabla-reporte" cellspacing="0">
                    <tr>
                        <td width="15%" class="text-center">
                            <strong>FECHA PEDIDO</strong><br>
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $pedido->fecha_registro)->format('d/m/Y H:i') }}
                        </td>
                        <td width="15%" class="text-center">
                            <strong>FECHA ENTREGA</strong><br>
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $pedido->fecha_entrega)->format('d/m/Y') }}
                        </td>
                    </tr>
                </table>
            </div>

            <div style="border: 1px solid #000; border-radius: 7px;">
                <table class="tabla-reporte" cellspacing="0">
                    <tr>
                        <td width="15%"><strong>CLIENTE</strong></td>
                        <td width="45%">: {{$pedido->getCliente->nombres}}</td>
                        <td width="15%"><strong>DNI/RUC</strong></td>
                        <td width="25%">: {{$pedido->cliente_id}}</td>
                    </tr>
                    <tr>
                        <td><strong>DIRECCIÓN</strong></td>
                        <td>: {{$pedido->getCliente->direccion}}</td>
                        <td><strong>TELEFONO</strong></td>
                        <td>: {{$pedido->getCliente->celular}}</td>
                    </tr>
                </table>
            </div>

            <div style="margin-top: 30px;">
                <table class="tabla1" style="border-collapse: collapse; border-radius: 1em; overflow: hidden;">
                    <thead>
                        <tr style="background-color: #218825;">
                            <th width="10%" style=" color: #fff; border-top: 0.01em solid #000; border-left: 0.01em solid #000;">CANT.</th>
                            <th width="60%" style=" color: #fff; border-top: 0.01em solid #000;" class="text-left">DESCRIPCIÓN</th>
                            <th width="15%" style=" color: #fff; border-top: 0.01em solid #000;">PRECIO</th>
                            <th width="15%" style=" color: #fff; border-top: 0.01em solid #000; border-right: 0.01em solid #000;">IMPORTE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->getDetalles as $detalle)
                            <tr>
                                <td>{{$detalle->cantidad}}</td>
                                <td class="text-left">{{$detalle->descripcion}}</td>
                                <td>{{number_format($detalle->precio, 2, '.', ' ')}}</td>
                                <td>{{number_format($detalle->importe, 2, '.', ' ')}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td style="border: 0.01em solid;"><strong>A CUENTA</strong></td>
                            <td style="border: 0.01em solid;">{{number_format($pedido->acuenta, 2, '.', ' ')}}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td style="border: 0.01em solid;"><strong>RESTA</strong></td>
                            <td style="border: 0.01em solid;">{{number_format($pedido->saldo_total, 2, '.', ' ')}}</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td style="border: 0.01em solid;"><strong>TOTAL</strong></td>
                            <td style="border: 0.01em solid;">{{number_format($pedido->costo_total, 2, '.', ' ')}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div style="border: 1px solid #000; border-radius: 7px; margin-top: 50px;">
                <table class="tabla-reporte" cellspacing="0">
                    <tr>
                        <td>
                            <strong>TIPO DE ENTREGA DEL PEDIDO:</strong> 

                            @if ($pedido->tipo_entrega == 'LOCAL')
                            ENTREGA EN LOCAL
                            @elseif($pedido->tipo_entrega == 'DOMICILIO')
                            ENTREGA EN DOMICILIO
                            @elseif($pedido->tipo_entrega == 'AGENCIA')
                            ENTREGA ENVIAR POR AGENCIA
                            @endif

                            @if ($pedido->detalle_envio)
                                <pre>{{$pedido->detalle_envio}}</pre>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="footer">
            {{-- <hr>
            <div class="text-center">Jr. Aguilar #560 - Telf. (062) 62-2985 - (062) 62-2993</div> --}}
            <div style="border: 1px solid #000; border-radius: 7px;">
                <table class="tabla-reporte" cellspacing="0" style="font-size: 8px !important;">
                    <tr>
                        <td>USTED PUEDE HACER PAGOS DIRECTAMENTE EN NUESTRAS CUENTAS EN LOS SIGUIENTES BANCOS</td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <img src="./theme/images/yape.jpg" alt="YAPE" style="width: 150px; float: right;">
                                <strong>BANCO:</strong> BANCO DE CREDITO DEL PERU<br>
                                <strong>TITULAR:</strong> IRRIBARREN VALDIVIA ROBERT LIDER<br>
                                <strong>NRO CUENTA (SOLES):</strong> 36520627854062<br>
                                <strong>CCI:</strong> 00236512062785406252<br>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>