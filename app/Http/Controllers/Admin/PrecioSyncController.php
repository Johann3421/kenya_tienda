<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Producto;
use Illuminate\Support\Facades\DB;

class PrecioSyncController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.administrador.precios.sync');
    }

    public function sync(Request $request)
    {
        $request->validate([
            'csv_url' => 'required|url'
        ], [
            'csv_url.required' => 'La URL del Google Sheet es obligatoria.',
            'csv_url.url' => 'La URL ingresada no es válida.'
        ]);

        $url = $request->input('csv_url');
        
        try {
            // Descargar los datos de la URL
            // NOTA: Para Google Apps Script Web App, file_get_contents intentará seguir redirecciones automáticamente.
            $opts = [
                "http" => [
                    "method" => "GET",
                    "header" => "Accept: application/json\r\n",
                    "follow_location" => 1,
                    "max_redirects" => 5
                ]
            ];
            $context = stream_context_create($opts);
            $fileData = file_get_contents($url, false, $context);
            
            if ($fileData === false) {
                return back()->with('error', 'No se pudo descargar la información de la URL proporcionada. Asegúrate de usar la URL de tu Google Apps Script Web App (script.google.com/...) con el token correcto.');
            }

            // Intentar decodificar como JSON primero (Google Apps Script)
            $data = json_decode($fileData, true);

            if (is_array($data)) {
                if (isset($data['error'])) {
                    return back()->with('error', 'El servidor remoto devolvió un error: ' . $data['error']);
                }
                // Formato JSON 2D Array de Google Apps Script
                $header = array_shift($data);
                $rows = $data;
            } else {
                // Fallback a CSV si no es JSON
                $lines = explode(PHP_EOL, $fileData);
                $header = str_getcsv(array_shift($lines));
                $rows = array_map('str_getcsv', $lines);
            }
            
            // Buscar índices de las columnas importantes (indiferente a mayúsculas)
            $nroParteIndex = -1;
            $precioIndex = -1;
            
            if (!$header) {
                return back()->with('error', 'El archivo o JSON está vacío.');
            }

            foreach ($header as $index => $colName) {
                $cleanColName = strtolower(trim((string)$colName));
                if ($cleanColName === 'nro_parte' || $cleanColName === 'nro parte' || $cleanColName === 'numero de parte') {
                    $nroParteIndex = $index;
                }
                if ($cleanColName === 'precio' || $cleanColName === 'precio unitario' || $cleanColName === 'precio_unitario' || str_contains($cleanColName, 'valor venta canal')) {
                    $precioIndex = $index;
                }
            }

            if ($nroParteIndex === -1 || $precioIndex === -1) {
                return back()->with('error', 'No se encontraron las columnas necesarias. Asegúrate de que existan cabeceras llamadas "NRO_PARTE" y "VALOR VENTA CANAL". Columnas encontradas: ' . implode(', ', $header));
            }

            $actualizados = 0;
            $noEncontrados = 0;

            DB::beginTransaction();

            foreach ($rows as $row) {
                if (!is_array($row) || !isset($row[$nroParteIndex]) || !isset($row[$precioIndex])) continue;

                $nroParte = trim((string)$row[$nroParteIndex]);
                // Limpiar el precio (quitar símbolos de moneda, comas, etc)
                $precioStr = trim((string)$row[$precioIndex]);
                $precioLimpio = preg_replace('/[^0-9.]/', '', $precioStr);
                
                if (empty($nroParte) || !is_numeric($precioLimpio)) continue;

                // Actualizar producto en la BD
                $affectedRows = Producto::where('nro_parte', $nroParte)
                                        ->update(['precio_unitario' => $precioLimpio]);

                if ($affectedRows > 0) {
                    $actualizados += clone $affectedRows;
                } else {
                    $noEncontrados++;
                }
            }

            DB::commit();

            return back()->with('success', "Sincronización completada: $actualizados precios actualizados. $noEncontrados números de parte no se encontraron en la base de datos.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al procesar el archivo: ' . $e->getMessage());
        }
    }
}
