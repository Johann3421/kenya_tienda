<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.categorias.index');
    }

    public function buscar(Request $request)
    {

        $categoria = Categoria::with('getModelo')->where('nombre', 'LIKE', "%{$request->search}%")
            ->orWhereRaw("id::text LIKE ?", ["%{$request->search}%"]);

        $categoria = $categoria->paginate(10);

        return [
            'pagination' => [
                'total' => $categoria->total(),
                'current_page' => $categoria->currentPage(),
                'per_page' => $categoria->perPage(),
                'last_page' => $categoria->lastPage(),
                'from' => $categoria->firstItem(),
                'to' => $categoria->lastPage(),
                'index' => ($categoria->currentPage() - 1) * $categoria->perPage(),
            ],
            'categoria' => $categoria
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string',
            'estado' => 'required|string',
        ]);

        try {

            DB::beginTransaction();

            $categoria = new Categoria();
            $categoria->nombre = Str::upper($request->nombre);
            $categoria->activo = $request->estado;
            $categoria->save();

            $route = 'CATEGORIAS/'.$categoria->id;

            if ($request->hasFile('imagen')) {
                $file_1 = $request->file('imagen');
                $extension_1 = $file_1->extension();
                $file_name_1 = 'IMG1_'.Str::random(10).'.'.$extension_1;

                $savedPath = Storage::disk('public')->putFileAs($route, $file_1, $file_name_1);
                
                if (!$savedPath || !Storage::disk('public')->exists($savedPath)) {
                    throw new \Exception('Error al guardar la imagen en el almacenamiento');
                }

                $categoria->img_cat = $savedPath;
                $categoria->save();
            }

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'La Categoría se guardó correctamente.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th->getMessage() . ' Ocurrió un error al guardar la Categoria, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id'     => 'required|int',
            'nombre' => 'required|string',
            'estado' => 'required|string',
        ]);

        try {

            DB::beginTransaction();

            $categoria = Categoria::findOrFail($request->id);
            $categoria->nombre = Str::upper($request->nombre);
            $categoria->activo = $request->estado;

            $route = 'CATEGORIAS/'.$categoria->id;

            if ($request->hasFile('imagen')) {
                // Delete old image if exists
                if ($categoria->img_cat && Storage::disk('public')->exists($categoria->img_cat)) {
                    Storage::disk('public')->delete($categoria->img_cat);
                }

                $file = $request->file('imagen');
                $extension = $file->extension();
                $file_name = 'IMG_'.Str::random(10).'.'.$extension;

                // Save new image and verify it was saved
                $savedPath = Storage::disk('public')->putFileAs($route, $file, $file_name);
                
                if (!$savedPath || !Storage::disk('public')->exists($savedPath)) {
                    throw new \Exception('Error al guardar la imagen en el almacenamiento');
                }

                $categoria->img_cat = $savedPath;
            }

            $categoria->save();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'La Categoría se ha actualizado correctamente.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  $th->getMessage() . ' Ocurrió un error al actualizar la Categoria, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id'     => 'required|int',
        ]);

        try {

            DB::beginTransaction();

            $categoria = Categoria::findOrFail($request->id);

            $route = 'CATEGORIAS/'.$categoria->id;

            if ($categoria->img_cat) {
                Storage::delete('public/'.$categoria->img_cat);

                Storage::deleteDirectory('public/'.$route);
            }

            $categoria->delete();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'La Categoria se elimino correctamente.',
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar la Categoria, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
}
