@php
    // Hoy + 48 horas ≈ 2 días vista YYYY-MM-DD
    $minDate = now()->addDays(2)->format('Y-m-d');
    // Obtenemos el ID del hotel del usuario actual
    $hotelId = Auth::user()->hotel->id_hotel;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{__('Nueva reserva para cliente')}}</h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h5 mb-4">{{ __('Crear reserva de transfer (Gestión de Hotel)') }}</h1>
                        
                        <p class="small text-muted mb-4">
                            {{ __('Como hotel, estás creando una reserva para un cliente. Selecciona el viajero y completa los datos del servicio.') }}
                        </p>

                        @if (session('status'))
                            <div class="alert alert-success mt-3">{{ session('status') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('hotel.reservas.store') }}" method="post">
                            @csrf
                            
                            {{-- ID del Hotel oculto, ya que es el hotel actual --}}
                            <input type="hidden" name="id_hotel" value="{{ $hotelId }}">

                            {{-- Selección de VIAJERO --}}
                            <div class="mb-4">
                                <x-input-label for="id_viajero" :value="__('Seleccionar Viajero (Cliente)')" />
                                <select name="id_viajero" id="id_viajero"
                                    class="form-select @error('id_viajero') is-invalid @enderror" required>
                                    <option value="">{{ __('Busca o selecciona un cliente') }}</option>
                                    @foreach ($viajeros as $viajero)
                                        <option value="{{ $viajero->id_viajero }}" {{ old('id_viajero') == $viajero->id_viajero ? 'selected' : '' }}>
                                            {{ $viajero->user->name }} ({{ $viajero->user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_viajero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

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
                            </div>

                            {{-- Bloque SOLO IDA --}}
                            <div id="bloque-ida" class="d-none">
                                <h2 class="h6 mb-3 text-primary">{{ __('Datos de llegada (Aeropuerto → Hotel)') }}</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_entrada" :value="__('Fecha de llegada')" />
                                        <x-text-input id="fecha_entrada" name="fecha_entrada" type="date" class="w-100"
                                            :value="old('fecha_entrada')" min="{{ $minDate }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_entrada" :value="__('Hora de llegada')" />
                                        <x-text-input id="hora_entrada" name="hora_entrada" type="time" class="w-100"
                                            :value="old('hora_entrada')" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="numero_vuelo_entrada" :value="__('Nº Vuelo llegada')" />
                                        <x-text-input id="numero_vuelo_entrada" name="numero_vuelo_entrada" type="text"
                                            class="w-100" :value="old('numero_vuelo_entrada')" placeholder="EJ: IB1234" />
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <x-input-label for="origen_vuelo_entrada" :value="__('Ciudad Origen')" />
                                        <x-text-input id="origen_vuelo_entrada" name="origen_vuelo_entrada" type="text"
                                            class="w-100" :value="old('origen_vuelo_entrada')" />
                                    </div>
                                </div>
                            </div>

                            {{-- Bloque SOLO VUELTA --}}
                            <div id="bloque-vuelta" class="d-none">
                                <h2 class="h6 mb-3 text-primary">{{ __('Datos de salida (Hotel → Aeropuerto)') }}</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_vuelo_salida" :value="__('Fecha de salida')" />
                                        <x-text-input id="fecha_vuelo_salida" name="fecha_vuelo_salida" type="date"
                                            class="w-100" :value="old('fecha_vuelo_salida')" min="{{ $minDate }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_vuelo_salida" :value="__('Hora de salida')" />
                                        <x-text-input id="hora_vuelo_salida" name="hora_vuelo_salida" type="time"
                                            class="w-100" :value="old('hora_vuelo_salida')" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="numero_vuelo_salida" :value="__('Nº Vuelo salida')" />
                                        <x-text-input id="numero_vuelo_salida" name="numero_vuelo_salida" type="text"
                                            class="w-100" :value="old('numero_vuelo_salida')" />
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <x-input-label for="destino_vuelo_salida" :value="__('Ciudad Destino')" />
                                        <x-text-input id="destino_vuelo_salida" name="destino_vuelo_salida" type="text"
                                            class="w-100" :value="old('destino_vuelo_salida')" />
                                    </div>
                                </div>
                            </div>

                            {{-- Bloque común (nº viajeros, vehículo) --}}
                            <div id="bloque-comun" class="d-none border-top pt-3">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="num_viajeros" :value="__('Número de viajeros')" />
                                        <x-text-input id="num_viajeros" name="num_viajeros" type="number" min="1"
                                            class="w-100" :value="old('num_viajeros', 1)" />
                                    </div>

                                    <div class="col-md-8 mb-4">
                                        <x-input-label for="id_vehiculo" :value="__('Vehículo asignado')" />
                                        <select id="id_vehiculo" name="id_vehiculo"
                                            class="form-select @error('id_vehiculo') is-invalid @enderror">
                                            <option value="">{{ __('Selecciona vehículo') }}</option>
                                            @foreach ($vehiculos as $vehiculo)
                                                <option value="{{ $vehiculo->id_vehiculo }}"
                                                    data-plazas="{{ $vehiculo->plazas }}" 
                                                    {{ old('id_vehiculo') == $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                                    {{ $vehiculo->descripcion }} (Máx: {{ $vehiculo->plazas }} plazas)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <a href="{{ route('hotel.reservas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                                <div id="bloque-submit" class="d-none">
                                    <x-primary-button type="submit">{{ __('Guardar Reserva Cliente') }}</x-primary-button>
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
                const bloqueComun = document.getElementById('bloque-comun');
                const bloqueIda = document.getElementById('bloque-ida');
                const bloqueVuelta = document.getElementById('bloque-vuelta');
                const bloqueSubmit = document.getElementById('bloque-submit');

                const numViajeros = document.getElementById('num_viajeros');
                const vehiculoSelect = document.getElementById('id_vehiculo');

                const fechaIdaInput = document.getElementById('fecha_entrada');
                const fechaVueltaInput = document.getElementById('fecha_vuelo_salida');

                // Mínimo visual: hoy + 2 días (lo define PHP arriba como $minDate)
                const baseMin = '{{ $minDate }}';

                // Mostrar / ocultar bloques según tipo de reserva
                function toggleBloques() {
                    const tipo = tipoSelect.value; // "1", "2", "3"...

                    // Ocultamos todo
                    bloqueComun.classList.add('d-none');
                    bloqueIda.classList.add('d-none');
                    bloqueVuelta.classList.add('d-none');
                    bloqueSubmit.classList.add('d-none');

                    if (!tipo) {
                        return;
                    }

                    // Siempre que haya tipo seleccionado mostramos bloque común + botón
                    bloqueComun.classList.remove('d-none');
                    bloqueSubmit.classList.remove('d-none');

                    // Solo ida
                    if (tipo === '1') {
                        bloqueIda.classList.remove('d-none');
                    }
                    // Solo vuelta
                    else if (tipo === '2') {
                        bloqueVuelta.classList.remove('d-none');
                    }
                    // Ida y vuelta
                    else if (tipo === '3') {
                        bloqueIda.classList.remove('d-none');
                        bloqueVuelta.classList.remove('d-none');
                    }
                }

                // Filtrar vehículos según nº de viajeros
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

                // Reglas de mínimos para las fechas según tipo de reserva
                function actualizarMinimos() {
                    const tipo = tipoSelect.value; // "1", "2" o "3"

                    // Siempre: la ida no puede ser antes de baseMin
                    if (fechaIdaInput) {
                        fechaIdaInput.min = baseMin;
                    }

                    if (!fechaVueltaInput) return;

                    // --- SOLO IDA ---
                    if (tipo === '{{ $tiposReserva->firstWhere("codigo", "IDA")->id_tipo_reserva ?? "1" }}') {
                        fechaVueltaInput.min = baseMin;
                        return;
                    }

                    // --- SOLO VUELTA ---
                    if (tipo === '{{ $tiposReserva->firstWhere("codigo", "VUELTA")->id_tipo_reserva ?? "2" }}') {
                        fechaVueltaInput.min = baseMin;

                        if (fechaVueltaInput.value && fechaVueltaInput.value < baseMin) {
                            fechaVueltaInput.value = '';
                        }
                        return;
                    }

                    // --- IDA + VUELTA ---
                    if (tipo === '{{ $tiposReserva->firstWhere("codigo", "IDA_VUELTA")->id_tipo_reserva ?? "3" }}') {
                        let minVuelta = baseMin;

                        if (fechaIdaInput.value) {
                            const idaDate = new Date(fechaIdaInput.value + 'T00:00:00');
                            idaDate.setDate(idaDate.getDate() + 2);
                            const idaPlusOne = idaDate.toISOString().slice(0, 10);

                            // La vuelta no puede ser antes del día siguiente a la ida ni antes de baseMin (48h)
                            if (idaPlusOne > minVuelta) {
                                minVuelta = idaPlusOne;
                            }
                        }

                        fechaVueltaInput.min = minVuelta;

                        if (fechaVueltaInput.value && fechaVueltaInput.value < minVuelta) {
                            fechaVueltaInput.value = '';
                        }
                        return;
                    }

                    // Caso por defecto
                    fechaVueltaInput.min = baseMin;
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

                // Estado inicial (por si viene de old())
                toggleBloques();
                filtrarVehiculosPorPlazas();
                actualizarMinimos();
            });
        </script>
    @endpush
</x-app-layout>