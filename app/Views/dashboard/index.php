<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

  <!-- Message Toast Container -->
  <div id="toast-container" class="fixed top-4 right-4 z-[100] flex flex-col gap-2 w-full max-w-xs sm:max-w-sm pointer-events-none"></div>

  <!-- Dashboard Content -->
  <div id="dashboard-content" class="space-y-8 animate-fade-in-up">
    <!-- Flash Messages -->
    <?php if ($flash): ?>
      <div id="flash-message"
        class="mb-6 p-4 rounded-2xl shadow-sm border <?= $flash['type'] == 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-blue-50 border-blue-200 text-blue-700' ?> flex items-center gap-3">
        <?php if ($flash['type'] == 'success'): ?>
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <?php else: ?>
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
        <?php endif; ?>
        <div class="font-medium"><?= htmlspecialchars($flash['message']) ?></div>
      </div>
      <script>
        setTimeout(function () {
          $('#flash-message').fadeOut('slow');
        }, 3000);
      </script>
    <?php endif; ?>

    <!-- Welcome & Search Section -->
    <div class="relative bg-white rounded-3xl shadow-xl shadow-blue-900/5 overflow-hidden border border-slate-100 p-8 sm:p-10">
       <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full blur-3xl opacity-60"></div>
       <div class="relative z-10 text-center max-w-3xl mx-auto">
          <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Manage Your Network</h1>
          <p class="text-slate-500 text-lg mb-8">Search and manage PPP users, monitor traffic, and control access instantly.</p>

          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 group-focus-within:text-blue-500 transition-colors"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
            <input type="text" id="search-input"
              class="block w-full pl-14 pr-12 py-5 bg-slate-50 border-2 border-transparent text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-2xl text-lg shadow-inner transition-all duration-300"
              placeholder="Search by name, IP, or MAC address..." autocomplete="off">

             <!-- Loading indicator -->
            <div class="absolute right-5 top-1/2 transform -translate-y-1/2">
              <svg id="search-loading" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-loader-2 animate-spin text-blue-600 hidden">
                <path d="M21 12a9 9 0 1 1-6.219-8.56" />
              </svg>
            </div>
          </div>
          <p class="text-sm text-slate-400 mt-4 flex items-center justify-center gap-1.5">
             <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
             Type at least 2 characters to search
          </p>
       </div>
    </div>

    <!-- System Resource Section -->
    <div id="system-resource-section" class="grid grid-cols-1 md:grid-cols-3 gap-6 transition-all duration-500 ease-in-out">
        <!-- System Info Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
           <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-3">
                 <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="11" width="20" height="8" rx="2" ry="2"/><path d="M5 11V5a2 2 0 0 1 2-2h14"/><path d="M2 15h20"/></svg>
                 </div>
                 <h3 class="font-bold text-slate-700">System Info</h3>
              </div>
              <span id="sys-uptime" class="px-2.5 py-1 rounded-lg bg-green-50 text-green-700 text-xs font-bold border border-green-100">Loading...</span>
           </div>

           <div class="space-y-4">
              <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                 <span class="text-sm text-slate-500 font-medium">Board Name</span>
                 <span class="text-sm font-bold text-slate-800" id="sys-board">-</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                 <span class="text-sm text-slate-500 font-medium">RouterOS</span>
                 <span class="text-sm font-bold text-slate-800" id="sys-version">-</span>
              </div>
           </div>
        </div>

        <!-- CPU Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
           <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-3">
                 <div class="p-2.5 bg-purple-50 text-purple-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
                 </div>
                 <h3 class="font-bold text-slate-700">CPU Load</h3>
              </div>
              <span id="cpu-load-text" class="px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 text-xs font-bold border border-purple-100">0%</span>
           </div>

           <div class="space-y-4">
              <div class="relative pt-2">
                 <div class="overflow-hidden h-3 text-xs flex rounded-full bg-slate-100">
                    <div id="cpu-load-bar" style="width:0%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-purple-500 to-indigo-500 transition-all duration-700 ease-out"></div>
                 </div>
              </div>
              <div class="grid grid-cols-2 gap-3">
                 <div class="p-3 bg-slate-50 rounded-xl text-center">
                    <p class="text-xs text-slate-400 mb-1">Frequency</p>
                    <p class="text-sm font-bold text-slate-700" id="cpu-freq">-</p>
                 </div>
                 <div class="p-3 bg-slate-50 rounded-xl text-center">
                    <p class="text-xs text-slate-400 mb-1">Cores</p>
                    <p class="text-sm font-bold text-slate-700" id="cpu-count">-</p>
                 </div>
              </div>
           </div>
        </div>

        <!-- Storage/RAM Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
           <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-3">
                 <div class="p-2.5 bg-orange-50 text-orange-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><line x1="6" y1="16" x2="6.01" y2="16"/><line x1="10" y1="16" x2="10.01" y2="16"/></svg>
                 </div>
                 <h3 class="font-bold text-slate-700">Storage & RAM</h3>
              </div>
           </div>

           <div class="space-y-4">
              <!-- RAM -->
              <div>
                 <div class="flex justify-between text-xs mb-1.5 font-medium">
                    <span class="text-slate-500">RAM Usage</span>
                    <span class="text-orange-600" id="mem-text">0/0 MB</span>
                 </div>
                 <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div id="mem-bar" class="bg-gradient-to-r from-orange-400 to-red-400 h-2.5 rounded-full transition-all duration-700" style="width: 0%"></div>
                 </div>
              </div>
              <!-- HDD -->
              <div>
                 <div class="flex justify-between text-xs mb-1.5 font-medium">
                    <span class="text-slate-500">Disk Usage</span>
                    <span class="text-blue-600" id="hdd-text">0/0 MB</span>
                 </div>
                 <div class="w-full bg-slate-100 rounded-full h-2.5">
                    <div id="hdd-bar" class="bg-gradient-to-r from-blue-400 to-cyan-400 h-2.5 rounded-full transition-all duration-700" style="width: 0%"></div>
                 </div>
              </div>
           </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="results-section" class="hidden animate-fade-in-up">
      <div class="flex items-center justify-between mb-6">
         <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
            Search Results
            <span id="results-count" class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">0</span>
         </h2>
      </div>

      <!-- Loading Skeleton -->
      <div id="loading-skeleton" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php for ($i = 0; $i < 6; $i++): ?>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 h-40 animate-pulse">
               <div class="flex items-center space-x-4 mb-4">
                  <div class="rounded-full bg-slate-200 h-12 w-12"></div>
                  <div class="flex-1 space-y-2">
                     <div class="h-4 bg-slate-200 rounded w-3/4"></div>
                     <div class="h-3 bg-slate-200 rounded w-1/2"></div>
                  </div>
               </div>
               <div class="space-y-2">
                  <div class="h-3 bg-slate-200 rounded"></div>
                  <div class="h-3 bg-slate-200 rounded w-5/6"></div>
               </div>
            </div>
        <?php endfor; ?>
      </div>

      <!-- Users Grid -->
      <div id="users-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
          <!-- Populated by JS -->
      </div>
    </div>
  </div>

  <!-- Modals (Modernized) -->

  <!-- Disconnect Modal -->
  <div id="disconnect-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

      <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">Confirm Disconnection</h3>
              <div class="mt-2">
                <p class="text-sm text-slate-500">Are you sure you want to disconnect <strong id="disconnect-username" class="text-slate-800"></strong>? They will need to reconnect to access the internet.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
          <button type="button" id="confirm-disconnect-btn" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm transition-colors">
             <svg id="disconnect-loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
             Disconnect
          </button>
          <button type="button" id="cancel-disconnect-btn" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Ping Modal (Improved) -->
  <div id="ping-modal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
     <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
     <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh] relative z-10">
           <!-- Header -->
           <div class="bg-slate-900 p-5 flex items-center justify-between shrink-0">
              <div class="flex items-center gap-3">
                 <div class="p-2 bg-slate-800 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-400"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                 </div>
                 <div>
                    <h3 class="text-white font-bold text-lg">Network Diagnostic</h3>
                    <p class="text-slate-400 text-xs font-mono">Target: <span id="ping-target-address" class="text-white"></span></p>
                 </div>
              </div>
              <button id="close-ping-modal" class="p-2 text-slate-400 hover:text-white transition-colors rounded-full hover:bg-slate-800">
                 <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
              </button>
           </div>

           <!-- Controls -->
           <div class="bg-slate-50 border-b border-slate-200 p-4 flex gap-4 shrink-0">
               <button id="start-ping-btn" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 px-4 rounded-xl font-semibold shadow-sm shadow-green-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="6 3 20 12 6 21 6 3"/></svg>
                  Start Ping
               </button>
               <button id="stop-ping-btn" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2.5 px-4 rounded-xl font-semibold shadow-sm shadow-red-200 transition-all active:scale-95 hidden flex items-center justify-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/></svg>
                  Stop
               </button>

               <div class="flex items-center gap-2">
                   <select id="ping-count" class="bg-white border border-slate-300 text-slate-700 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-sm">
                       <option value="4">4 Packets</option>
                       <option value="10">10 Packets</option>
                       <option value="20">20 Packets</option>
                   </select>
               </div>
           </div>

           <!-- Terminal Output -->
           <div id="ping-results" class="flex-1 bg-slate-950 p-6 overflow-y-auto font-mono text-sm text-slate-300 leading-relaxed scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
               <div class="text-center py-10 text-slate-600">
                   <p>Ready to ping.</p>
               </div>
           </div>

           <!-- Stats Footer -->
           <div id="ping-summary" class="hidden bg-white border-t border-slate-200 p-4 grid grid-cols-4 gap-4 text-center">
               <div>
                   <p class="text-xs text-slate-500 uppercase font-bold">Sent</p>
                   <p id="ping-sent" class="text-lg font-bold text-blue-600">0</p>
               </div>
               <div>
                   <p class="text-xs text-slate-500 uppercase font-bold">Received</p>
                   <p id="ping-received" class="text-lg font-bold text-green-600">0</p>
               </div>
               <div>
                   <p class="text-xs text-slate-500 uppercase font-bold">Loss</p>
                   <p id="ping-loss-percent" class="text-lg font-bold text-slate-700">0%</p>
               </div>
               <div>
                   <p class="text-xs text-slate-500 uppercase font-bold">Avg RTT</p>
                   <p id="ping-avg-rtt" class="text-lg font-bold text-purple-600">0ms</p>
               </div>
           </div>
        </div>
     </div>
  </div>

