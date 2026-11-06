<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Admin\AdminTherapistController;
use App\Http\Controllers\Admin\AdminSessionController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminExpenseController;
use App\Http\Controllers\Admin\AdminBiodataController;
use App\Http\Controllers\Bos\BosDashboardController;
use App\Http\Controllers\Bos\BosOrderController;
use App\Http\Controllers\Bos\BosReportController;
use App\Http\Controllers\LanguageController;

// Language Switcher (Indonesian, English, Arabic/Dubai)
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Public: Landing Page redirects to login
Route::get('/', fn() => redirect()->route('login'))->name('home');

// ─── AUTH ROUTES ────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Admin / Bos Login
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── CUSTOMER ROUTES ────────────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])->prefix('app')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::get('/services/{spaService}', [ServiceController::class, 'show'])->name('service.show');
    Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Profile
    Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
});

// ─── ADMIN ROUTES ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Customer Preview Controls (Admin Only)
    Route::get('/preview-customer', [AdminDashboardController::class, 'enterCustomerPreview'])->name('preview.enter');
    Route::post('/preview-customer/exit', [AdminDashboardController::class, 'exitCustomerPreview'])->name('preview.exit');

    // Bookings
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/export/excel', [AdminBookingController::class, 'exportExcel'])->name('bookings.export.excel');
    Route::get('/bookings/check-new', [AdminBookingController::class, 'checkNewBookings'])->name('bookings.check-new');
    Route::post('/bookings', [AdminBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    // Sessions & Timer Controls
    Route::get('/sessions', [AdminSessionController::class, 'index'])->name('sessions');
    Route::patch('/sessions/{activeSession}/toggle-pause', [AdminSessionController::class, 'togglePause'])->name('sessions.toggle-pause');
    Route::patch('/sessions/{activeSession}/extend', [AdminSessionController::class, 'extendTime'])->name('sessions.extend');
    Route::patch('/sessions/{activeSession}/finish', [AdminSessionController::class, 'finishSession'])->name('sessions.finish');
    Route::post('/sessions/{activeSession}/sync', [AdminSessionController::class, 'syncTimer'])->name('sessions.sync');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
    Route::get('/customers/export/excel', [AdminCustomerController::class, 'exportExcel'])->name('customers.export.excel');
    Route::get('/customers/{user}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Services
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services');
    Route::get('/services/export/excel', [AdminServiceController::class, 'exportExcel'])->name('services.export.excel');
    Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{spaService}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{spaService}', [AdminServiceController::class, 'destroy'])->name('services.destroy');
    Route::patch('/services/{spaService}/toggle', [AdminServiceController::class, 'toggleActive'])->name('services.toggle');

    // Therapists
    Route::get('/therapists', [AdminTherapistController::class, 'index'])->name('therapists');
    Route::get('/therapists/export/excel', [AdminTherapistController::class, 'exportExcel'])->name('therapists.export.excel');
    Route::post('/therapists', [AdminTherapistController::class, 'store'])->name('therapists.store');
    Route::put('/therapists/{therapist}', [AdminTherapistController::class, 'update'])->name('therapists.update');
    Route::delete('/therapists/{therapist}', [AdminTherapistController::class, 'destroy'])->name('therapists.destroy');
    Route::patch('/therapists/{therapist}/toggle', [AdminTherapistController::class, 'toggleActive'])->name('therapists.toggle');
    Route::patch('/therapists/{therapist}/status', [AdminTherapistController::class, 'updateStatus'])->name('therapists.status');

    // Reports & Transactions
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
    Route::get('/reports/export/excel', [AdminReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [AdminReportController::class, 'exportPdf'])->name('reports.export.pdf');

    // Pengeluaran / Expenses (Admin only)
    Route::get('/expenses', [AdminExpenseController::class, 'index'])->name('expenses');
    Route::get('/expenses/export/excel', [AdminExpenseController::class, 'exportExcel'])->name('expenses.export.excel');
    Route::post('/expenses', [AdminExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{expense}', [AdminExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [AdminExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Biodata Admin
    Route::get('/biodata', [AdminBiodataController::class, 'edit'])->name('biodata');
    Route::put('/biodata', [AdminBiodataController::class, 'update'])->name('biodata.update');
});

// ─── BOS ROUTES (Executive View-only) ─────────────────────────────────
Route::middleware(['auth', 'role:bos'])->prefix('bos')->name('bos.')->group(function () {
    Route::get('/dashboard', [BosDashboardController::class, 'index'])->name('dashboard');

    // Riwayat Booking (Read-Only)
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/export/excel', [AdminBookingController::class, 'exportExcel'])->name('bookings.export.excel');
    Route::get('/bookings/check-new', [AdminBookingController::class, 'checkNewBookings'])->name('bookings.check-new');

    // Timer & Sesi Aktif (Read-Only)
    Route::get('/sessions', [AdminSessionController::class, 'index'])->name('sessions');

    // Orders & History (Backward Compatibility)
    Route::get('/orders', [BosOrderController::class, 'orders'])->name('orders');
    Route::get('/orders/export/excel', [BosOrderController::class, 'exportExcel'])->name('orders.export.excel');
    Route::get('/orders/history', [BosOrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/history/export/excel', [BosOrderController::class, 'exportHistoryExcel'])->name('orders.history.export.excel');
    Route::get('/orders/{booking}', [BosOrderController::class, 'show'])->name('orders.show');

    // Financial Reports (Read-Only)
    Route::get('/reports', [BosReportController::class, 'index'])->name('reports');
    Route::get('/reports/export/excel', [BosReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [BosReportController::class, 'exportPdf'])->name('reports.export.pdf');

    // Biodata Bos
    Route::get('/biodata', [AdminBiodataController::class, 'edit'])->name('biodata');
    Route::put('/biodata', [AdminBiodataController::class, 'update'])->name('biodata.update');
});
