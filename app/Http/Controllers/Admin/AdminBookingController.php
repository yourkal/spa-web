<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ActiveSession;
use App\Models\SpaService;
use App\Models\Therapist;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminBookingController extends Controller
{
    public function index(Request $request)
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

        $services  = SpaService::where('is_active', true)->get();
        $therapists = Therapist::all();
        $statuses  = ['all', 'confirmed', 'in_progress', 'completed', 'cancelled'];
        $newBookingsCount = Booking::where('is_new', true)->count();

        return view('admin.bookings', compact('bookings', 'services', 'therapists', 'statuses', 'status', 'date', 'search', 'newBookingsCount'));
    }

    public function show(Booking $booking)
    {
        $booking->load('user', 'service', 'therapist', 'activeSession');
        return view('admin.booking-detail', compact('booking'));
    }

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

        // Query ALL data matching filters (without pagination limit)
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
            'Kode Booking',
            'Nama Pelanggan',
            'No. WhatsApp',
            'Layanan Spa',
            'Terapis',
            'Tanggal Booking',
            'Jam Kedatangan',
            'Durasi (Menit)',
            'Total Harga (AED)',
            'Metode Pembayaran',
            'Status',
        ];

        $rows = [];
        // Numbering starts at 1, 2, 3...
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
        $subtitle = empty($filterDesc) ? 'Seluruh Data Reservasi Booking' : implode(' | ', $filterDesc);

        $locale = app()->getLocale();
        if ($locale === 'ar') {
            $baseName = 'بيانات_حجوزات_السبا';
        } elseif ($locale === 'en') {
            $baseName = 'Reservation_Bookings_Data';
        } else {
            $baseName = 'Data_Reservasi_Booking';
        }
        $filename = "{$baseName}_" . now()->format('Ymd_His');

        return $excelService->download(
            filename: $filename,
            title: 'DATA KELOLA RESERVASI & BOOKING',
            subtitle: $subtitle,
            headers: $headers,
            rows: $rows,
            columnTypes: $columnTypes,
            totalColumns: [9],
            currencyCode: 'AED',
            extraMetadata: ['Total Data' => count($rows) . ' Reservasi']
        );
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => ['required', 'in:confirmed,in_progress,completed,cancelled']]);

        $old = $booking->status;
        $new = $request->status;

        if ($old === $new) {
            return back();
        }

        // 0. Status yang sudah selesai (completed) TIDAK BOLEH dikembalikan ke confirmed atau in_progress untuk mencegah perulangan sesi spa
        if ($old === 'completed' && in_array($new, ['confirmed', 'in_progress'])) {
            return back()->with('error', __('Booking yang sudah selesai (Completed) tidak dapat diubah kembali menjadi Confirmed atau In Progress untuk mencegah perulangan sesi treatment.'));
        }

        // Pembatasan Waktu: jika jadwal booking masih di masa depan, aksi tidak dapat diubah kecuali dibatalkan (cancel)
        if ($new !== 'cancelled' && $booking->booking_date && $booking->booking_time) {
            try {
                $scheduledAt = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time);
                if (now()->lt($scheduledAt)) {
                    return back()->with('error', __('Booking dijadwalkan pada :date jam :time. Status tidak dapat diubah kecuali dibatalkan (Cancel) sebelum jam reservasi tiba.', [
                        'date' => $booking->booking_date->format('d M Y'),
                        'time' => $booking->booking_time,
                    ]));
                }
            } catch (\Exception $e) {}
        }

        // 1. Pesanan baru (confirmed) tidak boleh langsung completed tanpa in_progress
        if ($old === 'confirmed' && $new === 'completed') {
            return back()->with('error', __('Pesanan baru harus diubah ke "In Progress" terlebih dahulu sebelum dapat diselesaikan.'));
        }

        // 2. Jika hendak diubah ke completed, pastikan tidak ada sesi aktif yang masih memiliki sisa waktu
        if ($new === 'completed') {
            $activeSession = ActiveSession::where('booking_id', $booking->id)
                ->whereIn('status', ['running', 'paused'])
                ->first();

            if ($activeSession) {
                $rem = $activeSession->syncRemaining();
                if ($rem > 0) {
                    $mins = floor($rem / 60);
                    $secs = $rem % 60;
                    $timeStr = ($mins > 0 ? ("{$mins} " . __('menit') . ' ') : '') . "{$secs} " . __('detik');
                    return back()->with('error', __('Status tidak dapat diubah ke Completed karena masih ada sisa waktu (:time) di Sesi Aktif & Timer.', ['time' => $timeStr]));
                }
            }
        }

        // Clear the is_new flag when admin acts on the booking
        $booking->update(['status' => $new, 'is_new' => false]);

        // Start active session when in_progress
        if ($new === 'in_progress' && $old !== 'in_progress') {
            ActiveSession::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'customer_name'          => $booking->customer_name,
                    'service_name'           => $booking->service_name,
                    'therapist_name'         => $booking->therapist_name ?? 'Therapist',
                    'started_at'             => now(),
                    'last_synced_at'         => now(),
                    'total_duration_minutes' => $booking->duration,
                    'remaining_seconds'      => $booking->duration * 60,
                    'status'                 => 'running',
                    'is_paused'              => false,
                    'ended_at'               => null,
                ]
            );

            if ($booking->therapist_id) {
                Therapist::where('id', $booking->therapist_id)->update(['status' => 'serving']);
            }
        }

        // End active session when completed/cancelled
        if (in_array($new, ['completed', 'cancelled'])) {
            ActiveSession::where('booking_id', $booking->id)
                ->whereIn('status', ['running', 'paused'])
                ->update([
                    'status'            => 'ended',
                    'ended_at'          => now(),
                    'remaining_seconds' => 0,
                    'last_synced_at'    => now(),
                ]);

            if ($booking->therapist_id) {
                Therapist::where('id', $booking->therapist_id)
                    ->where('status', 'serving')
                    ->update(['status' => 'ready']);
            }
        }

        // If returned to confirmed from in_progress
        if ($new === 'confirmed' && $old === 'in_progress') {
            ActiveSession::where('booking_id', $booking->id)
                ->whereIn('status', ['running', 'paused'])
                ->update([
                    'status'         => 'ended',
                    'ended_at'       => now(),
                    'last_synced_at' => now(),
                ]);

            if ($booking->therapist_id) {
                Therapist::where('id', $booking->therapist_id)
                    ->where('status', 'serving')
                    ->update(['status' => 'ready']);
            }
        }

        return back()->with('success', __('Status booking berhasil diperbarui.'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'spa_service_id' => ['required', 'exists:spa_services,id'],
            'therapist_id'   => ['required', 'exists:therapists,id'],
            'booking_date'   => ['required', 'date'],
            'booking_time'   => ['required', 'string'],
            'duration'       => ['required', 'integer'],
            'customer_name'  => ['required', 'string'],
            'customer_phone' => ['required', 'string'],
            'payment_method' => ['required', 'string'],
        ]);

        $service   = SpaService::findOrFail($request->spa_service_id);
        $therapist = Therapist::findOrFail($request->therapist_id);
        $duration  = (int) $request->duration;

        if ($therapist->status !== 'ready') {
            return back()->withInput()->with('error', __('Terapis :name saat ini sedang tidak tersedia (:status). Silakan pilih terapis yang berstatus Ready.', [
                'name' => $therapist->name,
                'status' => __($therapist->status_label),
            ]));
        }

        // Cek bentrok jadwal terapis
        $requestedStart = \Illuminate\Support\Carbon::parse($request->booking_date . ' ' . $request->booking_time);
        $requestedEnd = (clone $requestedStart)->addMinutes($duration);

        $therapistConflict = Booking::where('therapist_id', $therapist->id)
            ->whereDate('booking_date', $request->booking_date)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->get()
            ->first(function ($b) use ($requestedStart, $requestedEnd) {
                $bStart = \Illuminate\Support\Carbon::parse($b->booking_date->format('Y-m-d') . ' ' . $b->booking_time);
                $bEnd = (clone $bStart)->addMinutes((int) $b->duration);
                return $requestedStart->lt($bEnd) && $requestedEnd->gt($bStart);
            });

        if ($therapistConflict) {
            $conflictEnd = \Illuminate\Support\Carbon::parse($therapistConflict->booking_date->format('Y-m-d') . ' ' . $therapistConflict->booking_time)->addMinutes((int) $therapistConflict->duration)->format('H:i');
            return back()->withInput()->with('error', __('Jadwal bentrok! Terapis :name sudah memiliki jadwal booking pada jam :time s/d :endTime. Silakan pilih jam atau terapis lain.', [
                'name' => $therapist->name,
                'time' => $therapistConflict->booking_time,
                'endTime' => $conflictEnd,
            ]));
        }

        // Cek double booking oleh pelanggan yang sama (berdasarkan nomor HP)
        $customerConflict = Booking::where('customer_phone', $request->customer_phone)
            ->whereDate('booking_date', $request->booking_date)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->get()
            ->first(function ($b) use ($requestedStart, $requestedEnd) {
                $bStart = \Illuminate\Support\Carbon::parse($b->booking_date->format('Y-m-d') . ' ' . $b->booking_time);
                $bEnd = (clone $bStart)->addMinutes((int) $b->duration);
                return $requestedStart->lt($bEnd) && $requestedEnd->gt($bStart);
            });

        if ($customerConflict) {
            return back()->withInput()->with('error', __('Pelanggan dengan nomor :phone sudah memiliki booking aktif (:code) pada jam :time. Silakan periksa kembali jadwal.', [
                'phone' => $request->customer_phone,
                'code' => $customerConflict->booking_code,
                'time' => $customerConflict->booking_time,
            ]));
        }

        $price = $service->calculatePriceForDuration($duration);

        Booking::create([
            'booking_code'   => 'SPA-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4)),
            'user_id'        => null,
            'customer_name'  => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'spa_service_id' => $service->id,
            'service_name'   => $service->name,
            'therapist_id'   => $therapist->id,
            'therapist_name' => $therapist->name,
            'booking_date'   => $request->booking_date,
            'booking_time'   => $request->booking_time,
            'duration'       => $duration,
            'price'          => $price,
            'payment_method' => $request->payment_method,
            'status'         => 'confirmed',
        ]);

        return redirect()->route('admin.bookings')->with('success', __('Booking berhasil dibuat.'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings')->with('success', __('Booking berhasil dihapus.'));
    }

    public function checkNewBookings(Request $request)
    {
        $lastId = (int) $request->input('last_id', 0);
        $newBookings = Booking::where('id', '>', $lastId)
            ->where('is_new', true)
            ->latest('id')
            ->get();

        $totalNewCount = Booking::where('is_new', true)->count();
        $latestId = Booking::max('id') ?? $lastId;

        return response()->json([
            'has_new'         => $newBookings->isNotEmpty(),
            'new_count'       => $newBookings->count(),
            'total_new_count' => $totalNewCount,
            'latest_id'       => $latestId,
            'items'           => $newBookings->map(fn($b) => [
                'id'            => $b->id,
                'customer_name' => $b->customer_name,
                'service_name'  => $b->service_name,
                'booking_code'  => $b->booking_code,
                'price'         => number_format($b->price, 2),
                'time'          => $b->created_at->diffForHumans(),
            ]),
        ]);
    }
}
