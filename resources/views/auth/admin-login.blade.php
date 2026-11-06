<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('Portal Masuk Admin & Bos') }} - F55 MEN RELAXATION & MASSAGE CENTER</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+Arabic:wght@400;600;700;800&display=swap" rel="stylesheet">
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

    <!-- Multi-Language Switcher (ID / EN / AR-Dubai) -->
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

  <!-- Admin Login Card -->
  <div class="max-w-md w-full mx-auto my-auto rounded-3xl p-6 sm:p-8 space-y-6 relative overflow-hidden"
       style="background: linear-gradient(180deg, #161616 0%, #111111 100%); border: 1.5px solid #D4AF3740; box-shadow: 0 20px 60px rgba(0,0,0,0.8), 0 0 40px rgba(212,175,55,0.1);">

    <!-- Top Gold Glow Accent -->
    <div class="absolute top-0 left-0 right-0 h-1"
         style="background: linear-gradient(90deg, #D4AF37 0%, #F5DF93 50%, #B8960C 100%);"></div>

    <!-- Header Logo & Title -->
    <div class="text-center space-y-3 pt-2">
      <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto shadow-2xl overflow-hidden"
           style="border: 2px solid #D4AF37; box-shadow: 0 0 24px rgba(212,175,55,0.3);">
        <img src="{{ asset('images/logo.png') }}" alt="F55 Center Logo" class="w-full h-full object-cover">
      </div>

      <div>
        <h1 class="font-cinzel font-black text-xl tracking-wider text-white">F55 CENTER</h1>
        <p class="text-[10px] font-bold tracking-widest uppercase mt-0.5" style="color:#D4AF37;">
          MEN RELAXATION & MASSAGE CENTER
        </p>
        <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest"
              style="background:#D4AF3715;border:1px solid #D4AF3730;color:#F5DF93;">
          {{ __('Portal Masuk Admin & Bos') }}
        </span>
      </div>
    </div>

    <!-- Errors -->
    @if($errors->any())
      <div class="p-3.5 rounded-2xl text-xs space-y-1" style="background:#2a1010;border:1px solid #ef444450;color:#fca5a5;">
        @foreach($errors->all() as $error)
          <div class="flex items-center gap-1.5">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-red-400"></i>
            <span>{{ $error }}</span>
          </div>
        @endforeach
      </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4" x-data="{ loginVal: '{{ old('login', '081200000001') }}', pwdVal: 'admin123', showPwd: false }">
      @csrf

      <div class="space-y-1.5">
        <label class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Nomor HP') }} / Email</label>
        <div class="relative">
          <i data-lucide="user-check" class="w-4 h-4 absolute left-3.5 top-3.5" style="color:#D4AF37;"></i>
          <input type="text" name="login" x-model="loginVal" required
                 class="w-full pl-10 pr-4 py-3 rounded-xl text-xs text-white placeholder-gray-500 outline-none transition-all"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;"
                 onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'"
                 placeholder="08xxxxxxxxxx / email">
        </div>
      </div>

      <div class="space-y-1.5">
        <label class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ __('Password') }}</label>
        <div class="relative">
          <i data-lucide="lock" class="w-4 h-4 absolute left-3.5 top-3.5" style="color:#D4AF37;"></i>
          <input :type="showPwd ? 'text' : 'password'" name="password" x-model="pwdVal" required
                 class="w-full pl-10 pr-11 py-3 rounded-xl text-xs text-white placeholder-gray-500 outline-none transition-all"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;"
                 onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'"
                 placeholder="••••••••">
          <button type="button" @click="showPwd = !showPwd"
                  class="absolute right-3.5 top-3.5 text-gray-500 hover:text-white transition-colors">
            <i :data-lucide="showPwd ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
          </button>
        </div>
      </div>

      <button type="submit"
              class="w-full py-3.5 rounded-xl font-black text-xs shadow-lg transition-all flex items-center justify-center gap-2 mt-2"
              style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;box-shadow:0 6px 24px rgba(212,175,55,0.3);">
        <span>{{ __('Masuk ke Dashboard') }}</span>
        <i data-lucide="arrow-right" class="w-4 h-4"></i>
      </button>

      <!-- Quick Demo Selectors -->
      <div class="pt-2">
        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2 text-center">{{ __('Pilih Akun Cepat:') }}</p>
        <div class="grid grid-cols-2 gap-2 text-xs">
          <button type="button"
                  @click="loginVal = '081200000001'; pwdVal = 'admin123'"
                  class="p-2.5 rounded-xl text-left transition-all"
                  style="background:#1a1a1a;border:1px solid #D4AF3730;">
            <span class="block text-[10px] font-extrabold uppercase text-gray-400">⚙️ {{ __('Super Admin') }}</span>
            <span class="block text-[11px] font-bold text-white">{{ __('Full Akses') }}</span>
          </button>

          <button type="button"
                  @click="loginVal = '081200000002'; pwdVal = 'bos123'"
                  class="p-2.5 rounded-xl text-left transition-all"
                  style="background:#1a1a1a;border:1px solid #D4AF3750;">
            <span class="block text-[10px] font-extrabold uppercase" style="color:#D4AF37;">👑 {{ __('Akun Bos') }}</span>
            <span class="block text-[11px] font-bold text-white">{{ __('View Dashboard') }}</span>
          </button>
        </div>
      </div>
    </form>

    <!-- Footer Links -->
    <div class="pt-3 text-center text-xs text-gray-500" style="border-top:1px solid #D4AF3715;">
      <a href="{{ route('home') }}" class="hover:text-white transition-colors inline-flex items-center gap-1">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> {{ __('Kembali ke Halaman Utama') }}
      </a>
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
