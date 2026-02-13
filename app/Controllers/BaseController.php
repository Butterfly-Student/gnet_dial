<?php
namespace Controllers;

/**
 * Base Controller
 * 
 * Provides common functionality for all controllers
 */
class BaseController {
    
    /**
     * Render a view
     * 
     * @param string $view View name (e.g., 'dashboard/index')
     * @param array $data Data to pass to view
     */
    protected function view($view, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Build view path
        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} not found at {$viewPath}");
        }
        
        require $viewPath;
    }
    
    /**
     * Return JSON response
     * 
     * @param mixed $data Data to encode
     * @param int $status HTTP status code
     */
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to URL
     * 
     * @param string $url URL to redirect to
     */
    protected function redirect($url) {
        redirect($url);
    }
    
    /**
     * Get POST data
     * 
     * @param string $key Key to get
     * @param mixed $default Default value
     * @return mixed
     */
    protected function post($key = null, $default = null) {
        // Handle JSON Input
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            static $jsonInput = null;
            if ($jsonInput === null) {
                $jsonInput = json_decode(file_get_contents('php://input'), true) ?? [];
            }

            if ($key === null) {
                return $jsonInput;
            }
            return $jsonInput[$key] ?? $default;
        }

        // Handle Standard POST
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Get GET data
     * 
     * @param string $key Key to get
     * @param mixed $default Default value
     * @return mixed
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Validate request data
     * 
     * @param array $data Data to validate
     * @param array $rules Validation rules
     * @return array Validated data
     * @throws \Exception if validation fails
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $ruleList = is_array($rule) ? $rule : explode('|', $rule);
            
            foreach ($ruleList as $r) {
                // Required rule
                if ($r === 'required' && empty($data[$field])) {
                    $errors[$field][] = ucfirst($field) . ' is required';
                }
                
                // Email rule
                if ($r === 'email' && !empty($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = ucfirst($field) . ' must be a valid email';
                }
                
                // Min length
                if (strpos($r, 'min:') === 0) {
                    $min = (int)substr($r, 4);
                    if (!empty($data[$field]) && strlen($data[$field]) < $min) {
                        $errors[$field][] = ucfirst($field) . " must be at least {$min} characters";
                    }
                }
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['_old_input'] = $data;
            throw new \Exception("Validation failed");
        }
        
        return $data;
    }
    
    /**
     * Check if user is authenticated
     */
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }
    
    /**
     * Check if user is superadmin
     */
    protected function requireSuperAdmin() {
        $this->requireAuth();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
            $_SESSION['flash_message'] = 'Unauthorized access';
            $_SESSION['flash_type'] = 'error';
            $this->redirect('/');
        }
    }
}
