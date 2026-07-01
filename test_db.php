<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Search for series with pattern DM25 to see what series exist in local DB
$search = 'DM2';
echo "Searching for series starting with '$search'...\n";
$result = DB::table('garantia')->where('serie', 'LIKE', "$search%")->limit(20)->get();
print_r($result->toArray());

// Count all
echo "\nTotal records in garantia: " . DB::table('garantia')->count() . "\n";

// Check distinct serie patterns
$samples = DB::table('garantia')->select('serie')->distinct()->limit(20)->get();
echo "\nSample series:\n";
foreach ($samples as $s) {
    echo "  " . $s->serie . "\n";
}

// Check the SQL backup for this serie
echo "\nChecking backup SQL for DM258010009331...\n";
$backup = base_path('kenyacom_kenya (7).sql');
if (file_exists($backup)) {
    echo "Found kenyacom_kenya (7).sql\n";
    // grep for the series
    $output = [];
    exec('findstr /C:"DM258010009331" "' . $backup . '"', $output);
    if ($output) {
        echo "FOUND in SQL backup:\n";
        foreach ($output as $line) {
            echo substr($line, 0, 200) . "\n";
        }
    } else {
        echo "NOT FOUND in kenyacom_kenya (7).sql\n";
    }
} else {
    echo "Backup file not found\n";
}

$backup2 = base_path('RESPALDO_KENYA_DESPLEGADO_2-01-2026.sql');
if (file_exists($backup2)) {
    echo "\nFound RESPALDO_KENYA_DESPLEGADO_2-01-2026.sql\n";
    $output2 = [];
    exec('findstr /C:"DM258010009331" "' . $backup2 . '"', $output2);
    if ($output2) {
        echo "FOUND in SQL backup:\n";
        foreach ($output2 as $line) {
            echo substr($line, 0, 200) . "\n";
        }
    } else {
        echo "NOT FOUND in RESPALDO backup\n";
    }
}
