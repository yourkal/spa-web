<?php $__env->startSection('title', __('Form Reservasi Spa') . ' - F55 CENTER DUBAI'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $firstAvailableTherapist = $therapists->first(function ($t) {
        $hasBooking = $t->bookings && $t->bookings->whereIn('status', ['confirmed', 'in_progress'])->isNotEmpty();
        return $t->status === 'ready' && !$hasBooking;
    });

    $bookingConfig = [
        'services' => $services->map(function ($s) {
            return [
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
                'benefits' => collect((array) $s->benefits)->map(fn($b) => __($b))->all(),
                'is_active' => (bool) $s->is_active,
            ];
        }),
        'therapists' => $therapists->map(function ($t) {
            $hasActiveBooking = $t->bookings && $t->bookings->whereIn('status', ['confirmed', 'in_progress'])->isNotEmpty();
            $isBooked = ($t->status === 'serving') || $hasActiveBooking;
            return [
                'id' => $t->id,
                'name' => $t->name,
                'speciality' => $t->speciality,
                'experience' => $t->experience,
                'rating' => (float) $t->rating,
                'avatar' => $t->avatar,
                'status' => $t->status,
                'status_label' => __($t->status_label),
                'is_active' => (bool) $t->is_active,
                'is_booked' => (bool) $isBooked,
                'bookings' => ($t->bookings ?? collect())->map(function ($b) {
                    return [
                        'date' => $b->booking_date ? $b->booking_date->format('Y-m-d') : null,
                        'time' => $b->booking_time,
                        'duration' => (int) $b->duration,
                        'status' => $b->status,
                    ];
                })->values()->all(),
            ];
        })->values()->all(),
        'selectedServiceId' => (int) old('spa_service_id', request('service_id', $selectedService->id ?? ($services->first()->id ?? 0))),
        'selectedTherapistId' => (int) old('therapist_id', $firstAvailableTherapist->id ?? ($therapists->where('status', 'ready')->first()->id ?? 0)),
        'duration' => (int) old('duration', request('duration', $selectedService->duration ?? 60)),
        'bookingDate' => (string) old('booking_date', date('Y-m-d')),
        'bookingTime' => (string) old('booking_time', '14:00'),
        'paymentMethod' => (string) old('payment_method', 'QRIS / E-Wallet'),
        'allLabel' => __('Semua'),
        'labels' => [
            'hours'           => __('Jam'),
            'minutes'         => __('Menit'),
            'confirmTitle'    => __('Konfirmasi Reservasi'),
            'serviceLabel'    => __('Layanan Spa'),
            'therapistLabel'  => __('Therapist'),
            'scheduleLabel'   => __('Jadwal & Durasi'),
            'durationLabel'   => __('Durasi Dipilih'),
            'paymentLabel'    => __('Metode Bayar'),
            'totalLabel'      => __('Total Pembayaran'),
            'confirmQuestion' => __('Apakah Anda yakin data reservasi spa sudah sesuai?'),
            'confirmBtn'      => __('Ya, Lanjutkan Booking'),
            'cancelBtn'       => __('Periksa Kembali'),
        ],
    ];
?>

<div class="w-full space-y-8" x-data="bookingApp()">

  <!-- Header -->
  <div class="p-6 sm:p-8 rounded-3xl relative overflow-hidden"
       style="background:linear-gradient(135deg,#16140d,#141414);border:1.5px solid #D4AF3740;box-shadow:0 20px 60px rgba(0,0,0,0.8);">
    <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#D4AF37,#F5DF93,#B8960C);"></div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold mb-3"
             style="background:#D4AF3715;border:1px solid #D4AF3740;color:#F5DF93;">
          <i data-lucide="calendar-plus" class="w-3.5 h-3.5 text-yellow-400"></i>
          F55 CENTER DUBAI
        </div>
        <h1 class="text-2xl sm:text-3xl font-display font-black text-white tracking-tight">
          <?php echo e(__('Form Reservasi & Booking Spa')); ?>

        </h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1"><?php echo e(__('Lengkapi detail pemesanan di bawah ini untuk mengonfirmasi sesi perawatan Anda')); ?></p>
      </div>
      <div class="px-4 py-2 rounded-xl text-xs font-extrabold flex items-center gap-2"
           style="background:#D4AF3715;border:1px solid #D4AF3740;color:#F5DF93;">
        <i data-lucide="shield-check" class="w-4 h-4" style="color:#D4AF37;"></i>
        <span><?php echo e(__('Konfirmasi Instan & Garansi Layanan')); ?></span>
      </div>
    </div>
  </div>

  <!-- Form -->
  <form id="booking-form" action="<?php echo e(route('customer.booking.store')); ?>" method="POST" @submit.prevent="confirmBooking()" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <?php echo csrf_field(); ?>

    <!-- LEFT COLUMN -->
    <div class="lg:col-span-7 xl:col-span-8 space-y-6">

      <!-- STEP 1: Pilih Layanan -->
      <div class="p-6 sm:p-8 rounded-3xl space-y-5 shadow-xl"
           style="background:#141414;border:1px solid #D4AF3730;">
        <div class="flex items-center justify-between border-b pb-4 gap-3" style="border-color:#D4AF3720;">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-sm shrink-0"
                 style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">1</div>
            <div>
              <h2 class="font-extrabold text-base text-white"><?php echo e(__('Pilih Layanan & Durasi')); ?></h2>
              <p class="text-xs text-gray-500"><?php echo e(__('Pilih jenis perawatan spa dan sesuaikan durasi yang diinginkan')); ?></p>
            </div>
          </div>
          <button type="button" @click="openServiceModal = true"
            class="px-3.5 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 shrink-0 shadow-md hover:opacity-90"
            style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
            <span x-text="selectedService ? '<?php echo e(__('Ganti Layanan')); ?>' : '<?php echo e(__('Pilih Layanan')); ?>'"></span>
          </button>
        </div>

        <!-- Hidden input for form submission -->
        <input type="hidden" name="spa_service_id" :value="selectedServiceId" required>

        <div class="space-y-4">
          <!-- Selected Service Card Display -->
          <template x-if="selectedService">
            <div class="p-4 sm:p-5 rounded-2xl relative overflow-hidden transition-all"
                 style="background:#1a1a1a;border:1.5px solid #D4AF3740;box-shadow:0 8px 24px rgba(0,0,0,0.5);">
              <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                <div class="flex items-start sm:items-center gap-4 min-w-0">
                  <img :src="selectedService.image_url || 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=400&q=80'" 
                       :alt="selectedService.name" 
                       class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl object-cover shrink-0" 
                       style="border:1.5px solid #D4AF3730;">
                  <div class="space-y-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                      <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                            style="background:#D4AF3720;border:1px solid #D4AF3740;color:#F5DF93;"
                            x-text="selectedService.category || 'Spa Treatment'"></span>
                      <div class="flex items-center gap-1 text-[11px] text-amber-400 font-extrabold">
                        <i data-lucide="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>
                        <span x-text="Number(selectedService.rating || 5.0).toFixed(1)"></span>
                      </div>
                    </div>
                    <h3 class="font-display font-black text-base sm:text-lg text-white truncate" x-text="selectedService.name"></h3>
                    <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed" x-text="selectedService.description"></p>
                    
                    <!-- Benefits tags if available -->
                    <template x-if="selectedService.benefits && selectedService.benefits.length">
                      <div class="flex flex-wrap gap-1.5 pt-1">
                        <template x-for="(b, idx) in selectedService.benefits.slice(0, 3)" :key="idx">
                          <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold"
                                style="background:#D4AF3710;border:1px solid #D4AF3725;color:#F5DF93;"
                                x-text="'✓ ' + b"></span>
                        </template>
                      </div>
                    </template>
                  </div>
                </div>

                <!-- Price and Quick Change Button -->
                <div class="w-full sm:w-auto flex sm:flex-col items-center sm:items-end justify-between gap-2 pt-3 sm:pt-0 border-t sm:border-t-0 border-neutral-800 shrink-0">
                  <div class="text-left sm:text-right">
                    <span class="text-[10px] text-gray-500 font-bold uppercase block"><?php echo e(__('Total Tarif')); ?></span>
                    <span class="font-black text-lg" style="color:#D4AF37;" x-text="formattedTotalPrice"></span>
                  </div>
                  <button type="button" @click="openServiceModal = true"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                    style="background:#111;border:1px solid #D4AF3740;color:#F5DF93;">
                    <i data-lucide="repeat" class="w-3 h-3 text-yellow-400"></i>
                    <span><?php echo e(__('Ganti Layanan')); ?></span>
                  </button>
                </div>
              </div>
            </div>
          </template>

          <!-- Fallback when no service selected -->
          <template x-if="!selectedService">
            <div @click="openServiceModal = true"
                 class="p-6 rounded-2xl text-center cursor-pointer transition-all border-dashed hover:border-yellow-400"
                 style="background:#1a1a1a;border:2px dashed #D4AF3750;">
              <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2"
                   style="background:#D4AF3720;color:#D4AF37;">
                <i data-lucide="plus" class="w-6 h-6"></i>
              </div>
              <p class="text-sm font-bold text-white"><?php echo e(__('Belum ada layanan yang dipilih')); ?></p>
              <p class="text-xs text-gray-500 mt-1"><?php echo e(__('Klik untuk membuka katalog dan memilih layanan spa Anda')); ?></p>
            </div>
          </template>

          <!-- Quick Package Tiers Selector (if available) -->
          <template x-if="availableTiers.length > 0">
            <div class="space-y-1.5 pt-1">
              <div class="flex items-center justify-between">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">
                  <?php echo e(__('Pilihan Paket Durasi & Tarif')); ?>

                </label>
                <span class="text-[10px] text-yellow-400/80"><?php echo e(__('Pilih paket cepat')); ?></span>
              </div>
              <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                <template x-for="tier in availableTiers" :key="tier.duration">
                  <button type="button" @click="setDuration(tier.duration)"
                    class="p-2.5 rounded-xl text-left transition-all relative flex flex-col justify-between"
                    :style="duration === tier.duration
                      ? 'background:#241d05;border:1.5px solid #D4AF37;box-shadow:0 0 12px #D4AF3730;'
                      : 'background:#1a1a1a;border:1px solid #D4AF3725;'">
                    <span class="text-xs font-black text-white" x-text="formatDurationMinutes(tier.duration)"></span>
                    <span class="text-[11px] font-extrabold mt-1" style="color:#D4AF37;" x-text="'AED ' + Number(tier.price).toLocaleString('en-AE', {minimumFractionDigits:2, maximumFractionDigits:2})"></span>
                  </button>
                </template>
              </div>
            </div>
          </template>

          <!-- Duration Controls -->
          <div class="p-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3"
               style="background:#1a1a1a;border:1px solid #D4AF3730;">
            <div>
              <span class="text-xs font-bold text-white block"><?php echo e(__('Atur Durasi Perawatan')); ?></span>
              <span class="text-[11px] text-gray-500"><?php echo e(__('Durasi fleksibel: 30 menit s/d 240 menit (tarif otomatis menyesuaikan)')); ?></span>
            </div>

            <div class="flex items-center gap-3 p-2 rounded-xl" style="background:#111;border:1px solid #D4AF3730;">
              <button type="button" @click="decreaseDuration()"
                class="w-8 h-8 rounded-xl font-black text-base flex items-center justify-center transition-all hover:bg-neutral-800"
                style="background:#1a1a1a;border:1px solid #D4AF3730;color:#D4AF37;">-</button>
              <span class="text-xs font-black min-w-[85px] text-center" style="color:#F5DF93;" x-text="formattedDuration"></span>
              <button type="button" @click="increaseDuration()"
                class="w-8 h-8 rounded-xl font-black text-base flex items-center justify-center transition-all hover:bg-neutral-800"
                style="background:#1a1a1a;border:1px solid #D4AF3730;color:#D4AF37;">+</button>
            </div>
          </div>
        </div>
      </div>

      <!-- STEP 2: Pilih Therapist -->
      <div class="p-6 sm:p-8 rounded-3xl space-y-5 shadow-xl"
           style="background:#141414;border:1px solid #D4AF3730;">
        <div class="flex items-center gap-3 border-b pb-4" style="border-color:#D4AF3720;">
          <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-sm"
               style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">2</div>
          <div>
            <h2 class="font-extrabold text-base text-white"><?php echo e(__('Pilih Therapist Professional')); ?></h2>
            <p class="text-xs text-gray-500"><?php echo e(__('Semua therapist kami bersertifikat dan berpengalaman')); ?></p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <?php $__currentLoopData = $therapists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
              $hasActiveBooking = $th->bookings && $th->bookings->whereIn('status', ['confirmed', 'in_progress'])->isNotEmpty();
              $isBookedInitially = ($th->status === 'serving') || $hasActiveBooking;
              $isReadyInitially = ($th->status === 'ready') && !$isBookedInitially;
            ?>
            <label @click="canSelectTherapist(<?php echo e($th->id); ?>) ? selectedTherapistId = <?php echo e($th->id); ?> : null"
              :class="[
                canSelectTherapist(<?php echo e($th->id); ?>) ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed pointer-events-none'
              ]"
              :style="selectedTherapistId == <?php echo e($th->id); ?> && canSelectTherapist(<?php echo e($th->id); ?>)
                ? 'background:#1c1900;border:1.5px solid #D4AF37;box-shadow:0 0 16px #D4AF3730;'
                : 'background:#1a1a1a;border:1px solid #D4AF3730;'"
              class="p-3.5 rounded-2xl flex items-center gap-3 transition-all relative">
              <input type="radio" name="therapist_id" value="<?php echo e($th->id); ?>" :disabled="!canSelectTherapist(<?php echo e($th->id); ?>)" x-model.number="selectedTherapistId" class="hidden">
              <img src="<?php echo e($th->avatar ?? 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=200&q=80'); ?>" class="w-12 h-12 rounded-xl object-cover shrink-0" style="border:1px solid #D4AF3730;">
              <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-1">
                  <p class="font-extrabold text-xs text-white truncate"><?php echo e($th->name); ?></p>

                  <!-- Dynamic status badge: Booked vs Normal -->
                  <template x-if="isTherapistBooked(<?php echo e($th->id); ?>)">
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase shrink-0"
                          style="background:#7f1d1d;color:#fca5a5;border:1px solid #ef444450;">
                      <?php echo e(__('Sudah Dibooking')); ?>

                    </span>
                  </template>
                  <template x-if="!isTherapistBooked(<?php echo e($th->id); ?>)">
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase shrink-0"
                      style="
                        <?php if($th->status === 'ready'): ?> background:#064E3B;color:#6EE7B7;border:1px solid #05966950;
                        <?php elseif($th->status === 'not_ready'): ?> background:#78350F;color:#FCD34D;border:1px solid #F59E0B50;
                        <?php elseif($th->status === 'serving'): ?> background:#7f1d1d;color:#fca5a5;border:1px solid #ef444450;
                        <?php else: ?> background:#1a1a1a;color:#6B7280;border:1px solid #37415130;
                        <?php endif; ?>
                      ">
                      <?php if($th->status === 'serving'): ?>
                        <?php echo e(__('Sudah Dibooking')); ?>

                      <?php else: ?>
                        <?php echo e(__($th->status_label)); ?>

                      <?php endif; ?>
                    </span>
                  </template>

                </div>
                <p class="text-[10px] text-gray-500 truncate"><?php echo e($th->speciality); ?></p>
                <div class="flex items-center gap-1 mt-0.5 text-[10px] text-amber-400 font-bold">
                  <i data-lucide="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>
                  <span><?php echo e(number_format($th->rating, 1)); ?></span>
                  <span class="text-gray-500">(<?php echo e($th->experience); ?> <?php echo e(__('Thn Exp')); ?>)</span>
                </div>
              </div>
            </label>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

      <!-- STEP 3: Jadwal & Waktu -->
      <div class="p-6 sm:p-8 rounded-3xl space-y-5 shadow-xl"
           style="background:#141414;border:1px solid #D4AF3730;">
        <div class="flex items-center gap-3 border-b pb-4" style="border-color:#D4AF3720;">
          <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-sm"
               style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">3</div>
          <div>
            <h2 class="font-extrabold text-base text-white"><?php echo e(__('Pilih Tanggal & Jam Kedatangan')); ?></h2>
            <p class="text-xs text-gray-500"><?php echo e(__('Tentukan waktu kunjungan yang sesuai dengan aktivitas Anda')); ?></p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('Tanggal Booking')); ?></label>
            <input type="date" name="booking_date" x-model="bookingDate" min="<?php echo e(date('Y-m-d')); ?>" required
              class="w-full px-4 py-3 rounded-xl text-xs font-bold outline-none transition-all"
              style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
              onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
          </div>

          <div class="space-y-1.5">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block"><?php echo e(__('Jam Kedatangan')); ?></label>
            <input type="time" name="booking_time" x-model="bookingTime" required
              class="w-full px-4 py-3 rounded-xl text-xs font-bold outline-none transition-all"
              style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
              onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
          </div>
        </div>
      </div>

      <!-- STEP 4: Metode Pembayaran -->
      <div class="p-6 sm:p-8 rounded-3xl space-y-5 shadow-xl"
           style="background:#141414;border:1px solid #D4AF3730;">
        <div class="flex items-center gap-3 border-b pb-4" style="border-color:#D4AF3720;">
          <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-sm"
               style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">4</div>
          <div>
            <h2 class="font-extrabold text-base text-white"><?php echo e(__('Pilih Metode Pembayaran')); ?></h2>
            <p class="text-xs text-gray-500"><?php echo e(__('Pembayaran dapat dilakukan langsung atau via e-wallet/bank')); ?></p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <?php $__currentLoopData = ['QRIS / E-Wallet', 'Transfer Bank', 'Cash / Tunai']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <label @click="paymentMethod = '<?php echo e($method); ?>'"
            :style="paymentMethod === '<?php echo e($method); ?>'
              ? 'background:#1c1900;border:1.5px solid #D4AF37;color:#F5DF93;'
              : 'background:#1a1a1a;border:1px solid #D4AF3730;color:#9ca3af;'"
            class="p-4 rounded-2xl text-center text-xs cursor-pointer transition-all flex flex-col items-center justify-center gap-2 font-bold">
            <input type="radio" name="payment_method" value="<?php echo e($method); ?>" x-model="paymentMethod" class="hidden">
            <i data-lucide="credit-card" class="w-5 h-5" style="color:#D4AF37;"></i>
            <span><?php echo e(__($method)); ?></span>
          </label>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

    </div>

    <!-- RIGHT COLUMN: Order Summary -->
    <div class="lg:col-span-5 xl:col-span-4 sticky top-24 space-y-6">

      <div class="rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 relative"
           style="background:linear-gradient(135deg,#1c1900,#141414);border:1.5px solid #D4AF3750;">
        <div class="absolute top-0 left-0 right-0 h-0.5 rounded-t-3xl" style="background:linear-gradient(90deg,#D4AF37,#F5DF93,#B8960C);"></div>

        <div class="border-b pb-4 flex items-center justify-between" style="border-color:#D4AF3720;">
          <div class="flex items-center gap-2">
            <i data-lucide="receipt" class="w-5 h-5 text-amber-400"></i>
            <h3 class="font-extrabold text-base text-white"><?php echo e(__('Ringkasan Booking')); ?></h3>
          </div>
          <span class="px-2.5 py-1 rounded-full text-[10px] font-bold"
                style="background:#D4AF3720;border:1px solid #D4AF3730;color:#D4AF37;">Live Quote</span>
        </div>

        <!-- Selected Service Preview -->
        <div class="flex items-center gap-3 p-3 rounded-2xl" style="background:#1a1a1a;border:1px solid #D4AF3730;">
          <img :src="selectedService ? selectedService.image_url : ''" class="w-14 h-14 rounded-xl object-cover shrink-0" style="border:1px solid #D4AF3730;">
          <div class="min-w-0 flex-1">
            <h4 class="font-extrabold text-xs text-white truncate" x-text="selectedService ? selectedService.name : ''"></h4>
            <p class="text-[10px] text-gray-400" x-text="selectedService ? selectedService.category : ''"></p>
          </div>
        </div>

        <!-- Order Breakdown -->
        <div class="space-y-3 text-xs border-b pb-4" style="border-color:#D4AF3720;">
          <div class="flex justify-between items-center text-gray-400">
            <span><?php echo e(__('Therapist')); ?>:</span>
            <strong class="text-white font-bold" x-text="selectedTherapist ? selectedTherapist.name : ''"></strong>
          </div>
          <div class="flex justify-between items-center text-gray-400">
            <span><?php echo e(__('Tanggal Kunjungan')); ?>:</span>
            <strong class="text-white font-bold" x-text="bookingDate"></strong>
          </div>
          <div class="flex justify-between items-center text-gray-400">
            <span><?php echo e(__('Jam Kedatangan')); ?>:</span>
            <strong class="text-white font-bold" x-text="bookingTime"></strong>
          </div>
          <div class="flex justify-between items-center text-gray-400">
            <span><?php echo e(__('Durasi Dipilih')); ?>:</span>
            <strong class="font-extrabold" style="color:#F5DF93;" x-text="formattedDuration"></strong>
          </div>
          <div class="flex justify-between items-center text-gray-400">
            <span><?php echo e(__('Metode Bayar')); ?>:</span>
            <strong class="text-white font-bold" x-text="paymentMethod"></strong>
          </div>
        </div>

        <!-- Total Price Display -->
        <div class="space-y-1">
          <span class="text-[10px] uppercase font-bold text-gray-500 tracking-wider"><?php echo e(__('Total Pembayaran')); ?></span>
          <div class="text-2xl sm:text-3xl font-black tracking-tight" style="color:#D4AF37;" x-text="formattedTotalPrice"></div>
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" name="duration" :value="duration">

        <!-- Submit -->
        <button type="submit"
          class="w-full py-4 rounded-2xl font-black text-sm shadow-xl transition-all flex items-center justify-center gap-2 hover:opacity-90"
          style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;box-shadow:0 4px 20px #D4AF3740;">
          <i data-lucide="check-circle" class="w-5 h-5"></i>
          <span><?php echo e(__('Konfirmasi & Booking Sekarang')); ?></span>
        </button>

      </div>

    </div>

  </form>

  <!-- MODAL PILIH LAYANAN SPA (MODERN & INFORMATIF) -->
  <div x-show="openServiceModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       @keydown.escape.window="openServiceModal = false"
       class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 overflow-y-auto"
       style="background:rgba(0,0,0,0.85);backdrop-filter:blur(10px);display:none;">

    <div x-show="openServiceModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         @click.outside="openServiceModal = false"
         class="w-full max-w-5xl rounded-3xl overflow-hidden shadow-2xl flex flex-col my-auto max-h-[90vh]"
         style="background:#141414;border:1.5px solid #D4AF3750;box-shadow:0 25px 70px rgba(0,0,0,0.9),0 0 50px rgba(212,175,55,0.15);">

      <!-- Modal Header -->
      <div class="p-5 sm:p-6 border-b relative shrink-0" style="background:linear-gradient(135deg,#16140d,#141414);border-color:#D4AF3725;">
        <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#D4AF37,#F5DF93,#B8960C);"></div>

        <div class="flex items-center justify-between gap-4 mb-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                 style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;">
              <i data-lucide="sparkles" class="w-5 h-5"></i>
            </div>
            <div>
              <h2 class="text-lg sm:text-xl font-display font-black text-white tracking-tight">
                <?php echo e(__('Katalog Menu Layanan Spa')); ?>

              </h2>
              <p class="text-xs text-gray-400"><?php echo e(__('Silakan pilih menu perawatan favorit Anda untuk melanjutkan booking')); ?></p>
            </div>
          </div>

          <button type="button" @click="openServiceModal = false"
            class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all cursor-pointer"
            style="background:#1a1a1a;border:1px solid #D4AF3730;">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>

        <!-- Search & Filter Controls -->
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
          <!-- Search input -->
          <div class="relative flex-1">
            <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-gray-500"></i>
            <input type="text" x-model="serviceSearch"
              placeholder="<?php echo e(__('Cari nama layanan atau deskripsi...')); ?>"
              class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
              style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
              onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
          </div>

          <!-- Category Filter Pills -->
          <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
            <template x-for="cat in categories" :key="cat.value">
              <button type="button" @click="selectedCategory = cat.value"
                class="px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all"
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
      <div class="p-5 sm:p-6 overflow-y-auto space-y-4 flex-1 scrollbar-thin" style="background:#0e0e0e;">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <template x-for="svc in filteredServices" :key="svc.id">
            <div class="rounded-2xl overflow-hidden flex flex-col justify-between transition-all group hover:border-yellow-400/60"
                 :style="selectedServiceId === Number(svc.id)
                   ? 'background:#191503;border:2px solid #D4AF37;box-shadow:0 0 20px #D4AF3730;'
                   : 'background:#141414;border:1px solid #D4AF3725;'">

              <!-- Image & Badges -->
              <div class="relative h-44 overflow-hidden">
                <img :src="svc.image_url || 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=600&q=80'"
                     :alt="svc.name"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0" style="background:linear-gradient(to top,#0a0a0aEE,transparent 60%);"></div>

                <!-- Rating badge -->
                <div class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-full backdrop-blur-md text-amber-400 text-[11px] font-black flex items-center gap-1"
                     style="background:rgba(0,0,0,0.75);border:1px solid #D4AF3740;">
                  <i data-lucide="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>
                  <span x-text="Number(svc.rating || 5.0).toFixed(1)"></span>
                </div>

                <!-- Category badge -->
                <div class="absolute bottom-2.5 left-2.5 px-2.5 py-0.5 rounded-full backdrop-blur-md text-[10px] font-bold"
                     style="background:#D4AF3725;border:1px solid #D4AF3740;color:#F5DF93;"
                     x-text="svc.category || 'Spa'">
                </div>

                <!-- Active selection indicator -->
                <template x-if="selectedServiceId === Number(svc.id)">
                  <div class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-full text-[10px] font-black flex items-center gap-1"
                       style="background:#D4AF37;color:#111;">
                    <i data-lucide="check" class="w-3 h-3"></i>
                    <span><?php echo e(__('Sedang Dipilih')); ?></span>
                  </div>
                </template>
              </div>

              <!-- Content Body -->
              <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                <div class="space-y-1.5">
                  <h4 class="font-display font-black text-sm sm:text-base text-white tracking-tight line-clamp-1" x-text="svc.name"></h4>
                  <p class="text-[11px] text-gray-400 line-clamp-2 leading-relaxed" x-text="svc.description"></p>

                  <!-- Benefits pills if any -->
                  <template x-if="svc.benefits && svc.benefits.length">
                    <div class="flex flex-wrap gap-1 pt-1">
                      <template x-for="(b, bIdx) in svc.benefits.slice(0, 2)" :key="bIdx">
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold"
                              style="background:#D4AF3710;border:1px solid #D4AF3720;color:#F5DF93;"
                              x-text="'✓ ' + b"></span>
                      </template>
                    </div>
                  </template>
                </div>

                <!-- Price, Duration & Action Button -->
                <div class="pt-3 border-t space-y-2.5" style="border-color:#D4AF3715;">
                  <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-400 flex items-center gap-1 text-[11px]">
                      <i data-lucide="clock" class="w-3 h-3 text-yellow-400"></i>
                      <span x-text="formatDurationMinutes(svc.duration)"></span>
                    </span>
                    <span class="font-black text-sm" style="color:#D4AF37;"
                          x-text="'AED ' + Number(svc.price).toLocaleString('en-AE', {minimumFractionDigits:2, maximumFractionDigits:2})">
                    </span>
                  </div>

                  <!-- Select Button -->
                  <button type="button" @click="selectService(svc)"
                    class="w-full py-2.5 px-3 rounded-xl font-black text-xs transition-all flex items-center justify-center gap-1.5 shadow-md hover:opacity-90 cursor-pointer"
                    :style="selectedServiceId === Number(svc.id)
                      ? 'background:#2b2203;border:1px solid #D4AF37;color:#F5DF93;'
                      : 'background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;'">
                    <i :data-lucide="selectedServiceId === Number(svc.id) ? 'check-circle' : 'plus-circle'" class="w-3.5 h-3.5"></i>
                    <span x-text="selectedServiceId === Number(svc.id) ? '<?php echo e(__('Layanan Terpilih')); ?>' : '<?php echo e(__('Pilih Layanan Ini')); ?>'"></span>
                  </button>
                </div>

              </div>
            </div>
          </template>
        </div>

        <!-- Empty Search Result -->
        <template x-if="filteredServices.length === 0">
          <div class="py-12 text-center space-y-2">
            <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto"
                 style="background:#D4AF3715;border:1px solid #D4AF3730;">
              <i data-lucide="search-x" class="w-6 h-6" style="color:#D4AF37;"></i>
            </div>
            <p class="text-xs font-bold text-white"><?php echo e(__('Tidak ada layanan yang sesuai kriteria pencarian.')); ?></p>
            <button type="button" @click="serviceSearch = ''; selectedCategory = 'ALL'"
              class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer"
              style="background:#D4AF37;color:#111;">
              <?php echo e(__('Reset Filter')); ?>

            </button>
          </div>
        </template>
      </div>

      <!-- Modal Footer -->
      <div class="p-4 border-t flex items-center justify-between text-xs shrink-0"
           style="background:#141414;border-color:#D4AF3720;">
        <span class="text-gray-400">
          <span class="text-white font-bold" x-text="filteredServices.length"></span> <?php echo e(__('layanan tersedia')); ?>

        </span>
        <button type="button" @click="openServiceModal = false"
          class="px-4 py-2 rounded-xl font-bold text-xs transition-all cursor-pointer"
          style="background:#1a1a1a;border:1px solid #D4AF3730;color:#9ca3af;">
          <?php echo e(__('Tutup')); ?>

        </button>
      </div>

    </div>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
window.__BOOKING_CONFIG__ = <?php echo json_encode($bookingConfig, 15, 512) ?>;

function bookingApp() {
  const cfg = window.__BOOKING_CONFIG__;
  return {
    services: cfg.services || [],
    therapists: cfg.therapists || [],
    selectedServiceId: Number(cfg.selectedServiceId),
    selectedTherapistId: Number(cfg.selectedTherapistId),
    duration: Number(cfg.duration) || 60,
    bookingDate: cfg.bookingDate,
    bookingTime: cfg.bookingTime,
    paymentMethod: cfg.paymentMethod,

    // Modal state
    openServiceModal: false,
    serviceSearch: '',
    selectedCategory: 'ALL',

    timeToMinutes(timeStr) {
      if (!timeStr) return 0;
      const parts = String(timeStr).split(':');
      return (parseInt(parts[0], 10) * 60) + parseInt(parts[1] || 0, 10);
    },

    isTherapistBooked(therapistId) {
      const th = (this.therapists || []).find(t => Number(t.id) === Number(therapistId));
      if (!th) return false;
      if (th.status === 'serving') return true;
      if (!th.bookings || !th.bookings.length) return Boolean(th.is_booked);

      const curDate = this.bookingDate;
      const curTime = this.bookingTime;
      const curDur = Number(this.duration) || 60;
      const curStart = this.timeToMinutes(curTime);
      const curEnd = curStart + curDur;

      return th.bookings.some(b => {
        if (!b.date || b.date !== curDate) return false;
        if (b.time && curTime) {
          const bStart = this.timeToMinutes(b.time);
          const bEnd = bStart + (Number(b.duration) || 60);
          return (curStart < bEnd && curEnd > bStart);
        }
        return true;
      });
    },

    canSelectTherapist(therapistId) {
      const th = (this.therapists || []).find(t => Number(t.id) === Number(therapistId));
      if (!th) return false;
      if (th.status !== 'ready') return false;
      return !this.isTherapistBooked(therapistId);
    },

    validateSelectedTherapist() {
      if (this.selectedTherapistId && !this.canSelectTherapist(this.selectedTherapistId)) {
        const available = (this.therapists || []).find(t => this.canSelectTherapist(t.id));
        this.selectedTherapistId = available ? Number(available.id) : 0;
      }
    },

    init() {
      this.$watch('openServiceModal', (val) => {
        if (val) {
          document.body.style.overflow = 'hidden';
          this.$nextTick(() => {
            if (window.lucide) window.lucide.createIcons();
          });
        } else {
          document.body.style.overflow = '';
        }
      });
      this.$watch('bookingDate', () => this.validateSelectedTherapist());
      this.$watch('bookingTime', () => this.validateSelectedTherapist());
      this.$watch('duration', () => this.validateSelectedTherapist());
      this.validateSelectedTherapist();

      this.$nextTick(() => {
        if (window.lucide) window.lucide.createIcons();
      });
    },

    get categories() {
      const allText = cfg.allLabel || '<?php echo e(__('Semua')); ?>';
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

    get selectedService() {
      return this.services.find(s => Number(s.id) === Number(this.selectedServiceId)) || this.services[0] || null;
    },

    get selectedTherapist() {
      return (this.therapists || []).find(t => Number(t.id) === Number(this.selectedTherapistId) && this.canSelectTherapist(t.id)) ||
             (this.therapists || []).find(t => this.canSelectTherapist(t.id)) || null;
    },

    get availableTiers() {
      if (!this.selectedService) return [];
      let tiers = this.selectedService.pricing_tiers;
      if (typeof tiers === 'string') {
        try { tiers = JSON.parse(tiers); } catch(e) { tiers = null; }
      }
      if (!tiers || typeof tiers !== 'object') return [];
      return Object.keys(tiers).map(d => ({
        duration: Number(d),
        price: Number(tiers[d]),
      })).sort((a, b) => a.duration - b.duration);
    },

    selectService(svc) {
      if (!svc) return;
      this.selectedServiceId = Number(svc.id);
      this.duration = Number(svc.duration) || 60;
      this.openServiceModal = false;
      this.$nextTick(() => {
        if (window.lucide) window.lucide.createIcons();
      });
    },

    setDuration(d) {
      this.duration = Number(d);
    },

    formatDurationMinutes(mins) {
      mins = Number(mins) || 60;
      const h = Math.floor(mins / 60);
      const m = mins % 60;
      if (h > 0 && m > 0) return `${h} ${cfg.labels.hours} ${m} ${cfg.labels.minutes}`;
      if (h > 0) return `${h} ${cfg.labels.hours}`;
      return `${m} ${cfg.labels.minutes}`;
    },

    get totalPrice() {
      if (!this.selectedService) return 0;
      let tiers = this.selectedService.pricing_tiers;
      if (typeof tiers === 'string') {
        try { tiers = JSON.parse(tiers); } catch(e) { tiers = null; }
      }
      if (tiers && tiers[this.duration] !== undefined) {
        return Number(tiers[this.duration]);
      }
      const baseDuration = Number(this.selectedService.duration) || 60;
      const basePrice = Number(this.selectedService.price) || 0;
      if (this.duration === baseDuration) return basePrice;
      const hourlyRate = Math.round((basePrice / baseDuration) * 60);
      if (this.duration % 60 === 0) {
        return (this.duration / 60) * hourlyRate;
      }
      const halfHourRate = Math.round(hourlyRate / 2);
      const hours = Math.floor(this.duration / 60);
      const hasHalfHour = (this.duration % 60) === 30;
      return (hours * hourlyRate) + (hasHalfHour ? halfHourRate : 0);
    },

    get formattedTotalPrice() {
      return 'AED ' + Number(this.totalPrice).toLocaleString('en-AE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },

    get formattedDuration() {
      return this.formatDurationMinutes(this.duration);
    },

    onServiceChange() {
      if (this.selectedService) {
        this.duration = Number(this.selectedService.duration) || 60;
      }
    },

    decreaseDuration() {
      if (this.duration > 30) this.duration -= 30;
    },

    increaseDuration() {
      if (this.duration < 240) this.duration += 30;
    },

    confirmBooking() {
      if (!this.selectedTherapist || !this.canSelectTherapist(this.selectedTherapist.id)) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'warning',
            title: '<?php echo e(__('Peringatan')); ?>',
            text: '<?php echo e(__('Terapis ini sudah dibooking atau sedang tidak tersedia. Silakan pilih terapis yang berstatus Ready.')); ?>',
            background: '#141414',
            color: '#fff',
            confirmButtonColor: '#D4AF37',
          });
        }
        return;
      }

      const sName = this.selectedService ? this.selectedService.name : '-';
      const tName = this.selectedTherapist ? this.selectedTherapist.name : '-';
      const bDate = this.bookingDate;
      const bTime = this.bookingTime;
      const dDuration = this.formattedDuration;
      const pMethod = this.paymentMethod;
      const tPrice = this.formattedTotalPrice;

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '✨ ' + cfg.labels.confirmTitle,
          html: `
            <div style="text-align:left;font-size:12px;background:#1a1a1a;border:1px solid #D4AF3740;padding:16px;border-radius:14px;color:#e5e7eb;margin-top:10px;">
              <div style="display:flex;justify-content:space-between;margin-bottom:8px;border-bottom:1px solid #D4AF3720;padding-bottom:6px;">
                <span style="color:#9ca3af;">${cfg.labels.serviceLabel}:</span>
                <b style="color:#fff;">${sName}</b>
              </div>
              <div style="display:flex;justify-content:space-between;margin-bottom:8px;border-bottom:1px solid #D4AF3720;padding-bottom:6px;">
                <span style="color:#9ca3af;">${cfg.labels.therapistLabel}:</span>
                <b style="color:#fff;">${tName}</b>
              </div>
              <div style="display:flex;justify-content:space-between;margin-bottom:8px;border-bottom:1px solid #D4AF3720;padding-bottom:6px;">
                <span style="color:#9ca3af;">${cfg.labels.scheduleLabel}:</span>
                <b style="color:#fff;">${bDate} (${bTime})</b>
              </div>
              <div style="display:flex;justify-content:space-between;margin-bottom:8px;border-bottom:1px solid #D4AF3720;padding-bottom:6px;">
                <span style="color:#9ca3af;">${cfg.labels.durationLabel}:</span>
                <b style="color:#F5DF93;">${dDuration}</b>
              </div>
              <div style="display:flex;justify-content:space-between;margin-bottom:8px;border-bottom:1px solid #D4AF3720;padding-bottom:6px;">
                <span style="color:#9ca3af;">${cfg.labels.paymentLabel}:</span>
                <b style="color:#fff;">${pMethod}</b>
              </div>
              <div style="display:flex;justify-content:space-between;margin-top:10px;padding-top:8px;border-top:1px dashed #D4AF3760;">
                <span style="color:#D4AF37;font-weight:bold;">${cfg.labels.totalLabel}:</span>
                <b style="color:#D4AF37;font-size:16px;font-weight:900;">${tPrice}</b>
              </div>
            </div>
            <p style="margin-top:14px;font-size:11px;color:#9ca3af;">${cfg.labels.confirmQuestion}</p>
          `,
          icon: 'question',
          background: '#141414',
          color: '#fff',
          showCancelButton: true,
          confirmButtonColor: '#D4AF37',
          cancelButtonColor: '#374151',
          confirmButtonText: '<span style="color:#111;font-weight:900">' + cfg.labels.confirmBtn + '</span>',
          cancelButtonText: cfg.labels.cancelBtn,
          reverseButtons: true,
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('booking-form').submit();
          }
        });
      } else {
        document.getElementById('booking-form').submit();
      }
    }
  };
}

if (window.Alpine) {
  Alpine.data('bookingApp', bookingApp);
} else {
  document.addEventListener('alpine:init', () => {
    Alpine.data('bookingApp', bookingApp);
  });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Azizah Qudsiyah\Desktop\spa-web\resources\views/customer/booking.blade.php ENDPATH**/ ?>