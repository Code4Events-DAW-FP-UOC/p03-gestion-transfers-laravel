<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $vehiculos = Vehiculo::orderBy('descripcion')->paginate(20)->withQueryString();

        return view('admin.vehiculos.index', compact('vehiculos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $vehiculo = new Vehiculo(['activo' => true,]);

        return view('admin.vehiculos.create', compact('vehiculo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $data = $request->validate([
            'descripcion' => ['required', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'max:255'],
            'matricula'   => ['required', 'string', 'max:50'],
            'plazas'      => ['required', 'integer', 'min:1', 'max:99'],
            'activo'      => ['nullable', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');

        Vehiculo::create($data);

        return redirect()->route('admin.vehiculos.index')->with('status', 'Vehículo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehiculo $vehiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehiculo $vehiculo)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        return view('admin.vehiculos.edit', compact('vehiculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $data = $request->validate([
            'descripcion' => ['required', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'max:255'],
            'matricula'   => ['required', 'string', 'max:50'],
            'plazas'      => ['required', 'integer', 'min:1', 'max:99'],
            'activo'      => ['nullable', 'boolean'],
        ]);

        $data['activo'] = $request->boolean('activo');

        $vehiculo->update($data);

        return redirect()
            ->route('admin.vehiculos.index')
            ->with('status', 'Vehículo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehiculo $vehiculo)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $vehiculo->activo = false;
        $vehiculo->save();

        return redirect()
            ->route('admin.vehiculos.index')
            ->with('status', 'Vehículo desactivado correctamente.');
    }
}
