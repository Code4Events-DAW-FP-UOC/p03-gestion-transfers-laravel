<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Viajero;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre'        => ['required', 'string', 'max:100'],
            'apellido1'     => ['required', 'string', 'max:100'],
            'apellido2'     => ['nullable', 'string', 'max:100'],
            'direccion'     => ['required', 'string', 'max:150'],
            'codigo_postal' => ['required', 'string', 'max:20'],
            'ciudad'        => ['required', 'string', 'max:100'],
            'pais'          => ['required', 'string', 'max:100'],
            'telefono'      => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:150', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'      => $request->nombre,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'rol'       => 'viajero',
        ]);

        Viajero::create([
            'user_id'       => $user->id,
            'nombre'        => $request->nombre,
            'apellido1'     => $request->apellido1,
            'apellido2'     => $request->apellido2 ?? null,
            'direccion'     => $request->direccion,
            'codigo_postal' => $request->codigo_postal,
            'ciudad'        => $request->ciudad,
            'pais'          => $request->pais,
            'email'         => $request->email,
            'telefono'      => $request->telefono ?? null,
            'activo'        => true,

        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
