<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Illuminate\Support\Facades\DB::statement('ALTER TABLE productos ADD COLUMN IF NOT EXISTS precio_especial NUMERIC(10,2) NULL');
    Illuminate\Support\Facades\DB::statement('ALTER TABLE productos ADD COLUMN IF NOT EXISTS precio_referencial NUMERIC(10,2) NULL');
    echo "Columnas agregadas con exito";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
