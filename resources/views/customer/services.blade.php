@extends('layouts.app')

@section('title', __('Layanan Spa') . ' - F55 CENTER DUBAI')

@section('content')
<div class="space-y-8">

  <!-- Header Banner -->
  <div class="p-6 sm:p-8 rounded-3xl relative overflow-hidden"
       style="background:linear-gradient(135deg,#16140d 0%,#141414 50%,#0a0a0a 100%);border:1.5px solid #D4AF3740;box-shadow:0 20px 60px rgba(0,0,0,0.8),0 0 40px rgba(212,175,55,0.1);">
    <div class="absolute top-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#D4AF37,#F5DF93,#B8960C);"></div>
    <div class="absolute -right-8 -bottom-8 w-48 h-48 rounded-full blur-3xl pointer-events-none" style="background:#D4AF3710;"></div>

    <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold mb-3"
             style="background:#D4AF3715;border:1px solid #D4AF3740;color:#F5DF93;">
          <i data-lucide="sparkles" class="w-3.5 h-3.5 text-yellow-400"></i>
          <span>F55 MEN RELAXATION & MASSAGE CENTER</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-display font-black tracking-tight text-white">{{ __('Katalog Layanan Spa & Wellness') }}</h1>
        <p class="text-xs text-gray-500 mt-1">{{ __('Pilih perawatan favorit Anda dan atur durasi sesuai keinginan') }}</p>
      </div>

      <!-- Search Bar -->
      <form action="{{ route('customer.services') }}" method="GET" class="flex items-center gap-2">
        @if($category)
          <input type="hidden" name="category" value="{{ $category }}">
        @endif
        <div class="relative w-full sm:w-64">
          <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-3 text-gray-500"></i>
          <input type="text" name="search" value="{{ request('search') }}"
            placeholder="{{ __('Cari perawatan...') }}"
            class="w-full pl-10 pr-4 py-2.5 rounded-xl text-xs font-semibold outline-none transition-all"
            style="background:#1a1a1a;border:1px solid #D4AF3730;color:#f3f4f6;"
            onfocus="this.style.borderColor='#D4AF37'" onblur="this.style.borderColor='#D4AF3730'">
        </div>
        <button type="submit"
          class="px-4 py-2.5 rounded-xl font-black text-xs transition-all"
          style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Cari') }}
        </button>
      </form>
    </div>

    <!-- Category Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pt-4 pb-1 scrollbar-none">
      @foreach($categories as $cat)
      <a href="{{ route('customer.services', ['category' => $cat, 'search' => request('search')]) }}"
        class="px-4 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all"
        style="{{ ($category == $cat || (!$category && $cat == 'Semua'))
          ? 'background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;'
          : 'background:#1a1a1a;border:1px solid #D4AF3730;color:#9ca3af;' }}">
        {{ __($cat) }}
      </a>
      @endforeach
    </div>
  </div>

  <!-- Services Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($services as $svc)
    <div x-data="{
        tiers: {{ json_encode($svc->pricing_tiers ?? []) }},
        baseDuration: {{ $svc->duration }},
        basePrice: {{ $svc->price }},
        duration: {{ $svc->duration }},
        get price() {
          if (this.tiers && (this.tiers[this.duration] !== undefined)) {
            return Number(this.tiers[this.duration]);
          }
          if (this.duration === this.baseDuration) return this.basePrice;
          const hourlyRate = Math.round((this.basePrice / this.baseDuration) * 60);
          if (this.duration % 60 === 0) {
            return (this.duration / 60) * hourlyRate;
          }
          const halfHourRate = Math.round(hourlyRate / 2);
          const hours = Math.floor(this.duration / 60);
          const hasHalfHour = (this.duration % 60) === 30;
          return (hours * hourlyRate) + (hasHalfHour ? halfHourRate : 0);
        },
        get formattedPrice() {
          return 'AED ' + Number(this.price).toLocaleString('en-AE', {minimumFractionDigits:2,maximumFractionDigits:2});
        },
        get formattedDuration() {
          let h = Math.floor(this.duration / 60);
          let m = this.duration % 60;
          if (h > 0 && m > 0) return h + ' {{ __("Jam") }} ' + m + ' {{ __("Menit") }}';
          if (h > 0) return h + ' {{ __("Jam") }}';
          return m + ' {{ __("Menit") }}';
        },
        decreaseDuration() {
          if (this.duration > 30) this.duration -= 30;
        },
        increaseDuration() {
          if (this.duration < 240) this.duration += 30;
        }
      }"
      class="rounded-3xl overflow-hidden flex flex-col justify-between card-lift"
      style="background:#141414;border:1px solid #D4AF3730;">

      <!-- Image & Category Tag -->
      <div class="relative h-52 overflow-hidden">
        <img src="{{ $svc->image_url }}" class="w-full h-full object-cover" alt="{{ $svc->name }}">
        <div class="absolute inset-0" style="background:linear-gradient(to top,#0a0a0aCC,transparent);"></div>
        <div class="absolute top-3 right-3 px-3 py-1 rounded-full backdrop-blur-md text-amber-400 text-xs font-bold flex items-center gap-1"
             style="background:rgba(0,0,0,0.7);border:1px solid #D4AF3740;">
          <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
          <span>{{ number_format($svc->rating, 1) }}</span>
        </div>
        <div class="absolute bottom-3 left-3 px-3 py-1 rounded-full backdrop-blur-md text-[11px] font-bold"
             style="background:#D4AF3720;border:1px solid #D4AF3740;color:#F5DF93;">
          {{ __($svc->category) }}
        </div>
      </div>

      <!-- Card Body -->
      <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
        <div class="space-y-2">
          <h3 class="font-display font-black text-lg text-white tracking-tight">{{ __($svc->name) }}</h3>
          <p class="text-xs text-gray-400 leading-relaxed">{{ __($svc->description) }}</p>

          <!-- Benefits Pills -->
          @if(!empty($svc->benefits))
          <div class="pt-2 flex flex-wrap gap-1.5">
            @foreach((array)$svc->benefits as $b)
              <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold"
                    style="background:#D4AF3710;border:1px solid #D4AF3730;color:#F5DF93;">
                ✓ {{ __($b) }}
              </span>
            @endforeach
          </div>
          @endif
        </div>

        <!-- Duration Adjustment & Price -->
        <div class="pt-4 border-t space-y-3" style="border-color:#D4AF3715;">
          <div class="flex items-center justify-between p-2.5 rounded-xl"
               style="background:#1a1a1a;border:1px solid #D4AF3725;">
            <span class="text-[11px] font-bold text-gray-400 flex items-center gap-1">
              <i data-lucide="clock" class="w-3.5 h-3.5" style="color:#D4AF37;"></i> {{ __('Durasi') }}:
            </span>

            <div class="flex items-center gap-2">
              <button @click="decreaseDuration()"
                class="w-7 h-7 rounded-xl font-black flex items-center justify-center transition-all text-sm"
                style="background:#111;border:1px solid #D4AF3730;color:#D4AF37;">
                -
              </button>
              <span class="text-xs font-black min-w-[75px] text-center" style="color:#F5DF93;" x-text="formattedDuration"></span>
              <button @click="increaseDuration()"
                class="w-7 h-7 rounded-xl font-black flex items-center justify-center transition-all text-sm"
                style="background:#111;border:1px solid #D4AF3730;color:#D4AF37;">
                +
              </button>
            </div>
          </div>

          <!-- Price & Action Button -->
          <div class="flex items-center justify-between">
            <div>
              <span class="text-[10px] text-gray-500 font-bold uppercase block">{{ __('Total Harga') }}</span>
              <span class="font-black text-lg" style="color:#D4AF37;" x-text="formattedPrice"></span>
            </div>

            <a :href="'{{ route('customer.booking.create') }}?service_id={{ $svc->id }}&duration=' + duration"
              class="px-5 py-2.5 rounded-xl font-black text-xs shadow-md transition-all flex items-center gap-1.5 hover:opacity-90"
              style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
              <span>{{ __('Booking') }}</span>
              <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
          </div>
        </div>

      </div>
    </div>
    @empty
    <div class="col-span-full py-16 text-center rounded-3xl space-y-3"
         style="background:#141414;border:1px solid #D4AF3720;">
      <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto"
           style="background:#D4AF3715;border:1px solid #D4AF3730;">
        <i data-lucide="search-x" class="w-8 h-8" style="color:#D4AF37;"></i>
      </div>
      <p class="text-sm font-bold text-white">{{ __('Tidak ada layanan spa yang sesuai dengan pencarian Anda.') }}</p>
      <a href="{{ route('customer.services') }}"
         class="inline-block px-5 py-2.5 rounded-xl font-bold text-xs transition-all"
         style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
        {{ __('Tampilkan Semua Layanan') }}
      </a>
    </div>
    @endforelse
  </div>

</div>
@endsection
