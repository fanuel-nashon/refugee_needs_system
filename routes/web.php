<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('/', 'auth.login')->name('login.view');

// route to the login controller
// Route::post('/login', [LoginController::class, 'login'])->name('login');