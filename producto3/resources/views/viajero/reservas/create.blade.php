{{-- resources/views/viajero/reservas/create.blade.php --}}

@php
    // Hoy + 48 horas ≈ 2 días vista YYYY-MM-DD
    $minDate = now()->addDays(2)->format('Y-m-d');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{__('Nueva reserva')}}</h2>
    </x-slot>
    {{-- Contenido princiapl --}}
    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h5 mb-4">{{ __('Crear reserva de transfer') }}</h1>
                        {{-- Mensaje informativo --}}
                        <p class="small text-muted-mb-4">
                            {{ __('Selecciona el tipo de reserva y completa los datos del vuelo y del hotel. Las reservas deben realizarse con un mínimo de 48 horas de antelación.') }}
                        </p>
                        <form action="{{ route('viajero.reservas.store') }}" method="post">
                            @csrf
                            {{-- Tipo de reserva --}}
                            <div class="mb-4">
                                <x-input-label for="id_tipo_reserva" :value="__('Tipo de reserva')" />
                                <select name="id_tipo_reserva" id="id_tipo_reserva"
                                    class="form-select @error('id_tipo_reserva') is-invalid @enderror" required>
                                    <option value="">{{ __('Selecciona un tipo de reserva') }}</option>
                                    @foreach ($tiposReserva as $tipo)
                                        <option value="{{ $tipo->id_tipo_reserva }}" {{ (string) old('id_tipo_reserva') === (string) $tipo->id_tipo_reserva ? 'selected' : '' }}>
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
                                            :value="old('fecha_entrada')" min="{{ $minDate }}" />
                                        <x-input-error :messages="$errors->get('fecha_entrada')" />
                                    </div>
                                    {{-- Hora de llegada --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_entrada" :value="__('Hora de llegada')" />
                                        <x-text-input id="hora_entrada" name="hora_entrada" type="time" class="w-100"
                                            :value="old('hora_entrada')" />
                                        <x-input-error :messages="$errors->get('hora_entrada')" />
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- Número de vuelo de llegada --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="numero_vuelo_entrada" :value="__('Número de vuelo (llegada)')" />
                                        <x-text-input id="numero_vuelo_entrada" name="numero_vuelo_entrada" type="text"
                                            class="w-100" :value="old('numero_vuelo_entrada')" />
                                        <x-input-error :messages="$errors->get('numero_vuelo_entrada')" />
                                    </div>
                                    {{-- Origen del vuelo --}}
                                    <div class="col-md-6 mb-4">
                                        <x-input-label for="origen_vuelo_entrada" :value="__('Origen del vuelo')" />
                                        <x-text-input id="origen_vuelo_entrada" name="origen_vuelo_entrada" type="text"
                                            class="w-100" :value="old('origen_vuelo_entrada')" />
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
                                            class="w-100" :value="old('fecha_vuelo_salida')" min="{{ $minDate }}" />
                                        <x-input-error :messages="$errors->get('fecha_vuelo_salida')" />
                                    </div>
                                    {{-- Hora de salida --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_vuelo_salida" :value="__('Hora de salida')" />
                                        <x-text-input id="hora_vuelo_salida" name="hora_vuelo_salida" type="time"
                                            class="w-100" :value="old('hora_vuelo_salida')" />
                                        <x-input-error :messages="$errors->get('hora_vuelo_salida')" />
                                    </div>
                                </div>
                                {{-- Número de vuelo de destino --}}
                                <div class="mb-3">
                                    <x-input-label for="numero_vuelo_salida" :value="__('Número de vuelo (salida)')" />
                                    <x-text-input id="numero_vuelo_salida" name="numero_vuelo_salida" type="text"
                                        class="w-100" :value="old('numero_vuelo_salida')" />
                                    <x-input-error :messages="$errors->get('numero_vuelo_salida')" />
                                </div>
                                {{-- Origen del vuelo --}}
                                <div class="mb-4">
                                    <x-input-label for="destino_vuelo_salida" :value="__('Destino del vuelo')" />
                                    <x-text-input id="destino_vuelo_salida" name="destino_vuelo_salida" type="text"
                                        class="w-100" :value="old('destino_vuelo_salida')" />
                                    <x-input-error :messages="$errors->get('destino_vuelo_salida')" />
                                </div>
                            </div>

                            {{-- Bloque común (hotel, nº viajeros, vehículo) --}}
                            <div id="bloque-comun" class="d-none">
                                {{-- Hotel (origen/destino según tipo) --}}
                                <div class="mb-4">
                                    <x-input-label for="id_hotel" :value="__('Selecciona el Hotel')" />
                                    <select name="id_hotel" id="id_hotel" class="form-select" required>
                                        <option value="">{{ __('Selecciona un hotel') }}</option>
                                        @foreach ($hoteles as $hotel)
                                            <option value="{{ $hotel->id_hotel }}" 
                                                {{ (string) old('id_hotel') === (string) $hotel->id_hotel ? 'selected' : '' }}
                                                data-vehiculos="{{ $hotel->precios->pluck('id_vehiculo')->unique()->implode(',') }}">
                                                {{ $hotel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    {{-- Nº de viajeros --}}
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="num_viajeros" :value="__('Número de viajeros')" />
                                        <x-text-input id="num_viajeros" name="num_viajeros" type="number" min="1"
                                            max="{{ $maxPlazas }}" class="w-100" :value="old('num_viajeros', 1)" />
                                        <x-input-error :messages="$errors->get('num_viajeros')" />
                                        <small class="form-text text-muted">
                                            {{ __('No puedes reservar más plazas que la capacidad máxima disponible.') }}</small>
                                    </div>
                                    {{-- Vehículo --}}
                                    <div class="mb-4">
                                        <x-input-label for="id_vehiculo" :value="__('Tipo de Vehículo')" />
                                        <select name="id_vehiculo" id="id_vehiculo" class="form-select" required>
                                            <option value="">{{ __('Selecciona un vehículo') }}</option>
                                            @foreach ($vehiculos as $vehiculo)
                                                <option value="{{ $vehiculo->id_vehiculo }}" 
                                                    data-plazas="{{ $vehiculo->plazas }}"
                                                    {{ (string) old('id_vehiculo') === (string) $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                                    {{ $vehiculo->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            {{-- Botón de envío (solo cuando haya tipo seleccionado) --}}
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <a href="{{ route('viajero.dashboard') }}"
                                    class="btn btn-outline-secondary">Cancelar</a>
                                <div id="bloque-submit" class="d-none">
                                    <x-primary-button id="btn-confirmar-reserva"
                                        type="submit">{{ __('Confirmar reserva') }}</x-primary-button>
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
            const tipoSelect = document.getElementById('id_tipo_reserva');
            const hotelSelect = document.getElementById('id_hotel'); // Asegúrate de que este ID existe en tu HTML
            const bloqueComun = document.getElementById('bloque-comun');
            const bloqueIda = document.getElementById('bloque-ida');
            const bloqueVuelta = document.getElementById('bloque-vuelta');
            const bloqueSubmit = document.getElementById('bloque-submit');

            const numViajeros = document.getElementById('num_viajeros');
            const vehiculoSelect = document.getElementById('id_vehiculo');

            const fechaIdaInput = document.getElementById('fecha_entrada');
            const fechaVueltaInput = document.getElementById('fecha_vuelo_salida');

            const baseMin = '{{ $minDate }}';

            // --- Lógica de bloques (Ida/Vuelta) ---
            function toggleBloques() {
                const tipo = tipoSelect.value;
                [bloqueComun, bloqueIda, bloqueVuelta, bloqueSubmit].forEach(b => b?.classList.add('d-none'));

                if (!tipo) return;

                bloqueComun.classList.remove('d-none');
                bloqueSubmit.classList.remove('d-none');

                if (tipo === '1') bloqueIda.classList.remove('d-none');
                else if (tipo === '2') bloqueVuelta.classList.remove('d-none');
                else if (tipo === '3') {
                    bloqueIda.classList.remove('d-none');
                    bloqueVuelta.classList.remove('d-none');
                }
            }

            function filtrarVehiculos() {
                const n = parseInt(numViajeros.value || '0', 10);
                const hotelOption = hotelSelect.options[hotelSelect.selectedIndex];
                const hotelSeleccionado = hotelSelect.value !== "";

                const vehiculosPermitidos = hotelOption && hotelOption.dataset.vehiculos 
                    ? hotelOption.dataset.vehiculos.split(',') 
                    : [];

                Array.from(vehiculoSelect.options).forEach(option => {
                    if (!option.value) return;

                    const plazas = parseInt(option.dataset.plazas || '0', 10);
                    const idVehiculo = option.value.toString();

                    const cabeGente = (n <= plazas);


                    const tienePrecio = hotelSeleccionado && vehiculosPermitidos.includes(idVehiculo);

                    if (cabeGente && tienePrecio) {
                        option.disabled = false;
                        option.style.display = 'block'; 
                    } else {
                        option.disabled = true;
                        option.style.display = 'none'; 
                    }
                });

                if (vehiculoSelect.selectedOptions.length) {
                    if (vehiculoSelect.selectedOptions[0].disabled) {
                        vehiculoSelect.value = '';
                    }
                }
            }

            // --- Lógica de fechas (Mantenida igual) ---
            function actualizarMinimos() {
                const tipo = tipoSelect.value;
                if (fechaIdaInput) fechaIdaInput.min = baseMin;
                if (!fechaVueltaInput) return;

                // Lógica según tipos (Ida, Vuelta, Ida y Vuelta)
                const idIda = '{{ $tiposReserva->firstWhere("codigo", "IDA")->id_tipo_reserva ?? "1" }}';
                const idVuelta = '{{ $tiposReserva->firstWhere("codigo", "VUELTA")->id_tipo_reserva ?? "2" }}';
                const idAmbos = '{{ $tiposReserva->firstWhere("codigo", "IDA_VUELTA")->id_tipo_reserva ?? "3" }}';

                if (tipo === idIda || tipo === idVuelta) {
                    fechaVueltaInput.min = baseMin;
                } else if (tipo === idAmbos) {
                    let minVuelta = baseMin;
                    if (fechaIdaInput.value) {
                        const idaDate = new Date(fechaIdaInput.value + 'T00:00:00');
                        idaDate.setDate(idaDate.getDate() + 2);
                        const idaPlusTwo = idaDate.toISOString().slice(0, 10);
                        if (idaPlusTwo > minVuelta) minVuelta = idaPlusTwo;
                    }
                    fechaVueltaInput.min = minVuelta;
                }

                if (fechaVueltaInput.value && fechaVueltaInput.value < fechaVueltaInput.min) {
                    fechaVueltaInput.value = '';
                }
            }

            // --- Eventos ---
            if (tipoSelect) {
                tipoSelect.addEventListener('change', () => {
                    toggleBloques();
                    actualizarMinimos();
                });
            }

            if (hotelSelect) {
                hotelSelect.addEventListener('change', filtrarVehiculos);
            }

            if (numViajeros) {
                numViajeros.addEventListener('input', filtrarVehiculos);
            }

            if (fechaIdaInput) {
                fechaIdaInput.addEventListener('change', actualizarMinimos);
            }

            // Estado inicial
            toggleBloques();
            filtrarVehiculos();
            actualizarMinimos();
        });
    </script>
    @endpush
</x-app-layout>