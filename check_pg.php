<?php
try {
    $pdo = new PDO("pgsql:host=127.0.0.1;port=5432;dbname=kenya_tienda", "postgres", "postgres");
    echo "Connected to pgsql with postgres:postgres\n";
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'soportes'");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "postgres:postgres failed: " . $e->getMessage() . "\n";
}
try {
    $pdo = new PDO("pgsql:host=127.0.0.1;port=5432;dbname=kenya_tienda", "postgres", "123456");
    echo "Connected to pgsql with postgres:123456\n";
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'soportes'");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "postgres:123456 failed: " . $e->getMessage() . "\n";
}
try {
    $pdo = new PDO("pgsql:host=127.0.0.1;port=5432;dbname=kenya_tienda", "postgres", "");
    echo "Connected to pgsql with postgres:[empty]\n";
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'soportes'");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "postgres:[empty] failed: " . $e->getMessage() . "\n";
}
try {
    $pdo = new PDO("pgsql:host=127.0.0.1;port=5432;dbname=postgres", "postgres", "postgres");
    echo "Connected to pgsql with postgres:postgres to postgres DB\n";
    $stmt = $pdo->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'soportes'");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "postgres:postgres (DB postgres) failed: " . $e->getMessage() . "\n";
}
