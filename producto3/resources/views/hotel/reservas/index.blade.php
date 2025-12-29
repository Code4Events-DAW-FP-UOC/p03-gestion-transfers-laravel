{{-- resources/views/hotel/reservas/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">
                {{ __('Reservas de mis clientes') }}
            </h2>
            <a href="{{ route('hotel.reservas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                {{ __('Nueva reserva') }}
            </a>
        </div>
    </x-slot>

    @include('layouts.partials.flash-messages')

    <div class="py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Localizador') }}</th>
                                <th>{{ __('Viajero') }}</th>
                                <th>{{ __('Tipo') }}</th>
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
                                    $fechaIda = $reserva->fecha_entrada
                                        ? $reserva->fecha_entrada->format('d/m/Y')
                                        : null;

                                    $fechaVuelta = $reserva->fecha_vuelo_salida
                                        ? $reserva->fecha_vuelo_salida->format('d/m/Y')
                                        : null;

                                    $estado = $reserva->estado;

                                    $badgeClass = match ($estado) {
                                        'pendiente'  => 'bg-warning text-dark',
                                        'confirmada' => 'bg-primary',
                                        'realizada'  => 'bg-success',
                                        'cancelada'  => 'bg-secondary',
                                        default      => 'bg-light text-dark',
                                    };

                                    $canEdit   = in_array($estado, ['pendiente', 'confirmada'], true);
                                    $canDelete = in_array($estado, ['pendiente'], true);
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $reserva->localizador }}</td>
                                    <td>
                                        {{ optional($reserva->viajero)->nombre }}
                                        {{ optional($reserva->viajero)->apellido1 }}
                                    </td>
                                    <td>{{ $reserva->tipoReserva->descripcion ?? '—' }}</td>
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
                                    <td>{{ $reserva->num_viajeros }}</td>
                                    <td>{{ $reserva->vehiculo->descripcion ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $badgeClass }}">
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        {{-- Editar --}}
                                        @if ($canEdit)
                                            <a href="{{ route('hotel.reservas.edit', $reserva) }}"
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

                                        {{-- Cancelar --}}
                                        @if ($canDelete)
                                            <form action="{{ route('hotel.reservas.destroy', $reserva) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('{{ __('¿Seguro que quieres cancelar esta reserva?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="{{ __('Cancelar') }}">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-outline-secondary"
                                                    disabled
                                                    title="{{ __('No se puede cancelar en este estado') }}">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        {{ __('No hay reservas para mostrar.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($reservas->hasPages())
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
                @endif
            </div>
        </div>
    </div>
</x-app-layout>