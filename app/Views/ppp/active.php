<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

  <!-- Toast Container -->
  <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

  <!-- PPP Active Content -->
  <div id="ppp-active-content">
    <!-- Flash Messages -->
    <?php if ($flash): ?>
      <div id="flash-message"
        class="mb-6 p-4 rounded-lg <?= $flash['type'] == 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-blue-100 border border-blue-400 text-blue-700' ?>">
        <?= htmlspecialchars($flash['message']) ?>
      </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-lg p-2.5 sm:p-4 md:p-6 mb-3 sm:mb-6">
      <h2
        class="text-sm sm:text-xl md:text-2xl font-bold text-gray-800 mb-3 sm:mb-6 text-center flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-users mr-1.5 sm:mr-3 text-blue-600 w-5 h-5 sm:w-7 sm:h-7 md:w-8 md:h-8">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
          <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        PPP Aktif
      </h2>

      <!-- Search Section -->
      <div class="max-w-2xl mx-auto">
        <div class="flex gap-3">
          <div class="flex-1 relative">
            <input type="text" id="search-input"
              class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base h-[42px]"
              placeholder="Cari pengguna..." autocomplete="off">

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
              <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" />
              <path d="M21 3v5h-5" />
              <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" />
              <path d="M8 16H3v5" />
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
          class="lucide lucide-users mr-1.5 sm:mr-3 text-blue-600 w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
          <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        <span id="results-title" class="text-base sm:text-lg md:text-xl">Data PPP Aktif</span>
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
            <path d="m9 18 6-6-6-6" />
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

<!-- Disconnect Confirmation Modal -->
<div id="disconnect-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
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
      <h3 class="text-base sm:text-lg font-medium text-gray-900 mt-2">Konfirmasi Disconnect</h3>
      <div class="mt-2 px-7 py-3">
        <p class="text-sm text-gray-500">
          Apakah Anda yakin ingin memutuskan koneksi user <strong id="disconnect-username"></strong>?
        </p>
      </div>
      <div class="items-center px-4 py-3">
        <button id="confirm-disconnect-btn"
          class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 flex items-center justify-center">
          <svg id="disconnect-loading" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-loader-2 animate-spin mr-2 hidden">
            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
          </svg>
          Ya, Disconnect
        </button>
        <button id="cancel-disconnect-btn"
          class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Isolir Confirmation Modal -->
<div id="isolir-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
  <div
    class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-sm sm:w-96 shadow-lg rounded-md bg-white">
    <div class="mt-2 sm:mt-3 text-center">
      <div class="mx-auto flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-yellow-100">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-triangle-alert text-yellow-600 w-6 h-6">
          <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
          <path d="M12 9v4" />
          <path d="M12 17h.01" />
        </svg>
      </div>
      <h3 class="text-base sm:text-lg font-medium text-gray-900 mt-2" id="isolir-modal-title">Konfirmasi Isolir</h3>
      <div class="mt-2 px-7 py-3">
        <p class="text-sm text-gray-500">
          Apakah Anda yakin ingin <span id="isolir-action-text" class="font-bold">mengisolir</span> user <strong
            id="isolir-username"></strong>?
        </p>
      </div>
      <div class="items-center px-4 py-3">
        <button id="confirm-isolir-btn"
          class="px-4 py-2 bg-yellow-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 flex items-center justify-center">
          <svg id="isolir-loading" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-loader-2 animate-spin mr-2 hidden">
            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
          </svg>
          Ya, Lanjutkan
        </button>
        <button id="cancel-isolir-btn"
          class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modern Ping Modal -->
