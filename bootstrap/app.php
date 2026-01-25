<?php
/**
 * Application Bootstrap
 * 
 * This file initializes the application environment, sets up error handling,
 * starts the session, and loads the configuration.
 */

// Define base paths
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $file = str_replace('\\', '/', $class) . '.php';
    
    // Check in app directory
    $appPath = APP_PATH . '/' . $file;
    if (file_exists($appPath)) {
        require_once $appPath;
        return;
    }
    
    // Check for specific namespace prefixes
    $prefixes = [
        'App\\' => APP_PATH . '/',
    ];
    
    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Load helper functions first (needed by config files)
require_once CONFIG_PATH . '/helpers.php';

// Load configuration files
require_once CONFIG_PATH . '/app.php';
require_once CONFIG_PATH . '/database.php';

// Initialize database connection
$pdo = getDbConnection();

// Set up error handling
error_reporting(E_ALL);
ini_set('display_errors', config('app.debug') ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', BASE_PATH . '/error_log');

// Set timezone
date_default_timezone_set(config('app.timezone'));

// Start session
session_start();

// Load routes
$router = require_once CONFIG_PATH . '/routes.php';

return $router;
