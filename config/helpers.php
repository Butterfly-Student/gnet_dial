<?php
/**
 * Helper Functions
 * 
 * Global helper functions available throughout the application
 */

// Configuration cache
$_config = [];

/**
 * Get configuration value
 * 
 * @param string $key Configuration key in dot notation (e.g., 'app.name')
 * @param mixed $default Default value if key not found
 * @return mixed
 */
function config($key, $default = null) {
    global $_config;
    
    $parts = explode('.', $key);
    $file = $parts[0];
    
    // Load config file if not cached
    if (!isset($_config[$file])) {
        $configFile = CONFIG_PATH . '/' . $file . '.php';
        if (file_exists($configFile)) {
            $_config[$file] = require $configFile;
        } else {
            return $default;
        }
    }
    
    // Get nested value
    $value = $_config[$file];
    for ($i = 1; $i < count($parts); $i++) {
        if (!isset($value[$parts[$i]])) {
            return $default;
        }
        $value = $value[$parts[$i]];
    }
    
    return $value;
}

/**
 * Redirect to URL
 * 
 * @param string $url
 */
function redirect($url) {
    // Handle relative URLs
    if ($url[0] === '/') {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $url = rtrim($protocol . '://' . $_SERVER['HTTP_HOST'], '/') . $url;
    }
    header('Location: ' . $url);
    exit();
}

/**
 * Get base URL
 * 
 * @return string
 */
function baseUrl($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $base = rtrim($protocol . '://' . $_SERVER['HTTP_HOST'], '/');
    return $base . '/' . ltrim($path, '/');
}

/**
 * Get asset URL
 * 
 * @param string $path
 * @return string
 */
function asset($path) {
    return baseUrl('assets/' . ltrim($path, '/'));
}

/**
 * Escape HTML
 * 
 * @param string $string
 * @return string
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Get old input value
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function old($key, $default = '') {
    return $_SESSION['_old_input'][$key] ?? $default;
}

/**
 * Check if request is POST
 * 
 * @return bool
 */
function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Check if request is GET
 * 
 * @return bool
 */
function isGet() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Get current URL path
 * 
 * @return string
 */
function currentPath() {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return rtrim($path, '/') ?: '/';
}

/**
 * Check if current path matches
 * 
 * @param string $path
 * @return bool
 */
function isCurrentPath($path) {
    return currentPath() === $path;
}

/**
 * Dump and die
 * 
 * @param mixed ...$vars
 */
function dd(...$vars) {
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Get database connection
 * 
 * @return PDO
 */
function getDbConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        $dbConfig = config('database');
        
        $host = $dbConfig['host'];
        $port = !empty($dbConfig['port']) ? ";port={$dbConfig['port']}" : "";
        $database = $dbConfig['database'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];
        $charset = $dbConfig['charset'] ?? 'utf8mb4';
        
        $dsn = "mysql:host={$host}{$port};dbname={$database};charset={$charset}";
        
        try {
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    
    return $pdo;
}

/**
 * Check if user is logged in
 * 
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user is superadmin
 * 
 * @return bool
 */
function isSuperAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin';
}

/**
 * Check if user can manage users
 * 
 * @return bool
 */
function canManageUsers() {
    return isLoggedIn() && isset($_SESSION['role']) && in_array($_SESSION['role'], ['superadmin', 'admin']);
}

/**
 * Get assigned MikroTik ID for current user
 * 
 * @return int|null
 */
function getAssignedMikrotikId() {
    return $_SESSION['mikrotik_id'] ?? null;
}
