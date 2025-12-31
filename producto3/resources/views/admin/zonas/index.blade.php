{{-- resources/views/admin/zonas/index.blade.php --}}
<x-admin-layout>
    {{-- Título + botón "Nueva" --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Zonas') }}
            </h2>
            <a href="{{ route('admin.zonas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                {{ __('Nueva zona') }}
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
                            @forelse ($zonas as $zona)
                                <tr>
                                    <td>{{ $zona->descripcion }}</td>
                                    <td>{{ $zona->codigo }}</td>
                                    <td class="text-end">
                                        {{-- Editar --}}
                                        <x-buttons.icon-link
                                            :href="route('admin.zonas.edit', $zona)"
                                            icon="pencil"
                                            :title="__('Editar')"
                                            variant="outline-secondary"
                                            size="sm"
                                        />

                                        {{-- Abrir modal de confirmación --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteZona-{{ $zona->id_zona }}"
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
                @if(method_exists($zonas, 'links'))
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            {{ __('Mostrando :from a :to de :total resultados', [
                                'from'  => $zonas->firstItem(),
                                'to'    => $zonas->lastItem(),
                                'total' => $zonas->total(),
                            ]) }}
                        </div>
                        {{ $zonas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modales de confirmación (fuera de la tabla) --}}
    @foreach ($zonas as $zona)
        <x-ui.modal
            :id="'deleteZona-' . $zona->id_zona"
            :title="__('Eliminar zona')"
            size="sm"
        >
            <p class="mb-3">
                {{ __('¿Seguro que quieres eliminar la zona ":zona"? Esta acción no se puede deshacer.', [
                    'zona' => $zona->descripcion,
                ]) }}
            </p>

            <x-slot name="footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('Cancelar') }}
                </button>

                <form action="{{ route('admin.zonas.destroy', $zona) }}"
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