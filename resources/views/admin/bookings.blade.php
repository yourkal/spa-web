@extends('layouts.admin')

@section('title', (Auth::user()->role === 'bos' ? __('Riwayat Booking') : __('Kelola Booking')) . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', Auth::user()->role === 'bos' ? __('Riwayat Booking & Reservasi') : __('Kelola Reservasi Booking'))
@section('header_subtitle', Auth::user()->role === 'bos' ? __('Pemantauan seluruh jadwal reservasi treatment spa pelanggan') : __('Pantau status jadwal, filter reservasi, dan input booking manual kasir'))

@section('content')
<div class="space-y-6" x-data="adminBookingApp()">

  {{-- ── Top Action & Filter Bar ────────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h2 class="text-sm font-black text-white">{{ __('Daftar Seluruh Reservasi') }}</h2>
          @if(isset($newBookingsCount) && $newBookingsCount > 0)
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 animate-pulse">
              <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
              {{ $newBookingsCount }} {{ __('Order Baru') }}
            </span>
          @endif
        </div>
        <p class="text-[11px] text-gray-500">{{ __('Kelola reservasi treatment dari pelanggan atau kasir') }}</p>
      </div>
      <div class="flex flex-wrap items-center gap-2">
        <a href="{{ Auth::user()->role === 'bos' ? route('bos.bookings.export.excel', request()->all()) : route('admin.bookings.export.excel', request()->all()) }}"
           class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
           style="background:#1a1a1a;border:1px solid #10B98150;color:#34D399;">
          <i data-lucide="sheet" class="w-4 h-4 text-emerald-400"></i>
          <span>{{ __('Unduh Excel') }}</span>
        </a>

        @if(Auth::user()->role !== 'bos')
        <button @click="createModal = true"
                class="px-4 py-2.5 rounded-xl font-black text-xs shadow-md transition-all flex items-center gap-2 self-start sm:self-auto cursor-pointer"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          <i data-lucide="plus-circle" class="w-4 h-4"></i>
          <span>{{ __('Tambah Booking Manual') }}</span>
        </button>
        @endif
      </div>
    </div>

    {{-- Filters Form --}}
    <form action="{{ Auth::user()->role === 'bos' ? route('bos.bookings') : route('admin.bookings') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2">
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
          <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
          <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
          <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
          <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
        </select>
      </div>

      <div>
        <input type="date" name="date" value="{{ request('date') }}"
               class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
               style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
      </div>

      <div class="flex gap-2">
        <button type="submit"
                class="flex-1 py-2.5 rounded-xl font-bold text-xs transition-all cursor-pointer"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Terapkan Filter') }}
        </button>
        @if(request()->hasAny(['search', 'status', 'date']))
          <a href="{{ Auth::user()->role === 'bos' ? route('bos.bookings') : route('admin.bookings') }}"
             class="px-3 py-2.5 rounded-xl text-xs font-bold text-gray-400 hover:text-white flex items-center justify-center"
             style="background:#1a1a1a;border:1px solid #D4AF3730;">
            {{ __('Reset') }}
          </a>
        @endif
      </div>
    </form>
  </div>

  {{-- ── Bookings Table ─────────────────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-xs">
        <thead>
          <tr class="border-b text-gray-400 uppercase text-[10px] tracking-wider" style="border-color:#D4AF3720;">
            <th class="py-2.5 px-3 text-center w-12">{{ __('No') }}</th>
            <th class="py-2.5 px-3">{{ __('Kode Booking') }}</th>
            <th class="py-2.5 px-3">{{ __('Pelanggan') }}</th>
            <th class="py-2.5 px-3">{{ __('Layanan') }}</th>
            <th class="py-2.5 px-3">{{ __('Terapis') }}</th>
            <th class="py-2.5 px-3">{{ __('Jadwal Kunjungan') }}</th>
            <th class="py-2.5 px-3">{{ __('Total (AED)') }}</th>
            <th class="py-2.5 px-3">{{ __('Status') }}</th>
            <th class="py-2.5 px-3 text-center">{{ __('Timer') }}</th>
            <th class="py-2.5 px-3 text-right">{{ __('Aksi') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $b)
          @php
            $isNewBooking = (bool) $b->is_new;
            $rowNo = (($bookings->currentPage() - 1) * $bookings->perPage()) + $loop->iteration;
            $isFuture = false;
            $bookingDateTime = null;
            if ($b->booking_date && $b->booking_time) {
                try {
                    $bookingDateTime = \Illuminate\Support\Carbon::parse($b->booking_date->format('Y-m-d') . ' ' . $b->booking_time);
                    $isFuture = now()->lt($bookingDateTime);
                } catch (\Exception $e) {}
            }
          @endphp
          <tr class="transition-colors hover:bg-white/5 {{ $isNewBooking ? 'bg-emerald-950/20' : '' }}"
              style="border-bottom:1px solid #D4AF3710; {{ $isNewBooking ? 'border-left:3px solid #22c55e;' : '' }}">
            <td class="py-3 px-3 text-center font-bold text-gray-400 font-mono">{{ $rowNo }}</td>
            <td class="py-3 px-3">
              <a href="{{ (Auth::user()->role === 'bos' ? route('bos.sessions') : route('admin.sessions')) }}?booking_code={{ $b->booking_code }}"
                 class="font-black text-xs hover:underline flex items-center gap-1.5 transition-colors group"
                 style="color:#D4AF37;"
                 title="{{ __('Buka Sesi & Timer :code', ['code' => $b->booking_code]) }}">
                <span>{{ $b->booking_code }}</span>
                <i data-lucide="arrow-up-right" class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity"></i>
              </a>
              @if($isNewBooking)
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 mt-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                  {{ __('ORDER BARU') }}
                </span>
              @endif
            </td>
            <td class="py-3 px-3">
              <span class="font-bold text-white block">{{ $b->customer_name }}</span>
              <span class="text-[10px] text-gray-500">{{ $b->customer_phone }}</span>
            </td>
            <td class="py-3 px-3 text-gray-300 font-semibold">{{ __($b->service_name) }}</td>
            <td class="py-3 px-3 text-gray-400">{{ $b->therapist_name }}</td>
            <td class="py-3 px-3 text-gray-400">
              <div class="flex items-center gap-1.5 flex-wrap">
                <span>{{ $b->booking_date->format('d M Y') }} - {{ $b->booking_time }}</span>
                @if($isFuture && $b->status === 'confirmed')
                  <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[8px] font-black bg-amber-500/20 text-amber-300 border border-amber-500/40"
                        title="{{ __('Jadwal booking belum tiba. Aksi dan timer terkunci sebelum jam ini.') }}">
                    <i data-lucide="lock" class="w-2.5 h-2.5"></i>
                    {{ __('Belum Jamnya') }}
                  </span>
                @endif
              </div>
              <span class="text-[10px] font-bold" style="color:#D4AF37;">({{ $b->duration }} {{ __('Menit') }})</span>
            </td>
            <td class="py-3 px-3 font-black text-white">AED {{ number_format($b->price, 2) }}</td>
            <td class="py-3 px-3">
              @if(Auth::user()->role === 'bos' || $b->status === 'completed')
                <span class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase inline-flex items-center gap-1
                      @if($b->status === 'completed') badge-completed
                      @elseif($b->status === 'confirmed') badge-confirmed
                      @elseif($b->status === 'in_progress') badge-busy
                      @else badge-cancelled
                      @endif" title="{{ $b->status === 'completed' ? __('Treatment telah selesai. Status terkunci permanen.') : '' }}">
                  @if($b->status === 'completed')
                    <i data-lucide="check-check" class="w-3 h-3 text-emerald-400"></i>
                  @endif
                  <span>{{ strtoupper($b->status) }}</span>
                </span>
              @else
                <form action="{{ route('admin.bookings.status', $b) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  @php
                    $hasRemaining = 0;
                    if ($b->activeSession && in_array($b->activeSession->status, ['running', 'paused'])) {
                        $hasRemaining = max(0, (int) $b->activeSession->remaining_seconds);
                    }
                  @endphp
                  <select name="status"
                          data-current="{{ $b->status }}"
                          data-remaining="{{ $hasRemaining }}"
                          data-is-future="{{ $isFuture ? '1' : '0' }}"
                          data-scheduled-at="{{ $bookingDateTime ? $bookingDateTime->toIso8601String() : '' }}"
                          data-scheduled-time="{{ $b->booking_time }}"
                          data-scheduled-date="{{ $b->booking_date->format('d M Y') }}"
                          onchange="handleStatusChange(this)"
                          class="text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase outline-none cursor-pointer
                          @if($b->status === 'completed') badge-completed
                          @elseif($b->status === 'confirmed') badge-confirmed
                          @elseif($b->status === 'in_progress') badge-busy
                          @else badge-cancelled
                          @endif">
                    <option value="confirmed" {{ $b->status === 'confirmed' ? 'selected' : '' }}>{{ __('CONFIRMED') }}</option>
                    <option value="in_progress" {{ $b->status === 'in_progress' ? 'selected' : '' }} {{ ($isFuture && $b->status !== 'in_progress') ? 'disabled' : '' }}>
                      {{ __('IN PROGRESS') }}{{ ($isFuture && $b->status !== 'in_progress') ? ' (' . __('Terkunci') . ')' : '' }}
                    </option>
                    <option value="completed" {{ $b->status === 'completed' ? 'selected' : '' }} {{ ($isFuture && $b->status !== 'completed') ? 'disabled' : '' }}>
                      {{ __('COMPLETED') }}{{ ($isFuture && $b->status !== 'completed') ? ' (' . __('Terkunci') . ')' : '' }}
                    </option>
                    <option value="cancelled" {{ $b->status === 'cancelled' ? 'selected' : '' }}>{{ __('CANCELLED') }}</option>
                  </select>
                </form>
              @endif
            </td>
            <td class="py-3 px-3 text-center">
              @if($b->status === 'in_progress')
                @php
                  $ses = $b->activeSession;
                  $rem = $ses ? max(0, (int) $ses->remaining_seconds) : 0;
                  $m = floor($rem / 60);
                  $s = $rem % 60;
                @endphp
                <a href="{{ (Auth::user()->role === 'bos' ? route('bos.sessions') : route('admin.sessions')) }}?booking_code={{ $b->booking_code }}"
                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black transition-all hover:scale-105 shadow-md"
                   style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;"
                   title="{{ __('Buka Timer Sesi Ini (:code)', ['code' => $b->booking_code]) }}">
                  <i data-lucide="timer" class="w-3.5 h-3.5 animate-pulse"></i>
                  <span>{{ sprintf('%02d:%02d', $m, $s) }}</span>
                </a>
              @elseif($b->status === 'confirmed')
                @if($isFuture)
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold text-gray-400 bg-neutral-900 border border-neutral-700 cursor-not-allowed"
                        title="{{ __('Booking dijadwalkan pukul :time. Timer belum dapat dimulai.', ['time' => $b->booking_time]) }}">
                    <i data-lucide="lock" class="w-3 h-3 text-amber-400"></i>
                    <span>{{ __('Terkunci') }} ({{ $b->booking_time }})</span>
                  </span>
                @else
                  <a href="{{ (Auth::user()->role === 'bos' ? route('bos.sessions') : route('admin.sessions')) }}?booking_code={{ $b->booking_code }}"
                     class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold text-amber-300 hover:text-white bg-amber-950/40 border border-amber-500/30 transition-all hover:bg-amber-900/40"
                     title="{{ __('Sesi belum dimulai. Klik untuk cek timer') }}">
                    <i data-lucide="clock" class="w-3 h-3 text-amber-400"></i>
                    <span>{{ __('Belum Aktif') }}</span>
                  </a>
                @endif
              @elseif($b->status === 'completed')
                <a href="{{ (Auth::user()->role === 'bos' ? route('bos.sessions') : route('admin.sessions')) }}?booking_code={{ $b->booking_code }}"
                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold text-emerald-400 bg-emerald-950/30 border border-emerald-500/20 hover:bg-emerald-900/30 transition-all"
                   title="{{ __('Sesi telah selesai') }}">
                  <i data-lucide="check" class="w-3 h-3"></i>
                  <span>{{ __('Selesai') }}</span>
                </a>
              @else
                <span class="text-gray-600 text-[11px]">-</span>
              @endif
            </td>
            <td class="py-3 px-3 text-right">
              <div class="flex items-center justify-end gap-1">
                <a href="{{ Auth::user()->role === 'bos' ? route('bos.orders.show', $b) : route('admin.bookings.show', $b) }}"
                   class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-xl transition-all"
                   title="{{ __('Detail Order') }}">
                  <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
                @if(Auth::user()->role !== 'bos')
                <form action="{{ route('admin.bookings.destroy', $b) }}" method="POST"
                      data-confirm="{{ __('Booking') }} {{ $b->booking_code }} {{ __('akan dihapus permanen!') }}"
                      data-confirm-title="{{ __('Hapus Booking') }}" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="p-2 text-red-400 hover:text-red-300 hover:bg-red-950/40 rounded-xl transition-all cursor-pointer"
                          title="{{ __('Hapus Booking') }}">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="10" class="py-12 text-center text-xs text-gray-500 font-semibold">
              {{ __('Tidak ada data booking yang ditemukan.') }}
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="pt-4" style="border-top:1px solid #D4AF3715;">
      {{ $bookings->links() }}
    </div>
  </div>

  @if(Auth::user()->role !== 'bos')
  {{-- ── Modal Tambah Booking Manual (Dengan Modal Layanan Card) ──────────── --}}
  <div x-show="createModal" x-transition
       class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div @click.outside="createModal = false"
         class="rounded-2xl max-w-xl w-full p-6 sm:p-7 space-y-5 shadow-2xl relative"
         style="background:#141414;border:1px solid #D4AF3740;">

      <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3720;">
        <h3 class="font-black text-sm text-white flex items-center gap-2">
          <i data-lucide="plus-circle" class="w-4 h-4" style="color:#D4AF37;"></i>
          {{ __('Tambah Booking Manual') }} ({{ __('Kasir') }}/Admin)
        </h3>
        <button @click="createModal = false" class="text-gray-400 hover:text-white cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Nama Pelanggan') }}</label>
            <input type="text" name="customer_name" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('No. Telepon WhatsApp') }}</label>
            <input type="text" name="customer_phone" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        {{-- Layanan Spa Card Selector --}}
        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Layanan Spa') }}</label>
          <input type="hidden" name="spa_service_id" :value="selectedServiceId" required>

          <div class="p-3 rounded-xl flex items-center justify-between gap-3 transition-all"
               style="background:#1a1a1a;border:1.5px solid #D4AF3740;">
            <div class="flex items-center gap-3 min-w-0">
              <img :src="selectedService ? (selectedService.image_url || 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=200&q=80') : 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=200&q=80'"
                   class="w-12 h-12 rounded-xl object-cover shrink-0" style="border:1px solid #D4AF3730;">
              <div class="min-w-0">
                <p class="font-extrabold text-xs text-white truncate" x-text="selectedService ? selectedService.name : '{{ __('Belum ada layanan dipilih') }}'"></p>
                <div class="flex items-center gap-2 mt-0.5">
                  <span class="text-[11px] font-black" style="color:#D4AF37;" x-text="selectedService ? ('AED ' + Number(selectedService.price).toFixed(2) + ' / ' + selectedService.duration + ' min') : ''"></span>
                  <span class="text-[9px] px-2 py-0.2 rounded-full uppercase font-bold"
                        style="background:#D4AF3715;color:#F5DF93;"
                        x-text="selectedService ? (selectedService.category || 'Spa') : ''"></span>
                </div>
              </div>
            </div>
            <button type="button" @click="serviceModal = true"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 shadow cursor-pointer hover:opacity-90"
                    style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
              <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
              <span>{{ __('Pilih / Ganti Layanan') }}</span>
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Therapist') }}</label>
            <select name="therapist_id" required
                    class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
              @foreach($therapists as $th)
                <option value="{{ $th->id }}">{{ $th->name }} ({{ $th->speciality }})</option>
              @endforeach
            </select>
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Metode Pembayaran') }}</label>
            <select name="payment_method" required
                    class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
              <option value="Cash">{{ __('Cash') }}</option>
              <option value="Card">{{ __('Card') }}</option>
              <option value="Transfer">{{ __('Transfer') }}</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Tanggal Kunjungan') }}</label>
            <input type="date" name="booking_date" value="{{ date('Y-m-d') }}" required
                   class="w-full px-3 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Jam Kunjungan') }}</label>
            <input type="time" name="booking_time" value="14:00" required
                   class="w-full px-3 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Durasi (Menit)') }}</label>
            <input type="number" name="duration" x-model.number="duration" min="30" step="30" required
                   class="w-full px-3 py-2.5 rounded-xl text-xs font-semibold outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        <button type="submit"
                class="w-full py-3 rounded-xl font-black text-xs shadow-md transition-all mt-2 cursor-pointer hover:opacity-95"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Simpan Booking Manual') }}
        </button>
      </form>
    </div>
  </div>

  {{-- ── Modal Pemilihan Layanan Card (Seperti Customer Booking) ────────── --}}
  <div x-show="serviceModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       @keydown.escape.window="serviceModal = false"
       class="fixed inset-0 z-[60] flex items-center justify-center p-3 sm:p-6 overflow-y-auto"
       style="background:rgba(0,0,0,0.85);backdrop-filter:blur(8px);display:none;">

    <div x-show="serviceModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.outside="serviceModal = false"
         class="w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl flex flex-col my-auto max-h-[88vh]"
         style="background:#141414;border:1.5px solid #D4AF3750;">

      <!-- Modal Header -->
      <div class="p-5 border-b relative shrink-0" style="background:#16140d;border-color:#D4AF3725;">
        <div class="flex items-center justify-between gap-4 mb-3">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-lg"
                 style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;">
              <i data-lucide="sparkles" class="w-4 h-4"></i>
            </div>
            <div>
              <h3 class="text-base font-display font-black text-white tracking-tight">
                {{ __('Pilih Layanan Spa untuk Booking') }}
              </h3>
              <p class="text-[11px] text-gray-400">{{ __('Pilih salah satu menu perawatan spa dari katalog') }}</p>
            </div>
          </div>

          <button type="button" @click="serviceModal = false"
            class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all cursor-pointer"
            style="background:#1a1a1a;border:1px solid #D4AF3730;">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        </div>

        <!-- Search & Filter Controls -->
        <div class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center justify-between">
          <div class="relative flex-1">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-gray-500"></i>
            <input type="text" x-model="serviceSearch"
              placeholder="{{ __('Cari nama layanan...') }}"
              class="w-full pl-9 pr-3 py-2 rounded-xl text-xs font-semibold outline-none transition-all"
              style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;">
          </div>

          <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
            <template x-for="cat in categories" :key="cat.value">
              <button type="button" @click="selectedCategory = cat.value"
                class="px-3 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all cursor-pointer"
                :style="selectedCategory === cat.value
                  ? 'background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;'
                  : 'background:#1a1a1a;border:1px solid #D4AF3730;color:#9ca3af;'"
                x-text="cat.label">
              </button>
            </template>
          </div>
        </div>
      </div>

      <!-- Modal Body (Cards Grid) -->
      <div class="p-5 overflow-y-auto space-y-3 flex-1 scrollbar-thin" style="background:#0e0e0e;">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
          <template x-for="svc in filteredServices" :key="svc.id">
            <div class="rounded-2xl overflow-hidden flex flex-col justify-between transition-all group hover:border-yellow-400/60 cursor-pointer"
                 @click="selectService(svc)"
                 :style="selectedServiceId === Number(svc.id)
                   ? 'background:#191503;border:2px solid #D4AF37;box-shadow:0 0 16px #D4AF3730;'
                   : 'background:#141414;border:1px solid #D4AF3725;'">

              <div class="relative h-32 overflow-hidden">
                <img :src="svc.image_url || 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=400&q=80'"
                     :alt="svc.name"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0" style="background:linear-gradient(to top,#0a0a0aEE,transparent 60%);"></div>

                <div class="absolute top-2 right-2 px-2 py-0.5 rounded-full backdrop-blur-md text-amber-400 text-[10px] font-black flex items-center gap-1"
                     style="background:rgba(0,0,0,0.75);border:1px solid #D4AF3740;">
                  <i data-lucide="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>
                  <span x-text="Number(svc.rating || 5.0).toFixed(1)"></span>
                </div>

                <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-full backdrop-blur-md text-[9px] font-bold"
                     style="background:#D4AF3725;border:1px solid #D4AF3740;color:#F5DF93;"
                     x-text="svc.category || 'Spa'">
                </div>
              </div>

              <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                <div>
                  <h4 class="font-extrabold text-xs text-white truncate" x-text="svc.name"></h4>
                  <p class="text-[10px] text-gray-400 line-clamp-1 leading-relaxed mt-0.5" x-text="svc.description"></p>
                </div>

                <div class="pt-2 border-t flex items-center justify-between text-xs" style="border-color:#D4AF3715;">
                  <span class="text-gray-400 text-[10px]" x-text="svc.duration + ' {{ __('Menit') }}'"></span>
                  <span class="font-black text-xs" style="color:#D4AF37;"
                        x-text="'AED ' + Number(svc.price).toLocaleString('en-AE', {minimumFractionDigits:2, maximumFractionDigits:2})">
                  </span>
                </div>

                <button type="button" @click.stop="selectService(svc)"
                        class="w-full py-2 px-2.5 rounded-xl font-black text-xs transition-all flex items-center justify-center gap-1 shadow cursor-pointer hover:opacity-90"
                        :style="selectedServiceId === Number(svc.id)
                          ? 'background:#2b2203;border:1px solid #D4AF37;color:#F5DF93;'
                          : 'background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;'">
                  <i :data-lucide="selectedServiceId === Number(svc.id) ? 'check-circle' : 'plus-circle'" class="w-3.5 h-3.5"></i>
                  <span x-text="selectedServiceId === Number(svc.id) ? '{{ __('Layanan Terpilih') }}' : '{{ __('Pilih Layanan Ini') }}'"></span>
                </button>
              </div>
            </div>
          </template>
        </div>
      </div>

      <div class="p-3.5 border-t flex items-center justify-between text-xs shrink-0"
           style="background:#141414;border-color:#D4AF3720;">
        <span class="text-gray-400">
          <span class="text-white font-bold" x-text="filteredServices.length"></span> {{ __('layanan tersedia') }}
        </span>
        <button type="button" @click="serviceModal = false"
                class="px-4 py-1.5 rounded-xl font-bold text-xs transition-all cursor-pointer"
                style="background:#1a1a1a;border:1px solid #D4AF3730;color:#9ca3af;">
          {{ __('Tutup') }}
        </button>
      </div>

    </div>
  </div>
  @endif

</div>
@endsection

@push('scripts')
@php
  $adminServicesJson = $services->map(fn($s) => [
      'id' => $s->id,
      'name' => __($s->name),
      'raw_name' => $s->name,
      'category' => __($s->category),
      'raw_category' => $s->category,
      'description' => __($s->description),
      'duration' => (int) $s->duration,
      'price' => (float) $s->price,
      'pricing_tiers' => $s->pricing_tiers,
      'image_url' => $s->image_url,
      'rating' => (float) ($s->rating ?? 5.0),
      'is_active' => (bool) $s->is_active,
  ]);
@endphp
<script>
function adminBookingApp() {
  const allServices = @json($adminServicesJson ?? []);
  const initialId = allServices.length > 0 ? Number(allServices[0].id) : 0;
  const initialDuration = allServices.length > 0 ? (Number(allServices[0].duration) || 60) : 60;

  return {
    createModal: false,
    serviceModal: false,
    services: allServices,
    selectedServiceId: initialId,
    duration: initialDuration,
    serviceSearch: '',
    selectedCategory: 'ALL',

    init() {
      this.$watch('serviceModal', (val) => {
        if (val) {
          this.$nextTick(() => {
            if (window.lucide) window.lucide.createIcons();
          });
        }
      });
      this.$watch('createModal', (val) => {
        if (val) {
          this.$nextTick(() => {
            if (window.lucide) window.lucide.createIcons();
          });
        }
      });
    },

    get selectedService() {
      return this.services.find(s => Number(s.id) === Number(this.selectedServiceId)) || this.services[0] || null;
    },

    get categories() {
      const allText = '{{ __('Semua') }}';
      const cats = [{ label: allText, value: 'ALL' }];
      (this.services || []).forEach(s => {
        const catVal = s.raw_category || s.category;
        const catLabel = s.category;
        if (catVal && !cats.some(c => c.value === catVal)) {
          cats.push({ label: catLabel, value: catVal });
        }
      });
      return cats;
    },

    get filteredServices() {
      return (this.services || []).filter(s => {
        const matchCat = this.selectedCategory === 'ALL' || (s.raw_category || s.category) === this.selectedCategory;
        const q = (this.serviceSearch || '').toLowerCase().trim();
        const matchSearch = !q ||
          (s.name && s.name.toLowerCase().includes(q)) ||
          (s.raw_name && s.raw_name.toLowerCase().includes(q)) ||
          (s.description && s.description.toLowerCase().includes(q));
        return matchCat && matchSearch;
      });
    },

    selectService(svc) {
      if (!svc) return;
      this.selectedServiceId = Number(svc.id);
      this.duration = Number(svc.duration) || 60;
      this.serviceModal = false;
      this.$nextTick(() => {
        if (window.lucide) window.lucide.createIcons();
      });
    }
  };
}
</script>
@endpush
