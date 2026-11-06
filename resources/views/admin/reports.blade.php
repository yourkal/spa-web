@extends('layouts.admin')

@section('title', __('Laporan Keuangan') . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', __('Laporan & Pembukuan Rekap'))
@section('header_subtitle', __('Analisis omset, pengeluaran, laba bersih, dan cetak bukti transaksi'))

@section('content')
<div class="space-y-6">

  {{-- ── Filter & Action Bar (Hidden on Print) ─────────────────────────── --}}
  <div class="p-5 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 no-print"
       style="background:#141414;border:1px solid #D4AF3725;">
    <form action="{{ route('admin.reports') }}" method="GET" class="flex flex-wrap items-end gap-3">
      <div class="space-y-1">
        <label class="text-[10px] font-bold text-gray-500 uppercase block tracking-wider">{{ __('Dari Tanggal') }}</label>
        <input type="date" name="start_date" value="{{ $startDate }}"
               class="px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
               style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
      </div>
      <div class="space-y-1">
        <label class="text-[10px] font-bold text-gray-500 uppercase block tracking-wider">{{ __('Sampai Tanggal') }}</label>
        <input type="date" name="end_date" value="{{ $endDate }}"
               class="px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
               style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
      </div>
      <div>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl font-black text-xs transition-all"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          <i data-lucide="filter" class="w-3.5 h-3.5 inline mr-1"></i> {{ __('Tampilkan') }}
        </button>
      </div>
    </form>

    <div class="flex flex-wrap items-center gap-2">
      <a href="{{ route('admin.reports.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
         class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
         style="background:#1a1a1a;border:1px solid #10B98150;color:#34D399;">
        <i data-lucide="sheet" class="w-4 h-4 text-emerald-400"></i>
        <span>{{ __('Unduh Excel') }}</span>
      </a>

      <a href="{{ route('admin.reports.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
         class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
         style="background:#1a1a1a;border:1px solid #EF444450;color:#F87171;">
        <i data-lucide="file-down" class="w-4 h-4 text-red-400"></i>
        <span>{{ __('Unduh PDF') }}</span>
      </a>

      <button type="button" onclick="window.print()"
              class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
              style="background:#1a1a1a;border:1px solid #D4AF3750;color:#D4AF37;">
        <i data-lucide="printer" class="w-4 h-4"></i>
        <span>{{ __('Cetak (Browser)') }}</span>
      </button>
    </div>
  </div>

  {{-- ── KPI Cards ─────────────────────────────────────────────────────── --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

    {{-- Total Omset --}}
    <div class="card-lift p-4 sm:p-5 rounded-2xl space-y-2 col-span-2 sm:col-span-1"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #D4AF3740;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Total Omset Kotor') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#D4AF3720;">
          <i data-lucide="wallet" class="w-4 h-4" style="color:#D4AF37;"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black" style="color:#D4AF37;">AED {{ number_format($totalRevenue, 2) }}</p>
      <span class="text-[10px] sm:text-[11px] text-gray-400 font-semibold">{{ $totalBookings }} {{ __('transaksi treatment') }}</span>
    </div>

    {{-- Total Pengeluaran --}}
    <div class="card-lift p-4 sm:p-5 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #EF444430;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Total Pengeluaran') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-950/50 shrink-0">
          <i data-lucide="trending-down" class="w-4 h-4 text-red-400"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black text-red-400">AED {{ number_format($totalExpenses, 2) }}</p>
      <span class="text-[10px] sm:text-[11px] text-red-400 font-semibold">{{ __('Biaya Operasional') }}</span>
    </div>

    {{-- Laba Bersih --}}
    <div class="card-lift p-4 sm:p-5 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid {{ $netProfit >= 0 ? '#22C55E30' : '#EF444430' }};">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Laba Bersih') }} (Net)</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $netProfit >= 0 ? 'bg-green-950/50' : 'bg-red-950/50' }}">
          <i data-lucide="circle-dollar-sign" class="w-4 h-4 {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
        AED {{ number_format(abs($netProfit), 2) }}
      </p>
      <span class="text-[10px] sm:text-[11px] font-semibold {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
        {{ $netProfit >= 0 ? __('✓ Keuntungan Bersih') : __('✗ Defisit Operasional') }}
      </span>
    </div>

    {{-- Rata-rata Transaksi --}}
    <div class="card-lift p-4 sm:p-5 rounded-2xl space-y-2"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #3B82F630;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Rata-rata Order') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-950/50 shrink-0">
          <i data-lucide="receipt" class="w-4 h-4 text-blue-400"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black text-white">AED {{ number_format($avgOrderValue, 2) }}</p>
      <span class="text-[10px] sm:text-[11px] text-blue-400 font-semibold">{{ __('Per Pelanggan') }}</span>
    </div>

  </div>

  {{-- ── Visualisasi Grafik Pembukuan ─────────────────────────────────── --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 no-print">

    {{-- Grafik Tren Omset vs Pengeluaran --}}
    <div class="lg:col-span-2 p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="font-black text-white text-sm">{{ __('Grafik Rekap Keuangan & Tren Transaksi') }}</h3>
          <p class="text-[11px] text-gray-500">{{ __('Perbandingan omset dan pengeluaran selama periode terpilih') }}</p>
        </div>
        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg" style="background:#D4AF3715;color:#D4AF37;border:1px solid #D4AF3730;">
          {{ date('d M Y', strtotime($startDate)) }} — {{ date('d M Y', strtotime($endDate)) }}
        </span>
      </div>
      <div class="h-64 w-full relative">
        <canvas id="reportsTrendChart"></canvas>
      </div>
    </div>

    {{-- Grafik Porsi Omset Layanan --}}
    <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
      <div>
        <h3 class="font-black text-white text-sm">{{ __('Distribusi Pendapatan Layanan') }}</h3>
        <p class="text-[11px] text-gray-500">{{ __('Porsi omset berdasarkan paket massage') }}</p>
      </div>
      <div class="h-64 w-full relative">
        <canvas id="reportsServiceChart"></canvas>
      </div>
    </div>

  </div>

  {{-- ── Detail Printable Sheet & Transaksi ───────────────────────────── --}}
  <div class="p-6 sm:p-8 rounded-2xl space-y-6"
       style="background:#141414;border:1px solid #D4AF3725;" id="printable-report">

    {{-- Printable Brand Header --}}
    <div class="flex items-center justify-between pb-6" style="border-bottom:1px solid #D4AF3725;">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 overflow-hidden"
             style="border: 1.5px solid #D4AF37; box-shadow: 0 0 16px rgba(212,175,55,0.3);">
          <img src="{{ asset('images/logo.png') }}" alt="F55 Center Logo" class="w-full h-full object-cover">
        </div>
        <div>
          <h1 class="font-display font-black text-lg text-white tracking-wide">F55 MEN RELAXATION & MASSAGE CENTER</h1>
          <p class="text-xs text-gray-400">{{ __('Laporan Resmi Pembukuan & Rekap Penjualan') }}</p>
          <p class="text-[11px] font-semibold mt-0.5" style="color:#D4AF37;">
            {{ __('Periode:') }} {{ date('d M Y', strtotime($startDate)) }} {{ __('s/d') }} {{ date('d M Y', strtotime($endDate)) }}
          </p>
        </div>
      </div>
      <div class="text-right">
        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('REKAP LABA BERSIH') }}</span>
        <span class="text-2xl font-black {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
          AED {{ number_format(abs($netProfit), 2) }}
        </span>
        <span class="text-[10px] block text-gray-500">{{ __('Dicetak:') }} {{ now()->translatedFormat('d M Y H:i') }}</span>
      </div>
    </div>

    {{-- Tabel Ringkasan Kategori Layanan --}}
    <div class="space-y-3">
      <h3 class="font-black text-xs text-white uppercase tracking-wider flex items-center gap-2">
        <i data-lucide="layers" class="w-4 h-4" style="color:#D4AF37;"></i>
        {{ __('Ringkasan Penjualan Per Layanan') }}
      </h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="text-gray-500 uppercase tracking-wider text-[9px]" style="border-bottom:1px solid #D4AF3720;">
              <th class="py-2.5 px-3">{{ __('Nama Layanan') }}</th>
              <th class="py-2.5 px-3">{{ __('Kategori') }}</th>
              <th class="py-2.5 px-3 text-center">{{ __('Durasi') }}</th>
              <th class="py-2.5 px-3 text-center">{{ __('Jumlah Booking') }}</th>
              <th class="py-2.5 px-3 text-right">{{ __('Total Pendapatan') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($serviceBreakdown as $svc)
            <tr class="transition-colors hover:bg-white/5" style="border-bottom:1px solid #D4AF3710;">
              <td class="py-3 px-3 font-bold text-white">{{ $svc->name }}</td>
              <td class="py-3 px-3 text-gray-400">{{ $svc->category }}</td>
              <td class="py-3 px-3 text-center text-gray-400">{{ $svc->duration }} {{ __('Menit') }}</td>
              <td class="py-3 px-3 text-center font-bold text-white">{{ $svc->bookings_count }}x</td>
              <td class="py-3 px-3 text-right font-black" style="color:#D4AF37;">
                AED {{ number_format($svc->bookings_sum_price ?? 0, 2) }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Detail Transaksi Completed --}}
    <div class="space-y-3 pt-4" style="border-top:1px solid #D4AF3720;">
      <div class="flex items-center justify-between">
        <h3 class="font-black text-xs text-white uppercase tracking-wider flex items-center gap-2">
          <i data-lucide="receipt" class="w-4 h-4" style="color:#D4AF37;"></i>
          {{ __('Detail Seluruh Transaksi Selesai') }} ({{ $completedBookings->count() }})
        </h3>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="text-gray-500 uppercase tracking-wider text-[9px]" style="border-bottom:1px solid #D4AF3720;">
              <th class="py-2.5 px-3 text-center w-12">{{ __('No') }}</th>
              <th class="py-2.5 px-3">{{ __('Kode') }}</th>
              <th class="py-2.5 px-3">{{ __('Pelanggan') }}</th>
              <th class="py-2.5 px-3">{{ __('Layanan') }}</th>
              <th class="py-2.5 px-3">{{ __('Therapist') }}</th>
              <th class="py-2.5 px-3">{{ __('Tanggal') }}</th>
              <th class="py-2.5 px-3">{{ __('Metode Bayar') }}</th>
              <th class="py-2.5 px-3 text-right">{{ __('Nominal') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($completedBookings as $b)
            <tr class="transition-colors hover:bg-white/5" style="border-bottom:1px solid #D4AF3710;">
              <td class="py-3 px-3 text-center font-bold text-gray-400 font-mono">{{ $loop->iteration }}</td>
              <td class="py-3 px-3 font-black text-[11px]">
                <a href="{{ route('admin.sessions', ['booking_code' => $b->booking_code]) }}" class="hover:underline hover:text-yellow-300" style="color:#D4AF37;">
                  {{ $b->booking_code }}
                </a>
              </td>
              <td class="py-3 px-3 font-bold text-white">{{ $b->customer_name }}</td>
              <td class="py-3 px-3 text-gray-300">{{ $b->service_name }}</td>
              <td class="py-3 px-3 text-gray-400">{{ $b->therapist_name }}</td>
              <td class="py-3 px-3 text-gray-400">{{ $b->booking_date->format('d/m/Y') }} {{ $b->booking_time }}</td>
              <td class="py-3 px-3 text-gray-300">
                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold" style="background:#1a1a1a;border:1px solid #D4AF3730;color:#D4AF37;">
                  {{ strtoupper($b->payment_method ?? 'CASH') }}
                </span>
              </td>
              <td class="py-3 px-3 text-right font-black text-white">
                AED {{ number_format($b->price, 2) }}
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="py-8 text-center text-xs text-gray-600 font-semibold">
                {{ __('Tidak ada transaksi treatment yang selesai pada periode ini.') }}
              </td>
            </tr>
            @endforelse
          </tbody>
          @if($completedBookings->isNotEmpty())
          <tfoot>
            <tr style="border-top:2px solid #D4AF3740;">
              <td colspan="7" class="py-3 px-3 font-black text-right text-gray-400 uppercase text-[10px]">{{ __('Total Omset:') }}</td>
              <td class="py-3 px-3 font-black text-right text-sm" style="color:#D4AF37;">
                AED {{ number_format($totalRevenue, 2) }}
              </td>
            </tr>
          </tfoot>
          @endif
        </table>
      </div>
    </div>

  </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // ── Trend Chart (Omset vs Pengeluaran) ────────────────────────
  const trendCtx = document.getElementById('reportsTrendChart');
  if (trendCtx) {
    const labels  = {!! json_encode($chartLabels) !!};
    const revenue = {!! json_encode($chartRevenue) !!};
    const expense = {!! json_encode($chartExpense) !!};

    new Chart(trendCtx, {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            label: '{{ __("Omset Pendapatan") }}',
            data: revenue,
            backgroundColor: '#D4AF37',
            borderRadius: 6,
          },
          {
            label: '{{ __("Pengeluaran") }}',
            data: expense,
            backgroundColor: '#EF4444',
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
            labels: {
              color: '#9ca3af',
              font: { size: 11, weight: 'bold' }
            }
          }
        },
        scales: {
          x: { grid: { color: '#D4AF3710' }, ticks: { color: '#6b7280', font: { size: 10 } } },
          y: {
            grid: { color: '#D4AF3710' },
            ticks: {
              color: '#6b7280',
              callback: v => 'AED ' + Number(v).toLocaleString()
            }
          }
        }
      }
    });
  }

  // ── Service Breakdown Chart ──────────────────────────────────
  const serviceCtx = document.getElementById('reportsServiceChart');
  if (serviceCtx) {
    const services = {!! json_encode($serviceBreakdown->pluck('name')) !!};
    const counts   = {!! json_encode($serviceBreakdown->pluck('bookings_count')) !!};

    new Chart(serviceCtx, {
      type: 'doughnut',
      data: {
        labels: services,
        datasets: [{
          data: counts,
          backgroundColor: ['#D4AF37', '#B8960C', '#F5DF93', '#92700A', '#654C07', '#E5C158', '#423307'],
          borderColor: '#141414',
          borderWidth: 2,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              color: '#9ca3af',
              font: { size: 10, weight: 'bold' },
              padding: 10
            }
          }
        },
        cutout: '65%'
      }
    });
  }
});
</script>
@endpush
