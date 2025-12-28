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
                {{ __('Nuevo reserva') }}
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
                                {{-- Cabeceras genéricas, cambia según la entidad --}}
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
                                    // Fecha(s) de servicio
                                    $fechaIda = $reserva->fecha_entrada
                                        ? $reserva->fecha_entrada->format('d/m/Y')
                                        : null;

                                    $fechaVuelta = $reserva->fecha_vuelo_salida
                                        ? $reserva->fecha_vuelo_salida->format('d/m/Y')
                                        : null;
                                    // Estado + permisos de acciones
                                    $estado = $reserva->estado;

                                    $canEdit   = in_array($estado, ['pendiente', 'confirmada'], true);
                                    $canDelete = in_array($estado, ['pendiente'], true);
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
                                        @php
                                            $badgeClass = match ($estado) {
                                                'pendiente'  => 'bg-warning text-dark',
                                                'confirmada' => 'bg-primary',
                                                'realizada'  => 'bg-success',
                                                'cancelada'  => 'bg-secondary',
                                                default      => 'bg-light text-dark',
                                            };
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </td>
                                    {{-- Acciones --}}
                                    <td class="text-end">
                                        {{-- Ver detalles (siempre disponible) --}}
                                        <a href="{{ route('admin.reservas.show', $reserva) }}"
                                        class="btn btn-sm btn-outline-secondary me-1"
                                        title="{{ __('Ver detalles') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

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

                                        {{-- Borrar / Cancelar --}}
                                        @if ($canDelete)
                                            <form action="{{ route('admin.reservas.destroy', $reserva) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('{{ __('¿Seguro que quieres eliminar esta reserva?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="{{ __('Eliminar') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary" disabled
                                                    title="{{ __('No se puede eliminar en este estado') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
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
</x-admin-layout>