<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminComisionesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        // Filtros básicos
        $year    = (int) ($request->input('year') ?? now()->year);
        $month   = $request->input('month');      // 1–12 o null para todo el año
        $hotelId = $request->input('hotel_id');   // opcional

        // Query base: solo reservas REALIZADAS
        // y usamos la fecha de servicio (fecha_entrada)
        $query = Reserva::query()
            ->selectRaw('
                id_hotel_destino    as hotel_id,
                YEAR(fecha_entrada) as year,
                MONTH(fecha_entrada) as month,
                COUNT(*)            as total_reservas,
                SUM(comision_importe) as total_comision
            ')
            ->where('estado', 'realizada')
            ->whereNotNull('fecha_entrada')
            ->whereYear('fecha_entrada', $year);

        if (!empty($month)) {
            $query->whereMonth('fecha_entrada', (int) $month);
        }

        if (!empty($hotelId)) {
            $query->where('id_hotel_destino', (int) $hotelId);
        }

        $query->groupBy('hotel_id', 'year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('hotel_id');

        $resumen = $query->get();

        // Cargar nombres de hoteles
        $hoteles = Hotel::whereIn('id_hotel', $resumen->pluck('hotel_id')->unique())
            ->get()
            ->keyBy('id_hotel');

        // Para el filtro de hotel en el formulario
        $hotelesFiltro = Hotel::orderBy('nombre')->get();

        // Años posibles (según tus reservas seed)
        $yearsDisponibles = Reserva::selectRaw('DISTINCT YEAR(fecha_entrada) as year')
            ->whereNotNull('fecha_entrada')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.comisiones.index', compact(
            'resumen',
            'hoteles',
            'hotelesFiltro',
            'year',
            'month',
            'hotelId',
            'yearsDisponibles'
        ));
    }
}
