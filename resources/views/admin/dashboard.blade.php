@extends('layouts.admin')

@section('title', __('Dashboard') . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', __('Dashboard'))
@section('header_subtitle', __('Pantau kinerja bisnis, reservasi aktif, dan pendapatan spa'))

@section('content')
<div class="space-y-6">

  {{-- ── KPI Cards ─────────────────────────────────────── --}}
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

    {{-- Total Pendapatan --}}
    <div class="card-lift p-4 rounded-2xl space-y-2 col-span-2 sm:col-span-1"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #D4AF3740;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500">{{ __('Total Pendapatan') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
             style="background:#D4AF3720;border:1px solid #D4AF3740;">
          <i data-lucide="wallet" class="w-3.5 h-3.5" style="color:#D4AF37;"></i>
        </div>
      </div>
      <p class="text-lg font-black" style="color:#D4AF37;">AED {{ number_format($totalRevenue, 2) }}</p>
      <span class="text-[10px] text-green-400 font-semibold flex items-center gap-1">
        <i data-lucide="trending-up" class="w-3 h-3"></i> {{ __('Total Omset') }}
      </span>
    </div>

    {{-- Pengeluaran Bulan Ini --}}
    <div class="card-lift p-4 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #EF444430;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500">{{ __('Total Pengeluaran') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-950/60 border border-red-800/40">
          <i data-lucide="trending-down" class="w-3.5 h-3.5 text-red-400"></i>
        </div>
      </div>
      <p class="text-lg font-black text-red-400">AED {{ number_format($totalExpense, 2) }}</p>
      <span class="text-[10px] text-red-400 font-semibold">{{ now()->format('M Y') }}</span>
    </div>

    {{-- Laba Bersih --}}
    <div class="card-lift p-4 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid {{ $netProfit >= 0 ? '#22C55E30' : '#EF444430' }};">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500">{{ __('Laba Bersih') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $netProfit >= 0 ? 'bg-green-950/60 border border-green-800/40' : 'bg-red-950/60 border border-red-800/40' }}">
          <i data-lucide="circle-dollar-sign" class="w-3.5 h-3.5 {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}"></i>
        </div>
      </div>
      <p class="text-lg font-black {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">AED {{ number_format(abs($netProfit), 2) }}</p>
      <span class="text-[10px] font-semibold {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
        {{ $netProfit >= 0 ? ('✓ ' . __('Laba Bersih')) : ('✗ ' . __('Defisit')) }}
      </span>
    </div>

    {{-- Booking Hari Ini --}}
    <div class="card-lift p-4 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #3B82F630;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500">{{ __('Booking Hari Ini') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-950/60 border border-blue-800/40">
          <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-400"></i>
        </div>
      </div>
      <p class="text-lg font-black text-white">{{ $todayBookings }} <span class="text-xs font-normal text-gray-500">{{ __('sesi') }}</span></p>
      <span class="text-[10px] text-blue-400 font-semibold">{{ __('Aktif Hari Ini') }}</span>
    </div>

    {{-- Pelanggan Aktif --}}
    <div class="card-lift p-4 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #A855F730;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-500">{{ __('Pelanggan Aktif') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-purple-950/60 border border-purple-800/40">
          <i data-lucide="user-check" class="w-3.5 h-3.5 text-purple-400"></i>
        </div>
      </div>
      <p class="text-lg font-black text-white">{{ $activeClients }} <span class="text-xs font-normal text-gray-500">{{ __('orang') }}</span></p>
      <span class="text-[10px] text-purple-400 font-semibold">{{ __('Sesi Berjalan') }}</span>
    </div>

  </div>

  {{-- ── Charts Row ────────────────────────────────────── --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 p-5 rounded-2xl space-y-4"
         style="background:#141414;border:1px solid #D4AF3720;">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <h3 class="font-black text-white">{{ __('Grafik Pendapatan') }}</h3>
          <p class="text-[11px] text-gray-500">{{ __('Grafik omset harian spa') }}</p>
        </div>
        <div class="flex items-center gap-1 p-1 rounded-xl text-[11px] font-bold"
             style="background:#1a1a1a;border:1px solid #D4AF3720;">
          <button type="button" onclick="updateBladeRevenueChart('today')" id="btn-chart-today"
                  class="px-3 py-1.5 rounded-lg transition-all text-gray-500 hover:text-white">{{ __('Hari Ini') }}</button>
          <button type="button" onclick="updateBladeRevenueChart('week')" id="btn-chart-week"
                  class="px-3 py-1.5 rounded-lg transition-all text-gray-500 hover:text-white">{{ __('7 Hari') }}</button>
          <button type="button" onclick="updateBladeRevenueChart('month')" id="btn-chart-month"
                  class="px-3 py-1.5 rounded-lg transition-all text-gray-500 hover:text-white">{{ __('Bulan Ini') }}</button>
        </div>
      </div>
      <div class="h-56 sm:h-64 w-full relative">
        <canvas id="bladeRevenueChart"></canvas>
      </div>
    </div>

    {{-- Service Donut Chart --}}
    <div class="p-5 rounded-2xl space-y-4"
         style="background:#141414;border:1px solid #D4AF3720;">
      <div>
        <h3 class="font-black text-white">{{ __('Layanan Terpopuler') }}</h3>
        <p class="text-[11px] text-gray-500">{{ __('Porsi pemesanan layanan') }}</p>
      </div>
      <div class="h-56 sm:h-64 w-full relative">
        <canvas id="bladeServiceChart"></canvas>
      </div>
    </div>

  </div>

  {{-- ── Active Sessions ───────────────────────────────── --}}
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3715;">
      <div class="flex items-center gap-2">
        <span class="relative flex h-2.5 w-2.5">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
        </span>
        <h2 class="text-sm font-black text-white">{{ __('Monitor Sesi Aktif Saat Ini') }}</h2>
      </div>
      <span class="text-[11px] font-bold text-gray-500">{{ $activeSessions->count() }} {{ __('Sesi Berjalan') }}</span>
    </div>

    @if($activeSessions->isEmpty())
      <div class="py-8 text-center text-xs text-gray-600 font-semibold">
        {{ __('Saat ini tidak ada sesi treatment yang sedang berlangsung.') }}
      </div>
    @else
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($activeSessions as $ses)
        <div class="p-4 rounded-xl flex items-center justify-between gap-3"
             style="background:#1a1a1a;border:1px solid #D4AF3720;">
          <div class="space-y-1">
            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase"
                  style="background:#D4AF37;color:#111;">
              {{ $ses->booking->booking_code }}
            </span>
            <p class="font-bold text-xs text-white">{{ $ses->booking->service_name }}</p>
            <p class="text-[11px] text-gray-500">
              {{ $ses->booking->customer_name }} • {{ $ses->booking->therapist_name }}
            </p>
          </div>
          <div class="text-right shrink-0">
            <span class="text-[10px] font-bold uppercase block" style="color:#D4AF37;">{{ __('Jam Booking') }}</span>
            <span class="font-extrabold text-xs text-white">{{ $ses->booking->booking_time ?? ($ses->started_at ? $ses->started_at->format('H:i') : '-') }}</span>
          </div>
        </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- ── Recent Bookings ───────────────────────────────── --}}
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3715;">
      <div>
        <h2 class="text-sm font-black text-white">{{ __('Reservasi Terbaru') }}</h2>
        <p class="text-[11px] text-gray-500">{{ __('Daftar booking yang baru masuk') }}</p>
      </div>
      <a href="{{ Auth::user()->role === 'bos' ? route('bos.bookings') : route('admin.bookings') }}" class="text-xs font-bold transition-colors hover:opacity-80" style="color:#D4AF37;">
        {{ __('Lihat Semua') }} &rarr;
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="text-gray-600 uppercase tracking-wider text-[9px]" style="border-bottom:1px solid #D4AF3715;">
            <th class="py-2.5 px-2 text-center w-8">{{ __('No') }}</th>
            <th class="py-2.5 px-2">{{ __('Kode') }}</th>
            <th class="py-2.5 px-2">{{ __('Pelanggan') }}</th>
            <th class="py-2.5 px-2 hidden md:table-cell">{{ __('Layanan') }}</th>
            <th class="py-2.5 px-2 hidden lg:table-cell">{{ __('Therapist') }}</th>
            <th class="py-2.5 px-2 hidden sm:table-cell">{{ __('Jadwal') }}</th>
            <th class="py-2.5 px-2">{{ __('Harga') }}</th>
            <th class="py-2.5 px-2">{{ __('Status') }}</th>
            <th class="py-2.5 px-2 text-right">{{ __('Aksi') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($recentBookings as $b)
          <tr class="transition-colors hover:bg-white/5" style="border-bottom:1px solid #D4AF3710;">
            <td class="py-3 px-2 text-center font-bold text-gray-400 font-mono text-[11px]">{{ $loop->iteration }}</td>
            <td class="py-3 px-2 font-black text-[11px]">
              <a href="{{ Auth::user()->role === 'bos' ? route('bos.sessions', ['booking_code' => $b->booking_code]) : route('admin.sessions', ['booking_code' => $b->booking_code]) }}"
                 class="hover:underline hover:text-yellow-300 font-mono font-bold" style="color:#D4AF37;">
                {{ $b->booking_code }}
              </a>
            </td>
            <td class="py-3 px-2 font-bold text-white text-[11px]">{{ $b->customer_name }}</td>
            <td class="py-3 px-2 text-gray-400 text-[11px] hidden md:table-cell">{{ $b->service_name }}</td>
            <td class="py-3 px-2 text-gray-400 text-[11px] hidden lg:table-cell">{{ $b->therapist_name }}</td>
            <td class="py-3 px-2 text-gray-500 text-[11px] hidden sm:table-cell">{{ $b->booking_date->format('d M Y') }} {{ $b->booking_time }}</td>
            <td class="py-3 px-2 font-bold text-[11px] text-white">AED {{ number_format($b->price, 2) }}</td>
            <td class="py-3 px-2">
              <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase
                @if($b->status === 'completed') badge-completed
                @elseif($b->status === 'confirmed') badge-confirmed
                @elseif($b->status === 'in_progress') badge-busy
                @else badge-cancelled
                @endif">
                {{ __($b->status === 'confirmed' ? 'Confirmed' : ($b->status === 'in_progress' ? 'In Progress' : ($b->status === 'completed' ? 'Completed' : 'Cancelled'))) }}
              </span>
            </td>
            <td class="py-3 px-2 text-right">
              @if(Auth::user()->role === 'bos')
                <a href="{{ route('bos.orders.show', $b) }}" class="text-[10px] text-yellow-400 font-bold uppercase hover:underline">{{ __('Detail') }}</a>
              @else
                <form action="{{ route('admin.bookings.status', $b) }}" method="POST" class="inline-flex">
                  @csrf @method('PATCH')
                  @php
                    $hasRemaining = 0;
                    if ($b->activeSession && in_array($b->activeSession->status, ['running', 'paused'])) {
                        $hasRemaining = max(0, (int) $b->activeSession->remaining_seconds);
                    }
                  @endphp
                  <select name="status"
                    data-current="{{ $b->status }}"
                    data-remaining="{{ $hasRemaining }}"
                    onchange="handleStatusChange(this)"
                    class="text-[10px] py-1 px-2 rounded-lg font-bold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
                    <option value="confirmed" {{ $b->status === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                    <option value="in_progress" {{ $b->status === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                    <option value="completed" {{ $b->status === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="cancelled" {{ $b->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                  </select>
                </form>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  let bladeRevChartInstance = null;
  let bladeSrvChartInstance = null;

  Chart.defaults.color = '#9ca3af';
  Chart.defaults.font.family = "'Inter', sans-serif";
  Chart.defaults.borderColor = '#D4AF3715';

  const chartData = @json($chartData);

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
          label: '{{ __('Pendapatan (AED)') }}',
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
      : ['{{ __('Belum ada data') }}'];
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
@endpush
