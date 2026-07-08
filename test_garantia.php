<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $garantias = App\Garantia::where('serie', 'LIKE', '%00145201397%')->get();
    echo "Count LIKE %00145201397%: " . count($garantias) . "\n";
    if (count($garantias) > 0) {
        foreach($garantias as $g) {
            echo " - " . $g->serie . "\n";
        }
    }
    
    // Y para OGC?
    $garantiasOGC = App\Garantia::where('serie', 'LIKE', '%OGC%')->limit(5)->get();
    echo "Count LIKE %OGC%: " . count($garantiasOGC) . "\n";
    foreach($garantiasOGC as $g) {
        echo " - " . $g->serie . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
