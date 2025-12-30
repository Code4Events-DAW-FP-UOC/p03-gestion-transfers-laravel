<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Zona;
use App\Models\Viajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with(['hotel', 'viajero'])->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $zonas = Zona::all();
        return view('admin.usuarios.create', compact('zonas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_usuario' => 'required|in:admin,viajero,hotel',
            'password' => 'required|min:8|confirmed',
            'email' => 'required|email|unique:users,email',
            'nombre' => 'required_if:tipo_usuario,viajero',
            'apellido1' => 'required_if:tipo_usuario,viajero',
            'nombre_hotel' => 'required_if:tipo_usuario,hotel',
            'name_admin' => 'required_if:tipo_usuario,admin',
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
        ]);

        try {
            DB::beginTransaction();

            $email = $request->email; 
            $name = '';

            if ($request->tipo_usuario === 'viajero') {
                $name = trim($request->nombre . ' ' . $request->apellido1 . ' ' . ($request->apellido2 ?? ''));
            } elseif ($request->tipo_usuario === 'hotel') {
                $name = $request->nombre_hotel;
            } else {
                $name = $request->name_admin;
            }

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($request->password),
                'rol' => $request->tipo_usuario,
                'activo' => true,
            ]);

            if ($request->tipo_usuario === 'viajero') {
                Viajero::create([
                    'user_id' => $user->id,
                    'nombre' => $request->nombre,
                    'apellido1' => $request->apellido1,
                    'apellido2' => $request->apellido2,
                    'email' => $request->email,
                    'direccion' => $request->direccion,
                    'codigo_postal' => $request->cp,
                    'ciudad' => $request->ciudad,
                    'pais' => $request->pais,
                    'telefono' => $request->telefono,
                ]);
            } elseif ($request->tipo_usuario === 'hotel') {
                Hotel::create([
                    'user_id' => $user->id,
                    'nombre' => $request->nombre_hotel,
                    'id_zona' => $request->id_zona,
                    'email' => $request->email,
                    'telefono' => $request->telefono,
                    'comision' => $request->comision,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function destroy($id) 
    {
        $user = User::findOrFail($id); 

        if ($user->rol === 'viajero' && $user->viajero) {
            if ($user->viajero->reservas()->where('estado', '!=', 'cancelada')->exists()) {
                return back()->with('error', "No se puede eliminar al usuario porque tiene reservas asociadas.");
            }
        } 

        elseif ($user->rol === 'hotel' && $user->hotel) {
            if ($user->hotel->reservas()->where('estado', '!=', 'cancelada')->exists()) {
                return back()->with('error', "No se puede eliminar el hotel porque tiene reservas en su historial.");
            }
        }

        try {
            DB::beginTransaction();

            if ($user->rol === 'viajero' && $user->viajero) {
                $user->viajero->delete();
            } elseif ($user->rol === 'hotel' && $user->hotel) {
                $user->hotel->delete();
            }

            $user->delete();

            DB::commit();
            return redirect()->route('admin.usuarios.index')
                             ->with('success', 'Usuario eliminado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Fallo al borrar: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::with(['viajero', 'hotel'])->findOrFail($id);
        return view('admin.usuarios.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'activo' => 'required|boolean',
        ];

        if ($user->rol === 'viajero') {
            $rules += [
                'nombre_viajero' => 'required|string|max:255',
                'apellido1' => 'required|string|max:255',
            ];
        } elseif ($user->rol === 'hotel') {
            $rules += [
                'nombre_hotel' => 'required|string|max:255',
                'comision' => 'required|numeric|min:0|max:100',
            ];
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // 2. Actualizar Usuario Base
            $user->fill($request->only(['name', 'email', 'activo']));
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // 3. Actualizar Relaciones
            if ($user->rol === 'viajero') {
                $user->viajero()->update([
                    'nombre'    => $request->nombre_viajero,
                    'apellido1' => $request->apellido1,
                    'telefono'  => $request->telefono,
                    'direccion' => $request->direccion,
                    'ciudad'    => $request->ciudad,
                    'pais'      => $request->pais,
                ]);
            } elseif ($user->rol === 'hotel') {
                $user->hotel()->update([
                    'nombre'   => $request->nombre_hotel,
                    'telefono' => $request->telefono_hotel,
                    'comision' => $request->comision,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }
}