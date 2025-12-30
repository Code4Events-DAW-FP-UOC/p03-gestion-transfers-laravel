<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-primary">{{ __('Nueva Zona / Destino') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.zonas.store') }}" method="POST">
                            @csrf

                            <div class="alert alert-info border-0 mb-4">
                                <h5 class="h6 mb-3"><i class="bi bi-geo-alt-fill"></i> {{ __('Información del Destino') }}</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="codigo" :value="__('Código Identificador')" />
                                        
                                        <input type="text" 
                                               name="codigo" 
                                               id="codigo" 
                                               value="{{ old('codigo') }}" 
                                               class="form-control @error('codigo') is-invalid @enderror" 
                                               placeholder="Ej: ZONA-A" 
                                               required>
                                        
                                        @error('codigo')
                                            <div class="invalid-feedback mt-2">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-8 mb-3">
                                        <x-input-label for="descripcion" :value="__('Nombre / Descripción')" />
                                        
                                        <input type="text" 
                                               name="descripcion" 
                                               id="descripcion" 
                                               value="{{ old('descripcion') }}" 
                                               class="form-control @error('descripcion') is-invalid @enderror" 
                                               placeholder="Ej: Playa de Muro" 
                                               required>

                                        @error('descripcion')
                                            <div class="invalid-feedback mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <a href="{{ route('admin.configuracion.index') }}" class="btn btn-outline-secondary">{{ __('Cancelar') }}</a>
                                <x-primary-button>{{ __('Guardar Zona') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>