<!-- filepath: c:\xampp\htdocs\kenya_tienda\resources\views\sistema\servicio\recibo.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>RECIBO PDF N° {{$soporte->id}}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <style>
        body, pre { font-family: 'Montserrat', sans-serif; margin: 0; padding: 0; }
        body { font-size: 12px; }
        pre { font-size: 10px; white-space: pre-wrap; }
        table { font-size: 11px; border-collapse: collapse; }
        @page { margin: 90px 50px; }
        #header { position: fixed; left: 0; top: -70px; right: 0; height: 50px; text-align: center; }
        #footer { position: fixed; left: 0; bottom: 10px; right: 0; height: 50px; }
        .text-center { text-align: center !important; }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .float-right { float: right !important; }
        .float-left { float: left !important; }
        .text-justify { text-align: justify !important; }
        .tabla-reporte { width: 100%; }
        .tabla-reporte td { border-bottom: 0.01em solid #000; padding: 3px 10px; }
        .tabla-reporte tr:last-child td { border-bottom: none; }
        .tabla1 { width: 100%; text-align: center; }
        .tabla1 thead th, .tabla1 tbody td { padding: 4px 10px; }
        .tabla1 thead tr th, .tabla1 tbody tr td { border-bottom: 0.01em solid #000; }
        .tabla1 tbody tr td:first-child { border-left: 0.01em solid #000; }
        .tabla1 tbody tr td:last-child { border-right: 0.01em solid #000; }
        /* Estética para pie de página */
        #footer .condiciones {
            border: 1px solid #000;
            border-radius: 7px;
            margin-bottom: 10px;
            padding: 6px 12px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    @php
        $configs = App\Models\Configuracion::whereIn('nombre', [
            'contacto_email',
            'contacto_telefono',
            'contacto_direccion',
            'boleta_titulo',
            'ruc_empresa'
        ])->get()->keyBy('nombre');
        $fechaRegistro = is_string($soporte->fecha_registro)
            ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $soporte->fecha_registro)
            : $soporte->fecha_registro;
        $fechaEntrega = is_string($soporte->fecha_entrega)
            ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $soporte->fecha_entrega)
            : $soporte->fecha_entrega;
        $accesorios = json_decode($soporte->accesorios, true);
    @endphp

    <div class="mi_plantilla">
        <!-- Encabezado -->
        <div id="header">
            <table width="100%">
                <tr>
                    <td class="text-left" style="width: 65%;">
                        <img src="{{asset('theme/images/membrete.png')}}" alt="recibo-logo" style="width: 95%;">
                    </td>
                    <td class="text-right" style="width: 35%;">
                        <div class="text-center" style="border: 2px solid #000; border-radius: 7px; font-size: 14px; height: 90px; padding-top: 0px;">
                            <div style="padding: 3px 0;">RUC &nbsp; {{ $configs['ruc_empresa']->descripcion ?? '' }}</div>
                            <h3 style="background-color: #ee7c31; color: #fff; margin: 2px 0 0 0; padding: 5px 15px;">SOPORTE TÉCNICO</h3>
                            <div>N° {{str_pad($soporte->id, 4, '0', STR_PAD_LEFT)}}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Contenido principal -->
        <div id="content" style="margin-top: 40px;">
            <div class="text-center" style="margin-bottom: 30px; font-weight: 700;">
                <h2>SOPORTE TÉCNICO ESPECIALIZADO<br>DE EQUIPOS DE COMPUTO Y SUMINISTROS EN GENERAL</h2>
            </div>

            <div style="width: 50%; border: 1px solid #000; border-radius: 7px; margin-bottom: 10px;">
                <table class="tabla-reporte" cellspacing="0">
                    <tr>
                        <td width="15%" class="text-center">
                            <strong>FECHA REGISTRO</strong><br>
                            {{ $fechaRegistro->format('d/m/Y H:i') }}
                        </td>
                        <td width="15%" class="text-center">
                            <strong>FECHA ENTREGA</strong><br>
                            {{ $fechaEntrega->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                </table>
            </div>

            <div style="border: 1px solid #000; border-radius: 7px; margin-bottom: 10px;">
                <table class="tabla-reporte" cellspacing="0">
                    <tr>
                        <td width="15%"><strong>CLIENTE</strong></td>
                        <td width="45%">: {{$soporte->getCliente->nombres}}</td>
                        <td width="15%"><strong>DNI/RUC</strong></td>
                        <td width="25%">: {{$soporte->cliente_id}}</td>
                    </tr>
                    <tr>
                        <td><strong>DIRECCIÓN</strong></td>
                        <td>: {{$soporte->getCliente->direccion}}</td>
                        <td><strong>TELEFONO</strong></td>
                        <td>: {{$soporte->getCliente->celular}}</td>
                    </tr>
                </table>
            </div>

            <div style="border: 1px solid #000; border-radius: 7px;">
                <table class="tabla-reporte" cellspacing="0">
                    <tr style="background-color: #ee7c31; color: #fff;">
                        <td style="border-right: 0.01em solid #000;"><strong>EQUIPO</strong></td>
                        <td style="border-right: 0.01em solid #000;"><strong>MARCO</strong></td>
                        <td style="border-right: 0.01em solid #000;"><strong>MODELO</strong></td>
                        <td><strong>SERIE</strong></td>
                    </tr>
                    <tr>
                        <td style="border-right: 0.01em solid #000;">{{$soporte->equipo}}</td>
                        <td style="border-right: 0.01em solid #000;">{{$soporte->marca}}</td>
                        <td style="border-right: 0.01em solid #000;">{{$soporte->modelo}}</td>
                        <td>{{$soporte->serie}}</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <strong>DESCRIPCIÓN DE LA FALLA: </strong>
                            <pre style="max-width: 100%;">{{$soporte->descripcion}}</pre>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <strong>ACCESORIOS: </strong>
                            @if ($accesorios['sin_accesorios'] == 'SI')
                                <div>SIN ACCESORIOS</div>
                            @else
                                <div>
                                    <ul style="padding: 0 10px; margin: 0;">
                                        @if ($accesorios['cargador'] == 'SI') <li>CARGADOR</li> @endif
                                        @if ($accesorios['cable_usb'] == 'SI') <li>CABLE UDB</li> @endif
                                        @if ($accesorios['cable_poder'] == 'SI') <li>CABLE PODER</li> @endif
                                        @if ($accesorios['otros'] != '') <li>{{$accesorios['otros']}}</li> @endif
                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Tabla de detalles SIN precios ni importes -->
            <div style="margin-top: 30px;">
                <table class="tabla1" style="border-collapse: collapse; border-radius: 1em; overflow: hidden;">
                    <thead>
                        <tr style="background-color: #ee7c31;">
                            <th width="15%" style="color: #fff; border-top: 0.01em solid #000; border-left: 0.01em solid #000">CANT.</th>
                            <th width="85%" style="color: #fff; border-top: 0.01em solid #000; border-right: 0.01em solid #000" class="text-left">DESCRIPCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($soporte->getDetalles as $detalle)
                            <tr>
                                <td>{{$detalle->cantidad}}</td>
                                <td class="text-left">{{$detalle->descripcion}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Sección de reporte técnico -->
            <div style="margin-top: 50px; border: 1px solid #000; border-radius: 7px; padding: 5px 10px;">
                <strong>REPORTE TÉCNICO: </strong>
                @if ($soporte->reporte_tecnico)
                    <pre>{{$soporte->reporte_tecnico}}</pre>
                @endif
            </div>

            <!-- Firmas -->
            <div class="row" style="margin-top: 100px;">
                <table style="width: 100%;">
                    <tr class="column text-center">
                        <td style="padding: 0 15px;"><div style="border-top: 1px solid #000;">FIRMA DE CLIENTE</div></td>
                        <td style="padding: 0 15px;"><div style="border-top: 1px solid #000;">FIRMA DE CONFORMIDAD</div></td>
                        <td style="padding: 0 15px;"><div style="border-top: 1px solid #000;">V° B° TÉCNICO</div></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Pie de página solo condiciones -->
        <div id="footer">
            <div class="condiciones">
                <strong>CONDICIONES</strong><br>
                EQUIPOS CON MAS DE 30 DIAS, SE CONSIDERARA COMO ABANDONO Y PASARA A SER PARTE DE LA EMPRESA Y/O A SER RECICLADO.
            </div>
        </div>
    </div>
</body>
</html>
