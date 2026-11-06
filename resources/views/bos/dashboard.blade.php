@extends('layouts.admin')

@section('title', __('Dashboard Bos') . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', __('Executive Dashboard'))
@section('header_subtitle', __('Pantau kinerja bisnis F55 Men Relaxation & Massage Center secara menyeluruh'))

@section('content')
<div class="space-y-6">

  {{-- Bos Banner --}}
  <div class="p-5 rounded-2xl flex items-center gap-4"
       style="background:linear-gradient(135deg,#1a1500,#2a1f00);border:1px solid #D4AF3750;">
    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
         style="background:linear-gradient(135deg,#D4AF37,#B8960C);">
      <i data-lucide="crown" class="w-6 h-6" style="color:#111;"></i>
    </div>
    <div>
      <p class="font-display font-black text-lg" style="color:#D4AF37;">{{ __('Selamat datang') }}, {{ Auth::user()->name }}</p>
      <p class="text-xs text-gray-400">{{ __('Anda login sebagai') }} <strong class="text-yellow-500">{{ __('Bos') }}</strong> — {{ __('akses baca saja untuk semua data bisnis') }}</p>
    </div>
  </div>

  {{-- KPI Cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

    <div class="card-lift p-4 sm:p-5 rounded-2xl space-y-2 sm:space-y-3 col-span-2 sm:col-span-1"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #D4AF3730;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Total Pendapatan') }}</span>
        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center shrink-0" style="background:#D4AF3720;border:1px solid #D4AF3740;">
          <i data-lucide="wallet" class="w-4 h-4" style="color:#D4AF37;"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black" style="color:#D4AF37;">AED {{ number_format($totalRevenue, 2) }}</p>
      <span class="text-[10px] sm:text-[11px] text-green-400 font-semibold flex items-center gap-1">
        <i data-lucide="trending-up" class="w-3 h-3"></i> {{ __('Total Keseluruhan') }}
      </span>
    </div>

    <div class="card-lift p-4 sm:p-5 rounded-2xl space-y-2 sm:space-y-3"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #EF444430;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Pengeluaran Bulan Ini') }}</span>
        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center bg-red-950/50 border border-red-800/30 shrink-0">
          <i data-lucide="trending-down" class="w-4 h-4 text-red-400"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black text-red-400">AED {{ number_format($totalExpense, 2) }}</p>
      <span class="text-[10px] sm:text-[11px] text-red-400 font-semibold">{{ __('Bulan') }} {{ now()->isoFormat('MMMM YYYY') }}</span>
    </div>

    <div class="card-lift p-4 sm:p-5 rounded-2xl space-y-2 sm:space-y-3"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #22C55E30;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Laba Bersih') }}</span>
        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center bg-green-950/50 border border-green-800/30 shrink-0">
          <i data-lucide="circle-dollar-sign" class="w-4 h-4 text-green-400"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">AED {{ number_format(abs($netProfit), 2) }}</p>
      <span class="text-[10px] sm:text-[11px] font-semibold {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
        {{ $netProfit >= 0 ? '✓ ' . __('Untung') : '✗ ' . __('Rugi') }}
      </span>
    </div>

    <div class="card-lift p-4 sm:p-5 rounded-2xl space-y-2 sm:space-y-3"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #3B82F630;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Booking Hari Ini') }}</span>
        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center bg-blue-950/50 border border-blue-800/30 shrink-0">
          <i data-lucide="calendar" class="w-4 h-4 text-blue-400"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black text-white">{{ $todayBookings }} <span class="text-xs font-normal text-gray-500">{{ __('reservasi') }}</span></p>
      <span class="text-[10px] sm:text-[11px] text-blue-400 font-semibold">{{ __('Aktif Hari Ini') }}</span>
    </div>

  </div>

  {{-- Charts Row --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Revenue vs Expense Chart --}}
    <div class="lg:col-span-2 p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
      <div>
        <h3 class="font-black text-white">{{ __('Pendapatan vs Pengeluaran (6 Bulan)') }}</h3>
        <p class="text-[11px] text-gray-500">{{ __('Perbandingan omset dan pengeluaran per bulan') }}</p>
      </div>
      <div class="h-60 w-full relative">
        <canvas id="bosRevenueChart"></canvas>
      </div>
    </div>

    {{-- Layanan Terpopuler --}}
    <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
      <div>
        <h3 class="font-black text-white">{{ __('Layanan Terpopuler') }}</h3>
        <p class="text-[11px] text-gray-500">{{ __('Porsi pemesanan layanan') }}</p>
      </div>
      <div class="h-60 w-full relative">
        <canvas id="bosServiceChart"></canvas>
      </div>
    </div>

  </div>

  {{-- Active Sessions & Recent Bookings --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Active Sessions --}}
    <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
      <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3715;">
        <div class="flex items-center gap-2">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
          </span>
          <h2 class="text-sm font-black text-white">{{ __('Sesi Aktif Saat Ini') }}</h2>
        </div>
        <span class="text-[11px] font-bold text-gray-500">{{ $activeSessions->count() }} {{ __('Sesi') }}</span>
      </div>
      @if($activeSessions->isEmpty())
        <p class="py-6 text-center text-xs text-gray-600">{{ __('Tidak ada sesi aktif saat ini.') }}</p>
      @else
        <div class="space-y-2">
          @foreach($activeSessions as $ses)
          <div class="p-3 rounded-xl flex items-center justify-between gap-2" style="background:#1a1a1a;border:1px solid #D4AF3718;">
            <div>
              <span class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded-full" style="background:#D4AF37;color:#111;">{{ $ses->booking->booking_code }}</span>
              <p class="font-bold text-[11px] text-white mt-1">{{ $ses->booking->service_name }}</p>
              <p class="text-[10px] text-gray-500">{{ $ses->booking->customer_name }}</p>
            </div>
            <span class="text-[11px] font-black" style="color:#D4AF37;">{{ $ses->booking->booking_time ?? '-' }}</span>
          </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Recent Bookings (read-only) --}}
    <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
      <div class="pb-3" style="border-bottom:1px solid #D4AF3715;">
        <h2 class="text-sm font-black text-white">{{ __('Reservasi Terbaru') }}</h2>
        <p class="text-[11px] text-gray-500">{{ __('Data booking terkini (hanya lihat)') }}</p>
      </div>
      <div class="space-y-2">
        @foreach($recentBookings->take(6) as $b)
        <div class="flex items-center justify-between py-2" style="border-bottom:1px solid #D4AF3710;">
          <div>
            <p class="text-[11px] font-black" style="color:#D4AF37;">{{ $b->booking_code }}</p>
            <p class="text-[11px] text-white font-semibold">{{ $b->customer_name }}</p>
            <p class="text-[10px] text-gray-500">{{ $b->service_name }}</p>
          </div>
          <div class="text-right">
            <p class="text-[11px] font-bold text-white">AED {{ number_format($b->price, 2) }}</p>
            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase
              @if($b->status === 'completed') badge-completed
              @elseif($b->status === 'confirmed') badge-confirmed
              @elseif($b->status === 'in_progress') badge-busy
              @else badge-cancelled @endif">
              {{ $b->status }}
            </span>
          </div>
        </div>
        @endforeach
      </div>
    </div>

  </div>

</div>
@endsection

@push('scripts')
<script>
  Chart.defaults.color = '#6b7280';

  const revenueData = @json($revenueData);
  const serviceLabels = @json($serviceChartLabels ?? []);
  const serviceData = @json($serviceChartData ?? []);

  // Revenue vs Expense bar chart
  const bosRevCtx = document.getElementById('bosRevenueChart');
  if (bosRevCtx) {
    new Chart(bosRevCtx, {
      type: 'bar',
      data: {
        labels: revenueData.map(d => d.month),
        datasets: [
          {
            label: '{{ __("Pendapatan (AED)") }}',
            data: revenueData.map(d => d.revenue),
            backgroundColor: '#D4AF37CC',
            borderRadius: 6,
          },
          {
            label: '{{ __("Pengeluaran (AED)") }}',
            data: revenueData.map(d => d.expense),
            backgroundColor: '#EF444488',
            borderRadius: 6,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: { color: '#9ca3af', usePointStyle: true, padding: 16, font: { size: 10, weight: '700' } }
          }
        },
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

  // Service donut chart
  const bosSrvCtx = document.getElementById('bosServiceChart');
  if (bosSrvCtx) {
    const defaultLabels = ['{{ __("Belum Ada Data") }}'];
    const defaultData = [1];
    const finalLabels = serviceLabels.length > 0 ? serviceLabels : defaultLabels;
    const finalData = serviceData.length > 0 ? serviceData : defaultData;

    new Chart(bosSrvCtx, {
      type: 'doughnut',
      data: {
        labels: finalLabels,
        datasets: [{
          data: finalData,
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
            labels: { color: '#9ca3af', usePointStyle: true, padding: 10, font: { size: 10, weight: '700' } }
          }
        },
        cutout: '68%',
      }
    });
  }
</script>
@endpush
