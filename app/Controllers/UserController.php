<?php
namespace Controllers;

use Models\User;
use Models\MikrotikSetting;
use Services\SessionService;

/**
 * User Controller
 */
class UserController extends BaseController {
    
    /**
     * API: Get all users
     */
    public function getAll() {
        $this->requireAuth();
        
        $currentUser = \Services\AuthService::user();
        
        try {
            $users = User::getAllWithDetails($currentUser);
            
            if (empty($users) && $currentUser['role'] !== 'superadmin' && $currentUser['role'] !== 'admin') {
                 return $this->json(['success' => false, 'message' => 'Access denied']);
            }
            
            return $this->json(['success' => true, 'data' => $users]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * API: Add user
     */
    public function add() {
        $this->requireAuth();
        
        $currentUser = \Services\AuthService::user();
        if (!isSuperAdmin() && $currentUser['role'] !== 'admin') {
            return $this->json(['success' => false, 'message' => 'Access denied']);
        }
        
        $data = [
            'username' => $this->post('username'),
            'password' => $this->post('password'),
            'email' => $this->post('email'),
            'fullname' => $this->post('fullname'),
            'role' => $this->post('role', 'user'),
            'mikrotik_id' => !empty($this->post('mikrotik_id')) ? (int)$this->post('mikrotik_id') : null
        ];
        
        // Admin restrictions
        if ($currentUser['role'] === 'admin') {
            $data['role'] = 'user';
            $data['mikrotik_id'] = $currentUser['mikrotik_id'];
        }
        
        if (empty($data['username']) || empty($data['password'])) {
            return $this->json(['success' => false, 'message' => 'Username and password are required']);
        }
        
        if ($data['role'] !== 'superadmin' && empty($data['mikrotik_id'])) {
            return $this->json(['success' => false, 'message' => 'Non-superadmin users must be assigned to a MikroTik device']);
        }
        
        try {
            $userId = User::createUser($data);
            return $this->json(['success' => true, 'message' => 'User added successfully', 'id' => $userId]);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * API: Update user
     */
    public function update() {
        $this->requireAuth();
        
        $currentUser = \Services\AuthService::user();
        if (!isSuperAdmin() && $currentUser['role'] !== 'admin') {
            return $this->json(['success' => false, 'message' => 'Access denied']);
        }
        
        $id = $this->post('id');
        $data = [
            'username' => $this->post('username'),
            'email' => $this->post('email'),
            'fullname' => $this->post('fullname'),
            'role' => $this->post('role', 'user'),
            'mikrotik_id' => !empty($this->post('mikrotik_id')) ? (int)$this->post('mikrotik_id') : null
        ];
        
        $password = $this->post('password');
        if (!empty($password)) {
            $data['password'] = $password;
        }
        
        // Admin restrictions
        if ($currentUser['role'] === 'admin') {
            $data['role'] = 'user';
            $data['mikrotik_id'] = $currentUser['mikrotik_id'];
        }
        
        try {
            User::updateUser($id, $data);
            return $this->json(['success' => true, 'message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * API: Delete user
     */
    public function delete() {
        $this->requireAuth();
        
        $currentUser = \Services\AuthService::user();
        if (!isSuperAdmin() && $currentUser['role'] !== 'admin') {
            return $this->json(['success' => false, 'message' => 'Access denied']);
        }
        
        $id = $this->post('id');
        if ($id == $currentUser['id']) {
            return $this->json(['success' => false, 'message' => 'You cannot delete yourself']);
        }
        
        try {
            User::delete($id);
            return $this->json(['success' => true, 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
