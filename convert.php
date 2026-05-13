<?php

$inputFile = __DIR__ . '/RESPALDO_KENYA_DESPLEGADO_2-01-2026.sql';
$outputFile = __DIR__ . '/RESPALDO_POSTGRES.sql';

$lines = file($inputFile);
$outLines = [];

foreach ($lines as $line) {
    if (preg_match('/INSERT INTO `([^`]+)` \(([^)]+)\) VALUES/', $line, $matches)) {
        $table = $matches[1];
        $cols = str_replace('`', '"', $matches[2]);
        
        $newLine = str_replace("INSERT INTO `{$table}` ({$matches[2]})", "INSERT INTO \"{$table}\" ({$cols})", $line);
        $newLine = str_replace("\\'", "''", $newLine);
        $newLine = str_replace('\\"', '"', $newLine);
        
        $outLines[] = $newLine;
    }
}

file_put_contents($outputFile, implode("", $outLines));
echo "Se extrajeron " . count($outLines) . " sentencias INSERT listas para Postgres.\n";
