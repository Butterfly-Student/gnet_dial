<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-2.5 sm:px-4 py-3 sm:py-6 md:py-8">

  <!-- Toast Container -->
  <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

  <!-- PPP Profiles Content -->
  <div id="ppp-profiles-content">
    <!-- Flash Messages -->
    <?php if ($flash): ?>
      <div id="flash-message"
        class="mb-6 p-4 rounded-lg <?= $flash['type'] == 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-blue-100 border border-blue-400 text-blue-700' ?>">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-2.5 sm:p-4 md:p-6 mb-3 sm:mb-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 sm:mb-6">
        <h2
          class="text-sm sm:text-xl md:text-2xl font-bold text-gray-800 text-center sm:text-left flex items-center justify-center sm:justify-start">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-layers mr-1.5 sm:mr-3 text-blue-600 w-5 h-5 sm:w-7 sm:h-7 md:w-8 md:h-8">
            <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
            <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/>
            <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>
          </svg>
          PPP Profiles
        </h2>

        <button id="add-profile-btn"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center shadow-md h-[42px] min-w-[120px]">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-plus w-4 h-4 sm:w-5 sm:h-5 mr-2">
            <path d="M5 12h14" />
            <path d="M12 5v14" />
          </svg>
          <span class="text-sm sm:text-base">Tambah Profile</span>
        </button>
      </div>

      <!-- Search Section -->
      <div class="max-w-2xl mx-auto">
        <div class="flex gap-3">
          <div class="flex-1 relative">
            <input type="text" id="search-input"
              class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base h-[42px]"
              placeholder="Cari profile..." autocomplete="off">

            <!-- Loading indicator inside input -->
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
              <svg id="search-loading" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-loader-2 animate-spin text-blue-500 hidden">
                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
              </svg>
            </div>
          </div>
          <button id="refresh-btn"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center shadow-md h-[42px] min-w-[42px]">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-refresh-cw w-4 h-4 sm:w-5 sm:h-5">
              <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
              <path d="M21 3v5h-5" />
              <path d="M3 21l18-18" />
              <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 0-6.74-2.74L3 16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Results Section -->
  <div id="results-section" class="bg-white rounded-xl shadow-lg p-2.5 sm:p-4 md:p-6">
    <div class="flex sm:items-center justify-between mb-3 sm:mb-6 gap-3">
      <h3 class="text-xs sm:text-lg md:text-xl font-bold text-gray-800 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-layers mr-1.5 sm:mr-3 text-blue-600 w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6">
          <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
          <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/>
          <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>
        </svg>
        <span id="results-title" class="text-base sm:text-lg md:text-xl">Daftar PPP Profiles</span>
        <span id="results-count" class="text-xs sm:text-sm font-normal text-gray-500 ml-2"></span>
      </h3>

      <!-- Controls Row -->
      <div class="flex sm:items-center gap-3 sm:gap-4">
        <div class="flex items-center gap-2 sm:gap-3">
          <select id="per-page-select"
            class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 text-xs sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-2 sm:px-4 sm:py-2.5 outline-none transition-shadow cursor-pointer">
            <option value="10">10 Baris</option>
            <option value="25" selected>25 Baris</option>
            <option value="50">50 Baris</option>
            <option value="100">100 Baris</option>
            <option value="all">Semua</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Loading Skeleton -->
    <div id="loading-skeleton" class="hidden">
      <div class="space-y-4">
        <?php for ($i = 0; $i < 9; $i++): ?>
          <div class="animate-pulse">
            <div class="bg-gray-200 rounded-xl h-20"></div>
          </div>
        <?php endfor; ?>
      </div>
    </div>

    <!-- Results Grid -->
    <div id="results-grid" class="space-y-4">
      <!-- Results will be populated here via AJAX -->
    </div>

    <!-- Pagination Info & Controls -->
    <div id="pagination-info"
      class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500 hidden border-t border-gray-100 pt-6">
      <p>Menampilkan <span id="showing-start" class="font-semibold text-gray-700">0</span> - <span id="showing-end"
          class="font-semibold text-gray-700">0</span> dari <span id="total-records"
          class="font-semibold text-gray-700">0</span> data</p>

      <div id="pagination-controls" class="flex flex-wrap justify-center items-center gap-2 hidden">
        <button id="first-page"
          class="px-2 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          title="Halaman Pertama">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevrons-left w-4 h-4">
            <path d="m11 17-5-5 5-5" />
            <path d="m18 17-5-5 5-5" />
          </svg>
        </button>
        <button id="prev-page"
          class="px-2 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          title="Halaman Sebelumnya">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevron-left w-4 h-4">
            <path d="m15 18-6-6 6-6" />
          </svg>
        </button>

        <div id="page-numbers" class="flex items-center gap-1">
          <!-- Page numbers will be populated here -->
        </div>

        <button id="next-page"
          class="px-2 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          title="Halaman Berikutnya">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevron-right w-4 h-4">
            <path d="m9 18 6-6-6 6" />
          </svg>
        </button>
        <button id="last-page"
          class="px-2 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          title="Halaman Terakhir">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevrons-right w-4 h-4">
            <path d="m13 17 5-5-5-5" />
            <path d="m6 17 11-5-11-5" />
          </svg>
        </button>
      </div>

      <div class="flex items-center gap-2">
        <span class="whitespace-nowrap">Hal:</span>
        <input type="number" id="jump-to-page" min="1"
          class="w-12 px-2 py-1 border border-gray-300 rounded-md text-center focus:outline-none focus:ring-1 focus:ring-blue-500 h-[34px]">
        <button id="jump-page-btn"
          class="px-3 py-1 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 h-[34px]">Go</button>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Profile Modal -->
