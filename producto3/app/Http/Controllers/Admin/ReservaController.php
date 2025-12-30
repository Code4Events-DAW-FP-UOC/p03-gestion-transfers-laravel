<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Hotel;
use App\Models\Vehiculo;
use App\Models\TiposReserva;
use App\Models\Precio;
use App\Models\Viajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $query = Reserva::with(['viajero.user', 'hotelDestino', 'vehiculo', 'tipoReserva', 'precio']);

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $reservas = $query->orderByDesc('fecha_reserva')->paginate(20);

        return view('admin.reservas.index', compact('reservas'));
    }

    public function show(Reserva $reserva)
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        $reserva->load(['viajero.user', 'hotelDestino', 'vehiculo', 'tipoReserva', 'precio']);
        
        return view('admin.reservas.show', compact('reserva'));
    }

    public function edit(Reserva $reserva)
    {

        $tiposReserva = TiposReserva::all();
        $hoteles = Hotel::whereHas('user', function($query) {
            $query->where('activo', 1);
        })->get();
        $vehiculos = Vehiculo::all();
        $precios = Precio::with(['hotel', 'vehiculo'])->get();

        $maxPlazas = $vehiculos->max('plazas');

        return view('admin.reservas.edit', compact(
            'reserva', 
            'tiposReserva', 
            'hoteles', 
            'vehiculos',
            'precios',
            'maxPlazas'
        ));
    }

    public function update(Request $request, Reserva $reserva)
    {
        // 1. Validación de los datos
        $validated = $request->validate([
            // Campos de administración
            'estado'                => 'required|in:pendiente,realizada,cancelada',

            // Campos de la reserva (igual que el viajero)
            'id_precio'       => 'required|exists:p3_transfer_precios,id_precio',
            'id_tipo_reserva'       => 'required|exists:p3_transfer_tipos_reservas,id_tipo_reserva',
            'id_hotel'              => 'required|exists:p3_transfer_hoteles,id_hotel',
            'num_viajeros'          => 'required|integer|min:1',
            'id_vehiculo'           => 'required|exists:p3_transfer_vehiculos,id_vehiculo',

            // Datos de Llegada (Ida)
            'fecha_entrada'         => 'nullable|date',
            'hora_entrada'          => 'nullable',
            'numero_vuelo_entrada'  => 'nullable|string|max:20',
            'origen_vuelo_entrada'  => 'nullable|string|max:100',

            // Datos de Salida (Vuelta)
            'fecha_vuelo_salida'    => 'nullable|date',
            'hora_vuelo_salida'     => 'nullable',
            'numero_vuelo_salida'   => 'nullable|string|max:20',
            'destino_vuelo_salida'  => 'nullable|string|max:100',
        ]);

        $tarifa = Precio::where('id_hotel', $request->id_hotel)
                    ->where('id_vehiculo', $request->id_vehiculo)
                    ->first();

        if (!$tarifa) {
            return back()->withErrors(['id_vehiculo' => 'Este vehículo no tiene precio configurado para el hotel seleccionado.'])->withInput();
        }

        // 2. Asignación de valores
        $reserva->estado = $request->estado;
        $reserva->id_precio = $request->id_precio;
        $reserva->id_tipo_reserva = $request->id_tipo_reserva;
        $reserva->id_hotel_destino = $request->id_hotel;
        $reserva->num_viajeros = $request->num_viajeros;
        $reserva->id_vehiculo = $request->id_vehiculo;

        // Datos de vuelo ida
        $reserva->fecha_entrada = $request->fecha_entrada;
        $reserva->hora_entrada = $request->hora_entrada;
        $reserva->numero_vuelo_entrada = $request->numero_vuelo_entrada;
        $reserva->origen_vuelo_entrada = $request->origen_vuelo_entrada;

        // Datos de vuelo vuelta
        $reserva->fecha_vuelo_salida = $request->fecha_vuelo_salida;
        $reserva->hora_vuelo_salida = $request->hora_vuelo_salida;
        $reserva->numero_vuelo_salida = $request->numero_vuelo_salida;
        $reserva->destino_vuelo_salida = $request->destino_vuelo_salida;

        // 3. Guardar cambios
        $reserva->save();

        // 4. Redirección con mensaje de éxito
        return redirect()->route('admin.reservas.index')
            ->with('success', "La reserva {$reserva->localizador} ha sido actualizada correctamente.");
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->update(['estado' => 'cancelada']);

        return redirect()->route('admin.reservas.index')->with('success', 'Cancelada correctamente');
    }

    public function create()
    {
        $tiposReserva = TiposReserva::all();
        $hoteles = Hotel::whereHas('user', function($query) {
            $query->where('activo', 1);
        })->with('precios')->get();
        $vehiculos = Vehiculo::all();
        $maxPlazas = $vehiculos->max('plazas');

        $viajeros = Viajero::orderBy('nombre', 'asc')->get(); 

        return view('admin.reservas.create', compact(
            'tiposReserva', 
            'hoteles', 
            'vehiculos', 
            'maxPlazas', 
            'viajeros'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
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

        return redirect()->route('admin.reservas.index')->with('success', 'Reserva creada con éxito');
    }
}