<?php $__env->startSection('title', (Auth::user()->role === 'bos' ? __('Riwayat Booking') : __('Kelola Booking')) . ' - F55 MEN RELAXATION & MASSAGE CENTER'); ?>
<?php $__env->startSection('header_title', Auth::user()->role === 'bos' ? __('Riwayat Booking & Reservasi') : __('Kelola Reservasi Booking')); ?>
<?php $__env->startSection('header_subtitle', Auth::user()->role === 'bos' ? __('Pemantauan seluruh jadwal reservasi treatment spa pelanggan') : __('Pantau status jadwal, filter reservasi, dan input booking manual kasir')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="adminBookingApp()">

  
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h2 class="text-sm font-black text-white"><?php echo e(__('Daftar Seluruh Reservasi')); ?></h2>
          <?php if(isset($newBookingsCount) && $newBookingsCount > 0): ?>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 animate-pulse">
              <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
              <?php echo e($newBookingsCount); ?> <?php echo e(__('Order Baru')); ?>

            </span>
          <?php endif; ?>
        </div>
        <p class="text-[11px] text-gray-500"><?php echo e(__('Kelola reservasi treatment dari pelanggan atau kasir')); ?></p>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <a href="<?php echo e(Auth::user()->role === 'bos' ? route('bos.bookings.export.excel', request()->all()) : route('admin.bookings.export.excel', request()->all())); ?>"
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
           style="background:#1a1a1a;border:1px solid #10B98150;color:#34D399;">
          <i data-lucide="sheet" class="w-4 h-4 text-emerald-400"></i>
          <span><?php echo e(__('Unduh Excel')); ?></span>
        </a>

        <?php if(Auth::user()->role !== 'bos'): ?>
        <button @click="createModal = true"
                class="px-4 py-2.5 rounded-xl font-black text-xs shadow-md transition-all flex items-center gap-2 self-start sm:self-auto cursor-pointer"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          <i data-lucide="plus-circle" class="w-4 h-4"></i>
          <span><?php echo e(__('Tambah Booking Manual')); ?></span>
        </button>
        <?php endif; ?>
      </div>
    </div>

    
    <form action="<?php echo e(Auth::user()->role === 'bos' ? route('bos.bookings') : route('admin.bookings')); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2">
      <div>
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('Cari Kode / Pelanggan...')); ?>"
               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
               style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
      </div>

      <div>
        <select name="status"
                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
                style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          <option value="all" <?php echo e(request('status') === 'all' ? 'selected' : ''); ?>><?php echo e(__('Semua Status')); ?></option>
          <option value="confirmed" <?php echo e(request('status') === 'confirmed' ? 'selected' : ''); ?>><?php echo e(__('Confirmed')); ?></option>
          <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>><?php echo e(__('In Progress')); ?></option>
          <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>><?php echo e(__('Completed')); ?></option>
          <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>><?php echo e(__('Cancelled')); ?></option>
        </select>
      </div>

      <div>
        <input type="date" name="date" value="<?php echo e(request('date')); ?>"
               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
               style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
      </div>

      <div class="flex gap-2">
        <button type="submit"
                class="flex-1 py-2.5 rounded-xl font-bold text-xs transition-all cursor-pointer"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          <?php echo e(__('Terapkan Filter')); ?>

        </button>
        <?php if(request()->hasAny(['search', 'status', 'date'])): ?>
          <a href="<?php echo e(Auth::user()->role === 'bos' ? route('bos.bookings') : route('admin.bookings')); ?>"
             class="px-3 py-2.5 rounded-xl text-xs font-bold text-gray-400 hover:text-white flex items-center justify-center"
             style="background:#1a1a1a;border:1px solid #D4AF3730;">
            <?php echo e(__('Reset')); ?>

          </a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-xs">
        <thead>
          <tr class="border-b text-gray-400 uppercase text-[10px] tracking-wider" style="border-color:#D4AF3720;">
            <th class="py-2.5 px-3 text-center w-12"><?php echo e(__('No')); ?></th>
            <th class="py-2.5 px-3"><?php echo e(__('Kode Booking')); ?></th>
            <th class="py-2.5 px-3"><?php echo e(__('Pelanggan')); ?></th>
            <th class="py-2.5 px-3"><?php echo e(__('Layanan')); ?></th>
            <th class="py-2.5 px-3"><?php echo e(__('Terapis')); ?></th>
            <th class="py-2.5 px-3"><?php echo e(__('Jadwal Kunjungan')); ?></th>
            <th class="py-2.5 px-3"><?php echo e(__('Total (AED)')); ?></th>
            <th class="py-2.5 px-3"><?php echo e(__('Status')); ?></th>
            <th class="py-2.5 px-3 text-center"><?php echo e(__('Timer')); ?></th>
            <th class="py-2.5 px-3 text-right"><?php echo e(__('Aksi')); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <?php
            $isNewBooking = (bool) $b->is_new;
            $rowNo = (($bookings->currentPage() - 1) * $bookings->perPage()) + $loop->iteration;
            $isFuture = false;
            $bookingDateTime = null;
            if ($b->booking_date && $b->booking_time) {
                try {
                    $bookingDateTime = \Illuminate\Support\Carbon::parse($b->booking_date->format('Y-m-d') . ' ' . $b->booking_time);
                    $isFuture = now()->lt($bookingDateTime);
                } catch (\Exception $e) {}
            }
          ?>
          <tr class="transition-colors hover:bg-white/5 <?php echo e($isNewBooking ? 'bg-emerald-950/20' : ''); ?>"
              style="border-bottom:1px solid #D4AF3710; <?php echo e($isNewBooking ? 'border-left:3px solid #22c55e;' : ''); ?>">
            <td class="py-3 px-3 text-center font-bold text-gray-400 font-mono"><?php echo e($rowNo); ?></td>
            <td class="py-3 px-3">
              <a href="<?php echo e((Auth::user()->role === 'bos' ? route('bos.sessions') : route('admin.sessions'))); ?>?booking_code=<?php echo e($b->booking_code); ?>"
                 class="font-black text-xs hover:underline flex items-center gap-1.5 transition-colors group"
                 style="color:#D4AF37;"
                 title="<?php echo e(__('Buka Sesi & Timer :code', ['code' => $b->booking_code])); ?>">
                <span><?php echo e($b->booking_code); ?></span>
                <i data-lucide="arrow-up-right" class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity"></i>
              </a>
              <?php if($isNewBooking): ?>
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 mt-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                  <?php echo e(__('ORDER BARU')); ?>

                </span>
              <?php endif; ?>
            </td>
            <td class="py-3 px-3">
              <span class="font-bold text-white block"><?php echo e($b->customer_name); ?></span>
              <span class="text-[10px] text-gray-500"><?php echo e($b->customer_phone); ?></span>
            </td>
            <td class="py-3 px-3 text-gray-300 font-semibold"><?php echo e(__($b->service_name)); ?></td>
            <td class="py-3 px-3 text-gray-400"><?php echo e($b->therapist_name); ?></td>
            <td class="py-3 px-3 text-gray-400">
              <div class="flex items-center gap-1.5 flex-wrap">
                <span><?php echo e($b->booking_date->format('d M Y')); ?> - <?php echo e($b->booking_time); ?></span>
                <?php if($isFuture && $b->status === 'confirmed'): ?>
                  <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[8px] font-black bg-amber-500/20 text-amber-300 border border-amber-500/40"
                        title="<?php echo e(__('Jadwal booking belum tiba. Aksi dan timer terkunci sebelum jam ini.')); ?>">
                    <i data-lucide="lock" class="w-2.5 h-2.5"></i>
                    <?php echo e(__('Belum Jamnya')); ?>

                  </span>
                <?php endif; ?>
              </div>
              <span class="text-[10px] font-bold" style="color:#D4AF37;">(<?php echo e($b->duration); ?> <?php echo e(__('Menit')); ?>)</span>
            </td>
            <td class="py-3 px-3 font-black text-white">AED <?php echo e(number_format($b->price, 2)); ?></td>
            <td class="py-3 px-3">
              <?php if(Auth::user()->role === 'bos' || $b->status === 'completed'): ?>
                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase inline-flex items-center gap-1
                      <?php if($b->status === 'completed'): ?> badge-completed
                      <?php elseif($b->status === 'confirmed'): ?> badge-confirmed
                      <?php elseif($b->status === 'in_progress'): ?> badge-busy
                      <?php else: ?> badge-cancelled
                      <?php endif; ?>" title="<?php echo e($b->status === 'completed' ? __('Treatment telah selesai. Status terkunci permanen.') : ''); ?>">
                  <?php if($b->status === 'completed'): ?>
                    <i data-lucide="check-check" class="w-3 h-3 text-emerald-400"></i>
                  <?php endif; ?>
                  <span><?php echo e(strtoupper($b->status)); ?></span>
                </span>
              <?php else: ?>
                <form action="<?php echo e(route('admin.bookings.status', $b)); ?>" method="POST">
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('PATCH'); ?>
                  <?php
                    $hasRemaining = 0;
                    if ($b->activeSession && in_array($b->activeSession->status, ['running', 'paused'])) {
                        $hasRemaining = max(0, (int) $b->activeSession->remaining_seconds);
                    }
                  ?>
                  <select name="status"
                          data-current="<?php echo e($b->status); ?>"
                          data-remaining="<?php echo e($hasRemaining); ?>"
                          data-is-future="<?php echo e($isFuture ? '1' : '0'); ?>"
                          data-scheduled-at="<?php echo e($bookingDateTime ? $bookingDateTime->toIso8601String() : ''); ?>"
                          data-scheduled-time="<?php echo e($b->booking_time); ?>"
                          data-scheduled-date="<?php echo e($b->booking_date->format('d M Y')); ?>"
                          onchange="handleStatusChange(this)"
                          class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase outline-none cursor-pointer
                          <?php if($b->status === 'completed'): ?> badge-completed
                          <?php elseif($b->status === 'confirmed'): ?> badge-confirmed
                          <?php elseif($b->status === 'in_progress'): ?> badge-busy
                          <?php else: ?> badge-cancelled
                          <?php endif; ?>">
                    <option value="confirmed" <?php echo e($b->status === 'confirmed' ? 'selected' : ''); ?>><?php echo e(__('CONFIRMED')); ?></option>
                    <option value="in_progress" <?php echo e($b->status === 'in_progress' ? 'selected' : ''); ?> <?php echo e(($isFuture && $b->status !== 'in_progress') ? 'disabled' : ''); ?>>
                      <?php echo e(__('IN PROGRESS')); ?><?php echo e(($isFuture && $b->status !== 'in_progress') ? ' (' . __('Terkunci') . ')' : ''); ?>

                    </option>
                    <option value="completed" <?php echo e($b->status === 'completed' ? 'selected' : ''); ?> <?php echo e(($isFuture && $b->status !== 'completed') ? 'disabled' : ''); ?>>
                      <?php echo e(__('COMPLETED')); ?><?php echo e(($isFuture && $b->status !== 'completed') ? ' (' . __('Terkunci') . ')' : ''); ?>

                    </option>
                    <option value="cancelled" <?php echo e($b->status === 'cancelled' ? 'selected' : ''); ?>><?php echo e(__('CANCELLED')); ?></option>
                  </select>
                </form>
              <?php endif; ?>
            </td>
            <td class="py-3 px-3 text-center">
              <?php if($b->status === 'in_progress'): ?>
                <?php
                  $ses = $b->activeSession;
                  $rem = $ses ? max(0, (int) $ses->remaining_seconds) : 0;
                  $m = floor($rem / 60);
                  $s = $rem % 60;
                ?>
                <a href="<?php echo e((Auth::user()->role === 'bos' ? route('bos.sessions') : route('admin.sessions'))); ?>?booking_code=<?php echo e($b->booking_code); ?>"
                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black transition-all hover:scale-105 shadow-md"
                   style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;"
                   title="<?php echo e(__('Buka Timer Sesi Ini (:code)', ['code' => $b->booking_code])); ?>">
                  <i data-lucide="timer" class="w-3.5 h-3.5 animate-pulse"></i>
                  <span><?php echo e(sprintf('%02d:%02d', $m, $s)); ?></span>
                </a>
              <?php elseif($b->status === 'confirmed'): ?>
                <?php if($isFuture): ?>
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold text-gray-400 bg-neutral-900 border border-neutral-700 cursor-not-allowed"
                        title="<?php echo e(__('Booking dijadwalkan pukul :time. Timer belum dapat dimulai.', ['time' => $b->booking_time])); ?>">
                    <i data-lucide="lock" class="w-3 h-3 text-amber-400"></i>
                    <span><?php echo e(__('Terkunci')); ?> (<?php echo e($b->booking_time); ?>)</span>
                  </span>
                <?php else: ?>
                  <a href="<?php echo e((Auth::user()->role === 'bos' ? route('bos.sessions') : route('admin.sessions'))); ?>?booking_code=<?php echo e($b->booking_code); ?>"
                     class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold text-amber-300 hover:text-white bg-amber-950/40 border border-amber-500/30 transition-all hover:bg-amber-900/40"
                     title="<?php echo e(__('Sesi belum dimulai. Klik untuk cek timer')); ?>">
                    <i data-lucide="clock" class="w-3 h-3 text-amber-400"></i>
                    <span><?php echo e(__('Belum Aktif')); ?></span>
                  </a>
                <?php endif; ?>
              <?php elseif($b->status === 'completed'): ?>
                <a href="<?php echo e((Auth::user()->role === 'bos' ? route('bos.sessions') : route('admin.sessions'))); ?>?booking_code=<?php echo e($b->booking_code); ?>"
                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold text-emerald-400 bg-emerald-950/30 border border-emerald-500/20 hover:bg-emerald-900/30 transition-all"
                   title="<?php echo e(__('Sesi telah selesai')); ?>">
                  <i data-lucide="check" class="w-3 h-3"></i>
                  <span><?php echo e(__('Selesai')); ?></span>
                </a>
              <?php else: ?>
                <span class="text-gray-600 text-[11px]">-</span>
              <?php endif; ?>
            </td>
            <td class="py-3 px-3 text-right">
              <div class="flex items-center justify-end gap-1">
                <a href="<?php echo e(Auth::user()->role === 'bos' ? route('bos.orders.show', $b) : route('admin.bookings.show', $b)); ?>"
                   class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-xl transition-all"
                   title="<?php echo e(__('Detail Order')); ?>">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
                <?php if(Auth::user()->role !== 'bos'): ?>
                <form action="<?php echo e(route('admin.bookings.destroy', $b)); ?>" method="POST"
                      data-confirm="<?php echo e(__('Booking')); ?> <?php echo e($b->booking_code); ?> <?php echo e(__('akan dihapus permanen!')); ?>"
                      data-confirm-title="<?php echo e(__('Hapus Booking')); ?>" class="inline">
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('DELETE'); ?>
                  <button type="submit"
                          class="p-2 text-red-400 hover:text-red-300 hover:bg-red-950/40 rounded-xl transition-all cursor-pointer"
                          title="<?php echo e(__('Hapus Booking')); ?>">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="10" class="py-12 text-center text-xs text-gray-500 font-semibold">
              <?php echo e(__('Tidak ada data booking yang ditemukan.')); ?>

            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    
    <div class="pt-4" style="border-top:1px solid #D4AF3715;">
      <?php echo e($bookings->links()); ?>

    </div>
  </div>

  <?php if(Auth::user()->role !== 'bos'): ?>
  
  <div x-show="createModal" x-transition
       class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div @click.outside="createModal = false"
         class="rounded-2xl max-w-xl w-full p-6 sm:p-7 space-y-5 shadow-2xl relative"
         style="background:#141414;border:1px solid #D4AF3740;">

      <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3720;">
        <h3 class="font-black text-sm text-white flex items-center gap-2">
          <i data-lucide="plus-circle" class="w-4 h-4" style="color:#D4AF37;"></i>
          <?php echo e(__('Tambah Booking Manual')); ?> (<?php echo e(__('Kasir')); ?>/Admin)
        </h3>
        <button @click="createModal = false" class="text-gray-400 hover:text-white cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form action="<?php echo e(route('admin.bookings.store')); ?>" method="POST" class="space-y-4">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('Nama Pelanggan')); ?></label>
            <input type="text" name="customer_name" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('No. Telepon WhatsApp')); ?></label>
            <input type="text" name="customer_phone" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        
        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('Layanan Spa')); ?></label>
          <input type="hidden" name="spa_service_id" :value="selectedServiceId" required>

          <div class="p-3 rounded-xl flex items-center justify-between gap-3 transition-all"
               style="background:#1a1a1a;border:1.5px solid #D4AF3740;">
            <div class="flex items-center gap-3 min-w-0">
              <img :src="selectedService ? (selectedService.image_url || 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=200&q=80') : 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=200&q=80'"
                   class="w-12 h-12 rounded-xl object-cover shrink-0" style="border:1px solid #D4AF3730;">
              <div class="min-w-0">
                <p class="font-extrabold text-xs text-white truncate" x-text="selectedService ? selectedService.name : '<?php echo e(__('Belum ada layanan dipilih')); ?>'"></p>
                <div class="flex items-center gap-2 mt-0.5">
                  <span class="text-[11px] font-black" style="color:#D4AF37;" x-text="selectedService ? ('AED ' + Number(selectedService.price).toFixed(2) + ' / ' + selectedService.duration + ' min') : ''"></span>
                  <span class="text-[9px] px-2 py-0.2 rounded-full uppercase font-bold"
                        style="background:#D4AF3715;color:#F5DF93;"
                        x-text="selectedService ? (selectedService.category || 'Spa') : ''"></span>
                </div>
              </div>
            </div>
            <button type="button" @click="serviceModal = true"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 shadow cursor-pointer hover:opacity-90"
                    style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
              <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
              <span><?php echo e(__('Pilih / Ganti Layanan')); ?></span>
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('Therapist')); ?></label>
            <select name="therapist_id" required
                    class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
              <?php $__currentLoopData = $therapists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($th->id); ?>"><?php echo e($th->name); ?> (<?php echo e($th->speciality); ?>)</option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('Metode Pembayaran')); ?></label>
            <select name="payment_method" required
                    class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
              <option value="Cash"><?php echo e(__('Cash')); ?></option>
              <option value="Card"><?php echo e(__('Card')); ?></option>
              <option value="Transfer"><?php echo e(__('Transfer')); ?></option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('Tanggal Kunjungan')); ?></label>
            <input type="date" name="booking_date" value="<?php echo e(date('Y-m-d')); ?>" required
                   class="w-full px-3 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('Jam Kunjungan')); ?></label>
            <input type="time" name="booking_time" value="14:00" required
                   class="w-full px-3 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('Durasi (Menit)')); ?></label>
            <input type="number" name="duration" x-model.number="duration" min="30" step="30" required
                   class="w-full px-3 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        <button type="submit"
                class="w-full py-3 rounded-xl font-black text-xs shadow-md transition-all mt-2 cursor-pointer hover:opacity-95"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          <?php echo e(__('Simpan Booking Manual')); ?>

        </button>
      </form>
    </div>
  </div>

  
  <div x-show="serviceModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       @keydown.escape.window="serviceModal = false"
       class="fixed inset-0 z-[60] flex items-center justify-center p-3 sm:p-6 overflow-y-auto"
       style="background:rgba(0,0,0,0.85);backdrop-filter:blur(8px);display:none;">

    <div x-show="serviceModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.outside="serviceModal = false"
         class="w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl flex flex-col my-auto max-h-[88vh]"
         style="background:#141414;border:1.5px solid #D4AF3750;">

      <!-- Modal Header -->
      <div class="p-5 border-b relative shrink-0" style="background:#16140d;border-color:#D4AF3725;">
        <div class="flex items-center justify-between gap-4 mb-3">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg"
                 style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;">
              <i data-lucide="sparkles" class="w-4 h-4"></i>
            </div>
            <div>
              <h3 class="text-base font-display font-black text-white tracking-tight">
                <?php echo e(__('Pilih Layanan Spa untuk Booking')); ?>

              </h3>
              <p class="text-[11px] text-gray-400"><?php echo e(__('Pilih salah satu menu perawatan spa dari katalog')); ?></p>
            </div>
          </div>

          <button type="button" @click="serviceModal = false"
            class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all cursor-pointer"
            style="background:#1a1a1a;border:1px solid #D4AF3730;">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        </div>

        <!-- Search & Filter Controls -->
        <div class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center justify-between">
          <div class="relative flex-1">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-gray-500"></i>
            <input type="text" x-model="serviceSearch"
              placeholder="<?php echo e(__('Cari nama layanan...')); ?>"
              class="w-full pl-9 pr-3 py-2 rounded-xl text-xs font-semibold outline-none transition-all"
              style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;">
          </div>

          <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
            <template x-for="cat in categories" :key="cat.value">
              <button type="button" @click="selectedCategory = cat.value"
                class="px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer"
                :style="selectedCategory === cat.value
                  ? 'background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;'
                  : 'background:#1a1a1a;border:1px solid #D4AF3730;color:#9ca3af;'"
                x-text="cat.label">
              </button>
            </template>
          </div>
        </div>
      </div>

      <!-- Modal Body (Cards Grid) -->
      <div class="p-5 overflow-y-auto space-y-3 flex-1 scrollbar-thin" style="background:#0e0e0e;">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
          <template x-for="svc in filteredServices" :key="svc.id">
            <div class="rounded-2xl overflow-hidden flex flex-col justify-between transition-all group hover:border-yellow-400/60 cursor-pointer"
                 @click="selectService(svc)"
                 :style="selectedServiceId === Number(svc.id)
                   ? 'background:#191503;border:2px solid #D4AF37;box-shadow:0 0 16px #D4AF3730;'
                   : 'background:#141414;border:1px solid #D4AF3725;'">

              <div class="relative h-32 overflow-hidden">
                <img :src="svc.image_url || 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=400&q=80'"
                     :alt="svc.name"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0" style="background:linear-gradient(to top,#0a0a0aEE,transparent 60%);"></div>

                <div class="absolute top-2 right-2 px-2 py-0.5 rounded-full backdrop-blur-md text-amber-400 text-[10px] font-black flex items-center gap-1"
                     style="background:rgba(0,0,0,0.75);border:1px solid #D4AF3740;">
                  <i data-lucide="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>
                  <span x-text="Number(svc.rating || 5.0).toFixed(1)"></span>
                </div>

                <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-full backdrop-blur-md text-[9px] font-bold"
                     style="background:#D4AF3725;border:1px solid #D4AF3740;color:#F5DF93;"
                     x-text="svc.category || 'Spa'">
                </div>
              </div>

              <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                <div>
                  <h4 class="font-extrabold text-xs text-white truncate" x-text="svc.name"></h4>
                  <p class="text-[10px] text-gray-400 line-clamp-1 leading-relaxed mt-0.5" x-text="svc.description"></p>
                </div>

                <div class="pt-2 border-t flex items-center justify-between text-xs" style="border-color:#D4AF3715;">
                  <span class="text-gray-400 text-[10px]" x-text="svc.duration + ' <?php echo e(__('Menit')); ?>'"></span>
                  <span class="font-black text-xs" style="color:#D4AF37;"
                        x-text="'AED ' + Number(svc.price).toLocaleString('en-AE', {minimumFractionDigits:2, maximumFractionDigits:2})">
                  </span>
                </div>

                <button type="button" @click.stop="selectService(svc)"
                        class="w-full py-2 px-2.5 rounded-xl font-black text-xs transition-all flex items-center justify-center gap-1 shadow cursor-pointer hover:opacity-90"
                        :style="selectedServiceId === Number(svc.id)
                          ? 'background:#2b2203;border:1px solid #D4AF37;color:#F5DF93;'
                          : 'background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;'">
                  <i :data-lucide="selectedServiceId === Number(svc.id) ? 'check-circle' : 'plus-circle'" class="w-3.5 h-3.5"></i>
                  <span x-text="selectedServiceId === Number(svc.id) ? '<?php echo e(__('Layanan Terpilih')); ?>' : '<?php echo e(__('Pilih Layanan Ini')); ?>'"></span>
                </button>
              </div>
            </div>
          </template>
        </div>
      </div>

      <div class="p-3.5 border-t flex items-center justify-between text-xs shrink-0"
           style="background:#141414;border-color:#D4AF3720;">
        <span class="text-gray-400">
          <span class="text-white font-bold" x-text="filteredServices.length"></span> <?php echo e(__('layanan tersedia')); ?>

        </span>
        <button type="button" @click="serviceModal = false"
                class="px-4 py-1.5 rounded-xl font-bold text-xs transition-all cursor-pointer"
                style="background:#1a1a1a;border:1px solid #D4AF3730;color:#9ca3af;">
          <?php echo e(__('Tutup')); ?>

        </button>
      </div>

    </div>
  </div>
  <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php
  $adminServicesJson = $services->map(fn($s) => [
      'id' => $s->id,
      'name' => __($s->name),
      'raw_name' => $s->name,
      'category' => __($s->category),
      'raw_category' => $s->category,
      'description' => __($s->description),
      'duration' => (int) $s->duration,
      'price' => (float) $s->price,
      'pricing_tiers' => $s->pricing_tiers,
      'image_url' => $s->image_url,
      'rating' => (float) ($s->rating ?? 5.0),
      'is_active' => (bool) $s->is_active,
  ]);
?>
<script>
function adminBookingApp() {
  const allServices = <?php echo json_encode($adminServicesJson ?? [], 15, 512) ?>;
  const initialId = allServices.length > 0 ? Number(allServices[0].id) : 0;
  const initialDuration = allServices.length > 0 ? (Number(allServices[0].duration) || 60) : 60;

  return {
    createModal: false,
    serviceModal: false,
    services: allServices,
    selectedServiceId: initialId,
    duration: initialDuration,
    serviceSearch: '',
    selectedCategory: 'ALL',

    init() {
      this.$watch('serviceModal', (val) => {
        if (val) {
          this.$nextTick(() => {
            if (window.lucide) window.lucide.createIcons();
          });
        }
      });
      this.$watch('createModal', (val) => {
        if (val) {
          this.$nextTick(() => {
            if (window.lucide) window.lucide.createIcons();
          });
        }
      });
    },

    get selectedService() {
      return this.services.find(s => Number(s.id) === Number(this.selectedServiceId)) || this.services[0] || null;
    },

    get categories() {
      const allText = '<?php echo e(__('Semua')); ?>';
      const cats = [{ label: allText, value: 'ALL' }];
      (this.services || []).forEach(s => {
        const catVal = s.raw_category || s.category;
        const catLabel = s.category;
        if (catVal && !cats.some(c => c.value === catVal)) {
          cats.push({ label: catLabel, value: catVal });
        }
      });
      return cats;
    },

    get filteredServices() {
      return (this.services || []).filter(s => {
        const matchCat = this.selectedCategory === 'ALL' || (s.raw_category || s.category) === this.selectedCategory;
        const q = (this.serviceSearch || '').toLowerCase().trim();
        const matchSearch = !q ||
          (s.name && s.name.toLowerCase().includes(q)) ||
          (s.raw_name && s.raw_name.toLowerCase().includes(q)) ||
          (s.description && s.description.toLowerCase().includes(q));
        return matchCat && matchSearch;
      });
    },

    selectService(svc) {
      if (!svc) return;
      this.selectedServiceId = Number(svc.id);
      this.duration = Number(svc.duration) || 60;
      this.serviceModal = false;
      this.$nextTick(() => {
        if (window.lucide) window.lucide.createIcons();
      });
    }
  };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Azizah Qudsiyah\Desktop\spa-web\resources\views/admin/bookings.blade.php ENDPATH**/ ?>