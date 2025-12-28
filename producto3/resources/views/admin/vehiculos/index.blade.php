{{-- resources/views/admin/hoteles/index.blade.php --}}
<x-admin-layout>
    {{-- Título + botón "Nuevo" --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Vehículos') }}
            </h2>
            <a href="{{ route('admin.vehiculos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                {{ __('Nuevo vehiculo') }}
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                {{-- Tabla de resultados --}}
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Estado') }}</th>
                                <th>{{ __('Descripción') }}</th>
                                <th>{{ __('Plazas') }}</th>
                                <th>{{ __('Matricula') }}</th>
                                <th>{{ __('Correo electrónico') }}</th>
                                <th class="text-end">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehiculos as $vehiculo)
                                <tr>
                                    <td>
                                        @if($vehiculo->activo)
                                            <span class="badge bg-success">{{ __('Activo') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Inactivo') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $vehiculo->descripcion }}</td>
                                    <td>{{ $vehiculo->plazas }}</td>
                                    <td>{{ $vehiculo->matricula }}</td>
                                    <td>{{ $vehiculo->email }}</td>
                                    <td class="text-end">
                                        {{-- Editar --}}
                                        <x-buttons.icon-link
                                            :href="route('admin.vehiculos.edit', $vehiculo)"
                                            :title="__('Editar vehículo')"
                                            icon="pencil"
                                            variant="outline-primary"
                                            size="sm"
                                        />

                                        {{-- Desactivar (solo si está activo) --}}
                                        @if($vehiculo->activo)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger ms-1"
                                                    title="{{ __('Desactivar vehículo') }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deactivateVehiculoModal-{{ $vehiculo->id_vehiculo }}">
                                                <i class="bi bi-power"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                {{-- Modal de desactivación para cada fila --}}
                                @if($vehiculo->activo)
                                    <x-ui.modal :id="'deactivateVehiculoModal-'.$vehiculo->id_vehiculo"
                                                :title="__('Desactivar vehículo')"
                                                size="md">
                                        <p class="mb-3">
                                            {{ __('¿Seguro que quieres desactivar este vehículo?') }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>{{ $vehiculo->descripcion }}</strong>
                                            ({{ $vehiculo->matricula }})
                                        </p>

                                        <x-slot name="footer">
                                            <button type="button"
                                                    class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                {{ __('Cancelar') }}
                                            </button>

                                            <form action="{{ route('admin.vehiculos.destroy', $vehiculo) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-power me-1"></i>
                                                    {{ __('Desactivar') }}
                                                </button>
                                            </form>
                                        </x-slot>
                                    </x-ui.modal>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        {{ __('No hay registros para mostrar.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Paginación --}}
                @if ($vehiculos->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            {{ __('Mostrando :from a :to de :total resultados', [
                                'from'  => $vehiculos->firstItem(),
                                'to'    => $vehiculos->lastItem(),
                                'total' => $vehiculos->total(),
                            ]) }}
                        </div>
                        {{ $vehiculos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>