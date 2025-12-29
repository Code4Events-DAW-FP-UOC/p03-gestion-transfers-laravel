<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Hotel;
use App\Models\Zona;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Hotel::count() >= 10) {
            return;
        }

        $zonas = Zona::pluck('id_zona')->all();

        if (empty($zonas)) {
            // Sin zonas no podemos crear hoteles coherentes
            return;
        }

        $hoteles = [
            ['nombre' => 'Hotel Isla Bonita',      'email' => 'hotel1@example.com', 'telefono' => '971000001', 'comision' => 10.0],
            ['nombre' => 'Hotel Playa Dorada',     'email' => 'hotel2@example.com', 'telefono' => '971000002', 'comision' => 12.5],
            ['nombre' => 'Hotel Sol y Mar',        'email' => 'hotel3@example.com', 'telefono' => '971000003', 'comision' => 8.0],
            ['nombre' => 'Hotel Costa Azul',       'email' => 'hotel4@example.com', 'telefono' => '971000004', 'comision' => 9.5],
            ['nombre' => 'Hotel Bahía Serena',     'email' => 'hotel5@example.com', 'telefono' => '971000005', 'comision' => 11.0],
            ['nombre' => 'Hotel Vista Mar',        'email' => 'hotel6@example.com', 'telefono' => '971000006', 'comision' => 7.5],
            ['nombre' => 'Hotel Jardín Tropical',  'email' => 'hotel7@example.com', 'telefono' => '971000007', 'comision' => 10.5],
            ['nombre' => 'Hotel Cala Blanca',      'email' => 'hotel8@example.com', 'telefono' => '971000008', 'comision' => 9.0],
            ['nombre' => 'Hotel Puerto Azul',      'email' => 'hotel9@example.com', 'telefono' => '971000009', 'comision' => 13.0],
            ['nombre' => 'Hotel Golf Resort',      'email' => 'hotel10@example.com','telefono' => '971000010', 'comision' => 15.0],
        ];

        foreach ($hoteles as $index => $data) {
            $zonaId = $zonas[$index % count($zonas)];

            // 1) Usuario del hotel
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['nombre'],
                    'password' => Hash::make('password'),
                    'rol'      => 'hotel',
                ]
            );

            // 2) Ficha de hotel
            Hotel::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'id_zona'  => $zonaId,
                    'nombre'   => $data['nombre'],
                    'email'    => $data['email'],
                    'telefono' => $data['telefono'],
                    'comision' => $data['comision'],
                    'activo'   => true,
                ]
            );
        }

    }
}
