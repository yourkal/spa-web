@extends('layouts.admin')

@section('title', __('Kelola Layanan Spa') . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', __('Manajemen Katalog Layanan Spa'))
@section('header_subtitle', __('Kelola paket treatment massage, tarif, durasi, dan ketersediaan layanan'))

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, activeEdit: {} }">

  {{-- ── Action Bar ─────────────────────────────────────────────────────── --}}
  <div class="p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4"
       style="background:#141414;border:1px solid #D4AF3720;">
    <div>
      <h2 class="text-sm font-black text-white">{{ __('Daftar Paket & Layanan') }}</h2>
      <p class="text-[11px] text-gray-500">{{ __('Kelola item katalog yang dapat dipesan oleh pelanggan') }}</p>
    </div>
    <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
      <a href="{{ route('admin.services.export.excel') }}"
         class="px-4 py-2.5 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
         style="background:#1a1a1a;border:1px solid #10B98150;color:#34D399;">
        <i data-lucide="sheet" class="w-4 h-4 text-emerald-400"></i>
        <span>{{ __('Unduh Excel') }}</span>
      </a>

      <button @click="createModal = true"
              class="px-4 py-2.5 rounded-xl font-black text-xs shadow-md transition-all flex items-center gap-2"
              style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
        <i data-lucide="plus-circle" class="w-4 h-4"></i>
        <span>{{ __('Tambah Layanan Baru') }}</span>
      </button>
    </div>
  </div>

  {{-- ── Services Grid ──────────────────────────────────────────────────── --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($services as $svc)
    <div class="rounded-2xl overflow-hidden space-y-4 p-5 flex flex-col justify-between card-lift"
         style="background:#141414;border:1px solid #D4AF3720;">
      <div class="space-y-3">
        <div class="relative h-44 rounded-xl overflow-hidden" style="border:1px solid #D4AF3730;">
          <img src="{{ $svc->image_url }}" class="w-full h-full object-cover">
          <span class="absolute top-2 right-2 px-2.5 py-1 rounded-full text-[10px] font-black"
                style="background:rgba(0,0,0,0.75);border:1px solid #D4AF3740;color:#D4AF37;">
            ★ {{ number_format($svc->rating, 1) }}
          </span>
          <span class="absolute bottom-2 left-2 px-2.5 py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-wider"
                style="background:rgba(20,20,20,0.85);border:1px solid #D4AF3730;">
            {{ __($svc->category) }}
          </span>
        </div>

        <div>
          <h3 class="font-display font-black text-base text-white">{{ __($svc->name) }}</h3>
          <p class="text-xs text-gray-400 line-clamp-2 mt-1">{{ __($svc->description) }}</p>
        </div>

        <div class="flex items-center justify-between text-xs pt-2" style="border-top:1px solid #D4AF3715;">
          <div>
            <span class="text-[10px] text-gray-500 font-bold block uppercase tracking-wider">{{ __('Tarif') }} ({{ $svc->duration }} {{ __('min') }})</span>
            <span class="font-black text-sm" style="color:#D4AF37;">AED {{ number_format($svc->price, 2) }}</span>
          </div>

          <form action="{{ route('admin.services.toggle', $svc) }}" method="POST"
                data-confirm="{{ __('Ubah status ketersediaan layanan ini?') }}" data-confirm-title="{{ __('Ubah Status') }}">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase transition-all"
                    style="{{ $svc->is_active ? 'background:#D4AF3720;border:1px solid #D4AF3750;color:#D4AF37;' : 'background:#1a1a1a;border:1px solid #4b5563;color:#6b7280;' }}">
              {{ $svc->is_active ? __('Aktif') : __('Non-Aktif') }}
            </button>
          </form>
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="pt-3 flex items-center justify-between text-xs" style="border-top:1px solid #D4AF3715;">
        <button @click="activeEdit = {{ json_encode($svc) }}; editModal = true"
                class="font-bold hover:underline flex items-center gap-1 transition-all" style="color:#D4AF37;">
          <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> {{ __('Edit') }}
        </button>

        <form action="{{ route('admin.services.destroy', $svc) }}" method="POST"
              data-confirm="{{ __('Layanan') }} '{{ __($svc->name) }}' {{ __('akan dihapus permanen!') }}" data-confirm-title="{{ __('Hapus Layanan') }}">
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

  {{-- ── Modal Tambah Layanan ───────────────────────────────────────────── --}}
  <div x-show="createModal" x-transition
       class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div @click.outside="createModal = false"
         class="rounded-2xl max-w-lg w-full p-6 sm:p-7 space-y-5 shadow-2xl relative max-h-[90vh] overflow-y-auto"
         style="background:#141414;border:1px solid #D4AF3740;">
      <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3720;">
        <h3 class="font-black text-sm text-white flex items-center gap-2">
          <i data-lucide="plus-circle" class="w-4 h-4" style="color:#D4AF37;"></i>
          {{ __('Tambah Layanan Spa Baru') }}
        </h3>
        <button @click="createModal = false" class="text-gray-400 hover:text-white">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Nama Layanan') }} <span class="text-red-400">*</span></label>
          <input type="text" name="name" required
                 class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Kategori') }} <span class="text-red-400">*</span></label>
            <select name="category" required
                    class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
              <option value="Massage">{{ __('Massage') }}</option>
              <option value="Aromatherapy">{{ __('Aromatherapy') }}</option>
              <option value="Facial">{{ __('Facial') }}</option>
              <option value="Body Treatment">{{ __('Body Treatment') }}</option>
              <option value="Ice Bath & Recovery">{{ __('Ice Bath & Recovery') }}</option>
            </select>
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Durasi (Menit)') }} <span class="text-red-400">*</span></label>
            <input type="number" name="duration" value="60" min="30" step="15" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Tarif (AED)') }} <span class="text-red-400">*</span></label>
            <input type="number" name="price" min="0" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Rating (0-5)') }}</label>
            <input type="number" step="0.1" name="rating" value="4.9" min="0" max="5"
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Upload Foto Layanan') }} <span class="text-red-400">*</span></label>
          <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" required
                 class="w-full p-2 border border-dashed rounded-xl text-xs text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold cursor-pointer"
                 style="background:#1a1a1a;border-color:#D4AF3740;">
        </div>

        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Deskripsi') }}</label>
          <textarea name="description" rows="2"
                    class="w-full p-3 rounded-xl text-xs outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;"></textarea>
        </div>

        <button type="submit"
                class="w-full py-3 rounded-xl font-black text-xs shadow-md transition-all mt-2"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Simpan Layanan Baru') }}
        </button>
      </form>
    </div>
  </div>

  {{-- ── Modal Edit Layanan ─────────────────────────────────────────────── --}}
  <div x-show="editModal" x-transition
       class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div @click.outside="editModal = false"
         class="rounded-2xl max-w-lg w-full p-6 sm:p-7 space-y-5 shadow-2xl relative max-h-[90vh] overflow-y-auto"
         style="background:#141414;border:1px solid #D4AF3740;">
      <div class="flex items-center justify-between pb-3" style="border-bottom:1px solid #D4AF3720;">
        <h3 class="font-black text-sm text-white flex items-center gap-2">
          <i data-lucide="edit-3" class="w-4 h-4" style="color:#D4AF37;"></i>
          {{ __('Edit Layanan Spa') }}
        </h3>
        <button @click="editModal = false" class="text-gray-400 hover:text-white">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form :action="'/admin/services/' + activeEdit.id" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Nama Layanan') }} <span class="text-red-400">*</span></label>
          <input type="text" name="name" x-model="activeEdit.name" required
                 class="w-full px-3.5 py-2.5 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Kategori') }} <span class="text-red-400">*</span></label>
            <select name="category" x-model="activeEdit.category" required
                    class="w-full px-3.5 py-2.5 rounded-xl text-xs font-bold outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
              <option value="Massage">{{ __('Massage') }}</option>
              <option value="Aromatherapy">{{ __('Aromatherapy') }}</option>
              <option value="Facial">{{ __('Facial') }}</option>
              <option value="Body Treatment">{{ __('Body Treatment') }}</option>
              <option value="Ice Bath & Recovery">{{ __('Ice Bath & Recovery') }}</option>
            </select>
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Durasi (Menit)') }} <span class="text-red-400">*</span></label>
            <input type="number" name="duration" x-model="activeEdit.duration" min="30" step="15" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Tarif (AED)') }} <span class="text-red-400">*</span></label>
            <input type="number" name="price" x-model="activeEdit.price" min="0" required
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
          <div class="space-y-1">
            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Rating (0-5)') }}</label>
            <input type="number" step="0.1" name="rating" x-model="activeEdit.rating" min="0" max="5"
                   class="w-full px-3.5 py-2.5 rounded-xl text-xs outline-none"
                   style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
          </div>
        </div>

        <div class="space-y-2">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Ganti Foto Layanan') }}</label>
          <div class="flex items-center gap-3">
            <img :src="activeEdit.image_url" class="w-16 h-12 rounded-xl object-cover shrink-0" style="border:1px solid #D4AF3740;">
            <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                   class="w-full p-2 border border-dashed rounded-xl text-xs text-gray-400 cursor-pointer"
                   style="background:#1a1a1a;border-color:#D4AF3740;">
          </div>
          <p class="text-[10px] text-gray-500">{{ __('Kosongkan jika tidak ingin mengubah foto (Format JPG, PNG, WEBP).') }}</p>
        </div>

        <div class="space-y-1">
          <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">{{ __('Deskripsi') }}</label>
          <textarea name="description" x-model="activeEdit.description" rows="2"
                    class="w-full p-3 rounded-xl text-xs outline-none"
                    style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;"></textarea>
        </div>

        <button type="submit"
                class="w-full py-3 rounded-xl font-black text-xs shadow-md transition-all mt-2"
                style="background:linear-gradient(90deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Perbarui Layanan') }}
        </button>
      </form>
    </div>
  </div>

</div>
@endsection
