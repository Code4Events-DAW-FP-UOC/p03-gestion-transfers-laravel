<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiculo;

class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // No borramos nada, solo garantizamos un mínimo de vehículos
        if (Vehiculo::count() >= 10) {
            return;
        }

        $vehiculos = [
            [
                'descripcion' => 'Sedán 1-3 pax',
                'email'       => 'flota1@islatransfers.test',
                'matricula'   => '1234-ABC',
                'plazas'      => 3,
            ],
            [
                'descripcion' => 'Minivan 1-6 pax',
                'email'       => 'flota2@islatransfers.test',
                'matricula'   => '2345-BCD',
                'plazas'      => 6,
            ],
            [
                'descripcion' => 'Minibús 7-15 pax',
                'email'       => 'flota3@islatransfers.test',
                'matricula'   => '3456-CDE',
                'plazas'      => 15,
            ],
            [
                'descripcion' => 'Coche de lujo 1-3 pax',
                'email'       => 'flota4@islatransfers.test',
                'matricula'   => '4567-DEF',
                'plazas'      => 3,
            ],
            [
                'descripcion' => 'Furgoneta 1-8 pax',
                'email'       => 'flota5@islatransfers.test',
                'matricula'   => '5678-EFG',
                'plazas'      => 8,
            ],
            [
                'descripcion' => 'Minibús 16-25 pax',
                'email'       => 'flota6@islatransfers.test',
                'matricula'   => '6789-FGH',
                'plazas'      => 25,
            ],
            [
                'descripcion' => 'Autocar 26-40 pax',
                'email'       => 'flota7@islatransfers.test',
                'matricula'   => '7890-GHI',
                'plazas'      => 40,
            ],
            [
                'descripcion' => 'SUV 1-4 pax',
                'email'       => 'flota8@islatransfers.test',
                'matricula'   => '8901-HIJ',
                'plazas'      => 4,
            ],
            [
                'descripcion' => 'Taxi 1-3 pax',
                'email'       => 'flota9@islatransfers.test',
                'matricula'   => '9012-IJK',
                'plazas'      => 3,
            ],
            [
                'descripcion' => 'VIP Van 1-6 pax',
                'email'       => 'flota10@islatransfers.test',
                'matricula'   => '0123-JKL',
                'plazas'      => 6,
            ],
        ];

        foreach ($vehiculos as $data) {
            Vehiculo::firstOrCreate(
                ['matricula' => $data['matricula']], // clave "única" lógica para no duplicar
                $data
            );
        }
    }
}
