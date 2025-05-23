<?php

namespace App\Http\Controllers;
use App\Producto;
use App\Ruta;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Whoops\Run;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Driver_rutaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.garantia_ruta.index');
    }
    public function buscar(Request $request)
    {

        $drivers_ruta = Ruta::select("id", "rute","nombre_driver")
        ->where('rute', 'LIKE', '%'.$request->search.'%')
        ->orWhere('id', 'LIKE', '%'.$request->search.'%');

        $drivers_ruta = $drivers_ruta -> paginate(10);

        return [
            'pagination' => [
                'total' => $drivers_ruta->total(),
                'current_page' => $drivers_ruta->currentPage(),
                'per_page' => $drivers_ruta->perPage(),
                'last_page' => $drivers_ruta->lastPage(),
                'from' => $drivers_ruta->firstItem(),
                'to' => $drivers_ruta->lastPage(),
                'index' => ($drivers_ruta->currentPage() - 1) * $drivers_ruta->perPage(),
            ],
            'drivers_ruta' => $drivers_ruta,
        ];
    }
    public function store(Request $request)
{
    $request->validate([
        'nombre_driver' => 'required|string',
        'pdf_driver' => 'required|file',
        'chunkIndex' => 'required|integer',
        'totalChunks' => 'required|integer',
        'fileName' => 'required|string',
    ]);

    try {
        // Directorio temporal para almacenar los fragmentos
        $tempDir = storage_path('app/temp/' . $request->fileName);

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true); // Crear el directorio si no existe
        }

        // Guardar el fragmento en el directorio temporal
        $chunkPath = $tempDir . '/' . $request->chunkIndex;
        file_put_contents($chunkPath, file_get_contents($request->file('pdf_driver')->getRealPath()));

        // Verificar si todos los fragmentos han sido subidos
        $uploadedChunks = array_diff(scandir($tempDir), ['.', '..']);
        if (count($uploadedChunks) == $request->totalChunks) {
            // Crear el registro en la base de datos para obtener el ID
            $driver_ruta = new Ruta();
            $driver_ruta->nombre_driver = $request->nombre_driver;
            $driver_ruta->save();

            // Crear la carpeta con el ID del registro
            $route = 'DRIVERS/' . $driver_ruta->id;
            $finalDir = storage_path('app/public/' . $route);

            if (!file_exists($finalDir)) {
                mkdir($finalDir, 0777, true); // Crear el directorio final si no existe
            }

            // Ruta final del archivo combinado
            $finalPath = $finalDir . '/' . $request->fileName;
            $outputFile = fopen($finalPath, 'wb');

            // Combinar los fragmentos
            for ($i = 0; $i < $request->totalChunks; $i++) {
                $chunkPath = $tempDir . '/' . $i;
                if (!file_exists($chunkPath)) {
                    throw new \Exception("El fragmento {$i} no existe.");
                }
                fwrite($outputFile, file_get_contents($chunkPath));
                unlink($chunkPath); // Eliminar el fragmento después de combinarlo
            }

            fclose($outputFile);
            rmdir($tempDir); // Eliminar el directorio temporal

            // Actualizar la ruta del archivo en la base de datos
            $driver_ruta->rute = $route . '/' . $request->fileName;
            $driver_ruta->save();
        }

        return response()->json([
            'type' => 'success',
            'title' => 'CORRECTO:',
            'message' => 'Fragmento subido correctamente.',
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            'type' => 'danger',
            'title' => 'ERROR:',
            'message' => 'Error al guardar el fragmento: ' . $th->getMessage(),
        ], 500);
    }
}

// update
public function update(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:rutas,id',
        'nombre_driver' => 'required',
        'pdf_driver' => 'required|file|mimes:zip',
    ]);

    try {
        DB::beginTransaction();

        $driver_ruta = Ruta::findOrFail($request->id);
        $driver_ruta->nombre_driver = $request->nombre_driver;

        $route = 'DRIVERS/'.$driver_ruta->id;

        if ($request->hasFile('pdf_driver')) {
            if ($driver_ruta->rute) {
                Storage::delete('public/'.$driver_ruta->rute);
            }

            $file = $request->file('pdf_driver');
            $file_name = 'KENYA_'.Str::random(10).'.zip';
            $file->storeAs('public/'.$route, $file_name);

            $driver_ruta->rute = $route.'/'.$file_name;
        }

        $driver_ruta->save();
        DB::commit();

        return response()->json([
            'type' => 'success',
            'title' => 'CORRECTO:',
            'message' => 'La ruta fue actualizada correctamente.',
        ]);
    } catch (\Throwable $th) {
        DB::rollBack();

        return response()->json([
            'type' => 'danger',
            'title' => 'ERROR:',
            'message' => 'Error al actualizar: '.$th->getMessage(),
        ], 500);
    }
}

    public function delete(Request $request, Ruta  $drivers_ruta)
    {
        try {

            DB::beginTransaction();

            $driver_ruta = Ruta::findOrFail($request->id);
            $driver_ruta -> delete();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'La Ruta se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar la Ruta, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
