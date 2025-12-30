<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'descripcion' => 'required|string',
            'matricula'   => 'required|string|unique:p3_transfer_vehiculos',
            'plazas'      => 'required|integer|min:1',
        ], [
            'matricula.unique' => 'Esta matrícula ya está registrada en la flota.',
            'matricula.required' => 'La matrícula es obligatoria para identificar el vehículo.',
        ]);

        $data['email'] = 'temp@islatransfers.test';

        $vehiculo = Vehiculo::create($data);

        $emailReal = 'flota' . $vehiculo->id_vehiculo . '@islatransfers.test';

        $vehiculo->update(['email' => $emailReal]);
        
        return redirect()->route('admin.configuracion.index')->with('status', 'Vehículo añadido a la flota');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        if($vehiculo->precios()->exists()){
            return back()->with('error', "No se puede eliminar el vehículo porque tiene tarifas y hoteles asociados.");
        }
        $vehiculo->delete();
        return redirect()->route('admin.configuracion.index')->with('status', 'Vehículo eliminado');
    }

    public function create()
    {
        return view('admin.vehiculos.create');
    }

    public function edit(Vehiculo $vehiculo)
    {
        return view('admin.vehiculos.edit', compact('vehiculo'));
    }
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $data = $request->validate([
            'descripcion' => 'required|string',
            'matricula'   => 'required|string|unique:p3_transfer_vehiculos,matricula,' . $vehiculo->id_vehiculo . ',id_vehiculo',
            'plazas'      => 'required|integer|min:1',
        ]);

        $vehiculo->update($data);

        return redirect()->route('admin.configuracion.index')->with('status', 'Vehículo actualizado');
    }
}