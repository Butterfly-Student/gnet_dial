<?php
// scripts/migrate_customers.php

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

    // 1. Create ppp_profiles table
    echo "Creating ppp_profiles table...\n";
    $sqlProfiles = "CREATE TABLE IF NOT EXISTS ppp_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        local_address VARCHAR(50) NULL,
        remote_address VARCHAR(50) NULL,
        rate_limit VARCHAR(50) NULL,
        parent_queue VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sqlProfiles);
    echo "ppp_profiles table created/exists.\n";

    // 2. Create customers table
    echo "Creating customers table...\n";
    $sqlCustomers = "CREATE TABLE IF NOT EXISTS customers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        profile VARCHAR(100) NOT NULL,
        service VARCHAR(50) DEFAULT 'pppoe',
        status ENUM('active', 'inactive', 'isolir', 'take_off') DEFAULT 'active',
        coordinates VARCHAR(100) NULL,
        address TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_username (username),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sqlCustomers);
    echo "customers table created/exists.\n";

    echo "Migration completed successfully.\n";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
