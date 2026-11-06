@extends('layouts.admin')

@section('title', __('Detail Booking') . ' ' . $booking->booking_code . ' - F55 MEN RELAXATION CENTER')
@section('header_title', __('Detail Booking & Order'))
@section('header_subtitle', __('Informasi lengkap transaksi reservasi treatment pelanggan'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

  <!-- Back Link -->
  <div class="flex items-center justify-between">
    <a href="{{ Auth::user()->role === 'bos' ? route('bos.orders') : route('admin.bookings') }}"
       class="inline-flex items-center gap-1.5 text-xs font-bold transition-all hover:opacity-80"
       style="color:#D4AF37;">
      <i data-lucide="arrow-left" class="w-4 h-4"></i>
      {{ Auth::user()->role === 'bos' ? __('Kembali ke Daftar Order') : __('Kembali ke Kelola Reservasi') }}
    </a>

    @if($booking->status === 'in_progress' && Auth::user()->role === 'admin')
      <a href="{{ route('admin.sessions', ['booking_code' => $booking->booking_code]) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-black shadow-md transition-all hover:scale-105"
         style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
        <i data-lucide="timer" class="w-4 h-4 animate-pulse"></i>
        <span>{{ __('Buka Timer Sesi Ini') }}</span>
      </a>
    @endif
  </div>

  <!-- Booking Details Card -->
  <div class="rounded-3xl overflow-hidden shadow-2xl space-y-6 p-6 sm:p-8 relative"
       style="background:#141414;border:1.5px solid #D4AF3740;">
    <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#D4AF37,#F5DF93,#B8960C);"></div>

    <!-- Top Bar with Code & Status -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b pb-4" style="border-color:#D4AF3720;">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold mb-2"
             style="background:#D4AF3715;border:1px solid #D4AF3740;color:#F5DF93;">
          <i data-lucide="receipt" class="w-3 h-3 text-yellow-400"></i>
          <span>{{ __('KODE BOOKING') }}</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-display font-black text-white tracking-tight">{{ $booking->booking_code }}</h1>
        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('Dibuat pada') }}: {{ $booking->created_at->format('d M Y, H:i') }}</p>
      </div>

      <div class="flex items-center gap-2">
        <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider
          @if($booking->status === 'completed') badge-completed
          @elseif($booking->status === 'confirmed') badge-confirmed
          @elseif($booking->status === 'in_progress') badge-busy
          @else badge-cancelled
          @endif">
          {{ strtoupper($booking->status) }}
        </span>
      </div>
    </div>

    <!-- Information Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 rounded-2xl" style="background:#1a1a1a;border:1px solid #D4AF3720;">

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Nama Pelanggan') }}</span>
        <p class="font-extrabold text-base text-white">{{ $booking->customer_name }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Nomor Telepon / WhatsApp') }}</span>
        <p class="font-bold text-sm text-gray-200">{{ $booking->customer_phone }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Layanan Spa') }}</span>
        <p class="font-extrabold text-base text-white">{{ __($booking->service_name) }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Therapist Bertugas') }}</span>
        <p class="font-extrabold text-base text-white">{{ $booking->therapist_name ?? '-' }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Jadwal Kunjungan') }}</span>
        <p class="font-bold text-sm text-gray-200">{{ $booking->booking_date->format('d M Y') }} — {{ __('Jam') }} {{ $booking->booking_time }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Durasi Perawatan') }}</span>
        <p class="font-extrabold text-sm" style="color:#D4AF37;">{{ $booking->duration }} {{ __('Menit') }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Metode Pembayaran') }}</span>
        <p class="font-bold text-sm text-gray-200">{{ __($booking->payment_method ?? 'QRIS / E-Wallet') }}</p>
      </div>

      <div class="space-y-1">
        <span class="text-[10px] text-gray-500 font-bold uppercase block tracking-wider">{{ __('Akun Terhubung') }}</span>
        <p class="font-bold text-sm text-gray-300">
          @if($booking->user)
            {{ $booking->user->name }} ({{ $booking->user->email }})
          @else
            <span class="text-gray-500 font-normal">{{ __('Pemesanan Tamu / Manual') }}</span>
          @endif
        </p>
      </div>

    </div>

    <!-- Active Session Status (If present) -->
    @if($booking->activeSession)
      @php
        $ses = $booking->activeSession;
        $rem = max(0, (int) $ses->remaining_seconds);
        $mins = floor($rem / 60);
        $secs = $rem % 60;
      @endphp
      <div class="p-5 rounded-2xl space-y-2" style="background:#1a1a1a;border:1px solid #D4AF3740;">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i data-lucide="timer" class="w-4 h-4 text-yellow-400"></i>
            <span class="text-xs font-black text-white uppercase tracking-wider">{{ __('Status Sesi Perawatan') }}</span>
          </div>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase
            @if($ses->status === 'running') bg-emerald-500/20 text-emerald-400 border border-emerald-500/40
            @elseif($ses->status === 'paused') bg-amber-500/20 text-amber-400 border border-amber-500/40
            @else bg-gray-800 text-gray-400
            @endif">
            {{ strtoupper($ses->status) }}
          </span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2 text-xs">
          <div>
            <span class="text-gray-500 text-[10px] block uppercase">{{ __('Waktu Mulai') }}</span>
            <strong class="text-white">{{ $ses->started_at ? $ses->started_at->format('d/m/Y H:i:s') : '-' }}</strong>
          </div>
          <div>
            <span class="text-gray-500 text-[10px] block uppercase">{{ __('Total Durasi') }}</span>
            <strong class="text-white">{{ $ses->total_duration_minutes }} {{ __('Menit') }}</strong>
          </div>
          <div>
            <span class="text-gray-500 text-[10px] block uppercase">{{ __('Sisa Waktu') }}</span>
            <strong class="text-yellow-400 font-mono">{{ sprintf('%02d:%02d', $mins, $secs) }}</strong>
          </div>
          <div>
            <span class="text-gray-500 text-[10px] block uppercase">{{ __('Waktu Selesai') }}</span>
            <strong class="text-white">{{ $ses->ended_at ? $ses->ended_at->format('d/m/Y H:i:s') : '-' }}</strong>
          </div>
        </div>
      </div>
    @endif

    <!-- Price Section -->
    <div class="flex items-center justify-between p-5 rounded-2xl relative overflow-hidden"
         style="background:linear-gradient(90deg,#1c1900,#141414);border:1.5px solid #D4AF3750;">
      <div class="space-y-0.5">
        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Total Tarif Tagihan') }}</span>
        <span class="text-xs text-gray-500">{{ __('Tarif resmi treatment terverifikasi sistem') }}</span>
      </div>
      <div class="text-2xl sm:text-3xl font-black" style="color:#D4AF37;text-shadow:0 0 20px #D4AF3750;">
        AED {{ number_format($booking->price, 2) }}
      </div>
    </div>

    <!-- Admin Status Control (Admin Only, Not Bos) -->
    @if(Auth::user()->role === 'admin')
    <div class="pt-4 border-t flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="border-color:#D4AF3715;">
      @if($booking->status === 'completed')
        <div class="flex items-center gap-2 text-xs font-bold text-emerald-400 bg-emerald-950/40 border border-emerald-500/30 px-3.5 py-2 rounded-xl">
          <i data-lucide="check-circle" class="w-4 h-4"></i>
          <span>{{ __('Treatment telah selesai. Status terkunci permanen.') }}</span>
        </div>
      @else
        <form action="{{ route('admin.bookings.status', $booking) }}" method="POST" class="flex items-center gap-3">
          @csrf
          @method('PATCH')
          <span class="text-xs font-bold text-gray-400">{{ __('Ubah Status') }}:</span>
          <select name="status" class="px-3.5 py-1.5 rounded-xl text-xs font-bold outline-none cursor-pointer bg-dark-200 border border-yellow-500/30 text-white">
            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>CONFIRMED</option>
            <option value="in_progress" {{ $booking->status === 'in_progress' ? 'selected' : '' }}>IN PROGRESS</option>
            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>COMPLETED</option>
            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
          </select>
          <button type="submit" class="px-4 py-1.5 rounded-xl font-black text-xs bg-yellow-500 text-black hover:bg-yellow-400 transition-all">
            {{ __('Simpan Status') }}
          </button>
        </form>
      @endif

      <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
            data-confirm="{{ __('Booking') }} {{ $booking->booking_code }} {{ __('akan dihapus permanen!') }}"
            data-confirm-title="{{ __('Hapus Booking') }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-4 py-2 rounded-xl font-bold text-xs bg-red-950/40 border border-red-800/40 text-red-400 hover:bg-red-900/50 transition-all flex items-center gap-1.5">
          <i data-lucide="trash-2" class="w-4 h-4"></i>
          <span>{{ __('Hapus Data') }}</span>
        </button>
      </form>
    </div>
    @endif

  </div>

</div>
@endsection

