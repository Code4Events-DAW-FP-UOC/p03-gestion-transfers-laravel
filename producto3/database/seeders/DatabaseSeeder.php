<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ZonaSeeder::class,
            VehiculoSeeder::class,
            TiposReservaSeeder::class,
            UserSeeder::class,
            ViajeroSeeder::class,
            HotelSeeder::class,
            PrecioSeeder::class,
            ReservaSeeder::class,
        ]);
    }
}
