<?php
// scripts/migrate_v2.php

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

    // 1. Add columns to ppp_profiles
    echo "Updating ppp_profiles table...\n";

    // Check if columns exist
    $columns = $pdo->query("SHOW COLUMNS FROM ppp_profiles")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('mikrotik_id', $columns)) {
        $pdo->exec("ALTER TABLE ppp_profiles ADD COLUMN mikrotik_id INT NULL COMMENT 'MikroTik Device ID' AFTER id");
        $pdo->exec("ALTER TABLE ppp_profiles ADD CONSTRAINT fk_ppp_profiles_mikrotik FOREIGN KEY (mikrotik_id) REFERENCES mikrotik_settings(id) ON DELETE CASCADE");
        echo "Added mikrotik_id to ppp_profiles.\n";
    }

    if (!in_array('price', $columns)) {
        $pdo->exec("ALTER TABLE ppp_profiles ADD COLUMN price DECIMAL(12,2) DEFAULT 0 AFTER rate_limit");
        echo "Added price to ppp_profiles.\n";
    }

    if (!in_array('tax_rate', $columns)) {
        $pdo->exec("ALTER TABLE ppp_profiles ADD COLUMN tax_rate DECIMAL(5,2) DEFAULT 0 AFTER price");
        echo "Added tax_rate to ppp_profiles.\n";
    }

    // 2. Add columns to customers
    echo "Updating customers table...\n";
    $custColumns = $pdo->query("SHOW COLUMNS FROM customers")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('mikrotik_id', $custColumns)) {
        $pdo->exec("ALTER TABLE customers ADD COLUMN mikrotik_id INT NULL COMMENT 'MikroTik Device ID' AFTER id");
        $pdo->exec("ALTER TABLE customers ADD CONSTRAINT fk_customers_mikrotik FOREIGN KEY (mikrotik_id) REFERENCES mikrotik_settings(id) ON DELETE CASCADE");
        echo "Added mikrotik_id to customers.\n";
    }

    echo "Migration V2 completed successfully.\n";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
