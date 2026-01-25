<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto p-2 sm:p-6 lg:p-8">

  <!-- Toast Container -->
  <div id="toast-container" class="fixed top-4 right-4 z-[100] max-w-xs sm:max-w-sm"></div>

  <!-- Header -->
  <div class="mb-6 sm:mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pengaturan</h1>
    <p class="text-sm sm:text-base text-gray-500 mt-1">Kelola konfigurasi MikroTik dan pengguna aplikasi.</p>
  </div>

  <!-- Tabs Navigation -->
  <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200">
    <button onclick="switchTab('mikrotik')" id="mikrotik-tab"
      class="pb-3 px-4 text-sm sm:text-base font-medium border-b-2 transition-colors duration-200 text-blue-600 border-blue-600">
      <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-router"><rect width="20" height="8" x="2" y="14" rx="2"/><path d="M6.01 18h.01"/><path d="M10.01 18h.01"/><path d="M15 10v4"/><path d="M17.84 7.17a4 4 0 0 0-5.66 0"/><path d="M20.66 4.34a8 8 0 0 0-11.31 0"/></svg>
        MikroTik
      </div>
    </button>

    <?php if ($canManageUsers): ?>
    <button onclick="switchTab('users')" id="users-tab"
      class="pb-3 px-4 text-sm sm:text-base font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors duration-200">
      <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Users
      </div>
    </button>
    <?php endif; ?>
  </div>

  <!-- MikroTik Settings Tab -->
  <div id="mikrotik-content" class="space-y-6">
    <!-- Add New Configuration -->
    <?php 
    $showAddForm = isSuperAdmin() || $_SESSION['role'] === 'admin';
    if ($showAddForm): 
    ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
      <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div>
          <h2 class="text-lg sm:text-xl font-bold text-gray-800">Tambah Router</h2>
          <p class="text-sm text-gray-500">Hubungkan perangkat MikroTik baru</p>
        </div>
        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </div>
      </div>

      <form id="addMikroTikForm" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Nama Konfigurasi</label>
            <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="Contoh: Router Utama">
          </div>

          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Host / IP Address</label>
            <input type="text" name="host" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="192.168.88.1">
          </div>

          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Port API</label>
            <input type="number" name="port" value="8728" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow">
          </div>

          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="admin">
          </div>

          <div class="space-y-1.5 md:col-span-2">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="••••••••">
          </div>

          <div class="md:col-span-2">
            <?php if (isSuperAdmin()): ?>
            <label class="inline-flex items-center group cursor-pointer">
              <input type="checkbox" name="is_active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-4 w-4">
              <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">Jadikan koneksi utama (Dashboard Superadmin)</span>
            </label>
            <?php endif; ?>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
          <button type="button" onclick="testMikroTikConnection('addMikroTikForm')" class="px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plug-zap"><path d="M6.3 20.3a2.4 2.4 0 0 0 3.4 0L12 18l-6-6-2.3 2.3a2.4 2.4 0 0 0 0 3.4Z"/><path d="M2 22l3-3"/><path d="M7.5 13.5 10 11"/><path d="M10.5 16.5 13 14"/><path d="m18 3-4 4h6l-4 4"/></svg>
            Test Koneksi
          </button>
          <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm hover:shadow transition-all flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg>
            Simpan Router
          </button>
        </div>
      </form>
    </div>
    <?php endif; ?>

    <!-- Existing Configurations List -->
    <div id="mikrotik-list" class="space-y-4">
      <!-- Populated by JS -->
    </div>
  </div>

  <?php if ($canManageUsers): ?>
  <!-- Users Management Tab -->
  <div id="users-content" class="hidden space-y-6">
    <!-- Add New User -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
       <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div>
          <h2 class="text-lg sm:text-xl font-bold text-gray-800">Tambah Pengguna</h2>
          <p class="text-sm text-gray-500">Buat akun baru untuk akses aplikasi</p>
        </div>
        <div class="p-2 bg-green-50 text-green-600 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
        </div>
      </div>

      <form id="addUserForm" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="fullname" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="John Doe">
          </div>
          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Username</label>
            <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="john.doe">
          </div>
          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Email (Opsional)</label>
            <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="john@example.com">
          </div>
          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" placeholder="••••••••">
          </div>
          
          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Assign Router</label>
            <?php if (isSuperAdmin()): ?>
            <select name="mikrotik_id" id="add_mikrotik_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow">
              <option value="">-- Pilih Router (Opsional) --</option>
              <?php foreach ($configs as $config): ?>
                <option value="<?= $config['id'] ?>"><?= htmlspecialchars($config['name']) ?> (<?= htmlspecialchars($config['host']) ?>)</option>
              <?php endforeach; ?>
            </select>
            <p class="text-xs text-gray-500">Admin harus di-assign ke satu router.</p>
            <?php else: ?>
            <input type="hidden" name="mikrotik_id" value="<?= $currentUser['mikrotik_id'] ?>">
            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-600 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock mr-2 text-gray-400"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <?= htmlspecialchars($currentUser['mikrotik_id'] ? ($configs[0]['name'] ?? 'Router Anda') : 'Tidak ada router') ?>
            </div>
            <?php endif; ?>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs sm:text-sm font-medium text-gray-700">Role</label>
            <select name="role" id="add_role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow">
              <option value="user">User</option>
              <?php if (isSuperAdmin()): ?>
              <option value="admin">Admin</option>
              <option value="superadmin">Superadmin</option>
              <?php endif; ?>
            </select>
          </div>
        </div>

        <div class="flex justify-end pt-2">
          <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm hover:shadow transition-all flex items-center gap-2">
             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
             Simpan Pengguna
          </button>
        </div>
      </form>
    </div>

    <!-- User List -->
    <div id="users-list">
       <!-- Populated by JS -->
    </div>
  </div>
  <?php endif; ?>

  <!-- Modals -->
  <!-- Edit User Modal -->
  <div id="editUserModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/50 transition-opacity backdrop-blur-md"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-left w-full">
                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modal-title">Edit Pengguna</h3>
                <form id="editUserForm" class="space-y-4">
                  <input type="hidden" name="id" id="edit_user_id">
                  
                  <div class="grid grid-cols-1 gap-4">
                     <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="fullname" id="edit_user_fullname" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                     </div>
                     <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="edit_user_username" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                     </div>
                     <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="edit_user_email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                     </div>
                     <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Password Baru <span class="text-gray-400 font-normal">(Biarkan kosong jika tidak ubah)</span></label>
                        <input type="password" name="password" id="edit_user_password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                     </div>
                     <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Assign Router</label>
                        <select name="mikrotik_id" id="edit_user_mikrotik_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                          <option value="">-- No Assignment --</option>
                           <?php foreach ($configs as $config): ?>
                            <option value="<?= $config['id'] ?>"><?= htmlspecialchars($config['name']) ?></option>
                          <?php endforeach; ?>
                        </select>
                     </div>
                     <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="edit_user_role" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                           <option value="user">User</option>
                           <option value="admin">Admin</option>
                           <option value="superadmin">Superadmin</option>
                        </select>
                     </div>
                  </div>

                  <div class="mt-5 sm:mt-6 flex flex-row-reverse gap-2">
                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Update</button>
                    <button type="button" onclick="closeModal('editUserModal')" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Mikrotik Modal -->
  <div id="editMikroTikModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/50 transition-opacity backdrop-blur-md"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4">Edit Konfigurasi</h3>
            <form id="editMikroTikForm" class="space-y-4">
              <input type="hidden" name="id" id="edit_mikrotik_id">
              <div class="space-y-3">
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name" id="edit_mikrotik_name" required class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border">
                 </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Host</label>
                    <input type="text" name="host" id="edit_mikrotik_host" required class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border">
                 </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Port</label>
                    <input type="number" name="port" id="edit_mikrotik_port" required class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border">
                 </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="edit_mikrotik_username" required class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border">
                 </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="edit_mikrotik_password" required class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border">
                 </div>
                 <?php if (isSuperAdmin()): ?>
                 <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="edit_mikrotik_is_active" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="edit_mikrotik_is_active" class="ml-2 block text-sm text-gray-900">Koneksi Utama (Dashboard)</label>
                 </div>
                 <?php endif; ?>
              </div>
              <div class="mt-5 sm:mt-6 flex flex-row-reverse gap-2">
                 <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Simpan</button>
                 <button type="button" onclick="testMikroTikConnection('editMikroTikForm')" class="mt-3 inline-flex w-full justify-center rounded-md bg-yellow-50 px-3 py-2 text-sm font-semibold text-yellow-700 shadow-sm ring-1 ring-inset ring-yellow-200 hover:bg-yellow-100 sm:mt-0 sm:w-auto">Test</button>
                 <button type="button" onclick="closeModal('editMikroTikModal')" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirm Modal -->
  <div id="deleteConfirmModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
              </div>
              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Konfirmasi Hapus</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500" id="deleteConfirmMessage">Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button type="button" id="confirmDeleteBtn" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Hapus</button>
            <button type="button" onclick="closeModal('deleteConfirmModal')" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  let configsData = <?= json_encode($configs) ?>;
  let usersData = <?= json_encode($users) ?>;
  let currentUser = <?= json_encode($currentUser) ?>;
  let deleteAction = null;

  document.addEventListener('DOMContentLoaded', function() {
    renderMikroTikList();
    renderUsersList();
  });

  function switchTab(tabName) {
    const mikrotikContent = document.getElementById('mikrotik-content');
    const usersContent = document.getElementById('users-content');
    const mikrotikTab = document.getElementById('mikrotik-tab');
    const usersTab = document.getElementById('users-tab');

    if (tabName === 'mikrotik') {
      mikrotikContent.classList.remove('hidden');
      usersContent.classList.add('hidden');
      mikrotikTab.classList.add('border-blue-600', 'text-blue-600');
      mikrotikTab.classList.remove('border-transparent', 'text-gray-500');
      if (usersTab) {
          usersTab.classList.remove('border-blue-600', 'text-blue-600');
          usersTab.classList.add('border-transparent', 'text-gray-500');
      }
    } else {
      mikrotikContent.classList.add('hidden');
      usersContent.classList.remove('hidden');
      if(usersTab) {
          usersTab.classList.add('border-blue-600', 'text-blue-600');
          usersTab.classList.remove('border-transparent', 'text-gray-500');
      }
      mikrotikTab.classList.remove('border-blue-600', 'text-blue-600');
      mikrotikTab.classList.add('border-transparent', 'text-gray-500');
    }
  }

  function showToast(message, type = 'info') {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      
      let bgClass, iconHtml;
      if (type === 'success') {
          bgClass = 'bg-green-50 text-green-800 border-green-200';
          iconHtml = '<svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
      } else if (type === 'error') {
          bgClass = 'bg-red-50 text-red-800 border-red-200';
          iconHtml = '<svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
      } else {
          bgClass = 'bg-blue-50 text-blue-800 border-blue-200';
          iconHtml = '<svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
      }

      toast.className = `flex items-center p-4 mb-3 rounded-lg border shadow-sm ${bgClass} transform transition-all duration-300 translate-y-2 opacity-0`;
      toast.innerHTML = `${iconHtml}<span class="text-sm font-medium">${message}</span>`;
      
      container.appendChild(toast);
      
      // Animate in
      requestAnimationFrame(() => {
          toast.classList.remove('translate-y-2', 'opacity-0');
      });

      setTimeout(() => {
          toast.classList.add('opacity-0', '-translate-y-2');
          setTimeout(() => toast.remove(), 300);
      }, 4000);
  }

  function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    if (modalId === 'deleteConfirmModal') deleteAction = null;
  }

  // --- API Helper ---
  async function apiCall(url, data) {
    try {
      const formData = new URLSearchParams();
      for (const key in data) {
        formData.append(key, data[key]);
      }
      const response = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData
      });
      return await response.json();
    } catch (e) {
      console.error(e);
      return { success: false, message: 'Terjadi kesalahan koneksi.' };
    }
  }

  // --- Mikrotik Functions ---
  function renderMikroTikList() {
    const container = document.getElementById('mikrotik-list');
    if (!configsData || configsData.length === 0) {
      container.innerHTML = `
        <div class="text-center py-10 bg-white rounded-xl border border-dashed border-gray-300">
           <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-router mx-auto text-gray-300 mb-3"><rect width="20" height="8" x="2" y="14" rx="2"/><path d="M6.01 18h.01"/><path d="M10.01 18h.01"/><path d="M15 10v4"/><path d="M17.84 7.17a4 4 0 0 0-5.66 0"/><path d="M20.66 4.34a8 8 0 0 0-11.31 0"/></svg>
           <p class="text-gray-500 font-medium">Belum ada konfigurasi router.</p>
           <p class="text-sm text-gray-400 mt-1">Silahkan tambahkan router baru.</p>
        </div>`;
      return;
    }

    const canEdit = currentUser.role === 'superadmin' || currentUser.role === 'admin';

    container.innerHTML = configsData.map(config => `
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-5 hover:shadow-md transition-shadow relative overflow-hidden group ${config.is_active ? 'ring-1 ring-blue-500' : ''}">
         ${config.is_active ? '<div class="absolute top-0 right-0 bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-bl-lg">AKTIF</div>' : ''}
         
         <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3 sm:gap-4">
               <div class="flex-shrink-0 p-3 ${config.is_active ? 'bg-blue-50 text-blue-600' : 'bg-gray-50 text-gray-500'} rounded-lg">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-router"><rect width="20" height="8" x="2" y="14" rx="2"/><path d="M6.01 18h.01"/><path d="M10.01 18h.01"/><path d="M15 10v4"/><path d="M17.84 7.17a4 4 0 0 0-5.66 0"/><path d="M20.66 4.34a8 8 0 0 0-11.31 0"/></svg>
               </div>
               <div>
                  <h3 class="font-bold text-gray-900 text-base sm:text-lg">${config.name}</h3>
                  <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1">
                     <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                        ${config.host}:${config.port}
                     </span>
                     <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        ${config.username}
                     </span>
                      ${config.owner_name ? `<span class="text-xs px-1.5 py-0.5 bg-gray-100 rounded text-gray-600 border border-gray-200">${config.owner_name}</span>` : ''}
                  </div>
               </div>
            </div>

            <div class="flex items-center gap-2 self-end sm:self-center">
               ${canEdit ? `
               <button onclick="editMikroTik(${config.id})" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
               </button>
               <button onclick="confirmDelete('mikrotik', ${config.id}, '${config.name.replace(/'/g, "\\\'")}')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
               </button>
               ` : '<span class="text-xs text-gray-400 italic px-2">Read only</span>'}
            </div>
         </div>
      </div>
    `).join('');
  }

  function editMikroTik(id) {
    const config = configsData.find(c => c.id == id);
    if (!config) return;

    document.getElementById('edit_mikrotik_id').value = config.id;
    document.getElementById('edit_mikrotik_name').value = config.name;
    document.getElementById('edit_mikrotik_host').value = config.host;
    document.getElementById('edit_mikrotik_port').value = config.port;
    document.getElementById('edit_mikrotik_username').value = config.username;
    
    // Password usually blank or handled securely, user re-enters to change
    document.getElementById('edit_mikrotik_password').value = config.password; 

    // Handle is_active Checkbox safely
    const activeCb = document.getElementById('edit_mikrotik_is_active');
    if(activeCb) activeCb.checked = config.is_active == 1;

    document.getElementById('editMikroTikModal').classList.remove('hidden');
  }

  async function testMikroTikConnection(formId) {
    const form = document.getElementById(formId);
    const btn = form.querySelector('button[onclick*="testMikroTikConnection"]');
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">⟳</span> Testing...';
    
    // Manual gathering because FormData might not include unchecked boxes or specific structure
    const data = {
        host: form.querySelector('[name="host"]').value,
        port: form.querySelector('[name="port"]').value,
        username: form.querySelector('[name="username"]').value,
        password: form.querySelector('[name="password"]').value
    };

    const res = await apiCall('/api/mikrotik/test-connection', data);
    showToast(res.message, res.success ? 'success' : 'error');

    btn.disabled = false;
    btn.innerHTML = originalText;
  }

  // --- Users Functions ---
  function renderUsersList() {
    const container = document.getElementById('users-list');
    if (!usersData || usersData.length === 0) {
      container.innerHTML = `<div class="p-6 text-center text-gray-500 bg-white rounded-xl border border-dashed">Belum ada user.</div>`;
      return;
    }

    // Responsive Table View
    container.innerHTML = `
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
               <tr>
                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">User Info</th>
                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Router</th>
                  <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
               </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              ${usersData.map(user => {
                 let roleColor = 'bg-gray-100 text-gray-800';
                 if(user.role === 'admin') roleColor = 'bg-blue-100 text-blue-800';
                 if(user.role === 'superadmin') roleColor = 'bg-purple-100 text-purple-800';

                 return `
                 <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                       <div class="flex items-center gap-3">
                          <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-bold shadow-sm">
                             ${(user.fullname || user.username).substring(0,2).toUpperCase()}
                          </div>
                          <div>
                             <div class="font-medium text-gray-900">${user.fullname || 'No Name'}</div>
                             <div class="text-xs text-gray-500">@${user.username}</div>
                             ${user.email ? `<div class="text-xs text-gray-400">${user.email}</div>` : ''}
                          </div>
                       </div>
                    </td>
                    <td class="px-6 py-4">
                       <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${roleColor}">
                          ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                       </span>
                    </td>
                    <td class="px-6 py-4">
                       <div class="text-sm text-gray-600 flex items-center gap-1.5">
                          ${user.mikrotik_name ? `
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-router text-gray-400"><rect width="20" height="8" x="2" y="14" rx="2"/><path d="M6.01 18h.01"/><path d="M10.01 18h.01"/><path d="M15 10v4"/><path d="M17.84 7.17a4 4 0 0 0-5.66 0"/><path d="M20.66 4.34a8 8 0 0 0-11.31 0"/></svg>
                            ${user.mikrotik_name}
                          ` : '<span class="text-gray-400 italic">Unassigned</span>'}
                       </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                       <div class="flex items-center justify-end gap-2">
                          <button onclick="editUser(${user.id})" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                          </button>
                          <button onclick="confirmDelete('user', ${user.id}, '${user.username}')" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                          </button>
                       </div>
                    </td>
                 </tr>
                 `;
              }).join('')}
            </tbody>
          </table>
        </div>
      </div>
    `;
  }

  function editUser(id) {
    const user = usersData.find(u => u.id == id);
    if (!user) return;

    document.getElementById('edit_user_id').value = user.id;
    document.getElementById('edit_user_fullname').value = user.fullname || '';
    document.getElementById('edit_user_username').value = user.username;
    document.getElementById('edit_user_email').value = user.email || '';
    
    // If edit_user_role exists (superadmin only)
    const roleSelect = document.getElementById('edit_user_role');
    if (roleSelect) roleSelect.value = user.role;

    const mikrotikSelect = document.getElementById('edit_user_mikrotik_id');
    if (mikrotikSelect) mikrotikSelect.value = user.mikrotik_id || '';

    // Remove hidden class
    document.getElementById('editUserModal').classList.remove('hidden');
  }

  function confirmDelete(type, id, name) {
    deleteAction = async () => {
        const endpoint = type === 'mikrotik' ? '/api/mikrotik/delete' : '/api/user/delete';
        const res = await apiCall(endpoint, { id });
        showToast(res.message, res.success ? 'success' : 'error');
        if (res.success) {
            if (type === 'mikrotik') location.reload(); // Simple reload to refresh all states
            else {
                // For users we can just re-fetch
                const userRes = await apiCall('/api/user/get-all', {});
                if(userRes.success) {
                    usersData = userRes.data;
                    renderUsersList();
                }
            }
        }
    };
    
    const msg = type === 'mikrotik' 
        ? `Yakin ingin menghapus router "<b>${name}</b>"?` 
        : `Yakin ingin menghapus user "<b>${name}</b>"?`;
        
    document.getElementById('deleteConfirmMessage').innerHTML = msg;
    document.getElementById('deleteConfirmModal').classList.remove('hidden');
  }

  document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
      if (deleteAction) deleteAction();
      closeModal('deleteConfirmModal');
  });

  // --- Form Handlers ---
  const forms = [
      { id: 'addMikroTikForm', url: '/api/mikrotik/add' },
      { id: 'editMikroTikForm', url: '/api/mikrotik/update' }, 
      { id: 'addUserForm', url: '/api/user/add' },
      { id: 'editUserForm', url: '/api/user/update' }
  ];

  forms.forEach(f => {
      const formEl = document.getElementById(f.id);
      if(formEl) {
          formEl.addEventListener('submit', async (e) => {
              e.preventDefault();
              const btn = formEl.querySelector('button[type="submit"]');
              const originalContent = btn.innerHTML;
              
              btn.disabled = true;
              btn.innerHTML = '<span class="inline-block animate-spin mr-2">⟳</span> Proses...';

              const formData = new FormData(formEl);
              // Convert FormData to object
              const data = {};
              formData.forEach((value, key) => data[key] = value);
              
              // Special handling for checkboxes which might be missing if unchecked
              if(f.id.includes('MikroTikForm')) {
                 data['is_active'] = formEl.querySelector('[name="is_active"]')?.checked ? '1' : '0';
              }

              const res = await apiCall(f.url, data);
              showToast(res.message, res.success ? 'success' : 'error');

              if(res.success) {
                  if(f.id.includes('add')) formEl.reset();
                  if(f.id.includes('edit')) closeModal(f.id.replace('Form', 'Modal'));
                  
                  // Reload data
                  // Ideally we fetch just data, but reload is safer for quick implementation
                  setTimeout(() => location.reload(), 500); 
              }

              btn.disabled = false;
              btn.innerHTML = originalContent;
          });
      }
  });

</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
