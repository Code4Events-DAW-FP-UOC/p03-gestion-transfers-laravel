<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-primary">{{ __('Editar Tarifa') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.precios.update', $precio->id_precio) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- SELECCIÓN DE HOTEL --}}
                                <div class="col-md-6 mb-3">
                                    <x-input-label for="id_hotel" :value="__('Hotel / Origen')" />
                                    <select name="id_hotel" id="id_hotel" class="form-select @error('id_hotel') is-invalid @enderror">
                                        @foreach($hoteles as $hotel)
                                            <option value="{{ $hotel->id_hotel }}" {{ old('id_hotel', $precio->id_hotel) == $hotel->id_hotel ? 'selected' : '' }}>
                                                {{ $hotel->nombre }} ({{ $hotel->zona->descripcion ?? 'Sin zona' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_hotel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- SELECCIÓN DE VEHÍCULO --}}
                                <div class="col-md-6 mb-3">
                                    <x-input-label for="id_vehiculo" :value="__('Vehículo')" />
                                    <select name="id_vehiculo" id="id_vehiculo" class="form-select @error('id_vehiculo') is-invalid @enderror">
                                        @foreach($vehiculos as $vehiculo)
                                            <option value="{{ $vehiculo->id_vehiculo }}" {{ old('id_vehiculo', $precio->id_vehiculo) == $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                                {{ $vehiculo->descripcion }} ({{ $vehiculo->plazas }} plazas)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_vehiculo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- PRECIO --}}
                                <div class="col-md-4 mb-3">
                                    <x-input-label for="precio" :value="__('Precio Tarifa (€)')" />
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="precio" id="precio" value="{{ old('precio', $precio->precio) }}" class="form-control @error('precio') is-invalid @enderror" required>
                                        <span class="input-group-text">€</span>
                                    </div>
                                    @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                                <a href="{{ route('admin.configuracion.index') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Actualizar Tarifa') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>