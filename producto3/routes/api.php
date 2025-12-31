<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EstadisticasZonaController;

Route::get('/reservas/zonas', [EstadisticasZonaController::class, 'reservasPorZona'])
    ->name('api.reservas.zonas');