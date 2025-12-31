{{-- resources/views/admin/hoteles/index.blade.php --}}
<x-admin-layout>
    {{-- Título + botón "Nuevo" --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Hoteles') }}
            </h2>
            <a href="{{ route('admin.hoteles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                {{ __('Nuevo hotel') }}
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                {{-- Filtros --}}
                <form method="GET" action="{{ route('admin.hoteles.index') }}" class="row g-2 mb-3">
                    {{-- Filtrar por nombre --}}
                    <div class="col-12 col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="{{ __('Buscar por nombre') }}"
                                value="{{ old('search', $search) }}"
                            >
                        </div>
                    </div>
                    {{-- Filtrar por zona --}}
                    <div class="col-12 col-md-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">
                                <i class="bi bi-geo-alt"></i>
                            </span>
                            <input
                                type="text"
                                name="zona"
                                class="form-control"
                                placeholder="{{ __('Filtrar por zona') }}"
                                value="{{ old('zona', $zona) }}"
                            >
                        </div>
                    </div>
                    {{-- Filtrar or estado --}}
                    <div class="col-6 col-md-3">
                        <select name="estado" class="form-select form-select-sm">
                            <option value="">{{ __('Todos los estados') }}</option>
                            <option value="1" {{ $estado === '1' ? 'selected' : '' }}>
                                {{ __('Solo activos') }}
                            </option>
                            <option value="0" {{ $estado === '0' ? 'selected' : '' }}>
                                {{ __('Solo inactivos') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3 d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-funnel me-1"></i>
                            {{ __('Filtrar') }}
                        </button>
                        <a href="{{ route('admin.hoteles.index') }}" class="btn btn-sm btn-outline-secondary">
                            {{ __('Limpiar') }}
                        </a>
                    </div>
                </form>
                {{-- Tabla de resultados --}}
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Estado') }}</th>
                                <th>{{ __('Nombre') }}</th>
                                <th>{{ __('Correo electrónico') }}</th>
                                <th>{{ __('Zona') }}</th>
                                <th>{{ __('Comisión') }}</th>
                                <th class="text-end">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hoteles as $hotel)
                                <tr>
                                    <td>
                                        @if($hotel->activo)
                                            <span class="badge bg-success">{{ __('Activo') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Inactivo') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $hotel->nombre }}</td>
                                    <td>{{ $hotel->email }}</td>
                                    <td>{{ optional($hotel->zona)->descripcion ?? '—' }}</td>
                                    <td>{{ $hotel->comision }}  %</td>
                                    <td class="text-end">
                                        <x-buttons.icon-link
                                            :href="route('admin.hoteles.edit', $hotel)"
                                            icon="pencil"
                                            title="{{ __('Editar') }}"
                                            variant="outline-secondary"
                                        />
                                        @php
                                            $desactivado = !$hotel->activo; 
                                        @endphp

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger {{ $desactivado ? 'disabled' : '' }}"
                                            {{ $desactivado ? 'disabled aria-disabled=true' : '' }}
                                            data-bs-toggle="modal"
                                            data-bs-target="#deactivateHotelModal-{{ $hotel->id_hotel }}"
                                            title="{{ $desactivado ? __('Ya está desactivado') : __('Desactivar hotel') }}"
                                        >
                                            <i class="bi bi-slash-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                {{-- Modal de confirmación para ESTE hotel --}}
                                <x-ui.modal
                                    :id="'deactivateHotelModal-' . $hotel->id_hotel"
                                    :title="__('Desactivar hotel')"
                                    size="md"
                                >
                                    <p class="mb-3">
                                        {{ __('¿Seguro que quieres desactivar el hotel ":nombre"?', ['nombre' => $hotel->nombre]) }}
                                    </p>
                                    <p class="text-muted small mb-0">
                                        {{ __('El hotel dejará de poder acceder al sistema y no se podrán crear nuevas reservas, pero se mantendrá el histórico de datos y reservas.') }}
                                    </p>

                                    <x-slot name="footer">
                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">
                                            {{ __('Cancelar') }}
                                        </button>

                                        <form action="{{ route('admin.hoteles.destroy', $hotel) }}"
                                            method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-danger">
                                                <i class="bi bi-slash-circle me-1"></i>
                                                {{ __('Sí, desactivar') }}
                                            </button>
                                        </form>
                                    </x-slot>
                                </x-ui.modal>
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
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        {{ __('Mostrando :from a :to de :total resultados', [
                            'from'  => $hoteles->firstItem(),
                            'to'    => $hoteles->lastItem(),
                            'total' => $hoteles->total(),
                        ]) }}
                    </div>

                    {{ $hoteles->links() }}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>