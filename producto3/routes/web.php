<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHotelController;
use App\Http\Controllers\Admin\AdminPrecioController;
use App\Http\Controllers\Admin\AdminReservaController;
use App\Http\Controllers\Admin\AdminTiposReservaController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVehiculoController;
use App\Http\Controllers\Admin\AdminZonaController;
use App\Http\Controllers\Hotel\HotelDashboardController;
use App\Http\Controllers\Hotel\HotelReservaController;
use App\Http\Controllers\Viajero\ViajeroDashboardController;
use App\Http\Controllers\Viajero\ReservaController as ViajeroReservaController;
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
        Route::resource('reservas', AdminReservaController::class);
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('hoteles', AdminHotelController::class)->names('hoteles')->parameters(['hoteles' => 'hotel'])->except(['show']);
        Route::resource('vehiculos', AdminVehiculoController::class)->except(['show']);
        Route::resource('tiposReserva', AdminTiposReservaController::class)->except('show');
        Route::resource('precios', AdminPrecioController::class)->except('show');
        Route::resource('zonas', AdminZonaController::class)->except('show');

        // reset password hotel
        Route::post('hoteles/{hotel}/reset-password', [AdminHotelController::class, 'resetPassword'])->name('hoteles.reset-password');
        // reset password usuario
        Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    });

    Route::prefix('hotel')->name('hotel.')->group(function () {
        Route::get('dashboard', [HotelDashboardController::class, 'index'])->name('dashboard');

        Route::resource('reservas', HotelReservaController::class)->except(['show']); 
    });

    Route::prefix('viajero')->name('viajero.')->group(function () {
        Route::get('dashboard', [ViajeroDashboardController::class, 'index'])->name('dashboard');
        // CRUD Viajero
        Route::resource('reservas', ViajeroReservaController::class)->only(['index', 'create','store','show','edit','update','destroy']);

    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
