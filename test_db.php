<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use Illuminate\Support\Facades\DB;

$prods = DB::table('productos')
    ->where('insertado_por_api', 1)
    ->orderBy('id', 'desc')
    ->limit(5)
    ->get(['id', 'nombre', 'ram', 'tarjetavideo', 'ficha_tecnica']);
echo json_encode($prods, JSON_PRETTY_PRINT);
