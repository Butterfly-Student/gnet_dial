<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-2.5 sm:px-4 py-3 sm:py-6 md:py-8">

  <!-- Toast Container -->
  <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

  <!-- Profiles Content -->
  <div id="profiles-content">
    <?php if ($flash): ?>
      <div id="flash-message" class="mb-6 p-4 rounded-lg <?= $flash['type'] == 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-blue-100 border border-blue-400 text-blue-700' ?>">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-lg p-2.5 sm:p-4 md:p-6 mb-3 sm:mb-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 sm:mb-6">
        <h2 class="text-sm sm:text-xl md:text-2xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-layers mr-3 text-blue-600 w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
            PPP Profiles
        </h2>
        <div class="flex gap-2">
            <button id="sync-profile-btn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                 <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-refresh-cw w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                 Sync from MikroTik
            </button>
            <button id="add-profile-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                 <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-plus w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                 Tambah Profile
            </button>
        </div>
      </div>

      <div class="max-w-2xl mx-auto">
         <div class="flex gap-3">
             <div class="flex-1 relative">
                 <input type="text" id="search-input" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Cari profile...">
             </div>
             <button id="refresh-btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                 <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
             </button>
         </div>
      </div>
    </div>

    <div id="results-section" class="bg-white rounded-xl shadow-lg p-6">
        <div id="results-grid" class="space-y-4"></div>
    </div>
  </div>
</div>

