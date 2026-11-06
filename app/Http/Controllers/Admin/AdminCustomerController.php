<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $customers = User::where('role', 'customer')
            ->withCount('bookings')
            ->withSum(['bookings as total_spend' => function($q) {
                $q->where('status', 'completed');
            }], 'price')
            ->when($search, function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.customers', compact('customers', 'search'));
    }

    public function show(User $user)
    {
        abort_unless($user->isCustomer(), 404);

        $user->load(['bookings' => function($q) {
            $q->latest();
        }]);

        $totalSpend = $user->bookings->where('status', 'completed')->sum('price');
        $totalVisits = $user->bookings->where('status', 'completed')->count();

        return view('admin.customer-detail', compact('user', 'totalSpend', 'totalVisits'));
    }

    public function exportExcel(Request $request, ExcelExportService $excelService)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $search = $request->get('search');

        $customers = User::where('role', 'customer')
            ->withCount('bookings')
            ->withSum(['bookings as total_spend' => function($q) {
                $q->where('status', 'completed');
            }], 'price')
            ->when($search, function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        $headers = [
            'No',
            'Nama Pelanggan',
            'Email',
            'No. WhatsApp / Telepon',
            'Total Reservasi (Kali)',
            'Total Belanja (AED)',
            'Tanggal Bergabung',
        ];

        $rows = [];
        foreach ($customers as $idx => $c) {
            $rows[] = [
                $idx + 1,
                $c->name,
                $c->email,
                $c->phone ?? '-',
                (int) $c->bookings_count,
                (float) ($c->total_spend ?? 0),
                $c->created_at ? $c->created_at->format('Y-m-d') : '-',
            ];
        }

        $columnTypes = [
            0 => 'int',
            1 => 'string',
            2 => 'string',
            3 => 'center',
            4 => 'int',
            5 => 'currency',
            6 => 'date',
        ];

        $subtitle = $search ? "Filter Pencarian: {$search}" : 'Seluruh Data Pelanggan Terdaftar';
        $locale = app()->getLocale();
        if ($locale === 'ar') {
            $baseName = 'بيانات_عملاء_السبا';
        } elseif ($locale === 'en') {
            $baseName = 'Customer_Directory_Data';
        } else {
            $baseName = 'Data_Pelanggan';
        }
        $filename = "{$baseName}_" . now()->format('Ymd_His');

        return $excelService->download(
            filename: $filename,
            title: 'DATA PELANGGAN & CUSTOMER SPA',
            subtitle: $subtitle,
            headers: $headers,
            rows: $rows,
            columnTypes: $columnTypes,
            totalColumns: [5],
            currencyCode: 'AED',
            extraMetadata: ['Total Pelanggan' => count($rows) . ' Orang']
        );
    }
}
