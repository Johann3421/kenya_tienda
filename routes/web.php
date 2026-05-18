<?php

use App\Http\Controllers\Admin\BannerMedioController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ReclamacionController;
use App\Http\Controllers\Sistema\AsideController;
use App\Http\Controllers\SoporteController;
use App\Http\Controllers\SerialDrawController;
use App\Http\Controllers\SearchController;
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
Route::get('/search-products', [SearchController::class, 'products'])->name('search.products');

// --------------------- INICIO --------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/perfil', [\App\Http\Controllers\PerfilController::class, 'index'])->name('perfil');
    Route::post('/perfil', [\App\Http\Controllers\PerfilController::class, 'update'])->name('perfil.update');
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

Route::get('/sorteo', [SerialDrawController::class, 'index'])->name('serial.draw');
Route::post('/sorteo', [SerialDrawController::class, 'store'])->name('serial.draw.store');
Route::post('/sorteo/claim', [SerialDrawController::class, 'claim'])->name('serial.draw.claim');

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
    Route::post('/drivers/asignar-serie', 'DriversController@asignarSerie');
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

Route::group(['middleware' => ['can:admin_paginas']], function () {
    Route::get('/paginas/admin', [\App\Http\Controllers\Sistema\ControlRutasController::class, 'index'])->name('paginas.admin');
    Route::post('/paginas/admin/cambiar-estado', [\App\Http\Controllers\Sistema\ControlRutasController::class, 'cambiarEstado'])->name('paginas.admin.cambiar_estado');
});
Route::post('/sistema/aside/duplicar', [App\Http\Controllers\Sistema\AsideController::class, 'duplicar'])->name('sistema.aside.duplicar');

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
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function() {
    // Listar banners (GET)
    Route::get('banners', [BannerMedioController::class, 'index'])->name('banners.index');

    // Crear banner (POST)
    Route::post('banners', [BannerMedioController::class, 'store'])->name('banners.store');

    // Actualizar banner (PUT/PATCH)
    Route::put('banners/{bannerMedio}', [BannerMedioController::class, 'update'])->name('banners.update');

    // Eliminar banner (DELETE)
    Route::delete('banners/{bannerMedio}', [BannerMedioController::class, 'destroy'])->name('banners.destroy');

    // Serial Rewards
    Route::resource('serial-rewards', \App\Http\Controllers\Admin\SerialRewardController::class);
});
Route::get('/producto/buscar-especificaciones', [ProductoController::class, 'buscarPorModeloONroParte']);


