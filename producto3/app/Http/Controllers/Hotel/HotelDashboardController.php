<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isHotel(), 403);

        /** @var Hotel|null $hotel */
        $hotel = $user->hotel;
        abort_unless($hotel !== null, 403);

        // --- Métricas generales de reservas del hotel ---
        $baseQuery = Reserva::where('id_hotel_destino', $hotel->id_hotel);

        $totalReservas = (clone $baseQuery)->count();
        $pendientes    = (clone $baseQuery)->where('estado', 'pendiente')->count();
        $confirmadas   = (clone $baseQuery)->where('estado', 'confirmada')->count();
        $realizadas    = (clone $baseQuery)->where('estado', 'realizada')->count();
        $canceladas    = (clone $baseQuery)->where('estado', 'cancelada')->count();

        // --- Resumen mensual de comisiones ---
        // Usamos siempre comision_importe y EXCLUIMOS canceladas
        $tablaReservas = (new Reserva())->getTable();

        $comisionesMensuales = DB::table($tablaReservas)
            ->selectRaw('YEAR(COALESCE(fecha_entrada, fecha_vuelo_salida)) as year')
            ->selectRaw('MONTH(COALESCE(fecha_entrada, fecha_vuelo_salida)) as month')
            ->selectRaw('COUNT(*) as total_reservas')
            ->selectRaw('SUM(CASE WHEN estado = "realizada" THEN comision_importe ELSE 0 END) as comision_realizada')
            ->selectRaw('SUM(CASE WHEN estado = "confirmada" THEN comision_importe ELSE 0 END) as comision_confirmada')
            ->selectRaw('SUM(CASE WHEN estado = "pendiente" THEN comision_importe ELSE 0 END) as comision_pendiente')
            // total = suma de realizada + confirmada + pendiente (canceladas ya están fuera del whereIn)
            ->selectRaw('SUM(comision_importe) as comision_total')
            ->where('id_hotel_destino', $hotel->id_hotel)
            ->whereIn('estado', ['pendiente', 'confirmada', 'realizada'])
            ->whereNotNull('comision_importe')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        // --- Próximas reservas (por si las quieres mostrar en el panel) ---
        $proximasReservas = Reserva::with(['viajero', 'vehiculo', 'tipoReserva'])
            ->where('id_hotel_destino', $hotel->id_hotel)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->orderByRaw('COALESCE(fecha_entrada, fecha_vuelo_salida) ASC')
            ->limit(10)
            ->get();

        return view('hotel.dashboard', compact(
            'hotel',
            'totalReservas',
            'pendientes',
            'confirmadas',
            'realizadas',
            'canceladas',
            'comisionesMensuales',
            'proximasReservas'
        ));
    }
}
