@extends('layouts.app')

@section('title', $spaService->name . ' - F55 CENTER DUBAI')

@section('content')
<div class="max-w-5xl mx-auto space-y-8" x-data="{
    tiers: {{ json_encode($spaService->pricing_tiers ?? []) }},
    baseDuration: {{ $spaService->duration }},
    basePrice: {{ $spaService->price }},
    duration: {{ $spaService->duration }},
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
  }">

  <!-- Back Link -->
  <a href="{{ route('customer.services') }}"
     class="inline-flex items-center gap-1.5 text-xs font-bold transition-all hover:opacity-80"
     style="color:#D4AF37;">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    {{ __('Kembali ke Katalog Layanan') }}
  </a>

  <!-- Main Hero Card -->
  <div class="rounded-3xl overflow-hidden shadow-2xl grid grid-cols-1 lg:grid-cols-12 gap-0"
       style="background:#141414;border:1.5px solid #D4AF3740;">

    <!-- Image Hero -->
    <div class="lg:col-span-6 relative h-72 lg:h-full min-h-[320px]">
      <img src="{{ $spaService->image_url }}" class="w-full h-full object-cover" alt="{{ $spaService->name }}">
      <div class="absolute inset-0" style="background:linear-gradient(135deg,#0a0a0a40,transparent 70%);"></div>
      <div class="absolute top-4 left-4 px-3 py-1 rounded-full backdrop-blur-md text-xs font-bold"
           style="background:#D4AF3720;border:1px solid #D4AF3740;color:#F5DF93;">
        {{ __($spaService->category) }}
      </div>
      <div class="absolute top-4 right-4 px-3 py-1 rounded-full backdrop-blur-md text-amber-400 text-xs font-bold flex items-center gap-1"
           style="background:rgba(0,0,0,0.7);border:1px solid #D4AF3740;">
        <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
        <span>{{ number_format($spaService->rating, 1) }} / 5.0</span>
      </div>
    </div>

    <!-- Content Details -->
    <div class="lg:col-span-6 p-6 sm:p-10 space-y-6 flex flex-col justify-between">
      <div class="space-y-3">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-bold"
             style="background:#D4AF3715;border:1px solid #D4AF3730;color:#F5DF93;">
          <i data-lucide="award" class="w-3.5 h-3.5"></i>
          F55 DUBAI LUXURY SPA
        </div>
        <h1 class="text-xl sm:text-2xl font-display font-black tracking-tight text-white">{{ __($spaService->name) }}</h1>
        <p class="text-xs sm:text-sm text-gray-400 leading-relaxed">{{ __($spaService->description) }}</p>

        <!-- Benefits & Features -->
        @if(!empty($spaService->benefits))
        <div class="space-y-2 pt-3">
          <span class="text-[10px] font-bold uppercase tracking-wider block" style="color:#D4AF37;">{{ __('Manfaat Utama Perawatan') }}:</span>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @foreach((array)$spaService->benefits as $b)
            <div class="flex items-center gap-2 text-xs font-semibold p-2 rounded-xl"
                 style="background:#D4AF3710;border:1px solid #D4AF3720;color:#F5DF93;">
              <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0" style="color:#D4AF37;"></i>
              <span>{{ __($b) }}</span>
            </div>
            @endforeach
          </div>
        </div>
        @endif
      </div>

      <!-- Duration Selector & Price Box -->
      <div class="pt-6 border-t space-y-4" style="border-color:#D4AF3720;">
        <!-- Duration Modifier -->
        <div class="flex items-center justify-between p-3 rounded-2xl"
             style="background:#1a1a1a;border:1px solid #D4AF3730;">
          <span class="text-xs font-bold text-gray-400 flex items-center gap-1.5">
            <i data-lucide="clock" class="w-4 h-4" style="color:#D4AF37;"></i>
            {{ __('Pilih Durasi') }}:
          </span>

          <div class="flex items-center gap-2">
            <button type="button" @click="decreaseDuration()"
              class="w-8 h-8 rounded-xl font-black flex items-center justify-center transition-all"
              style="background:#111;border:1px solid #D4AF3730;color:#D4AF37;">-</button>
            <span class="text-xs font-black min-w-[80px] text-center" style="color:#F5DF93;" x-text="formattedDuration"></span>
            <button type="button" @click="increaseDuration()"
              class="w-8 h-8 rounded-xl font-black flex items-center justify-center transition-all"
              style="background:#111;border:1px solid #D4AF3730;color:#D4AF37;">+</button>
          </div>
        </div>

        <!-- Total Price & CTA -->
        <div class="flex items-center justify-between gap-4">
          <div>
            <span class="text-[10px] text-gray-500 font-bold uppercase block">{{ __('Total Harga Perawatan') }}</span>
            <span class="font-black text-xl sm:text-2xl" style="color:#D4AF37;" x-text="formattedPrice"></span>
          </div>

          <a :href="'{{ route('customer.booking.create') }}?service_id={{ $spaService->id }}&duration=' + duration"
            class="px-6 py-3.5 rounded-2xl font-black text-xs sm:text-sm shadow-xl transition-all flex items-center gap-2 hover:opacity-90"
            style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i>
            <span>{{ __('Booking Sekarang') }}</span>
          </a>
        </div>
      </div>

    </div>

  </div>

</div>
@endsection
