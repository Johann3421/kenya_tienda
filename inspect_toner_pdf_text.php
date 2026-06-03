<?php
require __DIR__ . '/vendor/autoload.php';

$url = 'https://prod-pc-cdn.azureedge.net/contproveedor/Documentos/Productos/605335-20240605-120000.pdf';
$bin = file_get_contents($url);
$parser = new Smalot\PdfParser\Parser();
$text = $parser->parseContent($bin)->getText();
$norm = preg_replace('/\s+/u', ' ', trim($text));
echo substr($norm, 0, 3000) . PHP_EOL;
