<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto px-2.5 sm:px-4 py-3 sm:py-6 md:py-8">

  <!-- Message Toast Container -->
  <div id="toast-container" class="fixed top-2 right-2 z-50 max-w-xs sm:max-w-sm"></div>

  <!-- Dashboard Content -->
  <div id="dashboard-content" class="p-2 sm:p-6">
    <!-- Flash Messages -->
    <?php if ($flash): ?>
      <div id="flash-message"
        class="mb-4 z-20 sm:mb-6 p-3 sm:p-4 rounded-lg <?= $flash['type'] == 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-blue-100 border border-blue-400 text-blue-700' ?>">
        <div class="text-sm sm:text-base"><?= htmlspecialchars($flash['message']) ?></div>
      </div>
      <script>
        setTimeout(function () {
          $('#flash-message').fadeOut('slow');
        }, 3000); // 3 seconds
      </script>
    <?php endif; ?>

    <!-- Search Section -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-2.5 sm:p-4 md:p-6 mb-3 sm:mb-6">
      <h2 class="text-lg sm:text-2xl font-bold text-gray-800 mb-6 text-center flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-search mr-3 text-blue-600 w-6 h-6 sm:w-8 sm:h-8">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.3-4.3" />
        </svg>
        Pencarian PPP
      </h2>

      <div class="max-w-2xl mx-auto">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2 text-center flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-user mr-2 w-4 h-4">
              <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            Cari pengguna berdasarkan nama atau mac-address
          </label>
          <div class="relative">
            <input type="text" id="search-input"
              class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base shadow-sm transition-shadow"
              placeholder="Masukkan nama atau mac-address..." autocomplete="off">

            <!-- Loading indicator inside input -->
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
              <svg id="search-loading" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-loader-2 animate-spin text-blue-500 hidden w-5 h-5">
                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
              </svg>
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-2 text-center flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-info mr-1 w-3 h-3">
              <circle cx="12" cy="12" r="10" />
              <path d="M12 16v-4" />
              <path d="M12 8h.01" />
            </svg>
            Pencarian otomatis - ketik untuk mulai mencari
          </p>
        </div>
      </div>
    </div>

    <!-- System Resource Section -->
    <div id="system-resource-section" class="mb-3 sm:mb-6 transition-all duration-300">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4 md:gap-6">
        <!-- System Info Card -->
        <div
          class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">System Info</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-router text-blue-500">
              <rect x="2" y="11" width="20" height="8" rx="2" ry="2" />
              <path d="M5 11V5a2 2 0 0 1 2-2h14" />
              <path d="M2 15h20" />
            </svg>
          </div>
          <div class="space-y-2">
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-500">Board Name</span>
              <span class="font-semibold text-gray-800" id="sys-board">-</span>
            </div>
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-500">Version</span>
              <span class="font-medium text-gray-800 bg-blue-50 px-2 py-0.5 rounded text-xs" id="sys-version">-</span>
            </div>
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-500">Uptime</span>
              <span class="font-semibold text-blue-600" id="sys-uptime">-</span>
            </div>
          </div>
        </div>

        <!-- CPU Info Card -->
        <div
          class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 border-l-4 border-purple-500 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">CPU Status</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-cpu text-purple-500">
              <rect x="4" y="4" width="16" height="16" rx="2" ry="2" />
              <rect x="9" y="9" width="6" height="6" />
              <path d="M15 2v2" />
              <path d="M15 20v2" />
              <path d="M2 15h2" />
              <path d="M2 9h2" />
              <path d="M20 15h2" />
              <path d="M20 9h2" />
              <path d="M9 2v2" />
              <path d="M9 20v2" />
            </svg>
          </div>
          <div class="relative pt-1">
            <div class="flex mb-2 items-center justify-between">
              <div>
                <span
                  class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-purple-600 bg-purple-200">
                  Load
                </span>
              </div>
              <div class="text-right">
                <span class="text-xs font-semibold inline-block text-purple-600" id="cpu-load-text">0%</span>
              </div>
            </div>
            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-purple-100">
              <div id="cpu-load-bar" style="width:0%"
                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-purple-500 transition-all duration-500">
              </div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
              <span>Freq: <strong class="text-gray-700" id="cpu-freq">-</strong></span>
              <span>Count: <strong class="text-gray-700" id="cpu-count">-</strong></span>
            </div>
          </div>
        </div>

        <!-- Memory & Disk Card -->
        <div
          class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 border-l-4 border-green-500 hover:shadow-xl transition-shadow">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Resources</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-hard-drive text-green-500">
              <line x1="22" y1="12" x2="2" y2="12" />
              <path
                d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z" />
              <line x1="6" y1="16" x2="6.01" y2="16" />
              <line x1="10" y1="16" x2="10.01" y2="16" />
            </svg>
          </div>

          <!-- Memory -->
          <div class="mb-3">
            <div class="flex justify-between text-xs mb-1">
              <span class="text-gray-600">RAM Usage</span>
              <span class="font-bold text-green-600" id="mem-text">0/0 MB</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5">
              <div id="mem-bar" class="bg-green-500 h-1.5 rounded-full transition-all duration-500" style="width: 0%">
              </div>
            </div>
          </div>

          <!-- HDD -->
          <div>
            <div class="flex justify-between text-xs mb-1">
              <span class="text-gray-600">HDD Usage</span>
              <span class="font-bold text-yellow-600" id="hdd-text">0/0 MB</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5">
              <div id="hdd-bar" class="bg-yellow-500 h-1.5 rounded-full transition-all duration-500" style="width: 0%">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Results Section -->
    <div id="results-section" class="bg-white rounded-lg sm:rounded-xl shadow-lg p-2.5 sm:p-4 md:p-6 hidden">
      <h3 class="text-xs sm:text-lg md:text-xl font-bold text-gray-800 mb-2.5 sm:mb-4 md:mb-6 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-users mr-1 sm:mr-2 md:mr-3 text-green-600 w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
          <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        <span id="results-title" class="text-xs sm:text-lg md:text-xl">Hasil Pencarian</span>
        <span id="results-count"
          class="text-[9px] sm:text-xs md:text-sm font-normal text-gray-500 ml-0.5 sm:ml-1 block sm:inline mt-0.5 sm:mt-0"></span>
      </h3>

      <!-- Loading Skeleton -->
      <div id="loading-skeleton" class="hidden">
        <div class="space-y-3 sm:space-y-4">
          <?php for ($i = 0; $i < 6; $i++): ?>
            <div class="animate-pulse">
              <div class="bg-gray-200 rounded-lg sm:rounded-xl h-16 sm:h-20"></div>
            </div>
          <?php endfor; ?>
        </div>
      </div>

      <!-- All Users Section -->
      <div id="all-users-section">
        <div id="users-grid" class="space-y-3 sm:space-y-4">
          <!-- All users will be populated here -->
        </div>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal for Disconnect -->
  <div id="disconnect-modal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 px-4">
    <div
      class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-sm sm:w-96 shadow-lg rounded-md bg-white">
      <div class="mt-2 sm:mt-3 text-center">
        <div class="mx-auto flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-red-100">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-triangle-alert text-red-600 w-5 h-5 sm:w-6 sm:h-6">
            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
            <path d="M12 9v4" />
            <path d="M12 17h.01" />
          </svg>
        </div>
        <h3 class="text-base sm:text-lg font-medium text-gray-900 mt-2">Konfirmasi Disconnect</h3>
        <div class="mt-2 px-4 sm:px-7 py-2 sm:py-3">
          <p class="text-xs sm:text-sm text-gray-500">
            Apakah Anda yakin ingin memutuskan koneksi user <strong id="disconnect-username"></strong>?
          </p>
        </div>
        <div class="items-center px-2 sm:px-4 py-2 sm:py-3 space-y-2">
          <button id="confirm-disconnect-btn"
            class="px-3 sm:px-4 py-2 bg-red-600 text-white text-sm sm:text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 flex items-center justify-center">
            <svg id="disconnect-loading" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-loader-2 animate-spin mr-1 sm:mr-2 hidden w-3 h-3 sm:w-4 sm:h-4">
              <path d="M21 12a9 9 0 1 1-6.219-8.56" />
            </svg>
            Ya, Disconnect
          </button>
          <button id="cancel-disconnect-btn"
            class="px-3 sm:px-4 py-2 bg-gray-300 text-gray-800 text-sm sm:text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Isolir Confirmation Modal -->
  <div id="isolir-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 px-4">
    <div
      class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-sm sm:w-96 shadow-lg rounded-md bg-white">
      <div class="mt-2 sm:mt-3 text-center">
        <div class="mx-auto flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-yellow-100">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-triangle-alert text-yellow-600 w-5 h-5 sm:w-6 sm:h-6">
            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
            <path d="M12 9v4" />
            <path d="M12 17h.01" />
          </svg>
        </div>
        <h3 class="text-base sm:text-lg font-medium text-gray-900 mt-2" id="isolir-modal-title">Konfirmasi Isolir</h3>
        <div class="mt-2 px-4 sm:px-7 py-2 sm:py-3">
          <p class="text-xs sm:text-sm text-gray-500">
            Apakah Anda yakin ingin <span id="isolir-action-text" class="font-bold">mengisolir</span> user <strong
              id="isolir-username"></strong>?
          </p>
        </div>
        <div class="items-center px-2 sm:px-4 py-2 sm:py-3 space-y-2">
          <button id="confirm-isolir-btn"
            class="px-3 sm:px-4 py-2 bg-yellow-600 text-white text-sm sm:text-base font-medium rounded-md w-full shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-300 flex items-center justify-center">
            <svg id="isolir-loading" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-loader-2 animate-spin mr-1 sm:mr-2 hidden w-3 h-3 sm:w-4 sm:h-4">
              <path d="M21 12a9 9 0 1 1-6.219-8.56" />
            </svg>
            Ya, Lanjutkan
          </button>
          <button id="cancel-isolir-btn"
            class="px-3 sm:px-4 py-2 bg-gray-300 text-gray-800 text-sm sm:text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Improved Responsive Ping Modal with Scroll Support -->
  <div id="ping-modal"
    class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-start justify-center p-2 sm:p-4 hidden z-50 overflow-y-auto">
    <div
      class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl my-4 overflow-hidden min-h-0 flex flex-col max-h-[calc(100vh-2rem)]">
      <!-- Modal Header -->
      <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-3 sm:p-6 flex-shrink-0">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1">
            <div class="bg-white bg-opacity-20 rounded-full p-1.5 sm:p-2 flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-activity text-lg sm:text-2xl">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
              </svg>
            </div>
            <div class="min-w-0 flex-1">
              <h3 class="text-base sm:text-xl font-bold">Network Ping Tool</h3>
              <p class="text-blue-100 text-xs sm:text-sm truncate">
                Target: <span id="ping-target-address"
                  class="font-mono bg-black bg-opacity-20 px-1 sm:px-2 py-0.5 sm:py-1 rounded text-xs sm:text-sm">-</span>
              </p>
            </div>
          </div>
          <button id="close-ping-modal"
            class="text-white hover:bg-white hover:bg-opacity-20 p-1.5 sm:p-2 rounded-full transition-all duration-200 flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-x text-lg sm:text-xl">
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Scrollable Content Area -->
      <div class="flex-1 overflow-y-auto min-h-0">
        <div class="p-3 sm:p-6">
          <!-- Control Panel -->
          <div class="bg-gray-50 rounded-xl p-3 sm:p-4 mb-3 sm:mb-4">
            <div class="flex flex-row space-x-3 space-y-3 sm:space-y-0 sm:space-x-3 sm:items-center">
              <div class="flex gap-2 justify-center sm:justify-start">
                <button id="start-ping-btn"
                  class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg transform hover:scale-105 text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-play mr-1 sm:mr-2 w-3 h-3 sm:w-4 sm:h-4">
                    <polygon points="6 3 20 12 6 21 6 3" />
                  </svg>
                  <span>Start</span>
                </button>
                <button id="stop-ping-btn"
                  class="bg-red-600 hover:bg-red-700 text-white px-3 sm:px-4 py-2 rounded-lg transition-all duration-200 flex items-center shadow-md hover:shadow-lg transform hover:scale-105 hidden text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-square mr-1 sm:mr-2 w-3 h-3 sm:w-4 sm:h-4">
                    <rect width="18" height="18" x="3" y="3" rx="2" />
                  </svg>
                  <span>Stop</span>
                </button>
              </div>

              <div class="flex flex-row items-center gap-2 sm:gap-4">
                <div class="flex items-center w-full sm:w-auto justify-center sm:justify-start">
                  <label class="text-xs sm:text-sm font-medium text-gray-700 mr-2 whitespace-nowrap">Count:</label>
                  <input type="number" id="ping-count" value="4" min="1" max="20"
                    class="w-16 px-2 py-1 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>

                <div class="flex items-center w-full sm:w-auto justify-center sm:justify-start">
                  <label class="text-xs sm:text-sm font-medium text-gray-700 mr-2 whitespace-nowrap">Interval:</label>
                  <select id="ping-interval"
                    class="px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="1000">1s</option>
                    <option value="500">0.5s</option>
                    <option value="2000">2s</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Status Indicator -->
          <div id="ping-status" class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3 sm:mb-4 hidden">
            <div
              class="flex flex-col sm:flex-row items-center justify-center sm:justify-between space-y-2 sm:space-y-0">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-loader-2 animate-spin text-blue-600 mr-2 sm:mr-3 w-4 h-4 sm:w-5 sm:h-5">
                  <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                </svg>
                <span class="text-blue-800 font-medium text-sm">Running ping test...</span>
              </div>
              <div class="flex items-center space-x-2">
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
            <div
              class="bg-gray-800 p-2 sm:p-3 flex items-center justify-between border-b border-gray-700 flex-shrink-0">
              <div class="flex items-center space-x-2 min-w-0 flex-1">
                <div class="flex space-x-1">
                  <div class="w-2 h-2 sm:w-3 sm:h-3 bg-red-500 rounded-full"></div>
                  <div class="w-2 h-2 sm:w-3 sm:h-3 bg-yellow-500 rounded-full"></div>
                  <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-500 rounded-full"></div>
                </div>
                <span class="text-gray-300 text-xs sm:text-sm font-mono ml-2 sm:ml-4 truncate">ping@mikrotik:~$</span>
              </div>
              <button id="clear-ping-results"
                class="text-gray-400 hover:text-white text-xs sm:text-sm flex items-center space-x-1 transition-colors flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-trash-2 w-3 h-3">
                  <path d="M3 6h18" />
                  <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                  <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                  <line x1="10" x2="10" y1="11" y2="17" />
                  <line x1="14" x2="14" y1="11" y2="17" />
                </svg>
                <span class="hidden sm:inline">Clear</span>
              </button>
            </div>

            <!-- Terminal Content with Fixed Height and Scroll -->
            <div id="ping-results"
              class="p-3 sm:p-4 h-48 sm:h-64 md:h-80 overflow-y-auto font-mono text-xs leading-relaxed bg-gray-900 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
              <div class="text-gray-500 text-center py-4 sm:py-8">
                <div class="text-2xl sm:text-4xl mb-2 sm:mb-4 flex justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-activity text-gray-600">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                  </svg>
                </div>
                <p class="text-xs sm:text-base">Click "Start Ping" to begin network test</p>
                <p class="text-xs sm:text-sm mt-1 sm:mt-2 text-gray-600">Terminal ready...</p>
              </div>
            </div>
          </div>

          <!-- Statistics Panel -->
          <div id="ping-summary"
            class="mt-3 sm:mt-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-3 sm:p-4 hidden">
            <div
              class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-2 sm:mb-3 space-y-2 sm:space-y-0">
              <h4 class="font-bold text-gray-800 flex items-center text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="lucide lucide-chart-line text-blue-600 mr-1 sm:mr-2 w-4 h-4">
                  <path d="M3 3v18h18" />
                  <path d="m19 9-5 5-4-4-3 3" />
                </svg>
                Statistics Summary
              </h4>
              <div id="ping-status-indicator"
                class="px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-800">
                Completed
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-3">
              <!-- Packets -->
              <div class="bg-white rounded-lg p-2 sm:p-3 text-center shadow-sm">
                <div class="text-xs text-gray-500 mb-0.5 sm:mb-1">Sent</div>
                <div id="ping-sent" class="text-base sm:text-lg font-bold text-blue-600">0</div>
              </div>

              <div class="bg-white rounded-lg p-2 sm:p-3 text-center shadow-sm">
                <div class="text-xs text-gray-500 mb-0.5 sm:mb-1">Received</div>
                <div id="ping-received" class="text-base sm:text-lg font-bold text-green-600">0</div>
              </div>

              <div class="bg-white rounded-lg p-2 sm:p-3 text-center shadow-sm">
                <div class="text-xs text-gray-500 mb-0.5 sm:mb-1">Lost</div>
                <div id="ping-lost" class="text-base sm:text-lg font-bold text-red-600">0</div>
              </div>

              <div class="bg-white rounded-lg p-2 sm:p-3 text-center shadow-sm">
                <div class="text-xs text-gray-500 mb-0.5 sm:mb-1">Loss</div>
                <div id="ping-loss-percent" class="text-base sm:text-lg font-bold text-orange-600">0%</div>
              </div>

              <!-- RTT Stats -->
              <div class="bg-white rounded-lg p-2 sm:p-3 text-center shadow-sm">
                <div class="text-xs text-gray-500 mb-0.5 sm:mb-1">Min RTT</div>
                <div id="ping-min-rtt" class="text-sm font-bold text-purple-600">-</div>
              </div>

              <div class="bg-white rounded-lg p-2 sm:p-3 text-center shadow-sm">
                <div class="text-xs text-gray-500 mb-0.5 sm:mb-1">Avg RTT</div>
                <div id="ping-avg-rtt" class="text-sm font-bold text-indigo-600">-</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Custom Scrollbar Styles for Webkit browsers -->
