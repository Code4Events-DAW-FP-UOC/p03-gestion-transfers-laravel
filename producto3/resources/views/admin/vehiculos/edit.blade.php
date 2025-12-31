{{-- resources/views/admin/vehiculos/edit.blade.php --}}
<x-admin-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Editar vehículo') }}: {{ $vehiculo->descripcion }}
            </h2>

            @if ($vehiculo->activo)
                {{-- Botón que abre el modal de desactivación --}}
                <button type="button"
                        class="btn btn-sm btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#deactivateVehiculoModal-{{ $vehiculo->id_vehiculo }}">
                    <i class="bi bi-power me-1"></i>
                    {{ __('Desactivar vehículo') }}
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.vehiculos.update', $vehiculo) }}" method="POST">
                    @method('PUT')
                    @include('admin.vehiculos._form', ['vehiculo' => $vehiculo])
                </form>
            </div>
        </div>
    </div>

    {{-- Modal de desactivación --}}
    @if ($vehiculo->activo)
        <x-ui.modal :id="'deactivateVehiculoModal-'.$vehiculo->id_vehiculo"
                    :title="__('Desactivar vehículo')"
                    size="md">
            <p class="mb-3">
                {{ __('Vas a desactivar este vehículo. Dejará de estar disponible para nuevas reservas, pero se mantendrá en el historial.') }}
            </p>
            <p class="mb-0">
                <strong>{{ $vehiculo->descripcion }}</strong><br>
                <span class="text-muted small">
                    {{ __('Esta acción no elimina datos, solo marca el vehículo como inactivo.') }}
                </span>
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
</x-admin-layout>