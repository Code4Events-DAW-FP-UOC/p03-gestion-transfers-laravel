{{-- resources/views/viajero/reservas/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">Mis reservas</h2>
    </x-slot>
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('viajero.reservas.create') }}" class="btn btn-primary">Nueva reserva</a>
    </div>
    <div class="car shadow-sm">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Tipo</th>
                        <th>Hotel</th>
                        <th>Fecha entrada</th>
                        <th>Fecha salida</th>
                        <th>Coste</th>
                        <th>Estado</th>
                        <th>Creada por</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservas as $reserva)
                        @php
                            // Evitar el error de "call to member function format() on null"
                            $fechaEntrada = $reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') : '-';
                            $fechaSalida = $reserva->fecha_vuelo_salida ? \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') : '-';
                        @endphp
                        <tr>
                            <td>{{ $reserva->localizador }}</td>
                            <td>{{ $reserva->tipoReserva->descripcion ?? '-' }}</td>
                            <td>{{ $reserva->hotelDestino->nombre ?? '-' }}</td>
                            <td>{{ $fechaEntrada }}</td>
                            <td>{{ $fechaSalida  }}</td>
                            <td>@if (!is_null($reserva->importe))
                                {{ number_format($reserva->importe, 2, ',', '.') }} €
                            @else
                                    -
                                @endif
                            </td>
                            <td>{{  ucfirst($reserva->estado) }}</td>
                            <td>
                                @if ($reserva->creador)
                                    {{ $reserva->creador_label }}
                                @else
                                    -
                                @endif
                            </td>
                            @php
                                $reservaBloqueada = in_array($reserva->estado, ['cancelada', 'realizada'], true);
                            @endphp

                            <td class="text-end">
                                {{-- Ver (siempre disponible) --}}
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-accion-reserva"
                                    data-bs-toggle="modal" data-bs-target="#modal-detalle-{{ $reserva->id_reserva }}"
                                    title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                    <span class="visually-hidden">Ver detalles</span>
                                </button>

                                @if ($reservaBloqueada)
                                                    {{-- Editar deshabilitado --}}
                                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2 btn-accion-reserva"
                                                        disabled aria-disabled="true" title="{{ $reserva->estado === 'realizada'
                                    ? 'Reserva realizada (no editable)'
                                    : 'Reserva cancelada (no editable)' }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                        <span class="visually-hidden">Editar reserva</span>
                                                    </button>

                                                    {{-- Cancelar deshabilitado --}}
                                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2 btn-accion-reserva"
                                                        disabled aria-disabled="true" title="{{ $reserva->estado === 'realizada'
                                    ? 'Reserva realizada (no se puede cancelar)'
                                    : 'Reserva ya cancelada' }}">
                                                        <i class="bi bi-x-circle"></i>
                                                        <span class="visually-hidden">Cancelar reserva</span>
                                                    </button>
                                @else
                                    {{-- Editar activa --}}
                                    <a href="{{ route('viajero.reservas.edit', $reserva) }}"
                                        class="btn btn-sm btn-outline-primary ms-2 btn-accion-reserva" title="Editar reserva">
                                        <i class="bi bi-pencil-square"></i>
                                        <span class="visually-hidden">Editar reserva</span>
                                    </a>

                                    {{-- Cancelar activa (abre modal) --}}
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2 btn-accion-reserva"
                                        data-bs-toggle="modal" data-bs-target="#modal-cancelar-{{ $reserva->id_reserva }}"
                                        title="Cancelar reserva">
                                        <i class="bi bi-trash"></i>
                                        <span class="visually-hidden">Cancelar reserva</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-3">No tienes reservas todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($reservas instanceof \Illuminate\Pagination\AbstractPaginator)
                <div class="card-footer">{{ $reservas->links() }}</div>
            @endif
        </div>
</x-app-layout>

