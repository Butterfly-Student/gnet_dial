<?php
namespace Services;

/**
 * Session Service
 * 
 * Handles session management and flash messages
 */
class SessionService {
    
    /**
     * Set a flash message
     * 
     * @param string $message
     * @param string $type (success, error, info, warning)
     */
    public static function flash($message, $type = 'info') {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    
    /**
     * Get and clear flash message
     * 
     * @return array|null
     */
    public static function getFlash() {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            $type = $_SESSION['flash_type'] ?? 'info';
            
            unset($_SESSION['flash_message']);
            unset($_SESSION['flash_type']);
            
            return ['message' => $message, 'type' => $type];
        }
        
        return null;
    }
    
    /**
     * Set a session value
     * 
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get a session value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session has a key
     * 
     * @param string $key
     * @return bool
     */
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove a session value
     * 
     * @param string $key
     */
    public static function forget($key) {
        unset($_SESSION[$key]);
    }
    
    /**
     * Get all session data
     * 
     * @return array
     */
    public static function all() {
        return $_SESSION;
    }
    
    /**
     * Clear all session data
     */
    public static function clear() {
        $_SESSION = [];
    }
}
