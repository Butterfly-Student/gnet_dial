<?php
// scripts/migrate_v3.php

define('BASE_PATH', dirname(__DIR__));
define('CONFIG_PATH', BASE_PATH . '/config');

// Load helper functions
require_once CONFIG_PATH . '/helpers.php';

try {
    $config = config('database');
    $host = $config['host'] === 'localhost' ? '127.0.0.1' : $config['host'];
    $port = !empty($config['port']) ? ";port={$config['port']}" : "";
    $database = $config['database'];
    $username = $config['username'];
    $password = $config['password'];
    $charset = $config['charset'] ?? 'utf8mb4';

    echo "Connecting to database {$database} on {$host}...\n";

    $dsn = "mysql:host={$host}{$port};dbname={$database};charset={$charset}";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Connected successfully.\n";

    // 1. Update ppp_profiles constraints
    echo "Updating ppp_profiles constraints...\n";

    // Check if unique index 'name' exists
    $indexes = $pdo->query("SHOW INDEX FROM ppp_profiles WHERE Key_name = 'name'")->fetchAll();
    if (!empty($indexes)) {
        echo "Dropping unique index 'name' from ppp_profiles...\n";
        $pdo->exec("ALTER TABLE ppp_profiles DROP INDEX name");
    }

    // Add composite unique index
    echo "Adding composite unique index (name, mikrotik_id) to ppp_profiles...\n";
    $check = $pdo->query("SHOW INDEX FROM ppp_profiles WHERE Key_name = 'unique_name_mikrotik'")->fetchAll();
    if (empty($check)) {
        $pdo->exec("ALTER TABLE ppp_profiles ADD CONSTRAINT unique_name_mikrotik UNIQUE (name, mikrotik_id)");
    }

    // 2. Update customers constraints
    echo "Updating customers constraints...\n";

    // Check if unique index 'username' exists
    $indexesCust = $pdo->query("SHOW INDEX FROM customers WHERE Key_name = 'username'")->fetchAll();
    if (!empty($indexesCust)) {
        echo "Dropping unique index 'username' from customers...\n";
        $pdo->exec("ALTER TABLE customers DROP INDEX username");
    }

    // Add composite unique index
    echo "Adding composite unique index (username, mikrotik_id) to customers...\n";
    $checkCust = $pdo->query("SHOW INDEX FROM customers WHERE Key_name = 'unique_username_mikrotik'")->fetchAll();
    if (empty($checkCust)) {
        $pdo->exec("ALTER TABLE customers ADD CONSTRAINT unique_username_mikrotik UNIQUE (username, mikrotik_id)");
    }

    echo "Migration V3 completed successfully.\n";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
