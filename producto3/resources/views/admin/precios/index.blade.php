{{-- resources/views/admin/precios/index.blade.php --}}
<x-admin-layout>
    {{-- Título + botón "Nuevo" --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Precios') }}
            </h2>

            <a href="{{ route('admin.precios.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                {{ __('Nuevo precio') }}
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
                                <th>{{ __('Vehículo') }}</th>
                                <th>{{ __('Hotel') }}</th>
                                <th>{{ __('Precio') }}</th>
                                <th class="text-end">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($precios as $precio)
                                <tr>
                                    <td>{{ $precio->vehiculo->descripcion }}</td>
                                    <td>{{ $precio->hotel->nombre }}</td>
                                    <td>{{ number_format($precio->precio, 2, ',', '.') }} €</td>

                                    <td class="text-end">
                                        {{-- Editar --}}
                                        <x-buttons.icon-link
                                            :href="route('admin.precios.edit', $precio)"
                                            icon="pencil"
                                            title="{{ __('Editar') }}"
                                            variant="outline-secondary"
                                        />

                                        {{-- Botón que abre el modal de borrado --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deletePrecio-{{ $precio->id_precio }}"
                                                title="{{ __('Eliminar') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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

                {{-- Paginación (si la tienes activa) --}}
                {{-- <div class="mt-3">
                    {{ $precios->links() }}
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Modales de confirmación (uno por precio) --}}
    @foreach ($precios as $precio)
        <x-ui.modal
            :id="'deletePrecio-' . $precio->id_precio"
            :title="__('Eliminar precio')"
            size="sm"
        >
            <p class="mb-3">
                {{ __('¿Seguro que quieres eliminar el precio para el vehículo ":vehiculo" en el hotel ":hotel"?', [
                    'vehiculo' => $precio->vehiculo->descripcion,
                    'hotel'    => $precio->hotel->nombre,
                ]) }}
            </p>

            <x-slot name="footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('Cancelar') }}
                </button>

                <form action="{{ route('admin.precios.destroy', $precio) }}"
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