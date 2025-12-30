<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-primary">{{ __('Editar Zona / Destino') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.zonas.update', $zona->id_zona) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="alert alert-info border-0 mb-4">
                                <h5 class="h6 mb-3"><i class="bi bi-pencil-square"></i> {{ __('Información del Destino') }}</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="codigo" :value="__('Código Identificador')" />
                                        <input type="text" name="codigo" id="codigo" value="{{ old('codigo', $zona->codigo) }}" class="form-control @error('codigo') is-invalid @enderror" required>
                                        @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <x-input-label for="descripcion" :value="__('Nombre / Descripción')" />
                                        <input type="text" name="descripcion" id="descripcion" value="{{ old('descripcion', $zona->descripcion) }}" class="form-control @error('descripcion') is-invalid @enderror" required>
                                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <a href="{{ route('admin.configuracion.index') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Actualizar Zona') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>