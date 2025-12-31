{{-- resources/views/admin/hoteles/_form.blade.php --}}
@php
    /** @var \App\Models\Hotel|null $hotel */
    $hotel = $hotel ?? null;
@endphp

<div class="row g-3">

    {{-- Nombre --}}
    <div class="col-12 col-md-6">
        <x-input-label for="nombre" :value="__('Nombre del hotel')" />
        <x-text-input
            id="nombre"
            name="nombre"
            type="text"
            class="w-100"
            :value="old('nombre', optional($hotel)->nombre)"
            required
        />
        <x-input-error :messages="$errors->get('nombre')" />
    </div>

    {{-- Zona --}}
    <div class="col-12 col-md-6">
        <x-input-label for="id_zona" :value="__('Zona')" />
        <select
            id="id_zona"
            name="id_zona"
            class="form-select @error('id_zona') is-invalid @enderror"
            required
        >
            <option value="">{{ __('Selecciona una zona') }}</option>
            @foreach($zonas as $zona)
                <option value="{{ $zona->id_zona }}"
                    {{ (string) old('id_zona', optional($hotel)->id_zona) === (string) $zona->id_zona ? 'selected' : '' }}>
                    {{ $zona->descripcion }}
                </option>
            @endforeach
        </select>
        @error('id_zona')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email --}}
    <div class="col-12 col-md-6">
        <x-input-label for="email" :value="__('Correo electrónico')" />
        <x-text-input
            id="email"
            name="email"
            type="email"
            class="w-100"
            :value="old('email', optional($hotel)->email)"
            required
        />
        <x-input-error :messages="$errors->get('email')" />
    </div>

    {{-- Teléfono --}}
    <div class="col-12 col-md-6">
        <x-input-label for="telefono" :value="__('Teléfono')" />
        <x-text-input
            id="telefono"
            name="telefono"
            type="text"
            class="w-100"
            :value="old('telefono', optional($hotel)->telefono)"
        />
        <x-input-error :messages="$errors->get('telefono')" />
    </div>

    {{-- Comisión --}}
    <div class="col-12 col-md-4">
        <x-input-label for="comision" :value="__('Comisión (%)')" />
        <x-text-input
            id="comision"
            name="comision"
            type="number"
            step="0.01"
            min="0"
            max="100"
            class="w-100"
            :value="old('comision', optional($hotel)->comision)"
        />
        <x-input-error :messages="$errors->get('comision')" />
        <small class="form-text text-muted">
            {{ __('Porcentaje de comisión sobre el importe de la reserva.') }}
        </small>
    </div>

    {{-- Activo --}}
    <div class="col-12 col-md-4 d-flex align-items-center">
        <div class="form-check form-switch mt-3">
            <input
                class="form-check-input"
                type="checkbox"
                role="switch"
                id="activo"
                name="activo"
                value="1"
                {{ old('activo', optional($hotel)->activo ?? true) ? 'checked' : '' }}
            >
            <label class="form-check-label" for="activo">
                {{ __('Hotel activo') }}
            </label>
        </div>
    </div>

</div>

{{-- Botones --}}
<div class="mt-4 d-flex justify-content-end gap-2">
    <a href="{{ route('admin.hoteles.index') }}" class="btn btn-outline-secondary">
        {{ __('Cancelar') }}
    </a>
    <button class="btn btn-primary type="submit">
        {{ $hotel ? __('Guardar cambios') : __('Crear hotel') }}
    </button>
</div>