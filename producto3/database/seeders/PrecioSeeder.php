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
        $vehiculos = Vehiculo::all();

        if ($hoteles->isEmpty() || $vehiculos->isEmpty()) {
            // Si no hay datos previos, salimos sin hacer nada
            return;
        }

        /*
         * 1) Asegurar al menos UN precio por cada hotel
         *    Asignamos a cada hotel un vehículo (cíclico) y un precio base
         */
        foreach ($hoteles as $index => $hotel) {
            $vehiculo = $vehiculos[$index % $vehiculos->count()];

            Precio::firstOrCreate(
                [
                    'id_hotel'   => $hotel->id_hotel,
                    'id_vehiculo'=> $vehiculo->id_vehiculo,
                ],
                [
                    'precio'     => $this->precioBaseSegunPlazas($vehiculo->plazas),
                ]
            );
        }

        /*
         * 2) Generar combinaciones extra hasta llegar a ~15 precios
         */
        $targetTotal = 15;
        $current     = Precio::count();

        if ($current >= $targetTotal) {
            return;
        }

        // Creamos más combinaciones hotel–vehículo mientras queden
        foreach ($hoteles as $hotel) {
            foreach ($vehiculos as $vehiculo) {
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

                // Solo incrementamos si realmente se ha creado (no si ya existía)
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