@php
    // Hoy + 48 horas para la validación de fecha mínima
    $minDate = now()->addDays(2)->format('Y-m-d');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-primary">{{ __('Nueva Reserva (Panel Admin)') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-11">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        
                        <form action="{{ route('admin.reservas.store') }}" method="POST">
                            @csrf

                            {{-- SECCIÓN 1: CLIENTE / VIAJERO --}}
                            <div class="alert alert-info border-0 mb-4">
                                <h5 class="h6 mb-3"><i class="bi bi-person-fill"></i> {{ __('1. Asignar Cliente') }}</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-input-label for="id_viajero" :value="__('Seleccionar Viajero')" />
                                        <select name="id_viajero" id="id_viajero" class="form-select select2 @error('id_viajero') is-invalid @enderror" required>
                                            <option value="">{{ __('--- Buscar por nombre o email ---') }}</option>
                                            @foreach ($viajeros as $viajero)
                                                <option value="{{ $viajero->id_viajero }}" {{ old('id_viajero') == $viajero->id_viajero ? 'selected' : '' }}>
                                                    {{ $viajero->nombre }} {{ $viajero->apellidos }} ({{ $viajero->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('id_viajero')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- SECCIÓN 2: TIPO DE TRAYECTO --}}
                            <div class="mb-4">
                                <x-input-label for="id_tipo_reserva" :value="__('2. Tipo de Trayecto')" />
                                <select name="id_tipo_reserva" id="id_tipo_reserva" class="form-select @error('id_tipo_reserva') is-invalid @enderror" required>
                                    <option value="">{{ __('Seleccione el tipo de transfer') }}</option>
                                    @foreach ($tiposReserva as $tipo)
                                        <option value="{{ $tipo->id_tipo_reserva }}" 
                                            {{ (string) old('id_tipo_reserva') === (string) $tipo->id_tipo_reserva ? 'selected' : '' }}>
                                            {{ $tipo->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('id_tipo_reserva')" class="mt-2" />
                            </div>

                            <div class="row">
                                {{-- BLOQUE IDA: LLEGADA AL AEROPUERTO --}}
                                <div id="bloque-ida" class="col-md-6 d-none">
                                    <div class="card bg-light border-0 mb-3">
                                        <div class="card-body">
                                            <h6 class="text-primary border-bottom pb-2 mb-3">{{ __('Datos de LLEGADA (Aeropuerto → Hotel)') }}</h6>
                                            <div class="mb-3">
                                                <x-input-label for="fecha_entrada" :value="__('Fecha de llegada')" />
                                                <x-text-input id="fecha_entrada" name="fecha_entrada" type="date" class="w-100" :value="old('fecha_entrada')" min="{{ $minDate }}" />
                                                <x-input-error :messages="$errors->get('fecha_entrada')" />
                                            </div>
                                            <div class="mb-3">
                                                <x-input-label for="hora_entrada" :value="__('Hora de llegada')" />
                                                <x-text-input id="hora_entrada" name="hora_entrada" type="time" class="w-100" :value="old('hora_entrada')" />
                                                <x-input-error :messages="$errors->get('hora_entrada')" />
                                            </div>
                                            <div class="mb-3">
                                                <x-input-label for="numero_vuelo_entrada" :value="__('Nº Vuelo de llegada')" />
                                                <x-text-input id="numero_vuelo_entrada" name="numero_vuelo_entrada" type="text" class="w-100" :value="old('numero_vuelo_entrada')" placeholder="EJ: IB1234" />
                                                <x-input-error :messages="$errors->get('numero_vuelo_entrada')" />
                                            </div>
                                            <div class="mb-0">
                                                <x-input-label for="origen_vuelo_entrada" :value="__('Ciudad de Origen')" />
                                                <x-text-input id="origen_vuelo_entrada" name="origen_vuelo_entrada" type="text" class="w-100" :value="old('origen_vuelo_entrada')" />
                                                <x-input-error :messages="$errors->get('origen_vuelo_entrada')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- BLOQUE VUELTA: SALIDA AL AEROPUERTO --}}
                                <div id="bloque-vuelta" class="col-md-6 d-none">
                                    <div class="card bg-light border-0 mb-3">
                                        <div class="card-body">
                                            <h6 class="text-danger border-bottom pb-2 mb-3">{{ __('Datos de SALIDA (Hotel → Aeropuerto)') }}</h6>
                                            <div class="mb-3">
                                                <x-input-label for="fecha_vuelo_salida" :value="__('Fecha de salida')" />
                                                <x-text-input id="fecha_vuelo_salida" name="fecha_vuelo_salida" type="date" class="w-100" :value="old('fecha_vuelo_salida')" min="{{ $minDate }}" />
                                                <x-input-error :messages="$errors->get('fecha_vuelo_salida')" />
                                            </div>
                                            <div class="mb-3">
                                                <x-input-label for="hora_vuelo_salida" :value="__('Hora de salida')" />
                                                <x-text-input id="hora_vuelo_salida" name="hora_vuelo_salida" type="time" class="w-100" :value="old('hora_vuelo_salida')" />
                                                <x-input-error :messages="$errors->get('hora_vuelo_salida')" />
                                            </div>
                                            <div class="mb-3">
                                                <x-input-label for="numero_vuelo_salida" :value="__('Nº Vuelo de salida')" />
                                                <x-text-input id="numero_vuelo_salida" name="numero_vuelo_salida" type="text" class="w-100" :value="old('numero_vuelo_salida')" placeholder="EJ: IB5678" />
                                                <x-input-error :messages="$errors->get('numero_vuelo_salida')" />
                                            </div>
                                            <div class="mb-0">
                                                <x-input-label for="destino_vuelo_salida" :value="__('Ciudad de Destino')" />
                                                <x-text-input id="destino_vuelo_salida" name="destino_vuelo_salida" type="text" class="w-100" :value="old('destino_vuelo_salida')" />
                                                <x-input-error :messages="$errors->get('destino_vuelo_salida')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- BLOQUE COMÚN: LOGÍSTICA --}}
                            <div id="bloque-comun" class="d-none mt-4">
                                <h5 class="h6 mb-3"><i class="bi bi-geo-alt-fill"></i> {{ __('3. Detalles de Alojamiento y Vehículo') }}</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <x-input-label for="id_hotel" :value="__('Hotel de referencia')" />
                                        <select id="id_hotel" name="id_hotel" class="form-select @error('id_hotel') is-invalid @enderror">
                                            <option value="">{{ __('Selecciona un hotel') }}</option>
                                            @foreach ($hoteles as $hotel)
                                                <option value="{{ $hotel->id_hotel }}" {{ (string) old('id_hotel') === (string) $hotel->id_hotel ? 'selected' : '' }} data-vehiculos="{{ $hotel->precios->pluck('id_vehiculo')->implode(',') }}">
                                                    {{ $hotel->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('id_hotel')" />
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="num_viajeros" :value="__('Número de personas')" />
                                        <x-text-input id="num_viajeros" name="num_viajeros" type="number" min="1" max="{{ $maxPlazas }}" class="w-100" :value="old('num_viajeros', 1)" />
                                        <x-input-error :messages="$errors->get('num_viajeros')" />
                                    </div>

                                    <div class="col-md-8 mb-3">
                                        <x-input-label for="id_vehiculo" :value="__('Vehículo asignado')" />
                                        <select id="id_vehiculo" name="id_vehiculo" class="form-select @error('id_vehiculo') is-invalid @enderror">
                                            <option value="">{{ __('--- Seleccione vehículo ---') }}</option>
                                            @foreach ($vehiculos as $vehiculo)
                                                <option value="{{ $vehiculo->id_vehiculo }}" data-plazas="{{ $vehiculo->plazas }}" 
                                                    {{ (string) old('id_vehiculo') === (string) $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                                    {{ $vehiculo->descripcion }} (Máx: {{ $vehiculo->plazas }} plazas)
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('id_vehiculo')" />
                                    </div>
                                </div>
                            </div>

                            <div id="bloque-submit" class="d-none mt-5 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted small">
                                        <i class="bi bi-info-circle"></i> 
                                    </p>
                                    <div>
                                        <a href="{{ route('admin.reservas.index') }}" class="btn btn-outline-secondary me-2">{{ __('Cancelar') }}</a>
                                        <x-primary-button class="btn-lg px-5">
                                            {{ __('Crear Reserva') }}
                                        </x-primary-button>
                                    </div>
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
                const hotelSelect = document.getElementById('id_hotel');
                const tipoSelect = document.getElementById('id_tipo_reserva');
                const bloqueComun = document.getElementById('bloque-comun');
                const bloqueIda = document.getElementById('bloque-ida');
                const bloqueVuelta = document.getElementById('bloque-vuelta');
                const bloqueSubmit = document.getElementById('bloque-submit');
                const numViajeros = document.getElementById('num_viajeros');
                const vehiculoSelect = document.getElementById('id_vehiculo');

                function toggleBloques() {
                    const tipo = tipoSelect.value;
                    bloqueComun.classList.add('d-none');
                    bloqueIda.classList.add('d-none');
                    bloqueVuelta.classList.add('d-none');
                    bloqueSubmit.classList.add('d-none');

                    if (!tipo) return;

                    bloqueComun.classList.remove('d-none');
                    bloqueSubmit.classList.remove('d-none');

                    if (tipo === '1') { // Ida
                        bloqueIda.classList.remove('d-none');
                    } else if (tipo === '2') { // Vuelta
                        bloqueVuelta.classList.remove('d-none');
                    } else if (tipo === '3') { // Ida y Vuelta
                        bloqueIda.classList.remove('d-none');
                        bloqueVuelta.classList.remove('d-none');
                    }
                }

                function filtrarVehiculos() {
                    const n = parseInt(numViajeros.value || '0', 10);
                    const hotelOption = hotelSelect.options[hotelSelect.selectedIndex];
                    const vehiculosConTarifa = hotelOption.dataset.vehiculos 
                    ? hotelOption.dataset.vehiculos.split(',') 
                    : [];
                    Array.from(vehiculoSelect.options).forEach(option => {
                        if (!option.value) return;

                        const plazas = parseInt(option.dataset.plazas || '0', 10);
                        const idVehiculo = option.value.toString();

                        const tieneCapacidad = (n <= plazas);
                        const tieneTarifa = vehiculosConTarifa.includes(idVehiculo);

                        if (tieneCapacidad && tieneTarifa) {
                            option.disabled = false;
                            option.style.display = 'block';
                        } else {
                            option.disabled = true;
                            option.style.display = 'none';
                        }
                    });
                    if (vehiculoSelect.selectedOptions.length && vehiculoSelect.selectedOptions[0].disabled) {
                        vehiculoSelect.value = '';
                    }
                }

                tipoSelect.addEventListener('change', toggleBloques);
                hotelSelect.addEventListener('change', filtrarVehiculos);
                numViajeros.addEventListener('input', filtrarVehiculos);

                // Estado inicial
                toggleBloques();
                filtrarVehiculos();
            });
        </script>
    @endpush
</x-app-layout>