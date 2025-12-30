<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-primary">{{ __('Editar Usuario') }}: {{ $user->name }}</h2>
            <span class="badge {{ $user->isAdmin() ? 'bg-danger' : ($user->isHotel() ? 'bg-info text-dark' : 'bg-secondary') }}">
                Rol: {{ ucfirst($user->rol) }}
            </span>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.usuarios.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h5 class="text-primary mb-3 border-bottom pb-2"><i class="bi bi-person-gear me-2"></i>Datos de Cuenta</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nombre de Usuario (Login)</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Estado del Sistema</label>
                            <select name="activo" class="form-select">
                                <option value="1" {{ old('activo', $user->activo) == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('activo', $user->activo) == 0 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nueva Contraseña <small class="text-muted">(Opcional)</small></label>
                            <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para mantener actual">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    @if($user->rol === 'viajero')
                        <h5 class="text-primary mb-3 border-bottom pb-2"><i class="bi bi-card-list me-2"></i>Perfil del Viajero</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre</label>
                                <input type="text" name="nombre_viajero" class="form-control" value="{{ old('nombre_viajero', $user->viajero->nombre) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Primer Apellido</label>
                                <input type="text" name="apellido1" class="form-control" value="{{ old('apellido1', $user->viajero->apellido1) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $user->viajero->telefono) }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $user->viajero->direccion) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ciudad</label>
                                <input type="text" name="ciudad" class="form-control" value="{{ old('ciudad', $user->viajero->ciudad) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">País</label>
                                <input type="text" name="pais" class="form-control" value="{{ old('pais', $user->viajero->pais) }}">
                            </div>
                        </div>

                    @elseif($user->rol === 'hotel')
                        <h5 class="text-primary mb-3 border-bottom pb-2"><i class="bi bi-building me-2"></i>Perfil del Hotel</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nombre del Hotel</label>
                                <input type="text" name="nombre_hotel" class="form-control" value="{{ old('nombre_hotel', $user->hotel->nombre) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Teléfono Contacto</label>
                                <input type="text" name="telefono_hotel" class="form-control" value="{{ old('telefono_hotel', $user->hotel->telefono) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Comisión (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="comision" class="form-control" value="{{ old('comision', $user->hotel->comision) }}" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-5 border-top pt-3">
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            <i class="bi bi-save me-2"></i>Actualizar Usuario
                        </button>
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-link text-secondary">Cancelar y volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>