{{-- resources/views/hotel/reservas/create.blade.php --}}
@php
    $minDate = now()->format('Y-m-d'); // hoy (sin regla de 48h para hotel)
    $viajeroModoOld = old('viajero_modo', 'existente');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Nueva reserva (hotel)') }}</h2>
    </x-slot>

    @include('layouts.partials.flash-messages')

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h5 mb-4">{{ __('Crear reserva de transfer para un cliente del hotel') }}</h1>

                        <p class="small text-muted mb-4">
                            {{ __('Selecciona el viajero, el tipo de reserva y completa los datos del vuelo y del vehículo.') }}
                        </p>

                        <form action="{{ route('hotel.reservas.store') }}" method="POST">
                            @csrf

                            {{-- DATOS DEL VIAJERO --}}
                            <div class="mb-4">
                                <h2 class="h6 mb-3">{{ __('Datos del viajero') }}</h2>

                                {{-- Selector de viajero existente o "nuevo viajero" --}}
                                <div class="mb-3">
                                    <x-input-label for="id_viajero_select" :value="__('Viajero')" />
                                    <select id="id_viajero_select"
                                            name="id_viajero"
                                            class="form-select @error('id_viajero') is-invalid @enderror">
                                        <option value="">{{ __('Selecciona un viajero registrado') }}</option>
                                        @foreach ($viajeros as $viajero)
                                            <option value="{{ $viajero->id_viajero }}"
                                                @selected($viajeroModoOld === 'existente' && old('id_viajero') == $viajero->id_viajero)>
                                                {{ $viajero->nombre }} {{ $viajero->apellido1 }}
                                                {{ $viajero->apellido2 }}
                                                ({{ $viajero->user->email ?? $viajero->email }})
                                            </option>
                                        @endforeach
                                        <option value="__new" @selected($viajeroModoOld === 'nuevo')>
                                            {{ __('+ Nuevo viajero') }}
                                        </option>
                                    </select>
                                    @error('id_viajero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <input type="hidden" name="viajero_modo" id="viajero_modo"
                                           value="{{ $viajeroModoOld }}">
                                </div>

                                {{-- Bloque NUEVO VIAJERO --}}
                                <div id="bloque-nuevo-viajero"
                                     class="border rounded p-3 bg-light {{ $viajeroModoOld === 'nuevo' ? '' : 'd-none' }}">
                                    <p class="small text-muted mb-3">
                                        {{ __('Completa estos datos para registrar un nuevo viajero. Se creará un usuario con rol viajero asociado a esta ficha.') }}
                                    </p>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <x-input-label for="nuevo_nombre" :value="__('Nombre')" />
                                            <x-text-input id="nuevo_nombre" name="nuevo_nombre" type="text"
                                                          class="w-100"
                                                          :value="old('nuevo_nombre')" />
                                            <x-input-error :messages="$errors->get('nuevo_nombre')" />
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <x-input-label for="nuevo_apellido1" :value="__('Primer apellido')" />
                                            <x-text-input id="nuevo_apellido1" name="nuevo_apellido1" type="text"
                                                          class="w-100"
                                                          :value="old('nuevo_apellido1')" />
                                            <x-input-error :messages="$errors->get('nuevo_apellido1')" />
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <x-input-label for="nuevo_apellido2" :value="__('Segundo apellido')" />
                                            <x-text-input id="nuevo_apellido2" name="nuevo_apellido2" type="text"
                                                          class="w-100"
                                                          :value="old('nuevo_apellido2')" />
                                            <x-input-error :messages="$errors->get('nuevo_apellido2')" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <x-input-label for="nuevo_email" :value="__('Email')" />
                                            <x-text-input id="nuevo_email" name="nuevo_email" type="email"
                                                          class="w-100"
                                                          :value="old('nuevo_email')" />
                                            <x-input-error :messages="$errors->get('nuevo_email')" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-input-label for="nuevo_telefono" :value="__('Teléfono')" />
                                            <x-text-input id="nuevo_telefono" name="nuevo_telefono" type="text"
                                                          class="w-100"
                                                          :value="old('nuevo_telefono')" />
                                            <x-input-error :messages="$errors->get('nuevo_telefono')" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <x-input-label for="nuevo_direccion" :value="__('Dirección')" />
                                            <x-text-input id="nuevo_direccion" name="nuevo_direccion" type="text"
                                                          class="w-100"
                                                          :value="old('nuevo_direccion')" />
                                            <x-input-error :messages="$errors->get('nuevo_direccion')" />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <x-input-label for="nuevo_codigo_postal" :value="__('Código postal')" />
                                            <x-text-input id="nuevo_codigo_postal" name="nuevo_codigo_postal" type="text"
                                                          class="w-100"
                                                          :value="old('nuevo_codigo_postal')" />
                                            <x-input-error :messages="$errors->get('nuevo_codigo_postal')" />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <x-input-label for="nuevo_ciudad" :value="__('Ciudad')" />
                                            <x-text-input id="nuevo_ciudad" name="nuevo_ciudad" type="text"
                                                          class="w-100"
                                                          :value="old('nuevo_ciudad')" />
                                            <x-input-error :messages="$errors->get('nuevo_ciudad')" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-0">
                                            <x-input-label for="nuevo_pais" :value="__('País')" />
                                            <x-text-input id="nuevo_pais" name="nuevo_pais" type="text"
                                                          class="w-100"
                                                          :value="old('nuevo_pais', 'España')" />
                                            <x-input-error :messages="$errors->get('nuevo_pais')" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Tipo de reserva --}}
                            <div class="mb-4">
                                <x-input-label for="id_tipo_reserva" :value="__('Tipo de reserva')" />
                                <select name="id_tipo_reserva" id="id_tipo_reserva"
                                        class="form-select @error('id_tipo_reserva') is-invalid @enderror" required>
                                    <option value="">{{ __('Selecciona un tipo de reserva') }}</option>
                                    @foreach ($tiposReserva as $tipo)
                                        <option value="{{ $tipo->id_tipo_reserva }}"
                                            @selected(old('id_tipo_reserva') == $tipo->id_tipo_reserva)>
                                            {{ $tipo->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_tipo_reserva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Bloque SOLO IDA --}}
                            <div id="bloque-ida" class="d-none">
                                <h2 class="h6 mb-3">{{ __('Datos de llegada (Aeropuerto → Hotel)') }}</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_entrada" :value="__('Fecha de llegada')" />
                                        <x-text-input id="fecha_entrada" name="fecha_entrada" type="date"
                                                      class="w-100"
                                                      :value="old('fecha_entrada')"
                                                      min="{{ $minDate }}" />
                                        <x-input-error :messages="$errors->get('fecha_entrada')" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_entrada" :value="__('Hora de llegada')" />
                                        <x-text-input id="hora_entrada" name="hora_entrada" type="time"
                                                      class="w-100"
                                                      :value="old('hora_entrada')" />
                                        <x-input-error :messages="$errors->get('hora_entrada')" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="numero_vuelo_entrada" :value="__('Número de vuelo (llegada)')" />
                                        <x-text-input id="numero_vuelo_entrada" name="numero_vuelo_entrada" type="text"
                                                      class="w-100"
                                                      :value="old('numero_vuelo_entrada')" />
                                        <x-input-error :messages="$errors->get('numero_vuelo_entrada')" />
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <x-input-label for="origen_vuelo_entrada" :value="__('Origen del vuelo')" />
                                        <x-text-input id="origen_vuelo_entrada" name="origen_vuelo_entrada" type="text"
                                                      class="w-100"
                                                      :value="old('origen_vuelo_entrada')" />
                                        <x-input-error :messages="$errors->get('origen_vuelo_entrada')" />
                                    </div>
                                </div>
                            </div>

                            {{-- Bloque SOLO VUELTA --}}
                            <div id="bloque-vuelta" class="d-none">
                                <h2 class="h6 mb-3">{{ __('Datos de salida (vuelta)') }}</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_vuelo_salida" :value="__('Fecha de salida')" />
                                        <x-text-input id="fecha_vuelo_salida" name="fecha_vuelo_salida" type="date"
                                                      class="w-100"
                                                      :value="old('fecha_vuelo_salida')"
                                                      min="{{ $minDate }}" />
                                        <x-input-error :messages="$errors->get('fecha_vuelo_salida')" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_vuelo_salida" :value="__('Hora de salida')" />
                                        <x-text-input id="hora_vuelo_salida" name="hora_vuelo_salida" type="time"
                                                      class="w-100"
                                                      :value="old('hora_vuelo_salida')" />
                                        <x-input-error :messages="$errors->get('hora_vuelo_salida')" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <x-input-label for="numero_vuelo_salida" :value="__('Número de vuelo (salida)')" />
                                    <x-text-input id="numero_vuelo_salida" name="numero_vuelo_salida" type="text"
                                                  class="w-100"
                                                  :value="old('numero_vuelo_salida')" />
                                    <x-input-error :messages="$errors->get('numero_vuelo_salida')" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="destino_vuelo_salida" :value="__('Destino del vuelo')" />
                                    <x-text-input id="destino_vuelo_salida" name="destino_vuelo_salida" type="text"
                                                  class="w-100"
                                                  :value="old('destino_vuelo_salida')" />
                                    <x-input-error :messages="$errors->get('destino_vuelo_salida')" />
                                </div>
                            </div>

                            {{-- Bloque común: nº viajeros + vehículo --}}
                            <div id="bloque-comun" class="d-none">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="num_viajeros" :value="__('Número de viajeros')" />
                                        <x-text-input id="num_viajeros" name="num_viajeros" type="number"
                                                      min="1"
                                                      max="{{ $maxPlazas }}"
                                                      class="w-100"
                                                      :value="old('num_viajeros')" />
                                        <x-input-error :messages="$errors->get('num_viajeros')" />
                                        <small class="form-text text-muted">
                                            {{ __('No puedes reservar más plazas que la capacidad máxima disponible.') }}
                                        </small>
                                    </div>
                                    <div class="col-md-8 mb-4">
                                        <x-input-label for="id_vehiculo" :value="__('Vehículo')" />
                                        <select id="id_vehiculo" name="id_vehiculo"
                                                class="form-select @error('id_vehiculo') is-invalid @enderror">
                                            <option value="">{{ __('Selecciona un vehículo') }}</option>
                                            @foreach ($vehiculos as $vehiculo)
                                                <option value="{{ $vehiculo->id_vehiculo }}"
                                                        data-plazas="{{ $vehiculo->plazas }}"
                                                        @selected(old('id_vehiculo') == $vehiculo->id_vehiculo)>
                                                    {{ $vehiculo->descripcion }} ({{ $vehiculo->plazas }}
                                                    {{ __('plazas') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_vehiculo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ __('Solo se podrán seleccionar vehículos con plazas suficientes para el número de viajeros indicado.') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Acciones --}}
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <a href="{{ route('hotel.reservas.index') }}"
                                   class="btn btn-outline-secondary">
                                    {{ __('Cancelar') }}
                                </a>
                                <div id="bloque-submit" class="d-none">
                                    <x-primary-button type="submit">
                                        {{ __('Crear reserva') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // --- Viajero existente / nuevo ---
                const selectViajero = document.getElementById('id_viajero_select');
                const bloqueNuevo   = document.getElementById('bloque-nuevo-viajero');
                const inputModo     = document.getElementById('viajero_modo');

                function actualizarBloqueViajero() {
                    if (!selectViajero) return;

                    const value = selectViajero.value;

                    if (value === '__new') {
                        bloqueNuevo.classList.remove('d-none');
                        inputModo.value = 'nuevo';
                    } else {
                        bloqueNuevo.classList.add('d-none');
                        inputModo.value = 'existente';
                    }
                }

                if (selectViajero) {
                    selectViajero.addEventListener('change', actualizarBloqueViajero);
                    // Estado inicial (old())
                    actualizarBloqueViajero();
                }

                // --- Lógica de tipo de reserva / bloques / vehículos ---
                const tipoSelect       = document.getElementById('id_tipo_reserva');
                const bloqueComun      = document.getElementById('bloque-comun');
                const bloqueIda        = document.getElementById('bloque-ida');
                const bloqueVuelta     = document.getElementById('bloque-vuelta');
                const bloqueSubmit     = document.getElementById('bloque-submit');
                const numViajeros      = document.getElementById('num_viajeros');
                const vehiculoSelect   = document.getElementById('id_vehiculo');
                const fechaIdaInput    = document.getElementById('fecha_entrada');
                const fechaVueltaInput = document.getElementById('fecha_vuelo_salida');
                const baseMin          = '{{ $minDate }}';

                function toggleBloques() {
                    const tipo = tipoSelect.value;

                    bloqueComun.classList.add('d-none');
                    bloqueIda.classList.add('d-none');
                    bloqueVuelta.classList.add('d-none');
                    bloqueSubmit.classList.add('d-none');

                    if (!tipo) {
                        return;
                    }

                    bloqueComun.classList.remove('d-none');
                    bloqueSubmit.classList.remove('d-none');

                    if (tipo === '1') {
                        bloqueIda.classList.remove('d-none');
                    } else if (tipo === '2') {
                        bloqueVuelta.classList.remove('d-none');
                    } else if (tipo === '3') {
                        bloqueIda.classList.remove('d-none');
                        bloqueVuelta.classList.remove('d-none');
                    }
                }

                function filtrarVehiculosPorPlazas() {
                    const n = parseInt(numViajeros.value || '0', 10);

                    Array.from(vehiculoSelect.options).forEach(option => {
                        if (!option.value) return;

                        const plazas = parseInt(option.dataset.plazas || '0', 10);
                        option.disabled = (n > 0 && plazas < n);
                    });

                    if (vehiculoSelect.selectedOptions.length) {
                        const opt = vehiculoSelect.selectedOptions[0];
                        if (opt.disabled) {
                            vehiculoSelect.value = '';
                        }
                    }
                }

                function actualizarMinimos() {
                    const tipo = tipoSelect.value;

                    if (fechaIdaInput) {
                        fechaIdaInput.min = baseMin;
                    }
                    if (!fechaVueltaInput) return;

                    fechaVueltaInput.min = baseMin;

                    if (fechaVueltaInput.value && fechaVueltaInput.value < baseMin) {
                        fechaVueltaInput.value = '';
                    }
                }

                if (tipoSelect) {
                    tipoSelect.addEventListener('change', function () {
                        toggleBloques();
                        actualizarMinimos();
                    });
                }

                if (fechaIdaInput) {
                    fechaIdaInput.addEventListener('change', actualizarMinimos);
                }

                if (numViajeros) {
                    numViajeros.addEventListener('input', filtrarVehiculosPorPlazas);
                }

                // Estado inicial por si viene de old()
                toggleBloques();
                filtrarVehiculosPorPlazas();
                actualizarMinimos();
            });
        </script>
    @endpush
</x-app-layout>