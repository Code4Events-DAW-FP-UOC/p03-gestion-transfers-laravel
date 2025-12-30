{{-- resources/views/viajero/reservas/create.blade.php --}}

@php
    // Hoy + 48 horas ≈ 2 días vista YYYY-MM-DD
    $minDate = now()->addDays(2)->format('Y-m-d');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{__('Editar reserva')}}</h2>
    </x-slot>
    {{-- Contenido princiapl --}}
    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @php
                            // Hoy + 48 horas ≈ 2 días vista YYYY-MM-DD
                            $minDate = now()->addDays(2)->format('Y-m-d');

                            // Valores por defecto tomando la reserva, sobrescritos por old() si viene de validación
                            $fechaEntradaValue = old('fecha_entrada');
                            if ($fechaEntradaValue === null && $reserva->fecha_entrada) {
                                $fechaEntradaValue = $reserva->fecha_entrada->format('Y-m-d');
                            }

                            $fechaVueltaValue = old('fecha_vuelo_salida');
                            if ($fechaVueltaValue === null && $reserva->fecha_vuelo_salida) {
                                $fechaVueltaValue = $reserva->fecha_vuelo_salida->format('Y-m-d');
                            }

                            // Normalizar horas a HH:MM para <input type="time">
                            $horaEntradaValue = old('hora_entrada');
                            if ($horaEntradaValue === null && $reserva->hora_entrada) {
                                $horaEntradaValue = substr($reserva->hora_entrada, 0, 5);
                            }

                            $horaVueltaValue = old('hora_vuelo_salida');
                            if ($horaVueltaValue === null && $reserva->hora_vuelo_salida) {
                                $horaVueltaValue = substr($reserva->hora_vuelo_salida, 0, 5);
                            }

                            $numViajerosValue = old('num_viajeros', $reserva->num_viajeros);
                            $idHotelValue = old('id_hotel', $reserva->id_hotel);
                            $idVehiculoValue = old('id_vehiculo', $reserva->id_vehiculo);
                            $tipoReservaValue = old('id_tipo_reserva', $reserva->id_tipo_reserva);

                            $numVueloEntradaValue = old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada);
                            $origenEntradaValue = old('origen_vuelo_entrada', $reserva->origen_vuelo_entrada);
                            $numVueloSalidaValue = old('numero_vuelo_salida', $reserva->numero_vuelo_salida);
                            $destinoSalidaValue = old('destino_vuelo_salida', $reserva->destino_vuelo_salida);
                        @endphp
                        <h1 class="h5 mb-4">{{ __('Editar reserva de transfer') }}</h1>
                        {{-- Mensaje informativo --}}
                        <p class="small text-muted-mb-4">
                            {{ __('Selecciona el tipo de reserva y completa los datos del vuelo y del hotel. Las reservas deben realizarse con un mínimo de 48 horas de antelación.') }}
                        </p>
                        <form action="{{ route('viajero.reservas.update', $reserva) }}" method="post">
                            @csrf
                            @method('PUT')
                            {{-- Tipo de reserva --}}
                            <div class="mb-4">
                                <x-input-label for="id_tipo_reserva" :value="__('Tipo de reserva')" />
                                <select name="id_tipo_reserva" id="id_tipo_reserva"
                                    class="form-select @error('id_tipo_reserva') is-invalid @enderror" required>
                                    <option value="">{{ __('Selecciona un tipo de reserva') }}</option>
                                    @foreach ($tiposReserva as $tipo)
                                        <option value="{{ $tipo->id_tipo_reserva }}" {{ (string) $tipoReservaValue === (string) $tipo->id_tipo_reserva ? 'selected' : '' }}>
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
                                    {{-- Fecha de entrada / llegada --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_entrada" :value="__('Fecha de llegada')" />
                                        <x-text-input id="fecha_entrada" name="fecha_entrada" type="date" class="w-100"
                                            :value="$fechaEntradaValue" />
                                        <x-input-error :messages="$errors->get('fecha_entrada')" />
                                    </div>
                                    {{-- Hora de llegada --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_entrada" :value="__('Hora de llegada')" />
                                        <x-text-input id="hora_entrada" name="hora_entrada" type="time" class="w-100"
                                            :value="$horaEntradaValue" />
                                        <x-input-error :messages="$errors->get('hora_entrada')" />
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- Número de vuelo de llegada --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="numero_vuelo_entrada" :value="__('Número de vuelo (llegada)')" />
                                        <x-text-input id="numero_vuelo_entrada" name="numero_vuelo_entrada" type="text"
                                            class="w-100" :value="$numVueloEntradaValue" />
                                        <x-input-error :messages="$errors->get('numero_vuelo_entrada')" />
                                    </div>
                                    {{-- Origen del vuelo --}}
                                    <div class="col-md-6 mb-4">
                                        <x-input-label for="origen_vuelo_entrada" :value="__('Origen del vuelo')" />
                                        <x-text-input id="origen_vuelo_entrada" name="origen_vuelo_entrada" type="text"
                                            class="w-100" :value="$origenEntradaValue" />
                                        <x-input-error :messages="$errors->get('origen_vuelo_entrada')" />
                                    </div>
                                </div>
                            </div>

                            {{-- Bloque SOLO VUELTA --}}
                            <div id="bloque-vuelta" class="d-none">
                                <h2 class="h6 mb-3">{{ __('Datos de salida (vuelta)') }}</h2>
                                <div class="row">
                                    {{-- Fecha de salida --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_vuelo_salida" :value="__('Fecha de salida')" />
                                        <x-text-input id="fecha_vuelo_salida" name="fecha_vuelo_salida" type="date"
                                            class="w-100" :value="$fechaVueltaValue" min="{{ $minDate }}" />
                                        <x-input-error :messages="$errors->get('fecha_vuelo_salida')" />
                                    </div>
                                    {{-- Hora de salida --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_vuelo_salida" :value="__('Hora de salida')" />
                                        <x-text-input id="hora_vuelo_salida" name="hora_vuelo_salida" type="time"
                                            class="w-100" :value="$horaVueltaValue" />
                                        <x-input-error :messages="$errors->get('hora_vuelo_salida')" />
                                    </div>
                                </div>
                                {{-- Número de vuelo de destino --}}
                                <div class="mb-3">
                                    <x-input-label for="numero_vuelo_salida" :value="__('Número de vuelo (salida)')" />
                                    <x-text-input id="numero_vuelo_salida" name="numero_vuelo_salida" type="text"
                                        class="w-100" :value="$numVueloSalidaValue" />
                                    <x-input-error :messages="$errors->get('numero_vuelo_salida')" />
                                </div>
                                {{-- Origen del vuelo --}}
                                <div class="mb-4">
                                    <x-input-label for="destino_vuelo_salida" :value="__('Destino del vuelo')" />
                                    <x-text-input id="destino_vuelo_salida" name="destino_vuelo_salida" type="text"
                                        class="w-100" :value="$destinoSalidaValue" />
                                    <x-input-error :messages="$errors->get('destino_vuelo_salida')" />
                                </div>
                            </div>

                            {{-- Bloque común (hotel, nº viajeros, vehículo) --}}
                            <div id="bloque-comun" class="d-none">
                                {{-- Hotel (origen/destino según tipo) --}}
                                <div class="mb-3">
                                    <x-input-label for="id_hotel" :value="__('Hotel')" />
                                    <select id="id_hotel" name="id_hotel"
                                        class="form-select @error('id_hotel') is-invalid @enderror">
                                        <option value="">{{ __('Selecciona un hotel') }}</option>
                                        @foreach ($hoteles as $hotel)
                                            <option value="{{ $hotel->id_hotel }}"
                                                @selected(old('id_hotel', $reserva->id_hotel_destino) == $hotel->id_hotel)>
                                                {{ $hotel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_hotel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row">
                                    {{-- Nº de viajeros --}}
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="num_viajeros" :value="__('Número de viajeros')" />
                                        <x-text-input id="num_viajeros" name="num_viajeros" type="number" min="1"
                                            max="{{ $maxPlazas }}" class="w-100" :value="$numViajerosValue" />
                                        <x-input-error :messages="$errors->get('num_viajeros')" />
                                        <small class="form-text text-muted">
                                            {{ __('No puedes reservar más plazas que la capacidad máxima disponible.') }}</small>
                                    </div>
                                    {{-- Vehículo --}}
                                    <div class="col-md-8 mb-4">
                                        <x-input-label for="id_vehiculo" :value="__('Vehículo')" />
                                        <select id="id_vehiculo" name="id_vehiculo"
                                            class="form-select @error('id_vehiculo') is-invalid @enderror">
                                            <option value="">{{ __('Selecciona un vehículo') }}</option>
                                            @foreach ($vehiculos as $vehiculo)
                                                <option value="{{ $vehiculo->id_vehiculo }}"
                                                    data-plazas="{{ $vehiculo->plazas }}" {{ (string) $idVehiculoValue === (string) $vehiculo->id_vehiculo ? 'selected' : '' }}>
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
                            {{-- Botón de envío (solo cuando haya tipo seleccionado) --}}
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <a href="{{ route('viajero.reservas.index') }}"
                                    class="btn btn-outline-secondary">Cancelar</a>
                                <div id="bloque-submit" class="d-none">
                                    <x-primary-button id="btn-confirmar-reserva"
                                        type="submit">{{ __('Guardar cambios') }}</x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- JS para mostrar/ocultar bloques y filtrar vehículos por plazas --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoSelect     = document.getElementById('id_tipo_reserva');
            const bloqueComun    = document.getElementById('bloque-comun');
            const bloqueIda      = document.getElementById('bloque-ida');
            const bloqueVuelta   = document.getElementById('bloque-vuelta');
            const bloqueSubmit   = document.getElementById('bloque-submit');

            const numViajeros    = document.getElementById('num_viajeros');
            const vehiculoSelect = document.getElementById('id_vehiculo');

            const fechaIdaInput     = document.getElementById('fecha_entrada');
            const fechaVueltaInput  = document.getElementById('fecha_vuelo_salida');

            // Hoy + 48h (mínimo visual) viene de PHP
            const baseMin = '{{ $minDate }}';

            // IDs de tipos según código en BD
            const tipoSoloIda    = '{{ $tiposReserva->firstWhere("codigo", "SOLO_IDA")->id_tipo_reserva ?? "1" }}';
            const tipoSoloVuelta = '{{ $tiposReserva->firstWhere("codigo", "SOLO_VUELTA")->id_tipo_reserva ?? "2" }}';
            const tipoIdaVuelta  = '{{ $tiposReserva->firstWhere("codigo", "IDA_VUELTA")->id_tipo_reserva ?? "3" }}';

            /**
             * Mostrar / ocultar bloques según el tipo de reserva
             */
            function toggleBloques() {
                const tipo = tipoSelect.value;

                // Ocultar todo por defecto
                bloqueComun.classList.add('d-none');
                bloqueIda.classList.add('d-none');
                bloqueVuelta.classList.add('d-none');
                bloqueSubmit.classList.add('d-none');

                if (!tipo) {
                    return;
                }

                // Siempre mostramos bloque común y botón cuando hay tipo
                bloqueComun.classList.remove('d-none');
                bloqueSubmit.classList.remove('d-none');

                if (tipo === tipoSoloIda) {
                    // Solo ida
                    bloqueIda.classList.remove('d-none');
                } else if (tipo === tipoSoloVuelta) {
                    // Solo vuelta
                    bloqueVuelta.classList.remove('d-none');
                } else if (tipo === tipoIdaVuelta) {
                    // Ida + vuelta
                    bloqueIda.classList.remove('d-none');
                    bloqueVuelta.classList.remove('d-none');
                }
            }

            /**
             * Deshabilitar vehículos que no tengan plazas suficientes
             */
            function filtrarVehiculosPorPlazas() {
                const n = parseInt(numViajeros.value || '0', 10);

                Array.from(vehiculoSelect.options).forEach(option => {
                    if (!option.value) return; // opción vacía

                    const plazas = parseInt(option.dataset.plazas || '0', 10);
                    option.disabled = (n > 0 && plazas < n);
                });

                // Si el vehículo seleccionado ya no es válido, limpiamos selección
                if (vehiculoSelect.selectedOptions.length) {
                    const opt = vehiculoSelect.selectedOptions[0];
                    if (opt.disabled) {
                        vehiculoSelect.value = '';
                    }
                }
            }

            /**
             * Ajustar límites mínimos de fechas (solo visual)
             * - Ida: no antes de baseMin (hoy + 48h)
             * - Vuelta: según tipo:
             *   - SOLO_VUELTA: min = baseMin
             *   - IDA_VUELTA: min = max(baseMin, fecha_entrada)
             *   - SOLO_IDA: min = baseMin (aunque no se muestre bloque de vuelta)
             */
            function actualizarMinimos() {
                const tipo = tipoSelect.value;

                if (fechaIdaInput) {
                    fechaIdaInput.min = baseMin;
                }

                if (!fechaVueltaInput) {
                    return;
                }

                let minVuelta = baseMin;

                // SOLO VUELTA
                if (tipo === tipoSoloVuelta) {
                    fechaVueltaInput.min = minVuelta;
                    return;
                }

                // IDA + VUELTA
                if (tipo === tipoIdaVuelta) {
                    if (fechaIdaInput && fechaIdaInput.value) {
                        // La vuelta no puede ser antes de la ida ni antes de baseMin
                        if (fechaIdaInput.value > minVuelta) {
                            minVuelta = fechaIdaInput.value;
                        }
                    }
                    fechaVueltaInput.min = minVuelta;
                    // Importante: NO vaciamos el valor aunque sea menor;
                    // si algo no cumple las reglas, lo validará el backend.
                    return;
                }

                // SOLO IDA u otro caso
                fechaVueltaInput.min = minVuelta;
            }

            // Eventos
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

            // Estado inicial al cargar la página (edición o con old())
            toggleBloques();
            filtrarVehiculosPorPlazas();
            actualizarMinimos();
        });
    </script>
    @endpush
</x-app-layout>