{{-- resources/views/admin/vehiculos/_form.blade.php --}}
@csrf

<div class="row g-3">
    {{-- Descripción --}}
    <div class="col-md-6">
        <label for="descripcion" class="form-label">{{ __('Descripción') }}</label>
        <input id="descripcion"
               type="text"
               name="descripcion"
               class="form-control @error('descripcion') is-invalid @enderror"
               value="{{ old('descripcion', $vehiculo->descripcion) }}"
               required>
        @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Matrícula --}}
    <div class="col-md-3">
        <label for="matricula" class="form-label">{{ __('Matrícula') }}</label>
        <input id="matricula"
               type="text"
               name="matricula"
               class="form-control @error('matricula') is-invalid @enderror"
               value="{{ old('matricula', $vehiculo->matricula) }}"
               required>
        @error('matricula')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Plazas --}}
    <div class="col-md-3">
        <label for="plazas" class="form-label">{{ __('Plazas') }}</label>
        <input id="plazas"
               type="number"
               min="1"
               max="99"
               name="plazas"
               class="form-control @error('plazas') is-invalid @enderror"
               value="{{ old('plazas', $vehiculo->plazas) }}"
               required>
        @error('plazas')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email --}}
    <div class="col-md-6">
        <label for="email" class="form-label">{{ __('Email de contacto') }}</label>
        <input id="email"
               type="email"
               name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $vehiculo->email) }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Activo --}}
    <div class="col-md-6 d-flex align-items-center">
        <div class="form-check mt-3">
            <input class="form-check-input"
                   type="checkbox"
                   id="activo"
                   name="activo"
                   value="1"
                   {{ old('activo', $vehiculo->activo) ? 'checked' : '' }}>
            <label class="form-check-label" for="activo">
                {{ __('Vehículo activo (disponible para reservas)') }}
            </label>
        </div>
    </div>
</div>

<hr class="my-4">

<div class="d-flex justify-content-between">
    <a href="{{ route('admin.vehiculos.index') }}" class="btn btn-outline-secondary">
        {{ __('Cancelar') }}
    </a>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>
        {{ $vehiculo->exists ? __('Guardar cambios') : __('Crear vehículo') }}
    </button>
</div>