<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-primary">{{ __('Crear Nuevo Usuario') }}</h2>
    </x-slot>

    <div class="py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        @if ($errors->any() || session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="fw-bold">
                                    <i class="bi bi-exclamation-triangle-fill"></i> 
                                    {{ __('Hubo un problema:') }}
                                </div>
                                <ul class="mb-0 mt-2">
                                    {{-- Errores de validación (Inputs vacíos, etc) --}}
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach

                                    {{-- Errores de sistema (Fallo en base de datos, modelos, etc) --}}
                                    @if(session('error'))
                                        <li>{{ session('error') }}</li>
                                    @endif
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.usuarios.store') }}" method="POST">
                            @csrf

                            {{-- SECCIÓN 1: SELECCIÓN DE TIPO DE USUARIO --}}
                            <div class="alert alert-primary border-0 mb-4">
                                <h5 class="h6 mb-3"><i class="bi bi-person-gear"></i> {{ __('1. Tipo de Perfil') }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input-label for="tipo_usuario" :value="__('¿Qué tipo de usuario deseas crear?')" />
                                        <select name="tipo_usuario" id="tipo_usuario" class="form-select @error('tipo_usuario') is-invalid @enderror" required>
                                            <option value="">{{ __('--- Seleccione una opción ---') }}</option>
                                            <option value="viajero" {{ old('tipo_usuario') == 'viajero' ? 'selected' : '' }}>Viajero</option>
                                            <option value="hotel" {{ old('tipo_usuario') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                                            <option value="admin" {{ old('tipo_usuario') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('tipo_usuario')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            {{-- SECCIÓN 2: CAMPOS DINÁMICOS --}}
                            
                            {{-- BLOQUE VIAJERO --}}
                            <div id="campos-viajero" class="d-none">
                                <h5 class="h6 mb-3 text-primary border-bottom pb-2"><i class="bi bi-person-vcard"></i> Datos del Viajero</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="nombre" :value="__('Nombre *')" />
                                        <x-text-input id="nombre" name="nombre" type="text" class="w-100" :value="old('nombre')" required />
                                        <x-input-error :messages="$errors->get('nombre')" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="apellido1" :value="__('Primer Apellido *')" />
                                        <x-text-input id="apellido1" name="apellido1" type="text" class="w-100" :value="old('apellido1')" required />
                                        <x-input-error :messages="$errors->get('apellido1')" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="apellido2" :value="__('Segundo Apellido (Opcional)')" />
                                        <x-text-input id="apellido2" name="apellido2" type="text" class="w-100" :value="old('apellido2')" />
                                        <x-input-error :messages="$errors->get('apellido2')" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="email_viajero" :value="__('Email *')" />
                                        <x-text-input id="email_viajero" name="email" type="email" class="w-100" :value="old('email')" required />
                                        <x-input-error :messages="$errors->get('email')" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="telefono" :value="__('Teléfono *')" />
                                        <x-text-input id="telefono" name="telefono" type="text" class="w-100" :value="old('telefono')" required />
                                        <x-input-error :messages="$errors->get('telefono')" />
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <x-input-label for="direccion" :value="__('Dirección *')" />
                                        <x-text-input id="direccion" name="direccion" type="text" class="w-100" :value="old('direccion')" required />
                                        <x-input-error :messages="$errors->get('direccion')" />
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="cp" :value="__('Código Postal *')" />
                                        <x-text-input id="cp" name="cp" type="text" class="w-100" :value="old('cp')" required />
                                        <x-input-error :messages="$errors->get('cp')" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="ciudad" :value="__('Ciudad *')" />
                                        <x-text-input id="ciudad" name="ciudad" type="text" class="w-100" :value="old('ciudad')" required />
                                        <x-input-error :messages="$errors->get('ciudad')" />
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="pais" :value="__('País *')" />
                                        <x-text-input id="pais" name="pais" type="text" class="w-100" :value="old('pais')" required />
                                        <x-input-error :messages="$errors->get('pais')" />
                                    </div>
                                </div>
                            </div>

                            {{-- BLOQUE HOTEL --}}
                            <div id="campos-hotel" class="d-none">
                                <h5 class="h6 mb-3 text-info border-bottom pb-2"><i class="bi bi-building"></i> Datos del Hotel</h5>
                                <div class="row">
                                    {{-- Nombre del Hotel --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="nombre_hotel" :value="__('Nombre del Hotel *')" />
                                        <x-text-input id="nombre_hotel" name="nombre_hotel" type="text" class="w-100" :value="old('nombre_hotel')" required />
                                        <x-input-error :messages="$errors->get('nombre_hotel')" />
                                    </div>

                                    {{-- Email del Hotel --}}
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="email_hotel" :value="__('Email del Hotel *')" />
                                        <x-text-input id="email_hotel" name="email" type="email" class="w-100" :value="old('email')" required />
                                        <x-input-error :messages="$errors->get('email')" />
                                    </div>

                                    {{-- Zona (Desplegable) --}}
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="id_zona" :value="__('Zona *')" />
                                        <select name="id_zona" id="id_zona" class="form-select @error('id_zona') is-invalid @enderror" required>
                                            <option value="">{{ __('--- Seleccione Zona ---') }}</option>
                                            @foreach ($zonas as $zona)
                                                <option value="{{ $zona->id_zona }}" {{ old('id_zona') == $zona->id_zona ? 'selected' : '' }}>
                                                    {{ $zona->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('id_zona')" />
                                    </div>

                                    {{-- Teléfono --}}
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="telefono_hotel" :value="__('Teléfono *')" />
                                        <x-text-input id="telefono_hotel" name="telefono" type="text" class="w-100" :value="old('telefono')" required />
                                        <x-input-error :messages="$errors->get('telefono')" />
                                    </div>

                                    {{-- Comisión --}}
                                    <div class="col-md-4 mb-3">
                                        <x-input-label for="comision" :value="__('Comisión (%) *')" />
                                        <div class="input-group">
                                            <x-text-input id="comision" name="comision" type="number" step="0.01" min="0" max="100" class="form-control" :value="old('comision')" required />
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <x-input-error :messages="$errors->get('comision')" />
                                    </div>
                                </div>
                            </div>

                            {{-- BLOQUE ADMIN --}}
                            <div id="campos-admin" class="d-none">
                                <h5 class="h6 mb-3 text-danger border-bottom pb-2"><i class="bi bi-shield-lock"></i> Datos de Administrador</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="name_admin" :value="__('Nombre Completo *')" />
                                        <x-text-input id="name_admin" name="name_admin" type="text" class="w-100" :value="old('name_admin')" />
                                        <x-input-error :messages="$errors->get('name_admin')" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="email_admin" :value="__('Email *')" />
                                        <x-text-input id="email_admin" name="email" type="email" class="w-100" :value="old('email_admin')" />
                                        <x-input-error :messages="$errors->get('email_admin')" />
                                    </div>
                                </div>
                            </div>

                            {{-- SECCIÓN CONTRASEÑA (Común para todos) --}}
                            <div id="bloque-password" class="d-none mt-4">
                                <h5 class="h6 mb-3 text-dark border-bottom pb-2"><i class="bi bi-key"></i> Seguridad</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="password" :value="__('Contraseña')" />
                                        <x-text-input id="password" name="password" type="password" class="w-100" autocomplete="new-password" />
                                        <x-input-error :messages="$errors->get('password')" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="w-100" />
                                        <x-input-error :messages="$errors->get('password_confirmation')" />
                                    </div>
                                </div>
                            </div>

                            {{-- BOTONES --}}
                            <div id="bloque-submit" class="d-none mt-5 pt-3 border-top">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary me-2">{{ __('Cancelar') }}</a>
                                    <x-primary-button>
                                        {{ __('Guardar Usuario') }}
                                    </x-primary-button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tipoSelect = document.getElementById('tipo_usuario');
                const bloques = {
                    'viajero': document.getElementById('campos-viajero'),
                    'hotel': document.getElementById('campos-hotel'),
                    'admin': document.getElementById('campos-admin')
                };
                const bloquePassword = document.getElementById('bloque-password');
                const bloqueSubmit = document.getElementById('bloque-submit');

                function toggleCampos() {
                    const tipo = tipoSelect.value;

                    // 1. Ocultar todos y DESHABILITAR sus inputs
                    Object.keys(bloques).forEach(key => {
                        const bloque = bloques[key];
                        bloque.classList.add('d-none');
                        // Buscamos todos los inputs, selects y textareas del bloque
                        bloque.querySelectorAll('input, select, textarea').forEach(input => {
                            input.disabled = true; 
                        });
                    });

                    // Ocultar bloques comunes por defecto
                    bloquePassword.classList.add('d-none');
                    bloqueSubmit.classList.add('d-none');

                    if (!tipo) return;

                    // 2. Mostrar el bloque seleccionado y HABILITAR sus inputs
                    if (bloques[tipo]) {
                        bloques[tipo].classList.remove('d-none');
                        bloques[tipo].querySelectorAll('input, select, textarea').forEach(input => {
                            input.disabled = false;
                        });

                        bloquePassword.classList.remove('d-none');
                        bloqueSubmit.classList.remove('d-none');
                    }
                }

                tipoSelect.addEventListener('change', toggleCampos);

                // Ejecutar al inicio por si hay errores de validación (old value)
                if(tipoSelect.value) toggleCampos();
            });
        </script>
    @endpush
</x-app-layout>