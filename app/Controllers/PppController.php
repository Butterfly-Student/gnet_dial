<?php
namespace Controllers;

use Services\SessionService;
use Services\MikrotikService;
use Models\MikrotikSetting;

/**
 * PPP Controller
 * 
 * Handles PPP user management (active and non-active)
 */
class PppController extends BaseController
{

    /**
     * Show active PPP users page
     */
    public function active()
    {
        $this->requireAuth();

        $flash = SessionService::getFlash();

        $this->view('ppp/active', [
            'flash' => $flash
        ]);
    }

    /**
     * Show non-active PPP users page
     */
    public function nonActive()
    {
        $this->requireAuth();

        $flash = SessionService::getFlash();

        $this->view('ppp/non_active', [
            'flash' => $flash
        ]);
    }

    /**
     * API: Get all active PPP users
     */
    public function getActiveUsers()
    {
        $this->requireAuth();

        try {
            // Get active MikroTik configuration
            $config = MikrotikSetting::getActive();

            if (!$config) {
                return $this->json([
                    'success' => false,
                    'message' => 'Tidak ada konfigurasi MikroTik yang aktif. Silakan atur di Settings.'
                ]);
            }

            // Connect to MikroTik
            $api = new MikrotikService(
                $config['host'],
                $config['port'],
                $config['username'],
                $config['password']
            );

            if (!$api->testConnection()) {
                return $this->json([
                    'success' => false,
                    'message' => 'Koneksi ke MikroTik gagal! Periksa konfigurasi.'
                ]);
            }

            $activeUsers = $api->getPPPActive();

            return $this->json([
                'success' => true,
                'data' => $activeUsers,
                'total' => count($activeUsers)
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get all non-active PPP users
     */
    public function getNonActiveUsers()
    {
        $this->requireAuth();

        try {
            $config = MikrotikSetting::getActive();

            if (!$config) {
                return $this->json([
                    'success' => false,
                    'message' => 'Tidak ada konfigurasi MikroTik yang aktif. Silakan atur di Settings.'
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
                    'message' => 'Koneksi ke MikroTik gagal! Periksa konfigurasi.'
                ]);
            }

            $inactiveUsers = $api->getNonActiveSecrets();

            return $this->json([
                'success' => true,
                'data' => $inactiveUsers,
                'total' => count($inactiveUsers)
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Search PPP users
     */
    public function search()
    {
        $this->requireAuth();

        try {
            $searchTerm = $this->post('search_term');
            $searchType = $this->post('search_type', 'all'); // 'active', 'non_active', 'all'

            if (empty($searchTerm)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Search term tidak boleh kosong'
                ]);
            }

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

            // Search based on type
            if ($searchType === 'active') {
                $results = $api->searchActivePPP($searchTerm);
                return $this->json([
                    'success' => true,
                    'data' => $results,
                    'total' => count($results),
                    'search_term' => $searchTerm
                ]);
            } elseif ($searchType === 'non_active') {
                $results = $api->searchNonActivePPP($searchTerm);
                return $this->json([
                    'success' => true,
                    'data' => $results,
                    'total' => count($results),
                    'search_term' => $searchTerm
                ]);
            } else {
                $results = $api->searchSecrets($searchTerm);
                return $this->json([
                    'success' => true,
                    'data' => $results,
                    'search_term' => $searchTerm
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
     * API: Disconnect a user
     */
    public function disconnect()
    {
        $this->requireAuth();

        try {
            $username = $this->post('username');

            if (empty($username)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Username tidak boleh kosong'
                ]);
            }

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

            $api->disconnectPppActive($username);

            return $this->json([
                'success' => true,
                'message' => "User '$username' berhasil didisconnect",
                'username' => $username
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Disconnect multiple users
     */
    public function disconnectMultiple()
    {
        $this->requireAuth();

        try {
            $usernames = $this->post('usernames', []);

            // If usernames is sent as JSON string, decode it
            if (is_string($usernames)) {
                $usernames = json_decode($usernames, true);
            }

            if (empty($usernames) || !is_array($usernames)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Daftar username tidak valid atau kosong'
                ]);
            }

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

            $results = $api->disconnectMultiplePppActive($usernames);

            // Calculate success and failed counts
            $successCount = 0;
            $failedCount = 0;
            foreach ($results as $result) {
                if (isset($result['success']) && $result['success']) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            }

            return $this->json([
                'success' => true,
                'message' => "Selesai memproses " . count($usernames) . " user. Berhasil: $successCount, Gagal: $failedCount",
                'results' => $results,
                'summary' => [
                    'total' => count($usernames),
                    'success' => $successCount,
                    'failed' => $failedCount
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
     * API: Toggle Isolir/Restore user
     */
    public function toggleIsolir()
    {
        $this->requireAuth();

        try {
            $username = $this->post('username');
            $action = $this->post('action'); // 'isolir' or 'restore'

            if (empty($username) || empty($action)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Username dan action harus diisi'
                ]);
            }

            // Load Isolir config
            $configFile = BASE_PATH . '/config/Isolir.json';
            if (!file_exists($configFile)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Konfigurasi Isolir tidak ditemukan'
                ]);
            }

            $isolirConfig = json_decode(file_get_contents($configFile), true);
            $targetProfile = ($action === 'isolir') ? $isolirConfig['isolir_profile'] : $isolirConfig['normal_profile'];

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

            $api->updatePppSecretProfile($username, $targetProfile);

            return $this->json([
                'success' => true,
                'message' => "User '$username' berhasil di-" . ($action === 'isolir' ? 'isolir' : 'restore'),
                'new_profile' => $targetProfile
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get Isolir Config
     */
    public function getIsolirConfig()
    {
        $this->requireAuth();

        $configFile = BASE_PATH . '/config/Isolir.json';
        if (file_exists($configFile)) {
            $config = json_decode(file_get_contents($configFile), true);
            return $this->json([
                'success' => true,
                'data' => $config
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Config not found'
        ]);
    }

    /**
     * API: Get queue traffic data for a user or multiple users
     */
    public function getQueueTraffic()
    {
        $this->requireAuth();

        try {
            $usernames = $this->post('usernames', []);

            // If single username provided as string
            if (is_string($usernames) && !empty($usernames)) {
                $usernames = [$usernames];
            }

            // If sent as JSON string, decode it
            if (is_string($usernames)) {
                $usernames = json_decode($usernames, true);
            }

            if (empty($usernames) || !is_array($usernames)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Daftar username tidak valid atau kosong'
                ]);
            }

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

            $trafficData = [];

            // Get traffic for each user
            foreach ($usernames as $username) {
                try {
                    $queueData = $api->getQueueTrafficByUsername($username);

                    if ($queueData) {
                        // Parse traffic data
                        $traffic = [
                            'rx-rate' => '0',
                            'tx-rate' => '0',
                            'bytes-up' => '0',
                            'bytes-down' => '0',
                            'max-limit' => 'Unlimited'
                        ];

                        // Parse rate (format: "uploadbps/downloadbps")
                        if (isset($queueData['rate'])) {
                            $rateParts = explode('/', str_replace('bps', '', $queueData['rate']));
                            $traffic['tx-rate'] = isset($rateParts[0]) ? trim($rateParts[0]) : '0';
                            $traffic['rx-rate'] = isset($rateParts[1]) ? trim($rateParts[1]) : '0';
                        }

                        // Parse bytes (format: "upload/download")
                        if (isset($queueData['bytes'])) {
                            $bytesParts = explode('/', $queueData['bytes']);
                            $traffic['bytes-up'] = isset($bytesParts[0]) ? trim($bytesParts[0]) : '0';
                            $traffic['bytes-down'] = isset($bytesParts[1]) ? trim($bytesParts[1]) : '0';
                        }

                        // Get max limit
                        if (isset($queueData['max-limit'])) {
                            $traffic['max-limit'] = $queueData['max-limit'];
                        }

                        $trafficData[$username] = $traffic;
                    } else {
                        // No queue found for user
                        $trafficData[$username] = null;
                    }
                } catch (\Exception $e) {
                    $trafficData[$username] = null;
                }
            }

            return $this->json([
                'success' => true,
                'data' => $trafficData,
                'total' => count($trafficData)
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show PPP Secrets management page
     */
    public function secrets()
    {
        $this->requireAuth();

        $flash = SessionService::getFlash();

        $this->view('ppp/secrets', [
            'flash' => $flash
        ]);
    }

    /**
     * API: Get all PPP Secrets with search and pagination
     */
    public function getSecrets()
    {
        $this->requireAuth();

        try {
            $searchTerm = $this->post('search_term', '');
            $page = $this->post('page', 1);
            $limit = $this->post('limit', 25);

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

            $secrets = $api->getPPPSecrets();

            // Filter by search term
            if (!empty($searchTerm)) {
                $secrets = array_filter($secrets, function($secret) use ($searchTerm) {
                    $name = $secret['name'] ?? '';
                    $profile = $secret['profile'] ?? '';
                    return stripos($name, $searchTerm) !== false || stripos($profile, $searchTerm) !== false;
                });
            }

            // Sort by name
            usort($secrets, function($a, $b) {
                return strcmp($a['name'] ?? '', $b['name'] ?? '');
            });

            $total = count($secrets);

            // Pagination
            if ($limit !== 'all') {
                $limit = (int)$limit;
                $offset = ($page - 1) * $limit;
                $secrets = array_slice($secrets, $offset, $limit);
            }

            return $this->json([
                'success' => true,
                'data' => $secrets,
                'total' => $total,
                'page' => $page,
                'limit' => $limit
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get single PPP Secret
     */
    public function getSecret()
    {
        $this->requireAuth();

        try {
            $username = $this->post('username');

            if (empty($username)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Username harus diisi'
                ]);
            }

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

            $secret = $api->getPppSecretByName($username);

            if (!$secret) {
                return $this->json([
                    'success' => false,
                    'message' => "Secret '$username' tidak ditemukan"
                ]);
            }

            // Remove sensitive data
            unset($secret['password']);

            return $this->json([
                'success' => true,
                'data' => $secret
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Add new PPP Secret
     */
    public function addSecret()
    {
        $this->requireAuth();

        try {
            $username = $this->post('username');
            $password = $this->post('password');
            $profile = $this->post('profile');

            if (empty($username) || empty($password) || empty($profile)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Username, password, dan profile harus diisi'
                ]);
            }

            if (strlen($password) < 4) {
                return $this->json([
                    'success' => false,
                    'message' => 'Password minimal 4 karakter'
                ]);
            }

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

            // Check if username already exists
            $existing = $api->getPppSecretByName($username);
            if ($existing) {
                return $this->json([
                    'success' => false,
                    'message' => "Username '$username' sudah ada"
                ]);
            }

            $api->addPppSecret($username, $password, $profile);

            return $this->json([
                'success' => true,
                'message' => "PPP Secret '$username' berhasil ditambahkan"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Update PPP Secret
     */
    public function updateSecret()
    {
        $this->requireAuth();

        try {
            $username = $this->post('username');
            $password = $this->post('password');
            $profile = $this->post('profile');
            $disabled = $this->post('disabled', 'false') === 'true';

            if (empty($username) || empty($profile)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Username dan profile harus diisi'
                ]);
            }

            if (!empty($password) && strlen($password) < 4) {
                return $this->json([
                    'success' => false,
                    'message' => 'Password minimal 4 karakter'
                ]);
            }

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

            $api->updatePppSecret($username, $password, $profile, $disabled);

            return $this->json([
                'success' => true,
                'message' => "PPP Secret '$username' berhasil diupdate"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Delete PPP Secret
     */
    public function deleteSecret()
    {
        $this->requireAuth();

        try {
            $username = $this->post('username');

            if (empty($username)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Username harus diisi'
                ]);
            }

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

            $api->deletePppSecret($username);

            return $this->json([
                'success' => true,
                'message' => "PPP Secret '$username' berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Toggle PPP Secret Enabled/Disabled
     */
    public function toggleSecret()
    {
        $this->requireAuth();

        try {
            $username = $this->post('username');
            $disabled = $this->post('disabled', 'false') === 'true';

            if (empty($username)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Username harus diisi'
                ]);
            }

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

            $api->setPppSecretDisabled($username, $disabled);

            $action = $disabled ? 'dinonaktifkan' : 'diaktifkan';

            return $this->json([
                'success' => true,
                'message' => "PPP Secret '$username' berhasil $action"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get PPP Profiles
     */
    public function getProfiles()
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

            $profiles = $api->getPppProfiles();

            return $this->json([
                'success' => true,
                'data' => $profiles
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // ============================================================================
    // IP Pool API Methods
    // ============================================================================

    /**
     * API: Get all IP pools
     */
    public function getIpPools()
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

            $pools = $api->getIpPools();

            return $this->json([
                'success' => true,
                'data' => $pools
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get single IP pool
     */
    public function getIpPool()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');

            if (empty($name)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama IP pool harus diisi'
                ]);
            }

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

            $pool = $api->getIpPoolByName($name);

            if (!$pool) {
                return $this->json([
                    'success' => false,
                    'message' => "IP pool '$name' tidak ditemukan"
                ]);
            }

            return $this->json([
                'success' => true,
                'data' => $pool
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Add new IP pool
     */
    public function addIpPool()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');
            $ranges = $this->post('ranges');

            if (empty($name) || empty($ranges)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama dan ranges IP pool harus diisi'
                ]);
            }

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

            $api->addIpPool($name, $ranges);

            return $this->json([
                'success' => true,
                'message' => "IP pool '$name' berhasil ditambahkan"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Update IP pool
     */
    public function updateIpPool()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');
            $ranges = $this->post('ranges');

            if (empty($name) || empty($ranges)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama dan ranges IP pool harus diisi'
                ]);
            }

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

            $api->updateIpPool($name, $ranges);

            return $this->json([
                'success' => true,
                'message' => "IP pool '$name' berhasil diupdate"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Delete IP pool
     */
    public function deleteIpPool()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');

            if (empty($name)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama IP pool harus diisi'
                ]);
            }

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

            $api->deleteIpPool($name);

            return $this->json([
                'success' => true,
                'message' => "IP pool '$name' berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // ============================================================================
    // Parent Queue API Methods
    // ============================================================================

    /**
     * API: Get all parent queues
     */
    public function getParentQueues()
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

            $queues = $api->getParentQueues();

            return $this->json([
                'success' => true,
                'data' => $queues
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get single parent queue
     */
    public function getParentQueue()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');

            if (empty($name)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama parent queue harus diisi'
                ]);
            }

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

            $queue = $api->getParentQueueByName($name);

            if (!$queue) {
                return $this->json([
                    'success' => false,
                    'message' => "Parent queue '$name' tidak ditemukan"
                ]);
            }

            return $this->json([
                'success' => true,
                'data' => $queue
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Add new parent queue
     */
    public function addParentQueue()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');
            $maxLimit = $this->post('max_limit');
            $packetMark = $this->post('packet_mark', '');

            if (empty($name) || empty($maxLimit)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama dan max limit parent queue harus diisi'
                ]);
            }

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

            $api->addParentQueue($name, $maxLimit, $packetMark);

            return $this->json([
                'success' => true,
                'message' => "Parent queue '$name' berhasil ditambahkan"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Update parent queue
     */
    public function updateParentQueue()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');
            $maxLimit = $this->post('max_limit');
            $packetMark = $this->post('packet_mark', '');

            if (empty($name) || empty($maxLimit)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama dan max limit parent queue harus diisi'
                ]);
            }

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

            $api->updateParentQueue($name, $maxLimit, $packetMark);

            return $this->json([
                'success' => true,
                'message' => "Parent queue '$name' berhasil diupdate"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Delete parent queue
     */
    public function deleteParentQueue()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');

            if (empty($name)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama parent queue harus diisi'
                ]);
            }

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

            $api->deleteParentQueue($name);

            return $this->json([
                'success' => true,
                'message' => "Parent queue '$name' berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // ============================================================================
    // PPP Profile API Methods
    // ============================================================================

    /**
     * Show PPP Profiles management page
     */
    public function profiles()
    {
        $this->requireAuth();

        $flash = SessionService::getFlash();

        $this->view('ppp/profiles', [
            'flash' => $flash
        ]);
    }

    /**
     * API: Get all PPP Profiles with full details
     */
    public function getProfilesList()
    {
        $this->requireAuth();

        try {
            $searchTerm = $this->post('search_term', '');
            $page = $this->post('page', 1);
            $limit = $this->post('limit', 25);

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

            $profiles = $api->getPppProfilesFull();

            // Filter by search term
            if (!empty($searchTerm)) {
                $profiles = array_filter($profiles, function($profile) use ($searchTerm) {
                    $name = $profile['name'] ?? '';
                    return stripos($name, $searchTerm) !== false;
                });
            }

            // Sort by name
            usort($profiles, function($a, $b) {
                return strcmp($a['name'] ?? '', $b['name'] ?? '');
            });

            $total = count($profiles);

            // Pagination
            if ($limit !== 'all') {
                $limit = (int)$limit;
                $offset = ($page - 1) * $limit;
                $profiles = array_slice($profiles, $offset, $limit);
            }

            return $this->json([
                'success' => true,
                'data' => $profiles,
                'total' => $total,
                'page' => $page,
                'limit' => $limit
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Get single PPP Profile
     */
    public function getProfile()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');

            if (empty($name)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama profile harus diisi'
                ]);
            }

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

            $profile = $api->getPppProfileByName($name);

            if (!$profile) {
                return $this->json([
                    'success' => false,
                    'message' => "PPP profile '$name' tidak ditemukan"
                ]);
            }

            return $this->json([
                'success' => true,
                'data' => $profile
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Add new PPP Profile
     */
    public function addProfile()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');
            $localAddress = $this->post('local_address');
            $remoteAddress = $this->post('remote_address');
            $rateLimit = $this->post('rate_limit');
            $parentQueue = $this->post('parent_queue');

            if (empty($name) || empty($localAddress) || empty($remoteAddress) || empty($rateLimit) || empty($parentQueue)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Semua field harus diisi'
                ]);
            }

            // Validate rate limit format (upload/download)
            if (!preg_match('/^(\d+[kKMGT]?)\/(\d+[kKMGT]?)$/', $rateLimit)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Format rate limit harus: upload/download (contoh: 512k/1M)'
                ]);
            }

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

            $api->addPppProfile($name, $localAddress, $remoteAddress, $rateLimit, $parentQueue);

            return $this->json([
                'success' => true,
                'message' => "PPP profile '$name' berhasil ditambahkan"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Update PPP Profile
     */
    public function updateProfile()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');
            $localAddress = $this->post('local_address');
            $remoteAddress = $this->post('remote_address');
            $rateLimit = $this->post('rate_limit');
            $parentQueue = $this->post('parent_queue');
            $disabled = $this->post('disabled', 'false') === 'true';
            if (empty($name) || empty($localAddress) || empty($remoteAddress) || empty($rateLimit) || empty($parentQueue)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Semua field harus diisi'
                ]);
            }

            // Validate rate limit format
            if (!preg_match('/^(\d+[kKMGT]?)\/(\d+[kKMGT]?)$/', $rateLimit)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Format rate limit harus: upload/download (contoh: 512k/1M)'
                ]);
            }

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

            $api->updatePppProfile($name, $localAddress, $remoteAddress, $rateLimit, $parentQueue);

            return $this->json([
                'success' => true,
                'message' => "PPP profile '$name' berhasil diupdate"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Delete PPP Profile
     */
    public function deleteProfile()
    {
        $this->requireAuth();

        try {
            $name = $this->post('name');

            if (empty($name)) {
                return $this->json([
                    'success' => false,
                    'message' => 'Nama profile harus diisi'
                ]);
            }

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

            $api->deletePppProfile($name);

            return $this->json([
                'success' => true,
                'message' => "PPP profile '$name' berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * API: Toggle PPP Profile Enabled/Disabled
     */
}
