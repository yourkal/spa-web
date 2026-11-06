@extends('layouts.admin')

@section('title', __('Kelola Pelanggan') . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', __('Direktori & Data Pelanggan'))
@section('header_subtitle', __('Kelola riwayat reservasi, kontak, dan total akumulasi perawatan pelanggan'))

@section('content')
<div class="space-y-6">

  {{-- ── Header & Search Bar ───────────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4"
       style="background:#141414;border:1px solid #D4AF3720;">
    <div>
      <h2 class="text-sm font-black text-white">{{ __('Daftar Pelanggan Terdaftar') }}</h2>
      <p class="text-[11px] text-gray-500">{{ __('Direktori seluruh akun pelanggan F55 Men Relaxation Center') }}</p>
    </div>

    {{-- Search Bar & Export --}}
    <div class="flex flex-wrap items-center gap-2">
      <form action="{{ route('admin.customers') }}" method="GET" class="flex items-center gap-2">
        <div class="relative w-full sm:w-64">
          <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-gray-500"></i>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Cari nama/HP/email...') }}"
                 class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
        <button type="submit"
                class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Cari') }}
        </button>
      </form>

      <a href="{{ route('admin.customers.export.excel', request()->all()) }}"
         class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
         style="background:#1a1a1a;border:1px solid #10B98150;color:#34D399;">
        <i data-lucide="sheet" class="w-4 h-4 text-emerald-400"></i>
        <span>{{ __('Unduh Excel') }}</span>
      </a>
    </div>
  </div>

  {{-- ── Customers Table ────────────────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl overflow-hidden space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="text-gray-500 uppercase tracking-wider text-[9px]" style="border-bottom:1px solid #D4AF3720;">
            <th class="py-2.5 px-3 text-center w-12">{{ __('No') }}</th>
            <th class="py-2.5 px-3">{{ __('Pelanggan') }}</th>
            <th class="py-2.5 px-3">{{ __('No. Telepon WhatsApp') }}</th>
            <th class="py-2.5 px-3">{{ __('Email') }}</th>
            <th class="py-2.5 px-3">{{ __('Total Kunjungan') }}</th>
            <th class="py-2.5 px-3">{{ __('Akumulasi Pengeluaran') }}</th>
            <th class="py-2.5 px-3 text-right">{{ __('Aksi') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($customers as $c)
          <tr class="transition-colors hover:bg-white/5" style="border-bottom:1px solid #D4AF3710;">
            <td class="py-3 px-3 text-center font-bold text-gray-400 font-mono">{{ (($customers->currentPage() - 1) * $customers->perPage()) + $loop->iteration }}</td>
            <td class="py-3 px-3">
              <div class="flex items-center gap-3">
                <img src="{{ $c->avatar }}" class="w-9 h-9 rounded-xl object-cover shrink-0" style="border:1px solid #D4AF3740;">
                <div>
                  <span class="font-bold text-white block text-sm">{{ $c->name }}</span>
                  <span class="text-[10px] text-gray-500">{{ __('Terdaftar') }} {{ $c->created_at->format('d M Y') }}</span>
                </div>
              </div>
            </td>
            <td class="py-3 px-3 font-semibold text-gray-300">{{ $c->phone }}</td>
            <td class="py-3 px-3 text-gray-400">{{ $c->email ?? '-' }}</td>
            <td class="py-3 px-3 font-bold text-white">{{ $c->bookings_count }} <span class="text-[10px] font-normal text-gray-500">{{ __('kali') }}</span></td>
            <td class="py-3 px-3 font-black" style="color:#D4AF37;">AED {{ number_format($c->total_spend ?? 0, 2) }}</td>
            <td class="py-3 px-3 text-right">
              <a href="{{ route('admin.customers.show', $c) }}"
                 class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1"
                 style="background:#1a1a1a;border:1px solid #D4AF3740;color:#D4AF37;">
               <span>{{ __('Lihat Detail') }}</span>
               <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="py-12 text-center text-xs text-gray-500 font-semibold">
              {{ __('Tidak ada data pelanggan yang ditemukan.') }}
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="pt-4" style="border-top:1px solid #D4AF3715;">
      {{ $customers->links() }}
    </div>
  </div>

</div>
@endsection
