<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Hotel\HotelDashboardController;
use App\Http\Controllers\Viajero\ViajeroDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('home-guest');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isHotel()) {
            return redirect()->route('hotel.dashboard');
        }
        return redirect()->route('viajero.dashboard');
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('hotel')->name('hotel.')->group(function () {
        Route::get('dashboard', [HotelDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('viajero')->name('viajero.')->group(function () {
        Route::get('dashboard', [ViajeroDashboardController::class, 'index'])->name('dashboard');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
