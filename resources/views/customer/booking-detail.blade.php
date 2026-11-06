@extends('layouts.app')

@section('title', __('Detail Booking') . ' ' . $booking->booking_code . ' - F55 CENTER DUBAI')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

  <!-- Back Link -->
  <a href="{{ route('customer.bookings') }}"
     class="inline-flex items-center gap-1.5 text-xs font-bold transition-all hover:opacity-80"
     style="color:#D4AF37;">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    {{ __('Kembali ke Riwayat Booking') }}
  </a>

  <!-- Booking Details Card -->
  <div class="rounded-3xl overflow-hidden shadow-2xl space-y-6 p-6 sm:p-8 relative"
       style="background:#141414;border:1.5px solid #D4AF3740;">
    <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#D4AF37,#F5DF93,#B8960C);"></div>

    <!-- Top Bar with Code & Status -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b pb-4" style="border-color:#D4AF3720;">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold mb-2"
             style="background:#D4AF3715;border:1px solid #D4AF3740;color:#F5DF93;">
          <i data-lucide="receipt" class="w-3 h-3"></i>
          {{ __('KODE RESERVASI') }}
        </div>
        <h1 class="text-xl font-display font-black text-white tracking-tight">{{ $booking->booking_code }}</h1>
      </div>

      <div class="flex items-center gap-2">
        <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase
          @if($booking->status === 'completed') badge-completed
          @elseif($booking->status === 'confirmed') badge-confirmed
          @elseif($booking->status === 'in_progress') badge-busy
          @else badge-cancelled
          @endif">
          Status: {{ $booking->status }}
        </span>
      </div>
    </div>

    <!-- Booking Summary Information -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 rounded-2xl" style="background:#1a1a1a;border:1px solid #D4AF3720;">

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Layanan Spa') }}</span>
        <p class="font-extrabold text-base text-white">{{ $booking->service_name }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Therapist Bertugas') }}</span>
        <p class="font-extrabold text-base text-white">{{ $booking->therapist_name }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Tanggal & Waktu') }}</span>
        <p class="font-extrabold text-sm text-white">{{ $booking->booking_date->format('d M Y') }} — {{ $booking->booking_time }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Durasi Perawatan') }}</span>
        <p class="font-extrabold text-sm" style="color:#D4AF37;">{{ $booking->duration }} {{ __('Menit') }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Pemesan') }}</span>
        <p class="font-extrabold text-sm text-white">{{ $booking->customer_name }} ({{ $booking->customer_phone }})</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Metode Pembayaran') }}</span>
        <p class="font-extrabold text-sm text-white">{{ $booking->payment_method }}</p>
      </div>

    </div>

    <!-- Price Section -->
    <div class="flex items-center justify-between p-4 rounded-2xl relative overflow-hidden"
         style="background:linear-gradient(90deg,#1c1900,#141414);border:1.5px solid #D4AF3750;">
      <div class="absolute inset-0" style="background:linear-gradient(135deg,#D4AF3708,transparent);"></div>
      <span class="relative text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Total Biaya Perawatan') }}</span>
      <span class="relative text-xl sm:text-2xl font-black" style="color:#D4AF37;text-shadow:0 0 20px #D4AF3750;">
        AED {{ number_format($booking->price, 2) }}
      </span>
    </div>

    <!-- Cancel Action (If Confirmed) -->
    @if($booking->status === 'confirmed')
    <div class="pt-4 border-t flex justify-end" style="border-color:#D4AF3715;">
      <form action="{{ route('customer.booking.cancel', $booking) }}" method="POST"
            data-confirm="{{ __('Apakah Anda yakin ingin membatalkan reservasi ini?') }}"
            data-confirm-title="{{ __('Batalkan Reservasi?') }}">
        @csrf
        @method('PATCH')
        <button type="submit"
          class="px-5 py-2.5 rounded-xl font-bold text-xs border transition-all flex items-center gap-1.5 bg-red-950/40 border-red-800/40 text-red-400 hover:bg-red-900/50">
          <i data-lucide="x-circle" class="w-4 h-4"></i>
          <span>{{ __('Batalkan Reservasi') }}</span>
        </button>
      </form>
    </div>
    @endif

  </div>

</div>
@endsection
