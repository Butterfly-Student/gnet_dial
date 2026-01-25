<?php
/**
 * Public Entry Point
 * 
 * All requests are routed through this file
 */

// Bootstrap the application
$router = require_once '../bootstrap/app.php';

// Dispatch the request
try {
    $router->dispatch();
} catch (Exception $e) {
    // Handle errors
    http_response_code(500);
    
    if (config('app.debug')) {
        echo '<h1>Error</h1>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    } else {
        echo '<h1>500 - Internal Server Error</h1>';
        echo '<p>Something went wrong. Please try again later.</p>';
    }
}
