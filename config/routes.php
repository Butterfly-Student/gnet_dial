<?php
/**
 * Routes Configuration
 * 
 * Define all application routes here
 */

require_once __DIR__ . '/Router.php';

$router = new Router();

// ============================================================================
// Web Routes (HTML Pages)
// ============================================================================

// Authentication Routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Installation Route (Remove in production)
$router->get('/install', 'InstallController@index');

// Dashboard
$router->get('/', 'DashboardController@index');
$router->get('/dashboard', 'DashboardController@index');

// PPP Management
$router->get('/ppp/active', 'PppController@active');
$router->get('/ppp/non-active', 'PppController@nonActive');

// Settings
$router->get('/mikrotik/logs', 'MikrotikController@logs');
$router->get('/mikrotik/interface', 'MikrotikController@interface');
$router->get('/settings', 'MikrotikController@settings');
$router->post('/settings', 'MikrotikController@updateSettings');

// ============================================================================
// API Routes (JSON Responses)
// ============================================================================

$router->group('/api', function($router) {
    
    // PPP API
    $router->post('/ppp/active', 'PppController@getActiveUsers');
    $router->post('/ppp/non-active', 'PppController@getNonActiveUsers');
    $router->post('/ppp/search', 'PppController@search');
    $router->post('/ppp/disconnect', 'PppController@disconnect');
    $router->post('/ppp/disconnect-multiple', 'PppController@disconnectMultiple');
    $router->post('/ppp/toggle-isolir', 'PppController@toggleIsolir');
    $router->post('/ppp/isolir-config', 'PppController@getIsolirConfig');
    
    // MikroTik API
    $router->post('/mikrotik/get-all', 'MikrotikController@getAll');
    $router->post('/mikrotik/add', 'MikrotikController@addApi');
    $router->post('/mikrotik/update', 'MikrotikController@updateApi');
    $router->post('/mikrotik/delete', 'MikrotikController@deleteApi');
    $router->post('/mikrotik/test-connection', 'MikrotikController@testConnection');
    $router->post('/mikrotik/interfaces', 'MikrotikController@getInterfaces');
    $router->post('/mikrotik/logs', 'MikrotikController@getLogs');
    $router->post('/mikrotik/ping', 'MikrotikController@ping');
    $router->post('/mikrotik/monitor', 'MikrotikController@monitor');
    
    // User API
    $router->post('/user/get-all', 'UserController@getAll');
    $router->post('/user/add', 'UserController@add');
    $router->post('/user/update', 'UserController@update');
    $router->post('/user/delete', 'UserController@delete');
});

return $router;
