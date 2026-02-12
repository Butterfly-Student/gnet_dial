<?php
namespace Models;

/**
 * Base Model
 * 
 * Provides common database functionality for all models
 */
class BaseModel {
    protected static $table;
    protected static $primaryKey = 'id';
    protected $connection;
    
    /**
     * Get database connection
     */
    protected static function getConnection() {
        static $pdo = null;
        
        if ($pdo === null) {
            $config = config('database');
            $host = $config['host'];
            $port = !empty($config['port']) ? ";port={$config['port']}" : "";
            $database = $config['database'];
            $charset = $config['charset'] ?? 'utf8mb4';
            
            $dsn = "mysql:host={$host}{$port};dbname={$database};charset={$charset}";
            $pdo = new \PDO($dsn, $config['username'], $config['password'], $config['options']);
        }
        
        return $pdo;
    }
    
    /**
     * Find record by ID
     * 
     * @param mixed $id
     * @return array|null
     */
    public static function find($id) {
        $pdo = static::getConnection();
        $table = static::$table;
        $primaryKey = static::$primaryKey;
        
        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$primaryKey} = ? LIMIT 1");
        $stmt->execute([$id]);
        
        return $stmt->fetch() ?: null;
    }
    
    /**
     * Find all records
     * 
     * @return array
     */
    public static function all() {
        $pdo = static::getConnection();
        $table = static::$table;
        
        $stmt = $pdo->query("SELECT * FROM {$table}");
        return $stmt->fetchAll();
    }
    
    /**
     * Find records by condition
     * 
     * @param string $column
     * @param mixed $value
     * @return array
     */
    public static function where($column, $value) {
        $pdo = static::getConnection();
        $table = static::$table;
        
        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$column} = ?");
        $stmt->execute([$value]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Find first record by condition
     * 
     * @param string $column
     * @param mixed $value
     * @return array|null
     */
    public static function whereFirst($column, $value) {
        $pdo = static::getConnection();
        $table = static::$table;
        
        $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$value]);
        
        return $stmt->fetch() ?: null;
    }
    
    /**
     * Create new record
     * 
     * @param array $data
     * @return int Last insert ID
     */
    public static function create($data) {
        $pdo = static::getConnection();
        $table = static::$table;
        
        $columns = array_keys($data);
        $values = array_values($data);
        
        $columnList = implode(', ', $columns);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        
        $sql = "INSERT INTO {$table} ({$columnList}) VALUES ({$placeholders})";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        
        return $pdo->lastInsertId();
    }
    
    /**
     * Update record
     * 
     * @param mixed $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data) {
        $pdo = static::getConnection();
        $table = static::$table;
        $primaryKey = static::$primaryKey;
        
        $columns = array_keys($data);
        $values = array_values($data);
        
        $setClause = implode(', ', array_map(function($col) {
            return "{$col} = ?";
        }, $columns));
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$primaryKey} = ?";
        $values[] = $id;
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }
    
    /**
     * Delete record
     * 
     * @param mixed $id
     * @return bool
     */
    public static function delete($id) {
        $pdo = static::getConnection();
        $table = static::$table;
        $primaryKey = static::$primaryKey;
        
        $stmt = $pdo->prepare("DELETE FROM {$table} WHERE {$primaryKey} = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Execute custom query
     * 
     * @param string $sql
     * @param array $params
     * @return \PDOStatement
     */
    protected static function query($sql, $params = []) {
        $pdo = static::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Start database transaction
     */
    public static function beginTransaction() {
        return static::getConnection()->beginTransaction();
    }

    /**
     * Commit database transaction
     */
    public static function commit() {
        return static::getConnection()->commit();
    }

    /**
     * Rollback database transaction
     */
    public static function rollBack() {
        return static::getConnection()->rollBack();
    }
}
