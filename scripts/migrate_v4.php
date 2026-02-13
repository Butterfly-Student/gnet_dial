<?php
// scripts/migrate_v4.php

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

    // Add phone column to customers
    echo "Updating customers table...\n";

    // Check if column exists
    $columns = $pdo->query("SHOW COLUMNS FROM customers")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('phone', $columns)) {
        $pdo->exec("ALTER TABLE customers ADD COLUMN phone VARCHAR(20) DEFAULT NULL AFTER service");
        echo "Added phone to customers.\n";
    } else {
        echo "Column 'phone' already exists.\n";
    }

    echo "Migration V4 completed successfully.\n";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
