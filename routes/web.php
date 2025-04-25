<?php

use App\Http\Controllers\Admin\BannerMedioController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReclamacionController;
use App\Http\Controllers\Sistema\AsideController;
use App\Http\Controllers\SoporteController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/', function () {
    $categorias = App\Models\Categoria::with('getModelo')->where('activo', 'SI')->orderBy('nombre', 'ASC')->get();
    $modelo = App\Modelo::with('getCat')->where('activo', 'SI')->get();
    // $productos = App\Producto::orderBy('nombre', 'ASC')->where('pagina_web', 'SI')->get();
    $productos = App\Producto::with('getModelo')->orderBy('nombre', 'ASC')->where('pagina_web', 'SI')->get();
    $ofertas = App\Producto::orderBy('nombre', 'ASC')->where('pagina_web', 'SI')->where('precio_anterior', '!=', null)->get();
    $novedades = App\Producto::orderBy('created_at', 'DESC')->where('pagina_web', 'SI')->where('precio_anterior', null)->paginate(16);
    $banners = App\Models\Banner::where('activo', 'SI')->get();
    return view('welcome', compact('categorias', 'productos', 'ofertas', 'novedades', 'banners', 'modelo'));
    //return $productos;
});
Route::get('catalogo/{id}/detallemod', 'CatalogoController@detallemod')->name('detallemod');
Route::post('catalogo/{id}/buscar', 'CatalogoController@buscar');
Route::post('catalogo/{id}/categoria', 'CatalogoController@categoria');


Route::get('catalogo', 'CatalogoController@index')->name('catalogo');
Route::post('catalogo/categoria', 'CatalogoController@categoria');
Route::post('catalogo/buscar', 'CatalogoController@buscar');


//Landing Productos
Route::get('lproductos', 'LandingProductos@index')->name('lproductos');
Route::post('lproductos/categoria', 'LandingProductos@categoria');
Route::post('lproductos/buscar', 'LandingProductos@buscar');

//Auth::routes(['register' => false, ]);
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/api/dni/{numero}', 'ApiController@dni');
Route::get('/api/ruc/{numero}', 'ApiController@ruc');
Route::get('/api/proveedor/{numero}', 'ApiController@proveedor');

// --------------------- INICIO --------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/perfil', 'HomeController@index')->name('perfil');
});


// --------------------- SOPORTE --------------------------------
Route::group(['middleware' => ['can:servicio_tecnico']], function () {
    Route::get('/soporte', 'SoporteController@index')->name('soporte');
    Route::get('/soporte/buscar', 'SoporteController@buscar');
    Route::post('/soporte/store', 'SoporteController@store');
    Route::post('/soporte/update', 'SoporteController@update');
    Route::post('/soporte/delete', 'SoporteController@delete');
    Route::get('/soporte/recibo/{numero}', [SoporteController::class, 'recibo'])
     ->name('soporte.recibo');
    Route::post('/soporte/detalle/add', 'SoporteController@detalleAdd');
    Route::post('/soporte/detalle/delete', 'SoporteController@detalleDelete');
    Route::get('/soporte/codigo-barra', 'SoporteController@codigoBarra');
});
Route::post('/consultar/soporte', 'ConsultarController@soporte')->name('consultar.soporte');
Route::post('/consultar/soporte/buscar', 'ConsultarController@soporte_buscar');
Route::post('/consultar/pedido', 'ConsultarController@pedido')->name('consultar.pedido');
Route::post('/consultar/pedido/buscar', 'ConsultarController@pedido_buscar');
Route::get('/consultar/garantia', 'ConsultarController@garantia')->name('consultar.garantia');
Route::post('/consultar/garantia/buscar', 'ConsultarController@garantia_buscar');
Route::get('/consultar/garantia/{serie}', 'ConsultarController@buscar_serie');
// --------------------- Consultar --------------------------------


