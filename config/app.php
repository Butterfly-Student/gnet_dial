<?php
/**
 * Application Configuration
 */

return [
    'name' => 'GhaibNet MikroTik Manager',
    'env' => 'development', // development, production
    'debug' => true,
    'timezone' => 'Asia/Jakarta',
    
    'session' => [
        'lifetime' => 1576800000, // 50 years in seconds
        'cookie_httponly' => true,
        'cookie_secure' => false, // Set to true if using HTTPS
        'use_strict_mode' => true,
    ],
    
    'remember' => [
        'cookie_name' => 'mikrotik_remember',
        'cookie_lifetime' => 1576800000, // 50 years
    ],
];
