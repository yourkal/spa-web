@extends('layouts.admin')

@section('title', __('Detail Pelanggan') . ' - ' . $user->name)
@section('header_title', __('Profil & Riwayat Pelanggan'))
@section('header_subtitle', __('Detail informasi personal dan riwayat reservasi pelanggan'))

@section('content')
<div class="max-w-4xl space-y-6">

  {{-- Back Link --}}
  <a href="{{ route('admin.customers') }}"
     class="inline-flex items-center gap-1.5 text-xs font-bold transition-all" style="color:#D4AF37;">
    <i data-lucide="arrow-left" class="w-4 h-4"></i> {{ __('Kembali ke Direktori Pelanggan') }}
  </a>

  {{-- Profile Card --}}
  <div class="p-6 sm:p-8 rounded-2xl space-y-6" style="background:#141414;border:1px solid #D4AF3725;">
    <div class="flex flex-col sm:flex-row items-center gap-5 pb-6" style="border-bottom:1px solid #D4AF3715;">
      <img src="{{ $user->avatar }}" class="w-20 h-20 rounded-2xl object-cover shadow-lg" style="border:2px solid #D4AF37;">
      <div class="space-y-1 text-center sm:text-left">
        <h2 class="font-display font-black text-xl text-white">{{ $user->name }}</h2>
        <p class="text-xs text-gray-400">Phone: <strong class="text-white">{{ $user->phone }}</strong> • Email: <strong class="text-white">{{ $user->email ?? '-' }}</strong></p>
        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase mt-1"
              style="background:#D4AF3720;border:1px solid #D4AF3740;color:#D4AF37;">
          {{ __('Pelanggan Terdaftar') }}
        </span>
      </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div class="p-5 rounded-xl space-y-1" style="background:#1a1a1a;border:1px solid #D4AF3720;">
        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">{{ __('Total Pengeluaran (Spend)') }}</span>
        <p class="text-xl sm:text-2xl font-black" style="color:#D4AF37;">AED {{ number_format($totalSpend, 2) }}</p>
      </div>

      <div class="p-5 rounded-xl space-y-1" style="background:#1a1a1a;border:1px solid #D4AF3720;">
        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">{{ __('Total Kunjungan Selesai') }}</span>
        <p class="text-xl sm:text-2xl font-black text-white">{{ $totalVisits }} {{ __('kali') }}</p>
      </div>
    </div>
  </div>

  {{-- Bookings History --}}
  <div class="p-6 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3725;">
    <h3 class="font-black text-sm text-white pb-3" style="border-bottom:1px solid #D4AF3715;">{{ __('Riwayat Reservasi Pelanggan') }}</h3>

    <div class="divide-y text-xs" style="border-color:#D4AF3710;">
      @forelse($user->bookings as $b)
      <div class="py-3 flex items-center justify-between gap-4">
        <div>
          <span class="font-bold text-white block text-sm">{{ $b->service_name }}</span>
          <span class="text-gray-400">{{ __('Kode') }}: {{ $b->booking_code }} • {{ __('Therapist') }}: {{ $b->therapist_name }} • {{ $b->booking_date->format('d M Y') }}</span>
        </div>
        <div class="text-right">
          <span class="font-black text-sm block" style="color:#D4AF37;">AED {{ number_format($b->price, 2) }}</span>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
            @if($b->status === 'completed') badge-completed
            @elseif($b->status === 'confirmed') badge-confirmed
            @elseif($b->status === 'in_progress') badge-busy
            @else badge-cancelled
            @endif">
            {{ $b->status }}
          </span>
        </div>
      </div>
      @empty
      <p class="text-xs text-gray-500 py-6 text-center">{{ __('Pelanggan ini belum memiliki riwayat reservasi.') }}</p>
      @endforelse
    </div>
  </div>

</div>
@endsection
