<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Hotel;

class AdminDashboardController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        // Estadísticas globales
        $stats = [
            'total_reservas'      => Reserva::count(),
            'reservas_pendientes' => Reserva::where('estado', 'pendiente')->count(),
            'reservas_realizadas' => Reserva::where('estado', 'realizada')->count(),
            'reservas_canceladas' => Reserva::where('estado', 'cancelada')->count(),
            'reservas_hoy'        => Reserva::whereDate('fecha_entrada', now())->count(),
            'salidas_hoy'        => Reserva::whereDate('fecha_vuelo_salida', now())->count(),
            'total_usuarios'      => User::count(),
            'total_hoteles'       => Hotel::count(),
        ];
        $stats['total_hoy'] = $stats['reservas_hoy'] + $stats['salidas_hoy'];

        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();

        $hotelesComisiones = \App\Models\Hotel::whereHas('reservas', function($q) use ($inicioMes, $finMes) {
            $q->whereBetween('fecha_entrada', [$inicioMes, $finMes])
            ->where('estado', 'confirmada'); // Solo confirmadas
        })
        ->with(['reservas' => function($q) use ($inicioMes, $finMes) {
            $q->whereBetween('fecha_entrada', [$inicioMes, $finMes])
              ->where('estado', 'confirmada')
              ->with('viajero');
        }])
        ->get();

        // Calculamos el total global que el admin debe pagar a todos los hoteles
        $totalAPagarGlobal = $hotelesComisiones->sum(function($hotel) {
            return $hotel->reservas->sum('ganancia_hotel');
        });
        

        return view('admin.dashboard', compact('stats', 'hotelesComisiones', 'totalAPagarGlobal'));
    }
}