// ============================================================
// DIAGNÓSTICO DE STORAGE/IMÁGENES — BORRAR DESPUÉS DE USAR
// Acceder a: /diagnostico-storage
// ============================================================
Route::get('/diagnostico-storage', function() {
    $lines = [];
    $ok  = fn($msg) => "<li style='color:green'>✅ $msg</li>";
    $err = fn($msg) => "<li style='color:red'>❌ $msg</li>";
    $inf = fn($msg) => "<li style='color:#555'>ℹ️ $msg</li>";

    $lines[] = "<h2>1. PHP / Upload settings</h2><ul>";
    $lines[] = $inf("upload_max_filesize = " . ini_get('upload_max_filesize'));
    $lines[] = $inf("post_max_size = " . ini_get('post_max_size'));
    $lines[] = $inf("file_uploads = " . ini_get('file_uploads'));
    $lines[] = $inf("APP_ENV = " . app()->environment());
    $lines[] = "</ul>";

    // 2. Rutas físicas
    $storagePath   = storage_path('app/public');
    $publicStorage = public_path('storage');
    $lines[] = "<h2>2. Rutas de storage</h2><ul>";
    $lines[] = $inf("storage_path('app/public') → $storagePath");
    $lines[] = $inf("public_path('storage') → $publicStorage");

    // 3. ¿Existe storage/app/public?
    if (is_dir($storagePath)) {
        $lines[] = $ok("storage/app/public existe");
    } else {
        $lines[] = $err("storage/app/public NO existe — crear carpeta");
    }

    // 4. ¿Es escribible?
    if (is_writable($storagePath)) {
        $lines[] = $ok("storage/app/public es escribible");
    } else {
        $lines[] = $err("storage/app/public NO es escribible — revisar permisos (chmod 755 o 775)");
    }

    // 5. ¿public/storage es symlink correcto?
    if (is_link($publicStorage)) {
        $target = readlink($publicStorage);
        if (realpath($target) === realpath($storagePath) || $target === $storagePath) {
            $lines[] = $ok("public/storage es symlink → $target");
        } else {
            $lines[] = $err("public/storage es symlink pero apunta a: $target (esperado: $storagePath)");
        }
    } elseif (is_dir($publicStorage)) {
        $lines[] = $err("public/storage es directorio real (no symlink) — puede que storage:link no funcione en este hosting");
        $lines[] = $inf("Solución en cPanel: copiar contenido de storage/app/public a public/storage manualmente");
    } else {
        $lines[] = $err("public/storage NO existe — ejecutar php artisan storage:link o crear manualmente");
    }
    $lines[] = "</ul>";

    // 6. Carpeta PRODUCTOS dentro de storage
    $productosPath = $storagePath . '/PRODUCTOS';
    $lines[] = "<h2>3. Directorio PRODUCTOS</h2><ul>";
    if (is_dir($productosPath)) {
        $lines[] = $ok("storage/app/public/PRODUCTOS existe");
        // Listar primeras 5 subcarpetas
        $subs = array_slice(glob($productosPath . '/*', GLOB_ONLYDIR), 0, 5);
        foreach ($subs as $s) {
            $lines[] = $inf("Carpeta: " . basename($s));
        }
    } else {
        $lines[] = $err("storage/app/public/PRODUCTOS NO existe — aún no se subió ninguna imagen por el nuevo código");
    }

    // Buscar también double-public path
    $doublePublicPath = $storagePath . '/public/PRODUCTOS';
    if (is_dir($doublePublicPath)) {
        $lines[] = $err("Encontrado: storage/app/public/public/PRODUCTOS — ¡BUG de doble 'public'! Los archivos están aquí en vez de en PRODUCTOS/");
        $lines[] = $inf("Mover archivos: mv storage/app/public/public/* storage/app/public/");
    }
    $lines[] = "</ul>";

    // 7. Últimos 5 productos con imagen en BD
    $lines[] = "<h2>4. Últimos productos con imagen (BD vs disco)</h2><ul>";

    // Detectar qué columnas de imagen existen
    $cols = \Illuminate\Support\Facades\Schema::getColumnListing('productos');
    $hasImagen  = in_array('imagen',   $cols);
    $hasImagen1 = in_array('imagen_1', $cols);
    $hasFicha   = in_array('ficha',    $cols);
    $lines[] = $inf("Columnas de imagen en BD: imagen=" . ($hasImagen ? '✔' : '✘') . " | imagen_1=" . ($hasImagen1 ? '✔' : '✘') . " | ficha=" . ($hasFicha ? '✔' : '✘'));
    if (!$hasImagen) {
        $lines[] = $err("Columna 'imagen' NO existe — ejecutar la migración o el SQL de ALTER TABLE");
    }
    if (!$hasFicha) {
        $lines[] = $err("Columna 'ficha' NO existe — ejecutar la migración o el SQL de ALTER TABLE");
    }

    try {
        $select = ['id', 'nombre'];
        if ($hasImagen)  $select[] = 'imagen';
        if ($hasImagen1) $select[] = 'imagen_1';

        $query = \App\Producto::query();
        if ($hasImagen1) $query->orWhereNotNull('imagen_1');
        if ($hasImagen)  $query->orWhereNotNull('imagen');
        $prods = $query->orderBy('id', 'desc')->limit(5)->get($select);
        foreach ($prods as $p) {
            $img = ($hasImagen1 && $p->imagen_1) ? $p->imagen_1
                 : (($hasImagen  && $p->imagen)  ? $p->imagen : null);
            if (!$img) continue;
            $fullPath = $storagePath . '/' . $img;
            $url = asset('storage/' . $img);
            if (file_exists($fullPath)) {
                $lines[] = $ok("ID {$p->id} | DB: $img | Archivo: OK | URL: <a href='$url' target='_blank'>$url</a>");
            } else {
                $lines[] = $err("ID {$p->id} | DB: $img | Archivo: NO EXISTE en disco | URL: <a href='$url' target='_blank'>$url</a>");
                // Intentar búsqueda alternativa
                $basename = basename($img);
                $found = [];
                $rit = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storagePath, \FilesystemIterator::SKIP_DOTS));
                foreach ($rit as $f) {
                    if ($f->getFilename() === $basename) {
                        $found[] = $f->getPathname();
                    }
                }
                if ($found) {
                    $lines[] = $inf("&nbsp;&nbsp;&rarr; Encontrado en: " . implode(', ', $found));
                }
            }
        }
    } catch (\Exception $e) {
        $lines[] = $err("Error al consultar BD: " . $e->getMessage());
    }
    $lines[] = "</ul>";

    // 8. Prueba de escritura real
    $lines[] = "<h2>5. Prueba de escritura</h2><ul>";
    $testDir  = $storagePath . '/TEST_WRITE';
    $testFile = $testDir . '/test.txt';
    try {
        if (!is_dir($testDir)) mkdir($testDir, 0775, true);
        file_put_contents($testFile, 'ok');
        $lines[] = $ok("Escritura OK en storage/app/public/TEST_WRITE/test.txt");
        unlink($testFile);
        rmdir($testDir);
    } catch (\Throwable $e) {
        $lines[] = $err("No se pudo escribir: " . $e->getMessage());
    }
    $lines[] = "</ul>";

    return response(
        "<html><body style='font-family:monospace;padding:20px'>"
        . "<h1>🔍 Diagnóstico de Storage/Imágenes</h1>"
        . "<p style='color:orange'><b>⚠️ BORRAR ESTA RUTA DESPUÉS DE USAR</b></p>"
        . implode('', $lines)
        . "</body></html>"
    );
});
// ============================================================
// FIN DIAGNÓSTICO STORAGE — BORRAR BLOQUE ANTERIOR DESPUÉS DE USAR
// ============================================================

