<?php
namespace Models;

/**
 * MikroTik Settings Model
 */
class MikrotikSetting extends BaseModel {
    protected static $table = 'mikrotik_settings';
    protected static $primaryKey = 'id';
    
    /**
     * Get active MikroTik configuration
     * 
     * @return array|null
     */
    public static function getActive() {
        // Multi-tenant: Check if current user has an assigned MikroTik
        $user = \Services\AuthService::user(); // Use fully qualified class name or add use statement
        
        if ($user && $user['role'] !== 'superadmin' && !empty($user['mikrotik_id'])) {
            return self::find($user['mikrotik_id']);
        }
        
        // Superadmin or no assignment: fallback to global active
        return self::whereFirst('is_active', 1);
    }
    
    /**
     * Set a configuration as active
     * 
     * @param int $id
     * @return bool
     */
    public static function setActive($id) {
        $pdo = self::getConnection();
        
        try {
            $pdo->beginTransaction();
            
            // Deactivate all
            $pdo->exec("UPDATE mikrotik_settings SET is_active = 0");
            
            // Activate selected
            $stmt = $pdo->prepare("UPDATE mikrotik_settings SET is_active = 1 WHERE id = ?");
            $stmt->execute([$id]);
            
            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Get all configurations
     * 
     * @return array
     */
    public static function getAll() {
        return self::all();
    }
    
    /**
     * Create new configuration
     * 
     * @param array $data
     * @return int
     */
    public static function createConfig($data) {
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        $data['is_active'] = $data['is_active'] ?? 0;
        
        // If this is set to be active, deactivate all others
        if ($data['is_active']) {
            $pdo = self::getConnection();
            $pdo->exec("UPDATE mikrotik_settings SET is_active = 0");
        }
        
        return self::create($data);
    }
    
    /**
     * Update configuration
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateConfig($id, $data) {
        // If this is set to be active, deactivate all others
        if (isset($data['is_active']) && $data['is_active']) {
            $pdo = self::getConnection();
            $pdo->exec("UPDATE mikrotik_settings SET is_active = 0 WHERE id != " . (int)$id);
        }
        
        return self::update($id, $data);
    }
    
    /**
     * Delete configuration
     * 
     * @param int $id
     * @return bool
     */
    public static function deleteConfig($id) {
        // Don't delete if it's the active one
        $config = self::find($id);
        if ($config && $config['is_active']) {
            return false;
        }
        
        return self::delete($id);
    }
    
    /**
     * Test connection to MikroTik
     * 
     * @param array $config
     * @return bool
     */
    public static function testConnection($config) {
        require_once BASE_PATH . '/package/routeros_api.php';
        
        $api = new \RouterosAPI();
        $api->debug = false;
        
        // RouterosAPI requires port to be set as property, connect() strictly takes 3 args
        if (isset($config['port'])) {
            $api->port = (int)$config['port'];
        }
        
        try {
            if ($api->connect($config['host'], $config['username'], $config['password'])) {
                $api->disconnect();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
