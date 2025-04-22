<?php

namespace App\Http\Controllers\Admin;

use App\Models\BannerMedio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BannerMedioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $banners = BannerMedio::orderBy('orden')->get();

        if ($request->ajax()) {
            return response()->json($banners);
        }

        return view('sistema.administrador.configuracion.index', compact('banners'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'url_destino' => 'required|url',
            'activo' => 'boolean',
            'orden' => 'integer',
            'posicion' => 'string|in:medio,superior,inferior'
        ]);

        // Guardar la imagen directamente en public/banners
        $image = $request->file('imagen');
        $imageName = time().'_'.Str::slug($validated['titulo'] ?? 'banner').'.'.$image->getClientOriginalExtension();

        // Crear directorio si no existe
        if (!File::exists(public_path('banners'))) {
            File::makeDirectory(public_path('banners'), 0755, true);
        }

        // Mover la imagen a public/banners
        $image->move(public_path('banners'), $imageName);
        $imagePath = 'banners/'.$imageName;

        // Crear el banner
        $banner = BannerMedio::create([
            'titulo' => $validated['titulo'],
            'imagen_path' => $imagePath,
            'url_destino' => $validated['url_destino'],
            'activo' => $validated['activo'] ?? true,
            'orden' => $validated['orden'] ?? 0,
            'posicion' => $validated['posicion'] ?? 'medio'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Banner creado correctamente',
            'banner' => $banner
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BannerMedio $bannerMedio)
    {
        return response()->json($bannerMedio);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BannerMedio $bannerMedio)
{
    $validated = $request->validate([
        'titulo' => 'nullable|string|max:255',
        'imagen' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        'url_destino' => 'required|url',
        'orden' => 'integer'
    ]);

    if ($request->hasFile('imagen')) {
        // Eliminar imagen anterior
        if (file_exists(public_path($bannerMedio->imagen_path))) {
            unlink(public_path($bannerMedio->imagen_path));
        }

        // Guardar nueva imagen
        $imageName = time().'_'.Str::slug($request->titulo ?? 'banner').'.'.$request->imagen->extension();
        $request->imagen->move(public_path('banners'), $imageName);
        $validated['imagen_path'] = 'banners/'.$imageName;
    }

    $bannerMedio->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Banner actualizado correctamente'
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BannerMedio $bannerMedio)
    {
        try {
            // Eliminar la imagen del directorio public
            if (File::exists(public_path($bannerMedio->imagen_path))) {
                File::delete(public_path($bannerMedio->imagen_path));
            }

            // Eliminar el registro
            $bannerMedio->delete();

            return redirect()->route('sistema.administrador.configuracion.index')
                   ->with('success', 'Banner eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()
                   ->with('error', 'Error al eliminar el banner: '.$e->getMessage());
        }
    }
}
