<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'descripcion' => 'required|string|max:255',
            'codigo'      => 'required|string|unique:p3_transfer_zonas,codigo',
        ], [
            'codigo.unique' => 'El código de zona ya existe en el sistema. Por favor, use uno diferente.',
            'codigo.required' => 'El código es obligatorio.',
        ]);

        Zona::create($data);
        return redirect()->route('admin.configuracion.index')->with('status', 'Zona creada con éxito');
    }

    public function update(Request $request, Zona $zona)
    {
        $data = $request->validate([
            'descripcion' => 'required|string|max:255',
            'codigo'      => 'required|string|unique:p3_transfer_zonas,codigo,' . $zona->id_zona . ',id_zona',
        ], [
            'codigo.unique' => 'El código de zona ya existe en el sistema. Por favor, use uno diferente.',
            'codigo.required' => 'El código es obligatorio.',
        ]);

        $zona->update($data);
        return redirect()->route('admin.configuracion.index')->with('status', 'Zona actualizada');
    }

    public function destroy(Zona $zona)
    {
        if ($zona->hoteles()->exists()) {
            return back()->with('error', "No se puede eliminar la zona porque tiene hoteles asociadas.");
        }
        $zona->delete();
        return redirect()->route('admin.configuracion.index')->with('status', 'Zona eliminada');
    }

    public function create()
    {
        return view('admin.zonas.create');
    }

    public function edit(Zona $zona)
    {

        return view('admin.zonas.edit', compact('zona'));
    }
}