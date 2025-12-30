<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-gray-800">Configuración de Transfers</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.zonas.create') }}" class="btn btn-sm btn-outline-primary">Nueva Zona</a>
                <a href="{{ route('admin.vehiculos.create') }}" class="btn btn-sm btn-outline-primary">Nuevo Vehículo</a>
                <a href="{{ route('admin.precios.create') }}" class="btn btn-sm btn-primary">Nueva Tarifa</a>
            </div>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-success mt-3">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <div class="accordion mt-4 shadow-sm" id="accordionConfiguracion">
        
        {{-- TABLA 1: ZONAS --}}
        <div class="accordion-item border-0 mb-3 rounded shadow-sm">
            <h2 class="accordion-header" id="headingZonas">
                <button class="accordion-button fw-bold bg-white text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseZonas">
                    <i class="bi bi-geo-alt-fill me-2"></i> 1. Destinos y Zonas
                </button>
            </h2>
            <div id="collapseZonas" class="accordion-collapse collapse show" data-bs-parent="#accordionConfiguracion">
                <div class="accordion-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Código</th>
                                    <th>Descripción</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zonas as $zona)
                                <tr>
                                    <td class="ps-4"><span class="badge bg-light text-dark border">{{ $zona->codigo }}</span></td>
                                    <td>{{ $zona->descripcion }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.zonas.edit', $zona) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                        <button class="btn btn-sm btn-outline-danger ms-1" data-bs-toggle="modal" data-bs-target="#modal-delete-zona-{{ $zona->id_zona }}"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLA 2: VEHÍCULOS --}}
        <div class="accordion-item border-0 mb-3 rounded shadow-sm">
            <h2 class="accordion-header" id="headingVehiculos">
                <button class="accordion-button collapsed fw-bold bg-white text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVehiculos">
                    <i class="bi bi-truck me-2"></i> 2. Flota de Vehículos
                </button>
            </h2>
            <div id="collapseVehiculos" class="accordion-collapse collapse" data-bs-parent="#accordionConfiguracion">
                <div class="accordion-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Descripción</th>
                                    <th>Matrícula</th>
                                    <th>Plazas</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehiculos as $vehiculo)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $vehiculo->descripcion }}</td>
                                    <td>{{ $vehiculo->matricula }}</td>
                                    <td><span class="badge bg-info-subtle text-info border border-info">{{ $vehiculo->plazas }} pax</span></td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.vehiculos.edit', $vehiculo) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                        <button class="btn btn-sm btn-outline-danger ms-1" data-bs-toggle="modal" data-bs-target="#modal-delete-vehiculo-{{ $vehiculo->id_vehiculo }}"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLA 3: TARIFAS (PRECIOS) --}}
        <div class="accordion-item border-0 mb-3 rounded shadow-sm">
            <h2 class="accordion-header" id="headingPrecios">
                <button class="accordion-button collapsed fw-bold bg-white text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrecios">
                    <i class="bi bi-tags-fill me-2"></i> 3. Tarifas y Precios (Hotel - Vehículo)
                </button>
            </h2>
            <div id="collapsePrecios" class="accordion-collapse collapse" data-bs-parent="#accordionConfiguracion">
                <div class="accordion-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Hotel</th>
                                    <th>Vehículo</th>
                                    <th>Precio Base</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($precios as $precio)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $precio->hotel->nombre }}</div>
                                        <small class="text-muted">{{ $precio->hotel->zona->descripcion ?? 'Sin zona' }}</small>
                                    </td>
                                    <td>{{ $precio->vehiculo->descripcion }}</td>
                                    <td class="fw-bold text-success">{{ number_format($precio->precio, 2, ',', '.') }} €</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.precios.edit', $precio) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                        <button class="btn btn-sm btn-outline-danger ms-1" data-bs-toggle="modal" data-bs-target="#modal-delete-precio-{{ $precio->id_precio }}"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALES DE ELIMINACIÓN --}}

    {{-- Zonas --}}
    @foreach($zonas as $zona)
    <x-modal name="modal-delete-zona-{{ $zona->id_zona }}">
        <form method="post" action="{{ route('admin.zonas.destroy', $zona) }}" class="p-4">
            @csrf @method('delete')
            <h5 class="modal-title mb-3">Eliminar Zona</h5>
            <p>¿Estás seguro de que deseas eliminar la zona <strong>{{ $zona->descripcion }}</strong>?</p>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
            </div>
        </form>
    </x-modal>
    @endforeach

    {{-- Vehículos --}}
    @foreach($vehiculos as $vehiculo)
    <x-modal name="modal-delete-vehiculo-{{ $vehiculo->id_vehiculo }}">
        <form method="post" action="{{ route('admin.vehiculos.destroy', $vehiculo) }}" class="p-4">
            @csrf @method('delete')
            <h5 class="modal-title mb-3">Eliminar Vehículo</h5>
            <p>¿Deseas eliminar el vehículo <strong>{{ $vehiculo->descripcion }}</strong> ({{ $vehiculo->matricula }})?</p>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar Flota</button>
            </div>
        </form>
    </x-modal>
    @endforeach

    {{-- Precios --}}
    @foreach($precios as $precio)
    <x-modal name="modal-delete-precio-{{ $precio->id_precio }}">
        <form method="post" action="{{ route('admin.precios.destroy', $precio) }}" class="p-4">
            @csrf @method('delete')
            <h5 class="modal-title mb-3">Eliminar Tarifa</h5>
            <p>Vas a eliminar el precio de <strong>{{ number_format($precio->precio, 2) }}€</strong> para el hotel <strong>{{ $precio->hotel->nombre }}</strong>.</p>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar Tarifa</button>
            </div>
        </form>
    </x-modal>
    @endforeach

</x-app-layout>