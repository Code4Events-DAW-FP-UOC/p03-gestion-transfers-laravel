<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Precio;
use App\Models\Reserva;
use App\Models\TiposReserva;
use App\Models\Vehiculo;
use App\Models\Viajero;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $reservas = Reserva::with(['creador', 'tipoReserva', 'hotelDestino', 'vehiculo', 'viajero'])
            ->orderByDesc('fecha_reserva')
            ->paginate(20);

        return view('admin.reservas.index', compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        // Viajero: elegimos entre los viajeros existentes (con su user)
        $viajeros = Viajero::with('user')
            ->orderBy('nombre')
            ->orderBy('apellido1')
            ->get();

        $hoteles      = Hotel::activos()->orderBy('nombre')->get();
        $vehiculos    = Vehiculo::orderBy('descripcion')->get();
        $tiposReserva = TiposReserva::orderBy('id_tipo_reserva')->get();
        $maxPlazas    = $vehiculos->max('plazas') ?? 1;

        // reserva vacía para el formulario
        $reserva = new Reserva([
            'estado' => 'pendiente',
        ]);

        return view('admin.reservas.create', compact(
            'reserva',
            'viajeros',
            'hoteles',
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
        abort_unless($user && $user->isAdmin(), 403);

        $data = $this->validateReserva($request);

        $tipo     = (int) $data['id_tipo_reserva'];
        $esIda    = in_array($tipo, [1, 3], true);
        $esVuelta = in_array($tipo, [2, 3], true);

        $ahora = now();

        // -----------------------------
        // Comprobaciones de fechas
        // -----------------------------
        $momentoIda = null;
        $momentoVuelta = null;

        if ($esIda && !empty($data['fecha_entrada']) && !empty($data['hora_entrada'])) {
            $momentoIda = Carbon::parse($data['fecha_entrada'] . ' ' . $data['hora_entrada']);
        }

        if ($esVuelta && !empty($data['fecha_vuelo_salida']) && !empty($data['hora_vuelo_salida'])) {
            $momentoVuelta = Carbon::parse($data['fecha_vuelo_salida'] . ' ' . $data['hora_vuelo_salida']);
        }

        // No permitir fechas anteriores a hoy
        if ($momentoIda && $momentoIda->isBefore(Carbon::today())) {
            return back()
                ->withErrors(['fecha_entrada' => 'La fecha de ida no puede ser anterior a hoy.'])
                ->withInput();
        }

        if ($momentoVuelta && $momentoVuelta->isBefore(Carbon::today())) {
            return back()
                ->withErrors(['fecha_vuelo_salida' => 'La fecha de vuelta no puede ser anterior a hoy.'])
                ->withInput();
        }

        // Si hay ida y vuelta, la vuelta debe ser posterior a la ida
        if ($momentoIda && $momentoVuelta && $momentoVuelta->lessThanOrEqualTo($momentoIda)) {
            return back()
                ->withErrors(['fecha_vuelo_salida' => 'La fecha y hora de vuelta deben ser posteriores a la ida.'])
                ->withInput();
        }

        // -----------------------------
        // Capacidad del vehículo
        // -----------------------------
        $vehiculo = Vehiculo::findOrFail($data['id_vehiculo']);

        if ($data['num_viajeros'] > $vehiculo->plazas) {
            return back()
                ->withErrors(['num_viajeros' => 'El número de viajeros no puede superar las plazas del vehículo seleccionado.'])
                ->withInput();
        }

        // -----------------------------
        // Buscar precio (hotel + vehículo)
        // -----------------------------
        $precio = Precio::where('id_hotel', $data['id_hotel'])
            ->where('id_vehiculo', $data['id_vehiculo'])
            ->first();

        if (! $precio) {
            return back()
                ->withErrors(['id_vehiculo' => 'No hay un precio configurado para este hotel y vehículo.'])
                ->withInput();
        }

        // -----------------------------
        // Crear reserva
        // -----------------------------
        DB::transaction(function () use ($data, $user, $precio, $tipo, $esIda, $esVuelta) {
            $localizador = Reserva::generarLocalizador();

            Reserva::create([
                'localizador'        => $localizador,
                'id_viajero'         => $data['id_viajero'],
                'id_creador'         => $user->id,
                'id_tipo_reserva'    => $tipo,
                'id_precio'          => $precio->id_precio,
                'fecha_reserva'      => now(),
                'fecha_modificacion' => now(),

                'id_hotel_destino'   => $data['id_hotel'],
                'num_viajeros'       => $data['num_viajeros'],
                'id_vehiculo'        => $data['id_vehiculo'],

                // Tramo ida
                'fecha_entrada'        => $esIda ? $data['fecha_entrada']        : null,
                'hora_entrada'         => $esIda ? $data['hora_entrada']         : null,
                'numero_vuelo_entrada' => $esIda ? $data['numero_vuelo_entrada'] : null,
                'origen_vuelo_entrada' => $esIda ? $data['origen_vuelo_entrada'] : null,

                // Tramo vuelta
                'fecha_vuelo_salida'   => $esVuelta ? $data['fecha_vuelo_salida']   : null,
                'hora_vuelo_salida'    => $esVuelta ? $data['hora_vuelo_salida']    : null,
                'numero_vuelo_salida'  => $esVuelta ? $data['numero_vuelo_salida']  : null,
                'destino_vuelo_salida' => $esVuelta ? $data['destino_vuelo_salida'] : null,

                'estado'              => $data['estado'] ?? 'pendiente',
            ]);
        });

        return redirect()
            ->route('admin.reservas.index')
            ->with('status', 'Reserva creada correctamente.');
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
        abort_unless($user && $user->isAdmin(), 403);

        $reserva->load(['viajero', 'hotelDestino', 'vehiculo', 'tipoReserva']);

        $viajeros = Viajero::with('user')
            ->orderBy('nombre')
            ->orderBy('apellido1')
            ->get();

        $hoteles      = Hotel::activos()->orderBy('nombre')->get();
        $vehiculos    = Vehiculo::orderBy('descripcion')->get();
        $tiposReserva = TiposReserva::orderBy('id_tipo_reserva')->get();
        $maxPlazas    = $vehiculos->max('plazas') ?? 1;

        return view('admin.reservas.edit', compact(
            'reserva',
            'viajeros',
            'hoteles',
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
        abort_unless($user && $user->isAdmin(), 403);

        $data = $this->validateReserva($request);

        $tipo     = (int) $data['id_tipo_reserva'];
        $esIda    = in_array($tipo, [1, 3], true);
        $esVuelta = in_array($tipo, [2, 3], true);

        $momentoIda = null;
        $momentoVuelta = null;

        if ($esIda && !empty($data['fecha_entrada']) && !empty($data['hora_entrada'])) {
            $momentoIda = Carbon::parse($data['fecha_entrada'] . ' ' . $data['hora_entrada']);
        }

        if ($esVuelta && !empty($data['fecha_vuelo_salida']) && !empty($data['hora_vuelo_salida'])) {
            $momentoVuelta = Carbon::parse($data['fecha_vuelo_salida'] . ' ' . $data['hora_vuelo_salida']);
        }

        if ($momentoIda && $momentoIda->isBefore(Carbon::today())) {
            return back()
                ->withErrors(['fecha_entrada' => 'La fecha de ida no puede ser anterior a hoy.'])
                ->withInput();
        }

        if ($momentoVuelta && $momentoVuelta->isBefore(Carbon::today())) {
            return back()
                ->withErrors(['fecha_vuelo_salida' => 'La fecha de vuelta no puede ser anterior a hoy.'])
                ->withInput();
        }

        if ($momentoIda && $momentoVuelta && $momentoVuelta->lessThanOrEqualTo($momentoIda)) {
            return back()
                ->withErrors(['fecha_vuelo_salida' => 'La fecha y hora de vuelta deben ser posteriores a la ida.'])
                ->withInput();
        }

        // Capacidad vehículo
        $vehiculo = Vehiculo::findOrFail($data['id_vehiculo']);

        if ($data['num_viajeros'] > $vehiculo->plazas) {
            return back()
                ->withErrors(['num_viajeros' => 'El número de viajeros no puede superar las plazas del vehículo seleccionado.'])
                ->withInput();
        }

        // Precio
        $precio = Precio::where('id_hotel', $data['id_hotel'])
            ->where('id_vehiculo', $data['id_vehiculo'])
            ->first();

        if (! $precio) {
            return back()
                ->withErrors(['id_vehiculo' => 'No hay un precio configurado para este hotel y vehículo.'])
                ->withInput();
        }

        DB::transaction(function () use ($reserva, $data, $precio, $tipo, $esIda, $esVuelta) {
            $reserva->id_viajero         = $data['id_viajero'];
            $reserva->id_tipo_reserva    = $tipo;
            $reserva->id_precio          = $precio->id_precio;
            $reserva->fecha_modificacion = now();

            $reserva->id_hotel_destino   = $data['id_hotel'];
            $reserva->num_viajeros       = $data['num_viajeros'];
            $reserva->id_vehiculo        = $data['id_vehiculo'];

            // Ida
            if ($esIda) {
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

            // Vuelta
            if ($esVuelta) {
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

            $reserva->estado = $data['estado'] ?? $reserva->estado;

            $reserva->save();
        });

        return redirect()
            ->route('admin.reservas.index')
            ->with('status', 'Reserva actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        // No permitir cancelar si ya está realizada o cancelada
        if (in_array($reserva->estado, ['realizada', 'cancelada'], true)) {
            return redirect()
                ->route('admin.reservas.index')
                ->with('error', 'Esta reserva ya no se puede cancelar.');
        }

        $reserva->estado = 'cancelada';
        $reserva->fecha_modificacion = now();
        $reserva->save();

        return redirect()
            ->route('admin.reservas.index')
            ->with('status', 'Reserva cancelada correctamente.');
    }

    /**
     * Validación común para crear / actualizar
     */
    protected function validateReserva(Request $request): array
    {
        return $request->validate([
            'id_viajero'       => ['required', 'integer'],
            'id_hotel'         => ['required', 'integer'],
            'id_vehiculo'      => ['required', 'integer'],
            'id_tipo_reserva'  => ['required', 'integer'],
            'num_viajeros'     => ['required', 'integer', 'min:1'],

            'fecha_entrada'        => ['nullable', 'date'],
            // Acepta  HH:MM  o  HH:MM:SS
            'hora_entrada'         => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'numero_vuelo_entrada' => ['nullable', 'string', 'max:50'],
            'origen_vuelo_entrada' => ['nullable', 'string', 'max:100'],

            'fecha_vuelo_salida'   => ['nullable', 'date'],
            'hora_vuelo_salida'    => ['nullable', 'date_format:H:i'],
            'numero_vuelo_salida'  => ['nullable', 'string', 'max:50'],
            'destino_vuelo_salida' => ['nullable', 'string', 'max:100'],

            'estado' => ['nullable', 'in:pendiente,confirmada,realizada,cancelada'],
        ]);
    }
}
