<?php
namespace Controllers;

use Services\SessionService;
use Services\MikrotikService;
use Models\MikrotikSetting;
use Models\PppProfile;

class PppProfileController extends BaseController
{
    private function getMikrotikService()
    {
        $config = MikrotikSetting::getActive();
        if (!$config) {
            throw new \Exception('Tidak ada konfigurasi MikroTik yang aktif.');
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

            $sql = "SELECT * FROM ppp_profiles";
            $params = [];

            if (!empty($searchTerm)) {
                $sql .= " WHERE name LIKE ?";
                $term = "%$searchTerm%";
                $params = [$term];
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

        $data = [
            'name' => $this->post('name'),
            'local_address' => $this->post('local_address'),
            'remote_address' => $this->post('remote_address'),
            'rate_limit' => $this->post('rate_limit'),
            'parent_queue' => $this->post('parent_queue')
        ];

        if (empty($data['name'])) {
            return $this->json(['success' => false, 'message' => 'Nama profile wajib diisi.']);
        }

        if (PppProfile::whereFirst('name', $data['name'])) {
            return $this->json(['success' => false, 'message' => 'Nama profile sudah digunakan.']);
        }

        try {
            PppProfile::beginTransaction();

            PppProfile::create($data);

            $api = $this->getMikrotikService();
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
            'parent_queue' => $this->post('parent_queue')
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

            $api = $this->getMikrotikService();

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

            $api = $this->getMikrotikService();

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
}
