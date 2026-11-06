<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8">
  <title>{{ __('Laporan Keuangan') }} - {{ $startDate }} - {{ $endDate }}</title>
  <style>
    @page {
      margin: 15mm 12mm 15mm 12mm;
      size: A4 portrait;
    }
    body {
      font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 9px;
      color: #1f2937;
      line-height: 1.45;
      margin: 0;
      padding: 0;
      direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
      text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
    }
    
    /* Header Area */
    .header-table {
      width: 100%;
      border-bottom: 2.5px solid #D4AF37;
      padding-bottom: 12px;
      margin-bottom: 15px;
    }
    .brand-title {
      font-size: 15px;
      font-weight: bold;
      color: #111827;
      letter-spacing: 0.5px;
    }
    .brand-subtitle {
      font-size: 9.5px;
      color: #B8960C;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 2px;
    }
    .brand-contact {
      font-size: 8px;
      color: #6b7280;
      margin-top: 3px;
    }
    .report-title {
      font-size: 13px;
      font-weight: bold;
      color: #111827;
      text-transform: uppercase;
      {{ app()->getLocale() === 'ar' ? 'text-align: left;' : 'text-align: right;' }}
    }
    .report-badge {
      display: inline-block;
      background: #D4AF37;
      color: #111;
      font-weight: bold;
      font-size: 8px;
      padding: 2px 7px;
      border-radius: 4px;
      margin-bottom: 4px;
      text-transform: uppercase;
    }
    .report-meta {
      font-size: 8.5px;
      color: #4b5563;
      {{ app()->getLocale() === 'ar' ? 'text-align: left;' : 'text-align: right;' }}
      line-height: 1.5;
    }

    /* KPI Summary Cards */
    .kpi-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 5px;
      margin-bottom: 14px;
    }
    .kpi-box {
      background: #fdfdfd;
      border: 1px solid #e5e7eb;
      border-top: 2.5px solid #D4AF37;
      border-radius: 6px;
      padding: 7px 8px;
      text-align: center;
    }
    .kpi-box-red {
      border-top-color: #ef4444;
    }
    .kpi-box-green {
      border-top-color: #10b981;
    }
    .kpi-title {
      font-size: 7.5px;
      text-transform: uppercase;
      color: #6b7280;
      font-weight: bold;
      margin-bottom: 3px;
    }
    .kpi-value {
      font-size: 12px;
      font-weight: bold;
      color: #111827;
    }
    .kpi-gold { color: #B8960C; }
    .kpi-red { color: #dc2626; }
    .kpi-green { color: #16a34a; }

    /* Section Headers */
    .section-title {
      font-size: 10px;
      font-weight: bold;
      color: #111827;
      margin-top: 14px;
      margin-bottom: 6px;
      text-transform: uppercase;
      border-left: 3.5px solid #D4AF37;
      padding-left: 6px;
      {{ app()->getLocale() === 'ar' ? 'border-left: none; border-right: 3.5px solid #D4AF37; padding-left: 0; padding-right: 6px;' : '' }}
    }

    /* Data Tables */
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
    }
    table.data-table th {
      background-color: #1f2937;
      color: #F5DF93;
      font-weight: bold;
      font-size: 8px;
      text-transform: uppercase;
      padding: 6px 5px;
      border: 1px solid #374151;
      text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
    }
    table.data-table td {
      padding: 5px;
      border: 1px solid #e5e7eb;
      font-size: 8px;
    }
    table.data-table tr:nth-child(even) td {
      background-color: #f9fafb;
    }
    .text-center { text-align: center !important; }
    .text-right { text-align: right !important; }
    .text-left { text-align: left !important; }
    .text-bold { font-weight: bold; }

    .total-row td {
      background-color: #fef08a !important;
      font-weight: bold;
      border-top: 1.5px solid #ca8a04 !important;
      border-bottom: 2px solid #a16207 !important;
      color: #713f12;
    }

    /* Breakdown Tables Grid */
    .grid-table {
      width: 100%;
      margin-top: 6px;
    }

    /* Signatures Section */
    .signatures {
      width: 100%;
      margin-top: 25px;
      page-break-inside: avoid;
    }
    .sign-box {
      width: 32%;
      text-align: center;
    }
    .sign-role {
      font-size: 8px;
      color: #6b7280;
      text-transform: uppercase;
      margin-bottom: 45px;
      font-weight: bold;
    }
    .sign-name {
      font-weight: bold;
      font-size: 9px;
      border-top: 1px solid #374151;
      padding-top: 4px;
      color: #111827;
    }

    /* Footer */
    .footer-note {
      position: fixed;
      bottom: -8mm;
      left: 0;
      right: 0;
      font-size: 7.5px;
      color: #9ca3af;
      text-align: center;
      border-top: 1px solid #e5e7eb;
      padding-top: 4px;
    }
  </style>
</head>
<body>

  <!-- Footer fixed note -->
  <div class="footer-note">
    {{ __('F55 MEN RELAXATION & MASSAGE CENTER (DUBAI) — Dokumen laporan keuangan resmi dicetak secara otomatis dari sistem.') }}
  </div>

  <!-- Header Section -->
  <table class="header-table">
    <tr>
      <td style="width: 55%; vertical-align: top;">
        <table style="border: none; margin: 0; padding: 0;">
          <tr>
            <td style="width: 48px; vertical-align: middle; border: none; padding: 0; {{ app()->getLocale() === 'ar' ? 'padding-left: 10px;' : 'padding-right: 10px;' }}">
              <img src="{{ public_path('images/logo.png') }}" style="width: 44px; height: 44px; border-radius: 8px; border: 1px solid #D4AF37;">
            </td>
            <td style="vertical-align: middle; border: none; padding: 0;">
              <div class="brand-title">F55 MEN RELAXATION & MASSAGE CENTER</div>
              <div class="brand-subtitle">{{ __('Dubai Luxury Spa & Wellness Treatment') }}</div>
              <div class="brand-contact">
                Al Barsha, Dubai, United Arab Emirates | contact@f55center.com | +971 4 123 4567
              </div>
            </td>
          </tr>
        </table>
      </td>
      <td style="width: 45%; vertical-align: top;">
        <div style="{{ app()->getLocale() === 'ar' ? 'text-align: left;' : 'text-align: right;' }}">
          <span class="report-badge">{{ __('Laporan Resmi') }}</span>
        </div>
        <div class="report-title">{{ __('Laporan Keuangan & Rekap Transaksi') }}</div>
        <div class="report-meta">
          <strong>{{ __('Periode:') }}</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}<br>
          <strong>{{ __('Waktu Cetak:') }}</strong> {{ now()->format('d/m/Y H:i') }} (GST)<br>
          <strong>{{ __('Dicetak Oleh:') }}</strong> {{ Auth::user()->name ?? 'Administrator' }} ({{ ucfirst(Auth::user()->role ?? 'Admin') }})
        </div>
      </td>
    </tr>
  </table>

  <!-- KPI Summary Cards -->
  <table class="kpi-table">
    <tr>
      <td class="kpi-box">
        <div class="kpi-title">{{ __('Total Omset Kotor') }}</div>
        <div class="kpi-value kpi-gold">AED {{ number_format($totalRevenue, 2) }}</div>
      </td>
      <td class="kpi-box kpi-box-red">
        <div class="kpi-title">{{ __('Total Pengeluaran') }}</div>
        <div class="kpi-value kpi-red">AED {{ number_format($totalExpenses, 2) }}</div>
      </td>
      <td class="kpi-box kpi-box-green">
        <div class="kpi-title">{{ __('Laba Bersih (Net)') }}</div>
        <div class="kpi-value {{ $netProfit >= 0 ? 'kpi-green' : 'kpi-red' }}">
          AED {{ number_format(abs($netProfit), 2) }} {{ $netProfit < 0 ? '(' . __('Defisit') . ')' : '' }}
        </div>
      </td>
      <td class="kpi-box">
        <div class="kpi-title">{{ __('Total Transaksi') }}</div>
        <div class="kpi-value">{{ $totalBookings }} {{ __('Treatment') }}</div>
      </td>
      <td class="kpi-box">
        <div class="kpi-title">{{ __('Rata-rata Order') }}</div>
        <div class="kpi-value">AED {{ number_format($avgOrderValue, 2) }}</div>
      </td>
    </tr>
  </table>

  <!-- Section 1: Completed Bookings Table -->
  <div class="section-title">1. {{ __('Rincian Transaksi Selesai') }}</div>
  <table class="data-table">
    <thead>
      <tr>
        <th style="width: 25px;" class="text-center">{{ __('No') }}</th>
        <th style="width: 80px;">{{ __('Kode Booking') }}</th>
        <th>{{ __('Pelanggan') }}</th>
        <th>{{ __('Layanan') }}</th>
        <th>{{ __('Terapis') }}</th>
        <th style="width: 65px;" class="text-center">{{ __('Tgl & Jam') }}</th>
        <th style="width: 45px;" class="text-center">{{ __('Durasi') }}</th>
        <th style="width: 60px;">{{ __('Metode') }}</th>
        <th style="width: 75px;" class="text-right">{{ __('Total (AED)') }}</th>
      </tr>
    </thead>
    <tbody>
      @forelse($completedBookings as $i => $b)
      <tr>
        <td class="text-center">{{ $i + 1 }}</td>
        <td class="text-bold" style="color:#B8960C;">{{ $b->booking_code }}</td>
        <td>{{ $b->customer_name }}</td>
        <td>{{ $b->service_name }}</td>
        <td>{{ $b->therapist_name ?? '-' }}</td>
        <td class="text-center">{{ $b->booking_date->format('d/m/Y') }} {{ $b->booking_time }}</td>
        <td class="text-center">{{ $b->duration }} {{ __('Menit') }}</td>
        <td>{{ $b->payment_method }}</td>
        <td class="text-right text-bold">{{ number_format($b->price, 2) }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="9" class="text-center" style="padding: 15px; color: #777;">
          {{ __('Tidak ada data transaksi selesai pada periode ini.') }}
        </td>
      </tr>
      @endforelse

      @if($completedBookings->isNotEmpty())
      <tr class="total-row">
        <td colspan="8" class="text-right text-bold">{{ __('TOTAL OMSET KOTOR') }}:</td>
        <td class="text-right text-bold">AED {{ number_format($totalRevenue, 2) }}</td>
      </tr>
      @endif
    </tbody>
  </table>

  <!-- Section 2: Service & Expense Breakdown Grids -->
  <table class="grid-table">
    <tr>
      <!-- Service Performance -->
      <td style="width: 50%; vertical-align: top; padding-right: 6px;">
        <div class="section-title">2. {{ __('Rekapitulasi Performa Layanan Spa') }}</div>
        <table class="data-table">
          <thead>
            <tr>
              <th style="width: 20px;" class="text-center">{{ __('No') }}</th>
              <th>{{ __('Layanan') }}</th>
              <th style="width: 40px;" class="text-center">{{ __('Total') }}</th>
              <th style="width: 75px;" class="text-right">{{ __('Omset (AED)') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($serviceBreakdown ?? [] as $si => $sb)
            <tr>
              <td class="text-center">{{ $si + 1 }}</td>
              <td>{{ $sb->service_name ?? $sb->name }}</td>
              <td class="text-center">{{ $sb->total_bookings ?? $sb->bookings_count ?? 0 }}x</td>
              <td class="text-right text-bold">{{ number_format($sb->total_revenue ?? $sb->bookings_sum_price ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center" style="color: #888;">{{ __('Tidak ada data.') }}</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </td>

      <!-- Expense Breakdown -->
      <td style="width: 50%; vertical-align: top; padding-left: 6px;">
        <div class="section-title">3. {{ __('Rincian Pengeluaran Operasional (Expenses)') }}</div>
        <table class="data-table">
          <thead>
            <tr>
              <th style="width: 20px;" class="text-center">{{ __('No') }}</th>
              <th>{{ __('Kategori') }}</th>
              <th style="width: 40px;" class="text-center">{{ __('Item') }}</th>
              <th style="width: 75px;" class="text-right">{{ __('Jumlah (AED)') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($expensesByCategory ?? [] as $ei => $eb)
            <tr>
              <td class="text-center">{{ $ei + 1 }}</td>
              <td style="text-transform: capitalize;">{{ __($eb->category) }}</td>
              <td class="text-center">{{ $eb->total_items }}x</td>
              <td class="text-right text-bold" style="color: #dc2626;">{{ number_format($eb->total_amount, 2) }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="4" class="text-center" style="color: #888;">{{ __('Tidak ada pengeluaran pada periode ini.') }}</td>
            </tr>
            @endforelse
            @if(isset($expensesByCategory) && count($expensesByCategory) > 0)
            <tr style="background:#fee2e2; font-weight:bold; border-top:1.5px solid #dc2626;">
              <td colspan="3" class="text-right">{{ __('TOTAL PENGELUARAN') }}:</td>
              <td class="text-right" style="color:#b91c1c;">AED {{ number_format($totalExpenses, 2) }}</td>
            </tr>
            @endif
          </tbody>
        </table>
      </td>
    </tr>
  </table>

  <!-- Signatures Section -->
  <table class="signatures">
    <tr>
      <td class="sign-box" style="text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">
        <div class="sign-role">{{ __('Pembuat Laporan:') }}</div>
        <div class="sign-name">{{ Auth::user()->name ?? 'Administrator' }}<br><span style="font-size:7.5px;color:#6b7280;">{{ ucfirst(Auth::user()->role ?? 'Admin') }}</span></div>
      </td>
      <td style="width: 36%;"></td>
      <td class="sign-box" style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};">
        <div class="sign-role">{{ __('Mengetahui:') }}</div>
        <div class="sign-name">Executive Management<br><span style="font-size:7.5px;color:#6b7280;">F55 Center Dubai</span></div>
      </td>
    </tr>
  </table>

</body>
</html>