{{-- Modales de detalle de cada reserva y cancelar --}}
@foreach ($reservas as $reserva)
    <x-modal name="modal-detalle-{{ $reserva->id_reserva }}">
        <div class="modal-header">
            <h5 class="modal-title">
                Reserva {{ $reserva->localizador ?? '—' }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            {{-- Bloque resumen principal --}}
            <div class="mb-3">
                <span class="badge bg-primary">
                    {{ $reserva->tipoReserva->descripcion ?? 'Tipo no definido' }}
                </span>

                @if($reserva->estado)
                    <span
                        class="badge @if($reserva->estado === 'confirmada') bg-success @elseif($reserva->estado === 'cancelada') bg-danger @else bg-secondary @endif">{{ ucfirst($reserva->estado) }}</span>
                @endif
            </div>

            {{-- Datos en forma de definición --}}
            <dl class="row mb-0">
                <dt class="col-sm-4">Localizador</dt>
                <dd class="col-sm-8">{{ $reserva->localizador ?? '—' }}</dd>

                <dt class="col-sm-4">Creada por</dt>
                <dd class="col-sm-8">
                    @if($reserva->creador)
                        {{ $reserva->creador->name }}
                        <span class="text-muted">({{ $reserva->creador->rol }})</span>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-4 mt-3">Hotel de destino</dt>
                <dd class="col-sm-8 mt-3">
                    {{ $reserva->hotelDestino->nombre ?? $reserva->hotelGestor->nombre ?? '—' }}
                </dd>

                <dt class="col-sm-4">Fecha reserva</dt>
                <dd class="col-sm-8">
                    {{ optional($reserva->fecha_reserva)->format('d/m/Y H:i') ?? '—' }}
                </dd>

                {{-- BLOQUE IDA (si tiene datos de ida) --}}
                @if($reserva->fecha_entrada || $reserva->hora_entrada)
                    <dt class="col-sm-12 mt-3 mb-3">
                        <strong>Tramo de ida (aeropuerto → hotel)</strong>
                    </dt>

                    <dt class="col-sm-4">Fecha llegada</dt>
                    <dd class="col-sm-8">
                        {{ optional($reserva->fecha_entrada)->format('d/m/Y') ?? '—' }}
                        @if($reserva->hora_entrada)
                            a las {{ \Carbon\Carbon::parse($reserva->hora_entrada)->format('H:i') }}
                        @endif
                    </dd>

                    <dt class="col-sm-4">Vuelo llegada</dt>
                    <dd class="col-sm-8">
                        {{ $reserva->numero_vuelo_entrada ?? '—' }}
                        @if($reserva->origen_vuelo_entrada)
                            <span class="text-muted"> (origen: {{ $reserva->origen_vuelo_entrada }})</span>
                        @endif
                    </dd>
                @endif

                {{-- BLOQUE VUELTA (si tiene datos de vuelta) --}}
                @if($reserva->fecha_vuelo_salida || $reserva->hora_vuelo_salida)
                    <dt class="col-sm-12 mt-3 mb-3">
                        <strong>Tramo de vuelta (hotel → aeropuerto)</strong>
                    </dt>

                    <dt class="col-sm-4">Fecha salida</dt>
                    <dd class="col-sm-8">
                        {{ optional($reserva->fecha_vuelo_salida)->format('d/m/Y') ?? '—' }}
                        @if($reserva->hora_vuelo_salida)
                            a las {{ \Carbon\Carbon::parse($reserva->hora_vuelo_salida)->format('H:i') }}
                        @endif
                    </dd>

                    <dt class="col-sm-4">Vuelo salida</dt>
                    <dd class="col-sm-8">
                        {{ $reserva->numero_vuelo_salida ?? '—' }}
                        @if($reserva->destino_vuelo_salida)
                            <span class="text-muted"> (destino: {{ $reserva->destino_vuelo_salida }})</span>
                        @endif
                    </dd>
                @endif

                {{-- BLOQUE COMÚN --}}
                <dt class="col-sm-5 mt-3">Número de viajeros</dt>
                <dd class="col-sm-7 mt-3">{{ $reserva->num_viajeros }}</dd>

                <dt class="col-sm-5">Vehículo</dt>
                <dd class="col-sm-7">
                    @if($reserva->vehiculo)
                        {{ $reserva->vehiculo->descripcion }}
                        <span class="text-muted">
                            ({{ $reserva->vehiculo->plazas }} plazas)
                        </span>
                    @else
                        —
                    @endif
                </dd>

                <dt class="col-sm-4 mt-3">Coste</dt>
                <dd class="col-sm-8 mt-3">
                    @if(!is_null($reserva->importe ?? null))
                        {{ number_format($reserva->importe, 2, ',', '.') }} €
                    @else
                        —
                    @endif
                </dd>
            </dl>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Cerrar
            </button>
            {{-- Aquí podrías añadir un botón "Editar" o "Cancelar reserva" si procede --}}
        </div>
    </x-modal>

    {{-- Modal de confirmación de cancelación (SIN contraseña) --}}
    <x-modal name="modal-cancelar-{{ $reserva->id_reserva }}" size="modal-lg">
        <form method="post" action="{{ route('viajero.reservas.destroy', $reserva) }}">
            @csrf
            @method('delete')

            <div class="modal-header">
                <h5 class="modal-title">
                    {{ __('Confirmar cancelación de la reserva') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Cerrar') }}"></button>
            </div>

            <div class="modal-body">
                <p class="mb-3">
                    Vas a cancelar la reserva
                    <strong>{{ $reserva->localizador }}</strong>
                    del hotel
                    <strong>{{ $reserva->hotelDestino->nombre ?? '-' }}</strong>.
                </p>

                <p class="small text-muted mb-0">
                    Esta acción no eliminará la reserva del historial, pero
                    su estado pasará a <strong>“cancelada”</strong> y ya no
                    se prestará el servicio.
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('Volver') }}
                </button>

                <x-danger-button>
                    {{ __('Confirmar cancelación') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
@endforeach