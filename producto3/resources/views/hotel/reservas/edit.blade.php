@php
    $minDate = now()->addDays(2)->format('Y-m-d');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Editar reserva') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @php
                            $fechaEntradaValue = old('fecha_entrada', $reserva->fecha_entrada ? $reserva->fecha_entrada->format('Y-m-d') : '');
                            $fechaVueltaValue = old('fecha_vuelo_salida', $reserva->fecha_vuelo_salida ? $reserva->fecha_vuelo_salida->format('Y-m-d') : '');
                            $horaEntradaValue = old('hora_entrada', $reserva->hora_entrada ? substr($reserva->hora_entrada, 0, 5) : '');
                            $horaVueltaValue = old('hora_vuelo_salida', $reserva->hora_vuelo_salida ? substr($reserva->hora_vuelo_salida, 0, 5) : '');
                            $tipoReservaValue = old('id_tipo_reserva', $reserva->id_tipo_reserva);
                        @endphp

                        <h1 class="h5 mb-4">{{ __('Detalles de la reserva') }} ({{ $reserva->localizador }})</h1>
                        @if (session('status'))
                            <div class="alert alert-success mt-3">{{ session('status') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                        @endif

                        {{-- IMPORTANTE: Cambia la ruta a hotel.reservas.update si estás en el panel de hotel --}}
                        <form action="{{ route('hotel.reservas.update', $reserva) }}" method="post">
                            @csrf
                            @method('PUT')

                            {{-- Tipo de reserva --}}
                            <div class="mb-4">
                                <x-input-label for="id_tipo_reserva" :value="__('Tipo de reserva')" />
                                <select name="id_tipo_reserva" id="id_tipo_reserva" class="form-select @error('id_tipo_reserva') is-invalid @enderror" required>
                                    <option value="">{{ __('Selecciona un tipo') }}</option>
                                    @foreach ($tiposReserva as $tipo)
                                        <option value="{{ $tipo->id_tipo_reserva }}" 
                                            {{ (string) $tipoReservaValue === (string) $tipo->id_tipo_reserva ? 'selected' : '' }}>
                                            {{ $tipo->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Bloque LLEGADA --}}
                            <div id="bloque-ida" class="d-none border-start border-primary border-4 ps-3 mb-4">
                                <h2 class="h6 mb-3 text-primary">{{ __('Datos de llegada') }}</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_entrada" :value="__('Fecha de llegada')" />
                                        <x-text-input id="fecha_entrada" name="fecha_entrada" type="date" class="w-100" :value="$fechaEntradaValue" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_entrada" :value="__('Hora de llegada')" />
                                        <x-text-input id="hora_entrada" name="hora_entrada" type="time" class="w-100" :value="$horaEntradaValue" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="numero_vuelo_entrada" :value="__('Nº de vuelo')" />
                                        <x-text-input id="numero_vuelo_entrada" name="numero_vuelo_entrada" type="text" class="w-100" :value="old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada)" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="origen_vuelo_entrada" :value="__('Origen')" />
                                        <x-text-input id="origen_vuelo_entrada" name="origen_vuelo_entrada" type="text" class="w-100" :value="old('origen_vuelo_entrada', $reserva->origen_vuelo_entrada)" />
                                    </div>
                                </div>
                            </div>

                            {{-- Bloque SALIDA --}}
                            <div id="bloque-vuelta" class="d-none border-start border-info border-4 ps-3 mb-4">
                                <h2 class="h6 mb-3 text-info">{{ __('Datos de salida') }}</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_vuelo_salida" :value="__('Fecha de salida')" />
                                        <x-text-input id="fecha_vuelo_salida" name="fecha_vuelo_salida" type="date" class="w-100" :value="$fechaVueltaValue" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_vuelo_salida" :value="__('Hora de salida')" />
                                        <x-text-input id="hora_vuelo_salida" name="hora_vuelo_salida" type="time" class="w-100" :value="$horaVueltaValue" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="numero_vuelo_salida" :value="__('Nº de vuelo')" />
                                        <x-text-input id="numero_vuelo_salida" name="numero_vuelo_salida" type="text" class="w-100" :value="old('numero_vuelo_salida', $reserva->numero_vuelo_salida)" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="destino_vuelo_salida" :value="__('Destino')" />
                                        <x-text-input id="destino_vuelo_salida" name="destino_vuelo_salida" type="text" class="w-100" :value="old('destino_vuelo_salida', $reserva->destino_vuelo_salida)" />
                                    </div>
                                </div>
                            </div>

                            {{-- Bloque Común (VIAJERO Y VEHÍCULO) --}}
                            <div id="bloque-comun" class="d-none">
                                <div class="mb-4">
                                    <x-input-label for="id_viajero" :value="__('Cambiar Viajero')" />
                                    <select name="id_viajero" id="id_viajero" class="form-select @error('id_viajero') is-invalid @enderror" required>
                                        @foreach ($viajeros as $v)
                                            <option value="{{ $v->id_viajero }}" {{ $reserva->id_viajero == $v->id_viajero ? 'selected' : '' }}>
                                                {{ $v->nombre }} {{ $v->apellidos }} ({{ $v->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="num_viajeros" :value="__('Número de viajeros')" />
                                        <x-text-input id="num_viajeros" name="num_viajeros" type="number" min="1" class="w-100" :value="old('num_viajeros', $reserva->num_viajeros)" />
                                    </div>
                                    <div class="col-md-8 mb-4">
                                        <x-input-label for="id_vehiculo" :value="__('Vehículo disponible')" />
                                        {{-- Pasamos los vehículos que el hotel TIENE precio asignado mediante data-permitidos --}}
                                        <select id="id_vehiculo" name="id_vehiculo" class="form-select" 
                                            data-permitidos="{{ $reserva->hotelGestor->precios->pluck('id_vehiculo')->implode(',') }}">
                                            <option value="">{{ __('Selecciona un vehículo') }}</option>
                                            @foreach ($vehiculos as $vehiculo)
                                                <option value="{{ $vehiculo->id_vehiculo }}"
                                                    data-plazas="{{ $vehiculo->plazas }}"
                                                    {{ (string) old('id_vehiculo', $reserva->id_vehiculo) === (string) $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                                    {{ $vehiculo->descripcion }} ({{ $vehiculo->plazas }} plazas)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <a href="{{ route('hotel.reservas.index') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                                <div id="bloque-submit" class="d-none">
                                    <x-primary-button>{{ __('Actualizar Reserva') }}</x-primary-button>
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
            const numViajeros = document.getElementById('num_viajeros');
            const vehiculoSelect = document.getElementById('id_vehiculo');
            
            const bloqueIda = document.getElementById('bloque-ida');
            const bloqueVuelta = document.getElementById('bloque-vuelta');
            const bloqueComun = document.getElementById('bloque-comun');
            const bloqueSubmit = document.getElementById('bloque-submit');

            // Obtenemos los IDs de vehículos permitidos desde el atributo data del select
            const vehiculosPermitidos = vehiculoSelect.dataset.permitidos ? vehiculoSelect.dataset.permitidos.split(',') : [];

            function filtrarTodo() {
                const tipo = tipoSelect.value;
                const n = parseInt(numViajeros.value || '0', 10);
                
                // 1. Mostrar/Ocultar bloques según tipo
                bloqueIda.classList.toggle('d-none', !['1', '3'].includes(tipo));
                bloqueVuelta.classList.toggle('d-none', !['2', '3'].includes(tipo));
                bloqueComun.classList.toggle('d-none', !tipo);
                bloqueSubmit.classList.toggle('d-none', !tipo);

                // 2. Filtrar vehículos por plazas y por contrato del hotel
                Array.from(vehiculoSelect.options).forEach(option => {
                    if (!option.value) return;
                    
                    const plazas = parseInt(option.dataset.plazas || '0', 10);
                    const idVehiculo = option.value.toString();
                    
                    // Condición: El hotel debe tener precio para este vehículo Y caber la gente
                    const tienePrecio = vehiculosPermitidos.includes(idVehiculo);
                    const cabeGente = (n <= plazas);

                    if (tienePrecio && cabeGente) {
                        option.disabled = false;
                        option.style.display = 'block';
                    } else {
                        option.disabled = true;
                        option.style.display = 'none';
                        if (option.selected) vehiculoSelect.value = '';
                    }
                });
            }

            tipoSelect.addEventListener('change', filtrarTodo);
            numViajeros.addEventListener('input', filtrarTodo);

            // Inicializar al cargar
            filtrarTodo();
        });
    </script>
    @endpush
</x-app-layout>