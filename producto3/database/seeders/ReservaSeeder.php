<?php

namespace Database\Seeders;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Viajero;
use App\Models\Hotel;
use App\Models\Vehiculo;
use App\Models\TiposReserva;
use App\Models\Precio;
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

        $viajeros  = Viajero::all();
        $hoteles   = Hotel::all();
        $vehiculos = Vehiculo::all(); // (no lo usamos directamente, pero por coherencia)
        $tipos     = TiposReserva::all();
        $precios   = Precio::all();

        $adminUser     = User::where('rol', 'admin')->first();
        $viajeroUsers  = User::where('rol', 'viajero')->pluck('id');
        $hotelUsersIds = User::where('rol', 'hotel')->pluck('id');

        if (
            $viajeros->isEmpty() ||
            $hoteles->isEmpty() ||
            $tipos->isEmpty()   ||
            $precios->isEmpty() ||
            !$adminUser         ||
            $viajeroUsers->isEmpty() ||
            $hotelUsersIds->isEmpty()
        ) {
            return;
        }

        // Mapeo tipos por código
        $tiposByCodigo = $tipos->keyBy('codigo');
        $tipoSoloIda   = $tiposByCodigo['SOLO_IDA']   ?? null;
        $tipoSoloVta   = $tiposByCodigo['SOLO_VUELTA']?? null;
        $tipoIdaVta    = $tiposByCodigo['IDA_VUELTA'] ?? null;

        if (!$tipoSoloIda || !$tipoSoloVta || !$tipoIdaVta) {
            return;
        }

        // Parámetros de fechas
        $startServicio = Carbon::create(2025, 10, 1);
        $endServicio   = Carbon::create(2026, 3, 30);

        // Reservas con servicio antes del 31/12/2025 -> realizadas/canceladas
        // Servicio a partir del 31/12/2025 -> pendiente/confirmada
        $futurePivot   = Carbon::create(2025, 12, 31);
        $oldEnd        = Carbon::create(2025, 12, 30);

        // Distribución de tipos (250 = 84 / 83 / 83)
        $totalReservas = 250;
        $maxPorTipo = [
            'SOLO_IDA'   => 84,
            'SOLO_VUELTA'=> 83,
            'IDA_VUELTA' => 83,
        ];
        $contPorTipo = [
            'SOLO_IDA'   => 0,
            'SOLO_VUELTA'=> 0,
            'IDA_VUELTA' => 0,
        ];
        $codigosTipos = ['SOLO_IDA', 'SOLO_VUELTA', 'IDA_VUELTA'];

        // Seguimiento de "tiene al menos una realizada"
        $hotelHasRealizada   = [];
        foreach ($hoteles as $h) {
            $hotelHasRealizada[$h->id_hotel] = false;
        }
        $viajeroHasRealizada = [];
        foreach ($viajeros as $v) {
            $viajeroHasRealizada[$v->id_viajero] = false;
        }

        // Seguimiento de roles de creador
        $hayCreadorAdmin   = false;
        $hayCreadorHotel   = false;
        $hayCreadorViajero = false;

        // --- 1) Crear 250 reservas iniciales ---
        for ($i = 0; $i < $totalReservas; $i++) {

            // Escoger tipo respetando máximos
            $tiposDisponibles = array_filter($codigosTipos, function ($codigo) use ($contPorTipo, $maxPorTipo) {
                return $contPorTipo[$codigo] < $maxPorTipo[$codigo];
            });

            $tipoCodigo = $tiposDisponibles[array_rand($tiposDisponibles)];
            $contPorTipo[$tipoCodigo]++;

            $tipo = match ($tipoCodigo) {
                'SOLO_IDA'    => $tipoSoloIda,
                'SOLO_VUELTA' => $tipoSoloVta,
                'IDA_VUELTA'  => $tipoIdaVta,
            };

            // Precio aleatorio -> define hotel + vehículo
            /** @var Precio $precio */
            $precio   = $precios->random();
            $hotel    = $hoteles->firstWhere('id_hotel', $precio->id_hotel);
            if (!$hotel) {
                continue; // por seguridad, aunque no debería pasar
            }

            // Viajero aleatorio
            $viajero = $viajeros->random();

            // Creador: 50% viajero, 30% hotel, 20% admin
            $rand = mt_rand(1, 100);
            $creadorRol = null;
            if ($rand <= 50 && $viajero->user_id) {
                $idCreador = $viajero->user_id;
                $creadorRol = 'viajero';
            } elseif ($rand <= 80 && $hotel->user_id) {
                $idCreador = $hotel->user_id;
                $creadorRol = 'hotel';
            } else {
                $idCreador = $adminUser->id;
                $creadorRol = 'admin';
            }

            if ($creadorRol === 'viajero')   $hayCreadorViajero = true;
            if ($creadorRol === 'hotel')     $hayCreadorHotel   = true;
            if ($creadorRol === 'admin')     $hayCreadorAdmin   = true;

            // Fecha de servicio (para ida, o para vuelta en ida+vuelta / solo vuelta)
            $tsServicio = mt_rand($startServicio->timestamp, $endServicio->timestamp);
            $fechaServicio = Carbon::createFromTimestamp($tsServicio);

            // Fecha de reserva: entre startServicio y el día del servicio (o el mismo día)
            $maxReserva = (clone $fechaServicio)->subDay();
            if ($maxReserva->lessThan($startServicio)) {
                $maxReserva = clone $startServicio;
            }
            $tsReserva = mt_rand($startServicio->timestamp, $maxReserva->timestamp);
            $fechaReserva = Carbon::createFromTimestamp($tsReserva);

            // Fechas/hora según tipo
            $fechaEntrada        = null;
            $horaEntrada         = null;
            $numeroVueloEntrada  = null;
            $origenVueloEntrada  = null;

            $fechaSalida         = null;
            $horaSalida          = null;
            $numeroVueloSalida   = null;
            $destinoVueloSalida  = null;

            // helper horita
            $horaRandom = fn() => sprintf('%02d:%02d', mt_rand(0, 23), mt_rand(0, 1) * 30);

            if ($tipoCodigo === 'SOLO_IDA') {
                $fechaEntrada       = $fechaServicio->toDateString();
                $horaEntrada        = $horaRandom();
                $numeroVueloEntrada = 'IB' . mt_rand(1000, 9999);
                $origenVueloEntrada = 'MAD';
            } elseif ($tipoCodigo === 'SOLO_VUELTA') {
                $fechaSalida        = $fechaServicio->toDateString();
                $horaSalida         = $horaRandom();
                $numeroVueloSalida  = 'VY' . mt_rand(1000, 9999);
                $destinoVueloSalida = 'MAD';
            } else { // IDA_VUELTA
                $fechaSalida        = $fechaServicio->toDateString();
                $horaSalida         = $horaRandom();
                $numeroVueloSalida  = 'UX' . mt_rand(1000, 9999);
                $destinoVueloSalida = 'MAD';

                // Ida unos días antes (0–3) sin salirnos del rango
                $fechaEntradaObj = (clone $fechaServicio)->subDays(mt_rand(0, 3));
                if ($fechaEntradaObj->lessThan($startServicio)) {
                    $fechaEntradaObj = clone $startServicio;
                }
                $fechaEntrada       = $fechaEntradaObj->toDateString();
                $horaEntrada        = $horaRandom();
                $numeroVueloEntrada = 'IB' . mt_rand(1000, 9999);
                $origenVueloEntrada = 'BCN';
            }

            // Estado en función del servicio (usamos fechaServicio para la regla)
            if ($fechaServicio->lessThanOrEqualTo($oldEnd)) {
                $estado = (mt_rand(0, 1) === 0) ? 'realizada' : 'cancelada';
            } else {
                $estado = (mt_rand(0, 1) === 0) ? 'pendiente' : 'confirmada';
            }

            // Cálculo de comisión: precio según tipo
            $factor = ($tipoCodigo === 'IDA_VUELTA') ? 2 : 1;
            $importeBase = (float) $precio->precio * $factor;
            $porcentaje  = (float) ($hotel->comision ?? 0);
            $importeComision = round($importeBase * $porcentaje / 100, 2);

            // Localizador único
            do {
                $localizador = strtoupper(Str::random(8));
            } while (Reserva::where('localizador', $localizador)->exists());

            /** @var Reserva $reserva */
            $reserva = Reserva::create([
                'localizador'        => $localizador,

                // hotel que realiza la reserva (solo cuando el creador es hotel)
                'id_hotel'           => $creadorRol === 'hotel' ? $hotel->id_hotel : null,

                'id_viajero'         => $viajero->id_viajero,
                'id_creador'         => $idCreador,
                'id_modificador'     => null,
                'id_tipo_reserva'    => $tipo->id_tipo_reserva,
                'id_precio'          => $precio->id_precio,

                'fecha_reserva'      => $fechaReserva->toDateTimeString(),
                'fecha_modificacion' => null,

                'id_hotel_destino'   => $hotel->id_hotel,
                'num_viajeros'       => mt_rand(1, 6),
                'id_vehiculo'        => $precio->id_vehiculo,

                'fecha_entrada'        => $fechaEntrada,
                'hora_entrada'         => $horaEntrada,
                'numero_vuelo_entrada' => $numeroVueloEntrada,
                'origen_vuelo_entrada' => $origenVueloEntrada,

                'fecha_vuelo_salida'   => $fechaSalida,
                'hora_vuelo_salida'    => $horaSalida,
                'numero_vuelo_salida'  => $numeroVueloSalida,
                'destino_vuelo_salida' => $destinoVueloSalida,

                'estado'               => $estado,
                'observaciones'        => null,

                'comision_porcentaje'  => $porcentaje,
                'comision_importe'     => $importeComision,
            ]);

            // Marcar realizadas
            if ($estado === 'realizada') {
                $hotelHasRealizada[$hotel->id_hotel]   = true;
                $viajeroHasRealizada[$viajero->id_viajero] = true;
            }
        }

        // --- 2) Garantizar que TODOS los hoteles tienen al menos una 'realizada' ---
        foreach ($hoteles as $hotel) {
            if (!($hotelHasRealizada[$hotel->id_hotel] ?? false)) {
                /** @var Reserva|null $reserva */
                $reserva = Reserva::where('id_hotel_destino', $hotel->id_hotel)->first();
                if (!$reserva) {
                    continue;
                }

                // Fecha de servicio en tramo "pasado"
                $fechaServicio = Carbon::createFromTimestamp(
                    mt_rand($startServicio->timestamp, $oldEnd->timestamp)
                );

                $this->forzarReservaRealizadaEnFecha($reserva, $fechaServicio, $startServicio);

                $hotelHasRealizada[$hotel->id_hotel] = true;
                $viajeroHasRealizada[$reserva->id_viajero] = true;
            }
        }

        // --- 3) Garantizar que TODOS los viajeros tienen al menos una 'realizada' ---
        foreach ($viajeros as $viajero) {
            if (!($viajeroHasRealizada[$viajero->id_viajero] ?? false)) {
                /** @var Reserva|null $reserva */
                $reserva = Reserva::where('id_viajero', $viajero->id_viajero)->first();
                if (!$reserva) {
                    continue;
                }

                $fechaServicio = Carbon::createFromTimestamp(
                    mt_rand($startServicio->timestamp, $oldEnd->timestamp)
                );

                $this->forzarReservaRealizadaEnFecha($reserva, $fechaServicio, $startServicio);

                $viajeroHasRealizada[$viajero->id_viajero] = true;
                $hotelHasRealizada[$reserva->id_hotel_destino] = true;
            }
        }

        // --- 4) Asegurar que existen reservas creadas por admin, hotel y viajero ---

        $hayCreadorViajero = Reserva::whereIn('id_creador', $viajeroUsers)->exists();
        $hayCreadorHotel   = Reserva::whereIn('id_creador', $hotelUsersIds)->exists();
        $hayCreadorAdmin   = Reserva::where('id_creador', $adminUser->id)->exists();

        // Si falta alguno, reasignamos el creador de alguna reserva aleatoria
        if (!$hayCreadorViajero && $viajeroUsers->isNotEmpty()) {
            $reserva = Reserva::inRandomOrder()->first();
            if ($reserva) {
                $reserva->id_creador = $viajeroUsers->random();
                $reserva->save();
            }
        }

        if (!$hayCreadorHotel && $hotelUsersIds->isNotEmpty()) {
            $reserva = Reserva::inRandomOrder()->first();
            if ($reserva) {
                $reserva->id_creador = $hotelUsersIds->random();
                $reserva->save();
            }
        }

        if (!$hayCreadorAdmin) {
            $reserva = Reserva::inRandomOrder()->first();
            if ($reserva) {
                $reserva->id_creador = $adminUser->id;
                $reserva->save();
            }
        }
    }

    /**
     * Ajusta una reserva ya existente para que:
     *  - tenga estado 'realizada'
     *  - el servicio esté en la fecha indicada (en el tramo pasado)
     *  - respete coherencia de campos según el tipo.
     */
    protected function forzarReservaRealizadaEnFecha(Reserva $reserva, Carbon $fechaServicio, Carbon $startServicio): void
    {
        $horaRandom = function () {
            return sprintf('%02d:%02d', mt_rand(0, 23), mt_rand(0, 1) * 30);
        };

        // Obtenemos el tipo para saber cómo rellenar campos
        $tipoId = $reserva->id_tipo_reserva;

        // SOLO_IDA (id = 1) / SOLO_VUELTA (id = 2) / IDA_VUELTA (id = 3)
        if ($tipoId === 1) { // SOLO_IDA
            $reserva->fecha_entrada        = $fechaServicio->toDateString();
            $reserva->hora_entrada         = $horaRandom();
            $reserva->numero_vuelo_entrada = $reserva->numero_vuelo_entrada ?: 'IB' . mt_rand(1000, 9999);
            $reserva->origen_vuelo_entrada = $reserva->origen_vuelo_entrada ?: 'BCN';

            $reserva->fecha_vuelo_salida   = null;
            $reserva->hora_vuelo_salida    = null;
            $reserva->numero_vuelo_salida  = null;
            $reserva->destino_vuelo_salida = null;
        } elseif ($tipoId === 2) { // SOLO_VUELTA
            $reserva->fecha_vuelo_salida   = $fechaServicio->toDateString();
            $reserva->hora_vuelo_salida    = $horaRandom();
            $reserva->numero_vuelo_salida  = $reserva->numero_vuelo_salida ?: 'VY' . mt_rand(1000, 9999);
            $reserva->destino_vuelo_salida = $reserva->destino_vuelo_salida ?: 'MAD';

            $reserva->fecha_entrada        = null;
            $reserva->hora_entrada         = null;
            $reserva->numero_vuelo_entrada = null;
            $reserva->origen_vuelo_entrada = null;
        } else { // IDA_VUELTA
            $reserva->fecha_vuelo_salida   = $fechaServicio->toDateString();
            $reserva->hora_vuelo_salida    = $horaRandom();
            $reserva->numero_vuelo_salida  = $reserva->numero_vuelo_salida ?: 'UX' . mt_rand(1000, 9999);
            $reserva->destino_vuelo_salida = $reserva->destino_vuelo_salida ?: 'MAD';

            $fechaEntradaObj = (clone $fechaServicio)->subDays(mt_rand(0, 3));
            if ($fechaEntradaObj->lessThan($startServicio)) {
                $fechaEntradaObj = clone $startServicio;
            }
            $reserva->fecha_entrada        = $fechaEntradaObj->toDateString();
            $reserva->hora_entrada         = $horaRandom();
            $reserva->numero_vuelo_entrada = $reserva->numero_vuelo_entrada ?: 'IB' . mt_rand(1000, 9999);
            $reserva->origen_vuelo_entrada = $reserva->origen_vuelo_entrada ?: 'BCN';
        }

        // Estado y fechas de gestión
        $reserva->estado = 'realizada';

        // Ajustamos fecha_reserva para que sea anterior al servicio
        $maxReserva = (clone $fechaServicio)->subDay();
        if ($maxReserva->lessThan($startServicio)) {
            $maxReserva = clone $startServicio;
        }
        $tsReserva = mt_rand($startServicio->timestamp, $maxReserva->timestamp);
        $reserva->fecha_reserva      = Carbon::createFromTimestamp($tsReserva)->toDateTimeString();
        $reserva->fecha_modificacion = $reserva->fecha_reserva;

        $reserva->save();
    }
}
