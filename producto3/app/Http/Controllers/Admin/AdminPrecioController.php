<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Precio;
use App\Models\Reserva;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPrecioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $precios = Precio::with(['hotel', 'vehiculo'])->get();
        //$precios = Precio::with(['hotel', 'vehiculo'])->orderByHas('hotel', 'nombre')      // si tienes scope helper; si no, ver más abajo->paginate(20);

        return view('admin.precios.index', compact('precios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $precio    = new Precio();
        $hoteles   = Hotel::activos()->orderBy('nombre')->get();
        $vehiculos = Vehiculo::orderBy('descripcion')->get();

        return view('admin.precios.create', compact('precio', 'hoteles', 'vehiculos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $data = $request->validate([
            'id_hotel'    => ['required', 'integer', 'exists:p3_transfer_hoteles,id_hotel'],
            'id_vehiculo' => ['required', 'integer', 'exists:p3_transfer_vehiculos,id_vehiculo'],
            'precio'      => ['required', 'numeric', 'min:0'],
        ]);

        // Comprobar unicidad (hotel + vehículo) sin usar el validador "unique" con tabla
        $yaExiste = Precio::where('id_hotel', $data['id_hotel'])
            ->where('id_vehiculo', $data['id_vehiculo'])
            ->exists();

        if ($yaExiste) {
            return back()
                ->withErrors([
                    'id_vehiculo' => 'Ya existe un precio para esta combinación de hotel y vehículo.',
                ])
                ->withInput();
        }

        Precio::create($data);

        return redirect()
            ->route('admin.precios.index')
            ->with('status', 'Precio creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Precio $precio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Precio $precio)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $hoteles   = Hotel::activos()->orderBy('nombre')->get();
        $vehiculos = Vehiculo::orderBy('descripcion')->get();

        return view('admin.precios.edit', compact('precio', 'hoteles', 'vehiculos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Precio $precio)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $data = $request->validate([
            'id_hotel'    => ['required', 'integer', 'exists:p3_transfer_hoteles,id_hotel'],
            'id_vehiculo' => ['required', 'integer', 'exists:p3_transfer_vehiculos,id_vehiculo'],
            'precio'      => ['required', 'numeric', 'min:0'],
        ]);

        // Unicidad combo hotel+vehículo excluyendo el propio precio
        $yaExiste = Precio::where('id_hotel', $data['id_hotel'])
            ->where('id_vehiculo', $data['id_vehiculo'])
            ->where('id_precio', '!=', $precio->id_precio)
            ->exists();

        if ($yaExiste) {
            return back()
                ->withErrors([
                    'id_vehiculo' => 'Ya existe un precio para esta combinación de hotel y vehículo.',
                ])
                ->withInput();
        }

        $precio->update($data);

        return redirect()
            ->route('admin.precios.index')
            ->with('status', 'Precio actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Precio $precio)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        // Solo borrar si no hay reservas con este id_precio
        $enUso = Reserva::where('id_precio', $precio->id_precio)->exists();

        if ($enUso) {
            return redirect()
                ->route('admin.precios.index')
                ->withErrors([
                    'precios' => 'No se puede eliminar este precio porque está vinculado a alguna reserva.',
                ]);
        }

        $precio->delete();

        return redirect()
            ->route('admin.precios.index')
            ->with('status', 'Precio eliminado correctamente.');
    }
}
