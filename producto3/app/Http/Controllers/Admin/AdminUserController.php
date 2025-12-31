<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Viajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        $query = User::query()
            ->whereIn('rol', ['viajero', 'admin'])
            ->with('viajero');

        // (Opcional) pequeño buscador por nombre / email
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('viajero', function ($sub) use ($search) {
                      $sub->where('nombre', 'like', "%{$search}%")
                          ->orWhere('apellido1', 'like', "%{$search}%")
                          ->orWhere('apellido2', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query
            ->orderBy('rol')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        abort_unless($user && $user->isAdmin(), 403);

        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();
        abort_unless($authUser && $authUser->isAdmin(), 403);

        $userTable    = (new User)->getTable();
        $viajeroTable = (new Viajero)->getTable();

        $data = $request->validate([
            // Datos de USER
            'email'  => ['required', 'email', 'max:255', "unique:{$userTable},email"],
            'rol'    => ['required', Rule::in(['admin', 'viajero'])],
            'activo' => ['nullable', 'boolean'],

            // Datos de VIAJERO (solo se usarán si rol = viajero)
            'nombre'        => ['nullable', 'string', 'max:100'],
            'apellido1'     => ['nullable', 'string', 'max:100'],
            'apellido2'     => ['nullable', 'string', 'max:100'],
            'direccion'     => ['nullable', 'string', 'max:255'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],
            'ciudad'        => ['nullable', 'string', 'max:100'],
            'pais'          => ['nullable', 'string', 'max:100'],
            'telefono'      => ['nullable', 'string', 'max:50'],
            'viajero_activo'=> ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($data) {
            // Nombre para la tabla users: si hay nombre+apellido los usamos,
            // si no, usamos el email.
            $nameForUser = trim(($data['nombre'] ?? '') . ' ' . ($data['apellido1'] ?? ''));
            if ($nameForUser === '') {
                $nameForUser = $data['email'];
            }

            // USER
            $user = User::create([
                'name'   => $nameForUser,
                'email'  => $data['email'],
                'password' => Hash::make('islatransfers'),
                'rol'    => $data['rol'],
                'activo' => isset($data['activo']) ? (bool)$data['activo'] : true,
            ]);

            // VIAJERO (solo si rol = viajero)
            if ($data['rol'] === 'viajero') {
                Viajero::create([
                    'user_id'       => $user->id,
                    'nombre'        => $data['nombre'] ?? '',
                    'apellido1'     => $data['apellido1'] ?? '',
                    'apellido2'     => $data['apellido2'] ?? '',
                    'direccion'     => $data['direccion'] ?? null,
                    'codigo_postal' => $data['codigo_postal'] ?? null,
                    'ciudad'        => $data['ciudad'] ?? null,
                    'pais'          => $data['pais'] ?? null,
                    'email'         => $data['email'],
                    'telefono'      => $data['telefono'] ?? null,
                    'activo'        => isset($data['viajero_activo'])
                        ? (bool)$data['viajero_activo']
                        : (isset($data['activo']) ? (bool)$data['activo'] : true),
                ]);
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario creado correctamente. La contraseña inicial es "islatransfers".');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
         $authUser = Auth::user();
        abort_unless($authUser && $authUser->isAdmin(), 403);

        $user->load('viajero');

        return view('admin.users.edit', [
            'user'    => $user,
            'viajero' => $user->viajero,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        abort_unless($authUser && $authUser->isAdmin(), 403);

        $userTable = (new User)->getTable();

        $data = $request->validate([
            'email'  => ['required', 'email', 'max:255', Rule::unique($userTable, 'email')->ignore($user->id)],
            'rol'    => ['required', Rule::in(['admin', 'viajero'])],
            'activo' => ['nullable', 'boolean'],

            'nombre'        => ['nullable', 'string', 'max:100'],
            'apellido1'     => ['nullable', 'string', 'max:100'],
            'apellido2'     => ['nullable', 'string', 'max:100'],
            'direccion'     => ['nullable', 'string', 'max:255'],
            'codigo_postal' => ['nullable', 'string', 'max:20'],
            'ciudad'        => ['nullable', 'string', 'max:100'],
            'pais'          => ['nullable', 'string', 'max:100'],
            'telefono'      => ['nullable', 'string', 'max:50'],
            'viajero_activo'=> ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($data, $user) {
            $nameForUser = trim(($data['nombre'] ?? '') . ' ' . ($data['apellido1'] ?? ''));
            if ($nameForUser === '') {
                $nameForUser = $data['email'];
            }

            // Actualizar USER
            $user->name   = $nameForUser;
            $user->email  = $data['email'];
            $user->rol    = $data['rol'];
            $user->activo = isset($data['activo']) ? (bool)$data['activo'] : false;
            $user->save();

            // Gestionar VIAJERO según rol
            if ($data['rol'] === 'viajero') {
                $viajero = $user->viajero ?? new Viajero(['user_id' => $user->id]);

                $viajero->nombre        = $data['nombre'] ?? '';
                $viajero->apellido1     = $data['apellido1'] ?? '';
                $viajero->apellido2     = $data['apellido2'] ?? '';
                $viajero->direccion     = $data['direccion'] ?? null;
                $viajero->codigo_postal = $data['codigo_postal'] ?? null;
                $viajero->ciudad        = $data['ciudad'] ?? null;
                $viajero->pais          = $data['pais'] ?? null;
                $viajero->email         = $data['email'];
                $viajero->telefono      = $data['telefono'] ?? null;
                $viajero->activo        = isset($data['viajero_activo'])
                    ? (bool)$data['viajero_activo']
                    : $user->activo;

                $viajero->save();
            } else {
                // Si deja de ser viajero, marcamos la ficha viajero como inactiva (si existe)
                if ($user->viajero) {
                    $user->viajero->activo = false;
                    $user->viajero->save();
                }
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $authUser = Auth::user();
        abort_unless($authUser && $authUser->isAdmin(), 403);

        // Evitar que se borre a sí mismo
        if ($authUser->id === $user->id) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        DB::transaction(function () use ($user) {
            // Desactivar user
            $user->activo = false;
            $user->save();

            // Desactivar viajero asociado, si existe
            if ($user->viajero) {
                $user->viajero->activo = false;
                $user->viajero->save();
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario desactivado correctamente.');
    }

    public function resetPassword(User $user)
{
    $authUser = Auth::user();
    abort_unless($authUser && $authUser->isAdmin(), 403);

    if ($authUser->id === $user->id) {
        return back()->with('error', 'No puedes restablecer tu propia contraseña desde aquí.');
    }

    $user->password = Hash::make('islatransfers');
    $user->save();

    return back()->with(
        'status',
        'Contraseña restablecida correctamente. La nueva contraseña es "islatransfers".'
    );
}
}
