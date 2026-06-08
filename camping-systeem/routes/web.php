<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', LocaleController::class)->name('locale.update');

Route::middleware(SetLocale::class)->group(function (): void {
    Route::get('/login', [HomeController::class, 'index'])->name('login');
});
Route::post('/login', [LoginController::class, 'store'])->name('login');


Route::middleware(SetLocale::class)->group(function (): void {
    // Main booking route (English/neutral)
    Route::get('/booking', [BookingController::class, 'create'])->name('bookings.create');

    // Dutch-friendly URL for "Boek nu"
    Route::get('/', [BookingController::class, 'create']);

    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
});

Route::middleware(SetLocale::class)->group(function (): void {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'create'])->name('dashboard');

});


