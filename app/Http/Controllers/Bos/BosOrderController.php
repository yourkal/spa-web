<?php

namespace App\Http\Controllers\Bos;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class BosOrderController extends Controller
{
    /**
     * Halaman Order: Melihat daftar order berjalan dan seluruh pesanan aktif (Read-Only).
     */
    public function orders(Request $request)
    {
        $status = $request->get('status');
        $date   = $request->get('date');
        $search = $request->get('search');

        $bookings = Booking::with('user', 'service', 'therapist', 'activeSession')
            ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
            ->when($date, fn($q) => $q->whereDate('booking_date', $date))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(10);

        foreach ($bookings as $b) {
            if ($b->activeSession && $b->activeSession->status === 'running') {
                $b->activeSession->syncRemaining();
            }
        }

        $statuses = ['all', 'confirmed', 'in_progress', 'completed', 'cancelled'];

        return view('bos.orders', compact('bookings', 'statuses', 'status', 'date', 'search'));
    }

    /**
     * Halaman Riwayat Order: Melihat seluruh riwayat transaksi order yang telah selesai / historis (Read-Only).
     */
    public function history(Request $request)
    {
        $status = $request->get('status', 'completed');
        $date   = $request->get('date');
        $search = $request->get('search');

        $bookings = Booking::with('user', 'service', 'therapist')
            ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
            ->when($date, fn($q) => $q->whereDate('booking_date', $date))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%");
            }))
            ->latest('booking_date')
            ->paginate(10);

        $statuses = ['all', 'completed', 'cancelled'];

        return view('bos.order-history', compact('bookings', 'statuses', 'status', 'date', 'search'));
    }

    /**
     * Detail Order (Read-Only).
     */
    public function show(Booking $booking)
    {
        $booking->load('user', 'service', 'therapist', 'activeSession');
        return view('admin.booking-detail', compact('booking'));
    }

    /**
     * Unduh Excel Data Order (Boss).
     */
    public function exportExcel(Request $request, ExcelExportService $excelService)
    {
        $request->validate([
            'status' => ['nullable', 'string', 'in:all,confirmed,in_progress,completed,cancelled'],
            'date'   => ['nullable', 'date'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $status = $request->get('status');
        $date   = $request->get('date');
        $search = $request->get('search');

        $bookings = Booking::with('user', 'service', 'therapist')
            ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
            ->when($date, fn($q) => $q->whereDate('booking_date', $date))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%");
            }))
            ->latest()
            ->get();

        $headers = [
            'No',
            'Kode Order / Booking',
            'Nama Pelanggan',
            'No. WhatsApp',
            'Layanan Spa',
            'Terapis',
            'Tanggal Booking',
            'Jam Kedatangan',
            'Durasi (Menit)',
            'Total Harga (AED)',
            'Metode Pembayaran',
            'Status Order',
        ];

        $rows = [];
        foreach ($bookings as $idx => $b) {
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

        $filterDesc = [];
        if ($status && $status !== 'all') $filterDesc[] = "Status: " . ucfirst($status);
        if ($date) $filterDesc[] = "Tanggal: " . $date;
        if ($search) $filterDesc[] = "Pencarian: " . $search;
        $subtitle = empty($filterDesc) ? 'Seluruh Data Order Perawatan' : implode(' | ', $filterDesc);

        $locale = app()->getLocale();
        if ($locale === 'ar') {
            $baseName = 'قائمة_طلبات_السبا';
        } elseif ($locale === 'en') {
            $baseName = 'Orders_List_Data';
        } else {
            $baseName = 'Data_Order';
        }
        $filename = "{$baseName}_" . now()->format('Ymd_His');

        return $excelService->download(
            filename: $filename,
            title: 'DAFTAR ORDER & PESANAN SPA (EKSEKUTIF)',
            subtitle: $subtitle,
            headers: $headers,
            rows: $rows,
            columnTypes: $columnTypes,
            totalColumns: [9],
            currencyCode: 'AED',
            extraMetadata: ['Total Order' => count($rows) . ' Transaksi']
        );
    }

    /**
     * Unduh Excel Riwayat Order (Boss).
     */
    public function exportHistoryExcel(Request $request, ExcelExportService $excelService)
    {
        $request->validate([
            'status' => ['nullable', 'string', 'in:all,completed,cancelled'],
            'date'   => ['nullable', 'date'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $status = $request->get('status', 'completed');
        $date   = $request->get('date');
        $search = $request->get('search');

        $bookings = Booking::with('user', 'service', 'therapist')
            ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
            ->when($date, fn($q) => $q->whereDate('booking_date', $date))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%");
            }))
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
            'Jam Kunjungan',
            'Durasi (Menit)',
            'Nilai Transaksi (AED)',
            'Metode Pembayaran',
            'Status Akhir',
        ];

        $rows = [];
        foreach ($bookings as $idx => $b) {
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

        $filterDesc = [];
        if ($status && $status !== 'all') $filterDesc[] = "Status: " . ucfirst($status);
        if ($date) $filterDesc[] = "Tanggal: " . $date;
        if ($search) $filterDesc[] = "Pencarian: " . $search;
        $subtitle = empty($filterDesc) ? 'Riwayat Transaksi Order Selesai' : implode(' | ', $filterDesc);

        $locale = app()->getLocale();
        if ($locale === 'ar') {
            $baseName = 'سجل_الطلبات_المكتملة';
        } elseif ($locale === 'en') {
            $baseName = 'Completed_Orders_History';
        } else {
            $baseName = 'Riwayat_Order';
        }
        $filename = "{$baseName}_" . now()->format('Ymd_His');

        return $excelService->download(
            filename: $filename,
            title: 'RIWAYAT ORDER & TRANSAKSI SPA (EKSEKUTIF)',
            subtitle: $subtitle,
            headers: $headers,
            rows: $rows,
            columnTypes: $columnTypes,
            totalColumns: [9],
            currencyCode: 'AED',
            extraMetadata: ['Total Transaksi' => count($rows) . ' Record']
        );
    }
}

