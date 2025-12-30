<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-primary">{{ __('Nuevo Vehículo de Flota') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.vehiculos.store') }}" method="POST">
                            @csrf

                            <div class="alert alert-info border-0 mb-4">
                                <h5 class="h6 mb-3"><i class="bi bi-truck"></i> {{ __('Detalles Técnicos') }}</h5>
                                <div class="row">
                                    
                                    {{-- DESCRIPCIÓN --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="descripcion" :value="__('Descripción')" />
                                        <input type="text" 
                                               name="descripcion" 
                                               id="descripcion" 
                                               class="form-control @error('descripcion') is-invalid @enderror" 
                                               value="{{ old('descripcion') }}" 
                                               placeholder="Ej: Minibús Premium" 
                                               required>
                                        @error('descripcion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- MATRÍCULA (Validación de duplicados) --}}
                                    <div class="col-md-3 mb-3">
                                        <x-input-label for="matricula" :value="__('Matrícula')" />
                                        <input type="text" 
                                               name="matricula" 
                                               id="matricula" 
                                               class="form-control @error('matricula') is-invalid @enderror" 
                                               value="{{ old('matricula') }}" 
                                               placeholder="0000-BBB" 
                                               required>
                                        @error('matricula')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- PLAZAS --}}
                                    <div class="col-md-3 mb-3">
                                        <x-input-label for="plazas" :value="__('Nº Plazas')" />
                                        <input type="number" 
                                               name="plazas" 
                                               id="plazas" 
                                               min="1" 
                                               class="form-control @error('plazas') is-invalid @enderror" 
                                               value="{{ old('plazas', 4) }}" 
                                               required>
                                        @error('plazas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <a href="{{ route('admin.configuracion.index') }}" class="btn btn-outline-secondary">
                                    {{ __('Cancelar') }}
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    {{ __('Añadir Vehículo') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>