// ============================================================
// DIAGNÓSTICO: RAÍZ DEL SERVIDOR vs public_path()
// Acceder a: /diagnostico-servidor
// Responde la pregunta: ¿asset('PRODUCTOS/...') sirve el archivo?
// ============================================================
Route::get('/diagnostico-servidor', function () {
    $results = [];
    $ok  = fn($msg) => "<li style='color:green'>✅ $msg</li>";
    $err = fn($msg) => "<li style='color:red'>❌ $msg</li>";
    $inf = fn($msg) => "<li style='color:#555'>ℹ️ $msg</li>";
    $warn = fn($msg) => "<li style='color:orange'>⚠️ $msg</li>";

    $docRoot   = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
    $publicPath = rtrim(public_path(), '/');
    $appUrl     = config('app.url');

    $results[] = "<h2>1. Raíz del servidor</h2><ul>";
    $results[] = $inf("DOCUMENT_ROOT    = $docRoot");
    $results[] = $inf("public_path()    = $publicPath");
    $results[] = $inf("SCRIPT_FILENAME  = " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A'));
    $results[] = $inf("APP_URL          = $appUrl");

    if ($docRoot === $publicPath) {
        $results[] = $ok("Document root = public_path() ✔ asset('PRODUCTOS/...') funcionará directamente.");
    } else {
        $results[] = $warn("Document root ≠ public_path().");
        $results[] = $inf("Doc root: $docRoot");
        $results[] = $inf("Public:   $publicPath");
        $results[] = $warn("asset('PRODUCTOS/...') genera URL basada en APP_URL, pero Apache sirve desde DOCUMENT_ROOT.");
        $results[] = $warn("Si las URLs dan 404, significa que el doc root NO es la carpeta public/ de Laravel.");
    }
    $results[] = "</ul>";

    // Test: escribir un archivo en public_path() y dar la URL para verificar manualmente
    $testFile = 'test_srv_' . time() . '.txt';
    $testPath = public_path($testFile);
    $testUrl  = $appUrl . '/' . $testFile;
    file_put_contents($testPath, 'OK_DIRECT');
    $results[] = "<h2>2. Test acceso directo desde public_path()</h2><ul>";
    $results[] = $ok("Archivo creado: $testPath");
    $results[] = $inf("URL generada: <a href='$testUrl' target='_blank'>$testUrl</a>");
    $results[] = $warn("Abre esa URL en el navegador. Si ves 'OK_DIRECT' → asset() funciona sin storage/. Si da 404 → el doc root es diferente.");
    $results[] = $inf("(El archivo se auto-elimina en 60 segundos si recargas esta página)");
    // Intentar limpiar el de la vez anterior
    foreach (glob(public_path('test_srv_*.txt')) as $old) {
        if ($old !== $testPath) @unlink($old);
    }
    $results[] = "</ul>";

    // Test: escribir en storage y dar la URL
    $testStorage = 'test_storage_' . time() . '.txt';
    \Illuminate\Support\Facades\Storage::disk('public')->put($testStorage, 'OK_STORAGE');
    $storageUrl = $appUrl . '/storage/' . $testStorage;
    $results[] = "<h2>3. Test symlink storage (método anterior)</h2><ul>";
    $results[] = $ok("Archivo en storage/app/public/$testStorage");
    $results[] = $inf("URL: <a href='$storageUrl' target='_blank'>$storageUrl</a>");
    $results[] = $warn("Si ves 'OK_STORAGE' → el symlink funciona y el método anterior también serviría.");
    $results[] = $warn("Si da 404 → el symlink NO funciona (confirma que necesitamos guardar en public/).");
    $results[] = "</ul>";

    // Estado del .htaccess
    $htContent = file_exists(public_path('.htaccess')) ? file_get_contents(public_path('.htaccess')) : '';
    $results[] = "<h2>4. Estado .htaccess</h2><ul>";
    $results[] = (strpos($htContent, 'SymLinksIfOwnerMatch') !== false || strpos($htContent, 'FollowSymLinks') !== false)
        ? $ok(".htaccess tiene opción SymLinks")
        : $err(".htaccess SIN opción SymLinks → SUBE EL .htaccess ACTUALIZADO A CPANEL");
    $results[] = "</ul>";

    return response(
        "<html><body style='font-family:monospace;padding:20px'>"
        . "<h1>🔍 Diagnóstico Servidor/Imágenes</h1>"
        . "<p style='color:orange'><b>⚠️ BORRAR ESTA RUTA DESPUÉS DE USAR</b></p>"
        . implode('', $results)
        . "</body></html>"
    );
});
// ============================================================
// FIN DIAGNÓSTICO SERVIDOR — BORRAR BLOQUE ANTERIOR DESPUÉS DE USAR
// ============================================================

Route::get('/limpiar-todo', function() {
    try {
        // 1. Limpieza de cachés
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        // 2. Reoptimizar (solo para producción)
        if (app()->environment('production')) {
            Artisan::call('config:cache');
            Artisan::call('view:cache');
        }

        // 3. Recrear enlaces simbólicos
        Artisan::call('storage:link');

        return "¡Sistema limpiado! ✅<br>" .
               "Cache/Config/View/Route borrados.<br>" .
               "Ahora elimina esta ruta (/limpiar-todo) por seguridad.";

    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});





