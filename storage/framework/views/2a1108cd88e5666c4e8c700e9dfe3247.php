<?php $__env->startSection('title', __('Dashboard') . ' - F55 MEN RELAXATION & MASSAGE CENTER'); ?>
<?php $__env->startSection('header_title', __('Dashboard')); ?>
<?php $__env->startSection('header_subtitle', __('Pantau kinerja bisnis, reservasi aktif, dan pendapatan spa')); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

  
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

    
    <div class="card-lift p-4 rounded-2xl space-y-2 col-span-2 sm:col-span-1"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #D4AF3740;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500"><?php echo e(__('Total Pendapatan')); ?></span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
             style="background:#D4AF3720;border:1px solid #D4AF3740;">
          <i data-lucide="wallet" class="w-3.5 h-3.5" style="color:#D4AF37;"></i>
        </div>
      </div>
      <p class="text-lg font-black" style="color:#D4AF37;">AED <?php echo e(number_format($totalRevenue, 2)); ?></p>
      <span class="text-[10px] text-green-400 font-semibold flex items-center gap-1">
        <i data-lucide="trending-up" class="w-3 h-3"></i> <?php echo e(__('Total Omset')); ?>

      </span>
    </div>

    
    <div class="card-lift p-4 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #EF444430;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500"><?php echo e(__('Total Pengeluaran')); ?></span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-950/60 border border-red-800/40">
          <i data-lucide="trending-down" class="w-3.5 h-3.5 text-red-400"></i>
        </div>
      </div>
      <p class="text-lg font-black text-red-400">AED <?php echo e(number_format($totalExpense, 2)); ?></p>
      <span class="text-[10px] text-red-400 font-semibold"><?php echo e(now()->format('M Y')); ?></span>
    </div>

    
    <div class="card-lift p-4 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid <?php echo e($netProfit >= 0 ? '#22C55E30' : '#EF444430'); ?>;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500"><?php echo e(__('Laba Bersih')); ?></span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center <?php echo e($netProfit >= 0 ? 'bg-green-950/60 border border-green-800/40' : 'bg-red-950/60 border border-red-800/40'); ?>">
          <i data-lucide="circle-dollar-sign" class="w-3.5 h-3.5 <?php echo e($netProfit >= 0 ? 'text-green-400' : 'text-red-400'); ?>"></i>
        </div>
      </div>
      <p class="text-lg font-black <?php echo e($netProfit >= 0 ? 'text-green-400' : 'text-red-400'); ?>">AED <?php echo e(number_format(abs($netProfit), 2)); ?></p>
      <span class="text-[10px] font-semibold <?php echo e($netProfit >= 0 ? 'text-green-400' : 'text-red-400'); ?>">
        <?php echo e($netProfit >= 0 ? ('✓ ' . __('Laba Bersih')) : ('✗ ' . __('Defisit'))); ?>

      </span>
    </div>

    
    <div class="card-lift p-4 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #3B82F630;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500"><?php echo e(__('Booking Hari Ini')); ?></span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-950/60 border border-blue-800/40">
          <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-400"></i>
        </div>
      </div>
      <p class="text-lg font-black text-white"><?php echo e($todayBookings); ?> <span class="text-xs font-normal text-gray-500"><?php echo e(__('sesi')); ?></span></p>
      <span class="text-[10px] text-blue-400 font-semibold"><?php echo e(__('Aktif Hari Ini')); ?></span>
    </div>

    
    <div class="card-lift p-4 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #A855F730;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500"><?php echo e(__('Pelanggan Aktif')); ?></span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-purple-950/60 border border-purple-800/40">
          <i data-lucide="user-check" class="w-3.5 h-3.5 text-purple-400"></i>
        </div>
      </div>
      <p class="text-lg font-black text-white"><?php echo e($activeClients); ?> <span class="text-xs font-normal text-gray-500"><?php echo e(__('orang')); ?></span></p>
      <span class="text-[10px] text-purple-400 font-semibold"><?php echo e(__('Sesi Berjalan')); ?></span>
    </div>

  </div>

  
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    
    <div class="lg:col-span-2 p-5 rounded-2xl space-y-4"
         style="background:#141414;border:1px solid #D4AF3720;">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <h3 class="font-black text-white"><?php echo e(__('Grafik Pendapatan')); ?></h3>
          <p class="text-[11px] text-gray-500"><?php echo e(__('Grafik omset harian spa')); ?></p>
        </div>
        <div class="flex items-center gap-1 p-1 rounded-xl text-[11px] font-bold"
             style="background:#1a1a1a;border:1px solid #D4AF3720;">
          <button type="button" onclick="updateBladeRevenueChart('today')" id="btn-chart-today"
                  class="px-3 py-1.5 rounded-lg transition-all text-gray-500 hover:text-white"><?php echo e(__('Hari Ini')); ?></button>
          <button type="button" onclick="updateBladeRevenueChart('week')" id="btn-chart-week"
                  class="px-3 py-1.5 rounded-lg transition-all text-gray-500 hover:text-white"><?php echo e(__('7 Hari')); ?></button>
          <button type="button" onclick="updateBladeRevenueChart('month')" id="btn-chart-month"
                  class="px-3 py-1.5 rounded-lg transition-all text-gray-500 hover:text-white"><?php echo e(__('Bulan Ini')); ?></button>
        </div>
      </div>
      <div class="h-56 sm:h-64 w-full relative">
        <canvas id="bladeRevenueChart"></canvas>
      </div>
    </div>

    
    <div class="p-5 rounded-2xl space-y-4"
         style="background:#141414;border:1px solid #D4AF3720;">
      <div>
        <h3 class="font-black text-white"><?php echo e(__('Layanan Terpopuler')); ?></h3>
        <p class="text-[11px] text-gray-500"><?php echo e(__('Porsi pemesanan layanan')); ?></p>
      </div>
      <div class="h-56 sm:h-64 w-full relative">
        <canvas id="bladeServiceChart"></canvas>
      </div>
    </div>

  </div>

  
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3715;">
      <div class="flex items-center gap-2">
        <span class="relative flex h-2.5 w-2.5">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
        </span>
        <h2 class="text-sm font-black text-white"><?php echo e(__('Monitor Sesi Aktif Saat Ini')); ?></h2>
      </div>
      <span class="text-[11px] font-bold text-gray-500"><?php echo e($activeSessions->count()); ?> <?php echo e(__('Sesi Berjalan')); ?></span>
    </div>

    <?php if($activeSessions->isEmpty()): ?>
      <div class="py-8 text-center text-xs text-gray-600 font-semibold">
        <?php echo e(__('Saat ini tidak ada sesi treatment yang sedang berlangsung.')); ?>

      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <?php $__currentLoopData = $activeSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ses): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="p-4 rounded-xl flex items-center justify-between gap-3"
             style="background:#1a1a1a;border:1px solid #D4AF3720;">
          <div class="space-y-1">
            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase"
                  style="background:#D4AF37;color:#111;">
              <?php echo e($ses->booking->booking_code); ?>

            </span>
            <p class="font-bold text-xs text-white"><?php echo e($ses->booking->service_name); ?></p>
            <p class="text-[11px] text-gray-500">
              <?php echo e($ses->booking->customer_name); ?> • <?php echo e($ses->booking->therapist_name); ?>

            </p>
          </div>
          <div class="text-right shrink-0">
            <span class="text-[10px] font-bold uppercase block" style="color:#D4AF37;"><?php echo e(__('Jam Booking')); ?></span>
            <span class="font-extrabold text-xs text-white"><?php echo e($ses->booking->booking_time ?? ($ses->started_at ? $ses->started_at->format('H:i') : '-')); ?></span>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
  </div>

  
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3715;">
      <div>
        <h2 class="text-sm font-black text-white"><?php echo e(__('Reservasi Terbaru')); ?></h2>
        <p class="text-[11px] text-gray-500"><?php echo e(__('Daftar booking yang baru masuk')); ?></p>
      </div>
      <a href="<?php echo e(Auth::user()->role === 'bos' ? route('bos.bookings') : route('admin.bookings')); ?>" class="text-xs font-bold transition-colors hover:opacity-80" style="color:#D4AF37;">
        <?php echo e(__('Lihat Semua')); ?> &rarr;
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="text-gray-600 uppercase tracking-wider text-[9px]" style="border-bottom:1px solid #D4AF3715;">
            <th class="py-2.5 px-2 text-center w-8"><?php echo e(__('No')); ?></th>
            <th class="py-2.5 px-2"><?php echo e(__('Kode')); ?></th>
            <th class="py-2.5 px-2"><?php echo e(__('Pelanggan')); ?></th>
            <th class="py-2.5 px-2 hidden md:table-cell"><?php echo e(__('Layanan')); ?></th>
            <th class="py-2.5 px-2 hidden lg:table-cell"><?php echo e(__('Therapist')); ?></th>
            <th class="py-2.5 px-2 hidden sm:table-cell"><?php echo e(__('Jadwal')); ?></th>
            <th class="py-2.5 px-2"><?php echo e(__('Harga')); ?></th>
            <th class="py-2.5 px-2"><?php echo e(__('Status')); ?></th>
            <th class="py-2.5 px-2 text-right"><?php echo e(__('Aksi')); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr class="transition-colors hover:bg-white/5" style="border-bottom:1px solid #D4AF3710;">
            <td class="py-3 px-2 text-center font-bold text-gray-400 font-mono text-[11px]"><?php echo e($loop->iteration); ?></td>
            <td class="py-3 px-2 font-black text-[11px]">
              <a href="<?php echo e(Auth::user()->role === 'bos' ? route('bos.sessions', ['booking_code' => $b->booking_code]) : route('admin.sessions', ['booking_code' => $b->booking_code])); ?>"
                 class="hover:underline hover:text-yellow-300 font-mono font-bold" style="color:#D4AF37;">
                <?php echo e($b->booking_code); ?>

              </a>
            </td>
            <td class="py-3 px-2 font-bold text-white text-[11px]"><?php echo e($b->customer_name); ?></td>
            <td class="py-3 px-2 text-gray-400 text-[11px] hidden md:table-cell"><?php echo e($b->service_name); ?></td>
            <td class="py-3 px-2 text-gray-400 text-[11px] hidden lg:table-cell"><?php echo e($b->therapist_name); ?></td>
            <td class="py-3 px-2 text-gray-500 text-[11px] hidden sm:table-cell"><?php echo e($b->booking_date->format('d M Y')); ?> <?php echo e($b->booking_time); ?></td>
            <td class="py-3 px-2 font-bold text-[11px] text-white">AED <?php echo e(number_format($b->price, 2)); ?></td>
            <td class="py-3 px-2">
              <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase
                <?php if($b->status === 'completed'): ?> badge-completed
                <?php elseif($b->status === 'confirmed'): ?> badge-confirmed
                <?php elseif($b->status === 'in_progress'): ?> badge-busy
                <?php else: ?> badge-cancelled
                <?php endif; ?>">
                <?php echo e(__($b->status === 'confirmed' ? 'Confirmed' : ($b->status === 'in_progress' ? 'In Progress' : ($b->status === 'completed' ? 'Completed' : 'Cancelled')))); ?>

              </span>
            </td>
            <td class="py-3 px-2 text-right">
              <?php if(Auth::user()->role === 'bos'): ?>
                <a href="<?php echo e(route('bos.orders.show', $b)); ?>" class="text-[10px] text-yellow-400 font-bold uppercase hover:underline"><?php echo e(__('Detail')); ?></a>
              <?php else: ?>
                <form action="<?php echo e(route('admin.bookings.status', $b)); ?>" method="POST" class="inline-flex">
                  <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                  <?php
                    $hasRemaining = 0;
                    if ($b->activeSession && in_array($b->activeSession->status, ['running', 'paused'])) {
                        $hasRemaining = max(0, (int) $b->activeSession->remaining_seconds);
                    }
                  ?>
                  <select name="status"
                    data-current="<?php echo e($b->status); ?>"
                    data-remaining="<?php echo e($hasRemaining); ?>"
                    onchange="handleStatusChange(this)"
                    class="text-[10px] py-1 px-2 rounded-lg font-bold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
                    <option value="confirmed" <?php echo e($b->status === 'confirmed' ? 'selected' : ''); ?>><?php echo e(__('Confirmed')); ?></option>
                    <option value="in_progress" <?php echo e($b->status === 'in_progress' ? 'selected' : ''); ?>><?php echo e(__('In Progress')); ?></option>
                    <option value="completed" <?php echo e($b->status === 'completed' ? 'selected' : ''); ?>><?php echo e(__('Completed')); ?></option>
                    <option value="cancelled" <?php echo e($b->status === 'cancelled' ? 'selected' : ''); ?>><?php echo e(__('Cancelled')); ?></option>
                  </select>
                </form>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  let bladeRevChartInstance = null;
  let bladeSrvChartInstance = null;

  Chart.defaults.color = '#9ca3af';
  Chart.defaults.font.family = "'Inter', sans-serif";
  Chart.defaults.borderColor = '#D4AF3715';

  const chartData = <?php echo json_encode($chartData, 15, 512) ?>;

  function updateBladeRevenueChart(filter) {
    const ctx = document.getElementById('bladeRevenueChart');
    if (!ctx) return;
    if (bladeRevChartInstance) bladeRevChartInstance.destroy();

    const allBtns = ['btn-chart-today','btn-chart-week','btn-chart-month'];
    allBtns.forEach(id => {
      const el = document.getElementById(id);
      if (el) el.className = 'px-3 py-1.5 rounded-lg transition-all text-gray-500 hover:text-white';
      if (el) el.style.cssText = '';
    });

    let labels = chartData.week.labels;
    let data   = chartData.week.data;
    let activeBtn = 'btn-chart-week';

    if (filter === 'today') {
      activeBtn = 'btn-chart-today';
      labels = chartData.today.labels;
      data   = chartData.today.data;
    } else if (filter === 'month') {
      activeBtn = 'btn-chart-month';
      labels = chartData.month.labels;
      data   = chartData.month.data;
    }

    const activeBtnEl = document.getElementById(activeBtn);
    if (activeBtnEl) activeBtnEl.style.cssText = 'background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;font-weight:900;border-radius:8px;padding:6px 12px;';

    const c2d = ctx.getContext('2d');
    const gradient = c2d.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(212,175,55,0.35)');
    gradient.addColorStop(1, 'rgba(212,175,55,0.0)');

    bladeRevChartInstance = new Chart(c2d, {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: '<?php echo e(__('Pendapatan (AED)')); ?>',
          data,
          borderColor: '#D4AF37',
          borderWidth: 3,
          backgroundColor: gradient,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#D4AF37',
          pointBorderColor: '#111',
          pointBorderWidth: 2,
          pointRadius: 5,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { color: '#D4AF3710' }, ticks: { color: '#6b7280' } },
          y: {
            grid: { color: '#D4AF3710' },
            ticks: {
              color: '#6b7280',
              callback: v => 'AED ' + Number(v).toLocaleString(),
            }
          }
        }
      }
    });
  }

  function initBladeServiceChart() {
    const ctx = document.getElementById('bladeServiceChart');
    if (!ctx) return;
    if (bladeSrvChartInstance) bladeSrvChartInstance.destroy();

    const labels = (chartData.services && chartData.services.labels && chartData.services.labels.length)
      ? chartData.services.labels
      : ['<?php echo e(__('Belum ada data')); ?>'];
    const data = (chartData.services && chartData.services.data && chartData.services.data.length)
      ? chartData.services.data
      : [0];

    bladeSrvChartInstance = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{
          data: data,
          backgroundColor: ['#D4AF37','#B8960C','#F5DF93','#92700A','#654C07','#E5C158'],
          borderWidth: 3,
          borderColor: '#141414',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              usePointStyle: true,
              padding: 12,
              color: '#9ca3af',
              font: { size: 10, weight: '700' }
            }
          }
        },
        cutout: '68%',
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    updateBladeRevenueChart('week');
    initBladeServiceChart();
  });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Azizah Qudsiyah\Desktop\spa-web\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>