@extends('layouts.admin')

@section('title', __('Biodata Saya') . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', __('Biodata Saya'))
@section('header_subtitle', __('Kelola data pribadi akun Anda'))

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

  {{-- Profile Card --}}
  <div class="p-6 rounded-2xl text-center space-y-4" style="background:linear-gradient(135deg,#1a1500,#1a1a1a);border:1px solid #D4AF3740;">
    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full mx-auto flex items-center justify-center text-2xl sm:text-3xl font-black"
         style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;box-shadow:0 0 32px #D4AF3740;">
      {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
    <div>
      <p class="font-display font-black text-lg sm:text-xl" style="color:#D4AF37;">{{ $user->name }}</p>
      <span class="text-[11px] font-bold uppercase tracking-widest text-gray-500">{{ ucfirst($user->role) }}</span>
    </div>
    <div class="flex items-center justify-center gap-4 text-xs text-gray-500">
      <span class="flex items-center gap-1.5">
        <i data-lucide="phone" class="w-3.5 h-3.5" style="color:#D4AF37;"></i>
        {{ $user->phone ?? '-' }}
      </span>
      <span class="flex items-center gap-1.5">
        <i data-lucide="mail" class="w-3.5 h-3.5" style="color:#D4AF37;"></i>
        {{ $user->email ?? '-' }}
      </span>
    </div>
  </div>

  {{-- Edit Form --}}
  <div class="p-6 rounded-2xl space-y-5" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="pb-4" style="border-bottom:1px solid #D4AF3715;">
      <h2 class="font-black text-white flex items-center gap-2">
        <i data-lucide="edit-3" class="w-4 h-4" style="color:#D4AF37;"></i>
        {{ __('Edit Biodata') }}
      </h2>
      <p class="text-[11px] text-gray-500 mt-1">{{ __('Perbarui nama dan nomor HP Anda') }}</p>
    </div>

    <form method="POST" action="{{ Auth::user()->role === 'bos' ? route('bos.biodata.update') : route('admin.biodata.update') }}">
      @csrf @method('PUT')

      <div class="space-y-4">

        {{-- Nama --}}
        <div class="space-y-1.5">
          <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">
            {{ __('Nama Lengkap') }} <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-600"></i>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   placeholder="{{ __('Masukkan nama lengkap') }}"
                   class="w-full pl-10 pr-4 py-3 rounded-xl text-sm font-semibold outline-none transition-all"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;"
                   onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
          </div>
          @error('name') <p class="text-[11px] text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Nomor HP --}}
        <div class="space-y-1.5">
          <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">
            {{ __('Nomor HP') }} <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <i data-lucide="phone" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-600"></i>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                   placeholder="{{ __('Masukkan nomor HP') }}"
                   class="w-full pl-10 pr-4 py-3 rounded-xl text-sm font-semibold outline-none transition-all"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;"
                   onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
          </div>
          @error('phone') <p class="text-[11px] text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Divider --}}
        <div class="pt-2 pb-1" style="border-top:1px solid #D4AF3715;">
          <p class="text-[11px] font-bold text-gray-600 uppercase tracking-wider">{{ __('Ubah Password (Opsional)') }}</p>
        </div>

        {{-- Password Baru --}}
        <div class="space-y-1.5">
          <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">{{ __('Password Baru') }}</label>
          <div class="relative">
            <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-600"></i>
            <input type="password" name="password"
                   placeholder="{{ __('Kosongkan jika tidak ingin mengubah') }}"
                   class="w-full pl-10 pr-4 py-3 rounded-xl text-sm font-semibold outline-none transition-all"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;"
                   onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
          </div>
          @error('password') <p class="text-[11px] text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="space-y-1.5">
          <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">{{ __('Konfirmasi Password') }}</label>
          <div class="relative">
            <i data-lucide="lock-keyhole" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-600"></i>
            <input type="password" name="password_confirmation"
                   placeholder="{{ __('Ulangi password baru') }}"
                   class="w-full pl-10 pr-4 py-3 rounded-xl text-sm font-semibold outline-none transition-all"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;"
                   onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
          </div>
        </div>

        {{-- Submit --}}
        <div class="pt-2 flex justify-end">
          <button type="submit"
                  class="px-8 py-3 rounded-xl font-black text-sm transition-all hover:opacity-90"
                  style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;box-shadow:0 4px 16px #D4AF3740;">
            <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
            {{ __('Simpan Perubahan') }}
          </button>
        </div>
      </div>
    </form>
  </div>

  {{-- Info Card --}}
  <div class="p-4 rounded-2xl flex items-start gap-3" style="background:#D4AF3710;border:1px solid #D4AF3725;">
    <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5" style="color:#D4AF37;"></i>
    <div class="text-[11px] text-gray-400 space-y-1">
      <p>{{ __('Nomor HP digunakan untuk login ke sistem. Pastikan nomor yang dimasukkan aktif dan benar.') }}</p>
      <p>{{ __('Kosongkan field password jika tidak ingin mengubah password Anda.') }}</p>
    </div>
  </div>

</div>
@endsection