//-------------------------------- PEDIDOS -------------------------------
Route::group(['middleware' => ['can:pedidos']], function () {
    Route::get('/pedidos', 'PedidoController@inicio')->name('pedidos');
    Route::get('/pedidos/buscar', 'PedidoController@buscar');
    Route::post('/pedidos/store', 'PedidoController@store');
    Route::post('/pedidos/update', 'PedidoController@update');
    Route::post('/pedidos/delete', 'PedidoController@delete');
    Route::post('/pedidos/detalle/add', 'PedidoController@detalle_add');
    Route::post('/pedidos/detalle/delete', 'PedidoController@detalle_delete');
    Route::get('/pedidos/recibo/{numero}', [PedidoController::class, 'recibo'])
     ->name('pedidos.recibo');

    Route::post('/pedidos/proveedor/delete', 'PedidoController@proveedor_delete');
    // Route::get('/nuevo', 'PedidoController@nuevo')->name('pedidos.nuevo');
    // Route::get('frm-nuevo-pedido', 'PedidoController@frmNuevoPedido');
    // Route::get('buscar-series', 'PedidoController@buscarSeries');
    // Route::get('buscar-numeracion', 'PedidoController@buscarNumeracion');
    // Route::get('buscar-productos', 'PedidoController@buscarProductos');
    // Route::get('buscar-proveedor', 'PedidoController@buscarProveedor');
    // Route::post('guardar-proveedor', 'PedidoController@guardarProveedor');
    // Route::post('guardar', 'PedidoController@guardar');
    // Route::get('todos', 'PedidoController@todos');
    // Route::get('mdl-mostrar-recibo', 'PedidoController@mdlMostrarRecibo');
});

// --------------------- VENTAS --------------------------------
Route::group(['middleware' => ['can:ventas']], function () {
    Route::get('/ventas', 'VentaController@index')->name('ventas');
    Route::post('/ventas/buscar', 'VentaController@buscar');
    Route::post('/ventas/store', 'VentaController@store');
    Route::post('/ventas/update', 'VentaController@update');
    Route::post('/ventas/delete', 'VentaController@delete');
    Route::get('/ventas/recibo/{numero}', 'VentaController@recibo');
    Route::post('/ventas/detalle/add', 'VentaController@detalleAdd');
    Route::post('/ventas/detalle/delete', 'VentaController@detalleDelete');
});

// ----------------------- PRODUCTOS --------------------------------
Route::group(['middleware' => ['auth', 'can:productos']], function () {
    // Rutas existentes con sintaxis coherente
    Route::get('/producto', [ProductoController::class, 'inicio'])->name('productos');
    Route::post('/producto/buscar', [ProductoController::class, 'buscar']);
    Route::post('/producto/store', [ProductoController::class, 'store']);
    Route::post('/producto/update', [ProductoController::class, 'update']);
    Route::post('/producto/web', [ProductoController::class, 'web']);
    Route::post('/producto/delete', [ProductoController::class, 'delete']);
    Route::post('/producto/duplicar', [ProductoController::class, 'duplicar']);

    Route::get('/producto/{producto}/especificaciones', [ProductoController::class, 'getEspecificaciones']);
    Route::post('/producto/{producto}/especificaciones', [ProductoController::class, 'agregarEspecificacion'])
         ->name('productos.especificaciones.store');
    Route::post('/producto/{producto}/especificaciones/import', [ProductoController::class, 'importarEspecificacionesExcel'])
         ->name('productos.especificaciones.import');
    Route::delete('/producto/especificaciones/{especificacion}', [ProductoController::class, 'eliminarEspecificacion'])
         ->name('productos.especificaciones.destroy');
         Route::put('/producto/especificaciones/{id}/editar', [ProductoController::class, 'actualizarEspecificacion']);
});

Route::view('/quienes-somos', 'quienes-somos')->name('quienes.somos');
Route::view('/Catalogo', 'Catalogo')->name('catalogo');
Route::view('/Novedades', 'Novedades')->name('novedades');
Route::view('/Contactenos', 'Contactenos')->name('contactenos');
Route::view('/Reclamaciones', 'Reclamaciones')->name('reclamaciones');

Route::post('/reclamaciones/enviar', [ReclamacionController::class, 'enviar']);


