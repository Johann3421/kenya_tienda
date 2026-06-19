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
        $total = count($imagenes);

        // Numerar 1..N preservando el orden en que el navegador las envía.
        // Usar índice secuencial (no el nombre del archivo) evita que dos
        // archivos con el mismo nombre se sobreescriban entre sí.
        foreach ($imagenes as $i => $img) {
            $extension = $img->getClientOriginalExtension();
            $filename = ($i + 1) . '.' . $extension;
            $img->storeAs($path, $filename);
        }

        return back()->with('success', 'Las ' . $total . ' imágenes 360 se subieron correctamente al modelo seleccionado.');
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
