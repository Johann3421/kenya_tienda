<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$total = DB::table('productos')->count();
$synced = DB::table('productos')->whereNotNull('ficha_sync_at')->count();
$localEdited = DB::table('productos')->where('ficha_editada_localmente', true)->count();
$suspended = DB::table('productos')->where('vigencia', 'SUSPENDIDA')->count();
$paginaWebNo = DB::table('productos')->where('pagina_web', 'NO')->count();
$paginaWebSi = DB::table('productos')->where('pagina_web', 'SI')->count();

echo "Total: $total\n";
echo "Synced: $synced\n";
echo "Local Edit: $localEdited\n";
echo "Suspended: $suspended\n";
echo "Web SI: $paginaWebSi\n";
echo "Web NO: $paginaWebNo\n";
