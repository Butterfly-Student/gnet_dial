<?php
namespace Controllers;

use Services\SessionService;
use Services\MikrotikService;
use Models\MikrotikSetting;
use Models\PppProfile;

class PppProfileController extends BaseController
{
    private function getMikrotikService($mikrotikId = null)
    {
        if ($mikrotikId) {
            $config = MikrotikSetting::find($mikrotikId);
        } else {
            $config = MikrotikSetting::getActive();
        }

        if (!$config) {
            throw new \Exception('Konfigurasi MikroTik tidak ditemukan atau tidak aktif.');
        }

        $api = new MikrotikService(
            $config['host'],
            $config['port'],
            $config['username'],
            $config['password']
        );

        if (!$api->testConnection()) {
            throw new \Exception('Koneksi ke MikroTik gagal! Periksa konfigurasi.');
        }

        return $api;
    }

    public function index()
    {
        $this->requireAuth();
        $flash = SessionService::getFlash();
        $this->view('ppp_profiles/index', ['flash' => $flash]);
    }

    public function getList()
    {
        $this->requireAuth();
        try {
            $searchTerm = $this->post('search_term', '');
            $page = (int)$this->post('page', 1);
            $limit = $this->post('limit', 25);

            $mikrotikId = (int)$this->post('mikrotik_id', 0);

            // If no ID passed, try to get active or from session
            if (!$mikrotikId) {
                $active = MikrotikSetting::getActive();
                $mikrotikId = $active['id'] ?? 0;
            }

            $sql = "SELECT * FROM ppp_profiles WHERE 1=1";
            $params = [];

            if ($mikrotikId) {
                $sql .= " AND mikrotik_id = ?";
                $params[] = $mikrotikId;
            }

            if (!empty($searchTerm)) {
                $sql .= " AND name LIKE ?";
                $term = "%$searchTerm%";
                $params[] = $term;
            }

            $sql .= " ORDER BY name ASC";

            $stmt = PppProfile::query($sql, $params);
            $profiles = $stmt->fetchAll();

            $total = count($profiles);

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
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function add()
    {
        $this->requireAuth();

        $activeMikrotik = MikrotikSetting::getActive();
        if (!$activeMikrotik) {
            return $this->json(['success' => false, 'message' => 'Tidak ada MikroTik aktif.']);
        }

        $data = [
            'name' => $this->post('name'),
            'local_address' => $this->post('local_address'),
            'remote_address' => $this->post('remote_address'),
            'rate_limit' => $this->post('rate_limit'),
            'parent_queue' => $this->post('parent_queue'),
            'price' => $this->post('price', 0),
            'tax_rate' => $this->post('tax_rate', 0),
            'mikrotik_id' => $activeMikrotik['id']
        ];

        if (empty($data['name'])) {
            return $this->json(['success' => false, 'message' => 'Nama profile wajib diisi.']);
        }

        // Check duplicates for this Mikrotik
        // Assuming names are unique per mikrotik? Or global?
        // Let's assume unique globally for now as per schema
        if (PppProfile::whereFirst('name', $data['name'])) {
            return $this->json(['success' => false, 'message' => 'Nama profile sudah digunakan.']);
        }

        try {
            PppProfile::beginTransaction();

            PppProfile::create($data);

            $api = $this->getMikrotikService($data['mikrotik_id']);
            $api->addPppProfile(
                $data['name'],
                $data['local_address'],
                $data['remote_address'],
                $data['rate_limit'],
                $data['parent_queue']
            );

            PppProfile::commit();

            return $this->json(['success' => true, 'message' => 'Profile berhasil ditambahkan.']);

        } catch (\Exception $e) {
            PppProfile::rollBack();
            return $this->json(['success' => false, 'message' => 'Gagal menambah profile: ' . $e->getMessage()]);
        }
    }

    public function update()
    {
        $this->requireAuth();

        $id = $this->post('id');
        $data = [
            'name' => $this->post('name'),
            'local_address' => $this->post('local_address'),
            'remote_address' => $this->post('remote_address'),
            'rate_limit' => $this->post('rate_limit'),
            'parent_queue' => $this->post('parent_queue'),
            'price' => $this->post('price', 0),
            'tax_rate' => $this->post('tax_rate', 0)
        ];

        $profile = PppProfile::find($id);
        if (!$profile) {
            return $this->json(['success' => false, 'message' => 'Profile tidak ditemukan.']);
        }

        // Handle rename if needed (similar to Customer)
        $oldName = $profile['name'];
        $newName = $data['name'];

        if ($oldName !== $newName) {
            if (PppProfile::whereFirst('name', $newName)) {
                return $this->json(['success' => false, 'message' => 'Nama profile baru sudah digunakan.']);
            }
        }

        try {
            PppProfile::beginTransaction();

            PppProfile::update($id, $data);

            $api = $this->getMikrotikService($profile['mikrotik_id']);

            if ($oldName !== $newName) {
                // Rename logic: Delete old, Add new
                $api->deletePppProfile($oldName);
                $api->addPppProfile(
                    $newName,
                    $data['local_address'],
                    $data['remote_address'],
                    $data['rate_limit'],
                    $data['parent_queue']
                );
            } else {
                $api->updatePppProfile(
                    $oldName,
                    $data['local_address'],
                    $data['remote_address'],
                    $data['rate_limit'],
                    $data['parent_queue'],
                    false // disabled is not really used/supported well in profile update
                );
            }

            PppProfile::commit();
            return $this->json(['success' => true, 'message' => 'Profile berhasil diupdate.']);

        } catch (\Exception $e) {
            PppProfile::rollBack();
            return $this->json(['success' => false, 'message' => 'Gagal update profile: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        $this->requireAuth();
        $id = $this->post('id');

        $profile = PppProfile::find($id);
        if (!$profile) {
            return $this->json(['success' => false, 'message' => 'Profile tidak ditemukan.']);
        }

        try {
            PppProfile::beginTransaction();

            PppProfile::delete($id);

            $api = $this->getMikrotikService($profile['mikrotik_id']);

            try {
                $api->deletePppProfile($profile['name']);
            } catch (\Exception $mkError) {
                if (stripos($mkError->getMessage(), 'tidak ditemukan') === false) {
                    throw $mkError;
                }
            }

            PppProfile::commit();
            return $this->json(['success' => true, 'message' => 'Profile berhasil dihapus.']);

        } catch (\Exception $e) {
            PppProfile::rollBack();
            return $this->json(['success' => false, 'message' => 'Gagal menghapus profile: ' . $e->getMessage()]);
        }
    }

    public function getOne()
    {
        $this->requireAuth();
        $id = $this->post('id');
        $profile = PppProfile::find($id);

        if (!$profile) {
             return $this->json(['success' => false, 'message' => 'Profile tidak ditemukan']);
        }

        return $this->json(['success' => true, 'data' => $profile]);
    }

    public function syncFromMikrotik()
    {
        $this->requireAuth();

        try {
            $mikrotikId = (int)$this->post('mikrotik_id', 0);

            // If no ID passed, try to get active
            if (!$mikrotikId) {
                $active = MikrotikSetting::getActive();
                if (!$active) {
                    return $this->json(['success' => false, 'message' => 'Tidak ada MikroTik aktif.']);
                }
                $mikrotikId = $active['id'];
            }

            $api = $this->getMikrotikService($mikrotikId);

            // Note: getMikrotikService checks connection, so we are good to go.
            // But we need to call syncProfilesToDatabase on the service instance.

            $result = $api->syncProfilesToDatabase($mikrotikId);

            return $this->json([
                'success' => true,
                'message' => "Sinkronisasi selesai. Ditambahkan: " . $result['added'] . ", Total: " . $result['total']
            ]);

        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'Gagal sinkronisasi: ' . $e->getMessage()]);
        }
    }
}
