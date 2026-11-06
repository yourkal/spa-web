@extends('layouts.app')

@section('title', __('Biodata & Profil Saya') . ' - F55 CENTER DUBAI')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

  {{-- Profile Overview Card (Matching Admin Biodata aesthetic) --}}
  <div class="p-6 rounded-2xl text-center space-y-4"
       style="background:linear-gradient(135deg,#1a1500,#141414);border:1px solid #D4AF3740;box-shadow:0 12px 36px rgba(0,0,0,0.7),0 0 20px rgba(212,175,55,0.1);">
    
    <div class="relative inline-block">
      @if($user->avatar)
        <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
             class="w-24 h-24 rounded-full mx-auto object-cover border-2 shadow-2xl"
             style="border-color:#D4AF37;box-shadow:0 0 24px #D4AF3750;">
      @else
        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full mx-auto flex items-center justify-center text-2xl sm:text-3xl font-black"
             style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;box-shadow:0 0 32px #D4AF3740;">
          {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
      @endif
      <span class="absolute bottom-0 right-1 w-6 h-6 rounded-full flex items-center justify-center text-[10px]"
            style="background:#D4AF37;color:#111;box-shadow:0 0 8px #D4AF37;">
        <i data-lucide="check" class="w-3.5 h-3.5"></i>
      </span>
    </div>

    <div>
      <h1 class="font-display font-black text-xl sm:text-2xl tracking-wide" style="color:#D4AF37;">{{ $user->name }}</h1>
      <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-widest"
            style="background:#D4AF3715;border:1px solid #D4AF3730;color:#F5DF93;">
        {{ __('Pelanggan Terdaftar') }} • F55 DUBAI
      </span>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-4 text-xs text-gray-400 pt-2 border-t" style="border-color:#D4AF3715;">
      <span class="flex items-center gap-1.5">
        <i data-lucide="phone" class="w-3.5 h-3.5" style="color:#D4AF37;"></i>
        {{ $user->phone ?? '-' }}
      </span>
      <span class="flex items-center gap-1.5">
        <i data-lucide="mail" class="w-3.5 h-3.5" style="color:#D4AF37;"></i>
        {{ $user->email ?? '-' }}
      </span>
      <span class="flex items-center gap-1.5">
        <i data-lucide="calendar" class="w-3.5 h-3.5" style="color:#D4AF37;"></i>
        {{ __('Terdaftar') }}: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
      </span>
    </div>
  </div>

  {{-- Edit Biodata Form Card --}}
  <div class="p-6 sm:p-8 rounded-2xl space-y-6 shadow-2xl"
       style="background:#141414;border:1px solid #D4AF3725;">
    
    <div class="pb-4 border-b flex items-center justify-between" style="border-color:#D4AF3715;">
      <div>
        <h2 class="font-black text-white text-base flex items-center gap-2">
          <i data-lucide="edit-3" class="w-4 h-4" style="color:#D4AF37;"></i>
          <span>{{ __('Pengaturan Profil Pengguna') }}</span>
        </h2>
        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('Kelola informasi pribadi, kontak, dan keamanan kata sandi Anda') }}</p>
      </div>
      <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider"
            style="background:#D4AF3715;border:1px solid #D4AF3730;color:#D4AF37;">
        {{ __('Mata Uang') }}: AED
      </span>
    </div>

    <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-5">
      @csrf
      @method('PUT')

      {{-- Nama Lengkap --}}
      <div class="space-y-1.5">
        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">
          {{ __('Nama Lengkap') }} <span class="text-red-400">*</span>
        </label>
        <div class="relative">
          <i data-lucide="user" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
          <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                 class="w-full pl-10 pr-4 py-3 rounded-xl text-xs font-semibold outline-none transition-all"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
                 onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
        </div>
        @error('name') <p class="text-[11px] text-red-400">{{ $message }}</p> @enderror
      </div>

      {{-- No WhatsApp & Email --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="space-y-1.5">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">
            {{ __('No. Telepon WhatsApp') }} <span class="text-red-400">*</span>
          </label>
          <div class="relative">
            <i data-lucide="phone" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                   class="w-full pl-10 pr-4 py-3 rounded-xl text-xs font-semibold outline-none transition-all"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
                   onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
          </div>
          @error('phone') <p class="text-[11px] text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-1.5">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">
            {{ __('Email') }}
          </label>
          <div class="relative">
            <i data-lucide="mail" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   placeholder="email@dubai-spa.ae"
                   class="w-full pl-10 pr-4 py-3 rounded-xl text-xs font-semibold outline-none transition-all"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
                   onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
          </div>
          @error('email') <p class="text-[11px] text-red-400">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- Foto Profil URL --}}
      <div class="space-y-1.5">
        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">
          {{ __('URL Foto Profile / Avatar') }}
        </label>
        <div class="relative">
          <i data-lucide="image" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
          <input type="url" name="avatar" value="{{ old('avatar', $user->avatar) }}"
                 placeholder="https://..."
                 class="w-full pl-10 pr-4 py-3 rounded-xl text-xs font-medium outline-none transition-all"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
                 onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
        </div>
        @error('avatar') <p class="text-[11px] text-red-400">{{ $message }}</p> @enderror
      </div>

      {{-- Ubah Password --}}
      <div class="pt-4 border-t space-y-4" style="border-color:#D4AF3715;">
        <div>
          <h3 class="font-extrabold text-xs text-white uppercase tracking-wider">
            {{ __('Ubah Password (Opsional)') }}
          </h3>
          <p class="text-[10px] text-gray-500 mt-0.5">{{ __('Kosongkan jika tidak ingin mengubah password') }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">
              {{ __('Password Baru') }}
            </label>
            <div class="relative">
              <i data-lucide="lock" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
              <input type="password" name="password" placeholder="••••••••"
                     class="w-full pl-10 pr-4 py-3 rounded-xl text-xs outline-none transition-all"
                     style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
                     onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
            </div>
            @error('password') <p class="text-[11px] text-red-400">{{ $message }}</p> @enderror
          </div>

          <div class="space-y-1.5">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">
              {{ __('Konfirmasi Password Baru') }}
            </label>
            <div class="relative">
              <i data-lucide="shield-check" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
              <input type="password" name="password_confirmation" placeholder="••••••••"
                     class="w-full pl-10 pr-4 py-3 rounded-xl text-xs outline-none transition-all"
                     style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
                     onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
            </div>
          </div>
        </div>
      </div>

      {{-- Action Button --}}
      <button type="submit"
              class="w-full py-3.5 rounded-xl font-black text-xs shadow-xl transition-all flex items-center justify-center gap-2 hover:opacity-90"
              style="background:linear-gradient(90deg,#D4AF37 0%,#B8960C 100%);color:#111;box-shadow:0 4px 20px #D4AF3730;">
        <i data-lucide="save" class="w-4 h-4"></i>
        <span>{{ __('Simpan Perubahan Biodata') }}</span>
      </button>

    </form>
  </div>

</div>
@endsection
