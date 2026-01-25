<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-2.5 sm:px-4 py-3 sm:py-6 md:py-8">

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 max-w-xs sm:max-w-sm"></div>

    <!-- Floating Auto-Refresh Indicator -->
    <div id="auto-refresh-indicator" class="fixed top-16 sm:top-20 right-4 z-40 hidden">
      <div class="bg-white rounded-full shadow-lg border border-gray-200 p-2 flex items-center space-x-2 animate-pulse">
        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-500 rounded-full animate-spin"></div>
        <span class="text-xs text-gray-600 font-medium pr-1">Auto-refresh</span>
      </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($flash): ?>
      <div id="flash-message" class="mb-4 sm:mb-6 p-3 sm:p-4 rounded-lg text-sm sm:text-base <?= $flash['type'] == 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-blue-100 border border-blue-400 text-blue-700' ?>">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-2.5 sm:p-4 md:p-6 mb-3 sm:mb-6">
      <h2 class="text-sm sm:text-xl md:text-2xl font-bold text-gray-800 mb-3 sm:mb-6 text-center flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users mr-1.5 sm:mr-3 text-blue-600 w-5 h-5 sm:w-7 sm:h-7 md:w-8 md:h-8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        PPP Non Aktif
      </h2>

      <!-- Search Section -->
      <div class="max-w-2xl mx-auto">
        <div class="flex gap-3">
          <div class="flex-1 relative">
            <input
              type="text"
              id="search-input"
              class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base h-[42px]"
              placeholder="Cari pengguna..."
              autocomplete="off">

            <!-- Loading indicator inside input -->
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
              <svg id="search-loading" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-2 animate-spin text-blue-500 hidden"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
            </div>
          </div>
          
            <button id="refresh-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center shadow-md h-[42px] min-w-[42px]">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw w-4 h-4 sm:w-5 sm:h-5"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
            </button>
        </div>
      </div>
    </div>

      <!-- Results Section -->
      <div id="results-section" class="bg-white rounded-xl shadow-lg p-2.5 sm:p-4 md:p-6">
      <div class="flex sm:items-center justify-between mb-3 sm:mb-6 gap-3">
        <h3 class="text-xs sm:text-lg md:text-xl font-bold text-gray-800 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users mr-1.5 sm:mr-3 text-blue-600 w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span id="results-title" class="text-base sm:text-lg md:text-xl">Data PPP Non-Aktif</span>
          <span id="results-count" class="text-xs sm:text-sm font-normal text-gray-500 ml-2"></span>
        </h3>

        <!-- Controls Row -->
          <div class="flex sm:items-center gap-3 sm:gap-4">
            <div class="flex items-center gap-2 sm:gap-3">
              <select id="per-page-select" class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 text-xs sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-2 sm:px-4 sm:py-2.5 outline-none transition-shadow cursor-pointer">
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
      <div id="pagination-info" class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-500 hidden border-t border-gray-100 pt-6">
        <p>Menampilkan <span id="showing-start" class="font-semibold text-gray-700">0</span> - <span id="showing-end" class="font-semibold text-gray-700">0</span> dari <span id="total-records" class="font-semibold text-gray-700">0</span> data</p>
        
        <div id="pagination-controls" class="flex flex-wrap justify-center items-center gap-2 hidden">
          <button id="first-page" class="px-2 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Halaman Pertama">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-left w-4 h-4"><path d="m11 17-5-5 5-5"/><path d="m18 17-5-5 5-5"/></svg>
          </button>
          <button id="prev-page" class="px-2 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Halaman Sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left w-4 h-4"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          
          <div id="page-numbers" class="flex items-center gap-1">
            <!-- Page numbers will be populated here -->
          </div>
          
          <button id="next-page" class="px-2 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Halaman Berikutnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right w-4 h-4"><path d="m9 18 6-6-6-6"/></svg>
          </button>
          <button id="last-page" class="px-2 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" title="Halaman Terakhir">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-right w-4 h-4"><path d="m13 17 5-5-5-5"/><path d="m6 17 11-5-11-5"/></svg>
          </button>
        </div>

        <div class="flex items-center gap-2">
          <span class="whitespace-nowrap">Hal:</span>
          <input type="number" id="jump-to-page" min="1" class="w-12 px-2 py-1 border border-gray-300 rounded-md text-center focus:outline-none focus:ring-1 focus:ring-blue-500 h-[34px]">
          <button id="jump-page-btn" class="px-3 py-1 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 h-[34px]">Go</button>
        </div>
      </div>
    </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      let searchTimeout;
      let currentRequest;
      let autoRefreshInterval;
      let countdownInterval;
      let autoRefreshEnabled = false;
      let autoRefreshCountdown = 0;
      let backgroundRequest;
      let cachedData = null;
      let allData = []; // Store all data for client-side pagination
      let filteredData = []; // Store filtered data
      let currentPage = 1;
      let perPage = 25;
      let totalPages = 0;
      let isolirConfig = {
        isolir_profile: 'isolir' // Default backup
      };

      // Load isolir config
      $.ajax({
        url: '/api/ppp/isolir-config',
        type: 'POST',
        dataType: 'json'
      }).done(function(response) {
        if (response.success && response.data) {
          isolirConfig = response.data;
        }
      });

      const AUTO_REFRESH_INTERVAL = 7000; // 7 seconds

      const $searchInput = $('#search-input');
      const $searchLoading = $('#search-loading');
      const $resultsSection = $('#results-section');
      const $resultsGrid = $('#results-grid');
      const $resultsTitle = $('#results-title');
      const $resultsCount = $('#results-count');
      const $toastContainer = $('#toast-container');
      const $loadingSkeleton = $('#loading-skeleton');
      const $refreshBtn = $('#refresh-btn');
      const $autoRefreshIndicator = $('#auto-refresh-indicator');
      const $autoRefreshCountdown = $('#auto-refresh-countdown');
      const $toggleAutoRefresh = $('#toggle-auto-refresh');
      const $autoRefreshIcon = $('#auto-refresh-icon');
      const $autoRefreshText = $('#auto-refresh-text');
      const $perPageSelect = $('#per-page-select');

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

      // Function to generate initials from name
      function getProfileInitials(name) {
        if (!name || name === 'N/A') {
          return 'NA';
        }
        const words = name.trim().split(' ');
        if (words.length >= 2) {
          return (words[0][0] + words[1][0]).toUpperCase();
        } else {
          const firstLetter = words[0][0];
          const lastLetter = words[0][words[0].length - 1];
          return (firstLetter + lastLetter).toUpperCase();
        }
      }

      // Function to generate avatar color
      function getAvatarColor(name) {
        const colors = [
          'bg-red-500',
          'bg-blue-500',
          'bg-green-500',
          'bg-yellow-500',
          'bg-purple-500',
          'bg-pink-500',
          'bg-indigo-500',
          'bg-teal-500',
          'bg-orange-500',
          'bg-cyan-500'
        ];

        let hash = 0;
        const str = name || 'default';
        for (let i = 0; i < str.length; i++) {
          hash = ((hash << 5) - hash) + str.charCodeAt(i);
          hash = hash & hash; // Convert to 32bit integer
        }
        return colors[Math.abs(hash) % colors.length];
      }

      // Function to get profile icon
      // Function to get profile icon
      function getProfileIcon(profile, extraClass = '') {
        const profileName = (profile || 'none').toLowerCase();
        const commonClass = `lucide ${extraClass}`;
        
        if (profileName.includes('pppoe')) {
          return `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="${commonClass}"><rect x="16" y="16" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="9" y="2" width="6" height="6" rx="1"/><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"/><path d="M12 12V8"/></svg>`;
        } else if (profileName.includes('hotspot')) {
          return `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="${commonClass}"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>`;
        } else if (profileName.includes('vpn')) {
          return `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="${commonClass}"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>`;
        }
        return `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="${commonClass}"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`;
      }

      // Function to format last logout
      function formatLastLogout(datetime) {
        if (!datetime || datetime === 'N/A' || datetime === '') return '-';
        
        try {
          // Format from MikroTik: 2026-01-24 14:52:28
          const date = new Date(datetime.replace(' ', 'T'));
          if (isNaN(date.getTime())) return datetime;

          const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
          
          const day = date.getDate();
          const month = months[date.getMonth()];
          const year = date.getFullYear();
          const hours = date.getHours().toString().padStart(2, '0');
          const minutes = date.getMinutes().toString().padStart(2, '0');
          
          return `${day} ${month} ${year}, ${hours}:${minutes}`;
        } catch (e) {
          return datetime;
        }
      }

      // Function to show/hide loading state
      function setLoadingState(loading) {
        if (loading) {
          $searchLoading.removeClass('hidden');
          $loadingSkeleton.removeClass('hidden');
          $resultsGrid.addClass('hidden');
          $paginationControls.addClass('hidden');
          $paginationInfo.addClass('hidden');
        } else {
          $searchLoading.addClass('hidden');
          $loadingSkeleton.addClass('hidden');
          $resultsGrid.removeClass('hidden');
        }
      }

      // Function to show/hide auto-refresh indicator
      function setAutoRefreshIndicator(show) {
        if (show) {
          $autoRefreshIndicator.removeClass('hidden');
        } else {
          $autoRefreshIndicator.addClass('hidden');
        }
      }

      // Function to update countdown display
      function updateCountdown() {
        if (autoRefreshEnabled && autoRefreshCountdown > 0) {
          $autoRefreshCountdown.text(autoRefreshCountdown + 's');
          autoRefreshCountdown--;
        } else if (autoRefreshEnabled) {
          $autoRefreshCountdown.text('0s');
        } else {
          $autoRefreshCountdown.text('--');
        }
      }

      // Function to start auto-refresh
      function startAutoRefresh() {
        if (autoRefreshEnabled) return;

        autoRefreshEnabled = true;
        autoRefreshCountdown = Math.floor(AUTO_REFRESH_INTERVAL / 1000);

        $autoRefreshIcon.empty().append('<polygon points="5 3 19 12 5 21 5 3"></polygon><rect x="6" y="4" width="4" height="16"></rect>').attr('class', 'lucide lucide-pause w-3 h-3 text-[9px] sm:text-xs');
        // Actually for pause icon: <rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/>
        // Let's just simpler replace with html
        $autoRefreshIcon.replaceWith('<svg id="auto-refresh-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pause w-3 h-3 text-[9px] sm:text-xs"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>');
        $autoRefreshIcon = $('#auto-refresh-icon'); // Re-select after replace
        $autoRefreshText.text('Stop');
        $toggleAutoRefresh.removeClass('bg-green-100 hover:bg-green-200 text-green-700')
          .addClass('bg-red-100 hover:bg-red-200 text-red-700');

        // Start countdown
        countdownInterval = setInterval(updateCountdown, 1000);

        // Start auto-refresh
        autoRefreshInterval = setInterval(() => {
          autoRefreshCountdown = Math.floor(AUTO_REFRESH_INTERVAL / 1000);
          performBackgroundRefresh();
        }, AUTO_REFRESH_INTERVAL);

        showToast('Auto-refresh diaktifkan', 'success');
      }

      // Function to stop auto-refresh
      function stopAutoRefresh() {
        if (!autoRefreshEnabled) return;

        autoRefreshEnabled = false;

        if (autoRefreshInterval) {
          clearInterval(autoRefreshInterval);
          autoRefreshInterval = null;
        }

        if (countdownInterval) {
          clearInterval(countdownInterval);
          countdownInterval = null;
        }

        if (backgroundRequest) {
          backgroundRequest.abort();
          backgroundRequest = null;
        }

        setAutoRefreshIndicator(false);

        $autoRefreshIcon.replaceWith('<svg id="auto-refresh-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play w-3 h-3 text-[9px] sm:text-xs"><polygon points="6 3 20 12 6 21 6 3"/></svg>');
        $autoRefreshIcon = $('#auto-refresh-icon'); // Re-select after replace
        $autoRefreshText.text('Start');
        $toggleAutoRefresh.removeClass('bg-red-100 hover:bg-red-200 text-red-700')
          .addClass('bg-green-100 hover:bg-green-200 text-green-700');

        $autoRefreshCountdown.text('--');
        showToast('Auto-refresh dinonaktifkan', 'info');
      }

      // Function to perform background refresh
      function performBackgroundRefresh() {
        if (backgroundRequest) {
          backgroundRequest.abort();
        }

        setAutoRefreshIndicator(true);

        if (searchTerm) {
          // If search term exists, we just filter existing data on client side
          // But if we want to refresh data in background while searching, we should fetch 'all'
          // However, user requested client side search only.
          // So we continue to fetch 'all' non-active users to keep data fresh.
        }

        backgroundRequest = $.ajax({
            url: 'api/ppp/non-active',
            type: 'POST',
            dataType: 'json',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })

        backgroundRequest = $.ajax({
            url: url,
            type: 'POST',
            data: {},
            dataType: 'json',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .done(function(response) {
            setAutoRefreshIndicator(false);

            if (response.success) {
              // Update data and maintain current page if possible
              allData = response.data || [];
              filterAndPaginateData();
            }
          })
          .fail(function(xhr) {
            if (xhr.statusText !== 'abort') {
              setAutoRefreshIndicator(false);
            }
          })
          .always(function() {
            backgroundRequest = null;
          });
      }

      // Function to filter data based on search term
      function filterData(data, searchTerm) {
        if (!searchTerm) return data;

        const term = searchTerm.toLowerCase();
        return data.filter(ppp => {
          const name = (ppp.name || '').toLowerCase();
          const profile = (ppp.profile || '').toLowerCase();
          const mac_address = (ppp['last-caller-id'] || '').toLowerCase();
          const remote_address = (ppp['remote-address'] || '').toLowerCase();
          
          return name.includes(term) || mac_address.includes(term) || remote_address.includes(term);
        });
      }

      // Function to paginate data
      function paginateData(data, page, itemsPerPage) {
        if (itemsPerPage === 'all') {
          return {
            data: data,
            totalPages: 1,
            currentPage: 1,
            total: data.length,
            start: 1,
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

      // Function to filter and paginate data
      function filterAndPaginateData() {
        const searchTerm = $searchInput.val().trim();

        // Filter data
        filteredData = filterData(allData, searchTerm);

        // Get current per page setting
        const currentPerPage = $perPageSelect.val();
        const itemsPerPage = currentPerPage === 'all' ? 'all' : parseInt(currentPerPage);

        // Paginate filtered data
        const paginationResult = paginateData(filteredData, currentPage, itemsPerPage);

        // Update UI
        displayPaginatedData(paginationResult, searchTerm);
        updatePaginationControls(paginationResult);
      }

      // Function to display paginated data
      function displayPaginatedData(paginationResult, searchTerm = '') {
        // Update title and count
        if (searchTerm) {
          $resultsTitle.text('Hasil Pencarian');
        } else {
          $resultsTitle.text('Data PPP Non-Aktif');
        }

        $resultsCount.text('(' + paginationResult.total + ' data)');

        // Update pagination info
        $totalRecords.text(paginationResult.total);
        $showingStart.text(paginationResult.start);
        $showingEnd.text(paginationResult.end);

        if (paginationResult.total > 0 && $perPageSelect.val() !== 'all') {
          $paginationInfo.removeClass('hidden');
        } else {
          $paginationInfo.addClass('hidden');
        }

        // Render data
        let html = '';
        if (paginationResult.data && paginationResult.data.length > 0) {
          paginationResult.data.forEach(function(ppp) {
            html += renderPPPCard(ppp);
          });
        } else {
          const noDataMessage = searchTerm ?
            'Tidak ada hasil pencarian' :
            'Semua pengguna PPP sedang aktif';
          const noDataSubMessage = searchTerm ?
            'Coba dengan kata kunci yang berbeda' :
            'Tidak ada pengguna yang offline saat ini';

          html = `
            <div class="text-center py-8 sm:py-12">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-${searchTerm ? 'search' : 'users'} text-gray-400 mb-3 sm:mb-4 mx-auto"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              <p class="text-gray-500 text-base sm:text-lg">${noDataMessage}</p>
              <p class="text-gray-400 text-sm mt-2">${noDataSubMessage}</p>
            </div>
        `;
        }

        $resultsGrid.html(html);
      }

      // Function to update pagination controls
      function updatePaginationControls(paginationResult) {
        const {
          totalPages,
          currentPage: page,
          total
        } = paginationResult;

        // Show/hide pagination controls
        if (totalPages > 1) {
          $paginationControls.removeClass('hidden');
        } else {
          $paginationControls.addClass('hidden');
        }

        // Update button states
        $firstPage.prop('disabled', page === 1);
        $prevPage.prop('disabled', page === 1);
        $nextPage.prop('disabled', page === totalPages);
        $lastPage.prop('disabled', page === totalPages);

        // Update jump to page input
        $jumpToPage.attr('max', totalPages).val('');

        // Generate page numbers
        generatePageNumbers(page, totalPages);
      }

      // Function to generate page number buttons
      function generatePageNumbers(currentPage, totalPages) {
        let html = '';

        // Calculate which pages to show (reduce number for mobile)
        let startPage = Math.max(1, currentPage - 1);
        let endPage = Math.min(totalPages, currentPage + 1);

        // Adjust if we're near the beginning or end
        if (currentPage <= 2) {
          endPage = Math.min(3, totalPages);
        }
        if (currentPage >= totalPages - 1) {
          startPage = Math.max(1, totalPages - 2);
        }

        // Add first page and ellipsis if needed
        if (startPage > 1) {
          html += `<button class="page-number px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-md hover:bg-gray-50" data-page="1">1</button>`;
          if (startPage > 2) {
            html += `<span class="px-1 sm:px-2 text-gray-500 text-xs sm:text-sm">...</span>`;
          }
        }

        // Add page numbers
        for (let i = startPage; i <= endPage; i++) {
          const isActive = i === currentPage;
          const classes = isActive ?
            'px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm bg-blue-600 text-white rounded-md' :
            'page-number px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-md hover:bg-gray-50';

          html += `<button class="${classes}" data-page="${i}"${isActive ? ' disabled' : ''}>${i}</button>`;
        }

        // Add last page and ellipsis if needed
        if (endPage < totalPages) {
          if (endPage < totalPages - 1) {
            html += `<span class="px-1 sm:px-2 text-gray-500 text-xs sm:text-sm">...</span>`;
          }
          html += `<button class="page-number px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm border border-gray-300 rounded-md hover:bg-gray-50" data-page="${totalPages}">${totalPages}</button>`;
        }

        $pageNumbers.html(html);
      }

      // Function to go to specific page
      function goToPage(page) {
        const maxPage = Math.ceil(filteredData.length / ($perPageSelect.val() === 'all' ? filteredData.length : parseInt($perPageSelect.val())));
        if (page < 1 || page > maxPage) return;

        currentPage = page;
        filterAndPaginateData();
      }

      // Function to show toast notification
      function showToast(message, type = 'info', duration = 4000) {
        const toastId = 'toast-' + Date.now();
        let bgColor, borderColor, textColor, icon;

        switch (type) {
          case 'success':
            bgColor = 'bg-green-50';
            borderColor = 'border-green-200';
            textColor = 'text-green-800';
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-500 mt-0.5 mr-2 sm:mr-3 flex-shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            break;
          case 'error':
            bgColor = 'bg-red-50';
            borderColor = 'border-red-200';
            textColor = 'text-red-800';
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert text-red-500 mt-0.5 mr-2 sm:mr-3 flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>';
            break;
          case 'warning':
            bgColor = 'bg-yellow-50';
            borderColor = 'border-yellow-200';
            textColor = 'text-yellow-800';
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert text-yellow-500 mt-0.5 mr-2 sm:mr-3 flex-shrink-0"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>';
            break;
          default:
            bgColor = 'bg-blue-50';
            borderColor = 'border-blue-200';
            textColor = 'text-blue-800';
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info text-blue-500 mt-0.5 mr-2 sm:mr-3 flex-shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>';
        }

        const toastHtml = `
          <div id="${toastId}" class="toast-item ${bgColor} ${borderColor} ${textColor} border rounded-lg shadow-lg p-3 sm:p-4 mb-2 sm:mb-3 transform translate-x-full transition-all duration-300 ease-in-out">
            <div class="flex items-start">
              ${icon}
              <div class="flex-1 mr-2">
                <p class="text-xs sm:text-sm font-medium leading-tight">${message}</p>
              </div>
              <button type="button" class="toast-close flex-shrink-0 ml-1 sm:ml-2 text-gray-400 hover:text-gray-600 focus:outline-none" onclick="closeToast('${toastId}')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
              </button>
            </div>
          </div>
        `;

        $toastContainer.append(toastHtml);

        // Trigger animation
        setTimeout(() => {
          $(`#${toastId}`).removeClass('translate-x-full');
        }, 100);

        // Auto remove after duration
        if (duration > 0) {
          setTimeout(() => {
            closeToast(toastId);
          }, duration);
        }

        return toastId;
      }

      // Function to close toast
      window.closeToast = function(toastId) {
        const $toast = $(`#${toastId}`);
        if ($toast.length) {
          $toast.addClass('translate-x-full');
          setTimeout(() => {
            $toast.remove();
          }, 300);
        }
      }

      // Function to render PPP card with responsive design
function renderPPPCard(ppp) {
  const userName = ppp.name || 'N/A';
  const initials = getProfileInitials(userName);
  const avatarColor = getAvatarColor(userName);
  const profile = ppp.profile || 'none';
  const mac_address = ppp['last-caller-id'] || '';
  const last_logged_out = formatLastLogout(ppp['last-logged-out']);
  const profileIcon = getProfileIcon(profile, 'mr-1.5 w-3.5 h-3.5 inline-block');
  
  const last_caller_id = ppp['last-caller-id'] || '-';
  const remote_address = ppp['remote-address'] || '-';
  
  const statusClass = 'bg-red-100 text-red-700 border-red-200';
  const statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x mr-1.5 inline-block"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>';
  const statusTextFull = 'Tidak Aktif';
  const statusTextShort = 'Off';

  // Determine button styling and action
  const isIsolated = profile === isolirConfig.isolir_profile;
  const buttonColor = isIsolated ? 'border-green-300 text-green-700 hover:bg-green-50 hover:border-green-400' : 'border-orange-300 text-orange-700 hover:bg-orange-50 hover:border-orange-400';
  const buttonTitle = isIsolated ? 'Restore User' : 'Isolir User';
  const buttonIcon = isIsolated 
    ? '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-open group-hover:scale-110 transition-transform"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>' 
    : '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock group-hover:scale-110 transition-transform"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>';

  return `
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100" data-username="${userName}">
      <div class="p-4">
        <div class="flex flex-row items-stretch gap-4">
          
          <!-- Avatar -->
          <div class="flex-shrink-0">
            <div class="w-12 h-12 sm:w-14 sm:h-14 ${avatarColor} rounded-full flex items-center justify-center shadow-sm">
              <span class="text-white text-base sm:text-lg font-bold tracking-wide">${initials}</span>
            </div>
          </div>
          
          <!-- Profile & Details Section -->
          <div class="flex-1 min-w-0 flex flex-col justify-center">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-base sm:text-lg font-bold text-gray-900 truncate pr-2">
                ${userName}
              </h4>
              <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-semibold border ${statusClass}">
                ${statusIcon}
                <span class="hidden sm:inline">${statusTextFull}</span>
                <span class="sm:hidden">${statusTextShort}</span>
              </span>
            </div>
            
            <!-- Info Details -->
            <div class="space-y-1.5">
              <!-- Last Logged Out -->
              <p class="flex text-xs text-gray-600 items-center truncate" title="Last Logged Out">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 flex-shrink-0 text-gray-400"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span class="text-gray-700 font-medium">${last_logged_out || '-'}</span>
              </p>
              
              <!-- Last Caller ID -->
              <p class="flex text-xs text-gray-600 items-center truncate" title="Last Caller ID">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-smartphone mr-1.5 flex-shrink-0 text-gray-400"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                <span class="text-gray-700 font-medium">${last_caller_id}</span>
              </p>
              
              <!-- Profile Badge -->
              <div class="flex items-center gap-1.5">
                ${profileIcon}
                <span class="text-xs text-gray-600 font-medium">${profile}</span>
              </div>
            </div>
          </div>

          <!-- Action Buttons Section -->
          <div class="flex flex-col items-center justify-center gap-2 pl-4 border-l border-gray-200">
            <button class="isolir-btn group p-2.5 bg-white border-2 ${buttonColor} rounded-lg transition-all shadow-sm hover:shadow" 
              title="${buttonTitle}" 
              data-username="${userName}"
              data-current-profile="${profile}">
              ${buttonIcon}
            </button>
          </div>
          
        </div>
      </div>
    </div>
  `;
}

      // Function to load all non-active PPP
      function loadAllNonActive() {
        if (currentRequest) {
          currentRequest.abort();
        }

        setLoadingState(true);

        currentRequest = $.ajax({
            url: '/api/ppp/non-active',
            type: 'POST',
            dataType: 'json',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .done(function(response) {
            setLoadingState(false);

            if (response.success) {
              allData = response.data || [];
              currentPage = 1; // Reset to first page
              filterAndPaginateData();
              showToast('Data berhasil dimuat', 'success');
            } else {
              showToast(response.message || 'Gagal memuat data', 'error');
            }
          })
          .fail(function(xhr) {
            if (xhr.statusText !== 'abort') {
              setLoadingState(false);
              showToast('Terjadi kesalahan saat memuat data', 'error');
            }
          })
          .always(function() {
            currentRequest = null;
          });
      }

      // Event Handlers
      $toggleAutoRefresh.on('click', function() {
        if (autoRefreshEnabled) {
          stopAutoRefresh();
        } else {
          startAutoRefresh();
        }
      });

      $refreshBtn.on('click', function() {
        loadAllNonActive();
      });

      // Per page filter change
      $perPageSelect.on('change', function() {
        currentPage = 1; // Reset to first page when changing per page
        filterAndPaginateData();
      });

      // Pagination event handlers
      $firstPage.on('click', function() {
        if (!$(this).prop('disabled')) {
          goToPage(1);
        }
      });

      $prevPage.on('click', function() {
        if (!$(this).prop('disabled')) {
          goToPage(currentPage - 1);
        }
      });

      $nextPage.on('click', function() {
        if (!$(this).prop('disabled')) {
          goToPage(currentPage + 1);
        }
      });

      $lastPage.on('click', function() {
        if (!$(this).prop('disabled')) {
          const maxPage = Math.ceil(filteredData.length / ($perPageSelect.val() === 'all' ? filteredData.length : parseInt($perPageSelect.val())));
          goToPage(maxPage);
        }
      });

      // Page number click handler
      $(document).on('click', '.page-number', function() {
        const page = parseInt($(this).data('page'));
        goToPage(page);
      });

      // Jump to page handler
      $jumpPageBtn.on('click', function() {
        const page = parseInt($jumpToPage.val());
        if (page) {
          goToPage(page);
        }
      });

      $jumpToPage.on('keypress', function(e) {
        if (e.which === 13) {
          const page = parseInt($(this).val());
          if (page) {
            goToPage(page);
          }
        }
      });

      // Real-time search on input
      $searchInput.on('input', function() {
        const searchTerm = $(this).val().trim();

        if (searchTimeout) {
          clearTimeout(searchTimeout);
        }

        searchTimeout = setTimeout(() => {
          currentPage = 1; // Reset to first page when searching
          filterAndPaginateData();
        }, 300);
      });

      // Handle Enter key in search
      $searchInput.on('keypress', function(e) {
        if (e.which === 13) {
          if (searchTimeout) {
            clearTimeout(searchTimeout);
          }
          currentPage = 1;
          filterAndPaginateData();
        }
      });
      
      // Toggle details on mobile cards
      $(document).on('click', '.toggle-details', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $details = $(this).siblings('.mobile-details');
        const $icon = $(this).find('svg');
        
        $details.toggleClass('hidden');
        
        // Toggle icon
        if ($details.hasClass('hidden')) {
          $icon.removeClass('rotate-180');
          $(this).find('span').text('Lihat Detail');
        } else {
          $icon.addClass('transform transition-transform duration-200 rotate-180');
          $(this).find('span').text('Sembunyikan');
        }
      });

      // Cleanup on page unload
      $(window).on('beforeunload', function() {
        stopAutoRefresh();
      });

      // Auto load data when page loads
      loadAllNonActive();
    });

    // Flash message timeout
    setTimeout(function() {
      $('#flash-message').fadeOut('slow');
    }, 3000); // 3 seconds
  </script>

  <style>
    /* Custom responsive breakpoint for extra small screens */
    @media (min-width: 475px) {
      .xs\:inline {
        display: inline !important;
      }

      .xs\:hidden {
        display: none !important;
      }
    }

    /* Ensure proper touch targets on mobile */
    @media (max-width: 640px) {

      button,
      .btn,
      input,
      select {
        min-height: 44px;
      }

      .page-number {
        min-width: 36px;
        min-height: 36px;
      }

      /* Improve spacing on very small screens */
      @media (max-width: 375px) {
        .container {
          padding-left: 12px;
          padding-right: 12px;
        }
      }
    }
  </style>

  <?php include __DIR__ . '/../layouts/footer.php'; ?>
