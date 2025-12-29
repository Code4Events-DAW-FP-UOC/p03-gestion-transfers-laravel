<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Viajero;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ViajeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Si ya hay suficientes viajeros, no hacemos nada
        if (Viajero::count() >= 15) {
            return;
        }

        $viajeros = [
            ['nombre' => 'Juan',        'apellido1' => 'Pérez',     'apellido2' => 'García',  'email' => 'juan@example.com'],
            ['nombre' => 'María',       'apellido1' => 'López',     'apellido2' => 'Santos',  'email' => 'maria@example.com'],
            ['nombre' => 'Carlos',      'apellido1' => 'Ruiz',      'apellido2' => 'Martín',  'email' => 'carlos@example.com'],
            ['nombre' => 'Ana',         'apellido1' => 'Gómez',     'apellido2' => 'Torres',  'email' => 'ana@example.com'],
            ['nombre' => 'Luis',        'apellido1' => 'Fernández', 'apellido2' => 'Suárez',  'email' => 'luis@example.com'],
            ['nombre' => 'Laura',       'apellido1' => 'Martínez',  'apellido2' => 'Ramos',   'email' => 'laura@example.com'],
            ['nombre' => 'Pedro',       'apellido1' => 'Sánchez',   'apellido2' => 'Iglesias','email' => 'pedro@example.com'],
            ['nombre' => 'Elena',       'apellido1' => 'Romero',    'apellido2' => 'Vega',    'email' => 'elena@example.com'],
            ['nombre' => 'Sergio',      'apellido1' => 'Navarro',   'apellido2' => 'Cano',    'email' => 'sergio@example.com'],
            ['nombre' => 'Lucía',       'apellido1' => 'Ortega',    'apellido2' => 'Gil',     'email' => 'lucia@example.com'],
            ['nombre' => 'Pablo',       'apellido1' => 'Castro',    'apellido2' => 'Mora',    'email' => 'pablo@example.com'],
            ['nombre' => 'Cristina',    'apellido1' => 'Jiménez',   'apellido2' => 'Solé',    'email' => 'cristina@example.com'],
            ['nombre' => 'Javier',      'apellido1' => 'Moreno',    'apellido2' => 'Vidal',   'email' => 'javier@example.com'],
            ['nombre' => 'Marta',       'apellido1' => 'Domínguez', 'apellido2' => 'Roca',    'email' => 'marta@example.com'],
            ['nombre' => 'Raúl',        'apellido1' => 'Ibañez',    'apellido2' => 'Costa',   'email' => 'raul@example.com'],
        ];

        foreach ($viajeros as $data) {
            // 1) Usuario asociado
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['nombre'],
                    'password' => Hash::make('password'), // contraseña demo
                    'rol'      => 'viajero',
                ]
            );

            // 2) Ficha de viajero
            Viajero::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nombre'        => $data['nombre'],
                    'apellido1'     => $data['apellido1'],
                    'apellido2'     => $data['apellido2'],
                    'direccion'     => 'Calle de ejemplo 123',
                    'codigo_postal' => '08001',
                    'ciudad'        => 'Ciudad Demo',
                    'pais'          => 'España',
                    'email'         => $data['email'],
                    'telefono'      => '600000000',
                    'activo'        => true,
                ]
            );
        }

    }
}