</div>

<!-- Styles for animations -->
<style>
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
  }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(document).ready(function () {
    // === Core Variables ===
    let searchTimeout;
    let refreshInterval;
    let resourceInterval;
    let currentRequest;

    // UI References
    const $searchInput = $('#search-input');
    const $searchLoading = $('#search-loading');
    const $resultsSection = $('#results-section');
    const $resultsCount = $('#results-count');
    const $usersGrid = $('#users-grid');
    const $loadingSkeleton = $('#loading-skeleton');
    const $resourceSection = $('#system-resource-section');

    // === Render User Card Function (The core visual component) ===
    function renderUserCard(user) {
      const name = user.name || 'Unknown';
      const initials = name.substring(0, 2).toUpperCase();
      const isActive = user.status === 'active';
      const profile = user.profile || 'default';

      // Dynamic Colors based on name hash (simple version)
      const colors = ['bg-blue-500', 'bg-indigo-500', 'bg-purple-500', 'bg-pink-500', 'bg-teal-500'];
      const colorIndex = name.length % colors.length;
      const avatarColor = colors[colorIndex];

      // Status Styles
      const statusBadge = isActive
        ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
             <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span> Active
           </span>`
        : `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
             <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-1.5"></span> Offline
           </span>`;

      // Traffic Data (Active only)
      let trafficSection = '';
      if (isActive) {
        const rx = formatBytes(parseInt(user['rx-rate'] || 0));
        const tx = formatBytes(parseInt(user['tx-rate'] || 0));
        const totalDown = formatBytes(parseInt(user['bytes-down'] || 0));
        const totalUp = formatBytes(parseInt(user['bytes-up'] || 0));
        
        trafficSection = `
          <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2">
             <div class="bg-slate-50 rounded-lg p-2 text-center">
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-0.5">Download</p>
                <p class="text-sm font-bold text-blue-600 traffic-rx" data-username="${name}">${rx}</p>
                <p class="text-[10px] text-slate-500">${totalDown}</p>
             </div>
             <div class="bg-slate-50 rounded-lg p-2 text-center">
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-0.5">Upload</p>
                <p class="text-sm font-bold text-purple-600 traffic-tx" data-username="${name}">${tx}</p>
                <p class="text-[10px] text-slate-500">${totalUp}</p>
             </div>
          </div>
        `;
      }

      // Action Buttons
      const disconnectBtn = isActive
        ? `<button onclick="openDisconnectModal('${name}')" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Disconnect">
             <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
           </button>`
        : '';

      const ipAddress = user.address || user['caller-id'] || '';
      const pingBtn = ipAddress
        ? `<button onclick="openPingModal('${ipAddress}')" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Ping">
             <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
           </button>`
        : '';

      return `
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
           <div class="flex items-start justify-between">
              <div class="flex items-center gap-4">
                 <div class="w-12 h-12 ${avatarColor} rounded-xl shadow-lg shadow-blue-500/20 flex items-center justify-center text-white font-bold text-lg">
                    ${initials}
                 </div>
                 <div>
                    <h4 class="font-bold text-slate-800 text-lg leading-tight group-hover:text-blue-600 transition-colors">${name}</h4>
                    <p class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                       <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/><path d="m15 9 3 3-3 3"/></svg>
                       ${profile}
                    </p>
                 </div>
              </div>

              <!-- Menu/Status -->
              <div class="flex flex-col items-end gap-2">
                 ${statusBadge}
              </div>
           </div>

           <div class="mt-4 space-y-2">
              <div class="flex items-center justify-between text-sm">
                 <span class="text-slate-400 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    IP Address
                 </span>
                 <span class="font-mono text-slate-700 font-medium">${ipAddress || 'N/A'}</span>
              </div>
              <div class="flex items-center justify-between text-sm">
                 <span class="text-slate-400 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Uptime
                 </span>
                 <span class="text-slate-700 font-medium">${user.uptime || '-'}</span>
              </div>
           </div>

           ${trafficSection}

           <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              ${pingBtn}
              ${disconnectBtn}
           </div>
        </div>
      `;
    }

    // === Helper Functions ===
    function formatBytes(bytes, decimals = 2) {
      if (!+bytes) return '0 B';
      const k = 1024;
      const dm = decimals < 0 ? 0 : decimals;
      const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
    }

    // === Core Logic ===

    // 1. Search Logic
    $searchInput.on('input', function() {
       const term = $(this).val().trim();
       clearTimeout(searchTimeout);

       if (term.length === 0) {
          $resultsSection.addClass('hidden');
          $resourceSection.removeClass('hidden opacity-0').addClass('opacity-100');
          startResourceRefresh();
          return;
       }

       // Hide resource section for focus
       $resourceSection.removeClass('opacity-100').addClass('opacity-0 hidden');
       stopResourceRefresh();

       if (term.length >= 2) {
          $searchLoading.removeClass('hidden');
          searchTimeout = setTimeout(() => performSearch(term), 500);
       }
    });

    function performSearch(term) {
       if (currentRequest) currentRequest.abort();

       $loadingSkeleton.removeClass('hidden');
       $usersGrid.addClass('hidden');
       $resultsSection.removeClass('hidden');

       currentRequest = $.ajax({
          url: '/api/ppp/search',
          type: 'POST',
          data: { search_term: term, search_type: 'all' },
          dataType: 'json'
       }).done(function(res) {
          $loadingSkeleton.addClass('hidden');
          $searchLoading.addClass('hidden');
          $usersGrid.removeClass('hidden').empty();

          if (res.success && res.data) {
             const users = [...(res.data.active || []), ...(res.data.non_active || [])];
             $resultsCount.text(users.length);

             if (users.length === 0) {
                $usersGrid.html(`
                   <div class="col-span-full text-center py-12 text-slate-400">
                      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="m14 8-6 6"/><path d="m8 8 6 6"/></svg>
                      <p class="text-lg">No users found matching "${term}"</p>
                   </div>
                `);
             } else {
                users.forEach(u => $usersGrid.append(renderUserCard(u)));
             }
          }
       }).fail(function() {
          $loadingSkeleton.addClass('hidden');
          $searchLoading.addClass('hidden');
       });
    }

    // 2. Resource Monitoring
    function fetchResources() {
       $.post('/api/mikrotik/resource/info', function(res) {
          if (res.success && res.data) {
             const d = res.data;
             $('#sys-board').text(d['board-name']);
             $('#sys-version').text(d['version']);
             $('#sys-uptime').text(d['uptime']);

             // CPU
             const cpu = parseInt(d['cpu-load']);
             $('#cpu-load-text').text(cpu + '%');
             $('#cpu-load-bar').css('width', cpu + '%');
             $('#cpu-freq').text(d['cpu-frequency']);
             $('#cpu-count').text(d['cpu-count']);

             // RAM/HDD Bars (Simplified logic for visual update)
             const totalMem = parseFloat(d['total-memory']) || 1;
             const freeMem = parseFloat(d['free-memory']) || 0;
             const usedMem = totalMem - freeMem;
             $('#mem-bar').css('width', (usedMem/totalMem * 100) + '%');
             $('#mem-text').text(formatBytes(usedMem));

             const totalHdd = parseFloat(d['total-hdd-space']) || 1;
             const freeHdd = parseFloat(d['free-hdd-space']) || 0;
             const usedHdd = totalHdd - freeHdd;
             $('#hdd-bar').css('width', (usedHdd/totalHdd * 100) + '%');
             $('#hdd-text').text(formatBytes(usedHdd));
          }
       }, 'json');
    }

    function startResourceRefresh() {
       fetchResources();
       resourceInterval = setInterval(fetchResources, 5000);
    }

    function stopResourceRefresh() {
       clearInterval(resourceInterval);
    }

    // Init Resources
    startResourceRefresh();

    // === Modal Logic (Exposed Globally) ===
    window.openDisconnectModal = function(username) {
       $('#disconnect-username').text(username);
       $('#disconnect-modal').removeClass('hidden');

       $('#confirm-disconnect-btn').off('click').on('click', function() {
          const $btn = $(this);
          const $load = $('#disconnect-loading');

          $btn.prop('disabled', true);
          $load.removeClass('hidden');

          $.post('/api/ppp/disconnect', { username: username }, function(res) {
             if (res.success) {
                // Remove card or refresh
                $(`.traffic-rx[data-username="${username}"]`).closest('.bg-white').fadeOut();
                $('#disconnect-modal').addClass('hidden');

                // Show floating toast
                const toast = $(`<div class="bg-green-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 animate-fade-in-up"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> ${username} disconnected</div>`);
                $('#toast-container').append(toast);
                setTimeout(() => toast.remove(), 4000);
             }
          }, 'json').always(() => {
             $btn.prop('disabled', false);
             $load.addClass('hidden');
          });
       });
    };

    $('#cancel-disconnect-btn').click(() => $('#disconnect-modal').addClass('hidden'));

    // Ping Modal Logic
    let pingInterval;
    window.openPingModal = function(ip) {
       $('#ping-target-address').text(ip);
       $('#ping-modal').removeClass('hidden');
       $('#ping-results').empty().html('<div class="text-center py-10 text-slate-600"><p>Ready to ping ' + ip + '</p></div>');
       $('#ping-summary').addClass('hidden');

       $('#start-ping-btn').off('click').on('click', function() {
           const count = $('#ping-count').val();
           $(this).addClass('hidden');
           $('#stop-ping-btn').removeClass('hidden');
           $('#ping-results').empty();

           let sent = 0, received = 0;
           let pings = [];

           function doPing() {
               sent++;
               $.post('/api/mikrotik/ping', { address: ip, count: 1 }, function(res) {
                   if (res.success && res.data.status === 'success') {
                       received++;
                       const time = res.data.time || '0ms';
                       $('#ping-results').append(`<div class="mb-1 text-green-400">Reply from ${ip}: seq=${sent} time=${time}</div>`);
                   } else {
                       $('#ping-results').append(`<div class="mb-1 text-red-400">Request timeout for seq=${sent}</div>`);
                   }

                   // Scroll to bottom
                   const term = document.getElementById('ping-results');
                   term.scrollTop = term.scrollHeight;

                   if (sent >= count) stopPing();
               }, 'json').fail(function() {
                   $('#ping-results').append(`<div class="mb-1 text-red-500">Network Error</div>`);
                   if (sent >= count) stopPing();
               });
           }

           doPing(); // First one
           pingInterval = setInterval(() => {
               if (sent < count) doPing();
               else stopPing();
           }, 1000);

           function stopPing() {
               clearInterval(pingInterval);
               $('#start-ping-btn').removeClass('hidden');
               $('#stop-ping-btn').addClass('hidden');

               // Show Summary
               $('#ping-summary').removeClass('hidden');
               $('#ping-sent').text(sent);
               $('#ping-received').text(received);
               $('#ping-loss-percent').text(Math.round(((sent-received)/sent)*100) + '%');
           }

           $('#stop-ping-btn').click(stopPing);
       });
    };

    $('#close-ping-modal').click(() => {
        clearInterval(pingInterval);
        $('#ping-modal').addClass('hidden');
    });
  });
</script>

<?php include APP_PATH . '/Views/layouts/footer.php'; ?>
