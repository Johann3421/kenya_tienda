<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Serie;


class FacturarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'serie'       => 'required',
            'forma_pago'  => 'required',
            'monto'       => 'required',
            'sub_total'   => 'required',
            'igv'         => 'required',
            'total'       => 'required',
            'fecha_pago'  => 'required',
        ]);

        try {
            $factura = Api::where('descripcion', 'FACTURA')->where('activo', 'SI')->select('url', 'token', 'descripcion')->first();

            if (!$factura) {
                return [
                    'type'     =>  'warning',
                    'title'    =>  'INCORRECTO: ',
                    'message'  =>  'No existen datos para poder Facturar.'
                ];
            }

            $facturacion = Facturacion::findOrFail($request->facturacion);
            DB::beginTransaction();

            $pago = new Pago();
            $pago->fecha_emision = date('Y-m-d');
            $pago->subtotal = $request->sub_total;
            $pago->igv = $request->igv;
            $pago->total = $request->total;
            $pago->forma_pago = $request->forma_pago;
            $pago->numero_operacion = $request->operacion;
            $pago->tipodocumento_sunat = $facturacion->codigo_sunat;
            $pago->numerodoc_facturacion = $facturacion->numero_documento;
            $pago->denominacion_facturacion = $facturacion->razon_social;
            $pago->cliente_id = $request->id;
            $pago->serie_id = $request->serie;
            $pago->anulado = 'NO';
            $pago->save();

            $detalle = new Detallepago();
            $detalle->cantidad = 1;
            $detalle->monto = $request->monto;
            $detalle->importe = $request->monto;
            $detalle->catalogopago_id = $request->catalogopago_id;
            $detalle->asignarpago_id = $request->asignacion_id;
            $detalle->pago_id = $pago->id;
            $detalle->cliente_id = $request->id;
            $detalle->save();

            if ($request->serie_affected == 10) {
                $gravada = $request->sub_total;
                $exonerada = 0.00;
                $total_base_igv = $request->sub_total;
            } else {
                $gravada = 0.00;
                $exonerada = $request->sub_total;
                $total_base_igv = $request->sub_total;
            }

            $asignar = Asignarpago::find($request->asignacion_id);
            $asignar->saldo = 0;
            //$asignar->fecha_pago = $request->fecha_pago;
            $asignar->validado = 'SI';
            $asignar->save();

            $cat = Catalogopago::findOrFail($request->catalogopago_id);

            $items[] = array(    
                "codigo_interno" => "P".substr($cat->tipo, 0, 1).substr($cat->concepto, 0, 3).'-'.$cat->precio,    
                "descripcion" => $request->catalogopago_descripcion.' - '.$request->catalogopago_concepto,    
                "codigo_producto_sunat" => "81160000",    
                "unidad_de_medida" => "ZZ",    
                "cantidad" => 1,    
                "valor_unitario" => $request->sub_total,    
                "codigo_tipo_precio" => "01",    
                "precio_unitario" => $request->monto,    
                "codigo_tipo_afectacion_igv" => $request->serie_affected,    
                "total_base_igv" => $total_base_igv,    
                "porcentaje_igv" => 18,
                "total_igv" => $request->igv,    
                "total_impuestos" => $request->igv,    
                "total_valor_item" => $request->sub_total,    
                "total_item" => $request->monto  
            );

            $items = json_encode($items, true);

            $ubigeo = $facturacion->ubigeo;

            if ($request->forma_pago == 'EFECTIVO') {
                $forma_pago = 'Efectivo';
            } else {
                $forma_pago = 'Transferencia';
            }
        
            $postfields = "{\r\n  
                \"serie_documento\": \"".$request->serie_description."\",\r\n
                \"numero_documento\": \"#\",\r\n  
                \"fecha_de_emision\": \"".date('Y-m-d')."\",\r\n  
                \"hora_de_emision\": \"".date('H:i:s')."\",\r\n  
                \"codigo_tipo_operacion\": \"0101\",\r\n  
                \"codigo_tipo_documento\":\"".$request->serie_typedocument."\",\r\n
                \"codigo_tipo_moneda\": \"PEN\",\r\n  
                \"fecha_de_vencimiento\":\"".date('Y-m-d')."\",\r\n  
                \"numero_orden_de_compra\": \"PM".$pago->id."\", \r\n  
                \"datos_del_cliente_o_receptor\":{\r\n    
                    \"codigo_tipo_documento_identidad\": \"".$facturacion->codigo_sunat."\",\r\n    
                    \"numero_documento\": \"".$facturacion->numero_documento."\",\r\n    
                    \"apellidos_y_nombres_o_razon_social\": \"".$facturacion->razon_social."\",\r\n    
                    \"codigo_pais\": \"PE\",\r\n    
                    \"ubigeo\": \"".$ubigeo."\",\r\n    
                    \"direccion\": \"".$facturacion->direccion."\",\r\n    
                    \"correo_electronico\": \"ninguno\",\r\n    
                    \"telefono\": \"ninguno\"\r\n  
                },\r\n  
                \"totales\": {\r\n    
                    \"total_exportacion\": 0.00,\r\n    
                    \"total_operaciones_gravadas\": $gravada,\r\n    
                    \"total_operaciones_inafectas\": 0.00,\r\n    
                    \"total_operaciones_exoneradas\": $exonerada,\r\n    
                    \"total_operaciones_gratuitas\": 0.00,\r\n    
                    \"total_igv\": $request->igv,\r\n    
                    \"total_impuestos\": $request->igv,\r\n    
                    \"total_valor\": $request->sub_total,\r\n    
                    \"total_venta\": $request->total\r\n  
                },\r\n  
                \"items\":".$items.",\r\n
                \"informacion_adicional\": \"Forma de pago:".$forma_pago."|Caja: 1\",
                \"acciones\": {\r\n
                    \"enviar_email\":false\r\n
                }\r\n
                \r\n}";

            //return $postfields;
            //API FACTURADOR
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $factura->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $postfields,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$factura->token,
                ),
                CURLOPT_SSL_VERIFYPEER => false,
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //API FACTURADOR
            
            $arreglo = json_decode($response, true);
            
            $external_id = $arreglo['data']['external_id'];
            $filename = $arreglo['data']['filename'];
            $hash = $arreglo['data']['hash'];
            $number = $arreglo['data']['number'];
            $explode = explode('-', $number);
            $number_to_letter = $arreglo['data']['number_to_letter'];

            $b64 = $arreglo['data']['qr'];
            $bin = base64_decode($b64);
            $qrRuta = 'public/FACTURAS_PENSIONES/qr/'.$external_id.'.png';

            $state_type_description = $arreglo['data']['state_type_description'];
            $state_type_id = $arreglo['data']['state_type_id'];//new

            $url_cdr = $arreglo['links']['cdr'];
            $url_pdf = $arreglo['links']['pdf'];
            $url_xml = $arreglo['links']['xml'];

            if ($request->serie_typedocument == '01') {

                if ($url_cdr != '') {
                    $file_cdr = file_get_contents($url_cdr, false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false))));
                    $crdRuta = 'storage/FACTURAS_PENSIONES/cdr/'.$external_id.'.zip';
                }

                $code = $arreglo['response']['code'];//new
                $description = $arreglo['response']['description'];//new
                $notes = $arreglo['response']['description'][0];//new
            } else {
                $crdRuta = null;

                $code = 0;//new
                $description = 'La Boleta numero '.$number.' ha sido aceptada';//new
                $notes = 0;//new
            }
            $message = $code.'*/*'.$description.'*/*'.$notes;
            $file_pdf = file_get_contents($url_pdf, false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false))));
            $file_xml = file_get_contents($url_xml, false, stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false))));
            $pdfRuta = 'storage/FACTURAS_PENSIONES/pdf/'.$external_id.'.pdf';
            $xmlRuta = 'storage/FACTURAS_PENSIONES/xml/'.$external_id.'.xml';

            $pago->external_id = $external_id;
            $pago->file_name = $filename;
            $pago->hash = $hash;
            $pago->number = $number;
            $pago->number_to_letter = $number_to_letter;
            $pago->file_cdr = $crdRuta;
            $pago->file_xml = $xmlRuta;
            $pago->file_pdf = $pdfRuta;
            $pago->image_qr = $qrRuta;
            $pago->serial_number = $explode[1];
            $pago->serial = $explode[0];
            $pago->state_sunat = $state_type_description;
            $pago->message_sunat = $message;
            $pago->save();

            DB::commit();

            Storage::put($qrRuta, $bin);
            if ($crdRuta) {
                file_put_contents(public_path($crdRuta), $file_cdr);
            }
            file_put_contents(public_path($pdfRuta), $file_pdf);
            file_put_contents(public_path($xmlRuta), $file_xml);

            return [
                'state'     =>  'alert-success',
                'message'   =>  'Se realizo el pago al Cliente correctamente.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'state'     =>  'alert-danger',
                'message'   =>  $th.' Ocurrio un error al realizar el pago al Cliente, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function factDownload($id, $tipo)
    {
        $pago = Pago::findOrFail($id, ["id", "file_cdr", "file_xml", "file_pdf"]);
        switch ($tipo) {
            case 'cdr':
                $file = public_path($pago->file_cdr);
                return response()->download($file, 'facturaCDR.zip');
                //$headers = array('Content-Type: application/pdf',);
                break;
            
            case 'xml':
                $file = public_path($pago->file_xml);
                return response()->download($file, 'facturaXML.xml');
                break;

            case 'pdf':
                $file = public_path($pago->file_pdf);
                return response()->download($file, 'facturaPDF.pdf');
                //return Storage::download($billing->file_pdf);
                break;
        }
    }

    public function apiFactura(Request $request)
    {
        $data["contribuyente"] = array(
            "token_contribuyente" => "3P7OCA6139M0Y18L8YXQXEEX0L7966FWS7EZG", //Token del contribuyente
            "id_usuario_vendedor" => 1, //Debes ingresar el ID de uno de tus vendedores (opcional)
            "tipo_proceso" => "prueba", //Funcional en una siguiente versión. El ambiente al que se enviará, puede ser: {prueba, produccion}
            "tipo_envio" => "inmediato" //funcional en una siguiente versión. Aquí puedes definir si se enviará de inmediato a sunat
        );
        
        $data["cliente"] = array(
            "tipo_docidentidad" => $request->tipo_documento, //{0: SINDOC, 1: DNI, 6: RUC}
            "numerodocumento" => $request->numero_documento, //Es opcional solo cuando tipo_docidentidad es 0, caso contrario se debe ingresar el número de ruc
            "nombre" => $request->denominacion, //Es opcional solo cuando tipo_docidentidad es 1, caso contrario es obligatorio ingresar aquí la razón social
            "email" => "", //opcional: (si tiene correo se enviará automáticamente el email)
            "direccion" => $request->direccion, //opcional: 
            "ubigeo"	=> $request->ubigeo,
            "sexo" => "", //opcional: masculino
            "fecha_nac" => "", //opcional: 
            "celular" => "" //opcional
        );
        
        $data["cabecera_comprobante"] = array(
            "tipo_documento" => "01",  //{"01": FACTURA, "03": BOLETA}
            "moneda" => "PEN",  //{"USD", "PEN"}
            "idsucursal" => 1,  //{ID DE SUCURSAL}
            "id_condicionpago" => "",  //condicionpago_comprobante
            "fecha_comprobante" => "10/05/2020",  //fecha_comprobante
            "nro_placa" => "",  //nro_placa_vehiculo
            "nro_orden" => "",  //nro_orden
            "guia_remision" => "",  //guia_remision_manual
            "descuento_monto" => 0,  // (máximo 2 decimales) (monto total del descuento)
            "descuento_porcentaje" => 0,  // (máximo 2 decimales) (porcentaje total del descuento)
            "observacion" => "",  //observacion_documento
        );
        
        $detalle[] = array(
            "idproducto" => 0,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
            "codigo"	=> "TV_CODIGOPROD", //codigo del producto (requerido)
            "afecto_icbper" => "no",  //"afecto_icbper":"no",
            "id_tipoafectacionigv" => 10,  //"id_tipoafectacionigv":"10",
            "descripcion" => "Producto de Ejemplo",  //"descripcion":"Zapatos",
            "idunidadmedida" => 'NIU',  //{NIU para unidades, ZZ para servicio}
            "precio_venta" => 5,  //Precio unitario de venta (inc. IGV),
            "cantidad" => 111,  //"cantidad":"1"
        );
        
        $detalle[] = array(
            "idproducto" => 0,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
            "codigo"	=> "TV_CODIGOPROD2", //codigo del producto (requerido)
            "afecto_icbper" => "no",  //"afecto_icbper":"no",
            "id_tipoafectacionigv" => 20,  //"id_tipoafectacionigv":"10",
            "descripcion" => "Producto de Ejemplo",  //"descripcion":"Zapatos",
            "idunidadmedida" => 'ZZ',  //{NIU para unidades, ZZ(NIU) para servicio}
            "precio_venta" => 78.56,  //Precio unitario de venta (inc. IGV),
            "cantidad" => 1,  //"cantidad":"1"
        );
        
        $data["detalle"] = $detalle;
        
        $ruta = "https://grupovascoperu.com/sys_fe/api/procesar_venta";
        $data_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ruta);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer ",
                "Content-Type: application/json",
                "cache-control: no-cache"
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);
        if (curl_error($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (isset($error_msg)) {
            $resp["respuesta"] = "error";
            $resp["titulo"] = "Error";
            $resp["data"] = "";
            $resp["encontrado"] = false;
            $resp["mensaje"] = "Error en Api de Búsqueda";
            $resp["errores_curl"] = $error_msg;
            echo json_encode($resp);
            exit();
        }
        
        echo $respuesta;
        exit();
    }

    public function apiBoleta(Request $request)
    {
        $data["contribuyente"] = array(
            "token_contribuyente" => "", //Token del contribuyente
            "id_usuario_vendedor" => 1, //Debes ingresar el ID de uno de tus vendedores (opcional)
            "tipo_proceso" => "prueba", //Funcional en una siguiente versión. El ambiente al que se enviará, puede ser: {prueba, produccion}
            "tipo_envio" => "inmediato" //funcional en una siguiente versión. Aquí puedes definir si se enviará de inmediato a sunat
        );
        
        $data["cliente"] = array(
            "tipo_docidentidad" => 1, //{0: SINDOC, 1: DNI, 6: RUC}
            "numerodocumento" => "44359645", //Es opcional solo cuando tipo_docidentidad es 0, caso contrario se debe ingresar el número de ruc
            "nombre" => "nombre de tu cliente", //Es opcional solo cuando tipo_docidentidad es 1, caso contrario es obligatorio ingresar aquí la razón social
            "email" => "email_cliente@gmail.com", //opcional: (si tiene correo se enviará automáticamente el email)
            "direccion" => "", //opcional: 
            "ubigeo"	=> "",
            "sexo" => "", //opcional: masculino
            "fecha_nac" => "", //opcional: 
            "celular" => "" //opcional
        );
        
        $data["cabecera_comprobante"] = array(
            "tipo_documento" => "03",  //{"01": FACTURA, "03": BOLETA}
            "moneda" => "PEN",  //{"USD", "PEN"}
            "idsucursal" => 1,  //{ID DE SUCURSAL}
            "id_condicionpago" => "",  //condicionpago_comprobante
            "fecha_comprobante" => "10/05/2020",  //fecha_comprobante
            "nro_placa" => "",  //nro_placa_vehiculo
            "nro_orden" => "",  //nro_orden
            "guia_remision" => "",  //guia_remision_manual
            "descuento_monto" => 0,  // (máximo 2 decimales) (monto total del descuento)
            "observacion" => "",  //observacion_documento
        );
        
        $detalle[] = array(
            "idproducto" => 0,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
            "codigo"	=> "TV_CODIGOPROD", //codigo del producto (requerido)
            "afecto_icbper" => "no",  //"afecto_icbper":"no",
            "id_tipoafectacionigv" => 10,  //"id_tipoafectacionigv":"10",
            "descripcion" => "Producto de Ejemplo",  //"descripcion":"Zapatos",
            "idunidadmedida" => 'NIU',  //{NIU para unidades, ZZ para servicio}
            "precio_venta" => 5,  //Precio unitario de venta (inc. IGV),
            "cantidad" => 111,  //"cantidad":"1"
        );
        
        $detalle[] = array(
            "idproducto" => 0,  //(opcional, puede ser cero) (si el idproducto coincide con la BD se llevará control del stock)
            "codigo"	=> "TV_CODIGOPROD2", //codigo del producto (requerido)
            "afecto_icbper" => "no",  //"afecto_icbper":"no",
            "id_tipoafectacionigv" => 20,  //"id_tipoafectacionigv":"10",
            "descripcion" => "Producto de Ejemplo",  //"descripcion":"Zapatos",
            "idunidadmedida" => 'NIU',  //{NIU para unidades, ZZ para servicio}
            "precio_venta" => 78.56,  //Precio unitario de venta (inc. IGV),
            "cantidad" => 2.9,  //"cantidad":"1"
        );
        
        $data["detalle"] = $detalle;
        
        $ruta = "https://grupovascoperu.com/sys_fe/api/procesar_venta";
        $data_json = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ruta);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer ",
                "Content-Type: application/json",
                "cache-control: no-cache"
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);
        if (curl_error($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (isset($error_msg)) {
            $resp["respuesta"] = "error";
            $resp["titulo"] = "Error";
            $resp["data"] = "";
            $resp["encontrado"] = false;
            $resp["mensaje"] = "Error en Api de Búsqueda";
            $resp["errores_curl"] = $error_msg;
            echo json_encode($resp);
            exit();
        }
        
        echo $respuesta;
        exit();
    }
}
