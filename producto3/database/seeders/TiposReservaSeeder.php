<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TiposReserva;

class TiposReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TiposReserva::updateOrCreate(
            ['id_tipo_reserva' => 1],
            [
                'descripcion'     => 'Solo ida',
                'codigo'          => 'SOLO_IDA',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );
        TiposReserva::updateOrCreate(
            ['id_tipo_reserva' => 2],
            [
                'descripcion'     => 'Solo vuelta',
                'codigo'          => 'SOLO_VUELTA',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );
        TiposReserva::updateOrCreate(
            ['id_tipo_reserva' => 3],
            [
                'descripcion'     => 'Ida y vuelta',
                'codigo'          => 'IDA_VUELTA',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );
    }
}
