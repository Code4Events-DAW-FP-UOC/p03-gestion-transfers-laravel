<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AdminHotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $search = $request->input('search');
        $estado = $request->input('estado');
        $zona = $request->input('zona');

        $query = Hotel::with('zona');

        // Filtro por texto de hotel
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', '%'.$search.'%');
            });
        }
        
        // Filtro por estado
        if ($estado !== null && $estado !== '') {
            $query->where('activo', (bool) $estado);
        }

        // Filtro por descripción de ZONA
        if ($zona) {
            $query->whereHas('zona', function ($q) use ($zona) {
                $q->where('descripcion', 'like', '%'.$zona.'%');
            });
        }

        $hoteles = $query->orderBy('nombre')->paginate(15)->withQueryString();

        return view('admin.hoteles.index', compact('hoteles', 'search', 'estado', 'zona'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $zonas = Zona::orderBy('descripcion')->get();

        return view('admin.hoteles.create', compact('zonas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $validated = $request->validate([
            'nombre'   => ['required', 'string', 'max:150'],
            'id_zona'  => ['required', 'exists:p3_transfer_zonas,id_zona'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'comision' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'activo'   => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated) {
            // password por defecto
            $defaultPassword = 'islatransfers';
            // Crear usuario del hotel
            $user = User::create([
                'name'     => $validated['nombre'],
                'email'    => $validated['email'],
                'password' => Hash::make($defaultPassword),
                'rol'      => 'hotel',
                'activo'   => $validated['activo'] ?? true,
            ]);

            // Crear hotel
            Hotel::create([
                'user_id'  => $user->id,
                'id_zona'  => $validated['id_zona'],
                'nombre'   => $validated['nombre'],
                'email'    => $validated['email'],
                'comision' => $validated['comision'] ?? 0,
                'telefono' => $validated['telefono'] ?? null,
                'activo'   => $validated['activo'] ?? true,
            ]);
        });

        return redirect()
            ->route('admin.hoteles.index')
            ->with('status', 'Hotel creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotel $hotel)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $zonas = Zona::orderBy('descripcion')->get();

        return view('admin.hoteles.edit', compact('hotel', 'zonas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $validated = $request->validate([
            'nombre'   => ['required', 'string', 'max:150'],
            'id_zona'  => ['required', 'exists:p3_transfer_zonas,id_zona'],
            'email'    => [
                'required',
                'email',
                'max:255',
                // único en users, excepto el user actual del hotel
                'unique:users,email,' . ($hotel->user->id ?? 'NULL'),
            ],
            'comision' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'activo'   => ['nullable', 'boolean'],
        ]);

        $activo = $request->boolean('activo');

        DB::transaction(function () use ($hotel, $validated, $activo) {
            // Actualizar hotel
            $hotel->nombre   = $validated['nombre'];
            $hotel->id_zona  = $validated['id_zona'];
            $hotel->email    = $validated['email'];
            $hotel->comision = $validated['comision'] ?? 0;
            $hotel->telefono = $validated['telefono'] ?? null;
            $hotel->activo   = $activo;
            $hotel->save();

            // Sincronizar también el usuario asociado
            if ($hotel->user) {
                $hotel->user->name   = $validated['nombre'];
                $hotel->user->email  = $validated['email'];
                $hotel->user->activo = $activo;
                $hotel->user->save();
            }
        });

        return redirect()
            ->route('admin.hoteles.index')
            ->with('status', 'Hotel actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        // Desactivar hotel
        $hotel->activo = false;
        $hotel->save();

        // Desactivar también el usuario asociado, si existe
        if ($hotel->user) {
            $hotel->user->activo = false;
            $hotel->user->save();
        }

        return redirect()
            ->route('admin.hoteles.index')
            ->with('status', 'Hotel desactivado correctamente.');
    }

    public function resetPassword(Hotel $hotel)
    {
        $admin = Auth::user();
        abort_unless($admin && $admin->isAdmin(), 403);

        if (! $hotel->user) {
            return back()->withErrors('Este hotel no tiene usuario asociado.');
        }

        $defaultPassword = 'islatransfers';

        $hotel->user->password = Hash::make($defaultPassword);
        $hotel->user->save();

        return back()->with('status', 'Contraseña restablecida a la contraseña por defecto.');
    }
}
