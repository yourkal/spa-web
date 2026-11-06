@extends('layouts.admin')

@section('title', __('Sesi Aktif & Timer') . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', __('Kelola Sesi Perawatan & Realtime Timer'))
@section('header_subtitle', __('Pantau durasi waktu perawatan pelanggan secara realtime dengan sinkronisasi otomatis'))

@section('content')
<div class="space-y-6">

  {{-- ── Action Header & Audio Alert Bar ──────────────────────────────── --}}
  <div class="p-5 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-4"
       style="background:#141414;border:1px solid #D4AF3720;">
    <div class="space-y-1">
      <h2 class="text-sm font-black text-white flex items-center gap-2">
        <span>{{ __('Sesi Aktif & Timer') }}</span>
      </h2>
      <p class="text-[11px] text-gray-500">{{ __('Hitung mundur realtime durasi perawatan per ruangan / pelanggan') }}</p>
    </div>

    <div class="flex flex-wrap items-center gap-3">
      {{-- Sound Alert Status & Test Button --}}
      <div class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-2 font-bold"
           style="background:#D4AF3715;border:1px solid #D4AF3740;color:#D4AF37;">
        <i data-lucide="volume-2" class="w-4 h-4 text-yellow-400"></i>
        <span class="text-[11px]">{{ __('Audio Alert Aktif (10 mnt, 5 mnt, & alarm)') }}</span>
        <button type="button" onclick="SpaAudio.testAudio()"
                class="ml-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all hover:scale-105"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Uji Suara') }}
        </button>
      </div>

      <div class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2"
           style="background:#1a1a1a;border:1px solid #D4AF3730;color:#F5DF93;">
        <span class="relative flex h-2.5 w-2.5">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2.5 w-2.5" style="background:#D4AF37;"></span>
        </span>
        <span>{{ $activeSessions->count() }} {{ __('Sesi Berjalan') }}</span>
      </div>
    </div>
  </div>

  {{-- ── Target Booking Notice (When opened from Kelola Reservasi Timer Button) ── --}}
  @if(isset($targetBookingInfo) && $targetBookingInfo)
    @if($targetBookingInfo['status'] === 'running')
      <div class="p-4 rounded-2xl flex items-center justify-between gap-3 text-xs font-bold"
           style="background:#D4AF3718;border:1.5px solid #D4AF37;color:#F5DF93;">
        <div class="flex items-center gap-2">
          <i data-lucide="timer" class="w-5 h-5 text-yellow-400 animate-pulse"></i>
          <span>{{ __('Sedang menampilkan sesi aktif & timer untuk Booking :code', ['code' => $targetBookingInfo['code']]) }}</span>
        </div>
        <a href="{{ (Auth::user()->role === 'bos' ? route('bos.sessions') : route('admin.sessions')) }}" class="px-2.5 py-1 rounded-lg text-[10px] font-bold text-gray-400 hover:text-white bg-white/5">
          {{ __('Tampilkan Semua Sesi') }}
        </a>
      </div>
    @elseif($targetBookingInfo['status'] === 'not_started')
      @if(!empty($targetBookingInfo['is_future']))
        <div class="p-4 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs font-bold bg-neutral-900 border border-amber-500/40 text-amber-200">
          <div class="flex items-start sm:items-center gap-2.5">
            <i data-lucide="lock" class="w-5 h-5 text-amber-400 shrink-0 mt-0.5 sm:mt-0"></i>
            <div>
              <span class="inline-block px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/40 text-[10px] font-black uppercase tracking-wider mb-1">
                {{ __('Jadwal Belum Tiba') }}
              </span>
              <p class="text-xs text-gray-200">{{ $targetBookingInfo['message'] }}</p>
            </div>
          </div>
          <a href="{{ (Auth::user()->role === 'bos' ? route('bos.bookings', ['search' => $targetBookingInfo['code']]) : route('admin.bookings', ['search' => $targetBookingInfo['code']])) }}" class="px-3 py-1.5 rounded-xl text-xs font-black bg-amber-500 text-black shrink-0 hover:bg-amber-400 transition-all self-end sm:self-auto">
            {{ __('Buka Kelola Reservasi') }}
          </a>
        </div>
      @else
        <div class="p-4 rounded-2xl flex items-center justify-between gap-3 text-xs font-bold bg-amber-950/40 border border-amber-500/40 text-amber-300">
          <div class="flex items-center gap-2.5">
            <i data-lucide="alert-circle" class="w-5 h-5 text-amber-400 shrink-0"></i>
            <span>{{ $targetBookingInfo['message'] }}</span>
          </div>
          <a href="{{ (Auth::user()->role === 'bos' ? route('bos.bookings', ['search' => $targetBookingInfo['code']]) : route('admin.bookings', ['search' => $targetBookingInfo['code']])) }}" class="px-3 py-1.5 rounded-xl text-xs font-black bg-amber-500 text-black shrink-0 hover:bg-amber-400 transition-all">
            {{ __('Buka Kelola Reservasi') }}
          </a>
        </div>
      @endif
    @elseif($targetBookingInfo['status'] === 'completed')
      <div class="p-4 rounded-2xl flex items-center justify-between gap-3 text-xs font-bold bg-emerald-950/40 border border-emerald-500/40 text-emerald-300">
        <div class="flex items-center gap-2.5">
          <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 shrink-0"></i>
          <span>{{ $targetBookingInfo['message'] }}</span>
        </div>
        <a href="{{ (Auth::user()->role === 'bos' ? route('bos.bookings', ['search' => $targetBookingInfo['code']]) : route('admin.bookings', ['search' => $targetBookingInfo['code']])) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold text-gray-300 bg-white/10 shrink-0">
          {{ __('Lihat Riwayat') }}
        </a>
      </div>
    @else
      <div class="p-4 rounded-2xl flex items-center gap-2.5 text-xs font-bold bg-neutral-900 border border-neutral-700 text-gray-300">
        <i data-lucide="info" class="w-5 h-5 text-gray-400 shrink-0"></i>
        <span>{{ $targetBookingInfo['message'] }}</span>
      </div>
    @endif
  @endif

  {{-- ── Active Sessions Cards Grid ───────────────────────────────────── --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($activeSessions as $ses)
    @php
      $booking = $ses->booking;
      $isTargeted = isset($targetBookingCode) && $targetBookingCode && ($booking?->booking_code === $targetBookingCode);
      $svc = $booking?->service;
      $pricePerMin = ($svc && $svc->duration > 0) ? ($svc->price / $svc->duration) : (($booking && $booking->duration > 0) ? ($booking->price / $booking->duration) : 0);
      $sessionConfig = [
        'id'            => $ses->id,
        'remainingSecs' => (int) $ses->remaining_seconds,
        'totalSecs'     => (int) ($ses->total_duration_minutes * 60),
        'isPaused'      => (bool) $ses->is_paused,
        'pricePerMin'   => (float) $pricePerMin,
        'customerName'  => (string) $ses->customer_name,
        'serviceName'   => (string) $ses->service_name,
        'syncUrl'       => route('admin.sessions.sync', $ses),
        'csrfToken'     => csrf_token(),
      ];
    @endphp
    <div id="session-booking-{{ $ses->booking->booking_code ?? $ses->id }}"
         x-data="sessionCard(@js($sessionConfig))"
         class="rounded-2xl p-6 space-y-5 flex flex-col justify-between relative card-lift transition-all {{ $isTargeted ? 'ring-2 ring-yellow-400 shadow-[0_0_30px_rgba(212,175,55,0.4)]' : '' }}"
         style="background:#141414;border:1px solid #D4AF3730;">
      
      {{-- Top Session Info --}}
      <div class="space-y-3">
        <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3715;">
          <span class="px-3 py-1 rounded-full font-extrabold text-[10px] uppercase tracking-wider"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
            {{ $ses->booking->booking_code ?? 'F55-SESSION' }}
          </span>
          <div class="text-right">
            <span class="text-xs font-bold text-gray-400">
              Total {{ $ses->total_duration_minutes }} {{ __('Menit') }}
            </span>
            <span class="block text-[11px] font-black" style="color:#D4AF37;">
              {{ __('Biaya') }}: AED {{ number_format($ses->booking->price ?? 0, 2) }}
            </span>
          </div>
        </div>

        <div class="space-y-1">
          <h3 class="font-display font-black text-base sm:text-lg text-white truncate">{{ $ses->customer_name }}</h3>
          <p class="text-xs text-gray-400">
            {{ __('Layanan Spa') }}: <strong class="text-white">{{ $ses->service_name }}</strong>
          </p>
          <p class="text-xs text-gray-500">
            {{ __('Therapist') }}: <strong class="text-gray-300">{{ $ses->therapist_name }}</strong> • {{ __('Jam Booking') }}: <strong class="text-gray-300">{{ $ses->booking->booking_time ?? '-' }}</strong>
          </p>
        </div>
      </div>

      {{-- Realtime Timer Display --}}
      <div class="p-4 sm:p-6 rounded-2xl text-center space-y-3 shadow-inner"
           style="background:linear-gradient(135deg,#0a0a0a,#16140d);border:1px solid #D4AF3740;">
        <span class="text-[10px] uppercase tracking-widest font-bold block"
              :class="isPaused ? 'text-amber-400' : 'text-gray-400'">
          <span x-text="isPaused ? '⏸ {{ __('SESI SEDANG DI-PAUSE') }}' : '⏳ {{ __('SISA WAKTU PERAWATAN') }}'"></span>
        </span>
        
        <div class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight font-mono"
             style="color:#D4AF37;text-shadow:0 0 20px #D4AF3750;" x-text="formattedTime"></div>

        {{-- Progress Bar --}}
        <div class="w-full h-2.5 rounded-full overflow-hidden p-0.5" style="background:#0a0a0a;border:1px solid #D4AF3730;">
          <div class="h-full rounded-full transition-all duration-500"
               style="background:linear-gradient(90deg,#B8960C,#D4AF37);"
               :style="'width: ' + percentage + '%'"></div>
        </div>
      </div>

      {{-- Action Buttons --}}
      @if(Auth::user()->role === 'bos')
        <div class="p-3 rounded-xl text-center text-xs font-bold"
             style="background:#D4AF3715;border:1px solid #D4AF3730;color:#F5DF93;">
          <i data-lucide="eye" class="w-3.5 h-3.5 inline mr-1 text-yellow-400"></i>
          {{ __('Mode Pantau Bos — Realtime Live Monitor') }}
        </div>
      @else
        <div class="grid grid-cols-3 gap-1.5 sm:gap-2 text-[11px] sm:text-xs pt-2">
          {{-- Toggle Pause Form --}}
          <form action="{{ route('admin.sessions.toggle-pause', $ses) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="w-full py-2.5 rounded-xl font-bold flex items-center justify-center gap-1 transition-all"
                    style="{{ $ses->is_paused ? 'background:#D4AF37;color:#111;' : 'background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;' }}">
              <i data-lucide="{{ $ses->is_paused ? 'play' : 'pause' }}" class="w-4 h-4"></i>
              <span>{{ $ses->is_paused ? __('Lanjut') : __('Pause') }}</span>
            </button>
          </form>

          {{-- Extend Time Trigger --}}
          <button type="button" @click="extendModal = true"
                  class="w-full py-2.5 rounded-xl font-black flex items-center justify-center gap-1 transition-all shadow-md"
                  style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>{{ __('Tambah Waktu') }}</span>
          </button>

          {{-- Finish Session Form --}}
          <form action="{{ route('admin.sessions.finish', $ses) }}" method="POST"
                data-confirm="{{ __('Sesi perawatan :cust akan ditandai selesai.', ['cust' => $ses->customer_name]) }}"
                data-confirm-title="{{ __('Selesaikan Sesi?') }}">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="w-full py-2.5 rounded-xl font-bold flex items-center justify-center gap-1 transition-all text-green-400 bg-green-950/40 border border-green-800/40 hover:bg-green-900/50">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              <span>{{ __('Selesai') }}</span>
            </button>
          </form>
        </div>

        {{-- Modal Tambah Waktu & Update Harga (Input Manual) --}}
        <div x-show="extendModal" x-transition
             class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
          <div @click.outside="if (typeof Swal === 'undefined' || !Swal.isVisible()) extendModal = false"
               class="rounded-2xl max-w-sm w-full p-6 space-y-4 shadow-2xl relative text-left"
               style="background:#141414;border:1px solid #D4AF3740;">
            <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3720;">
              <div>
                <h4 class="font-black text-sm text-white">{{ __('Perpanjang Sesi') }}</h4>
                <p class="text-[11px] text-gray-500">{{ $ses->customer_name }} • {{ $ses->service_name }}</p>
              </div>
              <button @click="extendModal = false" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
              </button>
            </div>

            <form id="extend-form-{{ $ses->id }}" action="{{ route('admin.sessions.extend', $ses) }}" method="POST" class="space-y-4">
              @csrf
              @method('PATCH')

              <div class="space-y-2">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Pilih Tambahan Durasi') }}</label>
                <div class="grid grid-cols-3 gap-2">
                  <button type="button" @click="setExtendMinutes(15)"
                          :style="extendMinutes == 15 ? 'background:#D4AF37;color:#111;font-weight:900;' : 'background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;'"
                          class="py-2 rounded-xl text-xs transition-all">+15 {{ __('Menit') }}</button>
                  <button type="button" @click="setExtendMinutes(30)"
                          :style="extendMinutes == 30 ? 'background:#D4AF37;color:#111;font-weight:900;' : 'background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;'"
                          class="py-2 rounded-xl text-xs transition-all">+30 {{ __('Menit') }}</button>
                  <button type="button" @click="setExtendMinutes(60)"
                          :style="extendMinutes == 60 ? 'background:#D4AF37;color:#111;font-weight:900;' : 'background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;'"
                          class="py-2 rounded-xl text-xs transition-all">+60 {{ __('Menit') }}</button>
                </div>
                <input type="hidden" name="minutes" :value="extendMinutes">
              </div>

              {{-- Input Manual Biaya Tambahan --}}
              <div class="p-3.5 rounded-xl space-y-2" style="background:#1a1a1a;border:1px solid #D4AF3730;">
                <div class="flex justify-between items-center text-xs">
                  <label class="text-gray-300 font-bold block">{{ __('Atur Biaya Tambahan (AED)') }}</label>
                  <button type="button" @click="recalculateExtendPrice()"
                          class="text-[10px] text-amber-400 hover:underline flex items-center gap-1">
                    <i data-lucide="refresh-cw" class="w-3 h-3"></i> {{ __('Reset Otomatis') }}
                  </button>
                </div>
                <div class="relative">
                  <span class="absolute left-3.5 top-2.5 text-xs font-bold text-gray-500">AED</span>
                  <input type="number" step="0.5" min="0" name="custom_price" x-model="extendPrice"
                         class="w-full pl-12 pr-4 py-2 rounded-xl text-xs font-bold outline-none transition-all"
                         style="background:#111;border:1px solid #D4AF3740;color:#F5DF93;"
                         placeholder="0.00">
                </div>
                <p class="text-[10px] text-gray-500">
                  *{{ __('Biaya tambahan dapat disesuaikan secara manual (bisa diisi 0 jika gratis/kompensasi).') }}
                </p>
              </div>

              <button type="button"
                      @click="submitExtend()"
                      class="w-full py-2.5 rounded-xl font-black text-xs shadow-md transition-all hover:opacity-95"
                      style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
                {{ __('Konfirmasi Perpanjang Sesi') }}
              </button>
            </form>
          </div>
        </div>
      @endif

    </div>
    @empty
    <div class="col-span-full py-16 text-center rounded-2xl space-y-3" style="background:#141414;border:1px solid #D4AF3720;">
      <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto" style="background:#D4AF3715;border:1px solid #D4AF3730;">
        <i data-lucide="timer-off" class="w-8 h-8" style="color:#D4AF37;"></i>
      </div>
      <h3 class="text-sm font-bold text-white">{{ __('Tidak Ada Sesi Perawatan Berlangsung') }}</h3>
      <p class="text-xs text-gray-500 max-w-sm mx-auto">{{ __('Ubah status booking dari "Confirmed" ke "In Progress" pada menu Kelola Booking untuk mengaktifkan timer otomatis.') }}</p>
      <a href="{{ route('admin.bookings') }}" class="inline-block px-5 py-2.5 rounded-xl font-bold text-xs"
         style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
        {{ __('Buka Kelola Booking') }}
      </a>
    </div>
    @endforelse
  </div>

  {{-- ── Ended Sessions History ────────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <h3 class="font-black text-sm text-white pb-3" style="border-bottom:1px solid #D4AF3715;">{{ __('Riwayat Sesi Terakhir Selesai') }}</h3>

    <div class="divide-y" style="border-color:#D4AF3710;">
      @forelse($endedSessions as $end)
      <div class="py-3 flex items-center justify-between text-xs">
        <div>
          <strong class="text-white block text-sm">{{ $end->customer_name }}</strong>
          <span class="text-gray-400">{{ $end->service_name }} • {{ __('Therapist') }}: {{ $end->therapist_name }}</span>
        </div>
        <div class="text-right">
          <span class="px-2.5 py-1 rounded-full badge-completed text-[10px] font-bold uppercase">{{ __('Selesai') }}</span>
          <span class="text-[11px] text-gray-500 block mt-0.5">{{ $end->ended_at ? $end->ended_at->diffForHumans() : $end->updated_at->diffForHumans() }}</span>
        </div>
      </div>
      @empty
      <p class="text-xs text-gray-600 py-4 text-center">{{ __('Belum ada sesi yang diselesaikan hari ini.') }}</p>
      @endforelse
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
/**
 * Luxury Web Audio Synthesizer for Session Alerts & Alarm
 * - 10 minutes remaining: triple beep
 * - 5 minutes remaining: triple beep
 * - Last 10 seconds: continuous 1-second countdown beep
 * - 0: Continuous penetrating alarm loop that stops only when SweetAlert "Oke" button is pressed!
 */
const SpaAudio = (function() {
  let audioCtx = null;
  let alarmInterval = null;

  function getContext() {
    if (!audioCtx) {
      const AudioContextClass = window.AudioContext || window.webkitAudioContext;
      if (AudioContextClass) {
        audioCtx = new AudioContextClass();
      }
    }
    if (audioCtx && audioCtx.state === 'suspended') {
      audioCtx.resume();
    }
    return audioCtx;
  }

  // Auto unlock on first user interaction on the page
  const unlockEvents = ['click', 'touchstart', 'keydown'];
  const unlockHandler = () => {
    getContext();
    unlockEvents.forEach(evt => document.removeEventListener(evt, unlockHandler));
  };
  unlockEvents.forEach(evt => document.addEventListener(evt, unlockHandler, { once: true }));

  function playTone(freq, duration = 0.16, type = 'sine', gainLevel = 0.3) {
    try {
      const ctx = getContext();
      if (!ctx) return;
      const osc = ctx.createOscillator();
      const gain = ctx.createGain();

      osc.type = type;
      osc.frequency.setValueAtTime(freq, ctx.currentTime);

      gain.gain.setValueAtTime(gainLevel, ctx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + duration);

      osc.connect(gain);
      gain.connect(ctx.destination);

      osc.start();
      osc.stop(ctx.currentTime + duration);
    } catch (err) {
      console.warn('Audio tone error:', err);
    }
  }

  function playTripleBeep(freq = 880) {
    playTone(freq, 0.15, 'triangle', 0.35);
    setTimeout(() => playTone(freq, 0.15, 'triangle', 0.35), 220);
    setTimeout(() => playTone(freq, 0.15, 'triangle', 0.35), 440);
  }

  function playCountdownBeep(freq = 980) {
    playTone(freq, 0.16, 'sine', 0.3);
  }

  function playAlarmBurst() {
    // Clear, penetrating alarm burst (two-tone chime sequence)
    playTone(880, 0.22, 'triangle', 0.45);
    setTimeout(() => playTone(1174.66, 0.25, 'triangle', 0.45), 240);
    setTimeout(() => playTone(1479.98, 0.35, 'triangle', 0.5), 500);
  }

  function startAlarmLoop() {
    stopAlarmLoop();
    getContext();
    playAlarmBurst();
    alarmInterval = setInterval(() => {
      playAlarmBurst();
    }, 1300);
  }

  function stopAlarmLoop() {
    if (alarmInterval) {
      clearInterval(alarmInterval);
      alarmInterval = null;
    }
  }

  function testAudio() {
    getContext();
    startAlarmLoop();
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: '🔊 {{ __('Uji Alarm Suara') }}',
        html: '{{ __('Alarm suara sedang berbunyi berulang. Tekan Oke untuk mematikan alarm.') }}',
        icon: 'warning',
        background: '#141414',
        color: '#fff',
        confirmButtonColor: '#D4AF37',
        confirmButtonText: '<span style="color:#111;font-weight:900">{{ __('Oke, Matikan Alarm') }}</span>',
        allowOutsideClick: false,
        allowEscapeKey: false,
      }).then(() => {
        stopAlarmLoop();
      });
    }
  }

  return {
    unlock: getContext,
    playTone,
    playTripleBeep,
    playCountdownBeep,
    playAlarmBurst,
    startAlarmLoop,
    stopAlarmLoop,
    testAudio,
  };
})();

function sessionCard(cfg) {
  return {
    remainingSecs: cfg.remainingSecs,
    totalSecs: cfg.totalSecs,
    isPaused: cfg.isPaused,
    extendModal: false,
    extendMinutes: 15,
    pricePerMin: cfg.pricePerMin,
    extendPrice: (cfg.pricePerMin * 15).toFixed(2),
    // Wall-clock target finish timestamp (immune to interval drift or browser lag/freezing)
    targetEndTime: Date.now() + (cfg.remainingSecs * 1000),
    pausedRemainingSecs: cfg.remainingSecs,
    // Tracking flags so alerts fire exactly once
    alerted10m: cfg.remainingSecs < 600,
    alerted5m: cfg.remainingSecs < 300,
    alerted0s: cfg.remainingSecs <= 0,
    setExtendMinutes(mins) {
      this.extendMinutes = Number(mins);
      this.extendPrice = (this.pricePerMin * this.extendMinutes).toFixed(2);
    },
    recalculateExtendPrice() {
      this.extendPrice = (this.pricePerMin * this.extendMinutes).toFixed(2);
    },
    get formattedTime() {
      if (this.remainingSecs <= 0) return '00:00';
      let hrs = Math.floor(this.remainingSecs / 3600);
      let mins = Math.floor((this.remainingSecs % 3600) / 60);
      let secs = this.remainingSecs % 60;
      let pad = n => String(n).padStart(2, '0');
      if (hrs > 0) return pad(hrs) + ':' + pad(mins) + ':' + pad(secs);
      return pad(mins) + ':' + pad(secs);
    },
    get percentage() {
      let elapsed = this.totalSecs - this.remainingSecs;
      return Math.min(100, Math.max(0, (elapsed / this.totalSecs) * 100));
    },
    updateRemainingFromClock() {
      if (this.isPaused) {
        this.remainingSecs = this.pausedRemainingSecs;
        return;
      }
      const newRemaining = Math.max(0, Math.round((this.targetEndTime - Date.now()) / 1000));
      const prevRemaining = this.remainingSecs;
      this.remainingSecs = newRemaining;
      this.pausedRemainingSecs = newRemaining;
      this.checkAlerts(prevRemaining, newRemaining);
    },
    checkAlerts(prev, current) {
      // 10 minutes remaining: continuous looping alarm until admin clicks "Oke, Matikan Alarm"
      if (prev > 600 && current <= 600 && !this.alerted10m) {
        this.alerted10m = true;
        SpaAudio.startAlarmLoop();
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: '⚠️ {{ __('Peringatan: Sisa 10 Menit!') }}',
            html: `<b class="text-white text-base">${cfg.customerName}</b><br><span style="color:#D4AF37;font-weight:bold;">${cfg.serviceName}</span><br><p class="text-xs text-gray-300 mt-2">{{ __('Sesi perawatan tersisa 10 menit!') }}</p><span class="text-[11px] text-gray-400 mt-1 block">{{ __('Tekan Oke untuk mematikan alarm suara.') }}</span>`,
            icon: 'warning',
            background: '#141414',
            color: '#fff',
            confirmButtonColor: '#D4AF37',
            confirmButtonText: '<span style="color:#111;font-weight:900">{{ __('Oke, Matikan Alarm') }}</span>',
            allowOutsideClick: false,
            allowEscapeKey: false,
          }).then(() => {
            SpaAudio.stopAlarmLoop();
          });
        }
      }

      // 5 minutes remaining: continuous looping alarm until admin clicks "Oke, Matikan Alarm"
      if (prev > 300 && current <= 300 && !this.alerted5m) {
        this.alerted5m = true;
        SpaAudio.startAlarmLoop();
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: '⚠️ {{ __('Peringatan: Sisa 5 Menit!') }}',
            html: `<b class="text-white text-base">${cfg.customerName}</b><br><span style="color:#D4AF37;font-weight:bold;">${cfg.serviceName}</span><br><p class="text-xs text-gray-300 mt-2">{{ __('Sesi perawatan tersisa 5 menit!') }}</p><span class="text-[11px] text-gray-400 mt-1 block">{{ __('Tekan Oke untuk mematikan alarm suara.') }}</span>`,
            icon: 'warning',
            background: '#141414',
            color: '#fff',
            confirmButtonColor: '#D4AF37',
            confirmButtonText: '<span style="color:#111;font-weight:900">{{ __('Oke, Matikan Alarm') }}</span>',
            allowOutsideClick: false,
            allowEscapeKey: false,
          }).then(() => {
            SpaAudio.stopAlarmLoop();
          });
        }
      }

      // Last 10 seconds down to 1: beep countdown
      if (current <= 10 && current > 0 && current < prev) {
        const pitch = 1000 + (10 - current) * 40;
        SpaAudio.playCountdownBeep(pitch);
      }

      // Session finished (0 seconds): Alarm loops, then AUTO-FINISH session immediately
      if (current === 0 && !this.alerted0s) {
        this.alerted0s = true;
        SpaAudio.startAlarmLoop();

        @if(Auth::user()->role !== 'bos')
        // 1. Immediately fire AJAX to finish session in background (no delay, no waiting for click)
        fetch('{{ url('admin/sessions') }}/' + cfg.id + '/finish', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': cfg.csrfToken,
            'X-HTTP-Method-Override': 'PATCH',
          },
          body: JSON.stringify({ auto_finish: true }),
        }).catch(() => {
          const finishForm = document.querySelector('form[action*="/sessions/' + cfg.id + '/finish"]');
          if (finishForm) finishForm.submit();
        });
        @endif

        // 2. Notify user with SweetAlert & continuous audio alarm
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: '⏰ {{ __('Waktu Sesi Habis!') }}',
            html: `<b class="text-white text-base">${cfg.customerName}</b><br><span style="color:#D4AF37;font-weight:bold;">${cfg.serviceName}</span><br><p class="text-xs text-emerald-400 font-bold mt-2">✓ {{ __('Sesi perawatan telah otomatis diselesaikan!') }}</p><span class="text-[11px] text-gray-400 mt-1 block">{{ __('Tekan tombol di bawah untuk mematikan alarm suara.') }}</span>`,
            icon: 'success',
            background: '#141414',
            color: '#fff',
            confirmButtonColor: '#D4AF37',
            confirmButtonText: '<span style="color:#111;font-weight:900">{{ __('Oke, Matikan Alarm') }}</span>',
            allowOutsideClick: false,
            allowEscapeKey: false,
          }).then(() => {
            SpaAudio.stopAlarmLoop();
            setTimeout(() => { window.location.reload(); }, 400);
          });
        }
      }
    },
    syncToServer() {
      if (this.isPaused) return;
      this.updateRemainingFromClock();
      fetch(cfg.syncUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': cfg.csrfToken
        },
        body: JSON.stringify({ remaining_seconds: this.remainingSecs })
      })
      .then(res => res.json())
      .then(data => {
        if (data && typeof data.remaining_seconds !== 'undefined') {
          if (data.is_paused !== this.isPaused) {
            this.isPaused = Boolean(data.is_paused);
            if (this.isPaused) {
              this.pausedRemainingSecs = Number(data.remaining_seconds);
            } else {
              this.targetEndTime = Date.now() + (Number(data.remaining_seconds) * 1000);
            }
          }
          const serverRemaining = Number(data.remaining_seconds);
          // Only re-anchor if server has a significant difference (e.g. extension from another tab)
          if (Math.abs(serverRemaining - this.remainingSecs) > 3) {
            this.targetEndTime = Date.now() + (serverRemaining * 1000);
            this.remainingSecs = serverRemaining;
            this.pausedRemainingSecs = serverRemaining;
          }
        }
      })
      .catch(() => {});
    },
    notifyUser(title, message, icon = 'info') {
      if (typeof Swal !== 'undefined' && (!Swal.isVisible || !Swal.isVisible())) {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 4000,
          timerProgressBar: true,
          background: '#1a1a1a',
          color: '#fff',
          didOpen: (toast) => {
            toast.style.border = '1px solid #D4AF37';
          }
        });
        Toast.fire({
          icon: icon,
          title: `<b>${title}</b>: ${message}`
        });
      }
    },
    submitExtend() {
      const form = document.getElementById('extend-form-' + cfg.id);
      const custName = cfg.customerName;
      const formattedPrice = 'AED ' + Number(this.extendPrice || 0).toFixed(2);

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: '{{ __('Konfirmasi Perpanjang Sesi') }}',
          html: `{{ __('Tambah') }} <b>+${this.extendMinutes} {{ __('Menit') }}</b> {{ __('ke sesi') }} <b>${custName}</b>?<br><span style="color:#D4AF37;font-weight:bold">{{ __('Biaya tambahan:') }} ${formattedPrice}</span>`,
          icon: 'question',
          background: '#141414',
          color: '#fff',
          showCancelButton: true,
          confirmButtonColor: '#D4AF37',
          cancelButtonColor: '#4b5563',
          confirmButtonText: '{{ __('Ya, Perpanjang!') }}',
          cancelButtonText: '{{ __('Batal') }}',
        }).then((result) => {
          if (result.isConfirmed && form) {
            form.submit();
          }
        });
      } else {
        if (form) form.submit();
      }
    },
    init() {
      this.updateRemainingFromClock();

      // High-precision Web Worker ticker (runs on background thread, immune to main-thread UI lag and tab throttling)
      try {
        const workerBlob = new Blob([`
          let timer = null;
          self.onmessage = function(e) {
            if (e.data === 'start') {
              if (timer) clearInterval(timer);
              timer = setInterval(() => self.postMessage('tick'), 1000);
            } else if (e.data === 'stop') {
              if (timer) clearInterval(timer);
            }
          };
        `], { type: 'application/javascript' });
        const worker = new Worker(URL.createObjectURL(workerBlob));
        worker.onmessage = () => {
          this.updateRemainingFromClock();
        };
        worker.postMessage('start');
      } catch (err) {
        // Fallback 1-second interval ticker
        setInterval(() => {
          this.updateRemainingFromClock();
        }, 1000);
      }

      // Instantly sync wall-clock time if tab was backgrounded, minimized, or switched
      ['visibilitychange', 'focus', 'pageshow'].forEach(evt => {
        document.addEventListener(evt, () => {
          this.updateRemainingFromClock();
        });
        window.addEventListener(evt, () => {
          this.updateRemainingFromClock();
        });
      });

      // Periodic server sync every 10 seconds
      setInterval(() => {
        if (!this.isPaused) {
          this.syncToServer();
        }
      }, 10000);

      // Sync on leaving page
      window.addEventListener('beforeunload', () => {
        if (!this.isPaused) {
          navigator.sendBeacon(
            cfg.syncUrl,
            new Blob([JSON.stringify({ remaining_seconds: this.remainingSecs, _token: cfg.csrfToken })], { type: 'application/json' })
          );
        }
      });
    }
  };
}

document.addEventListener('DOMContentLoaded', () => {
  const targetCode = @js($targetBookingCode ?? null);
  if (targetCode) {
    const el = document.getElementById('session-booking-' + targetCode);
    if (el) {
      setTimeout(() => {
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }, 250);
    }
  }
});
</script>
@endpush
