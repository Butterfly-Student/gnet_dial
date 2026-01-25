<?php
namespace Services;

use Models\User;

/**
 * Authentication Service
 * 
 * Handles user authentication, authorization, and session management
 */
class AuthService {
    
    /**
     * Attempt to log in a user
     * 
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @return bool
     */
    public static function login($username, $password, $remember = false) {
        $user = User::findByUsername($username);
        
        if (!$user) {
            return false;
        }
        
        if (!User::verifyPassword($password, $user['password'])) {
            return false;
        }
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['mikrotik_id'] = $user['mikrotik_id'] ?? null;
        
        // Handle remember me
        if ($remember) {
            $cookieName = config('app.remember.cookie_name');
            $cookieLifetime = config('app.remember.cookie_lifetime');
            
            setcookie(
                $cookieName,
                $user['id'],
                time() + $cookieLifetime,
                '/',
                '',
                false, // Set to true if using HTTPS
                true   // httponly
            );
        }
        
        return true;
    }
    
    /**
     * Log out the current user
     */
    public static function logout() {
        // Clear session
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
        
        // Clear remember me cookie
        $cookieName = config('app.remember.cookie_name');
        if (isset($_COOKIE[$cookieName])) {
            setcookie($cookieName, '', time() - 3600, '/');
        }
    }
    
    /**
     * Check if user is authenticated
     * 
     * @return bool
     */
    public static function check() {
        // Check session first
        if (isset($_SESSION['user_id'])) {
            return true;
        }
        
        // Check remember me cookie
        $cookieName = config('app.remember.cookie_name');
        if (isset($_COOKIE[$cookieName])) {
            $userId = $_COOKIE[$cookieName];
            $user = User::find($userId);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['mikrotik_id'] = $user['mikrotik_id'] ?? null;
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get current authenticated user
     * 
     * @return array|null
     */
    public static function user() {
        if (!self::check()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'fullname' => $_SESSION['fullname'],
            'role' => $_SESSION['role'],
            'mikrotik_id' => $_SESSION['mikrotik_id'] ?? null,
        ];
    }
    
    /**
     * Get user ID
     * 
     * @return int|null
     */
    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Check if current user is superadmin
     * 
     * @return bool
     */
    public static function isSuperAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin';
    }
    
    /**
     * Check if user can access a specific MikroTik
     * 
     * @param int $mikrotikId
     * @return bool
     */
    public static function canAccessMikrotik($mikrotikId) {
        if (self::isSuperAdmin()) {
            return true;
        }
        
        $assignedId = $_SESSION['mikrotik_id'] ?? null;
        return $assignedId !== null && $assignedId == $mikrotikId;
    }
    
    /**
     * Check if user can manage users
     * 
     * @return bool
     */
    public static function canManageUsers() {
        return self::isSuperAdmin();
    }
    
    /**
     * Get assigned MikroTik ID
     * 
     * @return int|null
     */
    public static function getMikrotikId() {
        if (self::isSuperAdmin()) {
            return null; // Can access all
        }
        
        return $_SESSION['mikrotik_id'] ?? null;
    }
}