Route::post('/productos/asignar-filtros', [ProductoController::class, 'asignarFiltrosGenerico'])
    ->name('productos.asignar-filtros.generico');
    Route::get('/filtrar-productos', [ProductoController::class, 'filtrarAjax'])->name('productos.filtrar');


// Ruta fuera del grupo de middleware (si no requiere autenticación)
Route::get('producto/{id}/detalle', [ProductoController::class, 'detalle'])->name('producto_detalle');

// ------------------------ PRODUCTO-DRIVERS --------------------------------
Route::group(['middleware' => ['can:producto_drivers']], function () {
    Route::get('/producto/drivers', 'ProductoDriversController@index')->name('producto/drivers');
    Route::post('/drivers/store', 'ProductoDriversController@store');
    Route::post('/drivers/buscar', 'ProductoDriversController@buscar');
    Route::post('/drivers/update', 'ProductoDriversController@update');
    Route::post('/drivers/delete', 'ProductoDriversController@delete');
    Route::post('/drivers/autobuscar', 'ProductoDriversController@auto_buscar_producto');
});

// ------------------------ PRODUCTO-DRIVERS-RUTAS --------------------------------
Route::group(['middleware' => ['can:producto_drivers_ruta']], function () {
    Route::get('/producto/drivers_ruta', 'Driver_rutaController@index')->name('producto/drivers_ruta');
    Route::post('/drivers_ruta/store', 'Driver_rutaController@store');
    Route::post('/drivers_ruta/buscar', 'Driver_rutaController@buscar');
    Route::post('/drivers_ruta/update', 'Driver_rutaController@update');
    Route::post('/drivers_ruta/delete', 'Driver_rutaController@delete');
    Route::post('/drivers_ruta/autobuscar', 'Driver_rutaController@auto_buscar_producto');
});

// ------------------------ DRIVERS --------------------------------
// Route::group(['middleware' => ['can:drivers']], function () {
//     Route::get('/producto/drivers', 'DriversController@index')->name('producto/drivers');
//     Route::post('/drivers/store', 'DriversController@store');
//     Route::post('/drivers/buscar', 'DriversController@buscar');
//     Route::post('/drivers/update', 'DriversController@update');
//     Route::post('/drivers/delete', 'DriversController@delete');
//     Route::post('/drivers/autobuscar', 'DriversController@auto_buscar_producto');
// });

// ------------------------ MANUALES --------------------------------
Route::group(['middleware' => ['can:manual']], function () {
    Route::get('/producto/manuales', 'ManualController@index')->name('producto/manuales');
    Route::post('/manuales/store', 'ManualController@store');
    Route::post('/manuales/buscar', 'ManualController@buscar');
    Route::post('/manuales/update', 'ManualController@update');
    Route::post('/manuales/delete', 'ManualController@delete');
    Route::post('/manuales/autobuscar', 'ManualController@auto_buscar_producto');
});

// ------------------------ GARANTÍAS --------------------------------
Route::group(['middleware' => ['can:garantia']], function () {
    Route::get('/producto/garantias', 'GarantiaController@index')->name('producto/garantias');
    Route::post('/garantias/store', 'GarantiaController@store');
    Route::post('/garantias/buscar', 'GarantiaController@buscar');
    Route::post('/garantias/update', 'GarantiaController@update');
    Route::post('/garantias/delete', 'GarantiaController@delete');
    Route::post('/garantias/autobuscar', 'GarantiaController@auto_buscar_producto');
});

// --------------------- CATEGORIAS --------------------------------
Route::group(['middleware' => ['can:categorias']], function () {
    Route::get('/producto/categorias', 'CategoriaController@index')->name('producto/categorias');
    Route::post('/categorias/buscar', 'CategoriaController@buscar');
    Route::post('/categorias/store', 'CategoriaController@store');

    Route::post('/categorias/update', 'CategoriaController@update');
    Route::post('/categorias/delete', 'CategoriaController@delete');
    Route::post('/categorias/autobuscar', 'CategoriaController@auto_buscar_producto');
});

//---------------------PROCESADOR------------------//
Route::get('/procesador', 'ProcesadorController@index')->name('procesador');
Route::post('/procesador/buscar', 'ProcesadorController@buscar');
Route::post('/procesador/store', 'ProcesadorController@store');

