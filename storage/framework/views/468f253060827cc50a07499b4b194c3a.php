<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title', 'F55 MEN RELAXATION & MASSAGE CENTER - DUBAI'); ?></title>

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
    body { font-family: 'Inter', sans-serif; background-color: #0a0a0a; color: #f3f4f6; }

    /* ── Gold gradient text ── */
    .text-gold-gradient {
      background: linear-gradient(135deg, #D4AF37 0%, #F5DF93 50%, #D4AF37 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

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
    .badge-completed  { background: #064E3B; color: #6EE7B7; border: 1px solid #05966950; }
    .badge-confirmed  { background: #1E3A8A; color: #93C5FD; border: 1px solid #3B82F650; }
    .badge-busy       { background: #78350F; color: #FCD34D; border: 1px solid #F59E0B50; }
    .badge-cancelled  { background: #7F1D1D; color: #FCA5A5; border: 1px solid #EF444450; }

    /* ── Scrollbars ── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #0D0D0D; }
    ::-webkit-scrollbar-thumb { background: #D4AF3755; border-radius: 999px; }
  </style>

  <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="h-full bg-[#0a0a0a] text-gray-100 antialiased flex flex-col font-sans" x-data="{ notifOpen: false, mobileMenu: false }">

  <!-- CUSTOMER LAYOUT -->
  <div class="min-h-screen flex flex-col pb-20 md:pb-0 bg-[#0a0a0a]">

    
    <?php if(Auth::check() && Auth::user()->isAdmin() && session('admin_preview_customer') === true): ?>
    <div class="sticky top-0 z-50 px-4 sm:px-8 py-2.5 text-xs font-bold flex flex-wrap items-center justify-between gap-3 shadow-2xl border-b border-amber-500/50"
         style="background: linear-gradient(90deg, #78350F 0%, #92400E 50%, #B45309 100%); color: #FFFFFF;">
      <div class="flex items-center gap-2.5">
        <span class="flex h-2.5 w-2.5 relative">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-300 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-yellow-300"></span>
        </span>
        <div class="flex items-center gap-2">
          <i data-lucide="eye" class="w-4 h-4 text-yellow-300"></i>
          <span><?php echo e(__('Mode Preview Admin:')); ?></span>
          <span class="font-normal text-amber-100 hidden sm:inline"><?php echo e(__('Anda sedang melihat antarmuka sisi pelanggan sebagai Administrator.')); ?></span>
        </div>
      </div>
      <form action="<?php echo e(route('admin.preview.exit')); ?>" method="POST" class="inline m-0">
        <?php echo csrf_field(); ?>
        <button type="submit"
                class="px-4 py-1.5 rounded-xl font-black text-xs transition-all flex items-center gap-1.5 shadow-md hover:scale-105 active:scale-95"
                style="background: #111111; color: #F5DF93; border: 1px solid #D4AF37;">
          <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-yellow-400"></i>
          <span><?php echo e(__('Kembali ke Halaman Admin')); ?></span>
        </button>
      </form>
    </div>
    <?php endif; ?>
    
    <!-- Top Glassmorphism Navigation Bar -->
    <header class="sticky top-0 z-40 backdrop-blur-md bg-[#111111]/90 border-b border-[#D4AF3730] shadow-2xl">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 py-2.5 flex items-center justify-between gap-4">

        <!-- Logo -->
        <a href="<?php echo e(route('customer.dashboard')); ?>" class="flex items-center gap-3">
          <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-lg overflow-hidden"
               style="border: 1.5px solid #D4AF37; box-shadow: 0 0 16px rgba(212,175,55,0.3);">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="F55 Center Logo" class="w-full h-full object-cover">
          </div>
          <div>
            <span class="font-display font-black text-white text-base tracking-wide block leading-tight">F55 CENTER</span>
            <span class="text-[9px] font-bold tracking-widest uppercase block" style="color:#D4AF37;">DUBAI LUXURY SPA</span>
          </div>
        </a>

        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center gap-1.5 p-1.5 rounded-2xl border border-[#D4AF3725] bg-[#141414]/90 shadow-inner text-xs font-semibold">
          <a href="<?php echo e(route('customer.dashboard')); ?>"
            class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 <?php echo e(request()->routeIs('customer.dashboard') ? 'nav-active' : 'text-gray-300 hover:text-white hover:bg-white/5'); ?>">
            <i data-lucide="home" class="w-4 h-4"></i> <?php echo e(__('Dashboard')); ?>

          </a>
          <a href="<?php echo e(route('customer.services')); ?>"
            class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 <?php echo e(request()->routeIs('customer.services*') ? 'nav-active' : 'text-gray-300 hover:text-white hover:bg-white/5'); ?>">
            <i data-lucide="sparkles" class="w-4 h-4"></i> <?php echo e(__('Layanan Spa')); ?>

          </a>
          <a href="<?php echo e(route('customer.booking.create')); ?>"
            class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 <?php echo e(request()->routeIs('customer.booking.create') ? 'nav-active' : 'text-gray-300 hover:text-white hover:bg-white/5'); ?>">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i> <?php echo e(__('Booking')); ?>

          </a>
          <a href="<?php echo e(route('customer.bookings')); ?>"
            class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 <?php echo e(request()->routeIs('customer.bookings*') ? 'nav-active' : 'text-gray-300 hover:text-white hover:bg-white/5'); ?>">
            <i data-lucide="history" class="w-4 h-4"></i> <?php echo e(__('Riwayat')); ?>

          </a>
          <a href="<?php echo e(route('customer.profile')); ?>"
            class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 <?php echo e(request()->routeIs('customer.profile') ? 'nav-active' : 'text-gray-300 hover:text-white hover:bg-white/5'); ?>">
            <i data-lucide="user-circle" class="w-4 h-4"></i> <?php echo e(__('Biodata Saya')); ?>

          </a>
        </nav>

        <!-- User Controls & Actions -->
        <div class="flex items-center gap-3">
          
          <!-- Multi-Language Switcher (ID, EN, AR-Dubai) -->
          <div class="relative" x-data="{ langOpen: false }">
            <button @click="langOpen = !langOpen"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all"
                    style="background:#1a1a1a; border:1px solid #D4AF3740; color:#F5DF93;">
              <i data-lucide="globe" class="w-3.5 h-3.5 text-yellow-400"></i>
              <span class="text-[11px]">
                <?php if(app()->getLocale() === 'ar'): ?> 🇦🇪 العربية (دبي)
                <?php elseif(app()->getLocale() === 'en'): ?> 🇬🇧 EN (Dubai)
                <?php else: ?> 🇮🇩 ID (WIB)
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

          <!-- Notification Dropdown Toggle -->
          <div class="relative">
            <button @click="notifOpen = !notifOpen"
              class="p-2.5 rounded-xl border relative shadow-sm transition-all"
              style="background:#1a1a1a;border:1px solid #D4AF3730;color:#F5DF93;">
              <i data-lucide="bell" class="w-4 h-4"></i>
              <?php if(isset($unreadNotifCount) && $unreadNotifCount > 0): ?>
                <span class="absolute top-1 right-1 w-2.5 h-2.5 rounded-full bg-yellow-400 animate-ping"></span>
                <span class="absolute top-1 right-1 w-2.5 h-2.5 rounded-full bg-yellow-500 border border-black"></span>
              <?php endif; ?>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="notifOpen" @click.outside="notifOpen = false" x-transition
              class="absolute <?php echo e(app()->getLocale() === 'ar' ? 'left-0 origin-top-left' : 'right-0 origin-top-right'); ?> mt-2 w-80 sm:w-96 max-w-[calc(100vw-2rem)] rounded-2xl p-3.5 sm:p-4 space-y-3 z-50 shadow-2xl overflow-hidden"
              style="background:#141414;border:1px solid #D4AF3740;">
              <div class="flex items-center justify-between border-b pb-2" style="border-color:#D4AF3720;">
                <div class="flex items-center gap-2">
                  <span class="font-extrabold text-xs text-white"><?php echo e(__('Notifikasi Reservasi')); ?></span>
                  <?php if(isset($unreadNotifCount) && $unreadNotifCount > 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black shrink-0"
                          style="background:#D4AF3720;color:#D4AF37;border:1px solid #D4AF3740;"><?php echo e($unreadNotifCount); ?> <?php echo e(__('Aktif')); ?></span>
                  <?php endif; ?>
                </div>
                <button @click="notifOpen = false" class="text-[10px] font-bold hover:underline shrink-0" style="color:#D4AF37;"><?php echo e(__('Tutup')); ?></button>
              </div>
              <div class="space-y-2 text-xs max-h-72 overflow-y-auto divide-y" style="border-color:#D4AF3710;">
                <?php $__empty_1 = true; $__currentLoopData = $userNotifications ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ntf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e($ntf['url']); ?>" @click="notifOpen = false"
                  class="block p-2.5 rounded-xl hover:bg-white/5 transition-colors space-y-1">
                  <div class="flex items-center justify-between gap-2">
                    <span class="font-bold text-white text-xs truncate"><?php echo e($ntf['title']); ?></span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase shrink-0 whitespace-nowrap border <?php echo e($ntf['badge_class']); ?>">
                      <?php echo e($ntf['badge']); ?>

                    </span>
                  </div>
                  <p class="text-gray-400 text-[11px] leading-relaxed truncate"><?php echo e($ntf['message']); ?></p>
                  <span class="text-[10px] text-gray-500 font-medium block"><?php echo e($ntf['time']); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="py-6 text-center text-xs text-gray-500 font-medium space-y-1">
                  <i data-lucide="bell-off" class="w-6 h-6 mx-auto text-gray-600"></i>
                  <p><?php echo e(__('Belum ada aktivitas reservasi terbaru.')); ?></p>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <?php if(Auth::user() && Auth::user()->isAdmin()): ?>
            <a href="<?php echo e(route('admin.dashboard')); ?>"
              class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black shadow-md transition-all"
              style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
              <i data-lucide="shield" class="w-3.5 h-3.5"></i>
              <span><?php echo e(__('Panel Admin')); ?></span>
            </a>
          <?php endif; ?>

          <!-- User Profile & Biodata Link -->
          <div class="flex items-center gap-2 p-1 pl-3 rounded-2xl shadow-sm"
               style="background:#141414;border:1px solid #D4AF3730;">
            <a href="<?php echo e(route('customer.profile')); ?>" class="text-xs font-bold text-gray-200 hover:text-yellow-400 transition-colors hidden sm:inline">
              <?php echo e(Auth::user()->name ?? __('Pengguna')); ?>

            </a>
            <a href="<?php echo e(route('customer.profile')); ?>">
              <img src="<?php echo e(Auth::user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250'); ?>"
                class="w-7 h-7 rounded-xl object-cover border border-[#D4AF3760]">
            </a>
            <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
              <?php echo csrf_field(); ?>
              <button type="submit" title="<?php echo e(__('Keluar')); ?>" class="p-1.5 text-gray-500 hover:text-red-400 transition-colors">
                <i data-lucide="log-out" class="w-4 h-4"></i>
              </button>
            </form>
          </div>

        </div>
      </div>
    </header>

    <!-- Customer Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="mt-12 py-8 text-center text-xs text-gray-500"
            style="background:#111111;border-top:1px solid #D4AF3720;">
      <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 overflow-hidden shadow"
               style="border: 1px solid #D4AF37;">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="F55" class="w-full h-full object-cover">
          </div>
          <div class="text-left">
            <span class="font-bold text-white block text-sm">F55 MEN RELAXATION & MASSAGE CENTER</span>
            <span class="text-[10px] text-gray-500">DUBAI, UNITED ARAB EMIRATES</span>
          </div>
        </div>
        <p class="text-gray-500 text-xs">© <?php echo e(date('Y')); ?> F55 Center Dubai. All rights reserved. <?php echo e(__('Mata Uang')); ?>: <strong>AED (Dirham)</strong></p>
      </div>
    </footer>

    <!-- Customer Mobile Bottom Navigation Bar -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 px-3 py-2 flex justify-around items-center text-[10px] font-bold shadow-2xl backdrop-blur-md"
         style="background:#111111F0;border-top:1px solid #D4AF3730;">
      <a href="<?php echo e(route('customer.dashboard')); ?>" class="flex flex-col items-center gap-1 <?php echo e(request()->routeIs('customer.dashboard') ? 'text-yellow-400 font-extrabold' : 'text-gray-400'); ?>">
        <i data-lucide="home" class="w-4 h-4"></i>
        <span><?php echo e(__('Dashboard')); ?></span>
      </a>
      <a href="<?php echo e(route('customer.services')); ?>" class="flex flex-col items-center gap-1 <?php echo e(request()->routeIs('customer.services*') ? 'text-yellow-400 font-extrabold' : 'text-gray-400'); ?>">
        <i data-lucide="sparkles" class="w-4 h-4"></i>
        <span><?php echo e(__('Layanan')); ?></span>
      </a>
      <a href="<?php echo e(route('customer.booking.create')); ?>" class="flex flex-col items-center gap-1 p-2.5 rounded-full -mt-5 shadow-lg"
         style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;">
        <i data-lucide="calendar-plus" class="w-5 h-5"></i>
      </a>
      <a href="<?php echo e(route('customer.bookings')); ?>" class="flex flex-col items-center gap-1 <?php echo e(request()->routeIs('customer.bookings*') ? 'text-yellow-400 font-extrabold' : 'text-gray-400'); ?>">
        <i data-lucide="history" class="w-4 h-4"></i>
        <span><?php echo e(__('Riwayat')); ?></span>
      </a>
      <a href="<?php echo e(route('customer.profile')); ?>" class="flex flex-col items-center gap-1 <?php echo e(request()->routeIs('customer.profile*') ? 'text-yellow-400 font-extrabold' : 'text-gray-400'); ?>">
        <i data-lucide="user" class="w-4 h-4"></i>
        <span><?php echo e(__('Biodata')); ?></span>
      </a>
    </div>

  </div>

  <script>
    lucide.createIcons();

    // SweetAlert2 Flash Messages in Black & Gold Theme
    <?php if(session('success')): ?>
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: <?php echo json_encode(__(session('success')), 15, 512) ?>,
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#141414',
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
        background: '#141414',
        color: '#fff',
      });
    <?php endif; ?>
    <?php if(!empty($errors) && $errors->any()): ?>
      <?php $errHtml = '<ul style="text-align:left;font-size:13px;list-style:disc;padding-left:18px">' . collect($errors->all())->map(fn($e) => '<li>'.e($e).'</li>')->implode('') . '</ul>'; ?>
      Swal.fire({
        icon: 'error',
        title: <?php echo json_encode(__('Terjadi Kesalahan'), 15, 512) ?>,
        html: <?php echo json_encode($errHtml, 15, 512) ?>,
        background: '#141414',
        color: '#fff',
        confirmButtonColor: '#D4AF37',
        confirmButtonText: '<span style="color:#111;font-weight:900">OK</span>'
      });
    <?php endif; ?>

    // SweetAlert2 confirmation for forms
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('submit', function(e) {
          e.preventDefault();
          var form = this;
          var msg = el.dataset.confirm || <?php echo json_encode(__('Apakah Anda yakin?'), 15, 512) ?>;
          var title = el.dataset.confirmTitle || <?php echo json_encode(__('Konfirmasi Aksi'), 15, 512) ?>;
          Swal.fire({
            title: title,
            text: msg,
            icon: 'warning',
            background: '#141414',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#D4AF37',
            cancelButtonColor: '#4b5563',
            confirmButtonText: '<span style="color:#111;font-weight:900">' + <?php echo json_encode(__('Ya, Lanjutkan!'), 512) ?> + '</span>',
            cancelButtonText: <?php echo json_encode(__('Batal'), 15, 512) ?>,
          }).then(function(result) {
            if (result.isConfirmed) { form.submit(); }
          });
        });
      });
    });
  </script>
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Azizah Qudsiyah\Desktop\spa-web\resources\views/layouts/app.blade.php ENDPATH**/ ?>