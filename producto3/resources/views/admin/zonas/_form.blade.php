{{-- resources/views/admin/zonas/_form.blade.php --}}
@csrf

<div class="row g-3">
    {{-- Descripción --}}
    <div class="col-md-6">
        <label for="descripcion" class="form-label">{{ __('Descripción') }}</label>
        <input id="descripcion"
               type="text"
               name="descripcion"
               class="form-control @error('descripcion') is-invalid @enderror"
               value="{{ old('descripcion', $zona->descripcion) }}"
               required>
        @error('descripcion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Código --}}
    <div class="col-md-6">
        <label for="codigo" class="form-label">{{ __('Código') }}</label>
        <input id="codigo"
               type="text"
               name="codigo"
               class="form-control @error('codigo') is-invalid @enderror"
               value="{{ old('codigo', $zona->codigo) }}"
               required>
        @error('codigo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr class="my-4">

<div class="d-flex justify-content-between">
    <a href="{{ route('admin.zonas.index') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>
        {{ $zona->exists ? __('Guardar cambios') : __('Crear zona') }}
    </button>
</div>