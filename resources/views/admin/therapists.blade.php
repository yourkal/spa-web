@extends('layouts.admin')

@section('title', __('Kelola Therapist') . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', __('Manajemen Terapis & Staf'))
@section('header_subtitle', __('Tambah, edit, atau kelola status ketersediaan staf terapis profesional'))

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, activeEdit: {} }">

  {{-- ── Action Bar ─────────────────────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4"
       style="background:#141414;border:1px solid #D4AF3720;">
    <div>
      <h2 class="text-sm font-black text-white">{{ __('Daftar Therapist Professional') }}</h2>
      <p class="text-[11px] text-gray-500">{{ __('Tim staf terapis pria bersertifikat yang siap melayani treatment') }}</p>
    </div>
    <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
      <a href="{{ route('admin.therapists.export.excel') }}"
         class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
         style="background:#1a1a1a;border:1px solid #10B98150;color:#34D399;">
        <i data-lucide="sheet" class="w-4 h-4 text-emerald-400"></i>
        <span>{{ __('Unduh Excel') }}</span>
      </a>

      <button @click="createModal = true"
              class="px-4 py-2.5 rounded-xl font-black text-xs shadow-md transition-all flex items-center gap-2"
              style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
        <i data-lucide="plus-circle" class="w-4 h-4"></i>
        <span>{{ __('Tambah Therapist Baru') }}</span>
      </button>
    </div>
  </div>

  {{-- ── Therapists Grid ────────────────────────────────────────────────── --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($therapists as $th)
    @php
      $activeBookings = $th->bookings ? $th->bookings->whereIn('status', ['confirmed', 'in_progress']) : collect();
      $hasActiveBooking = $activeBookings->isNotEmpty();
      $isBooked = ($th->status === 'serving') || $hasActiveBooking;
      $firstBooking = $activeBookings->first();
    @endphp
    <div class="rounded-2xl p-5 space-y-4 flex flex-col justify-between card-lift"
         style="background:#141414;border:1px solid #D4AF3720;">
      <div class="space-y-3">
        <div class="flex items-center gap-4">
          <img src="{{ $th->avatar }}" class="w-16 h-16 rounded-2xl object-cover shrink-0 shadow-md"
               style="border:1.5px solid #D4AF37;">
          <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between gap-1 flex-wrap">
              <h3 class="font-display font-black text-base text-white truncate">{{ $th->name }}</h3>
              @if($isBooked)
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase shrink-0"
                      style="background:#7f1d1d;color:#fca5a5;border:1px solid #ef444450;">
                  {{ __('Sudah Dibooking') }}
                </span>
              @endif
            </div>
            <p class="text-xs text-gray-400 truncate">{{ $th->speciality }}</p>
            <div class="flex items-center gap-2 text-xs font-bold mt-1" style="color:#D4AF37;">
              <span class="flex items-center gap-1">
                <i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i> {{ $th->rating }}
              </span>
              <span class="text-gray-500">• {{ $th->experience }} {{ __('Thn Exp') }}</span>
            </div>
          </div>
        </div>

        @if($hasActiveBooking && $firstBooking)
          <div class="px-3 py-1.5 rounded-xl text-[10px] font-bold flex items-center justify-between gap-2"
               style="background:#7f1d1d25;border:1px solid #ef444440;color:#fca5a5;">
            <div class="flex items-center gap-1.5 min-w-0 truncate">
              <i data-lucide="calendar-check" class="w-3.5 h-3.5 text-rose-400 shrink-0"></i>
              <span class="truncate">{{ __('Booking') }}: {{ $firstBooking->booking_code }} ({{ $firstBooking->customer_name }})</span>
            </div>
            <span class="shrink-0 font-extrabold text-amber-300">{{ $firstBooking->booking_date->format('d M') }} • {{ $firstBooking->booking_time }}</span>
          </div>
        @endif

        {{-- Status Selector & Total Bookings --}}
        <div class="pt-3 flex items-center justify-between text-xs" style="border-top:1px solid #D4AF3715;">
          <span class="text-gray-400 font-medium">{{ __('Total Booking') }}: <strong class="text-white">{{ $th->bookings_count }}</strong></span>

          <form action="{{ route('admin.therapists.status', $th) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <select name="status" onchange="this.form.submit()"
                    class="text-[10px] py-1 px-2.5 rounded-full font-extrabold uppercase outline-none cursor-pointer"
                    style="background:#1a1a1a;border:1px solid #D4AF3740;color:#e5e7eb;">
              <option value="ready" {{ $th->status === 'ready' ? 'selected' : '' }}>🟢 {{ __('Ready') }}</option>
              <option value="not_ready" {{ $th->status === 'not_ready' ? 'selected' : '' }}>🔴 {{ __('Belum Ready') }}</option>
              <option value="serving" {{ $th->status === 'serving' ? 'selected' : '' }}>🟡 {{ __('Sedang Melayani') }}{{ $th->status === 'serving' ? ' (' . __('Sudah Dibooking') . ')' : '' }}</option>
              <option value="off" {{ $th->status === 'off' ? 'selected' : '' }}>⚪ Off</option>
            </select>
          </form>
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="pt-3 flex items-center justify-between text-xs" style="border-top:1px solid #D4AF3715;">
        <button @click="activeEdit = {{ json_encode($th) }}; editModal = true"
                class="font-bold hover:underline flex items-center gap-1 transition-all" style="color:#D4AF37;">
          <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> {{ __('Edit') }}
        </button>

        <form action="{{ route('admin.therapists.destroy', $th) }}" method="POST"
              data-confirm="{{ __('Data therapist') }} '{{ $th->name }}' {{ __('akan dihapus permanen!') }}" data-confirm-title="{{ __('Hapus Therapist') }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="text-red-400 hover:text-red-300 font-bold hover:underline flex items-center gap-1">
            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> {{ __('Hapus') }}
          </button>
        </form>
      </div>
    </div>
    @endforeach
  </div>

  {{-- ── Modal Tambah Therapist ─────────────────────────────────────────── --}}
  <div x-show="createModal" x-transition
       class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div @click.outside="createModal = false"
         class="rounded-2xl max-w-md w-full p-6 sm:p-7 space-y-5 shadow-2xl relative"
         style="background:#141414;border:1px solid #D4AF3740;">
      <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3720;">
        <h3 class="font-black text-sm text-white flex items-center gap-2">
          <i data-lucide="plus-circle" class="w-4 h-4" style="color:#D4AF37;"></i>
          {{ __('Tambah Therapist Baru') }}
        </h3>
        <button @click="createModal = false" class="text-gray-400 hover:text-white">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form action="{{ route('admin.therapists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Nama Lengkap') }} Therapist <span class="text-red-400">*</span></label>
          <input type="text" name="name" required
                 class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>

        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Spesialisasi') }} <span class="text-red-400">*</span></label>
          <input type="text" name="speciality" placeholder="Contoh: Balinese & Sports Massage" required
                 class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Pengalaman (Tahun)') }}</label>
            <input type="number" name="experience" value="5" min="0" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Rating (0-5)</label>
            <input type="number" step="0.1" name="rating" value="4.9" min="0" max="5"
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Nomor HP') }}</label>
            <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Status') }}</label>
            <select name="status"
                    class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
              <option value="ready">🟢 {{ __('Ready') }}</option>
              <option value="not_ready">🔴 {{ __('Belum Ready') }}</option>
              <option value="serving">🟡 {{ __('Sedang Melayani') }} ({{ __('Sudah Dibooking') }})</option>
              <option value="off">⚪ Off</option>
            </select>
          </div>
        </div>

        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Upload Foto Profil') }} <span class="text-red-400">*</span></label>
          <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" required
                 class="w-full p-2 border border-dashed rounded-xl text-xs text-gray-400 cursor-pointer"
                 style="background:#1a1a1a;border-color:#D4AF3740;">
        </div>

        <button type="submit"
                class="w-full py-3 rounded-xl font-black text-xs shadow-md transition-all mt-2"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Simpan Therapist Baru') }}
        </button>
      </form>
    </div>
  </div>

  {{-- ── Modal Edit Therapist ───────────────────────────────────────────── --}}
  <div x-show="editModal" x-transition
       class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div @click.outside="editModal = false"
         class="rounded-2xl max-w-md w-full p-6 sm:p-7 space-y-5 shadow-2xl relative"
         style="background:#141414;border:1px solid #D4AF3740;">
      <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3720;">
        <h3 class="font-black text-sm text-white flex items-center gap-2">
          <i data-lucide="edit-3" class="w-4 h-4" style="color:#D4AF37;"></i>
          {{ __('Edit Data Therapist') }}
        </h3>
        <button @click="editModal = false" class="text-gray-400 hover:text-white">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form :action="'/admin/therapists/' + activeEdit.id" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Nama Lengkap') }} Therapist</label>
          <input type="text" name="name" x-model="activeEdit.name" required
                 class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>

        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Spesialisasi') }}</label>
          <input type="text" name="speciality" x-model="activeEdit.speciality" required
                 class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Pengalaman (Tahun)') }}</label>
            <input type="number" name="experience" x-model="activeEdit.experience" min="0" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Rating (0-5)</label>
            <input type="number" step="0.1" name="rating" x-model="activeEdit.rating" min="0" max="5"
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Nomor HP') }}</label>
            <input type="text" name="phone" x-model="activeEdit.phone"
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Status') }}</label>
            <select name="status" x-model="activeEdit.status"
                    class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
              <option value="ready">🟢 {{ __('Ready') }}</option>
              <option value="not_ready">🔴 {{ __('Belum Ready') }}</option>
              <option value="serving">🟡 {{ __('Sedang Melayani') }} ({{ __('Sudah Dibooking') }})</option>
              <option value="off">⚪ Off</option>
            </select>
          </div>
        </div>

        <div class="space-y-2">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Ganti Foto Profil') }}</label>
          <div class="flex items-center gap-3">
            <img :src="activeEdit.avatar" class="w-12 h-12 rounded-xl object-cover shrink-0" style="border:1px solid #D4AF3740;">
            <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                   class="w-full p-2 border border-dashed rounded-xl text-xs text-gray-400 cursor-pointer"
                   style="background:#1a1a1a;border-color:#D4AF3740;">
          </div>
          <p class="text-[10px] text-gray-500">{{ __('Kosongkan jika tidak ingin mengubah foto') }} (Format JPG, PNG, WEBP).</p>
        </div>

        <button type="submit"
                class="w-full py-3 rounded-xl font-black text-xs shadow-md transition-all mt-2"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Perbarui Data Therapist') }}
        </button>
      </form>
    </div>
  </div>

</div>
@endsection
