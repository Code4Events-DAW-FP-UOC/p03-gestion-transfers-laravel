<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        // --- Reservas por estado ---
        $totalReservas = Reserva::count();

        $reservasPorEstado = Reserva::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $pendientes  = $reservasPorEstado['pendiente']  ?? 0;
        $confirmadas = $reservasPorEstado['confirmada'] ?? 0;
        $realizadas  = $reservasPorEstado['realizada']  ?? 0;
        $canceladas  = $reservasPorEstado['cancelada']  ?? 0;

        // --- Hoteles / vehículos / viajeros activos vs totales ---
        $hotelesTotales  = Hotel::count();
        $hotelesActivos  = Hotel::where('activo', true)->count();

        $vehiculosTotales = Vehiculo::count();
        $vehiculosActivos = Vehiculo::where('activo', true)->count();

        $viajerosTotales  = User::where('rol', 'viajero')->count();
        $viajerosActivos  = User::where('rol', 'viajero')
                                ->where('activo', true)
                                ->count();

        // --- Próximas reservas (para la tabla) ---
        $hoy = now()->toDateString();

        $proximasReservas = Reserva::with(['hotelDestino', 'viajero', 'tipoReserva', 'vehiculo'])
            ->where(function ($q) use ($hoy) {
                $q->where('fecha_entrada', '>=', $hoy)
                  ->orWhere('fecha_vuelo_salida', '>=', $hoy);
            })
            ->orderByRaw('COALESCE(fecha_entrada, fecha_vuelo_salida) ASC')
            ->limit(10)
            ->get();

        // --- Eventos para el calendario (similar a P2) ---
        $startDate = now()->subMonth()->toDateString();
        $endDate   = now()->addMonths(3)->toDateString();

        $reservasCalendario = Reserva::with(['hotelDestino', 'tipoReserva'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('fecha_entrada', [$startDate, $endDate])
                  ->orWhereBetween('fecha_vuelo_salida', [$startDate, $endDate]);
            })
            ->get();

        $calendarEvents = [];

        foreach ($reservasCalendario as $reserva) {
            // Evento para la IDA
            if ($reserva->fecha_entrada) {
                $calendarEvents[] = [
                    'id'    => 'R' . $reserva->id_reserva . '-ida',
                    'title' => $reserva->localizador . ' · Ida',
                    'start' => $reserva->fecha_entrada->format('Y-m-d'),
                    'extendedProps' => [
                        'estado' => $reserva->estado,
                        'hotel'  => optional($reserva->hotelDestino)->nombre,
                        'tipo'   => optional($reserva->tipoReserva)->descripcion,
                    ],
                ];
            }

            // Evento para la VUELTA
            if ($reserva->fecha_vuelo_salida) {
                $calendarEvents[] = [
                    'id'    => 'R' . $reserva->id_reserva . '-vta',
                    'title' => $reserva->localizador . ' · Vuelta',
                    'start' => $reserva->fecha_vuelo_salida->format('Y-m-d'),
                    'extendedProps' => [
                        'estado' => $reserva->estado,
                        'hotel'  => optional($reserva->hotelDestino)->nombre,
                        'tipo'   => optional($reserva->tipoReserva)->descripcion,
                    ],
                ];
            }
        }

        return view('admin.dashboard', compact(
            'totalReservas',
            'pendientes',
            'confirmadas',
            'realizadas',
            'canceladas',
            'hotelesActivos',
            'hotelesTotales',
            'vehiculosActivos',
            'vehiculosTotales',
            'viajerosActivos',
            'viajerosTotales',
            'proximasReservas',
            'calendarEvents'
        ));
        /* $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        // --- Estadísticas globales de reservas ---
        $totalReservas = Reserva::count();

        $reservasPorEstado = Reserva::select('estado', DB::raw('COUNT(*) as total'))->groupBy('estado')->pluck('total', 'estado');

        $pendientes = $reservasPorEstado['pendiente'] ?? 0;
        $confirmadas = $reservasPorEstado['confirmada'] ?? 0;
        $realizadas = $reservasPorEstado['realizada'] ??0;
        $canceladas = $reservasPorEstado['cancelada'] ??0;

        // --- Otros contadores útilies ---
        $hotelesActivos = Hotel::activos()->count();
        $vehiculosTotales = Vehiculo::count();
        $viajerosTotales = User::where('rol', 'viajero')->count();
        $hotelesUsuarios = User::where('rol', 'hotel')->count();

        // --- Próximas reservas (próximos días) ---
        $hoy = now()->toDateString();

        $proximasReservas = Reserva::with(['hotelDestino', 'viajero', 'tipoReserva'])
            ->where(function ($q) use ($hoy) {
                $q->where('fecha_entrada', '>=', $hoy);
            })
            ->orderByRaw('COALESCE(fecha_entrada, fecha_vuelo_salida) ASC')
            ->limit(10)
            ->get();


        return view('admin.dashboard', compact(
            'totalReservas',
            'pendientes',
            'confirmadas',
            'realizadas',
            'canceladas',
            'hotelesActivos',
            'vehiculosTotales',
            'viajerosTotales',
            'hotelesUsuarios',
            'proximasReservas',
        )); */
    }
}
