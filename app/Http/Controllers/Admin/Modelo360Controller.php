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
        $maxUploads = (int) ini_get('max_file_uploads');
        $request->validate([
            'modelo_id' => 'required|exists:modelos,id',
            'imagenes' => 'required|array|min:1|max:' . $maxUploads,
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ], [
            'imagenes.max' => 'El servidor permite subir máximo ' . $maxUploads . ' imágenes a la vez. Divide tu subida en lotes.',
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
        // Todas se guardan como .jpg para que la vista 360 funcione sin importar
        // la extensión original del archivo subido (png, webp, jpg, etc.).
        // Sin esto, la vista intenta cargar N.jpg pero el archivo es N.png → 404.
        foreach ($imagenes as $i => $img) {
            $filename = ($i + 1) . '.jpg';
            $this->guardarComoJpg($img, $path, $filename);
        }

        return back()->with('success', 'Las ' . $total . ' imágenes 360 se subieron correctamente al modelo seleccionado.');
    }

    /**
     * Convierte la imagen subida a JPEG y la guarda en storage.
     * Usa GD (incluido en PHP). Si falla la conversión, cae al storeAs original.
     */
    private function guardarComoJpg($img, string $path, string $filename): void
    {
        $sourcePath = $img->getRealPath();
        $mime = $img->getMimeType();
        $imgResource = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
            'image/png'  => @imagecreatefrompng($sourcePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : null,
            default      => null,
        };

        if (!$imgResource) {
            // Fallback: guardar con extensión original
            $img->storeAs($path, $filename);
            return;
        }

        // JPEG no soporta transparencia → rellenar con blanco
        if (in_array($mime, ['image/png', 'image/webp'], true)) {
            $width  = imagesx($imgResource);
            $height = imagesy($imgResource);
            $canvas = imagecreatetruecolor($width, $height);
            imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
            imagecopy($canvas, $imgResource, 0, 0, 0, 0, $width, $height);
            imagedestroy($imgResource);
            $imgResource = $canvas;
        }

        $fullPath = Storage::path($path . '/' . $filename);
        imagejpeg($imgResource, $fullPath, 90);
        imagedestroy($imgResource);
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
