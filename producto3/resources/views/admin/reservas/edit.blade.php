@php
    $minDate = now()->addDays(2)->format('Y-m-d');

    $fechaEntradaValue = old('fecha_entrada', $reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('Y-m-d') : '');
    $fechaVueltaValue = old('fecha_vuelo_salida', $reserva->fecha_vuelo_salida ? \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('Y-m-d') : '');
    
    $horaEntradaValue = old('hora_entrada', $reserva->hora_entrada ? substr($reserva->hora_entrada, 0, 5) : '');
    $horaVueltaValue = old('hora_vuelo_salida', $reserva->hora_vuelo_salida ? substr($reserva->hora_vuelo_salida, 0, 5) : '');

    $tipoReservaValue = old('id_tipo_reserva', $reserva->id_tipo_reserva);
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">{{ __('Gestión Administrativa de Reserva') }}: <span class="text-primary">{{ $reserva->localizador }}</span></h2>
    </x-slot>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card shadow-sm border-primary">
                    <div class="card-body">
                        
                        <form action="{{ route('admin.reservas.update', $reserva) }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="alert alert-primary mb-4">
                                <h5 class="alert-heading h6 fw-bold mb-3"><i class="bi bi-gear-fill"></i> Control de Administración</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="estado" :value="__('Estado de la Reserva')" />
                                        <select name="estado" id="estado" class="form-select border-primary fw-bold">
                                            <option value="pendiente" {{ old('estado', $reserva->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="realizada" {{ old('estado', $reserva->estado) == 'realizada' ? 'selected' : '' }}>Realizada</option>
                                            <option value="cancelada" {{ old('estado', $reserva->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="id_precio" :value="__('Importe Total (€)')" />
                                        <x-text-input 
                                            id="id_precio_display" 
                                            type="text" 
                                            class="w-100 bg-light" 
                                            :value="optional($reserva->precio)->precio . ' €'" 
                                            readonly 
                                        />

                                        <input type="hidden" name="id_precio" value="{{ $reserva->id_precio }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

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

                            <div id="bloque-ida" class="d-none p-3 bg-light rounded mb-3">
                                <h2 class="h6 mb-3 text-primary fw-bold">{{ __('Datos de llegada (Aeropuerto → Hotel)') }}</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_entrada" :value="__('Fecha de llegada')" />
                                        <x-text-input id="fecha_entrada" name="fecha_entrada" type="date" class="w-100" :value="$fechaEntradaValue" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_entrada" :value="__('Hora de llegada')" />
                                        <x-text-input id="hora_entrada" name="hora_entrada" type="time" class="w-100" :value="$horaEntradaValue" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="numero_vuelo_entrada" :value="__('Nº Vuelo (Llegada)')" />
                                        <x-text-input id="numero_vuelo_entrada" name="numero_vuelo_entrada" type="text" class="w-100" :value="old('numero_vuelo_entrada', $reserva->numero_vuelo_entrada)" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="origen_vuelo_entrada" :value="__('Origen')" />
                                        <x-text-input id="origen_vuelo_entrada" name="origen_vuelo_entrada" type="text" class="w-100" :value="old('origen_vuelo_entrada', $reserva->origen_vuelo_entrada)" />
                                    </div>
                                </div>
                            </div>

                            <div id="bloque-vuelta" class="d-none p-3 bg-light rounded mb-3">
                                <h2 class="h6 mb-3 text-danger fw-bold">{{ __('Datos de salida (Hotel → Aeropuerto)') }}</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="fecha_vuelo_salida" :value="__('Fecha de salida')" />
                                        <x-text-input id="fecha_vuelo_salida" name="fecha_vuelo_salida" type="date" class="w-100" :value="$fechaVueltaValue" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="hora_vuelo_salida" :value="__('Hora de salida')" />
                                        <x-text-input id="hora_vuelo_salida" name="hora_vuelo_salida" type="time" class="w-100" :value="$horaVueltaValue" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="numero_vuelo_salida" :value="__('Nº Vuelo (Salida)')" />
                                        <x-text-input id="numero_vuelo_salida" name="numero_vuelo_salida" type="text" class="w-100" :value="old('numero_vuelo_salida', $reserva->numero_vuelo_salida)" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="destino_vuelo_salida" :value="__('Destino')" />
                                        <x-text-input id="destino_vuelo_salida" name="destino_vuelo_salida" type="text" class="w-100" :value="old('destino_vuelo_salida', $reserva->destino_vuelo_salida)" />
                                    </div>
                                </div>
                            </div>

                            <div id="bloque-comun" class="d-none">
                                <div class="mb-3">
                                    <x-input-label for="id_hotel" :value="__('Hotel')" />
                                    <select id="id_hotel" name="id_hotel" class="form-select @error('id_hotel') is-invalid @enderror">
                                        @foreach ($hoteles as $hotel)
                                            <option value="{{ $hotel->id_hotel }}" {{ old('id_hotel', $reserva->id_hotel_destino) == $hotel->id_hotel ? 'selected' : '' }}>
                                                {{ $hotel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="num_viajeros" :value="__('Pasajeros')" />
                                        <x-text-input id="num_viajeros" name="num_viajeros" type="number" class="w-100" :value="old('num_viajeros', $reserva->num_viajeros)" />
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <x-input-label for="id_vehiculo" :value="__('Vehículo')" />
                                        <select id="id_vehiculo" name="id_vehiculo" class="form-select">
                                            @foreach ($vehiculos as $vehiculo)
                                            @php
                                                $hotelesConTarifa = $precios->where('id_vehiculo', $vehiculo->id_vehiculo)->pluck('id_hotel')->toArray();
                                            @endphp
                                                <option value="{{ $vehiculo->id_vehiculo }}" 
                                                    data-plazas="{{ $vehiculo->plazas }}"
                                                    data-hoteles="{{ implode(',', $hotelesConTarifa) }}">
                                                    {{ $vehiculo->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.reservas.index') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                                <x-primary-button id="bloque-submit" class="d-none">{{ __('Actualizar Reserva') }}</x-primary-button>
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
                const hotelSelect = document.getElementById('id_hotel');
                const bloqueComun = document.getElementById('bloque-comun');
                const bloqueIda = document.getElementById('bloque-ida');
                const bloqueVuelta = document.getElementById('bloque-vuelta');
                const bloqueSubmit = document.getElementById('bloque-submit');
                const numViajerosInput = document.getElementById('num_viajeros');
                const vehiculoSelect = document.getElementById('id_vehiculo');

                function toggleBloques() {
                    const tipo = tipoSelect.value;
                    bloqueComun.classList.toggle('d-none', !tipo);
                    bloqueSubmit.classList.toggle('d-none', !tipo);
                    
                    bloqueIda.classList.toggle('d-none', tipo !== '1' && tipo !== '3');
                    bloqueVuelta.classList.toggle('d-none', tipo !== '2' && tipo !== '3');
                }

                function filtrarVehiculos() {
                    const hotelId = hotelSelect.value;
                    const numViajeros = parseInt(numViajerosInput?.value) || 0;

                    console.log("--- Filtrando para Hotel:", hotelId, "y Viajeros:", numViajeros, "---");

                    Array.from(vehiculoSelect.options).forEach(option => {
                        if (!option.value) return; 
                    
                        const hotelesData = option.dataset.hoteles || "";
                        const hotelesPermitidos = hotelesData.split(',');
                        const plazas = parseInt(option.dataset.plazas) || 0;
                    
                        const tienePrecio = hotelesPermitidos.includes(hotelId);
                        const tienePlazas = plazas >= numViajeros;
                    
                        console.log(`Vehículo ${option.text}: Precio: ${tienePrecio}, Plazas: ${tienePlazas}`);
                    
                        if (tienePrecio && tienePlazas) {
                            option.hidden = false;
                            option.disabled = false;
                            option.style.display = 'block'; 
                        } else {
                            option.hidden = true;
                            option.disabled = true;
                            option.style.display = 'none';
                        }
                    });
                
                }

                hotelSelect.addEventListener('change', filtrarVehiculos);
                if(numViajerosInput) numViajerosInput.addEventListener('input', filtrarVehiculos);

                filtrarVehiculos();
                toggleBloques();
            });
        </script>
    @endpush
</x-app-layout>