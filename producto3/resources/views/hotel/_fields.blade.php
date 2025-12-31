{{-- resources/views/hotel/_fields.blade.php --}}

<div class="row mb-3">
    {{-- Nombre del hotel --}}
    <div class="col-md-8 mb-3">
        <x-input-label for="hotel_nombre" :value="__('Nombre del hotel')" />
        <x-text-input id="hotel_nombre" name="nombre" type="text" class="mt-1 w-100" :value="old('nombre', $hotel->nombre ?? '')" required />
        <x-input-error :messages="$errors->get('nombre')" />
    </div>
    {{-- Teléfono --}}
    <div class="col-md-4 mb-3">
        <x-input-label for="hotel_telefono" :value="__('Teléfono (opcional)')" />
        <x-text-input id="hotel_telefono" name="telefono" type="text" class="mt-1 w-100" :value="old('telefono', $hotel->telefono ?? '')" />
        <x-input-error :messages="$errors->get('telefono')" />
    </div>
</div>
<div class="row">
    {{-- Zona --}}
    <div class="col-md-6 mb-3">
        <x-input-label for="hotel_zona" :value="__('Zona')" />
        <select id="hotel_zona" name="id_zona" class="form-select @error('id_zona') is-invalid @enderror" required>
            <option value="">{{ __('Selecciona una zona') }}</option>

            @if (!empty($zonas))
                @foreach ($zonas as $zona)
                    <option value="{{ $zona->id_zona }}"
                        {{ (string) old('id_zona', $hotel->id_zona ?? '') === (string) $zona->id_zona ? 'selected' : '' }}>
                        {{ $zona->nombre ?? 'Zona ' . $zona->id_zona }}
                    </option>
                @endforeach
            @endif
        </select>

        @error('id_zona')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Comisión --}}
    <div class="col-md-6 mb-3">
        <x-input-label for="hotel_comision" :value="__('Comisión (%)')" />
        @if ($user->isAdmin())
            {{-- En admin, editable --}}
            <x-text-input id="hotel_comision" name="comision" type="number" step="0.01" min="0"
                max="100" class="w-100" :value="old('comision', $hotel->comision ?? '')" />
        @else
            {{-- Para hotel, solo lectura --}}
            <x-text-input id="hotel_comision" name="comision" type="number" step="0.01" min="0"
                max="100" class="w-100" :value="old('comision', $hotel->comision ?? '')" readonly />
        @endif


        <x-input-error :messages="$errors->get('comision')" />

        <small class="form-text text-muted">
            {{ __('Porcentaje de comisión que recibe el hotel sobre el precio del transfer.') }}
            @unless ($user->isAdmin())
                {{ __('Este valor solo puede modificarlo la administración.') }}
            @endunless
        </small>
    </div>
</div>
