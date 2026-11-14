<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActiveSession;
use Illuminate\Http\Request;

class AdminSessionController extends Controller
{
    public function index(Request $request)
    {
        $targetBookingCode = $request->query('booking_code');
        $targetBookingInfo = null;

        if ($targetBookingCode) {
            $booking = \App\Models\Booking::with('activeSession', 'service', 'therapist')
                ->where('booking_code', $targetBookingCode)
                ->first();

            if ($booking) {
                if ($booking->status === 'confirmed') {
                    $targetBookingInfo = [
                        'status'     => 'not_started',
                        'code'       => $booking->booking_code,
                        'booking_id' => $booking->id,
                        'customer'   => $booking->customer_name,
                        'service'    => $booking->service_name,
                        'therapist'  => $booking->therapist_name,
                        'duration'   => $booking->duration,
                        'time'       => $booking->booking_time,
                        'date'       => $booking->booking_date ? $booking->booking_date->format('d M Y') : '',
                        'message'    => __('Sesi untuk booking :code (:customer) belum dimulai. Klik tombol "Mulai Sesi Sekarang" saat customer sudah masuk ke ruangan perawatan.', [
                            'code'     => $booking->booking_code,
                            'customer' => $booking->customer_name,
                        ]),
                    ];
                } elseif ($booking->status === 'completed') {
                    $targetBookingInfo = [
                        'status'  => 'completed',
                        'code'    => $booking->booking_code,
                        'message' => __('Sesi perawatan untuk booking :code (:customer) telah selesai.', [
                            'code'     => $booking->booking_code,
                            'customer' => $booking->customer_name,
                        ]),
                    ];
                } elseif ($booking->status === 'cancelled') {
                    $targetBookingInfo = [
                        'status'  => 'cancelled',
                        'code'    => $booking->booking_code,
                        'message' => __('Booking :code telah dibatalkan.', [
                            'code' => $booking->booking_code,
                        ]),
                    ];
                } elseif ($booking->activeSession) {
                    $targetBookingInfo = [
                        'status'     => 'running',
                        'code'       => $booking->booking_code,
                        'session_id' => $booking->activeSession->id,
                    ];
                }
            } else {
                $targetBookingInfo = [
                    'status'  => 'not_found',
                    'code'    => $targetBookingCode,
                    'message' => __('Kode booking :code tidak ditemukan.', ['code' => $targetBookingCode]),
                ];
            }
        }

        $activeSessions = ActiveSession::with('booking.user', 'booking.service', 'booking.therapist')
            ->where('status', 'running')
            ->latest()
            ->get();

        // Synchronize real-time elapsed seconds based on server DB timestamps
        foreach ($activeSessions as $ses) {
            $ses->syncRemaining();
        }

        $endedSessions = ActiveSession::with('booking')
            ->where('status', 'ended')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.sessions', compact('activeSessions', 'endedSessions', 'targetBookingCode', 'targetBookingInfo'));
    }

    public function togglePause(ActiveSession $activeSession)
    {
        if ($booking = $activeSession->booking) {
            if ($booking->booking_date && $booking->booking_time) {
                try {
                    $scheduledAt = \Illuminate\Support\Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->booking_time);
                    if (now()->lt($scheduledAt)) {
                        return back()->with('error', __('Sesi belum dapat dimulai atau dijalankan sebelum jam kedatangan (:time).', ['time' => $booking->booking_time]));
                    }
                } catch (\Exception $e) {}
            }
        }

        if (!$activeSession->is_paused) {
            // About to pause: sync elapsed time first
            $activeSession->syncRemaining();
            $activeSession->update([
                'is_paused'      => true,
                'last_synced_at' => now(),
            ]);
            $msg = __('Sesi di-pause.');
        } else {
            // Resume: set is_paused false and reset reference timestamp
            $activeSession->update([
                'is_paused'      => false,
                'last_synced_at' => now(),
            ]);
            $msg = __('Sesi dilanjutkan.');
        }

        return back()->with('success', $msg);
    }

    public function extendTime(Request $request, ActiveSession $activeSession)
    {
        $minutes = (int) $request->input('minutes', 15);
        if ($minutes <= 0) {
            $minutes = 15;
        }

        // Sync first so remaining seconds is accurate before adding time
        $activeSession->syncRemaining();

        $activeSession->increment('total_duration_minutes', $minutes);
        $activeSession->increment('remaining_seconds', $minutes * 60);
        $activeSession->update(['last_synced_at' => now()]);

        $addedPrice = 0;
        if ($booking = $activeSession->booking) {
            $booking->loadMissing('service');
            $service = $booking->service;

            if ($request->has('custom_price') && $request->input('custom_price') !== null && $request->input('custom_price') !== '') {
                // Manual customized price input from cashier/admin
                $addedPrice = max(0, (float) $request->input('custom_price'));
            } elseif ($service && $service->duration > 0 && $service->price > 0) {
                $hourlyRate = round(((float) $service->price / (float) $service->duration) * 60);
                if ($minutes === 60) {
                    $addedPrice = $hourlyRate;
                } elseif ($minutes === 30) {
                    $addedPrice = round($hourlyRate / 2);
                } elseif ($minutes === 15) {
                    $addedPrice = round($hourlyRate / 4);
                } else {
                    $pricePerMinute = (float) $service->price / (float) $service->duration;
                    $addedPrice = (float) round($pricePerMinute * $minutes);
                }
            } elseif ($booking->duration > 0 && $booking->price > 0) {
                $pricePerMinute = (float) $booking->price / (float) $booking->duration;
                $addedPrice = (float) round($pricePerMinute * $minutes);
            }

            $booking->increment('duration', $minutes);
            if ($addedPrice > 0) {
                $booking->increment('price', $addedPrice);
            }
        }

        $priceMsg = $addedPrice > 0
            ? " (" . __('Biaya tambahan: :price', ['price' => 'AED ' . number_format($addedPrice, 2)]) . ")"
            : "";

        return back()->with('success', __('Durasi berhasil ditambah +:minutes menit:priceMsg', ['minutes' => $minutes, 'priceMsg' => $priceMsg]));
    }

    public function finishSession(Request $request, ActiveSession $activeSession)
    {
        $activeSession->update([
            'status'            => 'ended',
            'remaining_seconds' => 0,
            'ended_at'          => now(),
            'last_synced_at'    => now(),
        ]);

        if ($booking = $activeSession->booking) {
            $booking->update(['status' => 'completed']);
            if ($booking->therapist_id) {
                \App\Models\Therapist::where('id', $booking->therapist_id)
                    ->where('status', 'serving')
                    ->update(['status' => 'ready']);
            }
        }

        // Support AJAX (fetch) calls from auto-finish timer — return JSON for XHR
        if ($request->expectsJson() || $request->isXmlHttpRequest() || $request->has('auto_finish')) {
            return response()->json([
                'status'  => 'ok',
                'message' => __('Sesi perawatan telah ditandai selesai.'),
            ]);
        }

        return back()->with('success', __('Sesi perawatan telah ditandai selesai.'));
    }

    public function syncTimer(Request $request, ActiveSession $activeSession)
    {
        // Prevent JS or browser tampering: compute actual remaining time from server database timestamps
        $serverRemaining = $activeSession->syncRemaining();

        return response()->json([
            'status'            => 'ok',
            'remaining_seconds' => (int) $serverRemaining,
            'is_paused'         => (bool) $activeSession->is_paused,
        ]);
    }
}
