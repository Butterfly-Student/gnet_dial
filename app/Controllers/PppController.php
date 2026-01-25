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
}
