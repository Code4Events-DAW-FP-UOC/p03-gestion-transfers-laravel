{{-- resources/views/admin/tiposReserva/index.blade.php --}}
<x-admin-layout>
    {{-- Título + botón "Nuevo" --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Tipos de reserva') }}
            </h2>

            <a href="{{ route('admin.tiposReserva.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                {{ __('Nuevo tipo de reserva') }}
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
                                <th>{{ __('Descripción') }}</th>
                                <th>{{ __('Código') }}</th>
                                <th class="text-end">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tiposReservas as $tipoReserva)
                                <tr>
                                    <td>{{ $tipoReserva->descripcion }}</td>
                                    <td>{{ $tipoReserva->codigo }}</td>
                                    <td class="text-end">
                                        {{-- Editar --}}
                                        <x-buttons.icon-link
                                            :href="route('admin.tiposReserva.edit', $tipoReserva)"
                                            icon="pencil"
                                            title="{{ __('Editar') }}"
                                            variant="outline-secondary"
                                        />

                                        {{-- Botón que abre el modal de borrado --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteTipoReserva-{{ $tipoReserva->id_tipo_reserva }}"
                                                title="{{ __('Eliminar') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        {{ __('No hay registros para mostrar.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Si más adelante pagináis:
                <div class="mt-3">
                    {{ $tiposReservas->links() }}
                </div>
                --}}
            </div>
        </div>
    </div>

    {{-- Modales de confirmación (uno por tipo de reserva) --}}
    @foreach ($tiposReservas as $tipoReserva)
        <x-ui.modal
            :id="'deleteTipoReserva-' . $tipoReserva->id_tipo_reserva"
            :title="__('Eliminar tipo de reserva')"
            size="sm"
        >
            <p class="mb-3">
                {{ __('¿Seguro que quieres eliminar el tipo de reserva ":tipo"? Esta acción no se puede deshacer.', [
                    'tipo' => $tipoReserva->descripcion,
                ]) }}
            </p>

            <x-slot name="footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('Cancelar') }}
                </button>

                <form action="{{ route('admin.tiposReserva.destroy', $tipoReserva) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        {{ __('Eliminar') }}
                    </button>
                </form>
            </x-slot>
        </x-ui.modal>
    @endforeach
</x-admin-layout>