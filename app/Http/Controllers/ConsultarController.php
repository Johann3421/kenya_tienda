<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soporte;
use App\Pedido;
use App\Garantia;
use App\Producto;
use App\Models\Cliente;
use App\Models\Configuracion;
use Carbon\Carbon;

class ConsultarController extends Controller {
    public function soporte() {
        $whatsapp = Configuracion::where('nombre', 'contacto_whatsapp')->first();
        return view('consultar.soporte', compact('whatsapp'));
    }

    public function soporte_buscar(Request $request) {
        $dato = strtoupper($request->search);

        $barras = substr($dato, 0, 7);
        $id = substr($dato, -1, 4);

        $soporte = Soporte::where('id', $id)->where('codigo_barras', $barras)->with(['getCliente', 'getDetalles'])->first();

        if ($soporte) {
            return [
                'state' => 'success',
                'soporte' => $soporte,
            ];
        } else {
            return [
                'state' => 'error',
            ];
        }
    }

    public function pedido() {
        $whatsapp = Configuracion::where('nombre', 'contacto_whatsapp')->first();
        return view('consultar.pedido', compact('whatsapp'));
    }

    public function pedido_buscar(Request $request) {

        $dato = strtoupper($request->search);

        $barras = substr($dato, 0, 7);
        $id = substr($dato, -1, 4);

        $pedido = pedido::where('id', $id)->where('codigo_barras', $barras)->with(['getCliente', 'getDetalles'])->first();

        if ($pedido) {
            return [
                'state' => 'success',
                'pedido' => $pedido,
            ];
        } else {
            return [
                'state' => 'error',
            ];
        }
    }
    public function garantia() {
        $whatsapp = Configuracion::where('nombre', 'contacto_whatsapp')->first();
        $garantia = Garantia::with('getManuales')->first();
        $resta = Garantia::select('garantia.garantia')->get();

        $fventa = Garantia::select('garantia.fecha_venta');
        $prod = Garantia::with('getManuales.getManual')->first();
        //$date =Garantia::select('garantia.fecha_venta')->get();
        return view('consultar.garantia', compact('whatsapp', 'garantia', 'prod'));
        //return ($garantia);
    }

