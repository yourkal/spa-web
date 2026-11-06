<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\SpaService;
use App\Models\Therapist;
use App\Models\ActiveSession;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_id', $user->id)->latest()->get();

        $activeBooking = $bookings->whereIn('status', ['confirmed', 'in_progress'])->first();
        $activeSession = $activeBooking
            ? ActiveSession::where('booking_id', $activeBooking->id)->where('status', 'running')->first()
            : null;

        if ($activeSession) {
            $activeSession->syncRemaining();
        }

        $monthVisits = $bookings->where('status', 'completed')
            ->filter(fn($b) => $b->booking_date->format('Y-m') === now()->format('Y-m'))
            ->count();

        $favoriteServices = SpaService::withCount('bookings')->orderByDesc('bookings_count')->limit(3)->get();

        return view('customer.dashboard', compact(
            'user', 'bookings', 'activeBooking', 'activeSession', 'monthVisits', 'favoriteServices'
        ));
    }
}
