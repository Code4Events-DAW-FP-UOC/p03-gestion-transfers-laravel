<?php

namespace Database\Seeders;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Viajero;
use App\Models\Hotel;
use App\Models\Vehiculo;
use App\Models\TiposReserva;
use App\Models\Precio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos solo la tabla de reservas (no afecta a otras FK)
        Reserva::query()->delete();

        $viajeros     = Viajero::all();
        $hoteles      = Hotel::all();
        $vehiculos    = Vehiculo::all();
        $tipos        = TiposReserva::all();
        $precios      = Precio::all();
        $adminUser    = User::where('rol', 'admin')->first();
        $hotelUsers   = User::where('rol', 'hotel')->get();

        if (
            $viajeros->isEmpty() ||
            $hoteles->isEmpty() ||
            $vehiculos->isEmpty() ||
            $tipos->isEmpty() ||
            $precios->isEmpty() ||
            !$adminUser ||
            $hotelUsers->isEmpty()
        ) {
            return;
        }

        // Distribución de creadores: 50 viajero, 30 hotel, 20 admin
        $creadores = array_merge(
            array_fill(0, 50, 'viajero'),
            array_fill(0, 30, 'hotel'),
            array_fill(0, 20, 'admin')
        );
        shuffle($creadores);

        // Rango de fechas:
        $start1 = strtotime('2025-09-01');
        $end1   = strtotime('2025-12-17'); // antes del 18/12
        $start2 = strtotime('2025-12-18');
        $end2   = strtotime('2026-02-28');

        $estados = ['pendiente', 'confirmada', 'realizada', 'cancelada'];

        for ($i = 0; $i < 100; $i++) {
            // 1) Fecha de entrada según bloque
            if ($i < 50) {
                $tsEntrada = rand($start1, $end1);
            } else {
                $tsEntrada = rand($start2, $end2);
            }

            $fechaEntrada = Carbon::createFromTimestamp($tsEntrada);
            // fecha de reserva unos días antes
            $fechaReserva = (clone $fechaEntrada)->subDays(rand(1, 30));

            // 2) Seleccionar tipo, viajero, hotel, vehiculo, precio
            $tipo     = $tipos->random();
            $viajero  = $viajeros->random();
            $hotel    = $hoteles->random();
            $vehiculo = $vehiculos->random();

            // Precio para esa combinación, si existe
            $precio = $precios
                ->where('id_hotel', $hotel->id_hotel)
                ->where('id_vehiculo', $vehiculo->id_vehiculo)
                ->first() ?? $precios->random();

            // 3) Determinar creador
            $tipoCreador = $creadores[$i];
            $idCreador   = $adminUser->id;

            if ($tipoCreador === 'viajero') {
                $idCreador = $viajero->user_id;
            } elseif ($tipoCreador === 'hotel') {
                // Usuario de hotel asociado al hotel elegido
                $hotelUser = $hotelUsers->where('id', $hotel->user_id)->first();
                if (!$hotelUser) {
                    $hotelUser = $hotelUsers->random();
                }
                $idCreador = $hotelUser->id;
            }

            // 4) Localizador único
            do {
                $localizador = strtoupper(Str::random(8));
            } while (Reserva::where('localizador', $localizador)->exists());

            // 5) Crear reserva
            Reserva::create([
                'localizador'      => $localizador,
                'id_viajero'       => $viajero->id_viajero,
                'id_hotel'         => $hotel->id_hotel,       // hotel gestor
                'id_hotel_destino' => $hotel->id_hotel,       // destino = mismo hotel
                'id_creador'       => $idCreador,
                'id_tipo_reserva'  => $tipo->id_tipo_reserva,
                'id_precio'        => $precio->id_precio,
                'id_vehiculo'      => $vehiculo->id_vehiculo,
                'fecha_reserva'    => $fechaReserva->toDateTimeString(),
                'fecha_entrada'    => $fechaEntrada->toDateString(),
                'num_viajeros'     => rand(1, 6),
                'estado'           => $estados[array_rand($estados)],
                // el resto de campos (horas, vuelo, etc.) se quedan null
            ]);
        }
    }
}
