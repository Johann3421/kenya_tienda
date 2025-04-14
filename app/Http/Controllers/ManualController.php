<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manual;
use App\Producto;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class manualController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.manuales.index');
    }

    public function buscar(Request $request)
    {
        // $categorias = Categoria::where('nombres', 'LIKE', "%{$request->search}%")
        // ->orWhere('id', 'LIKE', "%{$request->search}%");

        $manuales = Manual::join('productos', 'productos.id', '=',"manual.producto_id")
        ->select("manual.id", "productos.id as producto_id", "productos.nombre as producto_descripcion","manual.descripcion", "manual.link", "manual.activo")
        ->where('manual.descripcion', 'LIKE', '%'.$request->search.'%')
        ->orWhere('manual.id', 'LIKE', '%'.$request->search.'%');

        $manuales = $manuales -> paginate(10);

        return [
            'pagination' => [
                'total' => $manuales->total(),
                'current_page' => $manuales->currentPage(),
                'per_page' => $manuales->perPage(),
                'last_page' => $manuales->lastPage(),
                'from' => $manuales->firstItem(),
                'to' => $manuales->lastPage(),
                'index' => ($manuales->currentPage() - 1) * $manuales->perPage(),
            ],
            'manuales' => $manuales,
        ];
    }

    public function auto_buscar_producto(Request $request)
    {

        $producto = Producto::select('id','nombre')
            ->where('nombre', 'LIKE', '%'.$request->search.'%')
            ->orWhere('id', 'LIKE', '%'.$request->search.'%')
            ->get();

        return [
            'producto' => $producto
        ];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'producto_id'          => 'required|int',
            'descripcion'          => 'required',
            'estado'               => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $manual = new Manual();
            $manual ->  producto_id = $request->producto_id;
            $manual ->  descripcion = Str::upper($request->descripcion);
            $manual ->  activo      = $request->estado;

            $route = 'PDF-MANUALES/'.$manual->id;

            if ($request->hasFile('pdf_manual')) {
                $file = $request->file('pdf_manual');
                $extension = $file->extension();
                $file_name = 'PDF_'.Str::random(10).'.'.$extension;

                Storage::putFileAs('public/'.$route, $file, $file_name);
                $manual->link = $route.'/'.$file_name;
            }

            $manual->save();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Manual se guardo correctamente.'
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar el Manual, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Manual  $manuales
     * @return \Illuminate\Http\Response
     */
    public function show(Manual $manuales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Manual  $manuales
     * @return \Illuminate\Http\Response
     */
    public function edit(Manual $manuales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Manual  $manuales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manual $manuales)
    {

        $request->validate([
            'id'                   => 'required|int',
            'producto_id'          => 'required|int',
            'descripcion'          => 'required',
            'estado'               => 'required|string',
        ]);

        try {

            DB::beginTransaction();

            $manual = Manual::findOrFail($request->id);
            $manual ->  producto_id = $request->producto_id;
            $manual ->  descripcion = $request->descripcion;
            $manual ->  activo      = $request->estado;

            $route = 'PDF-MANUALES/'.$manual->id;

            if ($request->hasFile('pdf_manual')) {
                Storage::delete('public/'.$manual->link);

                $file = $request->file('pdf_manual');
                $extension = $file->extension();
                $file_name = 'PDF_'.Str::random(10).'.'.$extension;

                Storage::putFileAs('public/'.$route, $file, $file_name);
                $manual->link = $route.'/'.$file_name;
            }

            $manual -> update();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'Los datos del Manual se actualizaron correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar los datos del Manual, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Manual  $manuales
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Manual $manuales)
    {
        try {

            DB::beginTransaction();

            $manual = Manual::findOrFail($request->id);
            $route2 = 'PDF-MANUALES/'.$manual->id;

            if ($manual->link) {
                Storage::delete('public/'.$manual->link);

                Storage::deleteDirectory('public/'.$route2);
            }
            $manual -> delete();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Manual se elimino correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar el Manual, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
