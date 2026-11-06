@extends('layouts.admin')

@section('title', __('Pengeluaran') . ' - F55 MEN RELAXATION & MASSAGE CENTER')
@section('header_title', __('Manajemen Pengeluaran'))
@section('header_subtitle', __('Catat dan pantau semua pengeluaran operasional spa'))

@section('content')
<div class="space-y-6">

  {{-- ── Summary Cards ─────────────────────────────── --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="p-5 rounded-2xl space-y-2 card-lift" style="background:linear-gradient(135deg,#1a1a1a,#141414);border:1px solid #EF444430;">
      <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __('Total Periode Ini') }}</span>
      <p class="text-xl sm:text-2xl font-black text-red-400">AED {{ number_format($totalExpense, 2) }}</p>
      <span class="text-[11px] text-gray-500">{{ date('d M Y', strtotime($startDate)) }} — {{ date('d M Y', strtotime($endDate)) }}</span>
    </div>

    @foreach($categoryBreakdown->take(2) as $cb)
    <div class="p-5 rounded-2xl space-y-2 card-lift" style="background:#141414;border:1px solid #D4AF3720;">
      <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">{{ __( \App\Models\Expense::categoryLabel($cb->category) ) }}</span>
      <p class="text-lg sm:text-xl font-black" style="color:#D4AF37;">AED {{ number_format($cb->total, 2) }}</p>
      <div class="w-full rounded-full h-1.5" style="background:#1a1a1a;">
        <div class="rounded-full h-1.5" style="background:#D4AF37;width:{{ $totalExpense > 0 ? round(($cb->total / $totalExpense) * 100) : 0 }}%;"></div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- ── Chart & Form Row ───────────────────────────── --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Monthly Expense Chart --}}
    <div class="lg:col-span-2 p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
      <div>
        <h3 class="font-black text-white">{{ __('Tren Pengeluaran 6 Bulan') }}</h3>
        <p class="text-[11px] text-gray-500">{{ __('Total pengeluaran per bulan') }}</p>
      </div>
      <div class="h-52 w-full">
        <canvas id="expenseMonthChart"></canvas>
      </div>
    </div>

    {{-- Category Breakdown Donut --}}
    <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
      <div>
        <h3 class="font-black text-white">{{ __('Kategori Pengeluaran') }}</h3>
        <p class="text-[11px] text-gray-500">{{ __('Distribusi per kategori') }}</p>
      </div>
      <div class="h-52 w-full">
        <canvas id="expenseCatChart"></canvas>
      </div>
    </div>

  </div>

  {{-- ── Filter + Tambah ────────────────────────────── --}}
  <div class="flex flex-col md:flex-row gap-4">

    {{-- Filter Bar --}}
    <div class="flex-1 p-4 rounded-2xl" style="background:#141414;border:1px solid #D4AF3720;">
      <form action="{{ route('admin.expenses') }}" method="GET" class="flex flex-wrap items-end gap-3">
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Dari Tanggal') }}</label>
          <input type="date" name="start_date" value="{{ $startDate }}"
                 class="px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Sampai Tanggal') }}</label>
          <input type="date" name="end_date" value="{{ $endDate }}"
                 class="px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Kategori') }}</label>
          <select name="category" class="px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                  style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
            <option value="">{{ __('Semua') }}</option>
            @foreach(\App\Models\Expense::categories() as $cat)
              <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ __( \App\Models\Expense::categoryLabel($cat) ) }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-black transition-all hover:opacity-90"
                style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;">
          {{ __('Tampilkan') }}
        </button>
      </form>
    </div>

  </div>

  {{-- ── Add Expense Form ────────────────────────────── --}}
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3730;">
    <h3 class="font-black text-white flex items-center gap-2">
      <i data-lucide="plus-circle" class="w-4 h-4" style="color:#D4AF37;"></i>
      {{ __('Tambah Pengeluaran Baru') }}
    </h3>
    <form action="{{ route('admin.expenses.store') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Judul / Keterangan') }} *</label>
          <input type="text" name="title" required placeholder="{{ __('contoh: Beli minyak pijat...') }}"
                 class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Kategori') }} *</label>
          <select name="category" required class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                  style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
            @foreach(\App\Models\Expense::categories() as $cat)
              <option value="{{ $cat }}">{{ __( \App\Models\Expense::categoryLabel($cat) ) }}</option>
            @endforeach
          </select>
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Jumlah (AED)') }} *</label>
          <input type="number" name="amount" required min="1" step="0.01" placeholder="{{ __('contoh: 250') }}"
                 class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Tanggal') }} *</label>
          <input type="date" name="expense_date" required value="{{ now()->format('Y-m-d') }}"
                 class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
        <div class="space-y-1 sm:col-span-2">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Catatan') }}</label>
          <input type="text" name="notes" placeholder="{{ __('opsional...') }}"
                 class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#1a1a1a;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
      </div>
      <div class="mt-4 flex justify-end">
        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black transition-all hover:opacity-90"
                style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;">
          <i data-lucide="save" class="w-4 h-4 inline mr-1"></i>
          {{ __('Simpan Pengeluaran') }}
        </button>
      </div>
    </form>
  </div>

  {{-- ── Expense Table ───────────────────────────────── --}}
  <div class="p-5 rounded-2xl space-y-4" style="background:#141414;border:1px solid #D4AF3720;">
    <div class="flex flex-wrap items-center justify-between gap-3 pb-3" style="border-bottom:1px solid #D4AF3715;">
      <div>
        <h2 class="text-sm font-black text-white">{{ __('Daftar Pengeluaran') }}</h2>
        <p class="text-[11px] text-gray-500">{{ $expenses->count() }} {{ __('data ditemukan') }}</p>
      </div>
      <a href="{{ route('admin.expenses.export.excel', ['start_date' => $startDate, 'end_date' => $endDate, 'category' => $category]) }}"
         class="px-4 py-2 rounded-xl font-bold text-xs transition-all flex items-center gap-2 hover:opacity-90"
         style="background:#1a1a1a;border:1px solid #10B98150;color:#34D399;">
        <i data-lucide="sheet" class="w-4 h-4 text-emerald-400"></i>
        <span>{{ __('Unduh Excel') }}</span>
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="text-gray-600 uppercase tracking-wider text-[9px]" style="border-bottom:1px solid #D4AF3715;">
            <th class="py-2.5 px-2 text-center w-10">{{ __('No') }}</th>
            <th class="py-2.5 px-2">{{ __('Tanggal') }}</th>
            <th class="py-2.5 px-2">{{ __('Keterangan') }}</th>
            <th class="py-2.5 px-2">{{ __('Kategori') }}</th>
            <th class="py-2.5 px-2 text-right">{{ __('Jumlah') }}</th>
            <th class="py-2.5 px-2 hidden md:table-cell">{{ __('Catatan') }}</th>
            <th class="py-2.5 px-2 text-right">{{ __('Aksi') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($expenses as $exp)
          <tr class="hover:bg-white/5 transition-colors" style="border-bottom:1px solid #D4AF3710;">
            <td class="py-3 px-2 text-center font-bold text-gray-400 font-mono">{{ $loop->iteration }}</td>
            <td class="py-3 px-2 text-gray-400 whitespace-nowrap">{{ $exp->expense_date->format('d M Y') }}</td>
            <td class="py-3 px-2 font-bold text-white">{{ $exp->title }}</td>
            <td class="py-3 px-2">
              <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase"
                    style="background:#D4AF3720;color:#D4AF37;border:1px solid #D4AF3730;">
                {{ __( \App\Models\Expense::categoryLabel($exp->category) ) }}
              </span>
            </td>
            <td class="py-3 px-2 text-right font-black text-red-400 whitespace-nowrap">
              AED {{ number_format($exp->amount, 2) }}
            </td>
            <td class="py-3 px-2 text-gray-500 hidden md:table-cell text-[11px]">{{ $exp->notes ?? '-' }}</td>
            <td class="py-3 px-2 text-right">
              <div class="flex items-center justify-end gap-2">
                {{-- Edit button via modal trigger --}}
                <button onclick="openEditExpense({{ $exp->id }}, '{{ addslashes($exp->title) }}', '{{ $exp->category }}', {{ $exp->amount }}, '{{ $exp->expense_date->format('Y-m-d') }}', '{{ addslashes($exp->notes ?? '') }}')"
                        class="px-2.5 py-1.5 rounded-lg text-[10px] font-bold transition-all hover:opacity-80"
                        style="background:#D4AF3720;border:1px solid #D4AF3730;color:#D4AF37;">
                  {{ __('Edit') }}
                </button>
                <form action="{{ route('admin.expenses.destroy', $exp) }}" method="POST" class="inline"
                      data-confirm="{{ __('Hapus pengeluaran ini?') }}" data-confirm-title="{{ __('Hapus Pengeluaran') }}">
                  @csrf @method('DELETE')
                  <button type="submit" class="px-2.5 py-1.5 rounded-lg text-[10px] font-bold transition-all hover:opacity-80 bg-red-950/50 border border-red-800/30 text-red-400">
                    {{ __('Hapus') }}
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="py-12 text-center text-xs text-gray-600">{{ __('Belum ada data pengeluaran untuk periode ini.') }}</td>
          </tr>
          @endforelse
        </tbody>
        @if($expenses->count() > 0)
        <tfoot>
          <tr>
            <td colspan="3" class="py-3 px-2 text-right text-[11px] font-extrabold text-gray-400 uppercase">{{ __('Total:') }}</td>
            <td class="py-3 px-2 text-right font-black text-red-400">AED {{ number_format($totalExpense, 2) }}</td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>

</div>

{{-- ── Edit Modal ─────────────────────────────────────── --}}
<div id="editExpenseModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
  <div class="w-full max-w-lg rounded-2xl p-6 space-y-5" style="background:#1a1a1a;border:1px solid #D4AF3740;">
    <div class="flex items-center justify-between">
      <h3 class="font-black text-white">{{ __('Edit Pengeluaran') }}</h3>
      <button onclick="closeEditExpense()" class="text-gray-500 hover:text-white">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>
    <form id="editExpenseForm" method="POST" class="space-y-4">
      @csrf @method('PUT')
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="space-y-1 sm:col-span-2">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Keterangan') }}</label>
          <input type="text" name="title" id="edit_title" required
                 class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#111;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Kategori') }}</label>
          <select name="category" id="edit_category" required
                  class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                  style="background:#111;border:1px solid #D4AF3730;color:#e5e7eb;">
            @foreach(\App\Models\Expense::categories() as $cat)
              <option value="{{ $cat }}">{{ __( \App\Models\Expense::categoryLabel($cat) ) }}</option>
            @endforeach
          </select>
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Jumlah (AED)') }}</label>
          <input type="number" name="amount" id="edit_amount" required min="1" step="0.01"
                 class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#111;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Tanggal') }}</label>
          <input type="date" name="expense_date" id="edit_date" required
                 class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#111;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
        <div class="space-y-1 sm:col-span-2">
          <label class="text-[10px] font-bold text-gray-500 uppercase block">{{ __('Catatan') }}</label>
          <input type="text" name="notes" id="edit_notes"
                 class="w-full px-3 py-2 rounded-xl text-xs font-semibold outline-none"
                 style="background:#111;border:1px solid #D4AF3730;color:#e5e7eb;">
        </div>
      </div>
      <div class="flex justify-end gap-3">
        <button type="button" onclick="closeEditExpense()"
                class="px-4 py-2 rounded-xl text-xs font-bold text-gray-400 hover:text-white transition-all"
                style="background:#2a2a2a;border:1px solid #374151;">{{ __('Batal') }}</button>
        <button type="submit" class="px-6 py-2 rounded-xl text-xs font-black hover:opacity-90 transition-all"
                style="background:linear-gradient(135deg,#D4AF37,#B8960C);color:#111;">{{ __('Simpan Perubahan') }}</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  Chart.defaults.color = '#6b7280';

  const monthlyData = @json($monthlyData);
  const categoryData = @json($categoryBreakdown);

  // Monthly expense line chart
  const expMonthCtx = document.getElementById('expenseMonthChart');
  if (expMonthCtx) {
    const c2d = expMonthCtx.getContext('2d');
    const grad = c2d.createLinearGradient(0, 0, 0, 220);
    grad.addColorStop(0, 'rgba(239,68,68,0.3)');
    grad.addColorStop(1, 'rgba(239,68,68,0)');

    new Chart(c2d, {
      type: 'line',
      data: {
        labels: monthlyData.map(d => d.month),
        datasets: [{
          label: '{{ __("Pengeluaran (AED)") }}',
          data: monthlyData.map(d => d.amount),
          borderColor: '#EF4444',
          borderWidth: 3,
          backgroundColor: grad,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#EF4444',
          pointBorderColor: '#111',
          pointBorderWidth: 2,
          pointRadius: 5,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { color: '#D4AF3710' }, ticks: { color: '#6b7280' } },
          y: {
            grid: { color: '#D4AF3710' },
            ticks: {
              color: '#6b7280',
              callback: v => 'AED ' + Number(v).toLocaleString(),
            }
          }
        }
      }
    });
  }

  // Category donut chart
  const expCatCtx = document.getElementById('expenseCatChart');
  if (expCatCtx && categoryData.length > 0) {
    new Chart(expCatCtx, {
      type: 'doughnut',
      data: {
        labels: categoryData.map(d => d.category),
        datasets: [{
          data: categoryData.map(d => d.total),
          backgroundColor: ['#D4AF37','#EF4444','#3B82F6','#A855F7','#22C55E'],
          borderWidth: 3,
          borderColor: '#141414',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { color: '#9ca3af', usePointStyle: true, padding: 8, font: { size: 10, weight: '700' } }
          }
        },
        cutout: '65%',
      }
    });
  }

  // Edit modal functions
  function openEditExpense(id, title, category, amount, date, notes) {
    document.getElementById('editExpenseForm').action = '/admin/expenses/' + id;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_amount').value = amount;
    document.getElementById('edit_date').value = date;
    document.getElementById('edit_notes').value = notes;
    document.getElementById('editExpenseModal').classList.remove('hidden');
    document.getElementById('editExpenseModal').classList.add('flex');
    lucide.createIcons();
  }
  function closeEditExpense() {
    document.getElementById('editExpenseModal').classList.add('hidden');
    document.getElementById('editExpenseModal').classList.remove('flex');
  }
</script>
@endpush
