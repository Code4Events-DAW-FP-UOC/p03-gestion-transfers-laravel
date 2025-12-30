<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\User;
use App\Models\Viajero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============ ADMIN ============
        $admin = User::updateOrCreate(
            ['email' => 'admin@islatransfers.test'], // clave única
            [
                'name'              => 'Admin Isla Transfers',
                'password'          => Hash::make('admin1234'),
                'rol'               => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // ============ VIAJERO ============
        $viajeroUser = User::updateOrCreate(
            ['email' => 'viajero@islatransfers.test'],
            [
                'name'              => 'Viajero Demo',
                'password'          => Hash::make('viajero1234'),
                'rol'               => 'viajero',
                'email_verified_at' => now(),
            ]
        );

        Viajero::updateOrCreate(
            ['user_id' => $viajeroUser->id],
            [
                'nombre'        => 'Viajero',
                'apellido1'     => 'Demo',
                'apellido2'     => null,
                'direccion'     => 'Calle Falsa 123',
                'codigo_postal' => '08001',
                'ciudad'        => 'Barcelona',
                'pais'          => 'España',
                'email'         => $viajeroUser->email,
                'telefono'      => '600000000',
                'activo'        => true,
            ]
        );

        // ============ HOTEL ============
        $hotelUser = User::updateOrCreate(
            ['email' => 'hotel@islatransfers.test'],
            [
                'name'              => 'Hotel Isla Bonita',
                'password'          => Hash::make('hotel1234'),
                'rol'               => 'hotel',
                'email_verified_at' => now(),
            ]
        );

        Hotel::updateOrCreate(
            ['user_id' => $hotelUser->id],
            [
                'id_zona'  => 1, // asumiendo que existe en ZonaSeeder
                'nombre'   => 'Hotel Isla Bonita',
                'email'    => $hotelUser->email,
                'telefono' => '971000000',
                'comision' => 10.00,
                'activo'   => true,
            ]
        );
    }
}
