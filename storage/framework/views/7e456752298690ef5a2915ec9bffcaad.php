<?php $__env->startSection('title', __('Dashboard') . ' - F55 CENTER DUBAI'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">

  <!-- Welcome Hero Banner -->
  <div class="relative overflow-hidden rounded-3xl p-6 sm:p-10 text-white shadow-2xl"
       style="background: linear-gradient(135deg, #16140d 0%, #111111 50%, #0a0a0a 100%); border: 1.5px solid #D4AF3740; box-shadow: 0 20px 60px rgba(0,0,0,0.8), 0 0 40px rgba(212,175,55,0.12);">
    
    
    <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full blur-3xl pointer-events-none" style="background:#D4AF3715;"></div>
    <div class="absolute right-20 -top-10 w-48 h-48 rounded-full blur-2xl pointer-events-none" style="background:#F5DF9310;"></div>
    <div class="absolute top-0 left-0 right-0 h-1" style="background:linear-gradient(90deg,#D4AF37,#F5DF93,#B8960C);"></div>

    <div class="relative z-10 max-w-2xl space-y-4">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold"
           style="background:#D4AF3715;border:1px solid #D4AF3740;color:#F5DF93;">
        <i data-lucide="sparkles" class="w-4 h-4 text-yellow-400"></i>
        <span><?php echo e(__('F55 MEN RELAXATION & MASSAGE CENTER DUBAI')); ?></span>
      </div>

      <h1 class="text-xl sm:text-3xl lg:text-4xl font-display font-black tracking-tight text-white leading-tight">
        <?php echo e(__('Selamat Datang')); ?>, <span class="text-gold-gradient"><?php echo e($user->name); ?></span>!
      </h1>

      <p class="text-gray-300 text-xs sm:text-sm leading-relaxed max-w-xl">
        <?php echo e(__('Nikmati pengalaman relaksasi pria terbaik kelas dunia di Dubai. Pilih terapis favorit Anda dengan tarif resmi dalam mata uang AED.')); ?>

      </p>

      <div class="pt-2 flex flex-wrap items-center gap-3">
        <a href="<?php echo e(route('customer.booking.create')); ?>"
          class="px-6 py-3.5 rounded-xl font-black text-xs sm:text-sm shadow-xl transition-all flex items-center gap-2 hover:opacity-90"
          style="background: linear-gradient(90deg,#D4AF37 0%,#B8960C 100%);color:#111;box-shadow:0 4px 20px #D4AF3740;">
          <i data-lucide="calendar-plus" class="w-4 h-4"></i>
          <span><?php echo e(__('Reservasi Sekarang')); ?></span>
        </a>
        <a href="<?php echo e(route('customer.services')); ?>"
          class="px-6 py-3.5 rounded-xl text-white font-bold text-xs sm:text-sm transition-all flex items-center gap-2 hover:bg-white/10"
          style="background:#1a1a1a;border:1px solid #D4AF3740;">
          <i data-lucide="grid" class="w-4 h-4" style="color:#D4AF37;"></i>
          <span><?php echo e(__('Lihat Menu Layanan')); ?></span>
        </a>
        <a href="<?php echo e(route('customer.profile')); ?>"
          class="px-6 py-3.5 rounded-xl text-gray-300 font-bold text-xs sm:text-sm transition-all flex items-center gap-2 hover:bg-white/10"
          style="background:#141414;border:1px solid #D4AF3725;">
          <i data-lucide="user-circle" class="w-4 h-4" style="color:#D4AF37;"></i>
          <span><?php echo e(__('Biodata Saya')); ?></span>
        </a>
      </div>
    </div>
  </div>

  <!-- Active Session / Booking Status Banner (If Any) -->
  <?php if($activeBooking): ?>
  <div class="p-6 rounded-3xl text-white shadow-2xl space-y-4"
       style="background:linear-gradient(135deg,#1c190d,#141414);border:1.5px solid #D4AF3750;">
    <div class="flex items-center justify-between pb-3 border-b" style="border-color:#D4AF3720;">
      <div class="flex items-center gap-3">
        <span class="relative flex h-3 w-3">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3" style="background:#D4AF37;"></span>
        </span>
        <h2 class="font-extrabold text-sm sm:text-base" style="color:#F5DF93;">
          <?php if($activeSession && $activeSession->status === 'running'): ?>
            <?php echo e(__('Perawatan Sedang Berlangsung')); ?>

          <?php else: ?>
            <?php echo e(__('Reservasi Terkonfirmasi')); ?>

          <?php endif; ?>
        </h2>
      </div>
      <span class="px-3 py-1 rounded-full text-[10px] font-extrabold tracking-wider uppercase"
            style="background:#D4AF3720;color:#D4AF37;border:1px solid #D4AF3740;">
        <?php echo e($activeBooking->booking_code); ?>

      </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
      <div>
        <span class="text-gray-400 text-[10px] font-bold block uppercase"><?php echo e(__('Layanan Spa')); ?></span>
        <p class="font-extrabold text-white text-sm mt-0.5"><?php echo e($activeBooking->service_name); ?></p>
      </div>
      <div>
        <span class="text-gray-400 text-[10px] font-bold block uppercase"><?php echo e(__('Therapist')); ?></span>
        <p class="font-extrabold text-white text-sm mt-0.5"><?php echo e($activeBooking->therapist_name); ?></p>
      </div>
      <div>
        <span class="text-gray-400 text-[10px] font-bold block uppercase"><?php echo e(__('Jadwal & Durasi')); ?></span>
        <p class="font-extrabold text-white text-sm mt-0.5"><?php echo e($activeBooking->booking_date->format('d M Y')); ?> - <?php echo e($activeBooking->booking_time); ?> (<?php echo e($activeBooking->duration); ?> min)</p>
      </div>
      <div>
        <span class="text-gray-400 text-[10px] font-bold block uppercase"><?php echo e(__('Total Biaya')); ?></span>
        <p class="font-black text-sm mt-0.5" style="color:#D4AF37;">AED <?php echo e(number_format($activeBooking->price, 2)); ?></p>
      </div>
    </div>

    <div class="pt-2 flex justify-end">
      <a href="<?php echo e(route('customer.booking.show', $activeBooking)); ?>"
        class="px-4 py-2 rounded-xl font-black text-xs transition-all flex items-center gap-1.5 shadow-md"
        style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
        <i data-lucide="eye" class="w-4 h-4"></i>
        <span><?php echo e(__('Lihat Detail Booking')); ?></span>
      </a>
    </div>
  </div>
  <?php endif; ?>

  <!-- Quick Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
    <div class="p-5 sm:p-6 rounded-2xl space-y-2 card-lift"
         style="background:#141414;border:1px solid #D4AF3725;">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider"><?php echo e(__('Kunjungan Bulan Ini')); ?></span>
        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center font-bold shrink-0"
             style="background:#D4AF3720;border:1px solid #D4AF3740;">
          <i data-lucide="calendar-check" class="w-4 h-4" style="color:#D4AF37;"></i>
        </div>
      </div>
      <p class="text-2xl sm:text-3xl font-black text-white"><?php echo e($monthVisits); ?> <span class="text-xs font-normal text-gray-500"><?php echo e(__('sesi')); ?></span></p>
    </div>

    <div class="p-5 sm:p-6 rounded-2xl space-y-2 card-lift"
         style="background:#141414;border:1px solid #D4AF3725;">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider"><?php echo e(__('Total Riwayat Booking')); ?></span>
        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center font-bold shrink-0"
             style="background:#D4AF3720;border:1px solid #D4AF3740;">
          <i data-lucide="history" class="w-4 h-4" style="color:#D4AF37;"></i>
        </div>
      </div>
      <p class="text-2xl sm:text-3xl font-black text-white"><?php echo e($bookings->count()); ?> <span class="text-xs font-normal text-gray-500"><?php echo e(__('reservasi')); ?></span></p>
    </div>

    <div class="p-5 sm:p-6 rounded-2xl space-y-2 card-lift"
         style="background:#141414;border:1px solid #D4AF3725;">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider"><?php echo e(__('Mata Uang')); ?></span>
        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center font-bold shrink-0"
             style="background:#D4AF3720;border:1px solid #D4AF3740;">
          <i data-lucide="coins" class="w-4 h-4" style="color:#D4AF37;"></i>
        </div>
      </div>
      <p class="text-xl sm:text-2xl font-black" style="color:#D4AF37;">AED <span class="text-xs font-normal text-gray-400">(<?php echo e(__('Dirham Dubai')); ?>)</span></p>
    </div>
  </div>

  <!-- Favorite Services Showcase -->
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
          <i data-lucide="sparkles" class="w-5 h-5 text-yellow-400"></i>
          <span><?php echo e(__('Layanan Populer')); ?></span>
        </h2>
        <p class="text-xs text-gray-400"><?php echo e(__('Rekomendasi perawatan spa terbaik di Dubai')); ?></p>
      </div>
      <a href="<?php echo e(route('customer.services')); ?>" class="text-xs font-bold transition-colors flex items-center gap-1"
         style="color:#D4AF37;">
        <span><?php echo e(__('Lihat Semua')); ?></span>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php $__currentLoopData = $favoriteServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="rounded-2xl overflow-hidden card-lift flex flex-col justify-between"
           style="background:#141414;border:1px solid #D4AF3725;">
        <div class="relative h-48 overflow-hidden">
          <img src="<?php echo e($svc->image_url); ?>" class="w-full h-full object-cover">
          <div class="absolute top-3 right-3 px-3 py-1 rounded-full bg-black/75 backdrop-blur-md text-yellow-400 text-xs font-bold flex items-center gap-1 border border-[#D4AF3740]">
            <i data-lucide="star" class="w-3.5 h-3.5 fill-yellow-400"></i>
            <span><?php echo e(number_format($svc->rating, 1)); ?></span>
          </div>
          <div class="absolute bottom-3 left-3 px-3 py-1 rounded-full bg-black/80 backdrop-blur-md text-white text-[11px] font-bold border border-[#D4AF3730]">
            <?php echo e(__($svc->category)); ?>

          </div>
        </div>

        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
          <div class="space-y-1.5">
            <h3 class="font-extrabold text-base text-white"><?php echo e(__($svc->name)); ?></h3>
            <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed"><?php echo e(__($svc->description)); ?></p>
          </div>

          <div class="pt-3 border-t flex items-center justify-between" style="border-color:#D4AF3715;">
            <div>
              <span class="text-[10px] text-gray-500 font-bold uppercase block"><?php echo e(__('Mulai dari')); ?> (<?php echo e($svc->duration); ?> min)</span>
              <span class="font-black text-base" style="color:#D4AF37;">AED <?php echo e(number_format($svc->price, 2)); ?></span>
            </div>
            <a href="<?php echo e(route('customer.booking.create', ['service_id' => $svc->id])); ?>"
              class="px-4 py-2 rounded-xl text-xs font-black shadow-md transition-all flex items-center gap-1 hover:opacity-90"
              style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
              <span><?php echo e(__('Booking')); ?></span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>

  <!-- Recent Bookings List -->
  <div class="p-6 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3725;">
    <div class="flex items-center justify-between border-b pb-4" style="border-color:#D4AF3715;">
      <div>
        <h2 class="text-base font-black text-white flex items-center gap-2">
          <i data-lucide="clock" class="w-4 h-4 text-yellow-400"></i>
          <span><?php echo e(__('Riwayat Reservasi Terakhir')); ?></span>
        </h2>
        <p class="text-xs text-gray-400"><?php echo e(__('Daftar booking terbaru Anda')); ?></p>
      </div>
      <a href="<?php echo e(route('customer.bookings')); ?>" class="text-xs font-bold hover:underline" style="color:#D4AF37;">
        <?php echo e(__('Lihat Riwayat Lengkap')); ?>

      </a>
    </div>

    <?php if($bookings->isEmpty()): ?>
      <div class="py-12 text-center space-y-3">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto"
             style="background:#D4AF3715;border:1px solid #D4AF3730;">
          <i data-lucide="calendar-x" class="w-8 h-8" style="color:#D4AF37;"></i>
        </div>
        <p class="text-xs font-bold text-gray-400"><?php echo e(__('Belum ada riwayat booking.')); ?></p>
        <a href="<?php echo e(route('customer.booking.create')); ?>" class="inline-block px-5 py-2.5 rounded-xl font-bold text-xs"
           style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          <?php echo e(__('Buat Booking Pertama')); ?>

        </a>
      </div>
    <?php else: ?>
      <div class="divide-y text-xs" style="border-color:#D4AF3710;">
        <?php $__currentLoopData = $bookings->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-extrabold shrink-0"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;">
              <i data-lucide="sparkles" class="w-5 h-5 text-yellow-400"></i>
            </div>
            <div>
              <span class="font-extrabold text-white block text-sm"><?php echo e($b->service_name); ?></span>
              <span class="text-gray-400 text-[11px]"><?php echo e(__('Therapist')); ?>: <?php echo e($b->therapist_name); ?> | <?php echo e($b->booking_date->format('d M Y')); ?> - <?php echo e($b->booking_time); ?></span>
            </div>
          </div>

          <div class="flex items-center gap-3 justify-between sm:justify-end">
            <span class="font-black text-sm" style="color:#D4AF37;">AED <?php echo e(number_format($b->price, 2)); ?></span>
            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase
              <?php if($b->status === 'completed'): ?> badge-completed
              <?php elseif($b->status === 'confirmed'): ?> badge-confirmed
              <?php elseif($b->status === 'in_progress'): ?> badge-busy
              <?php else: ?> badge-cancelled
              <?php endif; ?>">
              <?php echo e($b->status); ?>

            </span>
            <a href="<?php echo e(route('customer.booking.show', $b)); ?>" class="p-1.5 text-gray-400 hover:text-white">
              <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </a>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Azizah Qudsiyah\Desktop\spa-web\resources\views/customer/dashboard.blade.php ENDPATH**/ ?>