//---------------------Video------------------//
Route::get('/tarjetavideo', 'TarjetavideoController@index')->name('tarjetavideo');
Route::post('/tarjetavideo/buscar', 'TarjetavideoController@buscar');
Route::post('/tarjetavideo/store', 'TarjetavideoController@store');

//---------------------RAM------------------//
Route::get('/ram', 'RamController@index')->name('ram');
Route::post('/ram/buscar', 'RamController@buscar');
Route::post('/ram/store', 'RamController@store');

//---------------------ALMACENAMIENTO------------------//
Route::get('/almacenamiento', 'AlmacenamientoController@index')->name('almacenamiento');
Route::post('/almacenamiento/buscar', 'AlmacenamientoController@buscar');
Route::post('/almacenamiento/store', 'AlmacenamientoController@store');

//---------------------OFIMATICA------------------//
Route::get('/ofimatica', 'OfimaticaController@index')->name('ofimatica');
Route::post('/ofimatica/buscar', 'OfimaticaController@buscar');
Route::post('/ofimatica/store', 'OfimaticaController@store');


// --------------------- MODELOS --------------------------------
Route::group(['middleware' => ['can:modelos']], function () {
    Route::get('/producto/modelos', 'ModeloController@index')->name('producto/modelos');
    Route::post('/modelos/buscar', 'ModeloController@buscar');
    Route::post('/modelos/store', 'ModeloController@store');
    Route::post('/modelos/update', 'ModeloController@update');
    Route::post('/modelos/delete', 'ModeloController@delete');
    Route::post('/modelos/buscarCategorias', 'ModeloController@buscar_categorias');
});

// --------------------- MARCAS --------------------------------
Route::get('/marcas', 'MarcaController@index')->name('marcas');
Route::post('/marcas/buscar', 'MarcaController@buscar');
Route::post('/marcas/store', 'MarcaController@store');

// --------------------- CLIENTES --------------------------------
Route::group(['middleware' => ['can:clientes']], function () {
    Route::get('/clientes', 'ClienteController@index')->name('clientes');
    Route::post('/clientes/buscar', 'ClienteController@buscar');
    Route::post('/clientes/store', 'ClienteController@store');
    Route::post('/clientes/update', 'ClienteController@update');
    Route::post('/clientes/delete', 'ClienteController@delete');
});

// --------------------- PROVEEDORES --------------------------------
Route::group(['middleware' => ['can:proveedores']], function () {
    Route::get('/proveedores', 'ProveedorController@index')->name('proveedores');
    Route::post('/proveedores/buscar', 'ProveedorController@buscar');
    Route::post('/proveedores/store', 'ProveedorController@store');
    Route::post('/proveedores/update', 'ProveedorController@update');
    Route::post('/proveedores/delete', 'ProveedorController@delete');
});
Route::post('/proveedores/nuevo', 'ProveedorController@nuevo');
Route::post('/proveedores/buscar_take', 'ProveedorController@buscar_take');

// --------------------- BARRAS --------------------------------
Route::group(['middleware' => ['can:codigo_barras']], function () {
    Route::get('/barras', 'BarrasController@index')->name('barras');
    Route::post('/barras/buscar', 'BarrasController@buscar');
});

// --------------------- PERFILES --------------------------------
Route::group(['middleware' => ['can:perfiles']], function () {
    Route::get('/permisos', 'PermisoController@index')->name('permisos');
    Route::post('/permisos/buscar', 'PermisoController@buscar');
    Route::post('/permisos/store', 'PermisoController@store');
    Route::post('/permisos/update', 'PermisoController@update');
    Route::post('/permisos/delete', 'PermisoController@delete');

    Route::get('/roles', 'RolController@index')->name('roles');
    Route::post('/roles/buscar', 'RolController@buscar');
    Route::post('/roles/store', 'RolController@store');
    Route::post('/roles/update', 'RolController@update');
    Route::post('/roles/delete', 'RolController@delete');
});

