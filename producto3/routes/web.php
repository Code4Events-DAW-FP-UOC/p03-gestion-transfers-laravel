<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ReservaController as AdminReservaController;
use App\Http\Controllers\Hotel\HotelDashboardController;
use App\Http\Controllers\Hotel\ReservaController;
use App\Http\Controllers\Hotel\DatosController;
use App\Http\Controllers\Viajero\ViajeroDashboardController;
use App\Http\Controllers\Viajero\ReservaController as ViajeroReservaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\ZonaController;
use App\Http\Controllers\Admin\VehiculoController;
use App\Http\Controllers\Admin\PrecioController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\EstadisticasController;
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
        Route::get('/reservas/{reserva}/edit', [AdminReservaController::class, 'edit'])->name('reservas.edit');
        Route::put('/reservas/{reserva}', [AdminReservaController::class, 'update'])->name('reservas.update');
        Route::get('/reservas/{reserva}/create', [AdminReservaController::class, 'create'])->name('reservas.create');
        Route::put('/reservas/{store}', [AdminReservaController::class, 'store'])->name('reservas.store');
        Route::delete('/reservas/{reserva}', [AdminReservaController::class, 'destroy'])->name('reservas.destroy');
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::resource('zonas', ZonaController::class)->except(['index', 'show']);
        Route::resource('vehiculos', VehiculoController::class)->except(['index', 'show']);
        Route::resource('precios', PrecioController::class)->except(['index', 'show']);
        Route::resource('usuarios', UserController::class);
    });

    Route::prefix('hotel')->name('hotel.')->group(function () {
        Route::get('dashboard', [HotelDashboardController::class, 'index'])->name('dashboard');
        Route::resource('reservas', ReservaController::class);
        Route::resource('datos', DatosController::class);
    });

    Route::prefix('viajero')->name('viajero.')->group(function () {
        Route::get('dashboard', [ViajeroDashboardController::class, 'index'])->name('dashboard');
        // CRUD Viajero
        Route::resource('reservas', ViajeroReservaController::class)->only(['index', 'create','store','show','edit','update','destroy']);

    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/reservas/por-zona', [EstadisticasController::class, 'reservasPorZona']);

});

require __DIR__.'/auth.php';
