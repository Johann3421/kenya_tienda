<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\ConsultarController;

$request = Request::create('/consultar/garantia/buscar', 'POST', [
    'search' => 'DM258010009331'
]);

$controller = new ConsultarController();
$response = $controller->garantia_buscar($request);

echo json_encode($response, JSON_PRETTY_PRINT);
