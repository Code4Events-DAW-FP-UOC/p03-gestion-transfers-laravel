<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-primary">{{ __('Nueva Tarifa de Transfer') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.precios.store') }}" method="POST">
                            @csrf

                            {{-- SECCIÓN 1: VINCULACIÓN --}}
                            <div class="alert alert-info border-0 mb-4">
                                <h5 class="h6 mb-3"><i class="bi bi-link-45deg"></i> {{ __('1. Relación Hotel y Vehículo') }}</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="id_hotel" :value="__('Seleccionar Hotel')" />
                                        <select name="id_hotel" id="id_hotel" class="form-select @error('id_hotel') is-invalid @enderror" required>
                                            <option value="">{{ __('--- Seleccione un hotel ---') }}</option>
                                            @foreach ($hoteles as $hotel)
                                                <option value="{{ $hotel->id_hotel }}" {{ old('id_hotel') == $hotel->id_hotel ? 'selected' : '' }}>
                                                    {{ $hotel->nombre }} ({{ $hotel->zona->descripcion ?? 'Sin zona' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('id_hotel')" class="mt-2" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="id_vehiculo" :value="__('Seleccionar Vehículo')" />
                                        <select name="id_vehiculo" id="id_vehiculo" class="form-select @error('id_vehiculo') is-invalid @enderror" required>
                                            <option value="">{{ __('--- Seleccione un vehículo ---') }}</option>
                                            @foreach ($vehiculos as $vehiculo)
                                                <option value="{{ $vehiculo->id_vehiculo }}" {{ old('id_vehiculo') == $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                                    {{ $vehiculo->descripcion }} ({{ $vehiculo->plazas }} plazas)
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('id_vehiculo')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            {{-- SECCIÓN 2: PRECIO --}}
                            <div class="mb-4 mt-4">
                                <h5 class="h6 mb-3"><i class="bi bi-currency-euro"></i> {{ __('2. Importe de la Tarifa') }}</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <x-input-label for="precio" :value="__('Precio Base (€)')" />
                                        <div class="input-group">
                                            <x-text-input id="precio" name="precio" type="number" step="0.01" class="form-control" :value="old('precio')" placeholder="0.00" required />
                                            <span class="input-group-text bg-white">€</span>
                                        </div>
                                        <x-input-error :messages="$errors->get('precio')" class="mt-2" />
                                        <p class="text-muted small mt-2">Este precio se aplicará por trayecto.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-info-circle"></i> Si la tarifa ya existe para esta combinación, se actualizará el precio.
                                </p>
                                <div>
                                    <a href="{{ route('admin.configuracion.index') }}" class="btn btn-outline-secondary me-2">{{ __('Cancelar') }}</a>
                                    <x-primary-button class="px-4">
                                        {{ __('Establecer Tarifa') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>