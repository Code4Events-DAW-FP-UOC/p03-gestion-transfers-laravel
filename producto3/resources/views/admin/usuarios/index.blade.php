<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-primary">{{ __('Gestión de Usuarios') }}</h2>
            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus"></i> {{ __('Nuevo Usuario') }}
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid mb-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i>
                        <div class="fw-bold">Error: {{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                    <div class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Por favor, revisa los errores:</div>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <small class="text-muted">ID: #{{ $user->id }}</small>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($user->isHotel())
                                        <span class="badge bg-info text-dark">Hotel</span>
                                    @else
                                        <span class="badge bg-secondary">Viajero</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->activo)
                                        <span class="text-success"><i class="bi bi-check-circle-fill"></i> Activo</span>
                                    @else
                                        <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($user->isAdmin())
                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal" data-bs-target="#modal-detalle-{{ $user->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-sm btn-light border">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if(auth()->id() !== $user->id && $user->rol !== 'admin')
                                        {{-- Botón ROJO y ACTIVO para otros usuarios --}}
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-1"
                                                data-bs-toggle="modal" data-bs-target="#modal-eliminar-{{ $user->id }}"
                                                title="Eliminar usuario">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        {{-- Botón GRIS y DESHABILITADO para el propio usuario (uno mismo) --}}
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1" 
                                                disabled 
                                                title="No puedes eliminar tu propia cuenta">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- BLOQUE DE MODALES CORREGIDO --}}
    @foreach ($usuarios as $user)
        @if(!$user->isAdmin())

            {{-- MODAL DE DETALLE --}}
            <x-modal name="modal-detalle-{{ $user->id }}">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Ficha de {{ $user->isHotel() ? 'Hotel' : 'Viajero' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    @if($user->isHotel())
                        @php $hotel = $user->hotel; @endphp
                        @if($hotel)
                            <dl class="row mb-0">
                                <dt class="col-sm-4 mb-2">Nombre Hotel</dt>
                                <dd class="col-sm-8 mb-2 fw-bold text-primary">{{ $hotel->nombre }}</dd>
                                <dt class="col-sm-4 mb-2">Email</dt>
                                <dd class="col-sm-8 mb-2">{{ $user->email }}</dd>
                                <dt class="col-sm-4 mb-2">Teléfono</dt>
                                <dd class="col-sm-8 mb-2">{{ $hotel->telefono ?? '—' }}</dd>
                                <dt class="col-sm-4 mb-2">Comisión</dt>
                                <dd class="col-sm-8 mb-2">{{ number_format($hotel->comision, 2, ',', '.') }}%</dd>
                                <dt class="col-sm-4 mb-2">Estado</dt>
                                <dd class="col-sm-8 mb-2">
                                    @if($user->activo)
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Activo</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Inactivo</span>
                                    @endif
                                </dd>
                            </dl>
                        @endif

                    @elseif($user->isViajero())
                        @php $viajero = $user->viajero; @endphp
                        @if($viajero)
                            <dl class="row mb-0">
                                <dt class="col-sm-4 mb-2">Nombre</dt>
                                <dd class="col-sm-8 mb-2">{{ $viajero->nombre }} {{ $viajero->apellido1 }}</dd>
                                <dt class="col-sm-4 mb-2">Dirección</dt>
                                <dd class="col-sm-8 mb-2">{{ $viajero->direccion ?? '—' }}</dd>
                                <dt class="col-sm-4 mb-2">Ciudad/País</dt>
                                <dd class="col-sm-8 mb-2">{{ $viajero->ciudad ?? '—' }}, {{ $viajero->pais ?? '—' }}</dd>
                                <dt class="col-sm-4 mb-2">Email</dt>
                                <dd class="col-sm-8 mb-2">{{ $user->email }}</dd>
                                <dt class="col-sm-4 mb-2">Teléfono</dt>
                                <dd class="col-sm-8 mb-2">{{ $viajero->telefono ?? '—' }}</dd>
                                <dt class="col-sm-4 mb-2">Estado</dt>
                                <dd class="col-sm-8 mb-2">
                                    @if($user->activo)
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Activo</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Inactivo</span>
                                    @endif
                                </dd>
                            </dl>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </x-modal>

            {{-- MODAL DE ELIMINACIÓN --}}
            <x-modal name="modal-eliminar-{{ $user->id }}">
                <form method="post" action="{{ route('admin.usuarios.destroy', $user->id) }}">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de eliminar a <strong>{{ $user->name }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </x-modal>

        @endif 
    @endforeach
</x-app-layout>