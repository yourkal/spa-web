<?php

namespace App\Http\Controllers\Bos;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\SpaService;
use App\Services\ExcelExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BosReportController extends Controller
{
    /**
     * Halaman Laporan Keuangan untuk Role Boss (Read-Only).
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $completedBookings = Booking::with('user', 'service', 'therapist')
            ->where('status', 'completed')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->latest('booking_date')
            ->get();

        $totalRevenue  = (float) $completedBookings->sum('price');
        $totalBookings = $completedBookings->count();
        $avgOrderValue = $totalBookings > 0 ? round($totalRevenue / $totalBookings) : 0;

        // Total expenses in the same period
        $totalExpenses = (float) Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        $netProfit     = $totalRevenue - $totalExpenses;

        // Daily chart data for the period
        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);
        $diffDays = $start->diffInDays($end);

        $dailyBookings = Booking::where('status', 'completed')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->selectRaw('DATE(booking_date) as date, SUM(price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('expense_date as date, SUM(amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartRevenue = [];
        $chartExpense = [];

        $stepDays = $diffDays > 31 ? max(1, (int)ceil($diffDays / 20)) : 1;
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $dStr = $cursor->format('Y-m-d');
            $label = $cursor->format('d M');
            $chartLabels[] = $label;
            $chartRevenue[] = (float) ($dailyBookings[$dStr] ?? 0);
            $chartExpense[] = (float) ($dailyExpenses[$dStr] ?? 0);
            $cursor->addDays($stepDays);
        }

        // Service breakdown with revenue and bookings count
        $serviceBreakdown = SpaService::withCount(['bookings' => function($q) use ($startDate, $endDate) {
            $q->where('status', 'completed')->whereBetween('booking_date', [$startDate, $endDate]);
        }])
        ->withSum(['bookings' => function($q) use ($startDate, $endDate) {
            $q->where('status', 'completed')->whereBetween('booking_date', [$startDate, $endDate]);
        }], 'price')
        ->get();

        return view('bos.reports', compact(
            'completedBookings', 'totalRevenue', 'totalBookings',
            'avgOrderValue', 'totalExpenses', 'netProfit',
            'serviceBreakdown', 'startDate', 'endDate',
            'chartLabels', 'chartRevenue', 'chartExpense'
        ));
    }

    /**
     * Unduh Excel Laporan Keuangan (Boss).
     */
    public function exportExcel(Request $request, ExcelExportService $excelService)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $completedBookings = Booking::with('user', 'service', 'therapist')
            ->where('status', 'completed')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->latest('booking_date')
            ->get();

        $headers = [
            'No',
            'Kode Transaksi',
            'Nama Pelanggan',
            'No. WhatsApp',
            'Layanan Spa',
            'Terapis',
            'Tanggal Treatment',
            'Jam Kedatangan',
            'Durasi (Menit)',
            'Total Omset (AED)',
            'Metode Pembayaran',
            'Status Transaksi',
        ];

        $rows = [];
        foreach ($completedBookings as $idx => $b) {
            $rows[] = [
                $idx + 1,
                $b->booking_code,
                $b->customer_name,
                $b->customer_phone,
                $b->service_name,
                $b->therapist_name ?? '-',
                $b->booking_date ? $b->booking_date->format('Y-m-d') : '-',
                $b->booking_time ?? '-',
                (int) $b->duration,
                (float) $b->price,
                $b->payment_method ?? '-',
                strtoupper($b->status),
            ];
        }

        $columnTypes = [
            0 => 'int',
            1 => 'center',
            2 => 'string',
            3 => 'center',
            4 => 'string',
            5 => 'string',
            6 => 'date',
            7 => 'center',
            8 => 'int',
            9 => 'currency',
            10 => 'center',
            11 => 'center',
        ];

        $totalRevenue  = (float) $completedBookings->sum('price');
        $totalExpenses = (float) Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        $netProfit     = $totalRevenue - $totalExpenses;

        $extraMetadata = [
            __('Total Transaksi')   => count($rows) . ' ' . __('Treatment'),
            __('Total Omset')       => 'AED ' . number_format($totalRevenue, 2),
            __('Total Pengeluaran') => 'AED ' . number_format($totalExpenses, 2),
            __('Laba Bersih')       => 'AED ' . number_format($netProfit, 2),
        ];

        $locale = app()->getLocale();
        if ($locale === 'ar') {
            $baseName = 'التقرير_المالي';
            $sep = '_إلى_';
        } elseif ($locale === 'en') {
            $baseName = 'Financial_Report';
            $sep = '_to_';
        } else {
            $baseName = 'Laporan_Keuangan';
            $sep = '_sd_';
        }
        $filename = "{$baseName}_{$startDate}{$sep}{$endDate}";

        return $excelService->download(
            filename: $filename,
            title: __('LAPORAN KEUANGAN & OMSET BISNIS (EKSEKUTIF)'),
            subtitle: __('Periode:') . " {$startDate} - {$endDate}",
            headers: $headers,
            rows: $rows,
            columnTypes: $columnTypes,
            totalColumns: [9],
            currencyCode: 'AED',
            extraMetadata: $extraMetadata
        );
    }

    /**
     * Unduh PDF Laporan Keuangan (Boss).
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date'   => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $completedBookings = Booking::with('user', 'service', 'therapist')
            ->where('status', 'completed')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->latest('booking_date')
            ->get();

        $totalRevenue  = (float) $completedBookings->sum('price');
        $totalBookings = $completedBookings->count();
        $avgOrderValue = $totalBookings > 0 ? round($totalRevenue / $totalBookings) : 0;
        $totalExpenses = (float) Expense::whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        $netProfit     = $totalRevenue - $totalExpenses;

        $serviceBreakdown = SpaService::withCount(['bookings' => function($q) use ($startDate, $endDate) {
            $q->where('status', 'completed')->whereBetween('booking_date', [$startDate, $endDate]);
        }])
        ->withSum(['bookings' => function($q) use ($startDate, $endDate) {
            $q->where('status', 'completed')->whereBetween('booking_date', [$startDate, $endDate]);
        }], 'price')
        ->get();

        $expensesByCategory = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category, COUNT(*) as total_items, SUM(amount) as total_amount')
            ->groupBy('category')
            ->get();

        $pdf = Pdf::loadView('admin.reports-pdf', compact(
            'completedBookings', 'totalRevenue', 'totalBookings',
            'avgOrderValue', 'totalExpenses', 'netProfit',
            'serviceBreakdown', 'expensesByCategory', 'startDate', 'endDate'
        ))->setPaper('a4', 'portrait');

        $locale = app()->getLocale();
        if ($locale === 'ar') {
            $baseName = 'التقرير_المالي';
            $sep = '_إلى_';
        } elseif ($locale === 'en') {
            $baseName = 'Financial_Report';
            $sep = '_to_';
        } else {
            $baseName = 'Laporan_Keuangan';
            $sep = '_sd_';
        }
        $filename = "{$baseName}_{$startDate}{$sep}{$endDate}.pdf";

        return $pdf->download($filename);
    }
}

