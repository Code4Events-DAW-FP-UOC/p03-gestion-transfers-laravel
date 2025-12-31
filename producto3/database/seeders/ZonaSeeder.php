<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zona;

class ZonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Zona::updateOrCreate(
            ['id_zona' => 1],
            [
                'descripcion'  => 'Aeropuerto',
                'codigo' => 'AIRPORT',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        Zona::updateOrCreate(
            ['id_zona' => 2],
            [
                'descripcion'  => 'Zona Centro',
                'codigo' => 'CENTER_ZONE',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        Zona::updateOrCreate(
            ['id_zona' => 3],
            [
                'descripcion'  => 'Zona Norte',
                'codigo' => 'NORTH_ZONE',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        Zona::updateOrCreate(
            ['id_zona' => 4],
            [
                'descripcion'  => 'Zona Sur',
                'codigo' => 'SOUTH_ZONE',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        Zona::updateOrCreate(
            ['id_zona' => 5],
            [
                'descripcion'  => 'Zona Este',
                'codigo' => 'EAST_ZONE',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        Zona::updateOrCreate(
            ['id_zona' => 6],
            [
                'descripcion'  => 'Zona Oeste',
                'codigo' => 'WEST_ZONE',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
  
    }
}
