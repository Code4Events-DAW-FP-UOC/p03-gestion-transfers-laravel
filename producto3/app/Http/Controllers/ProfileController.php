<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'viajero' => $request->user()->viajero,
            'hotel' => $request->user()->hotel,
            'zona' => $request->user()->zona,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        DB::transaction(function () use ($user, $data) {

            // Actualizar tabla users
            if (isset($data['nombre'])) {
                $user->name = $data['nombre'];
            }

            $oldEmail = $user->email;
            $user->email = $data['email'];

            if ($oldEmail !== $user->email) {
                $user->email_verified_at = null;
            }

            $user->save();

            // Actualizar tabla viajeros
            if ($user->isViajero() && $user->viajero) {
                $viajero = $user->viajero;

                $viajero->nombre        = $data['nombre']        ?? $viajero->nombre;
                $viajero->apellido1     = $data['apellido1']     ?? $viajero->apellido1;
                $viajero->apellido2     = $data['apellido2']     ?? null;
                $viajero->direccion     = $data['direccion']     ?? $viajero->direccion;
                $viajero->codigo_postal = $data['codigo_postal'] ?? $viajero->codigo_postal;
                $viajero->ciudad        = $data['ciudad']        ?? $viajero->ciudad;
                $viajero->pais          = $data['pais']          ?? $viajero->pais;
                $viajero->telefono      = $data['telefono']      ?? null;

                $viajero->email = $user->email;

                $viajero->save();
            }

            // Actualizar tabla hotel
            if ($user->isHotel() && $user->hotel) {
                $hotel = $user->hotel;

                $hotel->nombre   = $data['nombre']   ?? $hotel->nombre;
                $hotel->telefono = $data['telefono'] ?? $hotel->telefono;
                $hotel->id_zona  = $data['id_zona']  ?? $hotel->id_zona;

                $hotel->email = $user->email;

                $hotel->save();
            }
        });
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', ['password' => ['required','current_password'],]);

        $user = $request->user();

        Auth::logout();

        // Desactivar viajero/hotel y borrar user en una transacción
        DB::transaction(function () use ($user) {
            if ($user->viajero) {
                $viajero = $user->viajero;
                $viajero->activo = false;
                $viajero->save();
            }

            if ($user->hotel) {
                $hotel = $user->hotel;
                $hotel->activo = false;
                $hotel->save();
            }

            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('home');
    }
}
