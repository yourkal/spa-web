@extends('layouts.app')

@section('title', __('Riwayat Booking') . ' - F55 CENTER DUBAI')

@section('content')
<div class="space-y-8">

  <!-- Header Banner -->
  <div class="p-6 sm:p-8 rounded-3xl relative overflow-hidden"
       style="background:linear-gradient(135deg,#16140d,#141414);border:1.5px solid #D4AF3740;">
    <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#D4AF37,#F5DF93,#B8960C);"></div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold mb-3"
             style="background:#D4AF3715;border:1px solid #D4AF3740;color:#F5DF93;">
          <i data-lucide="history" class="w-3.5 h-3.5 text-yellow-400"></i>
          F55 CENTER DUBAI
        </div>
        <h1 class="text-xl sm:text-2xl font-display font-black text-white tracking-tight">{{ __('Riwayat Reservasi Saya') }}</h1>
        <p class="text-xs text-gray-500 mt-1">{{ __('Daftar seluruh pesanan dan status reservasi spa Anda') }}</p>
      </div>
      <a href="{{ route('customer.booking.create') }}"
        class="px-5 py-3 rounded-xl font-black text-xs shadow-md transition-all flex items-center gap-1.5 self-start sm:self-auto hover:opacity-90"
        style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
        <i data-lucide="plus-circle" class="w-4 h-4"></i>
        <span>{{ __('Booking Baru') }}</span>
      </a>
    </div>
  </div>

  <!-- Bookings List -->
  <div class="rounded-3xl overflow-hidden shadow-xl" style="background:#141414;border:1px solid #D4AF3730;">
    @if($bookings->isEmpty())
      <div class="py-16 text-center space-y-3">
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto"
             style="background:#D4AF3715;border:1px solid #D4AF3730;">
          <i data-lucide="calendar-x" class="w-8 h-8" style="color:#D4AF37;"></i>
        </div>
        <h3 class="text-sm font-bold text-white">{{ __('Belum Ada Riwayat Reservasi') }}</h3>
        <p class="text-xs text-gray-500">{{ __('Anda belum pernah melakukan pemesanan layanan spa.') }}</p>
        <a href="{{ route('customer.booking.create') }}"
           class="inline-block px-5 py-2.5 rounded-xl font-black text-xs shadow-md hover:opacity-90"
           style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Reservasi Sekarang') }}
        </a>
      </div>
    @else
      <div class="divide-y" style="border-color:#D4AF3715;">
        @foreach($bookings as $b)
        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors hover:bg-white/5">

          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg shrink-0 shadow-md"
                 style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;">
              <i data-lucide="sparkles" class="w-5 h-5"></i>
            </div>
            <div class="space-y-1">
              <div class="flex flex-wrap items-center gap-2">
                <span class="font-extrabold text-white text-base">{{ $b->service_name }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold"
                      style="background:#D4AF3720;border:1px solid #D4AF3740;color:#F5DF93;">
                  {{ $b->booking_code }}
                </span>
                @if($b->created_at && $b->created_at->greaterThanOrEqualTo(now()->subHours(24)))
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 animate-pulse">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    {{ __('Order Baru') }}
                  </span>
                @endif
              </div>
              <p class="text-xs text-gray-400">
                {{ __('Therapist') }}: <strong class="text-gray-200">{{ $b->therapist_name }}</strong> •
                {{ __('Tgl') }}: <strong class="text-gray-200">{{ $b->booking_date->format('d M Y') }} ({{ $b->booking_time }})</strong>
              </p>
              <p class="text-[11px] text-gray-500">
                {{ __('Durasi') }}: {{ $b->duration }} Min • {{ __('Pembayaran') }}: {{ $b->payment_method }}
              </p>
            </div>
          </div>

          <div class="flex items-center justify-between md:justify-end gap-4 border-t md:border-t-0 pt-3 md:pt-0" style="border-color:#D4AF3715;">
            <div class="text-left md:text-right">
              <span class="text-[10px] text-gray-500 font-bold uppercase block">{{ __('Total Biaya') }}</span>
              <span class="font-black text-base" style="color:#D4AF37;">AED {{ number_format($b->price, 2) }}</span>
            </div>

            <div class="flex items-center gap-2">
              <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase
                @if($b->status === 'completed') badge-completed
                @elseif($b->status === 'confirmed') badge-confirmed
                @elseif($b->status === 'in_progress') badge-busy
                @else badge-cancelled
                @endif">
                {{ $b->status }}
              </span>

              <a href="{{ route('customer.booking.show', $b) }}"
                class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1"
                style="background:#1a1a1a;border:1px solid #D4AF3730;color:#D4AF37;">
                <span>{{ __('Detail') }}</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
              </a>
            </div>
          </div>

        </div>
        @endforeach
      </div>
    @endif
  </div>

</div>
@endsection
