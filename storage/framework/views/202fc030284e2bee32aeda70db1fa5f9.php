<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title', 'Panel Admin - F55 MEN RELAXATION & MASSAGE CENTER'); ?></title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?php echo e(asset('images/logo.png')); ?>">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            display: ['Playfair Display', 'serif'],
            sans: ['Inter', 'sans-serif'],
          },
          colors: {
            gold: {
              50:  '#FEFCE8',
              100: '#FEF9C3',
              200: '#FEF08A',
              300: '#FDE047',
              400: '#FACC15',
              500: '#D4AF37',
              600: '#B8960C',
              700: '#92700A',
              800: '#7A5C08',
              900: '#654C07',
            },
            dark: {
              50:  '#2A2A2A',
              100: '#222222',
              200: '#1C1C1C',
              300: '#181818',
              400: '#141414',
              500: '#111111',
              600: '#0D0D0D',
              700: '#0A0A0A',
              800: '#080808',
              900: '#050505',
            }
          }
        }
      }
    }
  </script>

  <style>
    * { box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; }

    /* ── Gold gradient text ── */
    .text-gold-gradient {
      background: linear-gradient(135deg, #D4AF37 0%, #F5DF93 50%, #D4AF37 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* ── Sidebar scrollbar ── */
    .sidebar-scroll::-webkit-scrollbar { width: 4px; }
    .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background: #D4AF3766; border-radius: 999px; }

    /* ── Active nav item glow ── */
    .nav-active {
      background: linear-gradient(90deg, #D4AF37 0%, #B8960C 100%);
      color: #111 !important;
      font-weight: 800;
      box-shadow: 0 4px 16px 0 #D4AF3740;
    }

    /* ── Card hover lift ── */
    .card-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card-lift:hover { transform: translateY(-3px); box-shadow: 0 8px 32px 0 #D4AF3720; }

    /* ── Status badges ── */
    .badge-completed  { background:#D1FAE5; color:#065F46; }
    .badge-confirmed  { background:#DBEAFE; color:#1E3A8A; }
    .badge-busy       { background:#FEF3C7; color:#92400E; }
    .badge-cancelled  { background:#FEE2E2; color:#991B1B; }

    /* ── Print styles ── */
    @media print {
      aside, header, .no-print { display: none !important; }
      body { background: white !important; }
      main { padding: 0 !important; }
    }

    /* ── Scrollbar for main ── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #0D0D0D; }
    ::-webkit-scrollbar-thumb { background: #D4AF3755; border-radius: 999px; }
  </style>

  <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="h-full bg-dark-300 text-gray-100 antialiased"
      x-data="{ 
        sidebarOpen: (window.innerWidth >= 768 ? (localStorage.getItem('admin_sidebar_open') !== 'false') : false),
        toggleSidebar() {
          this.sidebarOpen = !this.sidebarOpen;
          if (window.innerWidth >= 768) {
            localStorage.setItem('admin_sidebar_open', this.sidebarOpen);
          }
        }
      }">

  <div class="min-h-screen flex relative">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen"
         @click="toggleSidebar()"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm z-40 md:hidden"></div>

    <!-- ═══ SIDEBAR ═══ -->
    <aside :class="sidebarOpen ? 'w-64 translate-x-0 opacity-100' : 'w-0 -translate-x-full md:translate-x-0 md:w-0 overflow-hidden opacity-0 pointer-events-none border-0 p-0'"
           class="flex flex-col shrink-0 fixed md:sticky top-0 <?php echo e(app()->getLocale() === 'ar' ? 'right-0' : 'left-0'); ?> md:left-auto md:right-auto h-screen max-h-screen z-50 md:z-30 transition-all duration-300 ease-in-out overflow-hidden select-none"
           style="background: linear-gradient(180deg, #111111 0%, #0D0D0D 60%, #0A0A0A 100%); border-right: 1px solid #D4AF3722;">

      <div class="w-64 min-w-[16rem] flex flex-col h-full max-h-screen overflow-hidden">

        <div class="p-4 space-y-5 overflow-y-auto flex-1 min-h-0 sidebar-scroll">

          <!-- Logo & Close -->
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <!-- Official Brand Logo -->
              <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 overflow-hidden"
                   style="border: 1.5px solid #D4AF37; box-shadow: 0 0 16px #D4AF3730;">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="F55 Center Logo" class="w-full h-full object-cover">
              </div>
              <div>
                <h2 class="font-display font-black text-white text-sm tracking-wide leading-tight">F55 CENTER</h2>
                <span class="text-[9px] font-bold uppercase tracking-widest block" style="color:#D4AF37;">MEN RELAXATION & SPA</span>
              </div>
            </div>
            <!-- Close Sidebar Button (Mobile & Desktop) -->
            <button @click="toggleSidebar()" class="text-gray-400 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors" title="<?php echo e(__('Tutup Sidebar')); ?>">
              <i data-lucide="panel-left-close" class="w-5 h-5"></i>
            </button>
          </div>

        <!-- Role Badge -->
        <div class="px-3 py-2 rounded-xl text-center" style="background:#D4AF3715; border:1px solid #D4AF3730;">
          <?php if(Auth::user()->role === 'bos'): ?>
            <span class="text-[10px] font-extrabold uppercase tracking-widest" style="color:#D4AF37;">👑 <?php echo e(__('Akses Bos')); ?></span>
          <?php else: ?>
            <span class="text-[10px] font-extrabold uppercase tracking-widest" style="color:#D4AF37;">⚙️ <?php echo e(__('Super Admin')); ?></span>
          <?php endif; ?>
        </div>

        <!-- Navigation -->
        <div class="space-y-5">

          <!-- Main Menu -->
          <div>
            <span class="text-[9px] font-extrabold uppercase tracking-widest block mb-2 px-2" style="color:#D4AF3799;"><?php echo e(__('MENU UTAMA')); ?></span>
            <nav class="space-y-1 text-xs font-semibold">

              <?php if(Auth::user()->role === 'bos'): ?>
                <!-- BOS: Executive Read-Only Menu -->
                <a href="<?php echo e(route('bos.dashboard')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('bos.dashboard') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Dashboard')); ?>

                </a>
                <a href="<?php echo e(route('bos.bookings')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('bos.bookings*') || request()->routeIs('bos.orders*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="calendar" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Riwayat Booking')); ?>

                </a>
                <a href="<?php echo e(route('bos.sessions')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('bos.sessions*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="timer" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Timer & Sesi Aktif')); ?>

                </a>
                <a href="<?php echo e(route('bos.reports')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('bos.reports*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="file-text" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Laporan Keuangan')); ?>

                </a>
                <a href="<?php echo e(route('bos.biodata')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('bos.biodata') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="user-circle" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Biodata Saya')); ?>

                </a>
              <?php else: ?>
                <!-- ADMIN: full menu -->
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('admin.dashboard') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Dashboard')); ?>

                </a>
                <a href="<?php echo e(route('admin.bookings')); ?>"
                   class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('admin.bookings*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <div class="flex items-center gap-3">
                    <i data-lucide="calendar" class="w-4 h-4 shrink-0"></i> <span><?php echo e(__('Riwayat Booking')); ?></span>
                  </div>
                  <?php if(isset($newBookingsCount) && $newBookingsCount > 0): ?>
                    <span id="sidebar-order-badge" class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 animate-pulse">
                      <?php echo e($newBookingsCount); ?> <?php echo e(__('Baru')); ?>

                    </span>
                  <?php endif; ?>
                </a>
                <a href="<?php echo e(route('admin.sessions')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('admin.sessions*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="timer" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Sesi Aktif & Timer')); ?>

                </a>
                <a href="<?php echo e(route('admin.customers')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('admin.customers*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="user-check" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Pelanggan')); ?>

                </a>
                <a href="<?php echo e(route('admin.services')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('admin.services*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="sparkles" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Layanan Spa')); ?>

                </a>
                <a href="<?php echo e(route('admin.therapists')); ?>"
                   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('admin.therapists*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                  <i data-lucide="users" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Terapis')); ?>

                </a>
              <?php endif; ?>
            </nav>
          </div>

          <?php if(Auth::user()->role === 'admin'): ?>
          <!-- Keuangan -->
          <div>
            <span class="text-[9px] font-extrabold uppercase tracking-widest block mb-2 px-2" style="color:#D4AF3799;"><?php echo e(__('Laporan Keuangan')); ?></span>
            <nav class="space-y-1 text-xs font-semibold">
              <a href="<?php echo e(route('admin.reports')); ?>"
                 class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('admin.reports*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                <i data-lucide="file-text" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Laporan')); ?>

              </a>
              <a href="<?php echo e(route('admin.expenses')); ?>"
                 class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('admin.expenses*') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                <i data-lucide="trending-down" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Pengeluaran')); ?>

              </a>
            </nav>
          </div>

          <!-- Pengaturan -->
          <div>
            <span class="text-[9px] font-extrabold uppercase tracking-widest block mb-2 px-2" style="color:#D4AF3799;"><?php echo e(__('Biodata Saya')); ?></span>
            <nav class="space-y-1 text-xs font-semibold">
              <a href="<?php echo e(route('admin.biodata')); ?>"
                 class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all <?php echo e(request()->routeIs('admin.biodata') ? 'nav-active' : 'text-gray-400 hover:bg-white/5 hover:text-white'); ?>">
                <i data-lucide="user-circle" class="w-4 h-4 shrink-0"></i> <?php echo e(__('Biodata Saya')); ?>

              </a>
              <a href="<?php echo e(route('admin.preview.enter')); ?>"
                 class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                <i data-lucide="eye" class="w-4 h-4 shrink-0 text-yellow-400"></i> <?php echo e(__('Tampilan Customer')); ?>

              </a>
            </nav>
          </div>
          <?php endif; ?>

        </div>
      </div>

        <!-- User Footer (Always Visible at Bottom) -->
        <div class="p-3.5 shrink-0 bg-[#0d0d0d]" style="border-top: 1px solid #D4AF3720;">
          <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
              <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black shrink-0"
                   style="background: linear-gradient(135deg,#D4AF37,#B8960C); color:#111;">
                <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

              </div>
              <div class="min-w-0">
                <p class="text-xs font-bold text-white truncate"><?php echo e(Auth::user()->name); ?></p>
                <p class="text-[10px] font-medium truncate" style="color:#D4AF37;"><?php echo e(ucfirst(Auth::user()->role)); ?></p>
              </div>
            </div>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
              <?php echo csrf_field(); ?>
              <button type="submit" title="<?php echo e(__('Keluar')); ?>" class="p-2 rounded-lg transition-colors text-gray-400 hover:text-red-400 hover:bg-red-950/30">
                <i data-lucide="log-out" class="w-4 h-4"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </aside>

    <!-- ═══ MAIN CONTENT ═══ -->
    <div class="flex-1 flex flex-col min-w-0">

      <!-- Sticky Header -->
      <header class="sticky top-0 z-30 px-4 sm:px-6 py-3 flex items-center justify-between gap-4"
              style="background:#111111cc; backdrop-filter:blur(16px); border-bottom:1px solid #D4AF3720;">
        <div class="flex items-center gap-3">
          <!-- Sidebar Toggle (Mobile & Desktop) -->
          <button @click="toggleSidebar()" 
                  class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-colors cursor-pointer"
                  title="<?php echo e(__('Buka / Tutup Sidebar')); ?>">
            <i data-lucide="menu" class="w-5 h-5"></i>
          </button>

          <div>
            <h1 class="text-base sm:text-lg font-black text-white tracking-tight"><?php echo $__env->yieldContent('header_title', __('Dashboard')); ?></h1>
            <p class="text-[11px] text-gray-500 font-medium hidden sm:block"><?php echo $__env->yieldContent('header_subtitle', __('Panel kontrol F55 Men Relaxation & Massage Center')); ?></p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <!-- Multi-Language Switcher (ID, EN, AR-Dubai) -->
          <div class="relative" x-data="{ langOpen: false }">
            <button @click="langOpen = !langOpen"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-2 transition-all"
                    style="background:#1a1a1a; border:1px solid #D4AF3730; color:#F5DF93;">
              <i data-lucide="globe" class="w-3.5 h-3.5 text-yellow-400"></i>
              <span>
                <?php if(app()->getLocale() === 'ar'): ?> 🇦🇪 العربية (دبي)
                <?php elseif(app()->getLocale() === 'en'): ?> 🇬🇧 English (Dubai)
                <?php else: ?> 🇮🇩 Indonesia (WIB)
                <?php endif; ?>
              </span>
              <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400"></i>
            </button>

            <div x-show="langOpen" @click.outside="langOpen = false" x-transition
                 class="absolute <?php echo e(app()->getLocale() === 'ar' ? 'left-0 origin-top-left' : 'right-0 origin-top-right'); ?> mt-2 w-44 rounded-2xl p-1.5 shadow-2xl z-50 space-y-1"
                 style="background:#141414; border:1px solid #D4AF3740;">
              <a href="<?php echo e(route('lang.switch', 'ar')); ?>"
                 class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition-all <?php echo e(app()->getLocale() === 'ar' ? 'bg-[#D4AF37] text-black' : 'text-gray-300 hover:bg-white/10'); ?>">
                <span>🇦🇪 العربية (دبي)</span>
                <?php if(app()->getLocale() === 'ar'): ?> <i data-lucide="check" class="w-3.5 h-3.5"></i> <?php endif; ?>
              </a>
              <a href="<?php echo e(route('lang.switch', 'en')); ?>"
                 class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition-all <?php echo e(app()->getLocale() === 'en' ? 'bg-[#D4AF37] text-black' : 'text-gray-300 hover:bg-white/10'); ?>">
                <span>🇬🇧 English</span>
                <?php if(app()->getLocale() === 'en'): ?> <i data-lucide="check" class="w-3.5 h-3.5"></i> <?php endif; ?>
              </a>
              <a href="<?php echo e(route('lang.switch', 'id')); ?>"
                 class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition-all <?php echo e(app()->getLocale() === 'id' ? 'bg-[#D4AF37] text-black' : 'text-gray-300 hover:bg-white/10'); ?>">
                <span>🇮🇩 Indonesia</span>
                <?php if(app()->getLocale() === 'id'): ?> <i data-lucide="check" class="w-3.5 h-3.5"></i> <?php endif; ?>
              </a>
            </div>
          </div>

          <!-- Order Notification Dropdown -->
          <div class="relative" x-data="{ orderNotifOpen: false }">
            <button @click="orderNotifOpen = !orderNotifOpen"
                    class="p-2 rounded-xl border relative shadow-sm transition-all"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#F5DF93;"
                    title="<?php echo e(__('Notifikasi Order Masuk')); ?>">
              <i data-lucide="bell" class="w-4 h-4"></i>
              <?php if(isset($newBookingsCount) && $newBookingsCount > 0): ?>
                <span id="top-order-badge-ping" class="absolute top-1 right-1 w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                <span id="top-order-badge-dot" class="absolute top-1 right-1 w-2.5 h-2.5 rounded-full bg-emerald-500 border border-black"></span>
              <?php endif; ?>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="orderNotifOpen" @click.outside="orderNotifOpen = false" x-transition
                 class="absolute <?php echo e(app()->getLocale() === 'ar' ? 'left-0 origin-top-left' : 'right-0 origin-top-right'); ?> mt-2 w-80 sm:w-96 max-w-[calc(100vw-2rem)] rounded-2xl p-3.5 sm:p-4 space-y-3 z-50 shadow-2xl overflow-hidden"
                 style="background:#141414;border:1px solid #D4AF3740;">
              <div class="flex items-center justify-between border-b pb-2" style="border-color:#D4AF3720;">
                <div class="flex items-center gap-2">
                  <span class="font-extrabold text-xs text-white"><?php echo e(__('Notifikasi Order Masuk')); ?></span>
                  <span id="top-order-badge-text" class="px-2 py-0.5 rounded-full text-[10px] font-black shrink-0"
                        style="background:#22c55e20;color:#4ade80;border:1px solid #22c55e40;">
                    <?php echo e($newBookingsCount ?? 0); ?> <?php echo e(__('Baru')); ?>

                  </span>
                </div>
                <a href="<?php echo e(Auth::user()->role === 'bos' ? route('bos.bookings') : route('admin.bookings')); ?>" class="text-[10px] font-bold hover:underline shrink-0" style="color:#D4AF37;">
                  <?php echo e(__('Lihat Semua')); ?>

                </a>
              </div>
              <div class="space-y-2 text-xs max-h-72 overflow-y-auto divide-y" style="border-color:#D4AF3710;">
                <?php $__empty_1 = true; $__currentLoopData = $adminRecentBookings ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ob): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e((Auth::user()->role === 'bos' ? route('bos.bookings') : route('admin.bookings')) . '?search=' . $ob->booking_code); ?>" @click="orderNotifOpen = false"
                   class="block p-2.5 rounded-xl hover:bg-white/5 transition-colors space-y-1">
                  <div class="flex items-center justify-between gap-2">
                    <span class="font-bold text-white text-xs truncate"><?php echo e($ob->customer_name); ?></span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase shrink-0 whitespace-nowrap"
                          style="background:#D4AF3720;color:#F5DF93;border:1px solid #D4AF3740;">
                      AED <?php echo e(number_format($ob->price, 2)); ?>

                    </span>
                  </div>
                  <p class="text-gray-400 text-[11px] truncate"><?php echo e($ob->service_name); ?> (<?php echo e($ob->booking_code); ?>)</p>
                  <span class="text-[10px] text-gray-500 font-medium block"><?php echo e($ob->created_at->diffForHumans()); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="py-6 text-center text-xs text-gray-500 font-medium space-y-1">
                  <i data-lucide="bell-off" class="w-6 h-6 mx-auto text-gray-600"></i>
                  <p><?php echo e(__('Belum ada order baru')); ?></p>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Date Badge -->
          <div class="px-3 py-1.5 rounded-xl text-xs font-bold hidden sm:flex items-center gap-2"
               style="background:#D4AF3715; border:1px solid #D4AF3730; color:#D4AF37;"
               title="<?php echo e(config('app.timezone') === 'Asia/Dubai' ? __('Zona Waktu: Dubai (GST)') : __('Zona Waktu: Jakarta (WIB)')); ?>">
            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
            <span><?php echo e(now()->isoFormat('D MMMM YYYY')); ?> • <?php echo e(now()->format('H:i')); ?> <span class="text-[10px] font-extrabold uppercase">(<?php echo e(config('app.timezone') === 'Asia/Dubai' ? 'Dubai' : 'WIB'); ?>)</span></span>
          </div>

          <?php if(Auth::user()->role === 'admin'): ?>
          <a href="<?php echo e(route('admin.preview.enter')); ?>"
             class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 text-gray-400 hover:text-white hover:bg-white/10"
             title="<?php echo e(__('Pratinjau Tampilan Sisi Pelanggan')); ?>">
            <i data-lucide="eye" class="w-3.5 h-3.5 text-yellow-400"></i>
            <span class="hidden sm:inline"><?php echo e(__('Tampilan Customer')); ?></span>
          </a>
          <?php endif; ?>
        </div>
      </header>

      <!-- Page Content -->
      <main class="p-4 sm:p-6 flex-1 bg-dark-300">
        <?php echo $__env->yieldContent('content'); ?>
      </main>

    </div>
  </div>

  <script>
    lucide.createIcons();

    // SweetAlert2 Flash Messages
    <?php if(session('success')): ?>
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: <?php echo json_encode(__(session('success')), 15, 512) ?>,
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#1a1a1a',
        color: '#F5DF93',
        iconColor: '#D4AF37',
      });
    <?php endif; ?>
    <?php if(session('error')): ?>
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: <?php echo json_encode(__(session('error')), 15, 512) ?>,
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: '#1a1a1a',
        color: '#fff',
      });
    <?php endif; ?>
    <?php if(!empty($errors) && $errors->any()): ?>
      <?php $errHtml = '<ul style="text-align:left;font-size:13px;list-style:disc;padding-left:18px">' . collect($errors->all())->map(fn($e) => '<li>'.e($e).'</li>')->implode('') . '</ul>'; ?>
      Swal.fire({
        icon: 'error',
        title: <?php echo json_encode(__('Terjadi Kesalahan'), 15, 512) ?>,
        html: <?php echo json_encode($errHtml, 15, 512) ?>,
        background: '#1a1a1a',
        color: '#e5e7eb',
        confirmButtonColor: '#D4AF37',
        confirmButtonText: '<span style="color:#111;font-weight:900">OK</span>',
      });
    <?php endif; ?>

    // Confirm dialogs
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('submit', function(e) {
          e.preventDefault();
          var form = this;
          var msg = el.dataset.confirm || <?php echo json_encode(__('Apakah Anda yakin?'), 15, 512) ?>;
          var title = el.dataset.confirmTitle || <?php echo json_encode(__('Konfirmasi Aksi'), 15, 512) ?>;
          Swal.fire({
            title: title, text: msg, icon: 'warning',
            showCancelButton: true,
            background: '#1a1a1a', color: '#e5e7eb',
            confirmButtonColor: '#D4AF37', cancelButtonColor: '#374151',
            confirmButtonText: '<span style="color:#111;font-weight:900">' + <?php echo json_encode(__('Ya, Lanjutkan!'), 512) ?> + '</span>',
            cancelButtonText: <?php echo json_encode(__('Batal'), 15, 512) ?>,
          }).then(function(result) {
            if (result.isConfirmed) { form.submit(); }
          });
        });
      });

      document.querySelectorAll('[data-confirm-btn]').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          var targetForm = document.getElementById(btn.dataset.confirmForm);
          var msg = btn.dataset.confirmBtn || <?php echo json_encode(__('Apakah Anda yakin?'), 15, 512) ?>;
          var title = btn.dataset.confirmTitle || <?php echo json_encode(__('Konfirmasi Aksi'), 15, 512) ?>;
          Swal.fire({
            title: title, text: msg, icon: 'warning',
            showCancelButton: true,
            background: '#1a1a1a', color: '#e5e7eb',
            confirmButtonColor: '#D4AF37', cancelButtonColor: '#374151',
            confirmButtonText: '<span style="color:#111;font-weight:900">' + <?php echo json_encode(__('Ya, Lanjutkan!'), 512) ?> + '</span>',
            cancelButtonText: <?php echo json_encode(__('Batal'), 15, 512) ?>,
          }).then(function(result) {
            if (result.isConfirmed && targetForm) { targetForm.submit(); }
          });
        });
      });
    });

    window.handleStatusChange = function(selectEl) {
      var currentStatus = selectEl.getAttribute('data-current') || selectEl.value;
      var newStatus = selectEl.value;
      var remainingSecs = parseInt(selectEl.getAttribute('data-remaining') || '0', 10);
      var isFuture = selectEl.getAttribute('data-is-future') === '1';
      var scheduledAt = selectEl.getAttribute('data-scheduled-at');
      var scheduledTime = selectEl.getAttribute('data-scheduled-time') || '';
      var scheduledDate = selectEl.getAttribute('data-scheduled-date') || '';
      var form = selectEl.closest('form');

      if (newStatus === currentStatus) return;

      // Status yang sudah completed tidak boleh diubah kembali ke confirmed atau in_progress
      if (currentStatus === 'completed' && (newStatus === 'confirmed' || newStatus === 'in_progress')) {
        selectEl.value = currentStatus;
        Swal.fire({
          icon: 'warning',
          title: <?php echo json_encode(__('Sesi Sudah Selesai'), 15, 512) ?>,
          text: <?php echo json_encode(__('Booking yang sudah selesai (Completed) tidak dapat diubah kembali ke Confirmed atau In Progress.'), 15, 512) ?>,
          background: '#1a1a1a',
          color: '#e5e7eb',
          confirmButtonColor: '#D4AF37',
          confirmButtonText: '<span style="color:#111;font-weight:900">OK</span>',
        });
        return;
      }

      // Kunci jadwal masa depan: jika jam booking belum tiba, aksi tidak bisa diubah kecuali cancelled
      var isActuallyFuture = scheduledAt ? (new Date() < new Date(scheduledAt)) : isFuture;
      if (newStatus !== 'cancelled' && isActuallyFuture) {
        selectEl.value = currentStatus;
        Swal.fire({
          icon: 'warning',
          title: <?php echo json_encode(__('Belum Waktunya'), 15, 512) ?>,
          html: <?php echo json_encode(__('Booking ini dijadwalkan pada'), 15, 512) ?> + ' <b>' + scheduledDate + ' ' + scheduledTime + '</b>.<br><span class="text-xs text-gray-400 mt-2 block">' + <?php echo json_encode(__('Aksi tidak dapat diubah (kecuali Cancel) sebelum jam booking tiba.'), 15, 512) ?> + '</span>',
          background: '#1a1a1a',
          color: '#e5e7eb',
          confirmButtonColor: '#D4AF37',
          confirmButtonText: '<span style="color:#111;font-weight:900">OK</span>',
        });
        return;
      }

      if (currentStatus === 'confirmed' && newStatus === 'completed') {
        selectEl.value = currentStatus;
        Swal.fire({ icon: 'warning', title: <?php echo json_encode(__('Tidak Dapat Menyelesaikan'), 15, 512) ?>,
          text: <?php echo json_encode(__('Ubah ke "In Progress" terlebih dahulu.'), 15, 512) ?>,
          background: '#1a1a1a', color: '#e5e7eb', confirmButtonColor: '#D4AF37',
          confirmButtonText: '<span style="color:#111;font-weight:900">OK</span>',
        });
        return;
      }

      if (newStatus === 'completed' && remainingSecs > 0) {
        selectEl.value = currentStatus;
        var mins = Math.floor(remainingSecs / 60);
        var secs = remainingSecs % 60;
        var timeStr = (mins > 0 ? (mins + ' ' + <?php echo json_encode(__('menit'), 15, 512) ?> + ' ') : '') + secs + ' ' + <?php echo json_encode(__('detik'), 15, 512) ?>;
        Swal.fire({ icon: 'warning', title: <?php echo json_encode(__('Sesi Masih Berjalan'), 15, 512) ?>,
          html: <?php echo json_encode(__('Masih ada sisa waktu'), 15, 512) ?> + ' <b>' + timeStr + '</b>.',
          background: '#1a1a1a', color: '#e5e7eb', confirmButtonColor: '#D4AF37',
          confirmButtonText: '<span style="color:#111;font-weight:900">OK</span>',
        });
        return;
      }

      var statusLabels = {
        confirmed: 'CONFIRMED', in_progress: 'IN PROGRESS',
        completed: 'COMPLETED', cancelled: 'CANCELLED'
      };

      Swal.fire({
        title: <?php echo json_encode(__('Konfirmasi Ubah Status'), 15, 512) ?>,
        text: <?php echo json_encode(__('Ubah status booking menjadi:'), 15, 512) ?> + ' "' + (statusLabels[newStatus] || newStatus.toUpperCase()) + '"?',
        icon: 'question', showCancelButton: true,
        background: '#1a1a1a', color: '#e5e7eb',
        confirmButtonColor: '#D4AF37', cancelButtonColor: '#374151',
        confirmButtonText: '<span style="color:#111;font-weight:900">' + <?php echo json_encode(__('Ya, Ubah!'), 512) ?> + '</span>',
        cancelButtonText: <?php echo json_encode(__('Batal'), 15, 512) ?>,
      }).then(function(result) {
        if (result.isConfirmed) { form.submit(); }
        else { selectEl.value = currentStatus; }
      });
    };
    // Realtime Order Checker for Admin
    (function() {
      let lastKnownBookingId = <?php echo e($latestBookingId ?? 0); ?>;
      const checkUrl = '<?php echo e(Auth::user()->role === 'bos' ? route('bos.bookings.check-new') : route('admin.bookings.check-new')); ?>';

      function playOrderChime() {
        try {
          const AudioContextClass = window.AudioContext || window.webkitAudioContext;
          if (!AudioContextClass) return;
          const ctx = new AudioContextClass();
          const osc = ctx.createOscillator();
          const gain = ctx.createGain();
          osc.type = 'sine';
          osc.frequency.setValueAtTime(880, ctx.currentTime);
          osc.frequency.exponentialRampToValueAtTime(1320, ctx.currentTime + 0.3);
          gain.gain.setValueAtTime(0.3, ctx.currentTime);
          gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
          osc.connect(gain);
          gain.connect(ctx.destination);
          osc.start();
          osc.stop(ctx.currentTime + 0.4);
        } catch(e) {}
      }

      function pollNewBookings() {
        fetch(checkUrl + '?last_id=' + lastKnownBookingId)
          .then(r => r.json())
          .then(data => {
            if (data.latest_id) {
              lastKnownBookingId = Math.max(lastKnownBookingId, data.latest_id);
            }
            if (data.has_new && data.items && data.items.length > 0) {
              playOrderChime();
              data.items.forEach(item => {
                Swal.fire({
                  toast: true,
                  position: 'top-end',
                  icon: 'info',
                  title: `<b>🔔 <?php echo e(__('Order Baru Masuk')); ?>!</b><br><span style="font-size:12px;color:#D4AF37;">${item.customer_name}</span> - <span style="font-size:11px;color:#ccc;">${item.service_name}</span>`,
                  showConfirmButton: false,
                  timer: 6000,
                  timerProgressBar: true,
                  background: '#141414',
                  color: '#fff',
                  iconColor: '#22c55e',
                  didOpen: (toast) => {
                    toast.style.border = '1px solid #22c55e';
                  }
                });
              });

              // Update badge numbers
              const sBadge = document.getElementById('sidebar-order-badge');
              if (sBadge) {
                sBadge.textContent = data.total_new_count + ' <?php echo e(__('Baru')); ?>';
              }
              const tBadge = document.getElementById('top-order-badge-text');
              if (tBadge) {
                tBadge.textContent = data.total_new_count + ' <?php echo e(__('Baru')); ?>';
              }
            }
          })
          .catch(() => {});
      }

      // Check every 20 seconds
      setInterval(pollNewBookings, 20000);
    })();
  </script>
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Azizah Qudsiyah\Desktop\spa-web\resources\views/layouts/admin.blade.php ENDPATH**/ ?>