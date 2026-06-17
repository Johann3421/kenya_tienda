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
            // Descargar el archivo CSV de la URL
            $csvData = file_get_contents($url);
            
            if ($csvData === false) {
                return back()->with('error', 'No se pudo descargar el archivo CSV de la URL proporcionada. Asegúrate de que esté publicado en la web.');
            }

            // Parsear CSV (separado por comas)
            $lines = explode(PHP_EOL, $csvData);
            $header = str_getcsv(array_shift($lines));
            
            // Buscar índices de las columnas importantes (indiferente a mayúsculas)
            $nroParteIndex = -1;
            $precioIndex = -1;
            
            foreach ($header as $index => $colName) {
                $cleanColName = strtolower(trim($colName));
                if ($cleanColName === 'nro_parte' || $cleanColName === 'nro parte' || $cleanColName === 'numero de parte') {
                    $nroParteIndex = $index;
                }
                if ($cleanColName === 'precio' || $cleanColName === 'precio unitario' || $cleanColName === 'precio_unitario') {
                    $precioIndex = $index;
                }
            }

            if ($nroParteIndex === -1 || $precioIndex === -1) {
                return back()->with('error', 'No se encontraron las columnas necesarias en el CSV. Asegúrate de que existan columnas llamadas "nro_parte" y "precio".');
            }

            $actualizados = 0;
            $noEncontrados = 0;

            DB::beginTransaction();

            foreach ($lines as $line) {
                if (trim($line) === '') continue;
                
                $row = str_getcsv($line);
                
                if (!isset($row[$nroParteIndex]) || !isset($row[$precioIndex])) continue;

                $nroParte = trim($row[$nroParteIndex]);
                // Limpiar el precio (quitar símbolos de moneda, comas, etc)
                $precioStr = trim($row[$precioIndex]);
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
