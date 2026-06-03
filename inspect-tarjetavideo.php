<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Local env uses MySQL - check what's actually in tarjetavideo column
try {
    echo "=== DISTINCT tarjetavideo values (all products) ===\n";
    $vals = \App\Producto::select('tarjetavideo')
        ->whereNotNull('tarjetavideo')
        ->distinct()
        ->orderBy('tarjetavideo')
        ->pluck('tarjetavideo');
    foreach ($vals as $v) {
        echo "  [$v]\n";
    }

    echo "\n=== Total products with pagina_web=SI ===\n";
    $total = \App\Producto::where('pagina_web', 'SI')->count();
    echo "  Total: $total\n";

    echo "\n=== Products by tarjetavideo WHERE pagina_web=SI ===\n";
    $byGraf = \App\Producto::select('tarjetavideo', \Illuminate\Support\Facades\DB::raw('count(*) as c'))
        ->where('pagina_web', 'SI')
        ->groupBy('tarjetavideo')
        ->orderBy('tarjetavideo')
        ->get();
    foreach ($byGraf as $row) {
        $v = $row->tarjetavideo ?? 'NULL';
        echo "  [$v] => {$row->c}\n";
    }

    echo "\n=== Sample products with tarjetavideo like '8GB' or '8 GB' ===\n";
    $prods = \App\Producto::where('pagina_web', 'SI')
        ->where(function($q) {
            $q->where('tarjetavideo', 'LIKE', '%8%GB%')
              ->orWhere('tarjetavideo', 'LIKE', '%8gb%');
        })
        ->select('id', 'nombre', 'tarjetavideo', 'modelo_id')
        ->take(5)
        ->get();
    foreach ($prods as $p) {
        echo "  id={$p->id} modelo_id={$p->modelo_id} tarjetavideo=[{$p->tarjetavideo}] nombre=[{$p->nombre}]\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
