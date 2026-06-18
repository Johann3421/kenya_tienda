<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modelo;
use Illuminate\Support\Facades\Storage;

class Modelo360Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $modelos = Modelo::orderBy('descripcion', 'asc')->get();
        
        // Determinar cuáles tienen vista 360 subida
        foreach($modelos as $mod) {
            $path = 'public/modelos_360/' . $mod->id;
            if(Storage::exists($path)) {
                $files = Storage::files($path);
                $mod->has_360 = count($files) > 0;
                $mod->count_360 = count($files);
            } else {
                $mod->has_360 = false;
                $mod->count_360 = 0;
            }
        }

        return view('sistema.administrador.modelos_360.index', compact('modelos'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'modelo_id' => 'required|exists:modelos,id',
            'imagenes' => 'required|array|min:1',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $modeloId = $request->modelo_id;
        $path = 'public/modelos_360/' . $modeloId;

        // Limpiar el directorio si ya existe para evitar mezcla de archivos viejos y nuevos
        if (Storage::exists($path)) {
            Storage::deleteDirectory($path);
        }
        Storage::makeDirectory($path);

        $imagenes = $request->file('imagenes');
        
        // Es importante el orden. Si las suben seleccionando todo, el OS normalmente las manda ordenadas por nombre.
        // Las renombramos a 1.jpg, 2.jpg... basándonos en el orden que llegan (que idealmente debería ser el correcto si seleccionan todas juntas)
        // Alternativamente, el cliente puede subir las imágenes ya con nombres tipo 1.jpg, 2.jpg. 
        // Para asegurar, simplemente las numeraremos 1 al N.
        
        // Primero, intentamos ordenar los archivos por su nombre original para asegurar secuencia
        $filesArray = [];
        foreach($imagenes as $img) {
            $filesArray[$img->getClientOriginalName()] = $img;
        }
        ksort($filesArray, SORT_NATURAL);

        $i = 1;
        foreach ($filesArray as $originalName => $img) {
            $extension = $img->getClientOriginalExtension();
            $filename = $i . '.' . $extension;
            $img->storeAs($path, $filename);
            $i++;
        }

        return back()->with('success', 'Las ' . count($imagenes) . ' imágenes 360 se subieron correctamente al modelo seleccionado.');
    }

    public function delete($id)
    {
        $path = 'public/modelos_360/' . $id;
        if (Storage::exists($path)) {
            Storage::deleteDirectory($path);
        }
        return back()->with('success', 'Vista 360 eliminada correctamente del modelo.');
    }
}
