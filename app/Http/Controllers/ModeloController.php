<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Modelo;
use App\Models\Categoria;

class ModeloController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.modelos.index');
    }

    public function buscar(Request $request)
    {
        $term = trim($request->search);

        if (!Schema::hasColumn('modelos', 'stock_vigente')) {
            Schema::table('modelos', function (Blueprint $table) {
                $table->integer('stock_vigente')->default(20)->nullable();
            });
        }

        $modelos = Modelo::join('categorias AS cat', 'modelos.categoria_id', '=', 'cat.id')
            ->select(
                'modelos.id',
                'modelos.descripcion',
                'cat.nombre AS categoria_descripcion',
                'modelos.activo',
                'cat.id AS categoria_id',
                'modelos.img_mod',
                DB::raw("COALESCE(modelos.stock_vigente, 20) AS stock_vigente"),
                DB::raw("(SELECT COUNT(p.id) FROM productos p WHERE p.modelo_id = modelos.id) AS total_productos")
            )
            ->where(function ($q) use ($term) {
                if (!empty($term)) {
                    $q->where('modelos.descripcion', 'ILIKE', "%{$term}%")
                      ->orWhereRaw("modelos.id::text LIKE ?", ["%{$term}%"]);
                }
            })
            ->orderBy('modelos.id', 'DESC')
            ->paginate(10);

        return [
            'pagination' => [
                'total' => $modelos->total(),
                'current_page' => $modelos->currentPage(),
                'per_page' => $modelos->perPage(),
                'last_page' => $modelos->lastPage(),
                'from' => $modelos->firstItem(),
                'to' => $modelos->lastPage(),
                'index' => ($modelos->currentPage() - 1) * $modelos->perPage(),
            ],
            'modelos' => $modelos
        ];
    }

    public function buscar_categorias(Request $request)
    {

        $categorias = Categoria::select('id','nombre')
            ->where('nombre', 'LIKE', '%'.$request->search.'%')
            ->orWhereRaw("id::text LIKE ?", ['%'.$request->search.'%'])
            ->get();

        return [
            'categorias' => $categorias
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'categoria_id' => 'required|int',
            'descripcion' => 'required|string',
            'estado' => 'required|string',
        ]);

        try {

            DB::beginTransaction();

            $modelo = new Modelo();
            $modelo->categoria_id = $request->categoria_id;
            $modelo->descripcion = Str::upper($request->descripcion);
            $modelo->activo = Str::upper($request->estado);
            $modelo->save();

            $route = 'MODELOS/'.$modelo->id; // relative path inside the public disk

            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');
                $extension = $file->extension();
                $file_name = 'IMG_'.Str::random(10).'.'.$extension;

                // Use explicit public disk to avoid double "public/public" if FILESYSTEM_DRIVER changes
                Storage::disk('public')->putFileAs($route, $file, $file_name);
                $modelo->img_mod = $route.'/'.$file_name; // store path relative to disk root

                $modelo->save();
            }

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'El Modelo se guardo correctamente.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al guardar la Modelo, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
        return [
                    'type'     =>  'success',
                    'title'    =>  'CORRECTO: ',
                    'message'  =>  'El Modelo se guardo correctamente.',
                ];
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id'           => 'required|int',
            'categoria_id' => 'required|int',
            'descripcion'  => 'required|string',
            'estado'       => 'required|string',
        ]);

        try {

            DB::beginTransaction();

            $modelo = Modelo::findOrFail($request->id);
            $modelo->categoria_id = $request->categoria_id;
            $modelo->descripcion = Str::upper($request->descripcion);
            $modelo->activo = $request->estado;

            $route = 'MODELOS/'.$modelo->id; // relative path inside the public disk

            if ($request->hasFile('imagen')) {
                // Delete previous file using the public disk
                if ($modelo->img_mod) {
                    Storage::disk('public')->delete($modelo->img_mod);
                }

                $file = $request->file('imagen');
                $extension = $file->extension();
                $file_name = 'IMG_'.Str::random(10).'.'.$extension;

                Storage::disk('public')->putFileAs($route, $file, $file_name);
                $modelo->img_mod = $route.'/'.$file_name;
            }

            $modelo->update();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'La Categoría se han actualizado correctamente.',
            ];
        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al actualizar la Modelo, intente nuevamente o contacte al Administrador del Sistema.'
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

            $modelo = Modelo::findOrFail($request->id);

            $route = 'MODELOS/'.$modelo->id;

            if ($modelo->img_mod) {
                Storage::disk('public')->delete($modelo->img_mod);
                Storage::disk('public')->deleteDirectory($route);
            }

            $modelo->delete();

            DB::commit();

            return [
                'type'     =>  'success',
                'title'    =>  'CORRECTO: ',
                'message'  =>  'La Modelo se elimino correctamente.',
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'type'     =>  'danger',
                'title'    =>  'ERROR: ',
                'message'  =>  'Ocurrio un error al eliminar la Modelo, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }
    public function getModelosConProductos()
    {
        $modelos = Modelo::with(['Productos' => function($query) {
            $query->where('pagina_web', 'SI')->noSuspendido();
        }])->get();

        return response()->json([
            'modelos' => $modelos,
            'dimensiones' => [
                'ancho' => 350,
                'alto' => 300
            ]
        ]);
    }

    public function actualizarStock(Request $request)
    {
        $this->validate($request, [
            'operador'     => 'required|string|in:>=,<=,=',
            'stock_filtro' => 'required|numeric|min:0',
            'nuevo_stock'  => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Asegurar que la columna stock_vigente existe
            if (!Schema::hasColumn('modelos', 'stock_vigente')) {
                Schema::table('modelos', function (Blueprint $table) {
                    $table->integer('stock_vigente')->default(20)->nullable();
                });
            }

            $op = $request->operador;
            $filtro = (int) $request->stock_filtro;
            $nuevo = (int) $request->nuevo_stock;
            $modeloId = $request->input('modelo_id', 'ALL');

            // Si es un modelo específico, actualizar directamente sin condición de stock
            if ($modeloId !== 'ALL' && !empty($modeloId)) {
                DB::table('modelos')->where('id', $modeloId)->update(['stock_vigente' => $nuevo]);
                DB::table('productos')->where('modelo_id', $modeloId)->update(['stock_inicial' => $nuevo]);
                $afectados = 1;
            } else {
                // Para "Todos los modelos", filtrar por operador
                $modelosQuery = DB::table('modelos');

                if ($op === '>=') {
                    $modelosQuery->where(function($q) use ($filtro) {
                        $q->where('stock_vigente', '>=', $filtro)
                          ->orWhereNull('stock_vigente');
                    });
                } elseif ($op === '<=') {
                    $modelosQuery->where(function($q) use ($filtro) {
                        $q->where('stock_vigente', '<=', $filtro)
                          ->orWhereNull('stock_vigente');
                    });
                } else {
                    $modelosQuery->where(function($q) use ($filtro) {
                        $q->where('stock_vigente', '=', $filtro)
                          ->orWhereNull('stock_vigente');
                    });
                }

                $modelosIds = $modelosQuery->pluck('id')->toArray();
                $afectados = count($modelosIds);

                if (!empty($modelosIds)) {
                    DB::table('modelos')->whereIn('id', $modelosIds)->update(['stock_vigente' => $nuevo]);
                    DB::table('productos')->whereIn('modelo_id', $modelosIds)->update(['stock_inicial' => $nuevo]);
                }
            }

            DB::commit();

            return response()->json([
                'type'    => 'success',
                'title'   => 'CORRECTO: ',
                'message' => "Stock vigente actualizado a {$nuevo} en {$afectados} modelo(s)."
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'type'    => 'danger',
                'title'   => 'ERROR: ',
                'message' => 'Ocurrió un error al actualizar el stock: ' . $th->getMessage()
            ]);
        }
    }
}
