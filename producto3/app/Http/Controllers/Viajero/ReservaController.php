<?php

namespace App\Http\Controllers\Viajero;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreViajeroReservaRequest;
use App\Http\Requests\UpdateViajeroReservaRequest;
use App\Models\Reserva;
use App\Models\Hotel;
use App\Models\Vehiculo;
use App\Models\Precio;
use App\Models\TiposReserva;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        abort_unless($user->isViajero(), 403);

        $viajero = $user->viajero;

        $reservas = Reserva::with(['hotelGestor', 'hotelDestino', 'vehiculo', 'tipoReserva', 'creador', 'precio'])->where('id_viajero', $viajero->id_viajero)->orderByDesc('fecha_reserva')->paginate(100);

        return view('viajero.reservas.index', compact('reservas',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user->isViajero(), 403);

        $viajero        = $user->viajero;
        $hoteles        = Hotel::activos()->get();
        $vehiculos      = Vehiculo::all();
        $tiposReserva   = TiposReserva::all();
        $maxPlazas      = Vehiculo::max('plazas') ?? 1;

        return view('viajero.reservas.create', compact('viajero', 'hoteles', 'vehiculos', 'tiposReserva', 'maxPlazas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreViajeroReservaRequest $request)
    //public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->isViajero(), 403);

        $viajero = $user->viajero;
        abort_unless($viajero, 403);

        $data = $request->validated();
        $ahora = now();

        $tipo        = (int) $data['id_tipo_reserva'];
        $esIda       = in_array($tipo, [1, 3], true); // solo ida o ida+vuelta
        $esVuelta    = in_array($tipo, [2, 3], true); // solo vuelta o ida+vuelta

        /*
        |--------------------------------------------------------------------------
        | Regla de negocio: 48 horas de antelación
        |--------------------------------------------------------------------------
        | Interpretación: para cada servicio (ida y/o vuelta) debe haber al menos
        | 48 horas reales entre el momento actual y la fecha+hora del vuelo.
        */

        // Comprobació de la IDA
        if ($esIda) {
            $momentoIda = Carbon::parse($data['fecha_entrada'].' '.$data['hora_entrada']);

            // diffInHours con $absolute = false → diferencia con signo
            $diffIda = $ahora->diffInHours($momentoIda, false);

            if ($diffIda < 48) {
                return back()->withErrors(['fecha_entrada' => 'Las reservas deben realizarse con al menos 48 horas de antelación para la ida.',])->withInput();
            }
        }

        // Comprovación de la VUELTA
        if ($esVuelta) {
            $momentoVuelta = Carbon::parse($data['fecha_vuelo_salida'].' '.$data['hora_vuelo_salida']);

            $diffVuelta = $ahora->diffInHours($momentoVuelta, false);

            if ($diffVuelta < 48) {
                return back()->withErrors(['fecha_vuelo_salida' => 'Las reservas deben realizarse con al menos 48 horas de antelación para la vuelta.',])->withInput();
            }
            // En caso de ida+vuelta, comprobar que la vuelta es posterior a la ida
            if ($esIda && $momentoIda !== null && $momentoVuelta->lessThanOrEqualTo($momentoIda)) {
                return back()->withErrors(['fecha_vuelo_salida' => 'La fecha y hora de vuelta deben ser posteriores a la fecha y hora de ida.',])->withInput();
            }
        }
    
        /*
        |--------------------------------------------------------------------------
        | Regla de negocio: capacidad del vehículo
        |--------------------------------------------------------------------------
        | El número de viajeros no puede superar las plazas del vehículo elegido.
        */
        $vehiculo = Vehiculo::findOrFail($data['id_vehiculo']);

        if ($data['num_viajeros'] > $vehiculo->plazas) {
            return back()->withErrors(['num_viajeros' => 'El número de viajeros no puede superar las plazas del vehículo seleccionado.',])->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Buscar el precio (id_precio) para hotel + vehículo + tipo
        |--------------------------------------------------------------------------
        */
        $precio = Precio::where('id_hotel', $data['id_hotel'])
            ->where('id_vehiculo', $data['id_vehiculo'])
            ->first();

        if (! $precio) {
            return back()->withErrors(['id_vehiculo' => 'No hay un precio configurado para este hotel, vehículo y tipo de reserva.',])->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Creación de la reserva
        |--------------------------------------------------------------------------
        */
        // Crear la reserva en transacción
        DB::transaction(function () use ($data, $viajero, $user, $precio) {

            $localizador = Reserva::generarLocalizador();

            $tipo = (int) $data['id_tipo_reserva'];

            Reserva::create([
                'localizador'           => $localizador,
                'id_viajero'            => $viajero->id_viajero,
                'id_creador'            => $user->id,
                'id_tipo_reserva'       => $tipo,
                'id_precio'             => $precio->id_precio,
                'fecha_reserva'         => now(),
                'fecha_modificacion'    => now(),

                'id_hotel_destino'   => $data['id_hotel'],

                'num_viajeros'    => $data['num_viajeros'],
                'id_vehiculo'     => $data['id_vehiculo'],

                // Tramo de ida (si aplica)
                'fecha_entrada'        => in_array($tipo, [1, 3], true) ? $data['fecha_entrada']        : null,
                'hora_entrada'         => in_array($tipo, [1, 3], true) ? $data['hora_entrada']         : null,
                'numero_vuelo_entrada' => in_array($tipo, [1, 3], true) ? $data['numero_vuelo_entrada'] : null,
                'origen_vuelo_entrada' => in_array($tipo, [1, 3], true) ? $data['origen_vuelo_entrada'] : null,

                // Tramo de vuelta (si aplica)
                'fecha_vuelo_salida'   => in_array($tipo, [2, 3], true) ? $data['fecha_vuelo_salida']   : null,
                'hora_vuelo_salida'    => in_array($tipo, [2, 3], true) ? $data['hora_vuelo_salida']    : null,
                'numero_vuelo_salida'  => in_array($tipo, [2, 3], true) ? $data['numero_vuelo_salida']  : null,
                'destino_vuelo_salida' => in_array($tipo, [2, 3], true) ? $data['destino_vuelo_salida'] : null,

                // Ajusta si tu tabla tiene un valor por defecto distinto
                'estado'          => 'pendiente',
            ]);
        });

        return redirect()->route('viajero.reservas.index')->with('status', 'Reserva creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        $user = Auth::user();
        abort_unless($user->isViajero(), 403);

        abort_unless($reserva->id_viajero === $user->viajero->id_viajero, 403);

        $reserva->load(['hotelGestor', 'hotelDestino', 'vehiculo', 'tipoReserva', 'creador']);

        return view('viajero.reservas.show', compact('reserva'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        $user = Auth::user();
        abort_unless($user->isViajero(), 403);

        abort_unless($reserva->id_viajero === $user->viajero->id_viajero, 403);

        // No permitir editar reservas canceladas o realizadas
        if (in_array($reserva->estado, ['cancelada', 'realizada'], true)) {
            return redirect()->route('viajero.reservas.index')->withErrors(['reserva' => 'Esta reserva ya no se puede modificar.']);
        }

        // Regla de 48h: no se puede editar si falta menos de 49h para la fecha de entrada
        $momentoReferencia = null;

        if ($reserva->fecha_entrada && $reserva->hora_entrada) {
            $momentoReferencia = Carbon::parse(
                $reserva->fecha_entrada->format('Y-m-d').' '.$reserva->hora_entrada
            );
        } elseif ($reserva->fecha_vuelo_salida && $reserva->hora_vuelo_salida) {
            $momentoReferencia = Carbon::parse(
                $reserva->fecha_vuelo_salida->format('Y-m-d').' '.$reserva->hora_vuelo_salida
            );
        }

        if ($momentoReferencia && now()->diffInHours($momentoReferencia, false) < 48) {
            return redirect()->route('viajero.reservas.index')->with('error', 'No puedes modificar una reserva con menos de 48 horas de antelación.');
        }

        $hoteles        = Hotel::activos()->get();
        $vehiculos      = Vehiculo::all();
        $tiposReserva   = TiposReserva::all();
        $maxPlazas      = $vehiculos->max('plazas');

        return view('viajero.reservas.edit', compact('reserva', 'hoteles', 'vehiculos', 'tiposReserva', 'maxPlazas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateViajeroReservaRequest $request, Reserva $reserva)
    //public function update(Request $request, string $id)
    {
        $user = Auth::user();
        abort_unless($user->isViajero(), 403);

        $viajero = $user->viajero;
        abort_unless($viajero, 403);

        abort_unless($reserva->id_viajero === $user->viajero->id_viajero, 403);

        // No permitir modificar canceladas o realizadas
        if (in_array($reserva->estado, ['cancelada', 'realizada'], true)) {
            return redirect()->route('viajero.reservas.index')->withErrors(['reserva' => 'Esta reserva ya no se puede modificar.']);
        }

        $data  = $request->validated();
        $ahora = now();

        $tipo     = (int) $data['id_tipo_reserva'];
        $esIda    = in_array($tipo, [1, 3], true); // solo ida o ida+vuelta
        $esVuelta = in_array($tipo, [2, 3], true); // solo vuelta o ida+vuelta

        /*
        |--------------------------------------------------------------------------
        | Regla 48 horas para MODIFICAR
        |--------------------------------------------------------------------------
        | Misma lógica que en store: para cada tramo (ida/vuelta) debe haber
        | al menos 48h entre ahora y la fecha+hora del vuelo.
        */

        // IDA
        if ($esIda && !empty($data['fecha_entrada']) && !empty($data['hora_entrada'])) {
            $momentoIda = Carbon::parse($data['fecha_entrada'] . ' ' . $data['hora_entrada']);
            $diffIda    = $ahora->diffInHours($momentoIda, false);

            if ($diffIda < 48) {
                return back()->withErrors(['fecha_entrada' => 'Las modificaciones deben realizarse con al menos 48 horas de antelación para la ida.']) ->withInput();
            }
        }

        // VUELTA
        if ($esVuelta && !empty($data['fecha_vuelo_salida']) && !empty($data['hora_vuelo_salida'])) {
            $momentoVuelta = Carbon::parse($data['fecha_vuelo_salida'] . ' ' . $data['hora_vuelo_salida']);
            $diffVuelta    = $ahora->diffInHours($momentoVuelta, false);

            if ($diffVuelta < 48) {
                return back()->withErrors(['fecha_vuelo_salida' => 'Las modificaciones deben realizarse con al menos 48 horas de antelación para la vuelta.'])->withInput();
            }
        }

        // (Opcional) Si es ida+vuelta, comprobar que la vuelta es posterior a la ida
        if ($esIda && $esVuelta
            && !empty($data['fecha_entrada']) && !empty($data['hora_entrada'])
            && !empty($data['fecha_vuelo_salida']) && !empty($data['hora_vuelo_salida'])
        ) {
            $momentoIda    = Carbon::parse($data['fecha_entrada'] . ' ' . $data['hora_entrada']);
            $momentoVuelta = Carbon::parse($data['fecha_vuelo_salida'] . ' ' . $data['hora_vuelo_salida']);

            if ($momentoVuelta->lessThanOrEqualTo($momentoIda)) {
                return back()->withErrors(['fecha_vuelo_salida' => 'En reservas de ida y vuelta, la fecha y hora de vuelta deben ser posteriores a la ida.'])->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Capacidad del vehículo
        |--------------------------------------------------------------------------
        */
        $vehiculo = Vehiculo::findOrFail($data['id_vehiculo']);

        if ($data['num_viajeros'] > $vehiculo->plazas) {
            return back()->withErrors(['num_viajeros' => 'El número de viajeros no puede superar las plazas del vehículo seleccionado.'])->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Actualización en transacción
        |--------------------------------------------------------------------------
        */
        DB::transaction(function () use ($reserva, $viajero, $data, $tipo) {

            // Campos comunes
            $reserva->id_hotel_destino  = $data['id_hotel'];
            $reserva->id_tipo_reserva   = $tipo;
            $reserva->num_viajeros      = $data['num_viajeros'];
            $reserva->id_vehiculo       = $data['id_vehiculo'];
            $reserva->fecha_modificacion = now();

            // Tramo de ida (si aplica)
            if (in_array($tipo, [1, 3], true)) {
                $reserva->fecha_entrada        = $data['fecha_entrada'];
                $reserva->hora_entrada         = $data['hora_entrada'];
                $reserva->numero_vuelo_entrada = $data['numero_vuelo_entrada'];
                $reserva->origen_vuelo_entrada = $data['origen_vuelo_entrada'];
            } else {
                // Si ya no hay ida (ej: pasa a SOLO VUELTA), limpiamos campos
                $reserva->fecha_entrada        = null;
                $reserva->hora_entrada         = null;
                $reserva->numero_vuelo_entrada = null;
                $reserva->origen_vuelo_entrada = null;
            }

            // Tramo de vuelta (si aplica)
            if (in_array($tipo, [2, 3], true)) {
                $reserva->fecha_vuelo_salida = $data['fecha_vuelo_salida'];
                $reserva->hora_vuelo_salida  = $data['hora_vuelo_salida'];
                $reserva->numero_vuelo_salida = $data['numero_vuelo_salida'] ?? null;
                $reserva->destino_vuelo_salida = $data['destino_vuelo_salida'] ?? null;
            } else {
                // Si ya no hay vuelta, limpiamos
                $reserva->fecha_vuelo_salida   = null;
                $reserva->hora_vuelo_salida    = null;
                $reserva->numero_vuelo_salida  = null;
                $reserva->destino_vuelo_salida = null;
            }

            $reserva->save();
        });

        return redirect()->route('viajero.reservas.index')->with('status', 'Reserva actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        $user = Auth::user();
        abort_unless($user->isViajero(), 403);

        abort_unless($reserva->id_viajero === $user->viajero->id_viajero, 403);

        if ($reserva->fecha_entrada->diffInHours(now()) < 48) {
            return redirect()->route('viajero.reservas.index')->with('error', 'No puedes cancelar una reserva con menos de 48 horas de antelación.');
        }

        DB::transaction(function () use ($reserva, $user) {
        // En lugar de borrar la fila, marcamos como cancelada
            $reserva->estado = 'cancelada';
            $reserva->fecha_modificacion = now();
            $reserva->id_modificador = $user->id;
            $reserva->save();
         });
         
        return redirect()->route('viajero.reservas.index')->with('status', 'Rerserva cancelada correctamente.');
    }
}
