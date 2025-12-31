{{-- resources/views/admin/precios/_form.blade.php --}}
@csrf

<div class="row g-3">
    {{-- Hotel --}}
    <div class="col-md-6">
        <label for="id_hotel" class="form-label">{{ __('Hotel') }}</label>
        <select id="id_hotel"
                name="id_hotel"
                class="form-select @error('id_hotel') is-invalid @enderror"
                required>
            <option value="">{{ __('Selecciona un hotel') }}</option>
            @foreach($hoteles as $hotel)
                <option value="{{ $hotel->id_hotel }}"
                    {{ (string) old('id_hotel', $precio->id_hotel) === (string) $hotel->id_hotel ? 'selected' : '' }}>
                    {{ $hotel->nombre }}
                </option>
            @endforeach
        </select>
        @error('id_hotel')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Vehículo --}}
    <div class="col-md-6">
        <label for="id_vehiculo" class="form-label">{{ __('Vehículo') }}</label>
        <select id="id_vehiculo"
                name="id_vehiculo"
                class="form-select @error('id_vehiculo') is-invalid @enderror"
                required>
            <option value="">{{ __('Selecciona un vehículo') }}</option>
            @foreach($vehiculos as $vehiculo)
                <option value="{{ $vehiculo->id_vehiculo }}"
                    {{ (string) old('id_vehiculo', $precio->id_vehiculo) === (string) $vehiculo->id_vehiculo ? 'selected' : '' }}>
                    {{ $vehiculo->descripcion }} ({{ $vehiculo->plazas }} {{ __('plazas') }})
                </option>
            @endforeach
        </select>
        @error('id_vehiculo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Precio --}}
    <div class="col-md-4">
        <label for="precio" class="form-label">{{ __('Tarifa / Precio') }}</label>
        <div class="input-group">
            <span class="input-group-text">€</span>
            <input id="precio"
                   type="number"
                   step="0.01"
                   min="0"
                   name="precio"
                   class="form-control @error('precio') is-invalid @enderror"
                   value="{{ old('precio', $precio->precio) }}"
                   required>
        </div>
        @error('precio')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr class="my-4">

<div class="d-flex justify-content-between">
    <a href="{{ route('admin.precios.index') }}" class="btn btn-outline-secondary">
        {{ __('Cancelar') }}
    </a>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>
        {{ $precio->exists ? __('Guardar cambios') : __('Crear precio') }}
    </button>
</div>