<div id="profile-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
    <div class="mt-2 sm:mt-3">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-base sm:text-lg font-medium text-gray-900" id="profile-modal-title">Tambah Profile</h3>
        <button id="close-profile-modal"
          class="text-gray-400 hover:text-gray-600 focus:outline-none">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-x">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
          </svg>
        </button>
      </div>

      <form id="profile-form">
        <div class="space-y-4">
          <!-- Name -->
          <div>
            <label for="profile-name"
              class="block text-sm font-medium text-gray-700 mb-1">Nama Profile <span class="text-red-500">*</span></label>
            <input type="text" id="profile-name" name="name"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
              placeholder="Masukkan nama profile" required autocomplete="off">
          </div>

          <!-- Local Address -->
          <div>
            <label for="profile-local-address"
              class="block text-sm font-medium text-gray-700 mb-1">Local Address <span class="text-red-500">*</span></label>
            <input type="text" id="profile-local-address" name="local_address"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
              placeholder="192.168.1.1" required autocomplete="off">
            <p class="text-xs text-gray-500 mt-1">Masukkan alamat IP (contoh: 192.168.1.1)</p>
          </div>

          <!-- Remote Address -->
          <div>
            <label for="profile-remote-address"
              class="block text-sm font-medium text-gray-700 mb-1">Remote Address <span class="text-red-500">*</span></label>
            <select id="profile-remote-address" name="remote_address"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm cursor-pointer"
              required>
              <option value="">Pilih IP Pool...</option>
              <!-- IP Pools will be populated via AJAX -->
            </select>
          </div>

          <!-- Rate Limit -->
          <div>
            <label for="profile-rate-limit"
              class="block text-sm font-medium text-gray-700 mb-1">Rate Limit <span class="text-red-500">*</span></label>
            <input type="text" id="profile-rate-limit" name="rate_limit"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
              placeholder="512k/1M" required autocomplete="off">
            <p class="text-xs text-gray-500 mt-1">Format: upload/download (contoh: 512k/1M)</p>
          </div>

          <!-- Parent Queue -->
          <div>
            <label for="profile-parent-queue"
              class="block text-sm font-medium text-gray-700 mb-1">Parent Queue <span class="text-red-500">*</span></label>
            <select id="profile-parent-queue" name="parent_queue"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm cursor-pointer"
              required>
              <option value="">Pilih Parent Queue...</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">Pilih parent queue dari daftar</p>
          </div>
        </div>

        <div class="mt-6 flex gap-3">
          <button type="submit" id="save-profile-btn"
            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center justify-center">
            <svg id="save-profile-loading" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-loader-2 animate-spin mr-2 hidden">
              <path d="M21 12a9 9 0 1 1-6.219-8.56" />
            </svg>
            <span id="save-profile-text">Simpan</span>
          </button>
          <button type="button" id="cancel-profile-btn"
            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg shadow-sm transition-colors">
            Batal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div
    class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-sm sm:w-96 shadow-lg rounded-md bg-white">
    <div class="mt-2 sm:mt-3 text-center">
      <div class="mx-auto flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-red-100">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-triangle-alert text-red-600 w-6 h-6">
          <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
          <path d="M12 9v4" />
          <path d="M12 17h.01" />
        </svg>
      </div>
      <h3 class="text-base sm:text-lg font-medium text-gray-900 mt-2">Konfirmasi Hapus</h3>
      <div class="mt-2 px-7 py-3">
        <p class="text-sm text-gray-500">
          Apakah Anda yakin ingin menghapus profile <strong id="delete-name"></strong>? Tindakan ini tidak dapat dibatalkan.
        </p>
      </div>
      <div class="items-center px-4 py-3">
        <button id="confirm-delete-btn"
          class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 flex items-center justify-center">
          <svg id="delete-loading" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-loader-2 animate-spin mr-2 hidden">
            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
          </svg>
          Ya, Hapus
        </button>
        <button id="cancel-delete-btn"
          class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function () {
    let searchTimeout;
    let currentPage = 1;
    let currentLimit = 25;
    let allProfiles = [];
    let ipPools = [];
    let parentQueues = [];
    let editMode = false;
    let editName = '';

    const $searchInput = $('#search-input');
    const $searchLoading = $('#search-loading');
    const $resultsSection = $('#results-section');
    const $resultsGrid = $('#results-grid');
    const $resultsCount = $('#results-count');
    const $toastContainer = $('#toast-container');
    const $loadingSkeleton = $('#loading-skeleton');
    const $refreshBtn = $('#refresh-btn');
    const $perPageSelect = $('#per-page-select');
    const $addProfileBtn = $('#add-profile-btn');

    // Pagination elements
    const $paginationControls = $('#pagination-controls');
    const $paginationInfo = $('#pagination-info');
    const $showingStart = $('#showing-start');
    const $showingEnd = $('#showing-end');
    const $totalRecords = $('#total-records');
    const $firstPage = $('#first-page');
    const $prevPage = $('#prev-page');
    const $nextPage = $('#next-page');
    const $lastPage = $('#last-page');
    const $pageNumbers = $('#page-numbers');
    const $jumpToPage = $('#jump-to-page');
    const $jumpPageBtn = $('#jump-page-btn');

    // Modal elements
    const $profileModal = $('#profile-modal');
    const $profileForm = $('#profile-form');
    const $profileModalTitle = $('#profile-modal-title');
    const $closeProfileModal = $('#close-profile-modal');
    const $saveProfileBtn = $('#save-profile-btn');
    const $saveProfileText = $('#save-profile-text');
    const $saveProfileLoading = $('#save-profile-loading');
    const $cancelProfileBtn = $('#cancel-profile-btn');
    const $profileName = $('#profile-name');
    const $profileLocalAddress = $('#profile-local-address');
    const $profileRemoteAddress = $('#profile-remote-address');
    const $profileRateLimit = $('#profile-rate-limit');
    const $profileParentQueue = $('#profile-parent-queue');

    // Delete modal elements
    const $deleteModal = $('#delete-modal');
    const $deleteName = $('#delete-name');
    const $confirmDeleteBtn = $('#confirm-delete-btn');
    const $cancelDeleteBtn = $('#cancel-delete-btn');
    const $deleteLoading = $('#delete-loading');

    // Fetch IP Pools and Parent Queues on page load
    fetchIpPools();
    fetchParentQueues();
    fetchProfiles();

    // Function to generate initials from name
    function getProfileInitials(name) {
      if (!name || name === 'N/A') {
        return 'NA';
      }
      const words = name.trim().split(' ');
      if (words.length >= 2) {
        return (words[0][0] + words[1][0]).toUpperCase();
      }
      return name.substring(0, 2).toUpperCase();
    }

    // Function to generate avatar color
    function getAvatarColor(name) {
      const colors = [
        'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500',
        'bg-pink-500', 'bg-indigo-500', 'bg-teal-500', 'bg-orange-500', 'bg-cyan-500'
      ];

      let hash = 0;
      const str = name || 'default';
      for (let i = 0; i < str.length; i++) {
        hash = ((hash << 5) - hash) + str.charCodeAt(i);
        hash = hash & hash;
      }
      return colors[Math.abs(hash) % colors.length];
    }

    // Function to show/hide loading state
    function setLoadingState(loading) {
      if (loading) {
        $searchLoading.removeClass('hidden');
        $loadingSkeleton.removeClass('hidden');
        $resultsGrid.addClass('hidden');
        $paginationControls.addClass('hidden');
        $paginationInfo.addClass('hidden');
        $refreshBtn.prop('disabled', true);
      } else {
        $searchLoading.addClass('hidden');
        $loadingSkeleton.addClass('hidden');
        $resultsGrid.removeClass('hidden');
        $refreshBtn.prop('disabled', false);
      }
    }

    // Function to get profile field value (try multiple possible field names)
    function getProfileFieldValue(profile, fieldNames) {
      // Try multiple possible field names (MikroTik API response varies)
      for (const fieldName of fieldNames) {
        if (profile[fieldName] !== undefined && profile[fieldName] !== null) {
          return profile[fieldName];
        }
      }
      return 'N/A';
    }

    // Function to paginate data
    function paginateData(data, page, itemsPerPage) {
      if (itemsPerPage === 'all') {
        return {
          data: data,
          totalPages: 1,
          currentPage: 1,
          total: data.length,
          start: data.length > 0 ? 1 : 0,
          end: data.length
        };
      }

      const startIndex = (page - 1) * itemsPerPage;
      const endIndex = startIndex + itemsPerPage;
      const paginatedData = data.slice(startIndex, endIndex);
      const totalPages = Math.ceil(data.length / itemsPerPage);

      return {
        data: paginatedData,
        totalPages: totalPages,
        currentPage: page,
        total: data.length,
        start: data.length > 0 ? startIndex + 1 : 0,
        end: Math.min(endIndex, data.length)
      };
    }

    // Function to update pagination controls
    function updatePaginationControls(paginationResult) {
      const { totalPages, currentPage: page } = paginationResult;

      if (totalPages > 1) {
        $paginationControls.removeClass('hidden');
      } else {
        $paginationControls.addClass('hidden');
      }

      $firstPage.prop('disabled', page === 1);
      $prevPage.prop('disabled', page === 1);
      $nextPage.prop('disabled', page === totalPages);
      $lastPage.prop('disabled', page === totalPages);

      $jumpToPage.attr('max', totalPages).val('');
      generatePageNumbers(page, totalPages);
    }

    // Function to generate page number buttons
    function generatePageNumbers(currentPage, totalPages) {
      let html = '';
      let startPage = Math.max(1, currentPage - 1);
      let endPage = Math.min(totalPages, currentPage + 1);

      if (currentPage <= 2) endPage = Math.min(3, totalPages);
      if (currentPage >= totalPages - 1) startPage = Math.max(1, totalPages - 2);

      if (startPage > 1) {
        html += `<button class="page-number px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50" data-page="1">1</button>`;
        if (startPage > 2) html += `<span class="px-1 text-gray-500">...</span>`;
      }

      for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPage;
        const classes = isActive ?
          'px-3 py-1 bg-blue-600 text-white rounded-md' :
          'page-number px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50';
        html += `<button class="${classes}" data-page="${i}"${isActive ? ' disabled' : ''}>${i}</button>`;
      }

      if (endPage < totalPages) {
        if (endPage < totalPages - 1) html += `<span class="px-1 text-gray-500">...</span>`;
        html += `<button class="page-number px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50" data-page="${totalPages}">${totalPages}</button>`;
      }

      $pageNumbers.html(html);
    }

    // Function to go to specific page
    function goToPage(page) {
      currentPage = page;
      renderProfiles();
    }

    // Function to show toast notification
    function showToast(message, type = 'info', duration = 5000) {
      const toastId = 'toast-' + Date.now();
      let bgColor, borderColor, textColor, icon;

      switch (type) {
        case 'success':
          bgColor = 'bg-green-50';
          borderColor = 'border-green-200';
          textColor = 'text-green-800';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-500 mt-0.5 mr-3 flex-shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
          break;
        case 'error':
          bgColor = 'bg-red-50';
          borderColor = 'border-red-200';
          textColor = 'text-red-800';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert text-red-500 mt-0.5 mr-3 flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>';
          break;
        case 'warning':
          bgColor = 'bg-yellow-50';
          borderColor = 'border-yellow-200';
          textColor = 'text-yellow-800';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert text-yellow-500 mt-0.5 mr-3 flex-shrink-0"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>';
          break;
        default:
          bgColor = 'bg-blue-50';
          borderColor = 'border-blue-200';
          textColor = 'text-blue-800';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info text-blue-500 mt-0.5 mr-3 flex-shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>';
      }

      const toastHtml = `
        <div id="${toastId}" class="toast-item ${bgColor} ${borderColor} ${textColor} border rounded-lg shadow-lg p-4 mb-3 transform translate-x-full transition-all duration-300 ease-in-out max-w-sm">
          <div class="flex items-start">
            ${icon}
            <div class="flex-1 mr-2">
              <p class="text-sm font-medium">${message}</p>
            </div>
            <button type="button" class="toast-close flex-shrink-0 ml-2 text-gray-400 hover:text-gray-600 focus:outline-none" onclick="closeToast('${toastId}')">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
          </div>
        </div>
      `;

      $toastContainer.append(toastHtml);
      setTimeout(() => $(`#${toastId}`).removeClass('translate-x-full'), 100);
      if (duration > 0) setTimeout(() => closeToast(toastId), duration);
      return toastId;
    }

    // Function to close toast
    window.closeToast = function (toastId) {
      const $toast = $(`#${toastId}`);
      if ($toast.length) {
        $toast.addClass('translate-x-full');
        setTimeout(() => $toast.remove(), 300);
      }
    }

    // Function to render profile card
    function renderProfileCard(profile) {
      const profileName = getProfileFieldValue(profile, ['name']) || 'N/A';
      const initials = getProfileInitials(profileName);
      const avatarColor = getAvatarColor(profileName);
      
      // Try multiple field name variations (MikroTik API response structure varies)
      const rateLimit = getProfileFieldValue(profile, ['rate-limit', 'rate_limit']) || 'N/A';
      const localAddress = getProfileFieldValue(profile, ['local-address', 'local_address']) || 'N/A';
      const remoteAddress = getProfileFieldValue(profile, ['remote-address', 'remote_address']) || 'N/A';
      const parentQueue = getProfileFieldValue(profile, ['parent-queue', 'parent_queue', 'queue']) || 'N/A';
      
      // Determine status
      const disabled = getProfileFieldValue(profile, ['disabled', 'disable']) === 'true' || 
                       getProfileFieldValue(profile, ['disabled', 'disable']) === true;
      
      let statusClass, statusIcon, statusTextFull, statusTextShort;
      if (disabled) {
        statusClass = 'bg-red-50 text-red-700 border-red-200';
        statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>';
        statusTextFull = 'Nonaktif';
        statusTextShort = 'Off';
      } else {
        statusClass = 'bg-green-50 text-green-700 border-green-200';
        statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
        statusTextFull = 'Aktif';
        statusTextShort = 'On';
      }

      return `
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100" data-name="${profileName}">
      <div class="flex items-center p-4 gap-4">

        <!-- Avatar -->
        <div class="flex-shrink-0">
          <div class="w-12 h-12 sm:w-14 sm:h-14 ${avatarColor} rounded-full flex items-center justify-center shadow-sm">
            <span class="text-white text-base sm:text-lg font-bold tracking-wide">${initials}</span>
          </div>
        </div>

        <!-- Profile & Details Section -->
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between mb-1">
            <h4 class="text-base sm:text-lg font-bold text-gray-900 truncate pr-2">
              ${profileName}
            </h4>
            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-semibold border ${statusClass}">
              ${statusIcon}
              <span class="hidden sm:inline">${statusTextFull}</span>
              <span class="sm:hidden">${statusTextShort}</span>
            </span>
          </div>

          <!-- Info Details -->
          <div class="flex flex-col gap-2">
            <p class="text-sm text-gray-600 flex items-center truncate">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gauge mr-1.5 text-gray-400"><path d="m12 14 4-4"/><path d="M3.34 19a10 10 0 1 1 17.32 0"/></svg>
              <span class="font-medium">${rateLimit}</span>
            </p>
            <p class="text-xs text-gray-500 truncate">
              Local: ${localAddress}
            </p>
            <p class="text-xs text-gray-500 truncate">
              Remote: ${remoteAddress}
            </p>
            <p class="text-xs text-gray-500 truncate">
              Queue: ${parentQueue}
            </p>
          </div>
        </div>

        <!-- Desktop Actions -->
        <div class="hidden sm:flex flex-col gap-2 items-end justify-center pl-4 border-l border-gray-100">
          <div class="flex gap-2">
            <button class="edit-btn group p-2 bg-white border border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm" title="Edit Profile" data-name="${profileName}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit group-hover:scale-110 transition-transform"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
            </button>
            <button class="delete-btn group p-2 bg-white border border-red-200 text-red-600 rounded-lg hover:bg-red-50 hover:border-red-300 transition-all shadow-sm" title="Delete Profile" data-name="${profileName}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 group-hover:scale-110 transition-transform"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
            </button>
          </div>
        </div>

        <!-- Mobile: Three-dot dropdown menu -->
        <div class="sm:hidden relative">
          <button class="action-menu-btn p-2 text-gray-500 hover:bg-gray-100 rounded-full transition-colors" type="button" data-name="${profileName}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
          </button>
          <div class="action-menu hidden absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden" style="z-index: 1000;">
            <button class="edit-btn-mobile w-full text-left px-4 py-3 text-sm text-blue-600 hover:bg-blue-50 flex items-center gap-3 border-b border-gray-50" data-name="${profileName}">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
              <span>Edit</span>
            </button>
            <button class="delete-btn-mobile w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 flex items-center gap-3" data-name="${profileName}">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
              <span>Hapus</span>
            </button>
          </div>
        </div>

      </div>
    </div>
  `;
    }

    // Function to render profiles
    function renderProfiles() {
      const paginationResult = paginateData(allProfiles, currentPage, currentLimit);

      $resultsGrid.empty();

      if (paginationResult.data.length === 0) {
        $resultsGrid.html(`
          <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-inbox text-gray-300 mx-auto mb-4">
              <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-600 mb-2">Tidak ada data</h3>
            <p class="text-gray-500">Silakan tambahkan PPP profile baru</p>
          </div>
        `);
        $paginationControls.addClass('hidden');
        $paginationInfo.addClass('hidden');
      } else {
        paginationResult.data.forEach(profile => {
          $resultsGrid.append(renderProfileCard(profile));
        });

        $resultsCount.text(`(${paginationResult.total} profiles)`);

        // Update pagination
        $paginationInfo.removeClass('hidden');
        $showingStart.text(paginationResult.start);
        $showingEnd.text(paginationResult.end);
        $totalRecords.text(paginationResult.total);
        updatePaginationControls(paginationResult);
      }
    }

    // Function to fetch profiles
    function fetchProfiles(searchTerm = '') {
      setLoadingState(true);

      $.ajax({
        url: '/api/ppp/profiles',
        type: 'POST',
        data: {
          search_term: searchTerm,
          page: currentPage,
          limit: currentLimit
        },
        dataType: 'json'
      })
        .done(function (response) {
          console.log('Profiles Response:', response);
          
          setLoadingState(false);

          if (response.success) {
            allProfiles = response.data;
            renderProfiles();
          } else {
            showToast(response.message || 'Gagal mengambil data', 'error');
          }
        })
        .fail(function (xhr, status, error) {
          console.error('Profiles Error:', xhr, status, error);
          setLoadingState(false);
          showToast('Gagal terhubung ke server', 'error');
        });
    }

    // Function to fetch IP Pools
    function fetchIpPools() {
      $.ajax({
        url: '/api/ppp/ip-pools',
        type: 'POST',
        dataType: 'json'
      })
        .done(function (response) {
          console.log('IP Pools Response:', response);
          
          if (response.success && response.data) {
            ipPools = response.data;
            const $remoteSelect = $('#profile-remote-address');
            
            $remoteSelect.empty();
            $remoteSelect.append('<option value="">Pilih IP Pool...</option>');

            ipPools.forEach(pool => {
              const ranges = pool.ranges || 'N/A';
              $remoteSelect.append(`<option value="${pool.name}">${pool.name} (${ranges})</option>`);
            });
          }
        })
        .fail(function (xhr, status, error) {
          console.error('IP Pools Error:', xhr, status, error);
          showToast('Gagal mengambil IP pools', 'error');
        });
    }

    // Function to fetch Parent Queues
    function fetchParentQueues() {
      $.ajax({
        url: '/api/ppp/parent-queues',
        type: 'POST',
        dataType: 'json'
      })
        .done(function (response) {
          console.log('Parent Queues Response:', response);
          
          if (response.success && response.data) {
            parentQueues = response.data;
            const $parentQueueSelect = $('#profile-parent-queue');
            
            $parentQueueSelect.empty();
            $parentQueueSelect.append('<option value="">Pilih Parent Queue...</option>');

            if (parentQueues.length === 0) {
              $parentQueueSelect.append('<option value="" disabled>Tidak ada parent queue tersedia</option>');
            } else {
              parentQueues.forEach(queue => {
                const maxLimit = queue['max-limit'] || 'N/A';
                $parentQueueSelect.append(`<option value="${queue.name}">${queue.name} (${maxLimit})</option>`);
              });
            }
          }
        })
        .fail(function (xhr, status, error) {
          console.error('Parent Queues Error:', xhr, status, error);
          showToast('Gagal mengambil parent queues', 'error');
        });
    }

    // Function to show profile modal
    function showProfileModal(name = '') {
      editMode = name !== '';
      editName = name;

      if (editMode) {
        // Edit mode - fetch profile data
        $profileModalTitle.text('Edit Profile');
        $('#profile-name').val(name).prop('readonly', true);

        $.ajax({
          url: '/api/ppp/profile/get',
          type: 'POST',
          data: { name: name },
          dataType: 'json'
        })
          .done(function (response) {
            if (response.success && response.data) {
              const profile = response.data;
              
              // Use helper function to get correct field values
              $('#profile-local-address').val(
                getProfileFieldValue(profile, ['local-address', 'local_address']) || ''
              );
              $('#profile-remote-address').val(
                getProfileFieldValue(profile, ['remote-address', 'remote_address']) || ''
              );
              $('#profile-rate-limit').val(
                getProfileFieldValue(profile, ['rate-limit', 'rate_limit']) || ''
              );
              const parentQueueValue = getProfileFieldValue(profile, ['parent-queue', 'parent_queue', 'queue']) || '';
              $('#profile-parent-queue').val(parentQueueValue);
              console.log('Setting parent queue value:', parentQueueValue);
            } else {
              showToast(response.message || 'Gagal mengambil data profile', 'error');
            }
          })
          .fail(function () {
            showToast('Gagal terhubung ke server', 'error');
          });
      } else {
        // Add mode
        $profileModalTitle.text('Tambah Profile');
        $('#profile-name').val('').prop('readonly', false);
        $('#profile-local-address').val('');
        $('#profile-remote-address').val('');
        $('#profile-rate-limit').val('');
        $('#profile-parent-queue').val('');
      }

      $profileModal.removeClass('hidden');
    }

    // Function to hide profile modal
    function hideProfileModal() {
      $profileModal.addClass('hidden');
      $profileForm[0].reset();
      editMode = false;
      editName = '';
    }

    // Event listeners
    $addProfileBtn.on('click', function () {
      showProfileModal();
    });

    $closeProfileModal.on('click', hideProfileModal);

    $cancelProfileBtn.on('click', hideProfileModal);

    $profileForm.on('submit', function (e) {
      e.preventDefault();

      const name = $('#profile-name').val();
      const localAddress = $('#profile-local-address').val();
      const remoteAddress = $('#profile-remote-address').val();
      const rateLimit = $('#profile-rate-limit').val();
      const parentQueue = $('#profile-parent-queue').val();

      // Validate rate limit format
      if (!/^(\d+[kKMGT]?)\/(\d+[kKMGT]?)$/.test(rateLimit)) {
        showToast('Format rate limit harus: upload/download (contoh: 512k/1M)', 'warning');
        return;
      }

      $saveProfileLoading.removeClass('hidden');
      $saveProfileBtn.prop('disabled', true);
      $saveProfileText.text('Menyimpan...');

      const apiUrl = editMode ? '/api/ppp/profile/update' : '/api/ppp/profile/add';
      const data = {
        name: name,
        local_address: localAddress,
        remote_address: remoteAddress,
        rate_limit: rateLimit,
        parent_queue: parentQueue
      };

      $.ajax({
        url: apiUrl,
        type: 'POST',
        data: data,
        dataType: 'json'
      })
        .done(function (response) {
          $saveProfileLoading.addClass('hidden');
          $saveProfileBtn.prop('disabled', false);
          $saveProfileText.text('Simpan');

          if (response.success) {
            showToast(response.message, 'success');
            hideProfileModal();
            fetchProfiles();
          } else {
            showToast(response.message || 'Gagal menyimpan profile', 'error');
          }
        })
        .fail(function () {
          $saveProfileLoading.addClass('hidden');
          $saveProfileBtn.prop('disabled', false);
          $saveProfileText.text('Simpan');
          showToast('Gagal terhubung ke server', 'error');
        });
    });

    // Search input
    $searchInput.on('input', function () {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(function () {
        currentPage = 1;
        fetchProfiles($searchInput.val());
      }, 500);
    });

    // Refresh button
    $refreshBtn.on('click', function () {
      fetchProfiles($searchInput.val());
    });

    // Per page select
    $perPageSelect.on('change', function () {
      currentLimit = $(this).val();
      currentPage = 1;
      fetchProfiles($searchInput.val());
    });

    // Edit buttons
    $(document).on('click', '.edit-btn, .edit-btn-mobile', function () {
      const name = $(this).data('name');
      showProfileModal(name);
    });

    // Delete buttons
    $(document).on('click', '.delete-btn, .delete-btn-mobile', function () {
      const name = $(this).data('name');
      $deleteName.text(name);
      $deleteModal.removeClass('hidden');
    });

    $cancelDeleteBtn.on('click', function () {
      $deleteModal.addClass('hidden');
    });

    $confirmDeleteBtn.on('click', function () {
      const name = $deleteName.text();

      $deleteLoading.removeClass('hidden');
      $confirmDeleteBtn.prop('disabled', true);

      $.ajax({
        url: '/api/ppp/profile/delete',
        type: 'POST',
        data: { name: name },
        dataType: 'json'
      })
        .done(function (response) {
          $deleteLoading.addClass('hidden');
          $confirmDeleteBtn.prop('disabled', false);

          if (response.success) {
            showToast(response.message, 'success');
            $deleteModal.addClass('hidden');
            fetchProfiles();
          } else {
            showToast(response.message || 'Gagal menghapus profile', 'error');
          }
        })
        .fail(function () {
          $deleteLoading.addClass('hidden');
          $confirmDeleteBtn.prop('disabled', false);
          showToast('Gagal terhubung ke server', 'error');
        });
    });

    // Mobile action menu
    $(document).on('click', '.action-menu-btn', function (e) {
      e.stopPropagation();
      $(this).siblings('.action-menu').toggleClass('hidden');
    });

    $(document).on('click', function () {
      $('.action-menu').addClass('hidden');
    });

    // Pagination buttons
    $(document).on('click', '.page-number', function () {
      const page = $(this).data('page');
      goToPage(page);
    });

    $firstPage.on('click', function () {
      goToPage(1);
    });

    $prevPage.on('click', function () {
      if (currentPage > 1) {
        goToPage(currentPage - 1);
      }
    });

    $nextPage.on('click', function () {
      const totalPages = Math.ceil(allProfiles.length / currentLimit);
      if (currentPage < totalPages) {
        goToPage(currentPage + 1);
      }
    });

    $lastPage.on('click', function () {
      const totalPages = Math.ceil(allProfiles.length / currentLimit);
      goToPage(totalPages);
    });

    $jumpPageBtn.on('click', function () {
      const page = parseInt($jumpToPage.val());
      if (page > 0) {
        goToPage(page);
      }
    });

    // Close modals on escape key
    $(document).on('keydown', function (e) {
      if (e.key === 'Escape') {
        hideProfileModal();
        $deleteModal.addClass('hidden');
      }
    });
  });
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
