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
$router->get('/ppp/secrets', 'PppController@secrets');
$router->get('/ppp/profiles', 'PppProfileController@index'); // Updated to use new controller
$router->get('/customers', 'CustomerController@index');

// Settings
$router->get('/mikrotik/logs', 'MikrotikController@logs');
$router->get('/mikrotik/interface', 'MikrotikController@interface');
$router->get('/settings', 'MikrotikController@settings');
$router->post('/settings', 'MikrotikController@updateSettings');

// ============================================================================
// API Routes (JSON Responses)
// ============================================================================

$router->group('/api', function ($router) {

    // PPP API
    $router->post('/ppp/active', 'PppController@getActiveUsers');
    $router->post('/ppp/non-active', 'PppController@getNonActiveUsers');
    $router->post('/ppp/search', 'PppController@search');
    $router->post('/ppp/disconnect', 'PppController@disconnect');
    $router->post('/ppp/disconnect-multiple', 'PppController@disconnectMultiple');
    $router->post('/ppp/toggle-isolir', 'PppController@toggleIsolir');
    $router->post('/ppp/isolir-config', 'PppController@getIsolirConfig');
    $router->post('/ppp/queue/traffic', 'PppController@getQueueTraffic');
    $router->post('/ppp/secrets', 'PppController@getSecrets');
    $router->post('/ppp/secret/get', 'PppController@getSecret');
    $router->post('/ppp/secret/add', 'PppController@addSecret');
    $router->post('/ppp/secret/update', 'PppController@updateSecret');
    $router->post('/ppp/secret/delete', 'PppController@deleteSecret');
    $router->post('/ppp/secret/toggle', 'PppController@toggleSecret');
    $router->post('/ppp/profiles', 'PppController@getProfilesList');

    // IP Pool API
    $router->post('/ppp/ip-pools', 'PppController@getIpPools');
    $router->post('/ppp/ip-pool/get', 'PppController@getIpPool');
    $router->post('/ppp/ip-pool/add', 'PppController@addIpPool');
    $router->post('/ppp/ip-pool/update', 'PppController@updateIpPool');
    $router->post('/ppp/ip-pool/delete', 'PppController@deleteIpPool');

    // Parent Queue API
    $router->post('/ppp/parent-queues', 'PppController@getParentQueues');
    $router->post('/ppp/parent-queue/get', 'PppController@getParentQueue');
    $router->post('/ppp/parent-queue/add', 'PppController@addParentQueue');
    $router->post('/ppp/parent-queue/update', 'PppController@updateParentQueue');
    $router->post('/ppp/parent-queue/delete', 'PppController@deleteParentQueue');

    // PPP Profile API
    $router->post('/ppp/profile/get', 'PppController@getProfile');
    $router->post('/ppp/profile/add', 'PppController@addProfile');
    $router->post('/ppp/profile/update', 'PppController@updateProfile');
    $router->post('/ppp/profile/delete', 'PppController@deleteProfile');

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
    $router->post('/mikrotik/check', 'MikrotikController@checkConnection');
    $router->post('/mikrotik/resource/info', 'MikrotikController@resourceInfo');

    // User API
    $router->post('/user/get-all', 'UserController@getAll');
    $router->post('/user/add', 'UserController@add');
    $router->post('/user/update', 'UserController@update');
    $router->post('/user/delete', 'UserController@delete');

    // Customer API
    $router->post('/customers/list', 'CustomerController@getList');
    $router->post('/customers/get', 'CustomerController@getOne');
    $router->post('/customers/add', 'CustomerController@add');
    $router->post('/customers/update', 'CustomerController@update');
    $router->post('/customers/delete', 'CustomerController@delete');

    // PPP Profile API (Database backed)
    $router->post('/ppp/profiles/list', 'PppProfileController@getList');
    $router->post('/ppp/profile/get-db', 'PppProfileController@getOne');
    $router->post('/ppp/profile/add-db', 'PppProfileController@add');
    $router->post('/ppp/profile/update-db', 'PppProfileController@update');
    $router->post('/ppp/profile/delete-db', 'PppProfileController@delete');
    $router->post('/ppp/profile/sync', 'PppProfileController@syncFromMikrotik');
});

return $router;