// --------------------- USUARIOS --------------------------------
Route::group(['middleware' => ['can:usuarios']], function () {
    Route::get('/usuarios', 'UserController@index')->name('usuarios');
    Route::post('/usuarios/buscar', 'UserController@buscar');
    Route::post('/usuarios/store', 'UserController@store');
    Route::post('/usuarios/update', 'UserController@update');
    Route::post('/usuarios/delete', 'UserController@delete');
});

// --------------------- PAGINA WEB --------------------------------
Route::group(['middleware' => ['can:pagina_web']], function () {
    Route::get('/web/banners', 'BannerController@index')->name('banners');
    Route::post('/web/banners/buscar', 'BannerController@buscar');
    Route::post('/web/banners/store', 'BannerController@store');
    Route::post('/web/banners/update', 'BannerController@update');
    Route::post('/web/banners/delete', 'BannerController@delete');
});

// --------------------- CONFIGURACION --------------------------------
Route::group(['middleware' => ['can:configuracion']], function () {
    Route::get('/configuracion', 'ConfiguracionController@index')->name('configuracion');
    Route::post('/configuracion/buscar', 'ConfiguracionController@buscar');
    Route::post('/configuracion/store', 'ConfiguracionController@store');
    Route::post('/configuracion/update', 'ConfiguracionController@update');
    Route::post('/configuracion/delete', 'ConfiguracionController@delete');
    Route::post('/configuracion/file', 'ConfiguracionController@file');
    Route::post('/configuracion/show_file', 'ConfiguracionController@show_file');
    Route::post('/configuracion/delete_file', 'ConfiguracionController@delete_file');
});

// routes/web.php o routes/api.php


Route::post('/location', 'LocationController@ubigeo');

Route::prefix('sistema')->middleware(['auth', 'verified'])->group(function() {
    // Grupo de rutas para filtros
    Route::middleware('can:filtros')->group(function() {
        // Rutas principales del recurso (CRUD estándar)
        Route::resource('aside', \App\Http\Controllers\Sistema\AsideController::class)
             ->names([
                 'index' => 'sistema.aside.index',
                 'create' => 'sistema.aside.create',
                 'store' => 'sistema.aside.store',
                 'show' => 'sistema.aside.show',
                 'edit' => 'sistema.aside.edit',
                 'update' => 'sistema.aside.update',
                 'destroy' => 'sistema.aside.destroy'
             ]);

        // Rutas adicionales para operaciones con opciones/subfiltros
        Route::prefix('aside/{aside}')->group(function() {
            // Añadir nueva opción
            Route::post('agregar-opcion', [\App\Http\Controllers\Sistema\AsideController::class, 'agregarOpcion'])
                 ->name('sistema.aside.agregar-opcion');

            // Eliminar opción específica
            Route::delete('eliminar-opcion', [\App\Http\Controllers\Sistema\AsideController::class, 'eliminarOpcion'])
                 ->name('sistema.aside.eliminar-opcion');

            // Cambiar estado activo/inactivo
            Route::post('toggle-status', [\App\Http\Controllers\Sistema\AsideController::class, 'toggleStatus'])
                 ->name('sistema.aside.toggle-status');
        });
    });

    // ... otras rutas del sistema ...
});

Route::get('/test', function() {
    $modelo = \App\Modelo::find(10);
    return [
        'modelo_id' => 10,
        'exists' => $modelo ? 'Sí' : 'No',
        'asides_count' => $modelo ? $modelo->asides->count() : 0,
        'asides' => $modelo ? $modelo->asides->toArray() : []
    ];
});
Route::post('/banners', [BannerMedioController::class, 'store'])->name('banners.store');
Route::prefix('admin')->name('admin.')->group(function() {
    // Listar banners (GET)
    Route::get('banners', [BannerMedioController::class, 'index'])->name('banners.index');

    // Crear banner (POST)
    Route::post('banners', [BannerMedioController::class, 'store'])->name('banners.store');

    // Actualizar banner (PUT/PATCH)
    Route::put('banners/{bannerMedio}', [BannerMedioController::class, 'update'])->name('banners.update');

    // Eliminar banner (DELETE)
    Route::delete('banners/{bannerMedio}', [BannerMedioController::class, 'destroy'])->name('banners.destroy');
});
