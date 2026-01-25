<?php
namespace Models;

/**
 * Installer Model
 * 
 * Handles database initialization and reset
 */
class Installer extends BaseModel {
    
    /**
     * Reset and initialize database
     * 
     * @return array ['success' => bool, 'message' => string]
     */
    public static function resetDatabase() {
        $pdo = self::getConnection();
        
        try {
            // 1. Disable Foreign Key Checks
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
            
            // 2. Drop all tables
            $result = $pdo->query("SHOW TABLES");
            $tables = $result->fetchAll(\PDO::FETCH_COLUMN);
            
            foreach ($tables as $table) {
                $pdo->exec("DROP TABLE IF EXISTS `$table`");
            }
            
            // 3. Re-enable Foreign Key Checks
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
            
            // 4. Read SQL file
            $schemaPath = BASE_PATH . '/db_schema/schema.sql';
            if (!file_exists($schemaPath)) {
                throw new \Exception("Schema file not found at: " . $schemaPath);
            }
            
            $sql = file_get_contents($schemaPath);
            
            // 5. Execute SQL
            try {
                $pdo->exec($sql);
            } catch (\Exception $e) {
                // Fallback: simple split (naive)
                $queries = explode(';', $sql);
                foreach ($queries as $query) {
                    if (trim($query)) {
                        $pdo->exec($query);
                    }
                }
            }
            
            return ['success' => true, 'message' => 'Database initialized successfully'];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