    public function garantia_buscar(Request $request) {
        try {
            \Log::info('=== INICIO garantia_buscar ===');
            \Log::info('Búsqueda por serie: ' . $request->search);

            $garantia = Garantia::where('serie', 'LIKE', "%{$request->search}%")
                ->with(['getManuales.getManual', 'getDriversprod.getDrivers'])
                ->first();

            if ($garantia) {
                \Log::info('Garantía encontrada ID: ' . $garantia->id);

                // Obtener todos los productos de esta garantía
                $productos = $garantia->getProductos;

                \Log::info('Productos encontrados: ' . count($productos));

                // Mapeo de nombres de campos de DB a nombres esperados en el template
                $campoMapping = [
                    'Procesador' => 'procesador',
                    'Memoria Ram' => 'ram',
                    'Almacenamiento' => 'almacenamiento',
                    'Unidad Óptica' => 'unidad_optica',
                    'Conectividad LAN' => 'conectividad_lan',
                    'Conectividad WLAN' => 'conectividad_wlan',
                    'Conectividad USB' => 'conectividad_usb',
                    'Conectividad VGA' => 'conectividad_vga',
                    'Conectividad HDMI' => 'conectividad_hdmi',
                    'Sistema Operativo' => 'sistema_operativo',
                    'Ofimatica' => 'suite_ofimatica',
                    'Ofimática' => 'suite_ofimatica',
                    'Periféricos' => 'perifericos',
                ];

                // Campos opcionales con valores por defecto
                $valoresDefault = [
                    'suite_ofimatica' => 'NO INCLUYE',
                    'perifericos' => 'NO ESPECIFICADO',
                ];

                if ($productos && $productos->count() > 0) {
                    $productosProcessados = [];

                    foreach ($productos as $producto) {
                        \Log::info('Procesando producto ID: ' . $producto->id . ', Nombre: ' . $producto->nombre);

                        $productoArray = $producto->toArray();

                        // Inicializar valores por defecto
                        foreach ($valoresDefault as $campo => $valor) {
                            $productoArray[$campo] = $valor;
                        }

                        // Cargar modelo
                        if ($producto->modelo) {
                            $modeloArr = $producto->modelo->toArray();
                            $modeloArr['img_url'] = $producto->modelo->img_url;
                            $productoArray['modelo'] = $modeloArr;
                        }

                        // Cargar especificaciones directamente de la tabla
                        $specs = \DB::table('especificaciones')
                            ->where('producto_id', $producto->id)
                            ->get();

                        \Log::info('Especificaciones encontradas para producto ' . $producto->id . ': ' . count($specs));

                        if ($specs && count($specs) > 0) {
                            foreach ($specs as $esp) {
                                // Usar el mapeo para convertir el nombre del campo
                                $nombreCampo = isset($campoMapping[$esp->campo])
                                    ? $campoMapping[$esp->campo]
                                    : strtolower(str_replace(' ', '_', $esp->campo));

                                \Log::info('Especificación original: "' . $esp->campo . '" → Mapeado a: "' . $nombreCampo . '" = ' . $esp->descripcion);
                                $productoArray[$nombreCampo] = $esp->descripcion;
                            }
                        } else {
                            \Log::warning('NO hay especificaciones para producto_id: ' . $producto->id);
                        }

                        $productosProcessados[] = $productoArray;
                    }

                    // Reemplazar la relación con los datos procesados
                    $garantia->setRelation('getProductos', collect($productosProcessados));
                }

                return [
                    'state' => 'success',
                    'garantia' => $garantia,
                ];
            } else {
                \Log::warning('No se encontró garantía con serie: ' . $request->search);
                return [
                    'state' => 'error',
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Error en garantia_buscar: ' . $e->getMessage() . ' - Línea: ' . $e->getLine());
            return [
                'state' => 'error',
            ];
        }
    }

    public function buscar_serie($serie) {
        try {
            $whatsapp = Configuracion::where('nombre', 'contacto_whatsapp')->first();
            $prod = Garantia::with('getManuales.getManual')->first();

            $garantia = Garantia::where('serie', 'LIKE', "%{$serie}%")
                ->with(['getManuales.getManual', 'getDriversprod.getDrivers'])
                ->first();

            if (!$garantia) {
                return redirect()->route('consultar.garantia', ['serie' => $serie]);
            }

            // Mapeo de nombres de campos de DB a nombres esperados en el template
            $campoMapping = [
                'Procesador' => 'procesador',
                'Memoria Ram' => 'ram',
                'Almacenamiento' => 'almacenamiento',
                'Unidad Óptica' => 'unidad_optica',
                'Conectividad LAN' => 'conectividad_lan',
                'Conectividad WLAN' => 'conectividad_wlan',
                'Conectividad USB' => 'conectividad_usb',
                'Conectividad VGA' => 'conectividad_vga',
                'Conectividad HDMI' => 'conectividad_hdmi',
                'Sistema Operativo' => 'sistema_operativo',
                'Ofimatica' => 'suite_ofimatica',
                'Ofimática' => 'suite_ofimatica',
                'Periféricos' => 'perifericos',
            ];

            // Campos opcionales con valores por defecto
            $valoresDefault = [
                'suite_ofimatica' => 'NO INCLUYE',
                'perifericos' => 'NO ESPECIFICADO',
            ];

            if ($garantia) {
                // Obtener todos los productos de esta garantía
                $productos = $garantia->getProductos;

                \Log::info('QR - Productos encontrados: ' . count($productos));

                if ($productos && $productos->count() > 0) {
                    $productosProcessados = [];

                    foreach ($productos as $producto) {
                        \Log::info('QR - Procesando producto ID: ' . $producto->id);

                        $productoArray = $producto->toArray();

                        // Inicializar valores por defecto
                        foreach ($valoresDefault as $campo => $valor) {
                            $productoArray[$campo] = $valor;
                        }

                        // Cargar modelo
                        if ($producto->modelo) {
                            $modeloArr = $producto->modelo->toArray();
                            $modeloArr['img_url'] = $producto->modelo->img_url;
                            $productoArray['modelo'] = $modeloArr;
                        }

                        // Cargar especificaciones directamente de la tabla
                        $specs = \DB::table('especificaciones')
                            ->where('producto_id', $producto->id)
                            ->get();

                        \Log::info('QR - Especificaciones encontradas para producto ' . $producto->id . ': ' . count($specs));

                        if ($specs && count($specs) > 0) {
                            foreach ($specs as $esp) {
                                // Usar el mapeo para convertir el nombre del campo
                                $nombreCampo = isset($campoMapping[$esp->campo])
                                    ? $campoMapping[$esp->campo]
                                    : strtolower(str_replace(' ', '_', $esp->campo));

                                \Log::info('QR - Especificación original: "' . $esp->campo . '" → Mapeado a: "' . $nombreCampo . '" = ' . $esp->descripcion);
                                $productoArray[$nombreCampo] = $esp->descripcion;
                            }
                        }

                        $productosProcessados[] = $productoArray;
                    }

                    // Reemplazar la relación con los datos procesados
                    $garantia->setRelation('getProductos', collect($productosProcessados));
                }
            }

            return view('consultar.garantiaQR', compact('whatsapp', 'garantia', 'prod'));
        } catch (\Exception $e) {
            \Log::error('Error en buscar_serie: ' . $e->getMessage() . ' - Línea: ' . $e->getLine());
            abort(500, 'Error al procesar la garantía');
        }
    }

}
