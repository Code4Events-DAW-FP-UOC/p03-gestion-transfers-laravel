<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Precio;
use App\Models\Reserva;
use App\Models\TiposReserva;
use App\Models\Vehiculo;
use App\Models\Viajero;
use App\Models\Hotel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HotelReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isHotel(), 403);

        $hotel = $user->hotel;
        abort_unless($hotel, 403);

        $reservas = Reserva::with(['viajero', 'vehiculo', 'tipoReserva'])
            ->where('id_hotel_destino', $hotel->id_hotel)
            ->orderByDesc('fecha_reserva')
            ->paginate(20);

        return view('hotel.reservas.index', compact('reservas', 'hotel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user && $user->isHotel(), 403);

        $hotel = $user->hotel;
        abort_unless($hotel, 403);

        $viajeros = Viajero::with('user')
            ->orderBy('nombre')
            ->orderBy('apellido1')
            ->get();

        $vehiculos    = Vehiculo::orderBy('descripcion')->get();
        $tiposReserva = TiposReserva::orderBy('id_tipo_reserva')->get();
        $maxPlazas    = $vehiculos->max('plazas') ?? 1;

        return view('hotel.reservas.create', compact(
            'hotel',
            'viajeros',
            'vehiculos',
            'tiposReserva',
            'maxPlazas'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isHotel(), 403);

        /** @var Hotel|null $hotel */
        $hotel = $user->hotel;
        if (! $hotel) {
            abort(403, 'No hay un hotel asociado a este usuario.');
        }

        // ¿Viajero existente o nuevo?
        $viajeroModo      = $request->input('viajero_modo', 'existente');
        $esNuevoViajero   = $viajeroModo === 'nuevo' || $request->input('id_viajero') === '__new';

        // --- Reglas de validación base de la reserva ---
        $rules = [
            'viajero_modo' => ['nullable', Rule::in(['existente', 'nuevo'])],

            // si es existente, requerimos id_viajero; si es nuevo, puede venir "__new"
            'id_viajero' => [
                $esNuevoViajero ? 'nullable' : 'required',
                'string',
            ],

            'id_tipo_reserva' => [
                'required',
                'integer',
                Rule::exists((new TiposReserva)->getTable(), 'id_tipo_reserva'),
            ],
            'id_vehiculo' => [
                'required',
                'integer',
                Rule::exists((new Vehiculo)->getTable(), 'id_vehiculo'),
            ],
            'num_viajeros' => ['required', 'integer', 'min:1'],

            // Tramo ida
            'fecha_entrada'        => ['nullable', 'date', 'after_or_equal:today'],
            'hora_entrada'         => ['nullable', 'date_format:H:i'],
            'numero_vuelo_entrada' => ['nullable', 'string', 'max:50'],
            'origen_vuelo_entrada' => ['nullable', 'string', 'max:100'],

            // Tramo vuelta
            'fecha_vuelo_salida'   => ['nullable', 'date', 'after_or_equal:fecha_entrada'],
            'hora_vuelo_salida'    => ['nullable', 'date_format:H:i'],
            'numero_vuelo_salida'  => ['nullable', 'string', 'max:50'],
            'destino_vuelo_salida' => ['nullable', 'string', 'max:100'],
        ];

        // --- Reglas adicionales si se crea un NUEVO viajero ---
        if ($esNuevoViajero) {
            $rules = array_merge($rules, [
                'nuevo_nombre'          => ['required', 'string', 'max:100'],
                'nuevo_apellido1'       => ['required', 'string', 'max:100'],
                'nuevo_apellido2'       => ['nullable', 'string', 'max:100'],
                'nuevo_email'           => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique((new User)->getTable(), 'email'),
                ],
                'nuevo_telefono'        => ['nullable', 'string', 'max:50'],
                'nuevo_direccion'       => ['nullable', 'string', 'max:255'],
                'nuevo_codigo_postal'   => ['nullable', 'string', 'max:20'],
                'nuevo_ciudad'          => ['nullable', 'string', 'max:100'],
                'nuevo_pais'            => ['nullable', 'string', 'max:100'],
            ]);
        }

        $data = $request->validate($rules);

        return DB::transaction(function () use ($data, $esNuevoViajero, $hotel, $user) {

            // 1) Crear o localizar VIAJERO
            if ($esNuevoViajero) {

                // Password por defecto para IslaTransfers
                // (puedes poner en .env: ISLATRANSFERS_DEFAULT_PASSWORD=lo_que_uses)
                $defaultPassword = env('ISLATRANSFERS_DEFAULT_PASSWORD', 'islatransfers');

                // Crear usuario con rol viajero
                $nuevoUser = User::create([
                    'name'              => trim($data['nuevo_nombre'].' '.$data['nuevo_apellido1']),
                    'email'             => $data['nuevo_email'],
                    'password'          => Hash::make($defaultPassword),
                    'rol'               => 'viajero',
                    'email_verified_at' => now(),   // opcional
                ]);

                // Crear ficha de viajero asociada
                $nuevoViajero = Viajero::create([
                    'user_id'        => $nuevoUser->id,
                    'nombre'         => $data['nuevo_nombre'],
                    'apellido1'      => $data['nuevo_apellido1'],
                    'apellido2'      => $data['nuevo_apellido2'] ?? '',
                    'email'          => $data['nuevo_email'],
                    'telefono'       => $data['nuevo_telefono'] ?? '',
                    'direccion'      => $data['nuevo_direccion'] ?? '',
                    'codigo_postal'  => $data['nuevo_codigo_postal'] ?? '',
                    'ciudad'         => $data['nuevo_ciudad'] ?? '',
                    'pais'           => $data['nuevo_pais'] ?? 'España',
                ]);

                $idViajero = $nuevoViajero->id_viajero;

            } else {
                // Viajero ya existente
                $idViajero = (int) $data['id_viajero'];
            }

            // 2) Tipo de reserva
            $tipoReserva = TiposReserva::findOrFail($data['id_tipo_reserva']);
            $codigo      = $tipoReserva->codigo;   // p.ej. SOLO_IDA, SOLO_VUELTA, IDA_VUELTA

            $esIda    = in_array($codigo, ['SOLO_IDA', 'IDA_VUELTA'], true);
            $esVuelta = in_array($codigo, ['SOLO_VUELTA', 'IDA_VUELTA'], true);

            // 3) Capacidad del vehículo
            $vehiculo = Vehiculo::findOrFail($data['id_vehiculo']);
            if ($data['num_viajeros'] > $vehiculo->plazas) {
                return back()
                    ->withErrors(['num_viajeros' => 'El vehículo no tiene plazas suficientes para el número de viajeros.'])
                    ->withInput();
            }

            // 4) Precio hotel + vehículo
            $precio = Precio::where('id_hotel', $hotel->id_hotel)
                ->where('id_vehiculo', $vehiculo->id_vehiculo)
                ->first();

            if (! $precio) {
                return back()
                    ->withErrors(['id_vehiculo' => 'No hay una tarifa definida para este hotel y vehículo.'])
                    ->withInput();
            }

            $importeBase = (float) $precio->precio;

            // 5) Comisión hotel
            $porcentajeComision = (float) ($hotel->comision ?? 0);
            $importeComision    = round($importeBase * ($porcentajeComision / 100), 2);

            // 6) Crear la reserva
            Reserva::create([
                'localizador'        => Reserva::generarLocalizador(),
                'id_viajero'         => $idViajero,
                'id_creador'         => $user->id,
                'id_tipo_reserva'    => $tipoReserva->id_tipo_reserva,
                'id_precio'          => $precio->id_precio,
                'fecha_reserva'      => now(),
                'fecha_modificacion' => now(),
                'id_hotel_destino'   => $hotel->id_hotel,

                'num_viajeros'       => $data['num_viajeros'],
                'id_vehiculo'        => $vehiculo->id_vehiculo,

                // Tramo ida
                'fecha_entrada'        => $esIda ? ($data['fecha_entrada'] ?? null) : null,
                'hora_entrada'         => $esIda ? ($data['hora_entrada'] ?? null) : null,
                'numero_vuelo_entrada' => $esIda ? ($data['numero_vuelo_entrada'] ?? null) : null,
                'origen_vuelo_entrada' => $esIda ? ($data['origen_vuelo_entrada'] ?? null) : null,

                // Tramo vuelta
                'fecha_vuelo_salida'   => $esVuelta ? ($data['fecha_vuelo_salida'] ?? null) : null,
                'hora_vuelo_salida'    => $esVuelta ? ($data['hora_vuelo_salida'] ?? null) : null,
                'numero_vuelo_salida'  => $esVuelta ? ($data['numero_vuelo_salida'] ?? null) : null,
                'destino_vuelo_salida' => $esVuelta ? ($data['destino_vuelo_salida'] ?? null) : null,

                'estado'              => 'pendiente',
                'comision_porcentaje' => $porcentajeComision,
                'comision_importe'    => $importeComision,
            ]);

            return redirect()
                ->route('hotel.reservas.index')
                ->with('status', 'Reserva creada correctamente.');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        $user = Auth::user();
        abort_unless($user && $user->isHotel(), 403);

        $hotel = $user->hotel;
        abort_unless($hotel && $reserva->id_hotel_destino === $hotel->id_hotel, 403);

        // No permitir editar reservas canceladas o realizadas
        if (in_array($reserva->estado, ['cancelada', 'realizada'], true)) {
            return redirect()
                ->route('hotel.reservas.index')
                ->with('error', 'Esta reserva ya no se puede modificar.');
        }

        $viajeros     = Viajero::with('user')->orderBy('nombre')->orderBy('apellido1')->get();
        $vehiculos    = Vehiculo::orderBy('descripcion')->get();
        $tiposReserva = TiposReserva::orderBy('id_tipo_reserva')->get();
        $maxPlazas    = $vehiculos->max('plazas') ?? 1;

        return view('hotel.reservas.edit', compact(
            'reserva',
            'hotel',
            'viajeros',
            'vehiculos',
            'tiposReserva',
            'maxPlazas'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $user = Auth::user();
        abort_unless($user && $user->isHotel(), 403);

        $hotel = $user->hotel;
        abort_unless($hotel && $reserva->id_hotel_destino === $hotel->id_hotel, 403);

        if (in_array($reserva->estado, ['cancelada', 'realizada'], true)) {
            return redirect()
                ->route('hotel.reservas.index')
                ->with('error', 'Esta reserva ya no se puede modificar.');
        }

        $data = $request->validate([
            'id_viajero'           => [
                'required',
                'integer',
                Rule::exists((new Viajero)->getTable(), 'id_viajero'),
            ],
            'id_tipo_reserva'      => [
                'required',
                'integer',
                Rule::exists((new TiposReserva)->getTable(), 'id_tipo_reserva'),
            ],
            'id_vehiculo'          => [
                'required',
                'integer',
                Rule::exists((new Vehiculo)->getTable(), 'id_vehiculo'),
            ],
            'num_viajeros'         => ['required', 'integer', 'min:1'],

            'fecha_entrada'        => ['nullable', 'date'],
            'hora_entrada'         => ['nullable', 'date_format:H:i'],
            'numero_vuelo_entrada' => ['nullable', 'string', 'max:50'],
            'origen_vuelo_entrada' => ['nullable', 'string', 'max:100'],

            'fecha_vuelo_salida'   => ['nullable', 'date'],
            'hora_vuelo_salida'    => ['nullable', 'date_format:H:i'],
            'numero_vuelo_salida'  => ['nullable', 'string', 'max:50'],
            'destino_vuelo_salida' => ['nullable', 'string', 'max:100'],
        ]);

        $ahora = now();

        $tipo     = (int) $data['id_tipo_reserva'];
        $esIda    = in_array($tipo, [1, 3], true);
        $esVuelta = in_array($tipo, [2, 3], true);

        // Validaciones de fechas
        if ($esIda && !empty($data['fecha_entrada']) && !empty($data['hora_entrada'])) {
            $momentoIda = Carbon::parse($data['fecha_entrada'] . ' ' . $data['hora_entrada']);
            if ($momentoIda->lt($ahora)) {
                return back()
                    ->withErrors(['fecha_entrada' => 'La fecha y hora de ida no pueden ser anteriores a ahora.'])
                    ->withInput();
            }
        }

        if ($esVuelta && !empty($data['fecha_vuelo_salida']) && !empty($data['hora_vuelo_salida'])) {
            $momentoVuelta = Carbon::parse($data['fecha_vuelo_salida'] . ' ' . $data['hora_vuelo_salida']);
            if ($momentoVuelta->lt($ahora)) {
                return back()
                    ->withErrors(['fecha_vuelo_salida' => 'La fecha y hora de vuelta no pueden ser anteriores a ahora.'])
                    ->withInput();
            }
        }

        if ($esIda && $esVuelta
            && !empty($data['fecha_entrada']) && !empty($data['hora_entrada'])
            && !empty($data['fecha_vuelo_salida']) && !empty($data['hora_vuelo_salida'])
        ) {
            $momentoIda    = Carbon::parse($data['fecha_entrada'] . ' ' . $data['hora_entrada']);
            $momentoVuelta = Carbon::parse($data['fecha_vuelo_salida'] . ' ' . $data['hora_vuelo_salida']);

            if ($momentoVuelta->lessThanOrEqualTo($momentoIda)) {
                return back()
                    ->withErrors(['fecha_vuelo_salida' => 'En reservas de ida y vuelta, la fecha y hora de vuelta deben ser posteriores a la ida.'])
                    ->withInput();
            }
        }

        // Capacidad del vehículo
        $vehiculo = Vehiculo::findOrFail($data['id_vehiculo']);
        if ($data['num_viajeros'] > $vehiculo->plazas) {
            return back()
                ->withErrors(['num_viajeros' => 'El número de viajeros no puede superar las plazas del vehículo seleccionado.'])
                ->withInput();
        }

        // Precio para esta combinación hotel+vehículo
        $precio = Precio::where('id_hotel', $hotel->id_hotel)
            ->where('id_vehiculo', $data['id_vehiculo'])
            ->first();

        if (! $precio) {
            return back()
                ->withErrors(['id_vehiculo' => 'No hay un precio configurado para este hotel y vehículo.'])
                ->withInput();
        }

        DB::transaction(function () use ($reserva, $data, $vehiculo, $precio, $hotel, $tipo) {

            $reserva->id_viajero         = $data['id_viajero'];
            $reserva->id_tipo_reserva    = $tipo;
            $reserva->id_vehiculo        = $data['id_vehiculo'];
            $reserva->num_viajeros       = $data['num_viajeros'];
            $reserva->id_precio          = $precio->id_precio;
            $reserva->fecha_modificacion = now();

            // Tramo ida
            if (in_array($tipo, [1, 3], true)) {
                $reserva->fecha_entrada        = $data['fecha_entrada'];
                $reserva->hora_entrada         = $data['hora_entrada'];
                $reserva->numero_vuelo_entrada = $data['numero_vuelo_entrada'];
                $reserva->origen_vuelo_entrada = $data['origen_vuelo_entrada'];
            } else {
                $reserva->fecha_entrada        = null;
                $reserva->hora_entrada         = null;
                $reserva->numero_vuelo_entrada = null;
                $reserva->origen_vuelo_entrada = null;
            }

            // Tramo vuelta
            if (in_array($tipo, [2, 3], true)) {
                $reserva->fecha_vuelo_salida   = $data['fecha_vuelo_salida'];
                $reserva->hora_vuelo_salida    = $data['hora_vuelo_salida'];
                $reserva->numero_vuelo_salida  = $data['numero_vuelo_salida'];
                $reserva->destino_vuelo_salida = $data['destino_vuelo_salida'];
            } else {
                $reserva->fecha_vuelo_salida   = null;
                $reserva->hora_vuelo_salida    = null;
                $reserva->numero_vuelo_salida  = null;
                $reserva->destino_vuelo_salida = null;
            }

            // Recalcular importe base y comisión (sin precio_total)
            $factor      = in_array($tipo, [3], true) ? 2 : 1;   // ida+vuelta => doble precio
            $importeBase = (float) $precio->precio * $factor;

            $porcentajeComision = (float) ($hotel->comision ?? 0);
            $importeComision    = round($importeBase * $porcentajeComision / 100, 2);

            $reserva->comision_porcentaje = $porcentajeComision;
            $reserva->comision_importe    = $importeComision;

            $reserva->save();
        });

        return redirect()
            ->route('hotel.reservas.index')
            ->with('status', 'Reserva actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        $user = Auth::user();
        abort_unless($user && $user->isHotel(), 403);

        $hotel = $user->hotel;
        abort_unless($hotel && $reserva->id_hotel_destino === $hotel->id_hotel, 403);

        if (! in_array($reserva->estado, ['realizada', 'cancelada'], true)) {
            $reserva->estado             = 'cancelada';
            $reserva->fecha_modificacion = now();
            $reserva->save();
        }

        return redirect()
            ->route('hotel.reservas.index')
            ->with('status', 'Reserva cancelada correctamente.');
    }
}