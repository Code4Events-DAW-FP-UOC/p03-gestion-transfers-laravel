<?php

namespace App\Http\Controllers\Viajero;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class ViajeroDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $viajero = $user->viajero;

        if (! $user->isViajero()) {
            abort(403);
        }

        $totalReservas      = Reserva::where('id_viajero', $viajero->id_viajero)->count();
        $reservasPendientes = Reserva::where('id_viajero', $viajero->id_viajero)->where('estado', 'pendiente')->count();
        $reservasRealizadas = Reserva::where('id_viajero', $viajero->id_viajero)->where('estado', 'realizada')->count();
        $reservasCanceladas = Reserva::where('id_viajero', $viajero->id_viajero)->where('estado', 'cancelada')->count();

        return view('viajero.dashboard', compact('viajero','totalReservas','reservasPendientes','reservasRealizadas', 'reservasCanceladas',));
    }
}
