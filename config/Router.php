<?php
/**
 * Simple Router
 * 
 * Handles routing and dispatching requests to controllers
 */

class Router {
    private $routes = [];
    private $groupPrefix = '';
    
    /**
     * Add GET route
     */
    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }
    
    /**
     * Add POST route
     */
    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }
    
    /**
     * Add route for multiple methods
     */
    public function match($methods, $path, $handler) {
        foreach ((array)$methods as $method) {
            $this->addRoute($method, $path, $handler);
        }
        return $this;
    }
    
    /**
     * Group routes with common prefix
     */
    public function group($prefix, $callback) {
        $previousPrefix = $this->groupPrefix;
        $this->groupPrefix = $previousPrefix . $prefix;
        $callback($this);
        $this->groupPrefix = $previousPrefix;
        return $this;
    }
    
    /**
     * Add a route
     */
    private function addRoute($method, $path, $handler) {
        $path = $this->groupPrefix . $path;
        $this->routes[$method][$path] = $handler;
    }
    
    /**
     * Dispatch the request
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';
        
        // Check for exact match
        if (isset($this->routes[$method][$path])) {
            return $this->callHandler($this->routes[$method][$path]);
        }
        
        // Check for pattern match
        foreach ($this->routes[$method] ?? [] as $routePath => $handler) {
            $pattern = $this->convertToRegex($routePath);
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove full match
                return $this->callHandler($handler, $matches);
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        if (file_exists(BASE_PATH . '/404.php')) {
            require BASE_PATH . '/404.php';
        } else {
            echo '404 - Page Not Found';
        }
        exit;
    }
    
    /**
     * Convert route path to regex pattern
     */
    private function convertToRegex($path) {
        // Convert {param} to named capture group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    /**
     * Call the route handler
     */
    private function callHandler($handler, $params = []) {
        // If handler is a closure
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }
        
        // If handler is Controller@method format
        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($controller, $method) = explode('@', $handler);
            
            // Add namespace if not present
            if (strpos($controller, '\\') === false) {
                $controller = 'Controllers\\' . $controller;
            }
            
            if (!class_exists($controller)) {
                throw new Exception("Controller {$controller} not found");
            }
            
            $instance = new $controller();
            
            if (!method_exists($instance, $method)) {
                throw new Exception("Method {$method} not found in {$controller}");
            }
            
            return call_user_func_array([$instance, $method], $params);
        }
        
        throw new Exception("Invalid route handler");
    }
}
