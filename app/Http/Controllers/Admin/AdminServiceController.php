<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpaService;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class AdminServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = SpaService::withCount('bookings')->latest()->get();
        return view('admin.services', compact('services'));
    }

    public function exportExcel(Request $request, ExcelExportService $excelService)
    {
        $services = SpaService::withCount('bookings')->latest()->get();

        $headers = [
            'No',
            'Nama Layanan Spa',
            'Kategori',
            'Durasi Standar (Menit)',
            'Tarif Standar (AED)',
            'Rating Layanan',
            'Total Treatment (Kali)',
            'Status Aktif',
        ];

        $rows = [];
        foreach ($services as $idx => $svc) {
            $rows[] = [
                $idx + 1,
                $svc->name,
                $svc->category,
                (int) $svc->duration,
                (float) $svc->price,
                number_format($svc->rating, 1),
                (int) $svc->bookings_count,
                $svc->is_active ? 'AKTIF' : 'NONAKTIF',
            ];
        }

        $columnTypes = [
            0 => 'int',
            1 => 'string',
            2 => 'center',
            3 => 'int',
            4 => 'currency',
            5 => 'center',
            6 => 'int',
            7 => 'center',
        ];

        $locale = app()->getLocale();
        if ($locale === 'ar') {
            $baseName = 'خدمات_السبا_والعلاج';
        } elseif ($locale === 'en') {
            $baseName = 'Spa_Services_Catalog';
        } else {
            $baseName = 'Data_Layanan_Spa';
        }
        $filename = "{$baseName}_" . now()->format('Ymd_His');

        return $excelService->download(
            filename: $filename,
            title: 'KATALOG LAYANAN SPA & WELLNESS',
            subtitle: 'Daftar Layanan Spa Resmi F55 Center',
            headers: $headers,
            rows: $rows,
            columnTypes: $columnTypes,
            totalColumns: [],
            currencyCode: 'AED',
            extraMetadata: ['Total Layanan' => count($rows) . ' Menu']
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category'    => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'duration'    => ['required', 'integer', 'min:30'],
            'price'       => ['required', 'numeric', 'min:0'],
            'rating'      => ['nullable', 'numeric', 'between:0,5'],
            'image'       => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'benefits'    => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active', true);
        if (!empty($data['benefits'])) {
            $data['benefits'] = array_filter(array_map('trim', explode(',', $data['benefits'])));
        }

        SpaService::create($data);
        return redirect()->route('admin.services')->with('success', __('Layanan berhasil ditambahkan dengan foto.'));
    }

    public function update(Request $request, SpaService $spaService)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category'    => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'duration'    => ['required', 'integer', 'min:30'],
            'price'       => ['required', 'numeric', 'min:0'],
            'rating'      => ['nullable', 'numeric', 'between:0,5'],
            'image'       => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'benefits'    => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            // Securely remove old image if locally stored
            if ($spaService->image_url && str_starts_with($spaService->image_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $spaService->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('services', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active', true);
        if (!empty($data['benefits'])) {
            $data['benefits'] = array_filter(array_map('trim', explode(',', $data['benefits'])));
        }

        $spaService->update($data);
        return redirect()->route('admin.services')->with('success', __('Layanan berhasil diperbarui.'));
    }

    public function destroy(SpaService $spaService)
    {
        if ($spaService->image_url && str_starts_with($spaService->image_url, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $spaService->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $spaService->delete();
        return redirect()->route('admin.services')->with('success', __('Layanan berhasil dihapus.'));
    }

    public function toggleActive(SpaService $spaService)
    {
        $spaService->update(['is_active' => !$spaService->is_active]);
        return back()->with('success', __('Status layanan diperbarui.'));
    }
}
