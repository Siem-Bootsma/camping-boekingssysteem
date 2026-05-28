<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\LocaleController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', LocaleController::class)->name('locale.update');

Route::middleware(SetLocale::class)->group(function (): void {
    Route::get('/', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
});

Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
