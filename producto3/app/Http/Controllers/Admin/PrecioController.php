<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Precio;
use App\Models\Hotel;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrecioController extends Controller
{
    public function create()
    {
        $hoteles = Hotel::all();
        $vehiculos = Vehiculo::all();
        return view('admin.precios.create', compact('hoteles', 'vehiculos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_hotel'    => 'required|exists:p3_transfer_hoteles,id_hotel',
            'id_vehiculo' => 'required|exists:p3_transfer_vehiculos,id_vehiculo',
            'precio'      => 'required|numeric|min:0',
        ]);

        // Evitamos duplicados: si ya existe esa combinación, la actualizamos
        Precio::updateOrCreate(
            ['id_hotel' => $request->id_hotel, 'id_vehiculo' => $request->id_vehiculo],
            ['precio' => $request->precio]
        );

        return redirect()->route('admin.configuracion.index')->with('status', 'Tarifa configurada correctamente');
    }

    public function destroy(Precio $precio)
    {
        if ($precio->reservas()->where('estado', '!=', 'cancelada')->exists()) {
            return back()->with('error', "No se puede eliminar la tarifa porque tiene reservas asociadas.");
        }
        $precio->delete();
        return redirect()->route('admin.configuracion.index')->with('status', 'Tarifa eliminada');
    }

    public function edit(Precio $precio)
    {
        $hoteles = \App\Models\Hotel::orderBy('nombre')->get();
        $vehiculos = \App\Models\Vehiculo::orderBy('descripcion')->get();

        return view('admin.precios.edit', compact('precio', 'hoteles', 'vehiculos'));
    }

    public function update(Request $request, Precio $precio)
    {
        $data = $request->validate([
            'id_hotel'    => ['required',
                Rule::unique('p3_transfer_precios')
                    ->where(function ($query) use ($request) {
                        return $query->where('id_hotel', $request->id_hotel)
                                     ->where('id_vehiculo', $request->id_vehiculo);
                    })
                    ->ignore($precio->id_precio, 'id_precio')
            ],
            'id_vehiculo' => 'required|exists:p3_transfer_vehiculos,id_vehiculo',
            'precio'      => 'required|numeric|min:0',
        ], [
            'id_hotel.unique' => '¡Error! Ya existe una tarifa registrada para este hotel con este vehículo. No se pueden duplicar.',
        ]);

        $precio->update($data);

        return redirect()->route('admin.configuracion.index')->with('status', 'Tarifa actualizada correctamente');
    }

}