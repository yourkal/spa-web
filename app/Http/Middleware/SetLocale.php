<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set active locale from session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', config('app.locale', 'id'));

        if (in_array($locale, ['id', 'en', 'ar'], true)) {
            App::setLocale($locale);
            \Illuminate\Support\Carbon::setLocale($locale);

            // Sesuaikan zona waktu dengan bahasa yang dipilih:
            // Bahasa Indonesia -> Asia/Jakarta (WIB, UTC+7)
            // Bahasa Inggris & Arab -> Asia/Dubai (GST, UTC+4)
            $timezone = ($locale === 'id') ? 'Asia/Jakarta' : 'Asia/Dubai';
            date_default_timezone_set($timezone);
            config(['app.timezone' => $timezone]);
        }

        return $next($request);
    }
}
