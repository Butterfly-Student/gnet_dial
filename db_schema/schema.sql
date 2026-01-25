-- ================================================================
-- MikroTik Manager - Production Database Schema
-- Version: 3.0
-- Last Updated: 2026-01-20
-- ================================================================

-- Drop and create database (optional - comment out if DB already exists)
-- DROP DATABASE IF EXISTS mikrotik_manager;
-- CREATE DATABASE mikrotik_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE mikrotik_manager;

-- ================================================================
-- Table: mikrotik_settings
-- Purpose: Store MikroTik device connection settings
-- ================================================================
CREATE TABLE IF NOT EXISTS mikrotik_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'Friendly name for the MikroTik device',
    host VARCHAR(100) NOT NULL COMMENT 'IP address or hostname',
    port INT DEFAULT 8728 COMMENT 'API port',
    username VARCHAR(50) NOT NULL COMMENT 'MikroTik login username',
    password VARCHAR(255) NOT NULL COMMENT 'MikroTik login password',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Whether this is the currently active device',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT chk_port_range CHECK (port BETWEEN 1 AND 65535),
    CONSTRAINT chk_host_not_empty CHECK (CHAR_LENGTH(host) > 0),
    
    -- Indexes
    INDEX idx_is_active (is_active),
    INDEX idx_host_port (host, port)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Table: users
-- Purpose: Store user accounts with role-based access and MikroTik assignment
-- ================================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL COMMENT 'User full name',
    username VARCHAR(50) NOT NULL UNIQUE COMMENT 'Login username',
    email VARCHAR(100) COMMENT 'User email address',
    password VARCHAR(255) NOT NULL COMMENT 'Hashed password',
    role ENUM('superadmin', 'admin', 'user') DEFAULT 'user' COMMENT 'User role: superadmin=full access, admin=manage own mikrotik, user=read-only',
    mikrotik_id INT NULL COMMENT 'Assigned MikroTik device (NULL for superadmin)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Key
    CONSTRAINT fk_users_mikrotik FOREIGN KEY (mikrotik_id) 
        REFERENCES mikrotik_settings(id) ON DELETE SET NULL,
    
    -- Indexes
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_mikrotik_id (mikrotik_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Default Superadmin User
-- Username: superadmin
-- Password: admin123
-- ================================================================
INSERT IGNORE INTO users (id, fullname, username, password, role, mikrotik_id) 
VALUES (1, 'Super Administrator', 'superadmin', '$2y$10$DKslUQsO2uj73f/WCR/xRODxLrbjCjIi0TUlAo4/JfyPxaMLcYQSS', 'superadmin', NULL);

-- ================================================================
-- Production Notes
-- ================================================================
-- 1. Change the superadmin password immediately after deployment
-- 2. Use environment variables for database credentials in config.php
-- 3. Enable HTTPS in production (update session cookies to secure=true)
-- 4. Regular database backups recommended
-- 5. Monitor and rotate MikroTik API passwords regularly
-- ================================================================
