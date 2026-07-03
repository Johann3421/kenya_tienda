<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\OfertaWeb;

class OfertasWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.web.ofertas');
    }

    public function buscar(Request $request)
    {
        $ofertas = OfertaWeb::paginate(20);

        return [
            'pagination' => [
                'total'        => $ofertas->total(),
                'current_page' => $ofertas->currentPage(),
                'per_page'     => $ofertas->perPage(),
                'last_page'    => $ofertas->lastPage(),
                'from'         => $ofertas->firstItem(),
                'to'           => $ofertas->lastPage(),
                'index'        => ($ofertas->currentPage() - 1) * $ofertas->perPage(),
            ],
            'ofertas' => $ofertas,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'imagen' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $oferta = new OfertaWeb();
            if ($request->hasFile('imagen')) {
                $file      = $request->file('imagen');
                $extension = $file->extension();
                $file_name = Str::random(10) . '.' . $extension;

                Storage::putFileAs('public/OFERTAS', $file, $file_name);
                $oferta->imagen = 'OFERTAS/' . $file_name;
            }
            $oferta->titulo      = $request->titulo;
            $oferta->descripcion = $request->descripcion;
            $oferta->color_fondo = $request->color_fondo;
            $oferta->link        = $request->link;
            $oferta->save();

            DB::commit();

            return [
                'type'    => 'success',
                'title'   => 'CORRECTO: ',
                'message' => 'La Oferta se guardó correctamente.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'    => 'danger',
                'title'   => 'ERROR: ',
                'message' => 'Ocurrió un error al guardar la Oferta: ' . $th->getMessage(),
            ];
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $oferta = OfertaWeb::findOrFail($request->id);
            if ($request->hasFile('imagen')) {
                $anterior  = $oferta->imagen;
                $file      = $request->file('imagen');
                $extension = $file->extension();
                $file_name = Str::random(10) . '.' . $extension;

                Storage::putFileAs('public/OFERTAS', $file, $file_name);
                Storage::delete('public/' . $anterior);
                $oferta->imagen = 'OFERTAS/' . $file_name;
            }
            $oferta->titulo      = $request->titulo;
            $oferta->descripcion = $request->descripcion;
            $oferta->color_fondo = $request->color_fondo;
            $oferta->link        = $request->link;
            $oferta->activo      = $request->activo;
            $oferta->save();

            DB::commit();

            return [
                'type'    => 'success',
                'title'   => 'CORRECTO: ',
                'message' => 'La Oferta se actualizó correctamente.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'    => 'danger',
                'title'   => 'ERROR: ',
                'message' => 'Ocurrió un error al actualizar la Oferta, intente nuevamente.',
            ];
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();

            $oferta = OfertaWeb::findOrFail($request->id);
            if ($oferta->imagen) {
                Storage::delete('public/' . $oferta->imagen);
            }
            $oferta->delete();

            DB::commit();

            return [
                'type'    => 'success',
                'title'   => 'CORRECTO: ',
                'message' => 'La Oferta se eliminó correctamente.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'    => 'danger',
                'title'   => 'ERROR: ',
                'message' => 'Ocurrió un error al eliminar la Oferta, intente nuevamente.',
            ];
        }
    }
}
