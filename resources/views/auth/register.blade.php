<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('Daftar Akun Baru') }} - F55 MEN RELAXATION & MASSAGE CENTER</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
  <style>
    body { font-family: {{ app()->getLocale() === 'ar' ? "'Noto Sans Arabic', 'Inter', sans-serif" : "'Inter', sans-serif" }}; }
    .font-cinzel { font-family: 'Cinzel', serif; }
  </style>
</head>
<body class="min-h-screen bg-[#0a0a0a] text-gray-100 antialiased flex flex-col justify-between p-4 sm:p-6 relative overflow-x-hidden">

  <!-- Background decorative glows -->
  <div class="fixed -top-40 -left-40 w-96 h-96 rounded-full bg-[#D4AF37] opacity-[0.04] blur-3xl pointer-events-none"></div>
  <div class="fixed -bottom-40 -right-40 w-96 h-96 rounded-full bg-[#D4AF37] opacity-[0.04] blur-3xl pointer-events-none"></div>

  <!-- Header with Language Switcher -->
  <div class="w-full max-w-md mx-auto flex items-center justify-between py-2">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 overflow-hidden"
           style="border: 1px solid #D4AF3766;">
        <img src="{{ asset('images/logo.png') }}" alt="F55" class="w-full h-full object-cover">
      </div>
      <span class="font-cinzel text-xs font-black tracking-widest text-[#F5DF93]">F55 CENTER</span>
    </div>

    <!-- Multi-Language Switcher -->
    <div class="relative" x-data="{ langOpen: false }">
      <button @click="langOpen = !langOpen"
              class="px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all"
              style="background:#161616; border:1px solid #D4AF3740; color:#F5DF93;">
        <i data-lucide="globe" class="w-3.5 h-3.5 text-yellow-400"></i>
        <span>
          @if(app()->getLocale() === 'ar') 🇦🇪 دبي
          @elseif(app()->getLocale() === 'en') 🇬🇧 EN
          @else 🇮🇩 ID
          @endif
        </span>
        <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400"></i>
      </button>

      <div x-show="langOpen" @click.outside="langOpen = false" x-transition
           class="absolute {{ app()->getLocale() === 'ar' ? 'left-0 origin-top-left' : 'right-0 origin-top-right' }} mt-2 w-44 rounded-2xl p-1.5 shadow-2xl z-50 space-y-1"
           style="background:#141414; border:1px solid #D4AF3750; box-shadow: 0 10px 30px rgba(0,0,0,0.8);">
        <a href="{{ route('lang.switch', 'ar') }}"
           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition-all {{ app()->getLocale() === 'ar' ? 'bg-[#D4AF37] text-black' : 'text-gray-300 hover:bg-white/10' }}">
          <span>🇦🇪 العربية (دبي)</span>
          @if(app()->getLocale() === 'ar') <i data-lucide="check" class="w-3.5 h-3.5"></i> @endif
        </a>
        <a href="{{ route('lang.switch', 'en') }}"
           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition-all {{ app()->getLocale() === 'en' ? 'bg-[#D4AF37] text-black' : 'text-gray-300 hover:bg-white/10' }}">
          <span>🇬🇧 English</span>
          @if(app()->getLocale() === 'en') <i data-lucide="check" class="w-3.5 h-3.5"></i> @endif
        </a>
        <a href="{{ route('lang.switch', 'id') }}"
           class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold transition-all {{ app()->getLocale() === 'id' ? 'bg-[#D4AF37] text-black' : 'text-gray-300 hover:bg-white/10' }}">
          <span>🇮🇩 Indonesia</span>
          @if(app()->getLocale() === 'id') <i data-lucide="check" class="w-3.5 h-3.5"></i> @endif
        </a>
      </div>
    </div>
  </div>

  <!-- Register Card -->
  <div class="max-w-md w-full mx-auto my-auto rounded-3xl p-6 sm:p-8 space-y-6 relative overflow-hidden"
       style="background: linear-gradient(180deg, #161616 0%, #111111 100%); border: 1.5px solid #D4AF3740; box-shadow: 0 20px 60px rgba(0,0,0,0.8), 0 0 35px rgba(212,175,55,0.08);">

    <!-- Top Gold Glow Bar -->
    <div class="absolute top-0 left-0 right-0 h-1"
         style="background: linear-gradient(90deg, #D4AF37 0%, #F5DF93 50%, #B8960C 100%);"></div>

    <!-- Header Logo & Title -->
    <div class="text-center space-y-2 pt-1">
      <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto shadow-2xl"
           style="background: linear-gradient(135deg, #1f1f1f, #2a2a2a); border: 2px solid #D4AF37; box-shadow: 0 0 24px rgba(212,175,55,0.25);">
        <i data-lucide="user-plus" class="w-8 h-8 text-[#F5DF93]"></i>
      </div>
      <div>
        <h1 class="font-cinzel font-black text-xl tracking-wider text-white">{{ __('Daftar Akun Baru') }}</h1>
        <p class="text-[10px] font-bold tracking-widest uppercase mt-0.5" style="color:#D4AF37;">
          F55 MEN RELAXATION CENTER DUBAI
        </p>
        <p class="text-xs text-gray-400 mt-2">{{ __('Bergabunglah dan raih kemudahan reservasi spa premium') }}</p>
      </div>
    </div>

    <!-- Errors -->
    @if($errors->any())
      <div class="p-3.5 rounded-2xl text-xs space-y-1" style="background:#2a1010; border:1px solid #ef444450; color:#fca5a5;">
        @foreach($errors->all() as $error)
          <div class="flex items-center gap-1.5">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-red-400"></i>
            <span>{{ $error }}</span>
          </div>
        @endforeach
      </div>
    @endif

    <!-- Register Form -->
    <form action="{{ route('register.post') }}" method="POST" class="space-y-4" x-data="{ showPwd: false, showPwd2: false }">
      @csrf

      <div class="space-y-1.5">
        <label class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Nama Lengkap') }}</label>
        <div class="relative">
          <i data-lucide="user" class="w-4 h-4 absolute left-3.5 top-3.5" style="color:#D4AF37;"></i>
          <input type="text" name="name" value="{{ old('name') }}" required
                 class="w-full pl-10 pr-4 py-3 rounded-xl text-xs text-white placeholder-gray-500 outline-none transition-all"
                 style="background:#1a1a1a; border:1px solid #D4AF3730;"
                 onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'"
                 placeholder="{{ __('Contoh: Haikal / Ahmed Al-Maktoum') }}">
        </div>
      </div>

      <div class="space-y-1.5">
        <label class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('No. Telepon WhatsApp') }}</label>
        <div class="relative">
          <i data-lucide="phone" class="w-4 h-4 absolute left-3.5 top-3.5" style="color:#D4AF37;"></i>
          <input type="text" name="phone" value="{{ old('phone') }}" required
                 class="w-full pl-10 pr-4 py-3 rounded-xl text-xs text-white placeholder-gray-500 outline-none transition-all"
                 style="background:#1a1a1a; border:1px solid #D4AF3730;"
                 onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'"
                 placeholder="08xxxxxxxxxx / +971 50 xxx xxxx">
        </div>
      </div>

      <div class="space-y-1.5">
        <label class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Password') }}</label>
        <div class="relative">
          <i data-lucide="lock" class="w-4 h-4 absolute left-3.5 top-3.5" style="color:#D4AF37;"></i>
          <input :type="showPwd ? 'text' : 'password'" name="password" required
                 class="w-full pl-10 pr-11 py-3 rounded-xl text-xs text-white placeholder-gray-500 outline-none transition-all"
                 style="background:#1a1a1a; border:1px solid #D4AF3730;"
                 onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'"
                 placeholder="{{ __('Minimal 8 karakter') }}">
          <button type="button" @click="showPwd = !showPwd"
                  class="absolute right-3.5 top-3.5 text-gray-500 hover:text-white transition-colors">
            <i :data-lucide="showPwd ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
          </button>
        </div>
      </div>

      <div class="space-y-1.5">
        <label class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Konfirmasi Password') }}</label>
        <div class="relative">
          <i data-lucide="check-circle" class="w-4 h-4 absolute left-3.5 top-3.5" style="color:#D4AF37;"></i>
          <input :type="showPwd2 ? 'text' : 'password'" name="password_confirmation" required
                 class="w-full pl-10 pr-11 py-3 rounded-xl text-xs text-white placeholder-gray-500 outline-none transition-all"
                 style="background:#1a1a1a; border:1px solid #D4AF3730;"
                 onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'"
                 placeholder="{{ __('Ulangi password') }}">
          <button type="button" @click="showPwd2 = !showPwd2"
                  class="absolute right-3.5 top-3.5 text-gray-500 hover:text-white transition-colors">
            <i :data-lucide="showPwd2 ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
          </button>
        </div>
      </div>

      <button type="submit"
              class="w-full py-3.5 rounded-xl font-black text-xs shadow-lg transition-all flex items-center justify-center gap-2 mt-4"
              style="background:linear-gradient(90deg,#D4AF37,#B8960C); color:#111; box-shadow:0 6px 24px rgba(212,175,55,0.25);">
        <span>{{ __('Buat Akun Sekarang') }}</span>
        <i data-lucide="arrow-right" class="w-4 h-4"></i>
      </button>
    </form>

    <!-- Footer Links -->
    <div class="pt-3 text-center text-xs text-gray-400" style="border-top: 1px solid #D4AF3720;">
      <p>{{ __('Sudah punya akun?') }}
        <a href="{{ route('login') }}" class="font-extrabold hover:underline" style="color:#F5DF93;">{{ __('Masuk Di Sini') }}</a>
      </p>
    </div>
  </div>

  <!-- Footer text -->
  <footer class="w-full max-w-md mx-auto text-center py-4 text-[11px] text-gray-600">
    <p>&copy; {{ date('Y') }} F55 MEN RELAXATION & MASSAGE CENTER. {{ __('PUSAT RELAKSASI & SPA MEWAH DUBAI') }}.</p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });
    document.addEventListener('alpine:initialized', () => {
      lucide.createIcons();
    });
  </script>
</body>
</html>
