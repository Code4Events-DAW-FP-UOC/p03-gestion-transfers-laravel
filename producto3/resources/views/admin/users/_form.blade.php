{{-- resources/views/admin/users/_form.blade.php --}}
@php
    /** @var \App\Models\User|null $user */
    /** @var \App\Models\Viajero|null $viajero */
    $isEdit = isset($user) && $user && $user->exists;

    $rolActual = old('rol', $isEdit ? $user->rol : 'viajero');
@endphp

<div class="row g-3">
    {{-- Columna izquierda: datos de acceso (USER) --}}
    <div class="col-12 col-lg-5">
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ __('Datos de acceso') }}</strong>
            </div>
            <div class="card-body">

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Correo electrónico') }}</label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $isEdit ? $user->email : '') }}"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Rol --}}
                <div class="mb-3">
                    <label for="rol" class="form-label">{{ __('Rol') }}</label>
                    <select name="rol"
                            id="rol"
                            class="form-select @error('rol') is-invalid @enderror">
                        <option value="viajero" {{ $rolActual === 'viajero' ? 'selected' : '' }}>
                            {{ __('Viajero') }}
                        </option>
                        <option value="admin" {{ $rolActual === 'admin' ? 'selected' : '' }}>
                            {{ __('Administrador') }}
                        </option>
                    </select>
                    @error('rol')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        {{ __('Solo se gestionan viajeros y administradores desde este panel.') }}
                    </div>
                </div>

                {{-- Activo (user) --}}
                <div class="form-check mb-2">
                    <input type="checkbox"
                           name="activo"
                           id="activo"
                           value="1"
                           class="form-check-input"
                           {{ old('activo', $isEdit ? (int)$user->activo : 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="activo">
                        {{ __('Usuario activo') }}
                    </label>
                </div>

                @if(!$isEdit)
                    <div class="form-text">
                        {{ __('La contraseña inicial será "islatransfers". El usuario podrá cambiarla desde su perfil.') }}
                    </div>
                @else
                    <div class="form-text">
                        {{ __('La contraseña no se muestra aquí. Puedes añadir más adelante un botón de "restablecer".') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Columna derecha: datos de viajero --}}
    <div class="col-12 col-lg-7">
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ __('Datos personales (viajero)') }}</strong>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
                        <input type="text"
                               name="nombre"
                               id="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $viajero->nombre ?? '') }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="apellido1" class="form-label">{{ __('Primer apellido') }}</label>
                        <input type="text"
                               name="apellido1"
                               id="apellido1"
                               class="form-control @error('apellido1') is-invalid @enderror"
                               value="{{ old('apellido1', $viajero->apellido1 ?? '') }}">
                        @error('apellido1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="apellido2" class="form-label">{{ __('Segundo apellido') }}</label>
                        <input type="text"
                               name="apellido2"
                               id="apellido2"
                               class="form-control @error('apellido2') is-invalid @enderror"
                               value="{{ old('apellido2', $viajero->apellido2 ?? '') }}">
                        @error('apellido2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label for="direccion" class="form-label">{{ __('Dirección') }}</label>
                        <input type="text"
                               name="direccion"
                               id="direccion"
                               class="form-control @error('direccion') is-invalid @enderror"
                               value="{{ old('direccion', $viajero->direccion ?? '') }}">
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="codigo_postal" class="form-label">{{ __('Código postal') }}</label>
                        <input type="text"
                               name="codigo_postal"
                               id="codigo_postal"
                               class="form-control @error('codigo_postal') is-invalid @enderror"
                               value="{{ old('codigo_postal', $viajero->codigo_postal ?? '') }}">
                        @error('codigo_postal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="ciudad" class="form-label">{{ __('Ciudad') }}</label>
                        <input type="text"
                               name="ciudad"
                               id="ciudad"
                               class="form-control @error('ciudad') is-invalid @enderror"
                               value="{{ old('ciudad', $viajero->ciudad ?? '') }}">
                        @error('ciudad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="pais" class="form-label">{{ __('País') }}</label>
                        <input type="text"
                               name="pais"
                               id="pais"
                               class="form-control @error('pais') is-invalid @enderror"
                               value="{{ old('pais', $viajero->pais ?? '') }}">
                        @error('pais')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="telefono" class="form-label">{{ __('Teléfono') }}</label>
                        <input type="text"
                               name="telefono"
                               id="telefono"
                               class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono', $viajero->telefono ?? '') }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox"
                                   name="viajero_activo"
                                   id="viajero_activo"
                                   value="1"
                                   class="form-check-input"
                                   {{ old('viajero_activo', $viajero->activo ?? 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="viajero_activo">
                                {{ __('Ficha de viajero activa') }}
                            </label>
                        </div>
                        <div class="form-text">
                            {{ __('Estos datos solo se usan si el rol es "viajero". Para administradores puedes dejarlos en blanco.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>