<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\SpaService;
use App\Models\Therapist;
use App\Models\User;
use App\Models\ActiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalRevenue    = Booking::where('status', 'completed')->sum('price');
        $todayBookings   = Booking::whereDate('booking_date', $today)->count();
        $activeClients   = Booking::whereIn('status', ['confirmed', 'in_progress'])->count();
        $totalServices   = SpaService::where('is_active', true)->count();
        $totalTherapists = Therapist::count();
        $totalCustomers  = User::where('role', 'customer')->count();

        $totalExpense = (float) Expense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        $netProfit = $totalRevenue - (float) Expense::sum('amount');

        $recentBookings = Booking::with('user', 'service', 'therapist', 'activeSession')
            ->latest()
            ->limit(8)
            ->get();

        $activeSessions = ActiveSession::with('booking.user', 'booking.service', 'booking.therapist')
            ->where('status', 'running')
            ->get();

        foreach ($activeSessions as $ses) {
            $ses->syncRemaining();
        }

        $topServices = SpaService::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();

        // ── Real Revenue Chart Data From Database ─────────────────────────
        // 1. Today (time slots)
        $timeSlots = ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'];
        $todaySlots = collect($timeSlots)->map(function ($slot, $idx) use ($timeSlots) {
            $nextSlot = $timeSlots[$idx + 1] ?? '23:59';
            return (float) Booking::where('status', 'completed')
                ->whereDate('booking_date', Carbon::today())
                ->where('booking_time', '>=', $slot)
                ->where('booking_time', '<', $nextSlot)
                ->sum('price');
        });

        // 2. This Week (Last 7 Days)
        $weekCollection = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            $rev = (float) Booking::where('status', 'completed')
                ->whereDate('booking_date', $date)
                ->sum('price');
            return [
                'label'   => $date->isoFormat('ddd, D MMM'),
                'revenue' => $rev,
            ];
        });

        // 3. This Month (Weeks)
        $monthStart = Carbon::now()->startOfMonth();
        $monthWeeks = collect([
            ['label' => __('Minggu 1'), 'start' => $monthStart->copy()->day(1),  'end' => $monthStart->copy()->day(7)->endOfDay()],
            ['label' => __('Minggu 2'), 'start' => $monthStart->copy()->day(8),  'end' => $monthStart->copy()->day(14)->endOfDay()],
            ['label' => __('Minggu 3'), 'start' => $monthStart->copy()->day(15), 'end' => $monthStart->copy()->day(21)->endOfDay()],
            ['label' => __('Minggu 4'), 'start' => $monthStart->copy()->day(22), 'end' => Carbon::now()->endOfMonth()],
        ])->map(function ($w) {
            $rev = (float) Booking::where('status', 'completed')
                ->whereBetween('booking_date', [$w['start']->toDateString(), $w['end']->toDateString()])
                ->sum('price');
            return [
                'label'   => $w['label'],
                'revenue' => $rev,
            ];
        });

        $chartData = [
            'today' => [
                'labels' => $timeSlots,
                'data'   => $todaySlots->toArray(),
            ],
            'week' => [
                'labels' => $weekCollection->pluck('label')->toArray(),
                'data'   => $weekCollection->pluck('revenue')->toArray(),
            ],
            'month' => [
                'labels' => $monthWeeks->pluck('label')->toArray(),
                'data'   => $monthWeeks->pluck('revenue')->toArray(),
            ],
            'services' => [
                'labels' => $topServices->pluck('name')->toArray(),
                'data'   => $topServices->pluck('bookings_count')->toArray(),
            ],
        ];

        return view('admin.dashboard', compact(
            'totalRevenue', 'todayBookings', 'activeClients', 'totalServices',
            'totalTherapists', 'totalCustomers', 'recentBookings', 'activeSessions',
            'topServices', 'totalExpense', 'netProfit', 'chartData'
        ));
    }

    public function enterCustomerPreview(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403, 'Hanya Administrator yang berwenang menggunakan fitur preview ini.');

        session([
            'admin_preview_customer' => true,
            'admin_preview_origin'   => url()->previous() ?: route('admin.dashboard'),
        ]);

        return redirect()->route('customer.dashboard');
    }

    public function exitCustomerPreview(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403, 'Akses tidak diizinkan.');

        $origin = session()->pull('admin_preview_origin', route('admin.dashboard'));
        session()->forget('admin_preview_customer');

        return redirect($origin)->with('success', __('Mode preview customer selesai. Anda telah kembali ke panel Administrator.'));
    }
}
