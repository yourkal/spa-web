@extends('layouts.admin')

@section('title', __('Laporan Keuangan Eksekutif') . ' - F55 MEN RELAXATION CENTER')
@section('header_title', __('Laporan Keuangan & Omset Bisnis'))
@section('header_subtitle', __('Analisis performa finansial, omset treatment, pengeluaran operasional, dan laba bersih'))

@section('content')
<div class="space-y-6">

  {{-- ── Filter & Action Bar (Hidden on Print) ─────────────────────────── --}}
  <div class="p-5 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4 no-print"
       style="background:#141414;border:1px solid #D4AF3725;">
    <form action="{{ route('bos.reports') }}" method="GET" class="flex flex-wrap items-end gap-3">
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
      <a href="{{ route('bos.reports.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
         class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
         style="background:#1a1a1a;border:1px solid #10B98150;color:#34D399;">
        <i data-lucide="sheet" class="w-4 h-4 text-emerald-400"></i>
        <span>{{ __('Unduh Excel') }}</span>
      </a>

      <a href="{{ route('bos.reports.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
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
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid {{ $netProfit >= 0 ? '#10B98140' : '#EF444440' }};">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Laba Bersih (Net)') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $netProfit >= 0 ? 'bg-emerald-950/50' : 'bg-red-950/50' }}">
          <i data-lucide="{{ $netProfit >= 0 ? 'trending-up' : 'trending-down' }}" class="w-4 h-4 {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }}"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
        AED {{ number_format(abs($netProfit), 2) }}
      </p>
      <span class="text-[10px] sm:text-[11px] font-semibold {{ $netProfit >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
        {{ $netProfit >= 0 ? __('Surplus Profit') : __('Defisit') }}
      </span>
    </div>

    {{-- Rata-Rata Nilai Order --}}
    <div class="card-lift p-4 sm:p-5 rounded-2xl space-y-2 col-span-2 sm:col-span-1"
         style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #D4AF3720;">
      <div class="flex items-center justify-between">
        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Rata-rata Order') }}</span>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#D4AF3715;">
          <i data-lucide="calculator" class="w-4 h-4" style="color:#D4AF37;"></i>
        </div>
      </div>
      <p class="text-base sm:text-lg lg:text-xl font-black text-white">AED {{ number_format($avgOrderValue, 2) }}</p>
      <span class="text-[10px] sm:text-[11px] text-gray-400 font-semibold">{{ __('Per transaksi treatment') }}</span>
    </div>

  </div>

  {{-- ── Completed Bookings Table ────────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3715;">
      <div>
        <h3 class="font-black text-white text-sm">{{ __('Rincian Transaksi Selesai') }}</h3>
        <p class="text-[11px] text-gray-500">{{ $completedBookings->count() }} {{ __('transaksi selesai pada periode terpilih') }}</p>
      </div>
      <span class="text-xs font-bold" style="color:#D4AF37;">Total: AED {{ number_format($totalRevenue, 2) }}</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="text-gray-500 uppercase tracking-wider text-[9px]" style="border-bottom:1px solid #D4AF3715;">
            <th class="py-2.5 px-3 text-center w-12 font-mono">No</th>
            <th class="py-2.5 px-3">{{ __('Kode') }}</th>
            <th class="py-2.5 px-3">{{ __('Pelanggan') }}</th>
            <th class="py-2.5 px-3">{{ __('Layanan Spa') }}</th>
            <th class="py-2.5 px-3">Therapist</th>
            <th class="py-2.5 px-3">{{ __('Tanggal Treatment') }}</th>
            <th class="py-2.5 px-3">{{ __('Durasi') }}</th>
            <th class="py-2.5 px-3 text-right">{{ __('Nominal') }}</th>
            <th class="py-2.5 px-3">{{ __('Metode') }}</th>
            <th class="py-2.5 px-3 text-right">{{ __('Detail') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($completedBookings as $idx => $b)
          <tr class="hover:bg-white/5 transition-colors" style="border-bottom:1px solid #D4AF3710;">
            <td class="py-3 px-3 text-center text-gray-500 font-mono">{{ $idx + 1 }}</td>
            <td class="py-3 px-3 font-bold font-mono" style="color:#D4AF37;">{{ $b->booking_code }}</td>
            <td class="py-3 px-3 font-bold text-white">{{ $b->customer_name }}</td>
            <td class="py-3 px-3 text-gray-300 font-semibold">{{ $b->service_name }}</td>
            <td class="py-3 px-3 text-gray-400">{{ $b->therapist_name ?? '-' }}</td>
            <td class="py-3 px-3 text-gray-300">{{ $b->booking_date->format('d M Y') }} - {{ $b->booking_time }}</td>
            <td class="py-3 px-3 font-semibold" style="color:#D4AF37;">{{ $b->duration }}m</td>
            <td class="py-3 px-3 text-right font-black text-white">AED {{ number_format($b->price, 2) }}</td>
            <td class="py-3 px-3 text-gray-300">{{ $b->payment_method ?? 'QRIS' }}</td>
            <td class="py-3 px-3 text-right">
              <a href="{{ route('bos.orders.show', $b) }}" class="p-1.5 text-yellow-400 hover:text-white rounded-lg transition-all inline-flex items-center gap-1">
                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                <span class="text-[10px] font-bold">{{ __('Lihat') }}</span>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="10" class="py-12 text-center text-xs text-gray-500 font-semibold">
              {{ __('Tidak ada transaksi selesai pada periode ini.') }}
            </td>
          </tr>
          @endforelse
          @if($completedBookings->isNotEmpty())
          <tr class="font-black text-xs" style="background:#1c1900;border-top:2px solid #D4AF37;">
            <td colspan="7" class="py-3 px-3 text-right uppercase tracking-wider" style="color:#F5DF93;">{{ __('TOTAL OMSET KOTOR') }}:</td>
            <td class="py-3 px-3 text-right" style="color:#D4AF37;">AED {{ number_format($totalRevenue, 2) }}</td>
            <td colspan="2"></td>
          </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>

  {{-- ── Service Breakdown Table ────────────────────────────────────────── --}}
  @if(isset($serviceBreakdown) && $serviceBreakdown->isNotEmpty())
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3715;">
      <div>
        <h3 class="font-black text-white text-sm">{{ __('Rekap Performa Per Layanan Spa') }}</h3>
        <p class="text-[11px] text-gray-500">{{ __('Jumlah booking dan total omset per jenis perawatan') }}</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="text-gray-500 uppercase tracking-wider text-[9px]" style="border-bottom:1px solid #D4AF3715;">
            <th class="py-2.5 px-3 text-center w-12 font-mono">No</th>
            <th class="py-2.5 px-3">{{ __('Nama Layanan') }}</th>
            <th class="py-2.5 px-3">{{ __('Kategori') }}</th>
            <th class="py-2.5 px-3 text-center">{{ __('Total Treatment') }}</th>
            <th class="py-2.5 px-3 text-right">{{ __('Total Omset') }}</th>
            <th class="py-2.5 px-3 text-right">{{ __('Kontribusi') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($serviceBreakdown as $idx => $svc)
          @php
            $sum = (float) ($svc->bookings_sum_price ?? 0);
            $contrib = $totalRevenue > 0 ? round(($sum / $totalRevenue) * 100, 1) : 0;
          @endphp
          <tr class="hover:bg-white/5 transition-colors" style="border-bottom:1px solid #D4AF3710;">
            <td class="py-3 px-3 text-center text-gray-500 font-mono">{{ $idx + 1 }}</td>
            <td class="py-3 px-3 font-bold text-white">{{ $svc->name }}</td>
            <td class="py-3 px-3 text-gray-400">{{ $svc->category }}</td>
            <td class="py-3 px-3 text-center font-bold text-gray-300">{{ $svc->bookings_count }}x</td>
            <td class="py-3 px-3 text-right font-black text-white">AED {{ number_format($sum, 2) }}</td>
            <td class="py-3 px-3 text-right font-bold" style="color:#D4AF37;">{{ $contrib }}%</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

</div>
@endsection

