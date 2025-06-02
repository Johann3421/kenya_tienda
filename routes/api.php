<?php

use App\Http\Controllers\Api\BannerMedioApiController;
use App\Http\Controllers\Api\EspecificacionApiController;
use App\Http\Controllers\Api\ModeloApiController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SoporteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// routes/web.php o mejor: routes/api.php

Route::post('/upload-pdf', [SoporteController::class, 'uploadPdf'])->middleware('auth:sanctum');

Route::get('productos', function () {
    return App\Producto::select('id', 'nombre', 'modelo_id')->get();
});
Route::post('productos/especificaciones/import-multiple', [ProductoController::class, 'importMultipleEspecificaciones']);
Route::get('productos/{id}', [ProductoApiController::class, 'show']);
Route::get('modelos', function () {
    return App\Modelo::select('id', 'descripcion')->get();
});
Route::get('modelos/{id}', [ModeloApiController::class, 'show']);
Route::get('modelos/categoria/{categoria_id}', [ModeloApiController::class, 'porCategoria']);

Route::get('especificaciones', [EspecificacionApiController::class, 'index']);
Route::get('especificaciones/{id}', [EspecificacionApiController::class, 'show']);
Route::get('especificaciones/producto/{producto_id}', [EspecificacionApiController::class, 'porProducto']);

Route::get('banners', [BannerMedioApiController::class, 'index']);
Route::get('banners/{id}', [BannerMedioApiController::class, 'show']);
Route::get('banners/posicion/{posicion}', [BannerMedioApiController::class, 'porPosicion']);
Route::post('/productos/especificaciones/import', [ProductoController::class, 'importarEspecificaciones']);
