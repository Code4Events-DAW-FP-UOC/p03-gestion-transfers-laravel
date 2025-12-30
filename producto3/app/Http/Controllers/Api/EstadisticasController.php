<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class EstadisticasController extends Controller
{
    public function reservasPorZona(): JsonResponse
    {
        $totalReservas = DB::table('p3_transfer_reservas')->count();

        if ($totalReservas === 0) {
            return response()->json(['mensaje' => 'No hay reservas registradas'], 200);
        }

        $datos = DB::table('p3_transfer_zonas as z')
            ->join('p3_transfer_hoteles as h', 'z.id_zona', '=', 'h.id_zona')
            ->join('p3_transfer_reservas as r', 'h.id_hotel', '=', 'r.id_hotel')
            ->select(
                'z.codigo as zona',
                DB::raw('count(r.id_reserva) as num_traslados')
            )
            ->groupBy('z.id_zona', 'z.codigo')
            ->get();

        $resultado = $datos->map(function ($item) use ($totalReservas) {
            return [
                'zona'          => $item->zona,
                'num_traslados' => $item->num_traslados,
                'porcentaje'    => round(($item->num_traslados / $totalReservas) * 100, 2) . '%'
            ];
        });

        return response()->json($resultado);
    }
}