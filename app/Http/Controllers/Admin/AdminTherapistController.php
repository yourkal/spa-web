<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Therapist;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class AdminTherapistController extends Controller
{
    public function index()
    {
        $therapists = Therapist::withCount('bookings')
            ->with(['bookings' => function ($q) {
                $q->whereIn('status', ['confirmed', 'in_progress'])
                  ->whereDate('booking_date', '>=', now()->toDateString())
                  ->orderBy('booking_date')
                  ->orderBy('booking_time');
            }])
            ->latest()
            ->get();
        return view('admin.therapists', compact('therapists'));
    }

    public function exportExcel(Request $request, ExcelExportService $excelService)
    {
        $therapists = Therapist::withCount('bookings')->latest()->get();

        $headers = [
            'No',
            'Nama Terapis',
            'Spesialisasi Treatment',
            'Pengalaman (Tahun)',
            'Rating Pelayanan',
            'No. WhatsApp / HP',
            'Status Kerja Saat Ini',
            'Total Menangani Treatment',
        ];

        $rows = [];
        foreach ($therapists as $idx => $t) {
            $rows[] = [
                $idx + 1,
                $t->name,
                $t->speciality,
                (int) $t->experience,
                number_format($t->rating, 1),
                $t->phone ?? '-',
                strtoupper($t->status_label ?? $t->status),
                (int) $t->bookings_count,
            ];
        }

        $columnTypes = [
            0 => 'int',
            1 => 'string',
            2 => 'string',
            3 => 'int',
            4 => 'center',
            5 => 'center',
            6 => 'center',
            7 => 'int',
        ];

        $locale = app()->getLocale();
        if ($locale === 'ar') {
            $baseName = 'بيانات_معالجي_السبا';
        } elseif ($locale === 'en') {
            $baseName = 'Spa_Therapists_Directory';
        } else {
            $baseName = 'Data_Terapis';
        }
        $filename = "{$baseName}_" . now()->format('Ymd_His');

        return $excelService->download(
            filename: $filename,
            title: 'DATA TERAPIS & KARYAWAN SPA',
            subtitle: 'Daftar Seluruh Terapis F55 Relaxation Center',
            headers: $headers,
            rows: $rows,
            columnTypes: $columnTypes,
            totalColumns: [],
            currencyCode: 'AED',
            extraMetadata: ['Total Terapis' => count($rows) . ' Orang']
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'speciality'  => ['required', 'string'],
            'experience'  => ['nullable', 'integer', 'min:0'],
            'rating'      => ['nullable', 'numeric', 'between:0,5'],
            'phone'       => ['nullable', 'string'],
            'image'       => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'status'      => ['nullable', 'in:ready,not_ready,serving,off'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('therapists', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        $data['status'] = $request->input('status', 'ready');
        $data['is_active'] = $data['status'] !== 'off';

        Therapist::create($data);
        return redirect()->route('admin.therapists')->with('success', __('Therapist berhasil ditambahkan dengan foto.'));
    }

    public function update(Request $request, Therapist $therapist)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'speciality'  => ['required', 'string'],
            'experience'  => ['nullable', 'integer', 'min:0'],
            'rating'      => ['nullable', 'numeric', 'between:0,5'],
            'phone'       => ['nullable', 'string'],
            'image'       => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'status'      => ['nullable', 'in:ready,not_ready,serving,off'],
        ]);

        if ($request->hasFile('image')) {
            if ($therapist->avatar && str_starts_with($therapist->avatar, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $therapist->avatar);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('therapists', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        if ($request->filled('status')) {
            $data['status'] = $request->input('status');
            $data['is_active'] = $data['status'] !== 'off';
        }

        $therapist->update($data);
        return redirect()->route('admin.therapists')->with('success', __('Data therapist berhasil diperbarui.'));
    }

    public function updateStatus(Request $request, Therapist $therapist)
    {
        $request->validate([
            'status' => ['required', 'in:ready,not_ready,serving,off']
        ]);

        $status = $request->status;
        $therapist->update([
            'status'    => $status,
            'is_active' => $status !== 'off',
        ]);

        return back()->with('success', __('Status therapist berhasil diubah menjadi :status.', ['status' => __($therapist->status_label)]));
    }

    public function destroy(Therapist $therapist)
    {
        if ($therapist->avatar && str_starts_with($therapist->avatar, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $therapist->avatar);
            Storage::disk('public')->delete($oldPath);
        }

        $therapist->delete();
        return redirect()->route('admin.therapists')->with('success', __('Therapist berhasil dihapus.'));
    }

    public function toggleActive(Therapist $therapist)
    {
        $newActive = !$therapist->is_active;
        $therapist->update([
            'is_active' => $newActive,
            'status'    => $newActive ? 'ready' : 'off',
        ]);
        return back()->with('success', __('Status therapist diperbarui.'));
    }
}
