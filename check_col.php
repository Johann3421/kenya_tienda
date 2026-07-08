<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hasEspecial = Illuminate\Support\Facades\Schema::hasColumn('productos', 'precio_especial');
echo $hasEspecial ? 'SI' : 'NO';