<style>
  .scrollbar-thin::-webkit-scrollbar {
    width: 6px;
  }

  .scrollbar-thin::-webkit-scrollbar-track {
    background: #374151;
    border-radius: 3px;
  }

  .scrollbar-thin::-webkit-scrollbar-thumb {
    background: #6b7280;
    border-radius: 3px;
  }

  .scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
  }

  /* For Firefox */
  .scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: #6b7280 #374151;
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function () {
    let searchTimeout;
    let refreshInterval;
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

    const $searchInput = $('#search-input');
    const $searchLoading = $('#search-loading');
    const $resultsSection = $('#results-section');
    const $resultsTitle = $('#results-title');
    const $resultsCount = $('#results-count');
    const $toastContainer = $('#toast-container');
    const $loadingSkeleton = $('#loading-skeleton');

    // Section elements
    const $allUsersSection = $('#all-users-section');
    const $usersGrid = $('#users-grid');

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
    const $pingIntervalSelect = $('#ping-interval');
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

    // Function to stop ping - FIXED: Added missing function
    function stopPing() {
      if (pingInProgress) {
        pingInProgress = false;
        if (pingInterval) {
          clearInterval(pingInterval);
          pingInterval = null;
        }

        $startPingBtn.removeClass('hidden');
        $stopPingBtn.addClass('hidden');
        $pingStatus.addClass('hidden');

        appendPingResult('Ping test stopped by user', 'warning');
        updatePingStats();
      }
    }

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
        hash = hash & hash;
      }
      return colors[Math.abs(hash) % colors.length];
    }

    // Function to get profile icon
    // Function to get profile icon - FIXED: Return full SVG
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
      return uptime;
    }

    // Function to format bytes to human readable format
    function formatBytes(bytes, decimals = 2, suffix = 'B') {
      if (bytes === 0) return '0 ' + suffix;
      const k = 1024;
      const dm = decimals < 0 ? 0 : decimals;
      const sizes = [suffix, 'K' + suffix, 'M' + suffix, 'G' + suffix, 'T' + suffix];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    // Function to show/hide loading state
    function setLoadingState(loading) {
      if (loading) {
        $searchLoading.removeClass('hidden');
        $loadingSkeleton.removeClass('hidden');
        $usersGrid.addClass('hidden');
      } else {
        $searchLoading.addClass('hidden');
        $loadingSkeleton.addClass('hidden');
        $usersGrid.removeClass('hidden');
      }
    }

    // Function to show toast message
    function showToast(message, type = 'info', duration = 5000) {
      const toastId = 'toast-' + Date.now();
      let bgColor, borderColor, textColor, icon;

      switch (type) {
        case 'success':
          bgColor = 'bg-green-50';
          borderColor = 'border-green-200';
          textColor = 'text-green-800';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-500 mt-0.5 mr-2 flex-shrink-0 w-3 h-3 sm:w-4 sm:h-4"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
          break;
        case 'error':
          bgColor = 'bg-red-50';
          borderColor = 'border-red-200';
          textColor = 'text-red-800';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert text-red-500 mt-0.5 mr-2 flex-shrink-0 w-3 h-3 sm:w-4 sm:h-4"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>';
          break;
        case 'warning':
          bgColor = 'bg-yellow-50';
          borderColor = 'border-yellow-200';
          textColor = 'text-yellow-800';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert text-yellow-500 mt-0.5 mr-2 flex-shrink-0 w-3 h-3 sm:w-4 sm:h-4"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>';
          break;
        default:
          bgColor = 'bg-blue-50';
          borderColor = 'border-blue-200';
          textColor = 'text-blue-800';
          icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info text-blue-500 mt-0.5 mr-2 flex-shrink-0 w-3 h-3 sm:w-4 sm:h-4"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>';
      }

      const toastHtml = `
        <div id="${toastId}" class="toast-item ${bgColor} ${borderColor} ${textColor} border rounded-lg shadow-lg p-3 mb-2 transform translate-x-full transition-all duration-300 ease-in-out">
          <div class="flex items-start">
            ${icon}
            <div class="flex-1 mr-2">
              <p class="text-xs sm:text-sm font-medium leading-tight">${message}</p>
            </div>
            <button type="button" class="toast-close flex-shrink-0 ml-1 text-gray-400 hover:text-gray-600 focus:outline-none" onclick="closeToast('${toastId}')">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-3 h-3"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
          </div>
        </div>
      `;

      $toastContainer.append(toastHtml);

      setTimeout(() => {
        $(`#${toastId}`).removeClass('translate-x-full');
      }, 100);

      if (duration > 0) {
        setTimeout(() => {
          closeToast(toastId);
        }, duration);
      }
    }

    // Function to close toast
    window.closeToast = function (toastId) {
      const $toast = $(`#${toastId}`);
      if ($toast.length) {
        $toast.addClass('translate-x-full');
        setTimeout(() => {
          $toast.remove();
        }, 300);
      }
    }

    // Function to render user card
    function renderUserCard(user) {
      const userName = user.name || 'N/A';
      const initials = getProfileInitials(userName);
      const avatarColor = getAvatarColor(userName);
      const profile = user.profile || 'none';
      const profileIcon = getProfileIcon(profile, 'mr-1.5 w-3.5 h-3.5 inline-block');
      const isActive = user.status === 'active';

      // Status styling
      const statusClass = isActive ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200';
      const statusIcon = isActive
        ? '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 inline-block"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
        : '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x mr-1.5 inline-block"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>';
      const statusTextFull = isActive ? 'Aktif' : 'Tidak Aktif';
      const statusTextShort = isActive ? 'On' : 'Off';

      // Data fields based on status
      const address = isActive ? (user.address || 'N/A') : (user['remote-address'] || '-');
      const uptime = isActive ? (formatUptime(user.uptime) || 'N/A') : (user['last-logged-out'] || '-');
      const callerId = isActive ? (user['caller-id'] || '') : (user['last-caller-id'] || '-');

      // IP Address for buttons
      const ipAddress = isActive ? (user.address || user['caller-id'] || '') : '';

      // Traffic data (only for active users)
      const rxRate = isActive && user['rx-rate'] ? formatBytes(parseInt(user['rx-rate']), 2, 'bps') : '-';
      const txRate = isActive && user['tx-rate'] ? formatBytes(parseInt(user['tx-rate']), 2, 'bps') : '-';
      const bytesDown = isActive && user['bytes-down'] ? formatBytes(parseInt(user['bytes-down'])) : '-';
      const bytesUp = isActive && user['bytes-up'] ? formatBytes(parseInt(user['bytes-up'])) : '-';
      const maxLimit = isActive && user['max-limit'] ? user['max-limit'] : 'Unlimited';

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
                ${isActive ? 'Uptime:' : ''} <span class="text-gray-700 ml-1">${uptime}</span>
              </p>
            ` : ''}
            
            ${callerId ? `
              <p class="text-xs text-gray-500 flex items-center truncate">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-${isActive ? 'phone' : 'smartphone'} mr-1.5 text-gray-400">${isActive ? '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>' : '<rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/>'}</svg>
                ${callerId}
              </p>
            ` : ''}
          </div>
          
          <!-- Traffic Monitor (Only for Active Users) -->
          ${isActive ? `
            <div class="mt-3 pt-3 border-t border-gray-50">
              <div class="flex items-center gap-1.5 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity text-blue-600"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                <span class="text-xs font-bold text-gray-700">Traffic Monitor</span>
              </div>
              <div class="grid grid-cols-2 gap-2">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-2">
                  <div class="flex items-center gap-1 mb-0.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                    <p class="text-[10px] uppercase text-blue-600 font-bold">Download</p>
                  </div>
                  <p class="text-sm font-bold text-blue-700 traffic-rx" data-username="${userName}">${rxRate}</p>
                  <p class="text-[10px] text-blue-600 mt-0.5">Total: <span class="font-semibold">${bytesDown}</span></p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-2">
                  <div class="flex items-center gap-1 mb-0.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></div>
                    <p class="text-[10px] uppercase text-purple-600 font-bold">Upload</p>
                  </div>
                  <p class="text-sm font-bold text-purple-700 traffic-tx" data-username="${userName}">${txRate}</p>
                  <p class="text-[10px] text-purple-600 mt-0.5">Total: <span class="font-semibold">${bytesUp}</span></p>
                </div>
              </div>
              ${maxLimit !== 'Unlimited' ? `
                <div class="mt-2 text-[10px] text-gray-500 flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                  Limit: <span class="font-semibold text-gray-700">${maxLimit}</span>
                </div>
              ` : ''}
            </div>
          ` : ''}
        </div>

        <!-- Desktop Actions -->
        <div class="hidden sm:flex flex-col gap-2 items-end justify-center pl-4 border-l border-gray-100">
          <div class="flex gap-2">
            ${isActive ? `
              <button class="disconnect-btn group p-2 bg-white border border-red-200 text-red-600 rounded-lg hover:bg-red-50 hover:border-red-300 transition-all shadow-sm" title="Disconnect User" data-username="${userName}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-power group-hover:scale-110 transition-transform"><path d="M12 2v10"/><path d="M18.4 6.6a9 9 0 1 1-12.77.04"/></svg>
              </button>
            ` : ''}
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
            ${isActive ? `
              <button class="disconnect-btn-mobile w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 flex items-center gap-3 border-b border-gray-50" data-username="${userName}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-power"><path d="M12 2v10"/><path d="M18.4 6.6a9 9 0 1 1-12.77.04"/></svg>
                <span>Disconnect</span>
              </button>
            ` : ''}
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

            // Update user status
            const $userCard = $(`.bg-white[data-username="${username}"]`);
            $userCard.fadeOut(400, function () {
              // If we want to remove it: $(this).remove();
              // But for search results, we might want to keep it or refresh.
              // Simpler to refresh search.
              const searchTerm = $searchInput.val().trim();
              if (searchTerm.length >= 2) {
                performSearch(searchTerm);
              } else {
                $(this).remove(); // If no search term (unlikely in this view but possible if list preloaded)
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

    // Isolir Logic
    function handleIsolirClick(username, currentProfile) {
      if (!username) return;

      const isIsolir = currentProfile === isolirConfig.isolir_profile;
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
      $('.action-menu').addClass('hidden');
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

          // Refresh search result
          const searchTerm = $searchInput.val().trim();
          if (searchTerm.length >= 2) {
            performSearch(searchTerm);
          }
        } else {
          showToast(response.message || 'Gagal mengubah profile user', 'error');
        }
      }).fail(function () {
        $isolirLoading.addClass('hidden');
        $confirmIsolirBtn.prop('disabled', false);
        showToast('Terjadi kesalahan koneksi', 'error');
      });
    });

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
      const interval = parseInt($pingIntervalSelect.val()) || 1000;

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

    // Load all data explicitly on start
    let allUsersData = [];

    function fetchAllUsers() {
      setLoadingState(true);
      // Fetch separate and combine
      $.when(
        $.ajax({ url: '/api/ppp/active', type: 'POST', dataType: 'json' }),
        $.ajax({ url: '/api/ppp/non-active', type: 'POST', dataType: 'json' })
      ).done(function (activeResp, nonActiveResp) {
        const active = (activeResp[0] && activeResp[0].success) ? activeResp[0].data : [];
        const nonActive = (nonActiveResp[0] && nonActiveResp[0].success) ? nonActiveResp[0].data : [];

        // Mark status
        active.forEach(u => u.status = 'active');
        nonActive.forEach(u => u.status = 'inactive');

        allUsersData = [...active, ...nonActive];
        setLoadingState(false);
      }).fail(function () {
        setLoadingState(false);
        showToast('Gagal memuat data pengguna', 'error');
      });
    }

    // Function to perform search (client side)
    function performSearch(searchTerm) {
      if (!allUsersData.length && searchTerm.length > 0) {
        // Try to fetch if empty? Or just wait.
        // Maybe fetchAllUsers was called on init.
      }

      $resultsSection.removeClass('hidden');
      $resultsTitle.text('Hasil Pencarian');

      const term = searchTerm.toLowerCase();
      const filtered = allUsersData.filter(user => {
        const name = (user.name || '').toLowerCase();
        const callerId = (user['caller-id'] || '').toLowerCase();
        const lastCallerId = (user['last-caller-id'] || '').toLowerCase();
        const macAddress = (user['mac-address'] || '').toLowerCase();
        const remoteAddress = (user['remote-address'] || '').toLowerCase();
        const address = (user['address'] || '').toLowerCase();

        return name.includes(term) ||
          callerId.includes(term) ||
          lastCallerId.includes(term) ||
          macAddress.includes(term) ||
          remoteAddress.includes(term) ||
          address.includes(term);
      });

      const totalUsers = filtered.length;
      $resultsCount.text('(' + totalUsers + ' pengguna ditemukan)');

      let usersHtml = '';
      if (totalUsers > 0) {
        filtered.forEach(function (user) {
          usersHtml += renderUserCard(user);
        });
      } else {
        usersHtml = `
            <div class="text-center py-8 sm:py-12">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search text-gray-400 mb-3 sm:mb-4 mx-auto"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
              <p class="text-gray-500 text-base sm:text-lg">Tidak ditemukan pengguna yang cocok dengan pencarian "${searchTerm}"</p>
              <p class="text-gray-400 text-xs sm:text-sm mt-1 sm:mt-2">Coba gunakan kata kunci yang berbeda</p>
            </div>
          `;
      }
      $usersGrid.html(usersHtml);
    }

    // Helper to check connection
    function checkMikrotikConnection() {
      return $.ajax({
        url: '/api/mikrotik/check',
        type: 'POST',
        dataType: 'json',
        timeout: 3000 // 3s timeout for the check itself
      }).then(response => {
        return response.success;
      }).catch(() => false);
    }

    // Initialize with connection check
    checkMikrotikConnection().then(online => {
      if (online) {
        fetchAllUsers();
        // Also start resource refresh if online
        startResourceRefresh();
      } else {
        showToast('MikroTik Offline - Data tidak dapat dimuat', 'error');
        handleResourceError('MikroTik Offline');
        // Stop resource refresh if offline to prevent spam
        stopResourceRefresh();
      }
    });

    // Real-time search on input
    $searchInput.on('input', function () {
      const searchTerm = $(this).val().trim();

      if (searchTimeout) {
        clearTimeout(searchTimeout);
      }

      if (searchTerm === '') {
        $resultsSection.addClass('hidden');
        return;
      }

      if (searchTerm.length < 2) {
        return;
      }

      searchTimeout = setTimeout(() => {
        performSearch(searchTerm);
      }, 500);
    });

    // Handle Enter key
    $searchInput.on('keypress', function (e) {
      if (e.which === 13) {
        const searchTerm = $(this).val().trim();
        if (searchTerm.length >= 2) {
          if (searchTimeout) {
            clearTimeout(searchTimeout);
          }
          performSearch(searchTerm);
        }
      }
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
            <div class="text-4xl mb-4 flex justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity text-gray-600"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <p class="text-sm sm:text-base">Click "Start Ping" to begin network test</p>
            <p class="text-xs sm:text-sm mt-2 text-gray-600">Target: ${cleanIp}</p>
          </div>
        `);
        $pingSummary.addClass('hidden');
        $pingCount.val(4);
        $pingIntervalSelect.val('1000');
      }
    });

    // Open IP button event handler
    $(document).on('click', '.open-ip-btn', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const ipAddress = $(this).data('ip');
      if (ipAddress) {
        // Remove any protocol prefix and add http://
        const cleanIp = ipAddress.replace(/^https?:\/\//, '');
        const url = 'http://' + cleanIp;
        window.open(url, '_blank');
      }
    });

    // Confirm disconnect button
    $confirmDisconnectBtn.on('click', function () {
      if (currentDisconnectUsername) {
        disconnectUser(currentDisconnectUsername);
      }
    });

    // Cancel disconnect button
    $cancelDisconnectBtn.on('click', function () {
      $disconnectModal.addClass('hidden');
      currentDisconnectUsername = '';
    });

    // Close modal when clicking outside
    $disconnectModal.on('click', function (e) {
      if (e.target === this) {
        $disconnectModal.addClass('hidden');
        currentDisconnectUsername = '';
      }
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
          <div class="text-4xl mb-4 flex justify-center">
             <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity text-gray-600"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
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
    $('.bg-white.rounded-2xl, .relative.top-10').on('click', function (e) {
      e.stopPropagation();
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
      if (ipAddress) {
        $pingTargetAddress.text(ipAddress);
        $pingModal.removeClass('hidden');
        stopPing();
        $pingResults.html(`
          <div class="text-gray-500 text-center py-8">
            <div class="text-4xl mb-4 flex justify-center"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity text-gray-600"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
            <p class="text-sm sm:text-base">Click "Start Ping" to begin network test</p>
            <p class="text-xs sm:text-sm mt-2 text-gray-600">Target: ${ipAddress}</p>
          </div>
        `);
        $pingSummary.addClass('hidden');
        $pingCount.val(4);
        $pingIntervalSelect.val('1000');
        $('.action-menu').addClass('hidden');
      }
    });

    // Mobile open IP button
    $(document).on('click', '.open-ip-btn-mobile', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const ipAddress = $(this).data('ip');
      if (ipAddress) {
        window.open(`http://${ipAddress}`, '_blank');
        $('.action-menu').addClass('hidden');
      }
    });

    // Toggle details on mobile cards
    $(document).on('click', '.toggle-details', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const $details = $(this).siblings('.mobile-details');
      const $icon = $(this).find('svg');

      $details.toggleClass('hidden');

      // Toggle icon
      if ($details.hasClass('hidden')) {
        $icon.removeClass('rotate-180');
        $(this).find('span').text('Detail');
      } else {
        $icon.addClass('transform transition-transform duration-200 rotate-180');
        $(this).find('span').text('Sembunyikan');
      }
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
        // Close all dropdowns
        $('.action-menu').addClass('hidden');
      }
    });

    // Focus on search input when page loads
    $searchInput.focus();

    // ===== Search and Auto-Refresh Logic =====
    const REFRESH_INTERVAL = 3000; // 3 seconds

    // System Resource Monitoring Logic
    let resourceInterval;
    const $resourceSection = $('#system-resource-section');

    let connectionErrorShown = false;

    function startResourceRefresh() {
      fetchSystemResource();
      if (resourceInterval) clearInterval(resourceInterval);
      resourceInterval = setInterval(fetchSystemResource, 5000);
    }

    function stopResourceRefresh() {
      if (resourceInterval) clearInterval(resourceInterval);
    }

    function fetchSystemResource() {
      $.ajax({
        url: '/api/mikrotik/resource/info',
        type: 'POST',
        dataType: 'json'
      }).done(function (response) {
        if (response.success && response.data) {
          connectionErrorShown = false; // Reset error flag on success
          $('#sys-uptime').removeClass('text-red-600').addClass('text-blue-600');
          updateResourceUI(response.data);
        } else {
          handleResourceError(response.message || 'Gagal mengambil data resource');
        }
      }).fail(function () {
        handleResourceError('Gagal menghubungi server');
      });
    }

    function handleResourceError(message) {
      // Update UI to show offline status
      $('#sys-board').text('-');
      $('#sys-version').text('-');
      $('#sys-uptime').text('Offline').removeClass('text-blue-600').addClass('text-red-600');

      $('#cpu-load-text').text('0%');
      $('#cpu-load-bar').css('width', '0%').removeClass('bg-purple-500').addClass('bg-gray-400');
      $('#cpu-freq').text('-');
      $('#cpu-count').text('-');

      $('#mem-text').text('0/0 MB');
      $('#mem-bar').css('width', '0%').removeClass('bg-green-500').addClass('bg-gray-400');

      $('#hdd-text').text('0/0 MB');
      $('#hdd-bar').css('width', '0%').removeClass('bg-yellow-500').addClass('bg-gray-400');

      // Show toast only once until connection is restored
      if (!connectionErrorShown) {
        showToast(message, 'error');
        connectionErrorShown = true;
      }
    }

    function updateResourceUI(data) {
      // System Info
      $('#sys-board').text(data['board-name'] || '-');
      $('#sys-version').text(data['version'] || '-');
      $('#sys-uptime').text(data['uptime'] || '-');

      // CPU Info
      const cpuLoad = parseInt(data['cpu-load'] || 0);
      $('#cpu-load-text').text(cpuLoad + '%');
      $('#cpu-load-bar').css('width', cpuLoad + '%');
      $('#cpu-freq').text(data['cpu-frequency'] || '-');
      $('#cpu-count').text(data['cpu-count'] || '-');

      // Memory Info
      const totalMem = parseSize(data['total-memory']);
      const freeMem = parseSize(data['free-memory']);
      const usedMem = totalMem - freeMem;
      const memPercent = totalMem > 0 ? (usedMem / totalMem) * 100 : 0;

      $('#mem-text').text(`${formatSize(usedMem)}/${formatSize(totalMem)}`);
      $('#mem-bar').css('width', memPercent + '%');

      // HDD Info
      const totalHdd = parseSize(data['total-hdd-space']);
      const freeHdd = parseSize(data['free-hdd-space']);
      const usedHdd = totalHdd - freeHdd;
      const hddPercent = totalHdd > 0 ? (usedHdd / totalHdd) * 100 : 0;

      $('#hdd-text').text(`${formatSize(usedHdd)}/${formatSize(totalHdd)}`);
      $('#hdd-bar').css('width', hddPercent + '%');
    }

    function parseSize(sizeStr) {
      if (!sizeStr) return 0;
      // Handle format like "32.0MiB" or "7.5MiB"
      const value = parseFloat(sizeStr);
      if (sizeStr.includes('KiB')) return value * 1024;
      if (sizeStr.includes('MiB')) return value * 1024 * 1024;
      if (sizeStr.includes('GiB')) return value * 1024 * 1024 * 1024;
      return value; // Assume bytes if no known suffix
    }

    function formatSize(bytes) {
      if (bytes === 0) return '0 B';
      const k = 1024;
      const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function startResourceRefresh() {
      if (resourceInterval) clearInterval(resourceInterval);
      fetchSystemResource(); // Fetch immediately
      resourceInterval = setInterval(fetchSystemResource, REFRESH_INTERVAL);
    }

    function stopResourceRefresh() {
      if (resourceInterval) {
        clearInterval(resourceInterval);
        resourceInterval = null;
      }
    }

    // Initial start handled by checkMikrotikConnection
    // startResourceRefresh();

    // Search input event
    $('#search-input').on('input', function () {
      const searchTerm = $(this).val().trim();

      // Stop refresh when searching
      stopRefresh();

      // Clear previous timeout
      clearTimeout(searchTimeout);

      // Toggle Resource Section
      if (searchTerm.length > 0) {
        $resourceSection.addClass('hidden');
        $resourceSection.css('opacity', '0');
        $resourceSection.css('max-height', '0');
        stopResourceRefresh();
      } else {
        $resourceSection.removeClass('hidden');
        $resourceSection.css('opacity', '1');
        $resourceSection.css('max-height', '500px'); // sufficient height
        startResourceRefresh();
      }

      if (searchTerm.length >= 2) {
        $searchLoading.removeClass('hidden');

        // Debounce search
        searchTimeout = setTimeout(() => {
          performSearch(searchTerm);
        }, 500);
      } else {
        $resultsSection.addClass('hidden');
        $usersGrid.empty();
      }
    });

    // Function to perform search
    function performSearch(searchTerm) {
      // Cancel previous request if exists
      if (currentRequest) {
        currentRequest.abort();
      }

      setLoadingState(true);
      $resultsSection.removeClass('hidden');

      currentRequest = $.ajax({
        url: '/api/ppp/search',
        type: 'POST',
        data: {
          search_term: searchTerm,
          search_type: 'all' // Search both active and non-active
        },
        dataType: 'json'
      })
        .done(function (response) {
          setLoadingState(false);

          // Debug logging
          console.log('Search response:', response);
          console.log('Response data:', response.data);

          if (response.success) {
            const data = response.data || {};
            console.log('Parsed data:', data);
            console.log('Active users:', data.active);
            console.log('Non-active users:', data.non_active);

            const activeUsers = data.active || [];
            const nonActiveUsers = data.non_active || [];
            const allUsers = [...activeUsers, ...nonActiveUsers];

            console.log('All users count:', allUsers.length);

            if (allUsers.length > 0) {
              $resultsTitle.text('Hasil Pencarian');
              $resultsCount.text(`(${allUsers.length} pengguna ditemukan)`);

              // Clear existing cards
              $usersGrid.empty();

              // Render user cards
              allUsers.forEach(user => {
                const cardHtml = renderUserCard(user);
                $usersGrid.append(cardHtml);
              });

              // Start auto-refresh only if there are active users
              if (activeUsers.length > 0) {
                startRefresh(searchTerm);
              }
            } else {
              $resultsTitle.text('Tidak Ada Hasil');
              $resultsCount.text('');
              $usersGrid.html(`
              <div class="col-span-full text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-x mx-auto text-gray-400 mb-4"><path d="m13.5 8.5-5 5"/><path d="m8.5 8.5 5 5"/><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <p class="text-gray-500">Tidak ada pengguna yang cocok dengan pencarian "${searchTerm}"</p>
              </div>
            `);
            }
          } else {
            showToast(response.message || 'Gagal mencari data', 'error');
          }
        })
        .fail(function (xhr) {
          if (xhr.statusText !== 'abort') {
            setLoadingState(false);
            showToast('Terjadi kesalahan saat mencari', 'error');
          }
        });
    }

    // Function to start auto-refresh
    function startRefresh(searchTerm) {
      // Stop existing interval
      stopRefresh();

      // Start new interval
      refreshInterval = setInterval(() => {
        refreshTrafficData(searchTerm);
      }, REFRESH_INTERVAL);
    }

    // Function to stop auto-refresh
    function stopRefresh() {
      if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
      }
    }

    // Function to refresh only traffic data
    function refreshTrafficData(searchTerm) {
      // Get all usernames from current results
      const usernames = [];
      $('.traffic-rx[data-username]').each(function () {
        const username = $(this).data('username');
        if (username && !usernames.includes(username)) {
          usernames.push(username);
        }
      });

      if (usernames.length === 0) {
        return; // No users to refresh
      }

      $.ajax({
        url: '/api/ppp/queue/traffic',
        type: 'POST',
        data: {
          usernames: usernames
        },
        dataType: 'json'
      })
        .done(function (response) {
          if (response.success && response.data) {
            const trafficData = response.data;

            // Update traffic data for each user
            Object.keys(trafficData).forEach(username => {
              const traffic = trafficData[username];

              if (traffic) {
                const $rxElement = $(`.traffic-rx[data-username="${username}"]`);
                const $txElement = $(`.traffic-tx[data-username="${username}"]`);

                if ($rxElement.length && $txElement.length) {
                  const rxRate = traffic['rx-rate'] ? formatBytes(parseInt(traffic['rx-rate']), 2, 'bps') : '-';
                  const txRate = traffic['tx-rate'] ? formatBytes(parseInt(traffic['tx-rate']), 2, 'bps') : '-';

                  // Update with smooth transition
                  if ($rxElement.text() !== rxRate) {
                    $rxElement.fadeOut(100, function () {
                      $(this).text(rxRate).fadeIn(100);
                    });
                  }

                  if ($txElement.text() !== txRate) {
                    $txElement.fadeOut(100, function () {
                      $(this).text(txRate).fadeIn(100);
                    });
                  }
                }
              }
            });
          }
        })
        .fail(function () {
          // Silently fail, don't interrupt user experience
          console.log('Failed to refresh traffic data');
        });
    }

    // Stop refresh when page is hidden/unloaded
    $(window).on('blur', stopRefresh);
    $(window).on('beforeunload', stopRefresh);
  });

  // Show dashboard
  function showDashboard() {
    document.getElementById('dashboard-content').classList.remove('hidden');

    // Update navigation
    document.querySelectorAll('nav a').forEach(link => {
      link.classList.remove('bg-blue-50', 'border-r-4', 'border-blue-600', 'text-gray-700');
      link.classList.add('text-gray-600');
    });
    const dashboardLink = document.querySelector('nav a[href="#"]');
    if (dashboardLink) {
      dashboardLink.classList.add('bg-blue-50', 'border-r-4', 'border-blue-600', 'text-gray-700');
      dashboardLink.classList.remove('text-gray-600');
    }
  }

  // Load settings
  function loadSettings() {
    fetch('settings.php', {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(response => response.text())
      .then(html => {
        document.getElementById('settings-form-container').innerHTML = html;
      })
      .catch(error => {
        console.error('Error loading settings:', error);
        document.getElementById('settings-form-container').innerHTML = `
          <div class="text-center py-8">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert text-red-400 mx-auto mb-4 w-8 h-8"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
              <p class="text-red-500">Gagal memuat konfigurasi</p>
              <button onclick="loadSettings()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center justify-center mx-auto">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw mr-1"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg> Coba Lagi
              </button>
          </div>
      `;
      });
  }

  // Add click handler to dashboard link
  document.addEventListener('DOMContentLoaded', function () {
    const dashboardLink = document.querySelector('nav a[href="#"]');
    if (dashboardLink) {
      dashboardLink.onclick = function (e) {
        e.preventDefault();
        showDashboard();
      };
    }
  });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>