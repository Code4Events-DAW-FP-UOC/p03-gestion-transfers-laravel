<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DatosController extends Controller
{
    public function index()
    {
        $hotel = Auth::user()->hotel;

        if (!$hotel) {
            return redirect()->back()->with('error', 'No se encontró información del hotel.');
        }

        $hotel->load(['zona', 'reservas.precio', 'reservas.viajero']);

        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $reservasDelMes = $hotel->reservas()
            ->whereBetween('fecha_entrada', [$inicioMes, $finMes])
            ->with('viajero')
            ->where('estado', 'confirmada')
            ->get();

        $totalGanadoMes = $reservasDelMes->sum('ganancia_hotel');

        return view('hotel.datos.index', compact('hotel', 'reservasDelMes', 'totalGanadoMes'));
    }
}