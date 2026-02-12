<?php
namespace Controllers;

use Services\SessionService;
use Services\MikrotikService;
use Models\MikrotikSetting;
use Models\User;

/**
 * MikroTik Controller
 * 
 * Handles MikroTik settings, interfaces, logs, and system information
 */
class MikrotikController extends BaseController
{

    /**
     * Show settings page
     */
    public function settings()
    {
        $this->requireAuth();

        $currentUser = \Services\AuthService::user();

        // Regular users cannot access settings
        if ($currentUser['role'] === 'user') {
            SessionService::flash('Access denied', 'error');
            $this->redirect('/');
        }

        $flash = SessionService::getFlash();

        // Get MikroTik configurations based on role
        if ($currentUser['role'] === 'superadmin') {
            $configs = MikrotikSetting::getAll();
        } else {
            // Admin only sees their assigned MikroTik
            $configs = [];
            if (!empty($currentUser['mikrotik_id'])) {
                $config = MikrotikSetting::find($currentUser['mikrotik_id']);
                if ($config) {
                    $configs[] = $config;
                }
            }
        }

        $activeConfig = MikrotikSetting::getActive();

        // Create map for MikroTik names
        $mikrotikMap = [];
        foreach ($configs as $config) {
            $mikrotikMap[$config['id']] = $config['name'];
        }

        // Get users based on role
        if ($currentUser['role'] === 'superadmin') {
            $users = User::getAllWithDetails($currentUser);
        } else {
            // Admin sees users for their Mikrotik, plus themselves
            // We reuse User::getAllWithDetails which handles this, 
            // but we need to ensure it's filtering correctly.
            $users = User::getAllWithDetails($currentUser);
        }

        // Enrich users with mikrotik_name
        foreach ($users as &$user) {
            if (!empty($user['mikrotik_id']) && isset($mikrotikMap[$user['mikrotik_id']])) {
                $user['mikrotik_name'] = $mikrotikMap[$user['mikrotik_id']];
            } else {
                $user['mikrotik_name'] = '-';
            }
        }
        unset($user);

        // Check permissions
        $canManageUsers = true; // Admins and Superadmins can manage users

        $this->view('settings/index', [
            'flash' => $flash,
            'configs' => $configs,
            'users' => $users,
            'activeConfig' => $activeConfig,
            'currentUser' => $currentUser,
            'canManageUsers' => $canManageUsers
        ]);
    }

    /**
     * Show logs page
     */
    public function logs()
    {
        $this->requireAuth();
        $this->view('mikrotik/logs');
    }

    /**
     * Show interface monitor page
     */
    public function interface()
    {
        $this->requireAuth();
        $this->view('mikrotik/interface');
    }

    /**
     * Update MikroTik settings
     */
    public function updateSettings()
    {
        $this->requireAuth();

        if (!isPost()) {
            $this->redirect('/settings');
        }

        try {
            $action = $this->post('action');

            switch ($action) {
                case 'add':
                    $this->addConfig();
                    break;
                case 'edit':
                    $this->editConfig();
                    break;
                case 'delete':
                    $this->deleteConfig();
                    break;
                case 'set_active':
                    $this->setActiveConfig();
                    break;
                default:
                    SessionService::flash('Invalid action', 'error');
            }
        } catch (\Exception $e) {
            SessionService::flash('Error: ' . $e->getMessage(), 'error');
        }

        $this->redirect('/settings');
    }

    /**
     * Add new configuration
     */
    private function addConfig()
    {
        $data = [
            'name' => $this->post('name'),
            'host' => $this->post('host'),
            'port' => $this->post('port', 8728),
            'username' => $this->post('username'),
            'password' => $this->post('password'),
        ];

        MikrotikSetting::createConfig($data);
        SessionService::flash('Configuration added successfully', 'success');
    }

