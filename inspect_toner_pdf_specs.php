<?php
require __DIR__ . '/vendor/autoload.php';

$cmd = new App\Console\Commands\SyncFichasCommand();
$ref = new ReflectionClass($cmd);
$method = $ref->getMethod('parsePdfBinaryToSpecs');
$method->setAccessible(true);

$url = 'https://prod-pc-cdn.azureedge.net/contproveedor/Documentos/Productos/605335-20240605-120000.pdf';
$bin = file_get_contents($url);
$specs = $method->invoke($cmd, $bin);
ksort($specs);
foreach ($specs as $k => $v) {
    echo $k . '=' . $v . PHP_EOL;
}
