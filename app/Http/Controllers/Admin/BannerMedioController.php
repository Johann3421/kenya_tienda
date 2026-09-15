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

        // Guardar la imagen optimizada a WebP en storage/banners
        $image = $request->file('imagen');
        $stored = \App\Services\ImageOptimizerService::storeOptimized(
            $image,
            'banners',
            1400,
            85,
            Str::slug($validated['titulo'] ?? 'banner')
        );
        $imagePath = 'storage/'.$stored;

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
        if ($bannerMedio->imagen_path) {
            \App\Services\ImageOptimizerService::deleteIfExists($bannerMedio->imagen_path);
        }

        // Guardar nueva imagen optimizada
        $stored = \App\Services\ImageOptimizerService::storeOptimized(
            $request->file('imagen'),
            'banners',
            1400,
            85,
            Str::slug($request->titulo ?? 'banner')
        );
        $validated['imagen_path'] = 'storage/'.$stored;
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
            // Eliminar la imagen
            if ($bannerMedio->imagen_path) {
                \App\Services\ImageOptimizerService::deleteIfExists($bannerMedio->imagen_path);
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
