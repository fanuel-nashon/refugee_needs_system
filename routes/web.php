<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use Illuminate\Support\Facades\Route;

// Default Landing Page (Login)
Route::view('/', 'auth.login')->name('login.view');

// Registration Flow
Route::get('/register', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
Route::get('/register/countries', [RegistrationController::class, 'countries'])->name('register.countries');

// Login Processing
Route::post('/login', [LoginController::class, 'login'])->name('login');


Route::view('/dashboard','pages.dashboard')->name('dashboard');

Route::post('/register/verify-otp', [RegistrationController::class, 'verifyOtp'])->name('registration-otp');
Route::post('/register/resend-otp', [RegistrationController::class, 'resendOtp'])->name('register.resend-otp');
