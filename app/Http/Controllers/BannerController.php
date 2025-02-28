<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Banner;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.web.banners');
    }

    public function buscar(Request $request)
    {
        $banners = Banner::paginate(20);

        return [
            'pagination' => [
                'total' => $banners->total(),
                'current_page' => $banners->currentPage(),
                'per_page' => $banners->perPage(),
                'last_page' => $banners->lastPage(),
                'from' => $banners->firstItem(),
                'to' => $banners->lastPage(),
                'index' => ($banners->currentPage()-1)*$banners->perPage(),
            ],
            'banners' => $banners
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'imagen'     => 'required',
        ]);

        try {

            DB::beginTransaction();

            $banner = new Banner();
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $extension = $file->extension();
                $file_name = Str::random(10).'.'.$extension;

                Storage::putFileAs('public/BANNERS', $file, $file_name);
                $banner->imagen = 'BANNERS/'.$file_name;
            }
            $banner->titulo = $request->titulo;
            $banner->titulo_color = $request->titulo_color;
            $banner->descripcion = $request->descripcion;
            $banner->contenido = $request->contenido;
            $banner->link = $request->link;
            $banner->link_nombre = $request->link_nombre;
            $banner->save();
                
            DB::commit();
    
            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Banner se guardo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrió un error al guardar el Banner: ' . $th->getMessage(),
            ];
        }
    }

    public function update(Request $request)
    {
       try {

            DB::beginTransaction();

            $banner = Banner::findOrFail($request->id);
            if ($request->hasFile('imagen')) {
                $anterior = $banner->imagen;
                $file = $request->file('imagen');
                $extension = $file->extension();
                $file_name = Str::random(10).'.'.$extension;

                Storage::putFileAs('public/BANNERS', $file, $file_name);
                Storage::delete('public/'.$anterior);
                $banner->imagen = 'BANNERS/'.$file_name;
            }
            $banner->titulo = $request->titulo;
            $banner->titulo_color = $request->titulo_color;
            $banner->descripcion = $request->descripcion;
            $banner->contenido = $request->contenido;
            $banner->link = $request->link;
            $banner->link_nombre = $request->link_nombre;
            $banner->activo = $request->activo;
            $banner->save();
                
            DB::commit();
    
            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Banner se actualizó correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar el Banner, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $banner = Banner::findOrFail($request->id);
            if ($banner->imagen) {
                Storage::delete('public/'.$banner->imagen);
            }
            $banner->delete();
            
            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Banner se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Banner, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
