<!DOCTYPE html>
<html>
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
        }
        table {
            font-size: 11px;
        }
        @page { margin: 90px 50px; }
        #header { position: fixed; left: 0px; top: -70px; right: 0px; height: 50px; text-align: center; }
        #footer { position: fixed; left: 0px; bottom: -20px; right: 0px; height: 50px;}

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
        // Consulta optimizada para obtener todas las configuraciones necesarias
        $configuraciones = App\Models\Configuracion::whereIn('nombre', [
            'contacto_email',
            'contacto_telefono',
            'contacto_direccion',
            'boleta_titulo',
            'ruc_empresa'
        ])->get()->keyBy('nombre');

        // Convertir fechas a Carbon si son strings
        $fechaRegistro = is_string($pedido->fecha_registro)
            ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $pedido->fecha_registro)
            : $pedido->fecha_registro;

        $fechaEntrega = is_string($pedido->fecha_entrega)
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $pedido->fecha_entrega)
            : $pedido->fecha_entrega;
    @endphp

    <div class="mi_plantilla">
        <div id="header">
            <table width="100%">
                <tr>
                    <td class="text-left" style="width: 65%;">
                        <img src="{{asset('theme/images/membrete.png')}}" alt="recibo-logo" style="width: 95%;">
                    </td>
                    <td class="text-right" style="width: 35%;">
                        <div class="text-center" style="border: 2px solid #000; border-radius: 7px; font-size: 14px; height: 90px; padding-top: 0px;">
                            <div style="padding: 3px 0;">RUC &nbsp; {{ $configuraciones['ruc_empresa']->descripcion ?? '' }}</div>
                            <h3 style="background-color: #218825; color: #fff; margin: 2px 0 0 0; padding: 5px 15px;">PEDIDO</h3>
                            <div>N° {{str_pad($pedido->id, 4, '0', STR_PAD_LEFT)}}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div id="content" style="margin-top: 40px;">
            <div class="text-center" style="margin-bottom: 30px; font-weight: 700;">
                <h2>SOPORTE TÉCNICO ESPECIALIZADO<br>DE EQUIPOS DE COMPUTO Y SUMINISTROS EN GENERAL</h2>
            </div>

            <div style="width: 50%; border: 1px solid #000; border-radius: 7px; margin-bottom: 10px;">
                <table class="tabla-reporte" cellspacing="0">
                    <tr>
                        <td width="15%" class="text-center">
                            <strong>FECHA PEDIDO</strong><br>
                            {{ $fechaRegistro->format('d/m/Y H:i') }}
                        </td>
                        <td width="15%" class="text-center">
                            <strong>FECHA ENTREGA</strong><br>
                            {{ $fechaEntrega->format('d/m/Y') }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Resto del contenido se mantiene exactamente igual -->
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
