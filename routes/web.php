<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
 */

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::get('reset-password', [AuthController::class, 'recoverPassword'])->name('password.reset');
    Route::get('register', [AuthController::class, 'register'])->name('register');

    Route::post('login/authenticate', [AuthController::class, 'authenticate'])->middleware('guest')->name('login.authenticate');
    Route::post('forgot-password', [AuthController::class, 'sendEmailForgot'])->name('password.email');
    Route::post('reset-password', [AuthController::class, 'changePassword'])->name('password.update');
    Route::post('register', [AuthController::class, 'registerAccount'])->name('register.user');
});

Route::middleware(['auth'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
});

// Route::get('verify-email', [HomeController::class, 'index'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verifyAccount'])->middleware(['guest', 'signed'])->name('verification.verify');
Route::post('email/verification-notification', [AuthController::class, 'resendVerifyEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Menu Routes
|--------------------------------------------------------------------------
 */

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('menu', [MenuController::class, 'index'])->name('menu');
});
