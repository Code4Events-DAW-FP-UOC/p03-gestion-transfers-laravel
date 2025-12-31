<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminZonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $zonas = Zona::orderBy('descripcion')->paginate(20);

        return view('admin.zonas.index', compact('zonas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $zona = new Zona();

        return view('admin.zonas.create', compact('zona'));
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
            'codigo'      => ['required', 'string', 'max:50', 'unique:p3_transfer_zonas,codigo'],
        ]);

        Zona::create($data);

        return redirect()
            ->route('admin.zonas.index')
            ->with('status', 'Zona creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Zona $zona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zona $zona)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        return view('admin.zonas.edit', compact('zona'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Zona $zona)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $data = $request->validate([
            'descripcion' => ['required', 'string', 'max:255'],
            'codigo'      => [
                'required',
                'string',
                'max:50',
                'unique:p3_transfer_zonas,codigo,' . $zona->id_zona . ',id_zona',
            ],
        ]);

        $zona->update($data);

        return redirect()
            ->route('admin.zonas.index')
            ->with('status', 'Zona actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zona $zona)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        // No se puede eliminar si hay reservas que pasan por hoteles de esta zona
        $enUso = Reserva::whereHas('hotelDestino', function ($q) use ($zona) {
            $q->where('id_zona', $zona->id_zona);
        })->exists();

        if ($enUso) {
            return redirect()
                ->route('admin.zonas.index')
                ->withErrors([
                    'zonas' => 'No se puede eliminar esta zona porque hay reservas asociadas a hoteles de esta zona.',
                ]);
        }

        // También puedes evitar borrar si existen hoteles directamente en la zona
        if ($zona->hoteles()->exists()) {
            return redirect()
                ->route('admin.zonas.index')
                ->withErrors([
                    'zonas' => 'No se puede eliminar esta zona porque hay hoteles asociados a ella.',
                ]);
        }

        $zona->delete();

        return redirect()
            ->route('admin.zonas.index')
            ->with('status', 'Zona eliminada correctamente.');
    }
}
