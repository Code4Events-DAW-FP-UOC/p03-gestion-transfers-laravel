<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reserva;
use App\Models\Zona;
use Illuminate\Http\JsonResponse;

class EstadisticasZonaController extends Controller
{
    /**
     * Devuelve un JSON con información agregada de las reservas realizadas
     * por zona de la isla.
     *
     * Estructura:
     * {
     *   "total_traslados": 120,
     *   "zonas": [
     *     {
     *       "id_zona": 1,
     *       "descripcion": "Zona Norte",
     *       "codigo": "NORTH_ZONE",
     *       "num_traslados": 40,
     *       "porcentaje": 33.33
     *     },
     *     ...
     *   ],
     *   "generated_at": "2025-12-29T12:34:56+01:00"
     * }
     */
    public function reservasPorZona(): JsonResponse
    {
        $reservasTable = (new Reserva)->getTable(); // p3_transfer_reservas
        $hotelesTable  = (new Hotel)->getTable();   // p3_transfer_hoteles
        $zonasTable    = (new Zona)->getTable();    // p3_transfer_zonas

        $rows = Reserva::query()
            ->selectRaw("
                {$zonasTable}.id_zona,
                {$zonasTable}.descripcion,
                {$zonasTable}.codigo,
                COUNT({$reservasTable}.id_reserva) as total_traslados
            ")
            ->join($hotelesTable, "{$reservasTable}.id_hotel_destino", '=', "{$hotelesTable}.id_hotel")
            ->join($zonasTable, "{$hotelesTable}.id_zona", '=', "{$zonasTable}.id_zona")
            ->where("{$reservasTable}.estado", '=', 'realizada')
            ->groupBy("{$zonasTable}.id_zona", "{$zonasTable}.descripcion", "{$zonasTable}.codigo")
            ->orderBy("{$zonasTable}.descripcion")
            ->get();

        $totalTraslados = (int) $rows->sum('total_traslados');

        $zonas = $rows->map(function ($row) use ($totalTraslados) {
            $num = (int) $row->total_traslados;

            $porcentaje = $totalTraslados > 0
                ? round(($num * 100) / $totalTraslados, 2)
                : 0;

            return [
                'id_zona'       => (int) $row->id_zona,
                'descripcion'   => $row->descripcion,
                'codigo'        => $row->codigo,
                'num_traslados' => $num,
                'porcentaje'    => $porcentaje,
            ];
        });

        return response()->json([
            'total_traslados' => $totalTraslados,
            'zonas'           => $zonas,
            'generated_at'    => now()->toIso8601String(),
        ]);
    }
}
