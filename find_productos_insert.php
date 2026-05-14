<?php
$lines = file('c:\xampp\htdocs\kenya_tienda\RESPALDO_KENYA_DESPLEGADO_2-01-2026.sql');
foreach ($lines as $line) {
    if (strpos(strtolower($line), 'insert into') === 0 && strpos(strtolower($line), 'productos') !== false) {
        echo substr($line, 0, 800) . "\n";
    }
}
