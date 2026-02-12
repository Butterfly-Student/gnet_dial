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
      'flex flex-col items-center justify-center w-full h-full text-blue-600 relative' :
      'flex flex-col items-center justify-center w-full h-full text-gray-400 hover:text-gray-600 transition-colors relative';
  } else {
    return $isActive ?
      'flex items-center px-6 py-3.5 text-blue-600 bg-blue-50/80 border-r-[3px] border-blue-600 gap-3 font-medium transition-all duration-200' :
      'flex items-center px-6 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all duration-200 gap-3 font-medium';
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
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="<?= asset('g.ico') ?>" type="image/x-icon" />
  <style>
    .main-content {
      padding-top: 4rem;
      padding-bottom: 5rem;
    }

    @media (min-width: 1024px) {
      .main-content {
        padding-top: 4rem;
        padding-bottom: 0;
        margin-left: 16rem;
        min-height: 100vh;
      }

      .desktop-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 16rem;
        overflow-y: auto;
        z-index: 50;
      }

      .desktop-header {
        position: fixed;
        top: 0;
        right: 0;
        left: 16rem;
        height: 4rem;
        z-index: 40;
        display: flex;
        align-items: center;
        background: white;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      }
    }
  </style>
</head>

<body class="min-h-screen bg-gray-50 font-sans text-gray-900">
  <div class="min-h-screen">
    <!-- Desktop Sidebar -->
    <div class="hidden lg:block desktop-sidebar bg-white shadow-lg">
      <div class="flex items-center justify-center h-16 bg-gradient-to-r from-blue-600 to-purple-600 shadow-md">
        <h1 class="text-white text-xl font-bold flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-network">
            <rect x="16" y="16" width="6" height="6" rx="1" />
            <rect x="2" y="16" width="6" height="6" rx="1" />
            <rect x="9" y="2" width="6" height="6" rx="1" />
            <path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
            <path d="M12 12V8" />
          </svg>
          Ghaib Network
        </h1>
      </div>

      <nav class="mt-8">
        <a href="/" class="<?= getActiveClass('/', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-house-icon lucide-house">
            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
            <path
              d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
          </svg>
          <span>Dashboard</span>
        </a>
        <a href="/ppp/active" class="<?= getActiveClass('/ppp/active', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-wifi-icon lucide-wifi">
            <path d="M12 20h.01" />
            <path d="M2 8.82a15 15 0 0 1 20 0" />
            <path d="M5 12.859a10 10 0 0 1 14 0" />
            <path d="M8.5 16.429a5 5 0 0 1 7 0" />
          </svg>
          <span>PPP Aktif</span>
        </a>
        <a href="/ppp/non-active" class="<?= getActiveClass('/ppp/non-active', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-wifi-off-icon lucide-wifi-off">
            <path d="M12 20h.01" />
            <path d="M8.5 16.429a5 5 0 0 1 7 0" />
            <path d="M5 12.859a10 10 0 0 1 5.17-2.69" />
            <path d="M19 12.859a10 10 0 0 0-2.007-1.523" />
            <path d="M2 8.82a15 15 0 0 1 4.177-2.643" />
            <path d="M22 8.82a15 15 0 0 0-11.288-3.764" />
            <path d="m2 2 20 20" />
          </svg>
          <span>PPP Non-Aktif</span>
        </a>
        <a href="/ppp/secrets" class="<?= getActiveClass('/ppp/secrets', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-key-icon lucide-key">
            <path d="M2 18a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v2H2z" />
            <path d="M4 14h16" />
            <path d="m15 8-1-1" />
            <path d="m19 12-1-1" />
            <path d="m15 12-1-1" />
            <path d="m19 8-1-1" />
            <path d="m8 8-1-1" />
            <path d="m12 12-1-1" />
            <path d="m8 12-1-1" />
            <path d="m12 8-1-1" />
          </svg>
          <span>PPP Secrets</span>
        </a>
        <a href="/ppp/profiles" class="<?= getActiveClass('/ppp/profiles', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-layers-icon lucide-layers">
            <path
              d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z" />
            <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65" />
            <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65" />
          </svg>
          <span>PPP Profiles</span>
        </a>
        <a href="/mikrotik/logs" class="<?= getActiveClass('/mikrotik/logs', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-history-icon lucide-history">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
            <path d="M3 3v5h5" />
            <path d="M12 7v5l4 2" />
          </svg>
          <span>Log MikroTik</span>
        </a>
        <a href="/mikrotik/interface" class="<?= getActiveClass('/mikrotik/interface', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-ethernet-port-icon lucide-ethernet-port">
            <path d="m15 20 3-3h2a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h2l3 3z" />
            <path d="M6 8v1" />
            <path d="M10 8v1" />
            <path d="M14 8v1" />
            <path d="M18 8v1" />
          </svg>
          <span>Monitor Interface</span>
        </a>
        <a href="/settings" class="<?= getActiveClass('/settings', false) ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-settings-icon lucide-settings">
            <path
              d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915" />
            <circle cx="12" cy="12" r="3" />
          </svg>
          <span>Pengaturan</span>
        </a>
        <div class="mt-4 pt-4 border-t border-gray-100">
          <a href="/logout"
            class="flex items-center px-6 py-3 text-red-600 hover:bg-red-50 transition-colors gap-2 group">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-log-out group-hover:translate-x-1 transition-transform">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
              <polyline points="16 17 21 12 16 7" />
              <line x1="21" x2="9" y1="12" y2="12" />
            </svg>
            <span>Keluar</span>
          </a>
        </div>
      </nav>
    </div>

    <!-- Desktop Header -->
    <header class="hidden lg:flex desktop-header px-6">
      <div class="flex-1 flex items-center">
        <span class="text-sm text-gray-500 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-user-circle">
            <circle cx="12" cy="12" r="10" />
            <circle cx="12" cy="10" r="3" />
            <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" />
          </svg>
          Halo, <span class="font-semibold text-gray-800"><?= e(AuthService::user()['fullname'] ?? 'User') ?></span>
        </span>
      </div>
    </header>

    <!-- Mobile Header -->
    <header
      class="lg:hidden fixed top-0 left-0 right-0 h-16 bg-white/90 backdrop-blur-md shadow-sm z-50 flex items-center justify-between px-4 transition-all duration-300">
      <button id="mobile-menu-toggle" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-menu">
          <line x1="4" x2="20" y1="12" y2="12" />
          <line x1="4" x2="20" y1="6" y2="6" />
          <line x1="4" x2="20" y1="18" y2="18" />
        </svg>
      </button>
      <h1
        class="text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-network text-blue-600">
          <rect x="16" y="16" width="6" height="6" rx="1" />
          <rect x="2" y="16" width="6" height="6" rx="1" />
          <rect x="9" y="2" width="6" height="6" rx="1" />
          <path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
          <path d="M12 12V8" />
        </svg>
        Ghaib Network
      </h1>
      <a href="/logout" class="text-red-500 p-2 hover:bg-red-50 rounded-full transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          class="lucide lucide-log-out">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
          <polyline points="16 17 21 12 16 7" />
          <line x1="21" x2="9" y1="12" y2="12" />
        </svg>
      </a>
    </header>

    <!-- Mobile Sidebar Drawer -->
    <div id="mobile-sidebar" class="lg:hidden fixed inset-0 z-50 hidden">
      <div id="sidebar-backdrop" class="absolute inset-0 bg-black/50 transition-opacity"></div>
      <div id="sidebar-panel"
        class="absolute left-0 top-0 bottom-0 w-72 bg-white shadow-xl transform -translate-x-full transition-transform duration-300">
        <div class="flex items-center justify-between h-16 bg-gradient-to-r from-blue-600 to-purple-600 shadow-md px-4">
          <h1 class="text-white text-lg font-bold flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-network">
              <rect x="16" y="16" width="6" height="6" rx="1" />
              <rect x="2" y="16" width="6" height="6" rx="1" />
              <rect x="9" y="2" width="6" height="6" rx="1" />
              <path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
              <path d="M12 12V8" />
            </svg>
            Ghaib Network
          </h1>
          <button id="close-sidebar-btn" class="text-white p-2 hover:bg-white/20 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-x">
              <path d="M18 6 6 18" />
              <path d="m6 6 12 12" />
            </svg>
          </button>
        </div>

        <nav class="mt-4">
          <a href="/" class="<?= getActiveClass('/', false) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-house-icon lucide-house">
              <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
              <path
                d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            </svg>
            <span>Dashboard</span>
          </a>
          <a href="/ppp/active" class="<?= getActiveClass('/ppp/active', false) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-wifi-icon lucide-wifi">
              <path d="M12 20h.01" />
              <path d="M2 8.82a15 15 0 0 1 20 0" />
              <path d="M5 12.859a10 10 0 0 1 14 0" />
              <path d="M8.5 16.429a5 5 0 0 1 7 0" />
            </svg>
            <span>PPP Aktif</span>
          </a>
          <a href="/ppp/non-active" class="<?= getActiveClass('/ppp/non-active', false) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-wifi-off-icon lucide-wifi-off">
              <path d="M12 20h.01" />
              <path d="M8.5 16.429a5 5 0 0 1 7 0" />
              <path d="M5 12.859a10 10 0 0 1 5.17-2.69" />
              <path d="M19 12.859a10 10 0 0 0-2.007-1.523" />
              <path d="M2 8.82a15 15 0 0 1 4.177-2.643" />
              <path d="M22 8.82a15 15 0 0 0-11.288-3.764" />
              <path d="m2 2 20 20" />
            </svg>
            <span>PPP Non-Aktif</span>
          </a>
          <a href="/ppp/secrets" class="<?= getActiveClass('/ppp/secrets', false) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-key-icon lucide-key">
              <path d="M2 18a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v2H2z" />
              <path d="M4 14h16" />
              <path d="m15 8-1-1" />
              <path d="m19 12-1-1" />
              <path d="m15 12-1-1" />
              <path d="m19 8-1-1" />
              <path d="m8 8-1-1" />
              <path d="m12 12-1-1" />
              <path d="m8 12-1-1" />
              <path d="m12 8-1-1" />
            </svg>
            <span>PPP Secrets</span>
          </a>
          <a href="/ppp/profiles"
            class="<?= getActiveClass('/ppp/profiles', false) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-layers-icon lucide-layers">
              <path
                d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z" />
              <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65" />
              <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65" />
            </svg>
            <span>PPP Profiles</span>
          </a>
          <a href="/mikrotik/logs" class="<?= getActiveClass('/mikrotik/logs', false) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-history-icon lucide-history">
              <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
              <path d="M3 3v5h5" />
              <path d="M12 7v5l4 2" />
            </svg>
            <span>Log MikroTik</span>
          </a>
          <a href="/mikrotik/interface" class="<?= getActiveClass('/mikrotik/interface', false) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-ethernet-port-icon lucide-ethernet-port">
              <path d="m15 20 3-3h2a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h2l3 3z" />
              <path d="M6 8v1" />
              <path d="M10 8v1" />
              <path d="M14 8v1" />
              <path d="M18 8v1" />
            </svg>
            <span>Monitor Interface</span>
          </a>
          <a href="/settings" class="<?= getActiveClass('/settings', false) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-settings-icon lucide-settings">
              <path
                d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915" />
              <circle cx="12" cy="12" r="3" />
            </svg>
            <span>Pengaturan</span>
          </a>
          <div class="mt-4 pt-4 border-t border-gray-100">
            <a href="/logout"
              class="flex items-center px-6 py-3 text-red-600 hover:bg-red-50 transition-colors gap-2 group">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-log-out group-hover:translate-x-1 transition-transform">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" x2="9" y1="12" y2="12" />
              </svg>
              <span>Keluar</span>
            </a>
          </div>
        </nav>
      </div>
    </div>

    <!-- Main Content Area -->
    <main class="main-content py-30 lg:p-16">