    /**
     * Edit configuration
     */
    private function editConfig()
    {
        $id = $this->post('id');
        $data = [
            'name' => $this->post('name'),
            'host' => $this->post('host'),
            'port' => $this->post('port', 8728),
            'username' => $this->post('username'),
        ];

        // Only update password if provided
        $password = $this->post('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }

        MikrotikSetting::updateConfig($id, $data);
        SessionService::flash('Configuration updated successfully', 'success');
    }

    /**
     * Delete configuration
     */
    private function deleteConfig()
    {
        $id = $this->post('id');

        if (MikrotikSetting::deleteConfig($id)) {
            SessionService::flash('Configuration deleted successfully', 'success');
        } else {
            SessionService::flash('Cannot delete active configuration', 'error');
        }
    }

    /**
     * Set active configuration
     */
    private function setActiveConfig()
    {
        $id = $this->post('id');

        if (MikrotikSetting::setActive($id)) {
            SessionService::flash('Active configuration updated', 'success');
        } else {
            SessionService::flash('Failed to update active configuration', 'error');
        }
    }

    /**
     * API: Test MikroTik connection
     */
    public function testConnection()
    {
        $this->requireAuth();

        try {
            $host = $this->post('host');
            $port = $this->post('port', 8728);
            $username = $this->post('username');
            $password = $this->post('password');

            $config = [
                'host' => $host,
                'port' => $port,
                'username' => $username,
                'password' => $password
            ];

            if (MikrotikSetting::testConnection($config)) {
                return $this->json([
                    'success' => true,
                    'message' => 'Connection successful!'
                ]);
            } else {
                return $this->json([
                    'success' => false,
                    'message' => 'Connection failed!'
                ]);
            }
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Check connection status for frontend
     */
    public function checkConnection()
    {
        $this->requireAuth();
        try {
            $config = MikrotikSetting::getActive();
            if (!$config) {
                return $this->json(['success' => false, 'message' => 'No active configuration']);
            }

            $api = new MikrotikService($config['host'], $config['port'], $config['username'], $config['password']);

            // This will use the optimized timeout settings
            if ($api->testConnection()) {
                return $this->json(['success' => true]);
            } else {
                return $this->json(['success' => false, 'message' => 'Connection failed']);
            }
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Get all configurations
     */
    public function getAll()
    {
        $this->requireAuth();
        try {
            $configs = MikrotikSetting::getAll();
            return $this->json(['success' => true, 'data' => $configs]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Add configuration
     */
    public function addApi()
    {
        $this->requireAuth();
        try {
            $data = [
                'name' => $this->post('name'),
                'host' => $this->post('host'),
                'port' => $this->post('port', 8728),
                'username' => $this->post('username'),
                'password' => $this->post('password'),
            ];

            // Only Superadmin can set global active
            if (isSuperAdmin()) {
                $data['is_active'] = $this->post('is_active') === '1' ? 1 : 0;
            }

            MikrotikSetting::createConfig($data);
            return $this->json(['success' => true, 'message' => 'Configuration added successfully']);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Update configuration
     */
    public function updateApi()
    {
        $this->requireAuth();
        try {
            $id = $this->post('id');
            $data = [
                'name' => $this->post('name'),
                'host' => $this->post('host'),
                'port' => $this->post('port', 8728),
                'username' => $this->post('username'),
            ];

            // Only Superadmin can set global active
            if (isSuperAdmin()) {
                $data['is_active'] = $this->post('is_active') === '1' ? 1 : 0;
            } else {
                // Ensure we don't accidentally set it for non-superadmin
                if (isset($data['is_active']))
                    unset($data['is_active']);
            }

            $password = $this->post('password');
            if (!empty($password)) {
                $data['password'] = $password;
            }

            MikrotikSetting::updateConfig($id, $data);
            return $this->json(['success' => true, 'message' => 'Configuration updated successfully']);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Delete configuration
     */
    public function deleteApi()
    {
        $this->requireAuth();
        try {
            $id = $this->post('id');
            if (MikrotikSetting::deleteConfig($id)) {
                return $this->json(['success' => true, 'message' => 'Configuration deleted successfully']);
            } else {
                return $this->json(['success' => false, 'message' => 'Cannot delete active configuration']);
            }
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Get all interfaces
     */
    public function getInterfaces()
    {
        $this->requireAuth();

        try {
            $config = MikrotikSetting::getActive();

            if (!$config) {
                return $this->json([
                    'success' => false,
                    'message' => 'No active MikroTik configuration'
                ]);
            }

            $api = new MikrotikService(
                $config['host'],
                $config['port'],
                $config['username'],
                $config['password']
            );

            // Use new method that includes traffic stats
            $interfaces = $api->getInterfacesWithTraffic();
            $queues = $api->getSimpleQueues();

            return $this->json([
                'success' => true,
                'data' => [
                    'interfaces' => $interfaces,
                    'queues' => $queues
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get monitor data
     * (Retained if individual monitoring is needed, but main view polls getInterfaces)
     */
    public function monitor()
    {
        // ... implementation same as before or updated ...
        return $this->json(['success' => false, 'message' => 'Use getInterfaces for bulk data']);
    }

    /**
     * API: Get logs
     */
    public function getLogs()
    {
        $this->requireAuth();

        try {
            $limit = $this->post('limit', 100);
            $searchTerm = $this->post('search', '');

            $config = MikrotikSetting::getActive();

            if (!$config) {
                return $this->json([
                    'success' => false,
                    'message' => 'No active MikroTik configuration'
                ]);
            }

            $api = new MikrotikService(
                $config['host'],
                $config['port'],
                $config['username'],
                $config['password']
            );

            $logs = $api->getLogs($limit, $searchTerm);

            return $this->json([
                'success' => true,
                'data' => $logs,
                'total' => count($logs)
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get system resource info
     */
    public function resourceInfo()
    {
        $this->requireAuth();

        try {
            $config = MikrotikSetting::getActive();

            if (!$config) {
                return $this->json([
                    'success' => false,
                    'message' => 'Tidak ada konfigurasi MikroTik yang aktif.'
                ]);
            }

            $api = new MikrotikService(
                $config['host'],
                $config['port'],
                $config['username'],
                $config['password']
            );

            if (!$api->testConnection()) {
                return $this->json([
                    'success' => false,
                    'message' => 'Koneksi ke MikroTik gagal!'
                ]);
            }

            $resource = $api->getSystemResource();

            return $this->json([
                'success' => true,
                'data' => $resource
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Ping address
     */
    public function ping()
    {
        $this->requireAuth();

        try {
            $address = $this->post('address');
            $count = $this->post('count', 4);

            if (empty($address)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Address is required'
                ]);
            }

            $config = MikrotikSetting::getActive();

            if (!$config) {
                return $this->json([
                    'success' => false,
                    'message' => 'No active MikroTik configuration'
                ]);
            }

            $api = new MikrotikService(
                $config['host'],
                $config['port'],
                $config['username'],
                $config['password']
            );

            $result = $api->pingAddress($address, $count);

            return $this->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
