<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NeedController;
use App\Http\Controllers\RefugeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

// ── Refugee auth (phone + password) ─────────────────────────────────────────
Route::redirect('/', '/login');
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// ── Refugee registration ─────────────────────────────────────────────────────
Route::get('/register', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
Route::get('/register/countries', [RegistrationController::class, 'countries'])->name('register.countries');
Route::post('/register/verify-otp', [RegistrationController::class, 'verifyOtp'])->name('registration-otp');
Route::post('/register/resend-otp', [RegistrationController::class, 'resendOtp'])->name('register.resend-otp');

// ── Refugee protected routes ─────────────────────────────────────────────────
Route::middleware(['refugee.auth'])->group(function () {
    Route::get('/refugee/home', [RefugeeController::class, 'home'])->name('refugee.home');
    Route::get('/refugee/needs/create', [RefugeeController::class, 'createNeed'])->name('refugee.needs.create');
    Route::post('/refugee/needs', [RefugeeController::class, 'storeNeed'])->name('refugee.needs.store');
    Route::post('/refugee/logout', [RefugeeController::class, 'logout'])->name('refugee.logout');
});

// ── Staff auth (email + password) ────────────────────────────────────────────
Route::get('/staff/login', [AdminAuthController::class, 'showLogin'])->name('staff.login');
Route::post('/staff/login', [AdminAuthController::class, 'login'])->name('staff.login.post');
Route::post('/staff/logout', [AdminAuthController::class, 'logout'])->name('staff.logout');

// ── Staff protected routes ───────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Needs — accessible by admin and aid_worker
    Route::middleware(['role:admin|aid_worker'])->group(function () {
        Route::resource('needs', NeedController::class);
    });

    // Reports — accessible by admin and aid_worker
    Route::middleware(['role:admin|aid_worker'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    });

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    });
});
