@extends('layouts.admin')

@section('title', __('Riwayat Order & Transaksi') . ' - F55 MEN RELAXATION CENTER')
@section('header_title', __('Riwayat Order & Transaksi'))
@section('header_subtitle', __('Laporan historis seluruh pesanan treatment spa yang telah selesai atau dibatalkan'))

@section('content')
<div class="space-y-6">

  {{-- ── Action Header & Filter Bar ────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-sm font-black text-white flex items-center gap-2">
          <span>{{ __('Histori Seluruh Transaksi Order') }}</span>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">
            {{ __('Mode Eksekutif') }}
          </span>
        </h2>
        <p class="text-[11px] text-gray-500">{{ __('Pencarian arsip transaksi, filter tanggal, dan informasi detail pembayaran') }}</p>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('bos.orders.history.export.excel', request()->all()) }}"
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
           style="background:#1a1a1a;border:1px solid #10B98150;color:#34D399;">
          <i data-lucide="sheet" class="w-4 h-4 text-emerald-400"></i>
          <span>{{ __('Unduh Excel') }}</span>
        </a>
      </div>
    </div>

    {{-- Filters Form --}}
    <form action="{{ route('bos.orders.history') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2">
      <div>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Cari Kode / Pelanggan...') }}"
               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
               style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
      </div>

      <div>
        <select name="status"
                class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
                style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>{{ __('Semua Status') }}</option>
          <option value="completed" {{ request('status', 'completed') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
          <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
        </select>
      </div>

      <div>
        <input type="date" name="date" value="{{ request('date') }}"
               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
               style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
      </div>

      <div class="flex items-center gap-2">
        <button type="submit"
                class="flex-1 py-2.5 rounded-xl font-bold text-xs transition-all"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Filter') }}
        </button>
        <a href="{{ route('bos.orders.history') }}"
           class="py-2.5 px-4 rounded-xl text-xs font-bold transition-all text-center"
           style="background:#1a1a1a;border:1px solid #D4AF3720;color:#9ca3af;">
          {{ __('Reset') }}
        </a>
      </div>
    </form>
  </div>

  {{-- ── History Table ─────────────────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl overflow-hidden space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="text-gray-500 uppercase tracking-wider text-[9px]" style="border-bottom:1px solid #D4AF3720;">
            <th class="py-2.5 px-3 text-center w-14 font-mono font-bold">{{ __('No') }}</th>
            <th class="py-2.5 px-3">{{ __('Kode Transaksi') }}</th>
            <th class="py-2.5 px-3">{{ __('Pelanggan') }}</th>
            <th class="py-2.5 px-3">{{ __('Layanan Spa') }}</th>
            <th class="py-2.5 px-3">Therapist</th>
            <th class="py-2.5 px-3">{{ __('Tanggal Treatment') }}</th>
            <th class="py-2.5 px-3">{{ __('Durasi') }}</th>
            <th class="py-2.5 px-3">{{ __('Total Bayar') }}</th>
            <th class="py-2.5 px-3">{{ __('Metode') }}</th>
            <th class="py-2.5 px-3">{{ __('Status') }}</th>
            <th class="py-2.5 px-3 text-right">{{ __('Detail') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $b)
          @php
            $rowNo = 1 + (($bookings->currentPage() - 1) * $bookings->perPage()) + $loop->index;
          @endphp
          <tr class="transition-colors hover:bg-white/5" style="border-bottom:1px solid #D4AF3710;">
            <td class="py-3 px-3 text-center font-bold text-gray-400 font-mono">{{ $rowNo }}</td>
            <td class="py-3 px-3">
              <a href="{{ route('bos.sessions', ['booking_code' => $b->booking_code]) }}"
                 class="font-black text-[11px] block hover:underline"
                 style="color:#D4AF37;"
                 title="{{ __('Buka Sesi & Timer :code', ['code' => $b->booking_code]) }}">
                {{ $b->booking_code }}
              </a>
            </td>
            <td class="py-3 px-3">
              <span class="font-bold text-white block">{{ $b->customer_name }}</span>
              <span class="text-[10px] text-gray-500">{{ $b->customer_phone }}</span>
            </td>
            <td class="py-3 px-3 text-gray-300 font-semibold">{{ $b->service_name }}</td>
            <td class="py-3 px-3 text-gray-400">{{ $b->therapist_name ?? '-' }}</td>
            <td class="py-3 px-3 text-gray-300">
              {{ $b->booking_date->format('d M Y') }} — {{ $b->booking_time }}
            </td>
            <td class="py-3 px-3 font-semibold" style="color:#D4AF37;">{{ $b->duration }} min</td>
            <td class="py-3 px-3 font-black text-white">AED {{ number_format($b->price, 2) }}</td>
            <td class="py-3 px-3 text-gray-300">{{ $b->payment_method ?? 'QRIS' }}</td>
            <td class="py-3 px-3">
              <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase
                @if($b->status === 'completed') badge-completed
                @elseif($b->status === 'cancelled') badge-cancelled
                @else badge-confirmed
                @endif">
                {{ strtoupper($b->status) }}
              </span>
            </td>
            <td class="py-3 px-3 text-right">
              <a href="{{ route('bos.orders.show', $b) }}"
                 class="p-2 inline-flex items-center gap-1.5 text-yellow-400 hover:text-yellow-300 hover:bg-white/10 rounded-xl transition-all"
                 title="{{ __('Lihat Detail Transaksi') }}">
                <i data-lucide="eye" class="w-4 h-4"></i>
                <span class="text-[11px] font-bold">{{ __('Detail') }}</span>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="11" class="py-12 text-center text-xs text-gray-500 font-semibold">
              {{ __('Tidak ada data riwayat order yang ditemukan.') }}
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($bookings->hasPages())
      <div class="pt-4 border-t" style="border-color:#D4AF3715;">
        {{ $bookings->appends(request()->query())->links() }}
      </div>
    @endif
  </div>

</div>
@endsection