<div id="ping-modal"
  class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-2 sm:p-4 hidden z-50">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
    <!-- Modal Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 sm:p-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="bg-white bg-opacity-20 rounded-full p-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-activity text-xl sm:text-2xl">
              <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg sm:text-xl font-bold">Network Ping Tool</h3>
            <p class="text-blue-100 text-sm">Target: <span id="ping-target-address"
                class="font-mono bg-black bg-opacity-20 px-2 py-1 rounded">-</span></p>
          </div>
        </div>
        <button id="close-ping-modal"
          class="text-white hover:bg-white hover:bg-opacity-20 p-2 rounded-full transition-all duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-x">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
          </svg>
        </button>
      </div>
    </div>

    <div class="p-4 sm:p-6">
      <!-- Control Panel -->
      <div class="bg-gray-50 rounded-xl p-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
          <div class="flex gap-2">
            <button id="start-ping-btn"
              class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg transform hover:scale-105">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-play mr-2">
                <polygon points="6 3 20 12 6 21 6 3" />
              </svg>
              <span class="hidden sm:inline">Start Ping</span>
              <span class="sm:hidden">Start</span>
            </button>
            <button id="stop-ping-btn"
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg transform hover:scale-105 hidden">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-square mr-2">
                <rect width="18" height="18" x="3" y="3" rx="2" />
              </svg>
              <span class="hidden sm:inline">Stop Ping</span>
              <span class="sm:hidden">Stop</span>
            </button>
          </div>

          <div class="flex items-center gap-4">
            <div class="flex items-center">
              <label class="text-sm font-medium text-gray-700 mr-2">Count:</label>
              <input type="number" id="ping-count" value="4" min="1" max="20"
                class="w-16 px-2 py-1 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center">
              <label class="text-sm font-medium text-gray-700 mr-2">Interval:</label>
              <select id="ping-interval"
                class="px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1000">1s</option>
                <option value="500">0.5s</option>
                <option value="2000">2s</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Status Indicator -->
      <div id="ping-status" class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 hidden">
        <div class="flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-loader-2 animate-spin text-blue-600 mr-3">
            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
          </svg>
          <span class="text-blue-800 font-medium">Running ping test...</span>
          <div class="ml-auto flex items-center space-x-2">
            <span class="text-xs text-blue-600">Progress:</span>
            <span id="ping-progress" class="text-xs font-mono text-blue-800">0/4</span>
          </div>
        </div>
        <div class="mt-2 bg-blue-200 rounded-full h-2">
          <div id="ping-progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300"
            style="width: 0%"></div>
        </div>
      </div>

      <!-- Terminal-style Results -->
      <div class="bg-gray-900 rounded-xl overflow-hidden shadow-inner">
        <!-- Terminal Header -->
        <div class="bg-gray-800 p-3 flex items-center justify-between border-b border-gray-700">
          <div class="flex items-center space-x-2">
            <div class="flex space-x-1">
              <div class="w-3 h-3 bg-red-500 rounded-full"></div>
              <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
              <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            </div>
            <span class="text-gray-300 text-sm font-mono ml-4">ping@mikrotik:~$</span>
          </div>
          <button id="clear-ping-results"
            class="text-gray-400 hover:text-white text-sm flex items-center space-x-1 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-trash-2">
              <path d="M3 6h18" />
              <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
              <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
              <line x1="10" x2="10" y1="11" y2="17" />
              <line x1="14" x2="14" y1="11" y2="17" />
            </svg>
            <span class="hidden sm:inline">Clear</span>
          </button>
        </div>

        <!-- Terminal Content -->
        <div id="ping-results"
          class="p-4 h-64 sm:h-80 overflow-y-auto font-mono text-xs sm:text-sm leading-relaxed bg-gray-900">
          <div class="text-gray-500 text-center py-8">
            <div class="text-4xl mb-4 flex justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-activity text-gray-600">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
              </svg>
            </div>
            <p class="text-sm sm:text-base">Click "Start Ping" to begin network test</p>
            <p class="text-xs sm:text-sm mt-2 text-gray-600">Terminal ready...</p>
          </div>
        </div>
      </div>

      <!-- Statistics Panel -->
      <div id="ping-summary" class="mt-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-4 hidden">
        <div class="flex items-center justify-between mb-3">
          <h4 class="font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-chart-line text-blue-600 mr-2">
              <path d="M3 3v18h18" />
              <path d="m19 9-5 5-4-4-3 3" />
            </svg>
            Statistics Summary
          </h4>
          <div id="ping-status-indicator" class="px-3 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-800">
            Completed
          </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
          <!-- Packets -->
          <div class="bg-white rounded-lg p-3 text-center shadow-sm">
            <div class="text-xs text-gray-500 mb-1">Sent</div>
            <div id="ping-sent" class="text-lg font-bold text-blue-600">0</div>
          </div>

          <div class="bg-white rounded-lg p-3 text-center shadow-sm">
            <div class="text-xs text-gray-500 mb-1">Received</div>
            <div id="ping-received" class="text-lg font-bold text-green-600">0</div>
          </div>

          <div class="bg-white rounded-lg p-3 text-center shadow-sm">
            <div class="text-xs text-gray-500 mb-1">Lost</div>
            <div id="ping-lost" class="text-lg font-bold text-red-600">0</div>
          </div>

          <div class="bg-white rounded-lg p-3 text-center shadow-sm">
            <div class="text-xs text-gray-500 mb-1">Loss</div>
            <div id="ping-loss-percent" class="text-lg font-bold text-orange-600">0%</div>
          </div>

          <!-- RTT Stats -->
          <div class="bg-white rounded-lg p-3 text-center shadow-sm">
            <div class="text-xs text-gray-500 mb-1">Min RTT</div>
            <div id="ping-min-rtt" class="text-sm font-bold text-purple-600">-</div>
          </div>

          <div class="bg-white rounded-lg p-3 text-center shadow-sm">
            <div class="text-xs text-gray-500 mb-1">Avg RTT</div>
            <div id="ping-avg-rtt" class="text-sm font-bold text-indigo-600">-</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function () {
    let searchTimeout;
    let currentRequest;
    let currentDisconnectUsername = '';
    let pingInProgress = false;
    let pingInterval;
    let pingStats = {
      sent: 0,
      received: 0,
      lost: 0,
      times: [],
      startTime: null
    };
    let allActiveUsers = [];
    let filteredData = [];
    let currentPage = 1;
    let currentLimit = 25;

    const $searchInput = $('#search-input');
    const $searchLoading = $('#search-loading');
    const $resultsSection = $('#results-section');
    const $resultsGrid = $('#results-grid');
    const $resultsTitle = $('#results-title');
    const $resultsCount = $('#results-count');
    const $toastContainer = $('#toast-container');
    const $loadingSkeleton = $('#loading-skeleton');
    const $refreshBtn = $('#refresh-btn');
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

    // Modal elements
    const $disconnectModal = $('#disconnect-modal');
    const $disconnectUsername = $('#disconnect-username');
    const $confirmDisconnectBtn = $('#confirm-disconnect-btn');
    const $cancelDisconnectBtn = $('#cancel-disconnect-btn');
    const $disconnectLoading = $('#disconnect-loading');

    // Ping modal elements
    const $pingModal = $('#ping-modal');
    const $closePingModal = $('#close-ping-modal');
    const $pingTargetAddress = $('#ping-target-address');
    const $startPingBtn = $('#start-ping-btn');
    const $stopPingBtn = $('#stop-ping-btn');
    const $pingCount = $('#ping-count');
    const $pingInterval = $('#ping-interval');
    const $pingStatus = $('#ping-status');
    const $pingProgress = $('#ping-progress');
    const $pingProgressBar = $('#ping-progress-bar');
    const $pingResults = $('#ping-results');
    const $pingSummary = $('#ping-summary');
    const $clearPingResults = $('#clear-ping-results');
    const $pingStatusIndicator = $('#ping-status-indicator');

    // Stats elements
    const $pingSent = $('#ping-sent');
    const $pingReceived = $('#ping-received');
    const $pingLost = $('#ping-lost');
    const $pingLossPercent = $('#ping-loss-percent');
    const $pingMinRtt = $('#ping-min-rtt');
    const $pingAvgRtt = $('#ping-avg-rtt');

    // Isolir config and elements
    let isolirConfig = {
      isolir_profile: 'block_client',
      normal_profile: '100rb'
    };
    let currentIsolirUsername = '';
    let currentIsolirAction = '';

    const $isolirModal = $('#isolir-modal');
    const $isolirUser = $('#isolir-username');
    const $isolirActionText = $('#isolir-action-text');
    const $isolirModalTitle = $('#isolir-modal-title');
    const $confirmIsolirBtn = $('#confirm-isolir-btn');
    const $cancelIsolirBtn = $('#cancel-isolir-btn');
    const $isolirLoading = $('#isolir-loading');

    // Fetch Isolir Config
    $.ajax({
      url: '/api/ppp/isolir-config',
      type: 'POST',
      dataType: 'json'
    }).done(function (response) {
      if (response.success && response.data) {
        isolirConfig = response.data;
      }
    });

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

    // Function to format uptime
    function formatUptime(uptime) {
      if (!uptime || uptime === 'N/A') return 'N/A';
      console.log(uptime)
      // Parse format like 2d1h17m3s
      let days = 0, hours = 0, minutes = 0, seconds = 0;

      const dMatch = uptime.match(/(\d+)d/);
      const hMatch = uptime.match(/(\d+)h/);
      const mMatch = uptime.match(/(\d+)m/);
      const sMatch = uptime.match(/(\d+)s/);

      if (dMatch) days = parseInt(dMatch[1]);
      if (hMatch) hours = parseInt(hMatch[1]);
      if (mMatch) minutes = parseInt(mMatch[1]);
      if (sMatch) seconds = parseInt(sMatch[1]);

      let parts = [];
      if (days > 0) parts.push(`${days} hari`);

      const hStr = hours.toString().padStart(2, '0');
      const mStr = minutes.toString().padStart(2, '0');
      const sStr = seconds.toString().padStart(2, '0');

      parts.push(`${hStr}:${mStr}:${sStr}`);

      return parts.join(' ');
    }

    // Function to show/hide loading state
    function setLoadingState(loading) {
      if (loading) {
        $searchLoading.removeClass('hidden');
        $loadingSkeleton.removeClass('hidden');
        $resultsGrid.addClass('hidden');
        $paginationControls.addClass('hidden');
        $paginationInfo.addClass('hidden');
        $refreshBtn.prop('disabled', true).find('i').addClass('fa-spin');
      } else {
        $searchLoading.addClass('hidden');
        $loadingSkeleton.addClass('hidden');
        $resultsGrid.removeClass('hidden');
        $refreshBtn.prop('disabled', false).find('i').removeClass('fa-spin');
      }
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
      renderActiveUsers();
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

    // Function to render PPP card
    function renderPPPCard(ppp) {
      const userName = ppp.name || 'N/A';
      const initials = getProfileInitials(userName);
      const avatarColor = getAvatarColor(userName);
      const profile = ppp.profile || 'none';
      const service = ppp.service || '';
      const address = ppp.address || 'N/A';
      const uptime = formatUptime(ppp.uptime);
      const callerId = ppp['caller-id'] || '';
      const profileIcon = getProfileIcon(profile, 'mr-1.5 w-3.5 h-3.5 inline-block');

      const statusClass = 'bg-green-100 text-green-700 border-green-200';
      const statusIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 inline-block"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
      const statusTextFull = 'Aktif';
      const statusTextShort = 'On';

      const ipAddress = address || callerId || '';

      return `
    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100" data-username="${userName}">
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
              ${userName}
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
              ${profileIcon}
              <span class="font-medium">${profile}</span>
            </p>
            
            <p class="text-xs text-gray-500 flex items-center truncate">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe mr-1.5 text-gray-400"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
              ${address}
            </p>
          
            ${uptime !== 'N/A' ? `
              <p class="text-xs text-gray-500 flex items-center truncate">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-1.5 text-gray-400"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Uptime: <span class="text-gray-700 ml-1">${uptime}</span>
              </p>
            ` : ''}
            
            ${callerId ? `
              <p class="text-xs text-gray-500 flex items-center truncate">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone mr-1.5 text-gray-400"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                ${callerId}
              </p>
            ` : ''}
          </div>
        </div>

        <!-- Desktop Actions -->
        <div class="hidden sm:flex flex-col gap-2 items-end justify-center pl-4 border-l border-gray-100">
          <div class="flex gap-2">
            <button class="disconnect-btn group p-2 bg-white border border-red-200 text-red-600 rounded-lg hover:bg-red-50 hover:border-red-300 transition-all shadow-sm" title="Disconnect User" data-username="${userName}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-power group-hover:scale-110 transition-transform"><path d="M12 2v10"/><path d="M18.4 6.6a9 9 0 1 1-12.77.04"/></svg>
            </button>
            <button class="isolir-btn group p-2 bg-white border ${profile === isolirConfig.isolir_profile ? 'border-green-200 text-green-600 hover:bg-green-50' : 'border-orange-200 text-orange-600 hover:bg-orange-50'} rounded-lg transition-all shadow-sm" 
              title="${profile === isolirConfig.isolir_profile ? 'Restore User' : 'Isolir User'}" 
              data-username="${userName}"
              data-current-profile="${profile}">
              ${profile === isolirConfig.isolir_profile
          ? '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-open group-hover:scale-110 transition-transform"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>'
          : '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock group-hover:scale-110 transition-transform"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>'}
            </button>
          </div>
          <div class="flex gap-2">
            ${ipAddress && ipAddress !== 'N/A' ? `
              <button class="ping-btn group p-2 bg-white border border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm" title="Ping IP Address" data-ip="${ipAddress}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity group-hover:scale-110 transition-transform"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
              </button>
              <button class="open-ip-btn group p-2 bg-white border border-indigo-200 text-indigo-600 rounded-lg hover:bg-indigo-50 hover:border-indigo-300 transition-all shadow-sm" title="Open IP Address" data-ip="${ipAddress}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link group-hover:scale-110 transition-transform"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
              </button>
            ` : ''}
          </div>
        </div>
        
        <!-- Mobile: Three-dot dropdown menu -->
        <div class="sm:hidden relative">
          <button class="action-menu-btn p-2 text-gray-500 hover:bg-gray-100 rounded-full transition-colors" type="button" data-username="${userName}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
          </button>
          <div class="action-menu hidden absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden" style="z-index: 1000;">
            <button class="disconnect-btn-mobile w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 flex items-center gap-3 border-b border-gray-50" data-username="${userName}">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-power"><path d="M12 2v10"/><path d="M18.4 6.6a9 9 0 1 1-12.77.04"/></svg>
              <span>Disconnect</span>
            </button>
            <button class="isolir-btn-mobile w-full text-left px-4 py-3 text-sm ${profile === isolirConfig.isolir_profile ? 'text-green-600 hover:bg-green-50' : 'text-orange-600 hover:bg-orange-50'} flex items-center gap-3 border-b border-gray-50" 
              data-username="${userName}"
              data-current-profile="${profile}">
              ${profile === isolirConfig.isolir_profile
          ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-open"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>'
          : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>'}
              <span>${profile === isolirConfig.isolir_profile ? 'Restore Koneksi' : 'Isolir User'}</span>
            </button>
            ${ipAddress && ipAddress !== 'N/A' ? `
              <button class="ping-btn-mobile w-full text-left px-4 py-3 text-sm text-blue-600 hover:bg-blue-50 flex items-center gap-3 border-b border-gray-50" data-ip="${ipAddress}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                <span>Ping IP</span>
              </button>
              <button class="open-ip-btn-mobile w-full text-left px-4 py-3 text-sm text-indigo-600 hover:bg-indigo-50 flex items-center gap-3" data-ip="${ipAddress}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                <span>Buka IP</span>
              </button>
            ` : ''}
          </div>
        </div>
        
      </div>
    </div>
  `;
    }

    // Function to disconnect user
    function disconnectUser(username) {
      $disconnectLoading.removeClass('hidden');
      $confirmDisconnectBtn.prop('disabled', true);

      $.ajax({
        url: '/api/ppp/disconnect',
        type: 'POST',
        data: {
          username: username
        },
        dataType: 'json',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .done(function (response) {
          $disconnectLoading.addClass('hidden');
          $confirmDisconnectBtn.prop('disabled', false);

          if (response.success) {
            showToast(response.message, 'success');
            $(`.bg-white[data-username="${username}"]`).fadeOut(400, function () {
              $(this).remove();
              const currentCountText = $resultsCount.text();
              const currentCount = parseInt(currentCountText.match(/\d+/) || [0])[0];
              const newCount = Math.max(0, currentCount - 1);
              $resultsCount.text('(' + newCount + ' pengguna aktif)');

              if (newCount === 0) {
                $resultsGrid.html(`
                <div class="text-center py-12">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-x text-gray-400 mb-4 mx-auto"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="17" x2="22" y1="8" y2="13"/><line x1="22" x2="17" y1="8" y2="13"/></svg>
                  <p class="text-gray-500 text-lg">Tidak ada pengguna PPP yang aktif</p>
                  <p class="text-gray-400 text-sm mt-2">Semua pengguna sedang offline</p>
                </div>
              `);
              }
            });
            $disconnectModal.addClass('hidden');
          } else {
            showToast(response.message || 'Gagal memutuskan koneksi user', 'error');
          }
        })
        .fail(function () {
          $disconnectLoading.addClass('hidden');
          $confirmDisconnectBtn.prop('disabled', false);
          showToast('Terjadi kesalahan saat memutuskan koneksi', 'error');
        });
    }

    // Function to perform ping with improved API response handling
    function performPing(address, count = 1) {
      return $.ajax({
        url: '/api/mikrotik/ping',
        type: 'POST',
        data: {
          address: address,
          count: count
        },
        dataType: 'json',
        timeout: 30000, // 30 second timeout
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
    }

    // Function to append result to terminal
    function appendPingResult(text, type = 'normal') {
      const timestamp = new Date().toLocaleTimeString('en-US', {
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      });

      let className = 'text-gray-300';
      let prefix = '';

      switch (type) {
        case 'success':
          className = 'text-green-400';
          prefix = '✓ ';
          break;
        case 'error':
          className = 'text-red-400';
          prefix = '✗ ';
          break;
        case 'timeout':
          className = 'text-orange-400';
          prefix = '⚠ ';
          break;
        case 'info':
          className = 'text-blue-400';
          prefix = 'ℹ ';
          break;
        case 'warning':
          className = 'text-yellow-400';
          prefix = '⚠ ';
          break;
        case 'header':
          className = 'text-cyan-400 font-bold';
          prefix = '>>> ';
          break;
        case 'summary':
          className = 'text-purple-400 font-semibold';
          prefix = '=== ';
          break;
        case 'command':
          className = 'text-white font-mono';
          prefix = '$ ';
          break;
      }

      const resultHtml = `<div class="${className} mb-1">
        <span class="text-gray-500 text-xs">[${timestamp}]</span> 
        <span class="ml-2">${prefix}${text}</span>
      </div>`;

      $pingResults.append(resultHtml);
      $pingResults.scrollTop($pingResults[0].scrollHeight);
    }

    // Function to parse time from API response
    function parseTime(timeStr) {
      if (!timeStr || timeStr === 'N/A') return null;

      // Handle format like "23ms478us"
      const match = timeStr.match(/(\d+(?:\.\d+)?)ms/);
      if (match) {
        return parseFloat(match[1]);
      }

      // Handle other formats
      const numMatch = timeStr.match(/(\d+(?:\.\d+)?)/);
      if (numMatch) {
        return parseFloat(numMatch[1]);
      }

      return null;
    }

    // Function to format ping response from new API
    function formatPingResponse(response, seq, targetAddress) {
      try {
        if (response && response.success && response.data) {
          const pingData = response.data;

          if (pingData.status === 'success') {
            const size = pingData.size || '56';
            const ttl = pingData.ttl || 'N/A';
            const time = pingData.time || 'N/A';
            const host = pingData.raw_response?.host || pingData.host || targetAddress;

            // Parse time for statistics
            const timeMs = parseTime(time);
            if (timeMs !== null) {
              pingStats.times.push(timeMs);
              pingStats.received++;
            }

            return {
              success: true,
              message: `64 bytes from ${host}: icmp_seq=${seq} ttl=${ttl} time=${time}`,
              timeMs: timeMs
            };
          } else if (pingData.status === 'timeout' || pingData['packet-loss'] === '100') {
            pingStats.lost++;
            return {
              success: false,
              message: `Request timeout for icmp_seq ${seq}`,
              timeMs: null
            };
          } else {
            pingStats.lost++;
            const errorMsg = pingData.error || pingData.status || 'unknown error';
            return {
              success: false,
              message: `Ping failed for icmp_seq ${seq}: ${errorMsg}`,
              timeMs: null
            };
          }
        } else {
          pingStats.lost++;
          return {
            success: false,
            message: `Ping failed for icmp_seq ${seq}: ${response?.message || 'no response'}`,
            timeMs: null
          };
        }
      } catch (e) {
        console.error('Error formatting ping response:', e);
        pingStats.lost++;
        return {
          success: false,
          message: `Ping failed for icmp_seq ${seq}: parse error`,
          timeMs: null
        };
      }
    }

    // Function to update statistics display
    function updatePingStats() {
      const lossPercent = pingStats.sent > 0 ? Math.round((pingStats.lost / pingStats.sent) * 100) : 0;

      $pingSent.text(pingStats.sent);
      $pingReceived.text(pingStats.received);
      $pingLost.text(pingStats.lost);
      $pingLossPercent.text(lossPercent + '%');

      if (pingStats.times.length > 0) {
        const minTime = Math.min(...pingStats.times).toFixed(2);
        const avgTime = (pingStats.times.reduce((a, b) => a + b, 0) / pingStats.times.length).toFixed(2);
        $pingMinRtt.text(minTime + 'ms');
        $pingAvgRtt.text(avgTime + 'ms');
      } else {
        $pingMinRtt.text('-');
        $pingAvgRtt.text('-');
      }

      // Update status indicator
      if (pingInProgress) {
        $pingStatusIndicator.removeClass().addClass('px-3 py-1 rounded-full text-xs font-medium bg-blue-200 text-blue-800').text('Running');
      } else {
        if (lossPercent === 0) {
          $pingStatusIndicator.removeClass().addClass('px-3 py-1 rounded-full text-xs font-medium bg-green-200 text-green-800').text('Success');
        } else if (lossPercent === 100) {
          $pingStatusIndicator.removeClass().addClass('px-3 py-1 rounded-full text-xs font-medium bg-red-200 text-red-800').text('Failed');
        } else {
          $pingStatusIndicator.removeClass().addClass('px-3 py-1 rounded-full text-xs font-medium bg-yellow-200 text-yellow-800').text('Partial');
        }
      }

      $pingSummary.removeClass('hidden');
    }

    // Function to update progress
    function updateProgress(current, total) {
      const percent = Math.round((current / total) * 100);
      $pingProgress.text(`${current}/${total}`);
      $pingProgressBar.css('width', percent + '%');
    }

    // Function to start ping test
    function startPing(address) {
      if (pingInProgress) return;

      pingInProgress = true;
      $startPingBtn.addClass('hidden');
      $stopPingBtn.removeClass('hidden');
      $pingStatus.removeClass('hidden');

      const count = parseInt($pingCount.val()) || 4;
      const interval = parseInt($pingInterval.val()) || 1000;

      // Reset stats
      pingStats = {
        sent: 0,
        received: 0,
        lost: 0,
        times: [],
        startTime: new Date()
      };

      // Clear previous results
      $pingResults.html('');
      $pingSummary.addClass('hidden');

      // Add header
      appendPingResult(`PING ${address} (${address}) 56(84) bytes of data.`, 'header');
      appendPingResult(`ping -c ${count} ${address}`, 'command');
      appendPingResult('', 'normal');

      let currentPing = 0;

      function sendNextPing() {
        if (!pingInProgress || currentPing >= count) {
          // Ping completed
          finishPing();
          return;
        }

        pingStats.sent++;
        updateProgress(pingStats.sent, count);
        updatePingStats();

        performPing(address, 1)
          .done(function (response) {
            console.log('Ping API Response:', response);

            const result = formatPingResponse(response, currentPing + 1, address);
            if (result.success) {
              appendPingResult(result.message, 'success');
            } else {
              appendPingResult(result.message, 'timeout');
            }

            updatePingStats();
          })
          .fail(function (xhr, textStatus, errorThrown) {
            console.error('Ping request failed:', textStatus, errorThrown);
            pingStats.lost++;

            let errorMsg = `Request failed for icmp_seq ${currentPing + 1}`;
            if (textStatus === 'timeout') {
              errorMsg += ': connection timeout';
            } else if (textStatus === 'error') {
              errorMsg += ': network error';
            } else {
              errorMsg += `: ${textStatus}`;
            }

            appendPingResult(errorMsg, 'error');
            updatePingStats();
          })
          .always(function () {
            currentPing++;

            if (pingInProgress && currentPing < count) {
              setTimeout(sendNextPing, interval);
            } else if (currentPing >= count) {
              setTimeout(finishPing, 100);
            }
          });
      }

      function finishPing() {
        if (!pingInProgress) return;

        pingInProgress = false;
        $startPingBtn.removeClass('hidden');
        $stopPingBtn.addClass('hidden');
        $pingStatus.addClass('hidden');

        // Add summary
        const duration = new Date() - pingStats.startTime;
        const lossPercent = pingStats.sent > 0 ? Math.round((pingStats.lost / pingStats.sent) * 100) : 0;

        appendPingResult('', 'normal');
        appendPingResult(`--- ${address} ping statistics ---`, 'summary');

        let summaryText = `${pingStats.sent} packets transmitted, ${pingStats.received} received, ${lossPercent}% packet loss`;
        if (duration > 0) {
          summaryText += `, time ${Math.round(duration)}ms`;
        }
        appendPingResult(summaryText, 'info');

        if (pingStats.times.length > 0) {
          const minTime = Math.min(...pingStats.times).toFixed(3);
          const maxTime = Math.max(...pingStats.times).toFixed(3);
          const avgTime = (pingStats.times.reduce((a, b) => a + b, 0) / pingStats.times.length).toFixed(3);
          const stddev = Math.sqrt(
            pingStats.times.reduce((sq, n) => sq + Math.pow(n - avgTime, 2), 0) / pingStats.times.length
          ).toFixed(3);

          appendPingResult(`rtt min/avg/max/mdev = ${minTime}/${avgTime}/${maxTime}/${stddev} ms`, 'info');
        }

        updatePingStats();

        if (lossPercent === 0) {
          appendPingResult('Ping test completed successfully!', 'success');
        } else if (lossPercent === 100) {
          appendPingResult('Ping test failed - destination unreachable', 'error');
        } else {
          appendPingResult('Ping test completed with packet loss', 'warning');
        }
      }

      // Start first ping
      sendNextPing();
    }

    // Function to stop ping
    function stopPing() {
      if (!pingInProgress) return;

      pingInProgress = false;
      $startPingBtn.removeClass('hidden');
      $stopPingBtn.addClass('hidden');
      $pingStatus.addClass('hidden');

      appendPingResult('', 'normal');
      appendPingResult('Ping test interrupted by user', 'warning');
      updatePingStats();
    }

    // Function to load all active PPP
    function loadAllActive() {
      if (currentRequest) {
        currentRequest.abort();
      }

      setLoadingState(true);

      currentRequest = $.ajax({
        url: '/api/ppp/active',
        type: 'POST',
        dataType: 'json',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .done(function (response) {
          setLoadingState(false);

          if (response.success) {
            allActiveUsers = response.data || [];
            renderActiveUsers();
          } else {
            $resultsGrid.html(`
            <div class="text-center py-12">
              <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
              <p class="text-red-500 text-lg">Gagal memuat data PPP aktif</p>
              <p class="text-red-400 text-sm mt-2">${response.message || 'Terjadi kesalahan tidak diketahui'}</p>
            </div>
          `);
            showToast(response.message || 'Gagal memuat data PPP aktif', 'error');
          }
        })
        .fail(function (xhr) {
          if (xhr.statusText !== 'abort') {
            setLoadingState(false);
            $resultsGrid.html(`
            <div class="text-center py-12">
              <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
              <p class="text-red-500 text-lg">Koneksi gagal</p>
              <p class="text-red-400 text-sm mt-2">Tidak dapat terhubung ke server</p>
            </div>
          `);
            showToast('Terjadi kesalahan saat memuat data', 'error');
          }
        })
        .always(function () {
          currentRequest = null;
        });
    }

    // Function to render active users with filter and limit
    function renderActiveUsers() {
      const searchTerm = $searchInput.val().trim().toLowerCase();

      // Filter data
      filteredData = allActiveUsers;
      if (searchTerm) {
        filteredData = allActiveUsers.filter(ppp => {
          const name = (ppp.name || '').toLowerCase();
          const profile = (ppp.profile || '').toLowerCase();
          const service = (ppp.service || '').toLowerCase();
          const address = (ppp.address || '').toLowerCase();
          const callerId = (ppp['caller-id'] || '').toLowerCase();

          return name.includes(searchTerm) ||
            profile.includes(searchTerm) ||
            service.includes(searchTerm) ||
            address.includes(searchTerm) ||
            callerId.includes(searchTerm);
        });
      }

      const itemsPerPage = currentLimit === 'all' ? 'all' : parseInt(currentLimit);
      const paginationResult = paginateData(filteredData, currentPage, itemsPerPage);

      // Update titles and counts
      if (searchTerm) {
        $resultsTitle.text('Hasil Pencarian');
      } else {
        $resultsTitle.text('Data PPP Aktif');
      }
      $resultsCount.text('(' + filteredData.length + ' pengguna)');

      // Update pagination info
      $totalRecords.text(filteredData.length);
      $showingStart.text(paginationResult.start);
      $showingEnd.text(paginationResult.end);

      if (filteredData.length > 0 && currentLimit !== 'all') {
        $paginationInfo.removeClass('hidden');
      } else {
        $paginationInfo.addClass('hidden');
      }

      // Render Grid
      let html = '';
      if (paginationResult.data.length > 0) {
        paginationResult.data.forEach(function (ppp) {
          html += renderPPPCard(ppp);
        });
      } else {
        const noDataMessage = searchTerm ?
          'Tidak ada hasil pencarian' :
          'Tidak ada pengguna PPP yang aktif';
        const noDataSubMessage = searchTerm ?
          `"${searchTerm}" tidak ditemukan` :
          'Semua pengguna sedang offline';

        html = `
          <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-${searchTerm ? 'search' : 'users'} text-gray-400 mb-4 mx-auto"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <p class="text-gray-500 text-lg">${noDataMessage}</p>
            <p class="text-gray-400 text-sm mt-2">${noDataSubMessage}</p>
          </div>
        `;
      }

      $resultsGrid.html(html);
      updatePaginationControls(paginationResult);
    }

    // Event Handlers
    $refreshBtn.on('click', function () {
      loadAllActive();
    });

    $perPageSelect.on('change', function () {
      currentLimit = $(this).val();
      currentPage = 1;
      renderActiveUsers();
    });

    // Pagination Click Handlers
    $firstPage.on('click', () => goToPage(1));
    $prevPage.on('click', () => goToPage(currentPage - 1));
    $nextPage.on('click', () => goToPage(currentPage + 1));
    $lastPage.on('click', () => {
      const itemsPerPage = currentLimit === 'all' ? filteredData.length : parseInt(currentLimit);
      const totalPages = Math.ceil(filteredData.length / itemsPerPage);
      goToPage(totalPages);
    });

    $(document).on('click', '.page-number', function () {
      goToPage(parseInt($(this).data('page')));
    });

    $jumpPageBtn.on('click', function () {
      const page = parseInt($jumpToPage.val());
      const itemsPerPage = currentLimit === 'all' ? filteredData.length : parseInt(currentLimit);
      const totalPages = Math.ceil(filteredData.length / itemsPerPage);
      if (page >= 1 && page <= totalPages) goToPage(page);
    });

    $jumpToPage.on('keypress', function (e) {
      if (e.which === 13) $jumpPageBtn.click();
    });

    // Real-time search on input (Client-side)
    $searchInput.on('input', function () {
      const searchTerm = $(this).val().trim();
      renderActiveUsers();
    });

    // Disconnect button event handler
    $(document).on('click', '.disconnect-btn', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const username = $(this).data('username');
      if (username) {
        currentDisconnectUsername = username;
        $disconnectUsername.text(username);
        $disconnectModal.removeClass('hidden');
      }
    });

    // Ping button event handler
    $(document).on('click', '.ping-btn', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const ipAddress = $(this).data('ip');
      if (ipAddress && ipAddress !== 'N/A') {
        const cleanIp = ipAddress.replace(/^https?:\/\//, '').replace(/:\d+$/, '');
        $pingTargetAddress.text(cleanIp);
        $pingModal.removeClass('hidden');

        // Reset state
        stopPing();
        $pingResults.html(`
          <div class="text-gray-500 text-center py-8">
            <div class="text-4xl mb-4">
              <i class="fas fa-satellite-dish text-gray-600"></i>
            </div>
            <p class="text-sm sm:text-base">Click "Start Ping" to begin network test</p>
            <p class="text-xs sm:text-sm mt-2 text-gray-600">Target: ${cleanIp}</p>
          </div>
        `);
        $pingSummary.addClass('hidden');
        $pingCount.val(4);
        $pingInterval.val('1000');
      }
    });

    // Open IP button event handler
    $(document).on('click', '.open-ip-btn', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const ipAddress = $(this).data('ip');
      if (ipAddress) {
        const cleanIp = ipAddress.replace(/^https?:\/\//, '');
        const url = 'http://' + cleanIp;
        window.open(url, '_blank');
      }
    });

    // Modal event handlers
    $confirmDisconnectBtn.on('click', function () {
      if (currentDisconnectUsername) {
        disconnectUser(currentDisconnectUsername);
      }
    });

    $cancelDisconnectBtn.on('click', function () {
      $disconnectModal.addClass('hidden');
      currentDisconnectUsername = '';
    });

    $disconnectModal.on('click', function (e) {
      if (e.target === this) {
        $disconnectModal.addClass('hidden');
        currentDisconnectUsername = '';
      }
    });

    // Isolir Logic
    function handleIsolirClick(username, currentProfile) {
      if (!username) return;

      const isIsolir = currentProfile === isolirConfig.isolir_profile;
      // If current is isolir, action is restore. If not isolir, action is isolir.
      const action = isIsolir ? 'restore' : 'isolir';

      currentIsolirUsername = username;
      currentIsolirAction = action;

      $isolirUser.text(username);
      if (action === 'isolir') {
        $isolirModalTitle.text('Konfirmasi Isolir');
        $isolirActionText.text('mengisolir');
        $isolirActionText.removeClass('text-green-600').addClass('text-red-600');
        $confirmIsolirBtn.removeClass('bg-green-600 hover:bg-green-700 focus:ring-green-300').addClass('bg-red-600 hover:bg-red-700 focus:ring-red-300');
      } else {
        $isolirModalTitle.text('Konfirmasi Restore');
        $isolirActionText.text('memulihkan (restore)');
        $isolirActionText.removeClass('text-red-600').addClass('text-green-600');
        $confirmIsolirBtn.removeClass('bg-red-600 hover:bg-red-700 focus:ring-red-300').addClass('bg-green-600 hover:bg-green-700 focus:ring-green-300');
      }

      $isolirModal.removeClass('hidden');
    }

    $(document).on('click', '.isolir-btn', function (e) {
      e.preventDefault();
      e.stopPropagation();
      handleIsolirClick($(this).data('username'), $(this).data('current-profile'));
    });

    $(document).on('click', '.isolir-btn-mobile', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $('.action-menu').addClass('hidden'); // Close menu
      handleIsolirClick($(this).data('username'), $(this).data('current-profile'));
    });

    $cancelIsolirBtn.on('click', function () {
      $isolirModal.addClass('hidden');
      currentIsolirUsername = '';
      currentIsolirAction = '';
    });

    $isolirModal.on('click', function (e) {
      if (e.target === this) {
        $isolirModal.addClass('hidden');
        currentIsolirUsername = '';
        currentIsolirAction = '';
      }
    });

    $confirmIsolirBtn.on('click', function () {
      if (!currentIsolirUsername || !currentIsolirAction) return;

      $isolirLoading.removeClass('hidden');
      $confirmIsolirBtn.prop('disabled', true);

      $.ajax({
        url: '/api/ppp/toggle-isolir',
        type: 'POST',
        data: {
          username: currentIsolirUsername,
          action: currentIsolirAction
        },
        dataType: 'json'
      }).done(function (response) {
        $isolirLoading.addClass('hidden');
        $confirmIsolirBtn.prop('disabled', false);

        if (response.success) {
          showToast(response.message, 'success');
          $isolirModal.addClass('hidden');

          // Ideally we should reload the list or update the specific item
          // For now, re-fetch all to ensure up-to-date state (simplest)
          loadAllActive();
        } else {
          showToast(response.message || 'Gagal mengubah profile user', 'error');
        }
      }).fail(function () {
        $isolirLoading.addClass('hidden');
        $confirmIsolirBtn.prop('disabled', false);
        showToast('Terjadi kesalahan koneksi', 'error');
      });
    });

    // Ping modal event handlers
    $closePingModal.on('click', function () {
      stopPing();
      $pingModal.addClass('hidden');
    });

    $startPingBtn.on('click', function () {
      const address = $pingTargetAddress.text().trim();
      if (address) {
        startPing(address);
      }
    });

    $stopPingBtn.on('click', function () {
      stopPing();
    });

    $clearPingResults.on('click', function () {
      const address = $pingTargetAddress.text().trim();
      $pingResults.html(`
        <div class="text-gray-500 text-center py-8">
          <div class="text-4xl mb-4">
            <i class="fas fa-satellite-dish text-gray-600"></i>
          </div>
          <p class="text-sm sm:text-base">Click "Start Ping" to begin network test</p>
          ${address ? `<p class="text-xs sm:text-sm mt-2 text-gray-600">Target: ${address}</p>` : ''}
        </div>
      `);
      $pingSummary.addClass('hidden');
    });

    $pingModal.on('click', function (e) {
      if (e.target === this) {
        stopPing();
        $pingModal.addClass('hidden');
      }
    });

    // Prevent modal close when clicking inside
    $('.bg-white.rounded-2xl, .relative.top-20').on('click', function (e) {
      e.stopPropagation();
    });

    // Handle Escape key
    $(document).on('keydown', function (e) {
      if (e.key === 'Escape') {
        if (!$disconnectModal.hasClass('hidden')) {
          $disconnectModal.addClass('hidden');
          currentDisconnectUsername = '';
        }
        if (!$pingModal.hasClass('hidden')) {
          stopPing();
          $pingModal.addClass('hidden');
        }
      }
    });

    // ===== Mobile Dropdown Menu Event Handlers =====

    // Toggle dropdown menu on three-dot button click
    $(document).on('click', '.action-menu-btn', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const $menu = $(this).siblings('.action-menu');

      // Close all other open menus
      $('.action-menu').not($menu).addClass('hidden');

      // Toggle current menu
      $menu.toggleClass('hidden');
    });

    // Close dropdown when clicking outside
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.action-menu-btn').length && !$(e.target).closest('.action-menu').length) {
        $('.action-menu').addClass('hidden');
      }
    });

    // Mobile disconnect button
    $(document).on('click', '.disconnect-btn-mobile', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const username = $(this).data('username');
      if (username) {
        currentDisconnectUsername = username;
        $disconnectUsername.text(username);
        $disconnectModal.removeClass('hidden');
        $('.action-menu').addClass('hidden');
      }
    });

    // Mobile ping button
    $(document).on('click', '.ping-btn-mobile', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const ipAddress = $(this).data('ip');
      if (ipAddress && ipAddress !== 'N/A') {
        const cleanIp = ipAddress.replace(/^https?:\/\//, '').replace(/:\d+$/, '');
        $pingTargetAddress.text(cleanIp);
        $pingModal.removeClass('hidden');
        stopPing();
        $pingResults.html(`
          <div class="text-gray-500 text-center py-8">
            <div class="text-4xl mb-4"><i class="fas fa-satellite-dish text-gray-600"></i></div>
            <p class="text-sm sm:text-base">Click "Start Ping" to begin network test</p>
            <p class="text-xs sm:text-sm mt-2 text-gray-600">Target: ${cleanIp}</p>
          </div>
        `);
        $pingSummary.addClass('hidden');
        $pingCount.val(4);
        $pingInterval.val('1000');
        $('.action-menu').addClass('hidden');
      }
    });

    // Mobile open IP button
    $(document).on('click', '.open-ip-btn-mobile', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const ipAddress = $(this).data('ip');
      if (ipAddress && ipAddress !== 'N/A') {
        const cleanIp = ipAddress.replace(/^https?:\/\//, '').replace(/:\d+$/, '');
        window.open(`http://${cleanIp}`, '_blank');
        $('.action-menu').addClass('hidden');
      }
    });

    // Toggle details on mobile cards
    $(document).on('click', '.toggle-details', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const $details = $(this).siblings('.mobile-details');
      const $icon = $(this).find('i');

      $details.toggleClass('hidden');

      // Toggle icon
      if ($details.hasClass('hidden')) {
        $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        $(this).find('span').text('Detail');
      } else {
        $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        $(this).find('span').text('Sembunyikan');
      }
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

    // Auto load data when page loads
    checkMikrotikConnection().then(online => {
      if (online) {
        loadAllActive();
      } else {
        setLoadingState(false);
        $resultsGrid.addClass('hidden');
        $('#error-state').removeClass('hidden').html(`
          <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
              <div class="flex">
                  <div class="flex-shrink-0">
                      <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                      </svg>
                  </div>
                  <div class="ml-3">
                      <p class="text-sm text-red-700">
                          MikroTik Offline - Data tidak dapat dimuat
                      </p>
                  </div>
              </div>
          </div>
        `);
        showToast('MikroTik Offline', 'error');
      }
    });
  });
  // Flash message timeout
  setTimeout(function () {
    $('#flash-message').fadeOut('slow');
  }, 3000); // 3 seconds
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>