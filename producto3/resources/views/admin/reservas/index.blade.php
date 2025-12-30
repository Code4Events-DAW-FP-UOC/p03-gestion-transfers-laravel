{{-- resources/views/admin/reservas/index.blade.php --}}
<x-admin-layout>
    {{-- Título + botón "Nuevo" --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Reservas') }}
            </h2>
            <a href="{{ route('admin.reservas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                {{ __('Nueva reserva') }}
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
                                <th>{{ __('Localizador') }}</th>
                                <th>{{ __('Creada por') }}</th>
                                <th>{{ __('Tipo de reserva') }}</th>
                                <th>{{ __('Hotel destino') }}</th>
                                <th>{{ __('Fecha servicio') }}</th>
                                <th>{{ __('Viajeros') }}</th>
                                <th>{{ __('Vehículo') }}</th>
                                <th>{{ __('Estado') }}</th>
                                <th class="text-end">{{ __('Acciones') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reservas as $reserva)
                                @php
                                    // Quién la ha creado
                                    $rolCreador = optional($reserva->creador)->rol;

                                    $creadaPor = match ($rolCreador) {
                                        'viajero' => __('Viajero'),
                                        'hotel'   => __('Hotel'),
                                        'admin'   => __('IslaTransfers'),
                                        default   => __('Desconocido'),
                                    };

                                    // Fechas de servicio
                                    $fechaIda = $reserva->fecha_entrada
                                        ? $reserva->fecha_entrada->format('d/m/Y')
                                        : null;

                                    $fechaVuelta = $reserva->fecha_vuelo_salida
                                        ? $reserva->fecha_vuelo_salida->format('d/m/Y')
                                        : null;

                                    // Estado + permisos
                                    $estado = $reserva->estado;

                                    $canEdit   = in_array($estado, ['pendiente', 'confirmada'], true);
                                    $canCancel = in_array($estado, ['pendiente', 'confirmada'], true);

                                    $badgeClass = match ($estado) {
                                        'pendiente'  => 'bg-warning text-dark',
                                        'confirmada' => 'bg-primary',
                                        'realizada'  => 'bg-success',
                                        'cancelada'  => 'bg-secondary',
                                        default      => 'bg-light text-dark',
                                    };
                                @endphp
                                <tr>
                                    {{-- Localizador --}}
                                    <td><span class="fw-semibold">{{ $reserva->localizador }}</span></td>

                                    {{-- Creada por --}}
                                    <td>{{ $creadaPor }}</td>

                                    {{-- Tipo de reserva --}}
                                    <td>{{ $reserva->tipoReserva->descripcion ?? '—' }}</td>

                                    {{-- Hotel destino --}}
                                    <td>{{ $reserva->hotelDestino->nombre ?? '—' }}</td>

                                    {{-- Fecha servicio --}}
                                    <td>
                                        @if($fechaIda && $fechaVuelta)
                                            <div>
                                                <span class="d-block">
                                                    <strong>{{ __('Ida:') }}</strong> {{ $fechaIda }}
                                                </span>
                                                <span class="d-block">
                                                    <strong>{{ __('Vuelta:') }}</strong> {{ $fechaVuelta }}
                                                </span>
                                            </div>
                                        @elseif($fechaIda)
                                            <strong>{{ __('Ida:') }}</strong> {{ $fechaIda }}
                                        @elseif($fechaVuelta)
                                            <strong>{{ __('Vuelta:') }}</strong> {{ $fechaVuelta }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    {{-- Viajeros --}}
                                    <td>{{ $reserva->num_viajeros }}</td>

                                    {{-- Vehículo --}}
                                    <td>{{ $reserva->vehiculo->descripcion ?? '—' }}</td>

                                    {{-- Estado --}}
                                    <td>
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="text-end">
                                        {{-- Ver detalles en modal --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#adminReservaDetail-{{ $reserva->id_reserva }}"
                                                title="{{ __('Ver detalles') }}">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        {{-- Editar --}}
                                        @if ($canEdit)
                                            <a href="{{ route('admin.reservas.edit', $reserva) }}"
                                               class="btn btn-sm btn-outline-primary me-1"
                                               title="{{ __('Editar') }}">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary me-1" disabled
                                                    title="{{ __('No se puede editar en este estado') }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endif

                                        {{-- Cancelar (modal de confirmación) --}}
                                        @if ($canCancel)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#adminReservaCancel-{{ $reserva->id_reserva }}"
                                                    title="{{ __('Cancelar reserva') }}">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled
                                                    title="{{ __('No se puede cancelar en este estado') }}">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
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
                            'from'  => $reservas->firstItem(),
                            'to'    => $reservas->lastItem(),
                            'total' => $reservas->total(),
                        ]) }}
                    </div>

                    {{ $reservas->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modales de detalle + cancelación --}}
    @foreach($reservas as $reserva)
        {{-- Detalle --}}
        <x-ui.modal
            :id="'adminReservaDetail-' . $reserva->id_reserva"
            :title="__('Detalle de reserva :loc', ['loc' => $reserva->localizador])"
            size="lg"
        >
            @include('admin.reservas.partials.detail', ['reserva' => $reserva])
        </x-ui.modal>

        {{-- Confirmación de cancelación --}}
        <x-ui.modal
            :id="'adminReservaCancel-' . $reserva->id_reserva"
            :title="__('Cancelar reserva :loc', ['loc' => $reserva->localizador])"
            size="sm"
        >
            <p class="mb-3">
                {{ __('¿Seguro que quieres cancelar la reserva ":loc"? Esta acción no eliminará el registro, pero cambiará su estado a "cancelada".', [
                    'loc' => $reserva->localizador,
                ]) }}
            </p>

            <x-slot name="footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('Cerrar') }}
                </button>

                <form action="{{ route('admin.reservas.destroy', $reserva) }}"
                      method="POST"
                      class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-1"></i>
                        {{ __('Cancelar reserva') }}
                    </button>
                </form>
            </x-slot>
        </x-ui.modal>
    @endforeach
</x-admin-layout>