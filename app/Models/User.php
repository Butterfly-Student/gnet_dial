<?php
namespace Models;

/**
 * User Model
 */
class User extends BaseModel {
    protected static $table = 'users';
    protected static $primaryKey = 'id';
    
    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|null
     */
    public static function findByUsername($username) {
        return self::whereFirst('username', $username);
    }
    
    /**
     * Verify password
     * 
     * @param string $password Plain text password
     * @param string $hash Hashed password
     * @return bool
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Hash password
     * 
     * @param string $password
     * @return string
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Create new user
     * 
     * @param array $data
     * @return int User ID
     */
    public static function createUser($data) {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = self::hashPassword($data['password']);
        }
        
        // Set defaults
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        
        return self::create($data);
    }
    
    /**
     * Update user
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateUser($id, $data) {
        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = self::hashPassword($data['password']);
        } else {
            // Don't update password if not provided
            unset($data['password']);
        }
        
        return self::update($id, $data);
    }
    
    /**
     * Get all users except current user
     * 
     * @param int $currentUserId
     * @return array
     */
    public static function getAllExcept($currentUserId) {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id != ? ORDER BY created_at DESC");
        $stmt->execute([$currentUserId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Check if user can access MikroTik
     * 
     * @param int $userId
     * @param int $mikrotikId
     * @return bool
     */
    public static function canAccessMikrotik($userId, $mikrotikId) {
        $user = self::find($userId);
        
        if (!$user) {
            return false;
        }
        
        // Superadmin can access all
        if ($user['role'] === 'superadmin') {
            return true;
        }
        
        // Check if assigned MikroTik matches
        return $user['mikrotik_id'] == $mikrotikId;
    }
    /**
     * Get all users with MikroTik details
     * 
     * @param array $currentUser
     * @return array
     */
    public static function getAllWithDetails($currentUser) {
        $pdo = self::getConnection();
        
        if ($currentUser['role'] === 'superadmin') {
            $stmt = $pdo->query("
                SELECT u.id, u.username, u.email, u.fullname, u.role, u.mikrotik_id, 
                       m.name as mikrotik_name, u.created_at, u.updated_at
                FROM users u
                LEFT JOIN mikrotik_settings m ON u.mikrotik_id = m.id
                ORDER BY u.created_at DESC
            ");
            return $stmt->fetchAll();
        } elseif ($currentUser['role'] === 'admin') {
            $stmt = $pdo->prepare("
                SELECT u.id, u.username, u.email, u.fullname, u.role, u.mikrotik_id, 
                       m.name as mikrotik_name, u.created_at, u.updated_at
                FROM users u
                LEFT JOIN mikrotik_settings m ON u.mikrotik_id = m.id
                WHERE u.mikrotik_id = ? OR u.id = ?
                ORDER BY u.created_at DESC
            ");
            $stmt->execute([$currentUser['mikrotik_id'], $currentUser['id']]);
            return $stmt->fetchAll();
        }
        
        return [];
    }
}
