<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SpaService;
use App\Models\Therapist;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)->with('service', 'therapist')->latest()->get();
        return view('customer.bookings', compact('bookings'));
    }

    public function create(Request $request)
    {
        $services = SpaService::where('is_active', true)->get();
        $therapists = Therapist::with(['bookings' => function ($q) {
            $q->whereIn('status', ['confirmed', 'in_progress'])
              ->whereDate('booking_date', '>=', now()->toDateString())
              ->select('id', 'therapist_id', 'booking_date', 'booking_time', 'duration', 'status');
        }])->get();
        $selectedServiceId = $request->get('service_id');
        $selectedService = $selectedServiceId
            ? SpaService::find($selectedServiceId)
            : $services->first();

        return view('customer.booking', compact('services', 'therapists', 'selectedService'));
    }

    public function store(StoreBookingRequest $request)
    {
        $user = Auth::user();
        $service = SpaService::where('id', $request->spa_service_id)->where('is_active', true)->firstOrFail();
        $therapist = Therapist::findOrFail($request->therapist_id);

        // 1. Terapis harus berstatus ready
        if ($therapist->status !== 'ready') {
            return back()->withInput()->with('error', __('Terapis :name saat ini sedang tidak tersedia (:status). Silakan pilih terapis yang berstatus Ready.', [
                'name' => $therapist->name,
                'status' => $therapist->status_label ?? $therapist->status,
            ]));
        }

        $duration = (int) $request->duration;
        $requestedStart = \Illuminate\Support\Carbon::parse($request->booking_date . ' ' . $request->booking_time);
        $requestedEnd = (clone $requestedStart)->addMinutes($duration);

        // 2. Cegah bentrok jadwal terapis yang sama
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
            return back()->withInput()->with('error', __('Jadwal bentrok! Terapis :name sudah dipesan pada jam :time s/d :endTime. Silakan pilih jam lain atau pilih terapis yang tersedia.', [
                'name' => $therapist->name,
                'time' => $therapistConflict->booking_time,
                'endTime' => $conflictEnd,
            ]));
        }

        // 3. Cegah double booking oleh pelanggan yang sama di waktu yang sama
        $userConflict = Booking::where('user_id', $user->id)
            ->whereDate('booking_date', $request->booking_date)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->get()
            ->first(function ($b) use ($requestedStart, $requestedEnd) {
                $bStart = \Illuminate\Support\Carbon::parse($b->booking_date->format('Y-m-d') . ' ' . $b->booking_time);
                $bEnd = (clone $bStart)->addMinutes((int) $b->duration);
                return $requestedStart->lt($bEnd) && $requestedEnd->gt($bStart);
            });

        if ($userConflict) {
            return back()->withInput()->with('error', __('Anda sudah memiliki reservasi aktif (:code) pada jam :time (:date). Silakan pilih jadwal lain agar tidak bentrok.', [
                'code' => $userConflict->booking_code,
                'time' => $userConflict->booking_time,
                'date' => $userConflict->booking_date->format('d M Y'),
            ]));
        }

        $price = $service->calculatePriceForDuration($duration);

        $booking = Booking::create([
            'booking_code'    => 'SPA-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id'         => $user->id,
            'customer_name'   => $user->name,
            'customer_phone'  => $user->phone,
            'spa_service_id'  => $service->id,
            'service_name'    => $service->name,
            'therapist_id'    => $therapist->id,
            'therapist_name'  => $therapist->name,
            'booking_date'    => $request->booking_date,
            'booking_time'    => $request->booking_time,
            'duration'        => $duration,
            'price'           => $price,
            'payment_method'  => $request->payment_method,
            'status'          => 'confirmed',
            'is_new'          => true,
        ]);

        return redirect()->route('customer.booking.show', $booking)
            ->with('success', __('✅ Booking berhasil dibuat!'));
    }

    public function show(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);
        return view('customer.booking-detail', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);
        abort_unless(in_array($booking->status, ['confirmed']), 403, 'Booking tidak dapat dibatalkan.');

        $booking->update(['status' => 'cancelled']);

        \App\Models\ActiveSession::where('booking_id', $booking->id)
            ->whereIn('status', ['running', 'paused'])
            ->update([
                'status'            => 'ended',
                'ended_at'          => now(),
                'remaining_seconds' => 0,
                'last_synced_at'    => now(),
            ]);

        if ($booking->therapist_id) {
            \App\Models\Therapist::where('id', $booking->therapist_id)
                ->where('status', 'serving')
                ->update(['status' => 'ready']);
        }

        return redirect()->route('customer.bookings')
            ->with('success', __('Booking berhasil dibatalkan.'));
    }
}
