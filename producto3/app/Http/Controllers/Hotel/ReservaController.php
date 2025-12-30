<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Vehiculo;
use App\Models\Viajero;
use App\Models\Precio;
use App\Models\TiposReserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function index()
    {
        $hotelId = Auth::user()->hotel->id_hotel;

        $reservas = Reserva::with(['viajero.user', 'hotelDestino', 'vehiculo', 'tipoReserva'])
            ->where('id_hotel', $hotelId)
            ->orderBy('fecha_reserva', 'desc')
            ->paginate(10);

        return view('hotel.reservas.index', compact('reservas'));
    }

    public function create()
    {
        $hotelId = Auth::user()->hotel->id_hotel;

        $vehiculos = Vehiculo::whereHas('precios', function($query) use ($hotelId) {
            $query->where('id_hotel', $hotelId);
        })->get();
        $tiposReserva = TiposReserva::all();
        $viajeros = Viajero::with('user')->get();
        return view('hotel.reservas.create', compact('tiposReserva', 'vehiculos', 'viajeros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_viajero'           => 'required|exists:p3_transfer_viajeros,id_viajero',
            'id_tipo_reserva'      => 'required|exists:p3_transfer_tipos_reservas,id_tipo_reserva',
            
            // Datos de Llegada (Requeridos si tipo es 1 o 3)
            'fecha_entrada'        => 'required_if:id_tipo_reserva,1,3|nullable|date|after_or_equal:today + 2 days',
            'hora_entrada'         => 'required_if:id_tipo_reserva,1,3|nullable',
            'numero_vuelo_entrada' => 'required_if:id_tipo_reserva,1,3|nullable|string|max:20',
            'origen_vuelo_entrada' => 'required_if:id_tipo_reserva,1,3|nullable|string|max:100',

            // Datos de Salida (Requeridos si tipo es 2 o 3)
            'fecha_vuelo_salida'   => 'required_if:id_tipo_reserva,2,3|nullable|date|after_or_equal:today + 2 days',
            'hora_vuelo_salida'    => 'required_if:id_tipo_reserva,2,3|nullable',
            'numero_vuelo_salida'  => 'required_if:id_tipo_reserva,2,3|nullable|string|max:20',
            'destino_vuelo_salida' => 'required_if:id_tipo_reserva,2,3|nullable|string|max:100',

            // Campos comunes
            'id_hotel'             => 'required|exists:p3_transfer_hoteles,id_hotel',
            'id_vehiculo'          => 'required|exists:p3_transfer_vehiculos,id_vehiculo',
            'num_viajeros'         => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $tarifa = \App\Models\Precio::where('id_hotel', $request->id_hotel)
                                ->where('id_vehiculo', $request->id_vehiculo)
                                ->first();

        if (!$tarifa) {
            return back()->withInput()->withErrors(['id_vehiculo' => 'No hay una tarifa configurada para este hotel y vehículo.']);
        }

        $reserva = new Reserva();
        $reserva->localizador = Reserva::generarLocalizador();
        $reserva->id_creador = auth()->id();
        $reserva->fecha_reserva = now();

        $reserva->id_viajero = $request->id_viajero;
        $reserva->id_tipo_reserva = $request->id_tipo_reserva;
        $reserva->id_hotel = $request->id_hotel;
        $reserva->id_hotel_destino = $request->id_hotel;
        $reserva->id_vehiculo = $request->id_vehiculo;
        $reserva->id_precio = $tarifa->id_precio;

        $reserva->estado = 'pendiente';
        $reserva->num_viajeros = $request->num_viajeros;

        if (in_array($request->id_tipo_reserva, [1, 3])) {
            $reserva->fecha_entrada = $request->fecha_entrada;
            $reserva->hora_entrada = $request->hora_entrada;
            $reserva->numero_vuelo_entrada = $request->numero_vuelo_entrada;
            $reserva->origen_vuelo_entrada = $request->origen_vuelo_entrada;
        }

        if (in_array($request->id_tipo_reserva, [2, 3])) {
            $reserva->fecha_vuelo_salida = $request->fecha_vuelo_salida;
            $reserva->hora_vuelo_salida = $request->hora_vuelo_salida;
            $reserva->numero_vuelo_salida = $request->numero_vuelo_salida;
            $reserva->destino_vuelo_salida = $request->destino_vuelo_salida;
        }

            $reserva->save();

            DB::commit();

            return redirect()->route('hotel.reservas.index')->with('success', 'Reserva creada con éxito');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la reserva: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'id_viajero'      => 'required|exists:p3_transfer_viajeros,id_viajero',
            'id_tipo_reserva' => 'required',
            'id_vehiculo'     => 'required|exists:p3_transfer_vehiculos,id_vehiculo',
            'num_viajeros'    => 'required|integer|min:1',
            'fecha_entrada'   => 'nullable|date',
            'fecha_vuelo_salida' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $tarifa = \App\Models\Precio::where('id_hotel', $reserva->id_hotel)
                            ->where('id_vehiculo', $request->id_vehiculo)
                            ->first();

            if (!$tarifa) {

                return back()->withErrors(['id_vehiculo' => 'No hay precio definido para este vehículo en el hotel: ' . ($reserva->hotelGestor->nombre ?? 'ID '.$reserva->id_hotel)])->withInput();
            }

            $reserva->id_viajero      = $request->id_viajero;
            $reserva->id_tipo_reserva = $request->id_tipo_reserva;
            $reserva->id_vehiculo     = $request->id_vehiculo;
            $reserva->num_viajeros    = $request->num_viajeros;
            $reserva->id_precio       = $tarifa->id_precio;


            $reserva->fecha_entrada = $request->fecha_entrada;
            $reserva->hora_entrada  = $request->hora_entrada;
            $reserva->numero_vuelo_entrada = $request->numero_vuelo_entrada;
            $reserva->origen_vuelo_entrada = $request->origen_vuelo_entrada;

            $reserva->fecha_vuelo_salida = $request->fecha_vuelo_salida;
            $reserva->hora_vuelo_salida  = $request->hora_vuelo_salida;
            $reserva->numero_vuelo_salida = $request->numero_vuelo_salida;
            $reserva->destino_vuelo_salida = $request->destino_vuelo_salida;

            $reserva->save();

            DB::commit();

            return redirect()->route('hotel.reservas.index')
                             ->with('success', 'Reserva #' . $reserva->localizador . ' actualizada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Reserva $reserva)
    {
        $reserva->load('hotelGestor.precios');
        return view('hotel.reservas.edit', [
            'reserva'      => $reserva,
            'viajeros'     => \App\Models\Viajero::orderBy('nombre')->get(),
            'tiposReserva' => \App\Models\TiposReserva::all(),
            'vehiculos'    => \App\Models\Vehiculo::all(),
            'hotel'        => \App\Models\Hotel::with('precios')->find($reserva->id_hotel),
            'maxPlazas'    => \App\Models\Vehiculo::max('plazas')
        ]);
    }


    public function destroy(Reserva $reserva)
    {
        if ($reserva->id_hotel !== Auth::user()->hotel->id_hotel) {
            abort(403, 'Acción no autorizada.');
        }

        if ($reserva->estado === 'realizada') {
            return back()->with('error', 'No se puede cancelar una reserva que ya ha sido realizada.');
        }

        $reserva->update(['estado' => 'cancelada']);

        return redirect()->route('hotel.reservas.index')
                         ->with('status', 'La reserva ' . $reserva->localizador . ' ha sido cancelada.');
    }

    public function show(Reserva $reserva)
    {
        if ($reserva->id_hotel !== Auth::user()->hotel->id_hotel) {
            abort(403);
        }
        return view('hotel.reservas.show', compact('reserva'));
    }
}