<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HotelDashboardController extends Controller
{
    public function index()
    {
       $hotel = auth()->user()->hotel;

        if (!$hotel) {
            return redirect()->back()->with('error', 'Sin acceso al hotel.');
        }

        // 1. Estadísticas Generales (Todas las reservas del hotel)
        $totalReservas = $hotel->reservas()->count();

        // Ajusta los IDs (1, 2, 3) según tu tabla 'estados'
        $reservasPendientes = $hotel->reservas()->where('estado', 'pendiente')->count();
        $reservasRealizadas = $hotel->reservas()->where('estado', 'realizada')->count();
        $reservasCanceladas = $hotel->reservas()->where('estado', 'cancelada')->count();

        // 2. Cálculo de Comisiones del Mes Actual
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();

        $reservasMes = $hotel->reservas()
            ->whereBetween('fecha_entrada', [$inicioMes, $finMes])
            ->where('estado', 'confirmada')
            ->get();

        $totalComisionesMes = $reservasMes->sum('ganancia_hotel');

        return view('hotel.dashboard', compact(
            'totalReservas', 
            'reservasPendientes', 
            'reservasRealizadas', 
            'reservasCanceladas',
            'totalComisionesMes'
        ));
    }
}
