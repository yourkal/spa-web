<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $recentBookings = Booking::where('user_id', $user->id)
                    ->latest('updated_at')
                    ->take(6)
                    ->get();

                $notifications = $recentBookings->map(function ($booking) {
                    $notif = [
                        'id'         => $booking->id,
                        'booking_id' => $booking->id,
                        'code'       => $booking->booking_code,
                        'status'     => $booking->status,
                        'time'       => $booking->updated_at->diffForHumans(),
                        'url'        => route('customer.booking.show', $booking),
                    ];

                    switch ($booking->status) {
                        case 'in_progress':
                            $notif['title'] = __('Perawatan Sedang Berlangsung');
                            $notif['message'] = __('Sesi :service bersama :therapist sedang berjalan.', [
                                'service'   => $booking->service_name,
                                'therapist' => $booking->therapist_name,
                            ]);
                            $notif['badge'] = __('In Progress');
                            $notif['badge_class'] = 'bg-amber-100 text-amber-800 border-amber-200';
                            $notif['icon'] = 'clock';
                            break;
                        case 'completed':
                            $notif['title'] = __('Perawatan Selesai');
                            $notif['message'] = __('Sesi :service telah selesai. Terima kasih!', [
                                'service' => $booking->service_name,
                            ]);
                            $notif['badge'] = __('Completed');
                            $notif['badge_class'] = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                            $notif['icon'] = 'check-circle';
                            break;
                        case 'cancelled':
                            $notif['title'] = __('Booking Dibatalkan');
                            $notif['message'] = __('Reservasi :code telah dibatalkan.', [
                                'code' => $booking->booking_code,
                            ]);
                            $notif['badge'] = __('Cancelled');
                            $notif['badge_class'] = 'bg-rose-100 text-rose-800 border-rose-200';
                            $notif['icon'] = 'x-circle';
                            break;
                        case 'confirmed':
                        default:
                            $notif['title'] = __('Booking Terkonfirmasi');
                            $notif['message'] = __('Reservasi :service dijadwalkan pada :date :time.', [
                                'service' => $booking->service_name,
                                'date'    => $booking->booking_date ? $booking->booking_date->format('d M Y') : '-',
                                'time'    => $booking->booking_time ?? '-',
                            ]);
                            $notif['badge'] = __('Confirmed');
                            $notif['badge_class'] = 'bg-blue-100 text-blue-800 border-blue-200';
                            $notif['icon'] = 'calendar-check';
                            break;
                    }

                    return $notif;
                });

                $unreadCount = $recentBookings->whereIn('status', ['confirmed', 'in_progress'])->count();

                $view->with('userNotifications', $notifications);
                $view->with('unreadNotifCount', $unreadCount);
            } else {
                $view->with('userNotifications', collect());
                $view->with('unreadNotifCount', 0);
            }
        });

        View::composer('layouts.admin', function ($view) {
            $newBookingsCount = Booking::where('created_at', '>=', now()->subHours(24))->count();
            $latestBookingId  = Booking::max('id') ?? 0;
            $adminRecentBookings = Booking::latest()->take(6)->get();

            $view->with('newBookingsCount', $newBookingsCount);
            $view->with('latestBookingId', $latestBookingId);
            $view->with('adminRecentBookings', $adminRecentBookings);
        });
    }
}
