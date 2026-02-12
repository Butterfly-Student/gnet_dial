<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto py-2.5 sm:p-6 lg:p-8">
  <!-- Header Section -->
  <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-4 md:p-6 mb-3 sm:mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4">
      <div>
        <h2 class="text-base sm:text-xl md:text-2xl font-bold text-gray-800 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-history-icon lucide-history">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
            <path d="M3 3v5h5" />
            <path d="M12 7v5l4 2" />
          </svg>
          <span class="ml-2">Log Mikrotik</span>
        </h2>
        <p class="text-gray-500 text-xs sm:text-sm mt-0.5 sm:mt-1">Aktivitas sistem MikroTik terbaru</p>
      </div>

      <div class="flex items-center gap-2 sm:gap-3">
        <select id="log-limit"
          class="appearance-none bg-gray-50 border border-gray-300 text-gray-700 text-xs sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-2 sm:px-4 sm:py-2.5 outline-none transition-shadow cursor-pointer">
          <option value="50">50 Baris</option>
          <option value="100" selected>100 Baris</option>
          <option value="200">200 Baris</option>
          <option value="500">500 Baris</option>
        </select>
        <button id="refresh-logs"
          class="bg-blue-600 hover:bg-blue-700 text-white p-2 sm:p-2.5 rounded-lg transition-colors shadow-sm hover:shadow flex-shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-refresh-cw w-4 h-4 sm:w-5 sm:h-5">
            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
            <path d="M21 3v5h-5" />
            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
            <path d="M8 16H3v5" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Search Section -->
    <div class="mt-3 sm:mt-6 relative group">
      <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-search w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.3-4.3" />
        </svg>
      </div>
      <input type="text" id="log-search"
        class="block w-full pl-9 sm:pl-12 pr-10 py-2 sm:py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base transition duration-150 ease-in-out shadow-sm"
        placeholder="Cari dalam log..." autocomplete="off">
      <div id="search-loading"
        class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center pointer-events-none hidden">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-loader-2 animate-spin text-blue-500 w-4 h-4 sm:w-5 sm:h-5">
          <path d="M21 12a9 9 0 1 1-6.219-8.56" />
        </svg>
      </div>
    </div>
  </div>

  <!-- Logs Container -->
  <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <!-- Desktop Table (Hidden on small screens) -->
    <div class="hidden md:block overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-48">Waktu</th>
            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Topik</th>
            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Pesan</th>
          </tr>
        </thead>
        <tbody id="log-container-desktop" class="divide-y divide-gray-50">
          <!-- Populated by JS -->
        </tbody>
      </table>
    </div>

    <!-- Mobile Cards (Visible on small screens) -->
    <div id="log-container-mobile" class="md:hidden divide-y divide-gray-100">
      <!-- Populated by JS -->
    </div>

    <!-- Empty State / Loading State (Shared) -->
    <div id="log-status-message" class="hidden p-8 text-center">
      <!-- Populated by JS -->
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function () {
    let searchTimeout;

    function fetchLogs() {
      const limit = $('#log-limit').val();
      const searchTerm = $('#log-search').val();

      // Show loading styling
      $('#search-loading').removeClass('hidden');
      $('#refresh-logs svg').addClass('animate-spin');

      // Show generic loading state in container if empty or just updating
      const loadingHtml = `
        <div class="flex flex-col items-center justify-center py-12 text-gray-400">
             <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-loader-2 animate-spin text-blue-500 mb-4"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
             <span class="text-sm font-medium">Mengambil data log...</span>
        </div>`;

      // Only clear if search is changing substantially or initial load, otherwise maybe keep data? 
      // For now, let's clear to show fresh load visual feedback
      $('#log-container-desktop').empty();
      $('#log-container-mobile').empty();
      $('#log-status-message').removeClass('hidden').html(loadingHtml);
      $('.md\\:block').addClass('hidden'); // Hide table temporarily
      $('#log-container-mobile').addClass('hidden'); // Hide mobile list temporarily

      $.ajax({
        url: '/api/mikrotik/logs',
        type: 'POST',
        data: {
          limit: limit,
          search: searchTerm
        },
        dataType: 'json',
        success: function (response) {
          $('#search-loading').addClass('hidden');
          $('#refresh-logs svg').removeClass('animate-spin');

          if (response.success) {
            renderLogs(response.data);
          } else {
            showError(response.message);
          }
        },
        error: function () {
          $('#search-loading').addClass('hidden');
          $('#refresh-logs svg').removeClass('animate-spin');
          showError('Terjadi kesalahan saat mengambil log.');
        }
      });
    }

    function renderLogs(logs) {
      const desktopContainer = $('#log-container-desktop');
      const mobileContainer = $('#log-container-mobile');
      const statusMessage = $('#log-status-message');
      const tableWrapper = $('.md\\:block'); // The desktop table wrapper

      desktopContainer.empty();
      mobileContainer.empty();

      if (logs.length === 0) {
        tableWrapper.addClass('hidden');
        mobileContainer.addClass('hidden');
        statusMessage.removeClass('hidden').html(`
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-x mb-4 opacity-50"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m15 11-6 6"/><path d="m9 11 6 6"/></svg>
              <p>Tidak ada log yang ditemukan.</p>
            </div>
        `);
        return;
      }

      statusMessage.addClass('hidden');
      tableWrapper.removeClass('hidden'); // Show desktop table
      mobileContainer.removeClass('hidden'); // Show mobile list

      logs.forEach(log => {
        const time = log.time || '';
        const topics = (log.topics || '').split(',').map(topic =>
          `<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-100 mr-1">${topic.trim()}</span>`
        ).join('');

        let messageClass = 'text-gray-700';
        let rowBgClass = 'hover:bg-gray-50';
        let icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info text-blue-400 mt-0.5"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>';

        const msgLower = log.message.toLowerCase();

        if (msgLower.includes('error') || msgLower.includes('failed') || msgLower.includes('failure')) {
          messageClass = 'text-red-700';
          rowBgClass = 'bg-red-50/30 hover:bg-red-50/50';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x text-red-500 mt-0.5"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>';
        } else if (msgLower.includes('critical')) {
          messageClass = 'text-red-800 font-semibold';
          rowBgClass = 'bg-red-100/30 hover:bg-red-100/50';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-octagon text-red-600 mt-0.5"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>';
        } else if (msgLower.includes('warning') || msgLower.includes('warn')) {
          messageClass = 'text-orange-700';
          rowBgClass = 'bg-orange-50/30 hover:bg-orange-50/50';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert text-orange-500 mt-0.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>';
        } else if (msgLower.includes('info')) {
          // Default info style
        } else if (msgLower.includes('debug')) {
          messageClass = 'text-gray-500 italic';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bug text-gray-400 mt-0.5"><path d="m8 2 1.88 1.88"/><path d="M14.12 3.88 16 2"/><path d="M9 7.13v-1a3.003 3.003 0 1 1 6 0v1"/><path d="M12 20c-3.3 0-6-2.7-6-6v-3a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v3c0 3.3-2.7 6-6 6"/><path d="M12 20v-9"/><path d="M6.53 9C4.6 8.8 3 7.1 3 5"/><path d="M6 13H2"/><path d="M3 21c0-2.1 1.7-3.9 3.8-4"/><path d="M20.97 5c0 2.1-1.6 3.8-3.5 4"/><path d="M22 13h-4"/><path d="M17.2 17c2.1.1 3.8 1.9 3.8 4"/></svg>';
        }

        // Desktop Row
        const desktopRow = `
          <tr class="${rowBgClass} transition-colors group">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono align-top border-b border-gray-50 group-last:border-0">${time}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-top border-b border-gray-50 group-last:border-0">
               <div class="flex flex-wrap gap-1">${topics}</div>
            </td>
            <td class="px-6 py-4 text-sm ${messageClass} align-top border-b border-gray-50 group-last:border-0 leading-relaxed">
               ${log.message}
            </td>
          </tr>
        `;
        desktopContainer.append(desktopRow);

        // Mobile Card
        const mobileCard = `
          <div class="p-4 ${rowBgClass} transition-colors">
            <div class="flex items-start gap-3 mb-2">
               <div class="flex-shrink-0 mt-0.5">${icon}</div>
               <div class="flex-1 min-w-0">
                 <p class="text-sm ${messageClass} break-words leading-relaxed font-medium">${log.message}</p>
               </div>
            </div>
            <div class="flex flex-wrap items-center gap-y-2 gap-x-3 ml-7">
               <span class="text-xs text-gray-500 font-mono flex items-center bg-white px-1.5 py-0.5 rounded border border-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  ${time}
               </span>
               <div class="flex flex-wrap gap-1">${topics}</div>
            </div>
          </div>
        `;
        mobileContainer.append(mobileCard);
      });
    }

    function showError(message) {
      $('#log-container-desktop').empty();
      $('#log-container-mobile').empty();
      $('.md\\:block').addClass('hidden');
      $('#log-container-mobile').addClass('hidden');

      $('#log-status-message').removeClass('hidden').html(`
        <div class="flex flex-col items-center justify-center py-12 text-red-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert mb-4"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
          <span class="text-center font-medium px-4">${message}</span>
          <button onclick="fetchLogs()" class="mt-4 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm transition-colors">Coba Lagi</button>
        </div>
      `);
    }

    // Event Listeners
    $('#refresh-logs').click(fetchLogs);

    $('#log-limit').change(fetchLogs);

    $('#log-search').on('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        fetchLogs();
      }
    });

    // Add debounce for search typing
    $('#log-search').on('input', function () {
      if (searchTimeout) clearTimeout(searchTimeout);
      searchTimeout = setTimeout(fetchLogs, 500);
    });

    // Helper to check connection
    function checkMikrotikConnection() {
      return $.ajax({
        url: '/api/mikrotik/check',
        type: 'POST',
        dataType: 'json',
        timeout: 3000
      }).then(response => {
        return response.success;
      }).catch(() => false);
    }

    // Initial load
    checkMikrotikConnection().then(online => {
      if (online) {
        fetchLogs();
      } else {
        $loading.addClass('hidden');
        $errorState.removeClass('hidden');
        $errorMessage.text('MikroTik Offline - Tidak dapat mengambil log');
        showToast('MikroTik Offline', 'error');
      }
    });
  });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>