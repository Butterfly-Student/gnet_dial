<?php
namespace Controllers;

use Services\SessionService;
use Services\MikrotikService;
use Models\MikrotikSetting;
use Models\Customer;

class CustomerController extends BaseController
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
        $this->view('customers/index', ['flash' => $flash]);
    }

    public function getList()
    {
        $this->requireAuth();
        try {
            $searchTerm = $this->post('search_term', '');
            $page = (int)$this->post('page', 1);
            $limit = $this->post('limit', 25);

            // Fetch from DB
            // Note: Since BaseModel doesn't have complex query builder, we might need raw query or fetch all and filter if small dataset.
            // But let's try to be efficient.
            // For now, let's fetch all and filter in PHP as dataset might not be huge yet,
            // OR use raw query if BaseModel supports it. BaseModel has query().

            $mikrotikId = (int)$this->post('mikrotik_id', 0);

            // If no ID passed, try to get active or from session
            if (!$mikrotikId) {
                $active = MikrotikSetting::getActive();
                $mikrotikId = $active['id'] ?? 0;
            }

            $sql = "SELECT * FROM customers WHERE 1=1";
            $params = [];

            if ($mikrotikId) {
                $sql .= " AND mikrotik_id = ?";
                $params[] = $mikrotikId;
            }

            if (!empty($searchTerm)) {
                $sql .= " AND (name LIKE ? OR username LIKE ? OR address LIKE ?)";
                $term = "%$searchTerm%";
                $params[] = $term;
                $params[] = $term;
                $params[] = $term;
            }

            $sql .= " ORDER BY name ASC";

            $stmt = Customer::query($sql, $params);
            $customers = $stmt->fetchAll();

            $total = count($customers);

            if ($limit !== 'all') {
                $limit = (int)$limit;
                $offset = ($page - 1) * $limit;
                $customers = array_slice($customers, $offset, $limit);
            }

            return $this->json([
                'success' => true,
                'data' => $customers,
                'total' => $total,
                'page' => $page,
                'limit' => $limit
            ]);

        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getOne()
    {
        $this->requireAuth();
        try {
            $id = $this->post('id');
            $customer = Customer::find($id);

            if (!$customer) {
                return $this->json(['success' => false, 'message' => 'Customer tidak ditemukan']);
            }

            return $this->json(['success' => true, 'data' => $customer]);
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
            'username' => $this->post('username'),
            'password' => $this->post('password'),
            'profile' => $this->post('profile'),
            'coordinates' => $this->post('coordinates'),
            'address' => $this->post('address'),
            'status' => 'active', // Default
            'service' => 'pppoe', // Default
            'mikrotik_id' => $activeMikrotik['id']
        ];

        if (empty($data['name']) || empty($data['username']) || empty($data['password']) || empty($data['profile'])) {
            return $this->json(['success' => false, 'message' => 'Semua field wajib diisi kecuali koordinat dan alamat.']);
        }

        // Check duplicate username in DB for this Mikrotik (assuming username unique per mikrotik or global?)
        // Usually unique per Mikrotik. But table has UNIQUE constraint on username globally in Schema V1.
        // If Schema V2 didn't drop unique constraint, it's global.
        // Ideally should be unique per mikrotik_id, but keeping global unique for simplicity if schema enforces it.
        // Let's assume global unique for now as per migration script.
        if (Customer::whereFirst('username', $data['username'])) {
            return $this->json(['success' => false, 'message' => 'Username sudah digunakan.']);
        }

        try {
            Customer::beginTransaction();

            // 1. Insert to DB
            $id = Customer::create($data);

            // 2. Sync to Mikrotik
            $api = $this->getMikrotikService($data['mikrotik_id']);
            $api->addPppSecret($data['username'], $data['password'], $data['profile']);

            Customer::commit();

            return $this->json(['success' => true, 'message' => 'Customer berhasil ditambahkan.']);

        } catch (\Exception $e) {
            Customer::rollBack();
            return $this->json(['success' => false, 'message' => 'Gagal menambah customer: ' . $e->getMessage()]);
        }
    }

    public function update()
    {
        $this->requireAuth();

        $id = $this->post('id');
        $data = [
            'name' => $this->post('name'),
            'username' => $this->post('username'), // Usually username cannot be changed easily in Mikrotik without removing/adding, but let's assume username is key.
            // If username changes, we need to handle rename or delete/add. For simplicity, let's allow password/profile/etc update.
            // If username matches existing, it's fine.
            'password' => $this->post('password'),
            'profile' => $this->post('profile'),
            'coordinates' => $this->post('coordinates'),
            'address' => $this->post('address'),
            'status' => $this->post('status')
        ];

        $customer = Customer::find($id);
        if (!$customer) {
            return $this->json(['success' => false, 'message' => 'Customer tidak ditemukan.']);
        }

        // If username changed, we have complex logic. For now, let's prevent username change or handle it carefully.
        // User didn't specify requirements for username change.
        // Mikrotik API identifies by name (username). If we change name in DB, we must change in Mikrotik.

        $oldUsername = $customer['username'];
        $newUsername = $data['username'];
        $usernameChanged = $oldUsername !== $newUsername;

        if ($usernameChanged && Customer::whereFirst('username', $newUsername)) {
             return $this->json(['success' => false, 'message' => 'Username baru sudah digunakan.']);
        }

        try {
            Customer::beginTransaction();

            // 1. Update DB
            // Only update password if provided
            if (empty($data['password'])) {
                unset($data['password']);
                $passwordToSend = null; // Tell Mikrotik service to not update password
            } else {
                $passwordToSend = $data['password'];
            }

            Customer::update($id, $data);

            // 2. Sync Mikrotik
            $api = $this->getMikrotikService($customer['mikrotik_id']);

            if ($usernameChanged) {
                // To rename in Mikrotik, we usually set 'name' property.
                // MikrotikService::updatePppSecret uses `comm('/ppp/secret/set', ...)` where .id is found by OLD name.
                // But `updatePppSecret` in MikrotikService.php implementation:
                /*
                  $secrets = $this->api->comm('/ppp/secret/print', ['?name' => $name]);
                  ...
                  $params = ['.id' => $secretId, 'profile' => $profile];
                  ...
                  $this->api->comm('/ppp/secret/set', $params);
                */
                // It does NOT support renaming (changing 'name').
                // So if username changes, we might need to delete and add, or update MikrotikService to support rename.
                // Given the constraints, I will throw error if username changes OR try to delete/add.
                // Safer: Delete old, Add new.

                $api->deletePppSecret($oldUsername);
                $api->addPppSecret($newUsername, $data['password'] ?? $customer['password'], $data['profile'], $data['status'] !== 'active');
            } else {
                // Normal update
                // Determine disabled status
                $disabled = ($data['status'] !== 'active');
                $api->updatePppSecret($oldUsername, $passwordToSend, $data['profile'], $disabled);
            }

            Customer::commit();
            return $this->json(['success' => true, 'message' => 'Customer berhasil diupdate.']);

        } catch (\Exception $e) {
            Customer::rollBack();
            return $this->json(['success' => false, 'message' => 'Gagal update customer: ' . $e->getMessage()]);
        }
    }

    public function delete()
    {
        $this->requireAuth();
        $id = $this->post('id');

        $customer = Customer::find($id);
        if (!$customer) {
            return $this->json(['success' => false, 'message' => 'Customer tidak ditemukan.']);
        }

        try {
            Customer::beginTransaction();

            // 1. Delete from DB
            Customer::delete($id);

            // 2. Delete from Mikrotik
            $api = $this->getMikrotikService($customer['mikrotik_id']);
            // Check if exists first to avoid error? deletePppSecret handles "not found" by throwing error?
            // MikrotikService::deletePppSecret throws exception if not found.
            // We should catch that specifically or check existence.
            // But if it's not in Mikrotik, we still want to delete from DB?
            // User said "Sync". If it fails on Mikrotik, we rollback.
            // But if it's already gone from Mikrotik, we should probably allow DB delete.

            try {
                $api->deletePppSecret($customer['username']);
            } catch (\Exception $mkError) {
                // If error is "not found", ignore.
                if (stripos($mkError->getMessage(), 'tidak ditemukan') === false) {
                    throw $mkError;
                }
            }

            Customer::commit();
            return $this->json(['success' => true, 'message' => 'Customer berhasil dihapus.']);

        } catch (\Exception $e) {
            Customer::rollBack();
            return $this->json(['success' => false, 'message' => 'Gagal menghapus customer: ' . $e->getMessage()]);
        }
    }
}