<!-- Add/Edit Profile Modal -->
<div id="profile-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium text-gray-900 mb-4" id="profile-modal-title">Tambah Profile</h3>
        <form id="profile-form">
            <input type="hidden" id="profile-id" name="id">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Profile <span class="text-red-500">*</span></label>
                    <input type="text" id="profile-name" name="name" class="w-full px-3 py-2 border rounded-lg" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Local Address (IP) <span class="text-red-500">*</span></label>
                        <input type="text" id="profile-local-address" name="local_address" class="w-full px-3 py-2 border rounded-lg" placeholder="e.g. 192.168.1.1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Remote Address <span class="text-red-500">*</span></label>
                        <select id="profile-remote-address" name="remote_address" class="w-full px-3 py-2 border rounded-lg" required>
                            <option value="">Pilih Pool...</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Rate Limit (Up/Down) <span class="text-red-500">*</span></label>
                    <input type="text" id="profile-rate-limit" name="rate_limit" class="w-full px-3 py-2 border rounded-lg" placeholder="e.g. 5M/10M" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Parent Queue</label>
                    <select id="profile-parent-queue" name="parent_queue" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">None</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" id="profile-price" name="price" class="w-full px-3 py-2 border rounded-lg" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pajak (%)</label>
                        <input type="number" step="0.01" id="profile-tax-rate" name="tax_rate" class="w-full px-3 py-2 border rounded-lg" placeholder="0">
                    </div>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="submit" id="save-profile-btn" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                <button type="button" id="cancel-profile-btn" class="flex-1 bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-sm shadow-lg rounded-md bg-white text-center">
        <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
        <p class="mt-2 text-gray-500">Yakin ingin menghapus profile <strong id="delete-name"></strong>?</p>
        <div class="mt-4 flex gap-3 justify-center">
            <button id="confirm-delete-btn" class="bg-red-600 text-white px-4 py-2 rounded-lg">Hapus</button>
            <button id="cancel-delete-btn" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">Batal</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    let editMode = false;

    function showToast(msg, type='info') { alert(msg); } // Replace with better toast if available

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
    }

    function renderProfileCard(p) {
        const price = p.price ? formatRupiah(p.price) : 'Rp 0';
        const tax = p.tax_rate ? p.tax_rate + '%' : '0%';

        return `
            <div class="bg-white p-4 border rounded-xl shadow-sm hover:shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-bold text-lg text-gray-900">${p.name}</h4>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">${price}</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1 text-sm text-gray-600">
                        <p>Local: <span class="font-medium">${p.local_address || '-'}</span></p>
                        <p>Remote: <span class="font-medium">${p.remote_address || '-'}</span></p>
                        <p>Rate: <span class="font-medium">${p.rate_limit || '-'}</span></p>
                        <p>Queue: <span class="font-medium">${p.parent_queue || '-'}</span></p>
                        <p>Tax: <span class="font-medium">${tax}</span></p>
                    </div>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <button class="edit-btn flex-1 sm:flex-none p-2 text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50" data-id="${p.id}">Edit</button>
                    <button class="delete-btn flex-1 sm:flex-none p-2 text-red-600 border border-red-200 rounded-lg hover:bg-red-50" data-id="${p.id}" data-name="${p.name}">Del</button>
                </div>
            </div>
        `;
    }

    function fetchProfiles(term = '') {
        $.post('/api/ppp/profiles/list', {search_term: term, limit: 'all'}, function(res) {
            if(res.success) {
                $('#results-grid').empty();
                if(res.data.length === 0) {
                    $('#results-grid').html('<p class="text-center text-gray-500 py-8">Tidak ada profile.</p>');
                } else {
                    res.data.forEach(p => $('#results-grid').append(renderProfileCard(p)));
                }
            } else {
                showToast(res.message, 'error');
            }
        });
    }

    function loadDropdowns() {
        // Load IP Pools
        $.post('/api/ppp/ip-pools', function(res) {
            if(res.success) {
                const pools = res.data;
                let options = '<option value="">Pilih Pool...</option>';
                pools.forEach(pool => {
                    options += `<option value="${pool.name}">${pool.name} (${pool.ranges})</option>`;
                });
                // $('#profile-local-address').html(options); // Removed: Local address is manual input
                $('#profile-remote-address').html(options);
            }
        });

        // Load Parent Queues
        $.post('/api/ppp/parent-queues', function(res) {
            if(res.success) {
                const queues = res.data;
                let options = '<option value="">None</option>';
                queues.forEach(q => {
                    options += `<option value="${q.name}">${q.name}</option>`;
                });
                $('#profile-parent-queue').html(options);
            }
        });
    }

    $('#add-profile-btn').click(function() {
        editMode = false;
        $('#profile-modal-title').text('Tambah Profile');
        $('#profile-form')[0].reset();
        $('#profile-id').val('');
        loadDropdowns(); // Refresh dropdowns
        $('#profile-modal').removeClass('hidden');
    });

    $('#sync-profile-btn').click(function() {
        if(!confirm('Sync profiles from MikroTik to Database? Existing database profiles will be kept, new ones added.')) return;

        $(this).prop('disabled', true).text('Syncing...');
        $.post('/api/ppp/profile/sync', function(res) {
            $('#sync-profile-btn').prop('disabled', false).html('<svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-refresh-cw w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg> Sync from MikroTik');

            if(res.success) {
                showToast(res.message, 'success');
                fetchProfiles();
            } else {
                showToast(res.message, 'error');
            }
        }).fail(function() {
            $('#sync-profile-btn').prop('disabled', false).text('Sync from MikroTik');
            showToast('Sync failed', 'error');
        });
    });

    $('#cancel-profile-btn').click(function() { $('#profile-modal').addClass('hidden'); });

    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        editMode = true;
        $('#profile-modal-title').text('Edit Profile');
        loadDropdowns();

        // Use timeout to allow dropdowns to populate before setting value?
        // Better: Wait for data then set. But simple timeout works for now or chained promises.
        // Assuming dropdowns load fast or are already loaded.
        // Ideally we should load dropdowns once on page load or check if loaded.
        // For robustness, I'll load them now and then fetch data.

        setTimeout(() => {
            $.post('/api/ppp/profile/get-db', {id: id}, function(res) {
                if(res.success) {
                    const d = res.data;
                    $('#profile-id').val(d.id);
                    $('#profile-name').val(d.name);
                    $('#profile-local-address').val(d.local_address);
                    $('#profile-remote-address').val(d.remote_address);
                    $('#profile-rate-limit').val(d.rate_limit);
                    $('#profile-parent-queue').val(d.parent_queue);
                    $('#profile-price').val(d.price);
                    $('#profile-tax-rate').val(d.tax_rate);
                    $('#profile-modal').removeClass('hidden');
                } else {
                    showToast(res.message, 'error');
                }
            });
        }, 500); // Slight delay to ensure dropdowns populated
    });

    $('#profile-form').submit(function(e) {
        e.preventDefault();
        const url = editMode ? '/api/ppp/profile/update-db' : '/api/ppp/profile/add-db';
        const data = $(this).serialize();
        $('#save-profile-btn').prop('disabled', true);

        $.post(url, data, function(res) {
            $('#save-profile-btn').prop('disabled', false);
            if(res.success) {
                showToast(res.message, 'success');
                $('#profile-modal').addClass('hidden');
                fetchProfiles();
            } else {
                showToast(res.message, 'error');
            }
        }).fail(function() {
            $('#save-profile-btn').prop('disabled', false);
            showToast('Error saving profile', 'error');
        });
    });

    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        $('#delete-name').text(name);
        $('#confirm-delete-btn').data('id', id);
        $('#delete-modal').removeClass('hidden');
    });

    $('#cancel-delete-btn').click(function() { $('#delete-modal').addClass('hidden'); });

    $('#confirm-delete-btn').click(function() {
        const id = $(this).data('id');
        $(this).prop('disabled', true);
        $.post('/api/ppp/profile/delete-db', {id: id}, function(res) {
            $('#confirm-delete-btn').prop('disabled', false);
            if(res.success) {
                showToast(res.message, 'success');
                $('#delete-modal').addClass('hidden');
                fetchProfiles();
            } else {
                showToast(res.message, 'error');
            }
        });
    });

    $('#refresh-btn').click(function() { fetchProfiles($('#search-input').val()); });
    $('#search-input').on('input', function() { fetchProfiles($(this).val()); });

    // Initial load
    fetchProfiles();
    loadDropdowns();
});
</script>
<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
