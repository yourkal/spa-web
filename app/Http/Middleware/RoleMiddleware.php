<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            // Redirect admin or bos to admin login, others to customer login
            $needsAdminLogin = in_array($role, ['admin', 'bos']) || str_contains($role, 'admin') || str_contains($role, 'bos');
            return redirect()->route($needsAdminLogin ? 'admin.login' : 'login');
        }

        // Support pipe-separated roles: e.g. role:admin|bos
        $allowedRoles = explode('|', $role);

        // Controlled Admin Preview Mode:
        // Allows authenticated Admin with active server session flag to preview customer views securely
        if (in_array('customer', $allowedRoles, true) && Auth::user()->isAdmin() && session('admin_preview_customer') === true) {
            return $next($request);
        }

        if (!in_array(Auth::user()->role, $allowedRoles)) {
            // Graceful redirection for Bos accessing admin read-only pages
            if (Auth::user()->role === 'bos' && in_array('admin', $allowedRoles, true)) {
                if ($request->routeIs('admin.bookings')) {
                    return redirect()->route('bos.bookings', $request->query());
                }
                if ($request->routeIs('admin.bookings.export.excel')) {
                    return redirect()->route('bos.bookings.export.excel', $request->query());
                }
                if ($request->routeIs('admin.sessions')) {
                    return redirect()->route('bos.sessions', $request->query());
                }
                if ($request->routeIs('admin.reports')) {
                    return redirect()->route('bos.reports', $request->query());
                }
                if ($request->routeIs('admin.reports.export.excel')) {
                    return redirect()->route('bos.reports.export.excel', $request->query());
                }
                if ($request->routeIs('admin.reports.export.pdf')) {
                    return redirect()->route('bos.reports.export.pdf', $request->query());
                }
                if ($request->routeIs('admin.dashboard')) {
                    return redirect()->route('bos.dashboard');
                }
                if ($request->routeIs('admin.bookings.check-new')) {
                    return $next($request);
                }
            }

            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}
