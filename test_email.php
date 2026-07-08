<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Add stream options to bypass SSL verify peer just for testing locally
    config(['mail.mailers.smtp.stream' => [
        'ssl' => [
            'allow_self_signed' => true,
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]]);
    
    Illuminate\Support\Facades\Mail::raw('Test', function($msg) {
        $msg->to('prueba@kenya.com.pe')->subject('Test');
    });
    echo "OK";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
