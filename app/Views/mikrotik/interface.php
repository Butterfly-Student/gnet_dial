<?php include APP_PATH . '/Views/layouts/header.php'; ?>

<div class="container mx-auto py-2.5 sm:p-6 lg:p-8">
  <div class="bg-white w-full rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-4 md:p-6 mb-3 sm:mb-6">
    <div class="flex md:items-center justify-between gap-3 sm:gap-4">
      <div>
        <h2 class="text-base sm:text-xl md:text-2xl font-bold text-gray-800 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity mr-1.5 sm:mr-3 text-blue-600 w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          Monitor Resources
        </h2>
        <p class="text-gray-500 text-xs sm:text-sm mt-0.5 sm:mt-1">Real-time Traffic Monitoring</p>
      </div>
      
      <!-- Interval Selector & Refresh -->
      <div class="flex items-center gap-2">
         <div class="relative">
            <select id="poll-interval" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="1000">1s</option>
                <option value="3000" selected>3s</option>
                <option value="5000">5s</option>
                <option value="10000">10s</option>
            </select>
         </div>
         <button id="refresh-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg transition-colors shadow-sm hover:shadow text-sm flex items-center gap-2">
             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw w-4 h-4"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
             <span class="hidden sm:inline">Refresh</span>
         </button>
      </div>
    </div>
  </div>

  <!-- Tabs Header -->
  <div class="mb-4 border-b border-gray-200 bg-white rounded-t-xl px-4 pt-4">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="monitor-tabs" role="tablist">
        <li class="mr-2" role="presentation">
            <button class="inline-block p-4 border-b-2 border-blue-600 rounded-t-lg active text-blue-600 hover:text-blue-600" id="interfaces-tab" data-tabs-target="#interfaces" type="button" role="tab" aria-controls="interfaces" aria-selected="true">
                Interfaces (<span id="interface-count">0</span>)
            </button>
        </li>
        <li class="mr-2" role="presentation">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 text-gray-500" id="queues-tab" data-tabs-target="#queues" type="button" role="tab" aria-controls="queues" aria-selected="false">
                Simple Queues (<span id="queue-count">0</span>)
            </button>
        </li>
    </ul>
  </div>

  <!-- Tabs Content -->
  <div id="tab-content">
      <!-- Interfaces Content -->
      <div class="hidden p-4 rounded-lg bg-gray-50" id="interfaces" role="tabpanel" aria-labelledby="interfaces-tab">
          <div id="interface-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div class="col-span-full text-center py-8 text-gray-500">Loading interfaces...</div>
          </div>
      </div>
      
      <!-- Queues Content -->
      <div class="hidden p-4 rounded-lg bg-gray-50" id="queues" role="tabpanel" aria-labelledby="queues-tab">
          <!-- Search Input -->
          <div class="mb-4">
              <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                      <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                      </svg>
                  </div>
                  <input type="text" id="queue-search" class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search queues by name...">
              </div>
          </div>
          
          <div id="queue-container">
              <div class="text-center py-8 text-gray-500">Loading queues...</div>
          </div>
      </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    let pollIntervalId = null;
    let isPolling = false;
    
    // Tab switching logic
    $('#monitor-tabs button').click(function() {
        const target = $(this).data('tabs-target');
        
        // Update active class
        $('#monitor-tabs button').removeClass('border-blue-600 text-blue-600 active').addClass('border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300');
        $(this).removeClass('border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300').addClass('border-blue-600 text-blue-600 active');
        
        // Show/Hide content
        $('#tab-content > div').addClass('hidden');
        $(target).removeClass('hidden');
    });
    
    // Initial fetch
    startPolling();
    
    $('#refresh-btn').click(function() {
        fetchData();
    });
    
    $('#poll-interval').change(function() {
        startPolling();
    });
    
    // Show first tab by default
    $('#interfaces').removeClass('hidden');

    function startPolling() {
        if (pollIntervalId) clearInterval(pollIntervalId);
        
        const interval = parseInt($('#poll-interval').val());
        fetchData(); // Run immediately
        pollIntervalId = setInterval(fetchData, interval);
    }
    
    function fetchData() {
        if (isPolling) return; // Prevent stacking
        isPolling = true;
        
        const btnIcon = $('#refresh-btn svg');
        btnIcon.addClass('animate-spin');
        
        $.ajax({
            url: '/api/mikrotik/interfaces',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                isPolling = false;
                btnIcon.removeClass('animate-spin');
                
                if (response.success) {
                    renderInterfaces(response.data.interfaces);
                    renderQueues(response.data.queues);
                }
            },
            error: function() {
                isPolling = false;
                btnIcon.removeClass('animate-spin');
            }
        });
    }
    
    function renderInterfaces(interfaces) {
        const container = $('#interface-grid');
        $('#interface-count').text(interfaces ? interfaces.length : 0);
        
        if (container.children().length <= 1 && container.text().includes('Loading')) {
            container.empty();
        }

        if (!interfaces || interfaces.length === 0) {
             if (container.children().length === 0)
                container.html('<div class="col-span-full text-center py-8 text-gray-500">No interfaces found</div>');
             return;
        }

        interfaces.forEach(iface => {
             const safeName = iface.name.replace(/[^a-zA-Z0-9]/g, '_');
             const cardId = `iface-card-${safeName}`;
             
             const isRunning = iface.running === "true";
             const rx = formatBytes(iface['rx-bits-per-second'] || 0, 2, 'bps');
             const tx = formatBytes(iface['tx-bits-per-second'] || 0, 2, 'bps');
             
             // Clean interface name
             const displayName = cleanName(iface.name);
             const escapedName = escapeHtml(displayName);
             const escapedType = escapeHtml(iface.type);
             
             const html = `
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden group">
                     <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                           <div class="p-2 rounded-lg ${isRunning ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-500'}">
                               <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-network"><rect x="16" y="16" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="9" y="2" width="6" height="6" rx="1"/><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"/><path d="M12 12V8"/></svg>
                           </div>
                           <div>
                               <h3 class="font-bold text-gray-800 text-sm">${escapedName}</h3>
                               <p class="text-xs text-gray-500 font-medium">${escapedType}</p>
                           </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold border ${isRunning ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-500 border-gray-200'}">
                            ${isRunning ? 'Running' : 'Inactive'}
                        </span>
                     </div>
                     
                     <div class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-50">
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                <span class="text-[10px] uppercase text-gray-400 font-bold">RX</span>
                            </div>
                            <p class="text-sm font-bold text-gray-800">${rx}</p>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 mb-1">
                                <div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div>
                                <span class="text-[10px] uppercase text-gray-400 font-bold">TX</span>
                            </div>
                            <p class="text-sm font-bold text-gray-800">${tx}</p>
                        </div>
                     </div>
                </div>
             `;
             
             if ($('#' + cardId).length) {
                 $('#' + cardId).replaceWith($(html).attr('id', cardId));
             } else {
                 container.append($(html).attr('id', cardId));
             }
        });
    }
    
    function renderQueues(queues) {
        const container = $('#queue-container');
        const searchTerm = $('#queue-search').val().toLowerCase();
        
        if (!queues || queues.length === 0) {
             container.html('<div class="text-center py-8 text-gray-500">No queues found</div>');
             $('#queue-count').text(0);
             return;
        }
        
        // Group queues by parent
        const grouped = {};
        const noParent = [];
        
        queues.forEach(queue => {
            const parent = queue.parent || '';
            if (parent && parent !== 'none') {
                if (!grouped[parent]) {
                    grouped[parent] = [];
                }
                grouped[parent].push(queue);
            } else {
                noParent.push(queue);
            }
        });
        
        // Filter based on search
        const filteredQueues = queues.filter(q => {
            const cleanedName = cleanName(q.name);
            return cleanedName.toLowerCase().includes(searchTerm);
        });
        
        $('#queue-count').text(filteredQueues.length);
        
        let html = '';
        let hasVisible = false;
        
        // Render queues without parent first
        if (noParent.length > 0) {
            const filteredNoParent = noParent.filter(q => {
                const cleanedName = cleanName(q.name);
                return cleanedName.toLowerCase().includes(searchTerm);
            });
            
            if (filteredNoParent.length > 0) {
                hasVisible = true;
                html += `
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layers"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                            No Parent (${filteredNoParent.length})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            ${filteredNoParent.map(q => renderQueueCard(q, true)).join('')}
                        </div>
                    </div>
                `;
            }
        }
        
        // Render queues grouped by parent
        Object.keys(grouped).sort().forEach(parent => {
            const parentQueues = grouped[parent];
            const filteredParentQueues = parentQueues.filter(q => {
                const cleanedName = cleanName(q.name);
                return cleanedName.toLowerCase().includes(searchTerm);
            });
            
            if (filteredParentQueues.length > 0) {
                hasVisible = true;
                const parentDisplay = cleanName(parent);
                html += `
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-folder-tree"><path d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z"/><path d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z"/><path d="M3 5a2 2 0 0 0 2 2h3"/><path d="M3 3v13a2 2 0 0 0 2 2h3"/></svg>
                            ${escapeHtml(parentDisplay)} (${filteredParentQueues.length})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            ${filteredParentQueues.map(q => renderQueueCard(q, true)).join('')}
                        </div>
                    </div>
                `;
            }
        });
        
        if (!hasVisible) {
            html = '<div class="text-center py-8 text-gray-500">No queues match your search</div>';
        }
        
        container.html(html);
    }
    
    function renderQueueCard(queue, matchesSearch) {
        const cleanedName = cleanName(queue.name);
        const safeName = cleanedName.replace(/[^a-zA-Z0-9]/g, '_');
        
        // Parse bytes (format: "upload/download")
        let bytesRaw = queue.bytes || "0/0";
        let bytesParts = bytesRaw.split('/');
        let bytesUp = formatBytes(parseInt(bytesParts[0]) || 0);
        let bytesDown = formatBytes(parseInt(bytesParts[1]) || 0);
        
        // Parse rate (format: "uploadbps/downloadbps")
        let rateRaw = queue.rate || "0bps/0bps";
        let rateParts = rateRaw.replace(/bps/g, '').split('/');
        let rateUp = formatBytes(parseInt(rateParts[0]) || 0, 2, 'bps');
        let rateDown = formatBytes(parseInt(rateParts[1]) || 0, 2, 'bps');
        
        // Clean and escape all text fields
        const escapedName = escapeHtml(cleanedName);
        const escapedTarget = escapeHtml(cleanName(queue.target || 'N/A'));
        const escapedMaxLimit = escapeHtml(queue['max-limit'] || 'Unlimited');
        const escapedParent = queue.parent && queue.parent !== 'none' ? escapeHtml(cleanName(queue.parent)) : '';
        
        return `
            <div id="queue-card-${safeName}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden group ${matchesSearch ? '' : 'hidden'}">
                 <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                       <div class="p-2 rounded-lg bg-purple-50 text-purple-600 shrink-0">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-left"><path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/></svg>
                       </div>
                       <div class="overflow-hidden flex-1">
                           <h3 class="font-bold text-gray-800 text-sm break-all" title="${escapedName}">${escapedName}</h3>
                           <p class="text-xs text-gray-500 font-medium truncate">${escapedTarget}</p>
                       </div>
                    </div>
                    <div class="text-right shrink-0 ml-2">
                         ${escapedParent ? `<p class="text-[12px] text-blue-600 font-semibold mt-0.5 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M7 16 17 6"/></svg>${escapedParent}</p>` : ''}
                    </div>
                 </div>
                 
                 <div class="space-y-2 pt-2 border-t border-gray-50">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <div class="flex items-center gap-1 mb-0.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                <p class="text-[10px] uppercase text-gray-400 font-bold">Upload</p>
                            </div>
                            <p class="text-xs font-mono text-gray-700 bg-blue-50 px-2 py-1 rounded">${rateUp}</p>
                        </div>
                        <div>
                            <div class="flex items-center gap-1 mb-0.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
                                <p class="text-[10px] uppercase text-gray-400 font-bold">Download</p>
                            </div>
                            <p class="text-xs font-mono text-gray-700 bg-green-50 px-2 py-1 rounded">${rateDown}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold mb-0.5">Total Up</p>
                            <p class="text-xs font-mono text-gray-600">${bytesUp}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase text-gray-400 font-bold mb-0.5">Total Down</p>
                            <p class="text-xs font-mono text-gray-600">${bytesDown}</p>
                        </div>
                    </div>
                 </div>
            </div>
        `;
    }

    /**
     * Clean name by removing < and > characters only
     * Example: <pppoe-GLO_Affan> becomes pppoe-GLO_Affan
     * Example: hotspot-user becomes hotspot-user (no change)
     */
    function cleanName(name) {
        if (!name) return '';
        return name.replace(/[<>]/g, '');
    }

    /**
     * Escape HTML characters to prevent XSS and rendering issues
     */
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    /**
     * Format bytes to human readable format
     */
    function formatBytes(bytes, decimals = 2, suffix = 'B') {
        if (bytes === 0) return '0 ' + suffix;
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = [suffix, 'K'+suffix, 'M'+suffix, 'G'+suffix, 'T'+suffix];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
    
    // Search listener
    $('#queue-search').on('keyup', function() {
        // Re-render with search filter
        fetchData();
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>