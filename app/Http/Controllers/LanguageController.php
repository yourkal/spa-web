<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch application language between id, en, and ar (Dubai / UAE).
     */
    public function switch(string $locale)
    {
        $validLocales = ['id', 'en', 'ar'];

        if (in_array($locale, $validLocales, true)) {
            Session::put('locale', $locale);
            app()->setLocale($locale);

            $timezone = ($locale === 'id') ? 'Asia/Jakarta' : 'Asia/Dubai';
            Session::put('timezone', $timezone);
            date_default_timezone_set($timezone);
            config(['app.timezone' => $timezone]);
        }

        return back();
    }
}
