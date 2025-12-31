<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\TiposReserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminTiposReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $tiposReservas = TiposReserva::orderBy('descripcion')->paginate(20);

        return view('admin.tiposReserva.index', compact('tiposReservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $tipoReserva = new TiposReserva();

        return view('admin.tiposReserva.create', compact('tipoReserva'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
    abort_unless($user && $user->isAdmin(), 403);

    $tabla = (new TiposReserva)->getTable();

    $data = $request->validate([
        'descripcion' => ['required', 'string', 'max:255'],
        'codigo' => [
            'required',
            'string',
            'max:50',
            Rule::unique($tabla, 'codigo'),
        ],
    ]);

    TiposReserva::create($data);

    return redirect()
        ->route('admin.tiposReserva.index')
        ->with('status', 'Tipo de reserva creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TiposReserva $tiposReserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TiposReserva $tiposReserva)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $tipoReserva = $tiposReserva;

        return view('admin.tiposReserva.edit', compact('tipoReserva'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TiposReserva $tipoReserva)
    {
        $user = Auth::user();
    abort_unless($user && $user->isAdmin(), 403);

    $tabla = (new TiposReserva)->getTable();

    $data = $request->validate([
        'descripcion' => ['required', 'string', 'max:255'],
        'codigo' => [
            'required',
            'string',
            'max:50',
            Rule::unique($tabla, 'codigo')
                ->ignore($tipoReserva->id_tipo_reserva, 'id_tipo_reserva'),
        ],
    ]);

    $tipoReserva->update($data);

    return redirect()
        ->route('admin.tiposReserva.index')
        ->with('status', 'Tipo de reserva actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TiposReserva $tiposReserva)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        // No se puede borrar si hay reservas usando este tipo
        $enUso = Reserva::where('id_tipo_reserva', $tiposReserva->id_tipo_reserva)->exists();

        if ($enUso) {
            return redirect()->route('admin.tiposReserva.index')->withErrors(['tiposReserva' => 'No se puede eliminar este tipo de reserva porque está siendo utilizado en alguna reserva.',]);
        }

        $tiposReserva->delete();

        return redirect()->route('admin.tiposReserva.index')->with('status', 'Tipo de reserva eliminado correctamente.');
    }
}
