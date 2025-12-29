<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Vehiculo;
use App\Models\Precio;
use Illuminate\Database\Seeder;

class PrecioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hoteles   = Hotel::all();
        $vehiculos = Vehiculo::orderBy('id_vehiculo')->get();

        // Si no hay datos previos, salimos sin hacer nada
        if ($hoteles->isEmpty() || $vehiculos->count() < 2) {
            // Necesitamos al menos 1 hotel y 2 vehículos
            return;
        }

        // 1) Vehículo "bloqueado" (el primero) -> no debe tener ningún precio
        $vehiculoBloqueado   = $vehiculos->first();               // id_vehiculo más bajo
        $vehiculosDisponibles = $vehiculos->slice(1)->values();   // todos menos el primero

        // Por seguridad, eliminamos cualquier precio que pudiera existir asociado
        // al vehículo bloqueado (útil si se resemilla una BD ya usada).
        Precio::where('id_vehiculo', $vehiculoBloqueado->id_vehiculo)->delete();

        /*
         * 2) Asegurar al menos UN precio por cada hotel
         *    Usando SOLO los vehículos disponibles (no el bloqueado)
         */
        $numVehiculosDisponibles = $vehiculosDisponibles->count();

        foreach ($hoteles as $index => $hotel) {
            $vehiculo = $vehiculosDisponibles[$index % $numVehiculosDisponibles];

            Precio::firstOrCreate(
                [
                    'id_hotel'    => $hotel->id_hotel,
                    'id_vehiculo' => $vehiculo->id_vehiculo,
                ],
                [
                    'precio'      => $this->precioBaseSegunPlazas($vehiculo->plazas),
                ]
            );
        }

        /*
         * 3) Generar combinaciones extra hasta llegar a ~15 precios
         *    Siempre evitando el vehículo bloqueado
         */
        $targetTotal = 15;
        $current     = Precio::count();

        if ($current >= $targetTotal) {
            return;
        }

        foreach ($hoteles as $hotel) {
            foreach ($vehiculosDisponibles as $vehiculo) {
                if ($current >= $targetTotal) {
                    break 2; // salimos de ambos bucles
                }

                $created = Precio::firstOrCreate(
                    [
                        'id_hotel'    => $hotel->id_hotel,
                        'id_vehiculo' => $vehiculo->id_vehiculo,
                    ],
                    [
                        'precio'      => $this->precioBaseSegunPlazas($vehiculo->plazas),
                    ]
                );

                if ($created->wasRecentlyCreated) {
                    $current++;
                }
            }
        }
    }

    /**
     * Calcula un precio base "realista" según las plazas del vehículo.
     */
    private function precioBaseSegunPlazas(int $plazas): float
    {
        if ($plazas <= 3) {
            // Sedán / taxi
            return rand(40, 70);
        }

        if ($plazas <= 8) {
            // Van / minivan
            return rand(60, 90);
        }

        if ($plazas <= 20) {
            // Minibús
            return rand(80, 130);
        }

        // Autocar grande
        return rand(120, 180);
    }
}