    </main>

    <!-- Mobile Bottom Navigation (Glassmorphism) -->
    <nav class="lg:hidden fixed bottom-4 left-4 right-4 bg-white/90 backdrop-blur-xl border border-white/20 shadow-2xl rounded-2xl z-50 pb-safe">
      <div class="grid grid-cols-5 h-16 items-center justify-items-center">
        <a href="/" class="<?= getActiveClass('/', true) ?>">
          <div class="p-2 rounded-xl transition-all duration-300">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid w-6 h-6"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
          </div>
        </a>
        <a href="/ppp/active" class="<?= getActiveClass('/ppp/active', true) ?>">
          <div class="p-2 rounded-xl transition-all duration-300">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wifi w-6 h-6"><path d="M12 20h.01"/><path d="M2 8.82a15 15 0 0 1 20 0"/><path d="M5 12.859a10 10 0 0 1 14 0"/><path d="M8.5 16.429a5 5 0 0 1 7 0"/></svg>
          </div>
        </a>

        <!-- Center Action Button (Search/Add) -->
        <div class="relative -top-6">
           <a href="/customers" class="flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full shadow-lg shadow-blue-500/40 text-white transform hover:scale-105 transition-transform active:scale-95 border-4 border-slate-50">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
           </a>
        </div>

        <a href="/mikrotik/interface" class="<?= getActiveClass('/mikrotik/interface', true) ?>">
          <div class="p-2 rounded-xl transition-all duration-300">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity w-6 h-6"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          </div>
        </a>
        <a href="/settings" class="<?= getActiveClass('/settings', true) ?>">
          <div class="p-2 rounded-xl transition-all duration-300">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2 w-6 h-6"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
          </div>
        </a>
      </div>
    </nav>

    <script>
      $(document).ready(function() {
        // Mobile sidebar toggle logic
        const mobileSidebar = $('#mobile-sidebar');
        const sidebarPanel = $('#sidebar-panel');
        const backdrop = $('#sidebar-backdrop');
        const closeBtn = $('#close-sidebar-btn');
        const openBtn = $('#mobile-menu-toggle');

        function openSidebar() {
          mobileSidebar.removeClass('hidden');
          // Small delay to allow display:block to apply before transition
          setTimeout(() => {
            sidebarPanel.removeClass('-translate-x-full');
            // Check if backdrop exists/works as intended
            // backdrop.removeClass('opacity-0');
          }, 10);
        }

        function closeSidebar() {
          sidebarPanel.addClass('-translate-x-full');
          // backdrop.addClass('opacity-0');
          setTimeout(() => {
            mobileSidebar.addClass('hidden');
          }, 300);
        }

        openBtn.on('click', openSidebar);
        backdrop.on('click', closeSidebar);
        closeBtn.on('click', closeSidebar);

        // Close on link click
        $('#mobile-sidebar nav a').on('click', closeSidebar);
      });
    </script>
  </div>
</body>
</html>
