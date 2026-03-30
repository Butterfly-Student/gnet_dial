<?php
use Services\AuthService;

// Get current page for active menu highlighting
$currentPath = currentPath();

function isActive($path)
{
  $current = currentPath();

  if (is_array($path)) {
    return in_array($current, $path, true);
  }

  if ($path === '/' && $current === '/dashboard') {
    return true;
  }

  return $current === $path;
}

function getActiveClass($path, $isMobile = false)
{
  $isActive = isActive($path);

  if ($isMobile) {
    return $isActive ?
      'flex flex-col items-center justify-center w-full h-full text-blue-600 relative after:content-[""] after:absolute after:top-0 after:left-1/2 after:-translate-x-1/2 after:w-8 after:h-1 after:bg-blue-600 after:rounded-b-full transition-all duration-300' :
      'flex flex-col items-center justify-center w-full h-full text-gray-400 hover:text-gray-600 transition-colors relative';
  } else {
    // Desktop: Modern Pill Shape
    return $isActive ?
      'flex items-center px-4 py-3 text-blue-700 bg-blue-50 rounded-xl gap-3 font-semibold transition-all duration-300 shadow-sm ring-1 ring-blue-100' :
      'flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all duration-200 gap-3 font-medium';
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= config('app.name') ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          },
          colors: {
            brand: {
              50: '#eff6ff',
              100: '#dbeafe',
              200: '#bfdbfe',
              300: '#93c5fd',
              400: '#60a5fa',
              500: '#3b82f6',
              600: '#2563eb',
              700: '#1d4ed8',
              800: '#1e40af',
              900: '#1e3a8a',
            }
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="<?= asset('g.ico') ?>" type="image/x-icon" />
  <style>
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: transparent;
    }
    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 3px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    .main-content {
      padding-top: 5rem;
      padding-bottom: 6rem;
    }

    @media (min-width: 1024px) {
      .main-content {
        padding-top: 5rem;
        padding-bottom: 2rem;
        margin-left: 17rem; /* Wider sidebar */
        min-height: 100vh;
      }

      .desktop-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 17rem;
        overflow-y: auto;
        z-index: 50;
      }

      .desktop-header {
        position: fixed;
        top: 0;
        right: 0;
        left: 17rem;
        height: 4.5rem; /* Taller header */
        z-index: 40;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      }
    }
  </style>
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-900 selection:bg-blue-100 selection:text-blue-900">
  <div class="min-h-screen">
    <!-- Desktop Sidebar -->
    <aside class="hidden lg:flex flex-col desktop-sidebar bg-white border-r border-slate-200/60">
      <!-- Logo Area -->
      <div class="flex items-center justify-center h-20 border-b border-slate-100">
        <a href="/" class="flex items-center gap-3 group">
          <div class="relative w-10 h-10 flex items-center justify-center bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-xl shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
              class="text-white">
              <rect x="2" y="2" width="20" height="8" rx="2" ry="2" />
              <rect x="2" y="14" width="20" height="8" rx="2" ry="2" />
              <line x1="6" y1="6" x2="6.01" y2="6" />
              <line x1="6" y1="18" x2="6.01" y2="18" />
            </svg>
            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
          </div>
          <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight leading-none">Ghaib<span class="text-blue-600">Net</span></h1>
            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Manager</span>
          </div>
        </a>
      </div>

      <!-- Navigation -->
      <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
        <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Main Menu</p>

        <a href="/" class="<?= getActiveClass('/', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
          <span>Dashboard</span>
        </a>

        <a href="/customers" class="<?= getActiveClass('/customers', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>Customers</span>
        </a>

        <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mt-6 mb-2">PPP Manager</p>

        <a href="/ppp/active" class="<?= getActiveClass('/ppp/active', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wifi"><path d="M12 20h.01"/><path d="M2 8.82a15 15 0 0 1 20 0"/><path d="M5 12.859a10 10 0 0 1 14 0"/><path d="M8.5 16.429a5 5 0 0 1 7 0"/></svg>
          <span>PPP Active</span>
        </a>

        <a href="/ppp/non-active" class="<?= getActiveClass('/ppp/non-active', false) ?>">
           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wifi-off"><path d="M12 20h.01"/><path d="M8.5 16.429a5 5 0 0 1 7 0"/><path d="M5 12.859a10 10 0 0 1 5.17-2.69"/><path d="M19 12.859a10 10 0 0 0-2.007-1.523"/><path d="M2 8.82a15 15 0 0 1 4.177-2.643"/><path d="M22 8.82a15 15 0 0 0-11.288-3.764"/><path d="m2 2 20 20"/></svg>
          <span>PPP Non-Active</span>
        </a>

        <a href="/ppp/profiles" class="<?= getActiveClass('/ppp/profiles', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layers"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
          <span>PPP Profiles</span>
        </a>

        <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mt-6 mb-2">Monitoring</p>

        <a href="/mikrotik/logs" class="<?= getActiveClass('/mikrotik/logs', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scroll-text"><path d="M8 21h12a2 2 0 0 0 2-2v-2H10v2a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v3h4"/><path d="M19 17V5a2 2 0 0 0-2-2H4"/></svg>
          <span>System Logs</span>
        </a>

        <a href="/mikrotik/interface" class="<?= getActiveClass('/mikrotik/interface', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          <span>Interface Monitor</span>
        </a>

        <a href="/settings" class="<?= getActiveClass('/settings', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
          <span>Settings</span>
        </a>
      </div>

      <!-- Footer User Profile -->
      <div class="p-4 border-t border-slate-100">
        <a href="/logout" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 text-slate-600 hover:text-red-600 transition-colors group">
          <div class="p-2 rounded-lg bg-slate-100 group-hover:bg-red-100 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
          </div>
          <div class="flex-1">
            <p class="text-sm font-semibold">Sign Out</p>
            <p class="text-xs text-slate-400">End your session</p>
          </div>
        </a>
      </div>
    </aside>

    <!-- Desktop Header -->
    <header class="hidden lg:flex desktop-header px-8 justify-between">
      <!-- Search/Breadcrumbs (Placeholder) -->
      <div class="flex items-center text-sm text-slate-500">
        <span class="font-medium text-slate-900"><?= date('l, d F Y') ?></span>
      </div>

      <!-- Right Side Actions -->
      <div class="flex items-center gap-4">
        <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors relative">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
          <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
        </button>

        <div class="h-8 w-px bg-slate-200"></div>

        <div class="flex items-center gap-3 pl-2">
          <div class="text-right hidden xl:block">
            <p class="text-sm font-bold text-slate-800"><?= e(AuthService::user()['fullname'] ?? 'Administrator') ?></p>
            <p class="text-xs text-slate-500">Super Admin</p>
          </div>
          <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 p-[2px] shadow-md cursor-pointer hover:shadow-lg transition-shadow">
            <div class="w-full h-full rounded-full bg-white p-0.5">
              <img src="https://ui-avatars.com/api/?name=<?= urlencode(AuthService::user()['fullname'] ?? 'Admin') ?>&background=random&color=fff&bold=true" alt="Avatar" class="w-full h-full rounded-full object-cover">
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Mobile Header -->
    <header
      class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white/80 backdrop-blur-lg border-b border-slate-200/60 z-50 flex items-center justify-between px-4 transition-all duration-300">
      <button id="mobile-menu-toggle" class="p-2 hover:bg-slate-100 rounded-xl transition-colors text-slate-600">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-menu">
          <line x1="4" x2="20" y1="12" y2="12" />
          <line x1="4" x2="20" y1="6" y2="6" />
          <line x1="4" x2="20" y1="18" y2="18" />
        </svg>
      </button>

      <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
              class="text-white">
              <rect x="2" y="2" width="20" height="8" rx="2" ry="2" />
              <rect x="2" y="14" width="20" height="8" rx="2" ry="2" />
              <line x1="6" y1="6" x2="6.01" y2="6" />
              <line x1="6" y1="18" x2="6.01" y2="18" />
            </svg>
        </div>
        <span class="font-bold text-slate-800 text-lg">Ghaib<span class="text-blue-600">Net</span></span>
      </div>

      <div class="w-10">
        <!-- Spacer or secondary action -->
         <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden ml-auto">
             <img src="https://ui-avatars.com/api/?name=<?= urlencode(AuthService::user()['fullname'] ?? 'Admin') ?>&background=random" alt="Avatar">
         </div>
      </div>
    </header>

    <!-- Mobile Sidebar Drawer -->
    <div id="mobile-sidebar" class="lg:hidden fixed inset-0 z-50 hidden">
      <div id="sidebar-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
      <div id="sidebar-panel"
        class="absolute left-0 top-0 bottom-0 w-80 bg-white shadow-2xl transform -translate-x-full transition-transform duration-300 flex flex-col">

        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100 bg-white">
           <div class="flex items-center gap-3">
             <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center">
                 <span class="text-white font-bold text-lg">G</span>
             </div>
             <span class="font-bold text-xl text-slate-800">Ghaib<span class="text-blue-600">Net</span></span>
           </div>
           <button id="close-sidebar-btn" class="p-2 hover:bg-slate-100 rounded-full text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-x">
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
          <!-- Mobile Links -->
          <a href="/" class="<?= getActiveClass('/', false) ?>">Dashboard</a>
          <a href="/customers" class="<?= getActiveClass('/customers', false) ?>">Customers</a>
          <div class="py-2"><hr class="border-slate-100"></div>
          <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Network</p>
          <a href="/ppp/active" class="<?= getActiveClass('/ppp/active', false) ?>">PPP Active</a>
          <a href="/ppp/non-active" class="<?= getActiveClass('/ppp/non-active', false) ?>">PPP Non-Active</a>
          <a href="/ppp/profiles" class="<?= getActiveClass('/ppp/profiles', false) ?>">PPP Profiles</a>
          <div class="py-2"><hr class="border-slate-100"></div>
           <p class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">System</p>
          <a href="/mikrotik/logs" class="<?= getActiveClass('/mikrotik/logs', false) ?>">Logs</a>
          <a href="/mikrotik/interface" class="<?= getActiveClass('/mikrotik/interface', false) ?>">Interfaces</a>
          <a href="/settings" class="<?= getActiveClass('/settings', false) ?>">Settings</a>
        </nav>

        <div class="p-4 border-t border-slate-100 bg-slate-50">
            <a href="/logout" class="flex items-center justify-center gap-2 w-full py-3 bg-white border border-slate-200 text-red-600 font-semibold rounded-xl hover:bg-red-50 transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                Logout
            </a>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <main class="main-content">
